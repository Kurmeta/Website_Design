<?php
// Database connection parameters
$hostname = 'localhost';
$username = 'root';
$password = 'root';
$database = 'quiz_db';

// Create a new MySQLi object for database connection
$mysqli = new mysqli($hostname, $username, $password, $database);

// Check for database connection errors
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get selected quiz category from the URL
$selectedCategory = isset($_GET['category']) ? $mysqli->real_escape_string($_GET['category']) : null;

// Retrieve quiz questions based on the selected category
if ($selectedCategory) {
    $query = "SELECT * FROM quiz WHERE category = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $selectedCategory);
    $stmt->execute();
    $questionsResult = $stmt->get_result();
}

// Check if the form is submitted and process user responses
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $correctAnswers = 0;
    $userResponses = array(); // To store user responses

    // Rewind the result set pointer
    $questionsResult->data_seek(0);

    // Loop through each quiz question and compare user answers
    while ($question = $questionsResult->fetch_assoc()) {
        $questionTitle = $question['question'];
        $userAnswer = isset($_POST['answer_' . $question['id']]) ? $_POST['answer_' . $question['id']] : '';

        // Store user response with question title, correct answer, and reason
        $userResponses[$questionTitle] = array(
            'userAnswer' => $question['option_' . strtolower($userAnswer)],
            'correctAnswer' => $question['option_' . strtolower($question['correct_option'])],
            'reason' => $question['reason']
        );

        // Check if user's answer is correct
        if ($userAnswer === $question['correct_option']) {
            $correctAnswers++;
        }
    }

    // Calculate the percentage of correct answers
    $resultPercentage = ($correctAnswers / $questionsResult->num_rows) * 100;

    // Collect user's name
    $userName = isset($_POST['user_name']) ? $mysqli->real_escape_string($_POST['user_name']) : 'Anonymous';

    // Save quiz results to the database
    $insertQuery = "INSERT INTO quiz_results (user_name, quiz_category, correct_answers, total_questions, percentage, user_responses) VALUES (?, ?, ?, ?, ?, ?)";
    $insertStmt = $mysqli->prepare($insertQuery);
    $insertStmt->bind_param("ssiiis", $userName, $selectedCategory, $correctAnswers, $questionsResult->num_rows, $resultPercentage, json_encode($userResponses));
    $insertStmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Questions</title>

    <!-- Bootstrap and custom styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
    
<!-- Navigation bar -->
<nav class="navbar navbar-expand-lg">
    <!-- Navigation content -->
    <div class="container">
        <a class="navbar-brand" href="#">Coding Legend Courses</a>
        <!-- Toggle button for mobile view -->
        <button class="navbar-toggler custom-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Navigation links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <!-- Navigation items -->
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="booking.php">Booking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="quiz.php">Quiz</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="results.php">Results</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Payment.php">Payment</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="search.php">Search</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Quiz questions section -->
<section id="quiz-questions" class="container mt-5">
    <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST') : ?>
        <!-- Display quiz title if the form is not submitted -->
        <h2 class="quiz-title"><?php echo htmlspecialchars($selectedCategory); ?> Quiz</h2>
    <?php endif; ?>

    <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST') : ?>
        <!-- Display quiz questions and form if the form is not submitted -->
        <div class="quiz-container">
            <form action="quiz_q.php?category=<?php echo urlencode($selectedCategory); ?>" method="post">
                <?php $questionsResult->data_seek(0); // Rewind result set pointer ?>
                <?php while ($question = $questionsResult->fetch_assoc()) : ?>
                    <div class="jumbotron">
                        <!-- Display each quiz question and options -->
                        <h4 class="display-5"><?php echo htmlspecialchars($question['question']); ?></h4>
                        <?php foreach (['a', 'b', 'c'] as $option) : ?>
                            <div class="form-check">
                                <!-- Radio button for each option -->
                                <input type="radio" class="form-check-input" name="answer_<?php echo $question['id']; ?>" value="<?php echo $option; ?>" id="option<?php echo $option; ?>_<?php echo $question['id']; ?>" required>
                                <label class="form-check-label" for="option<?php echo $option; ?>_<?php echo $question['id']; ?>"><?php echo htmlspecialchars($question['option_' . strtolower($option)]); ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endwhile; ?>

                <div class="jumbotron text-center">
                    <!-- User name input field -->
                    <label for="user_name">Would you Like to submit your results, If so Enter your name below:</label>
                    <input type="text" class="form-control" id="user_name" name="user_name" required>
                </div>
                <div class="text-center">
                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary btn-custom" name="submit">Submit Answers</button>
                </div>
            </form>
        </div>
    <?php else : ?>
        <!-- Display quiz result and user responses if the form is submitted -->
        <div class="jumbotron text-center">
            <h4 class="display-4 quiz-result-title">Quiz Result</h4>
            <p class="lead">Correct Answers: <?php echo $correctAnswers; ?></p>
            <p class="lead">Total Questions: <?php echo $questionsResult->num_rows; ?></p>
            <p class="lead">Percentage: <?php echo number_format($resultPercentage, 2); ?>%</p>
        </div>
        <h5 class="mt-4 text-center">Your Responses:</h5>
        <?php foreach ($userResponses as $questionTitle => $responses) : ?>
            <div class="jumbotron text-center">
                <!-- Display user responses for each question -->
                <p><?php echo "{$questionTitle} <br><br> Your Answer - {$responses['userAnswer']} <br> Correct Answer - {$responses['correctAnswer']} <br><br> Reason - {$responses['reason']}"; ?></p>
            </div>
        <?php endforeach; ?>
        <div class="text-center">   
            <!-- Return button -->
            <a href="quiz.php" class="btn btn-secondary btn-custom mt-4">Return</a>
        </div>
    <?php endif; ?>

    <!-- JavaScript libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
