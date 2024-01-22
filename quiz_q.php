<?php
$hostname = 'localhost';
$username = 'root';
$password = 'root';
$database = 'quiz_db';

$mysqli = new mysqli($hostname, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$selectedCategory = isset($_GET['category']) ? $mysqli->real_escape_string($_GET['category']) : null;

if ($selectedCategory) {
    $query = "SELECT * FROM quiz WHERE category = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $selectedCategory);
    $stmt->execute();
    $questionsResult = $stmt->get_result();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $correctAnswers = 0;
    $userResponses = array(); // To store user responses

    // Rewind the result set pointer
    $questionsResult->data_seek(0);

    while ($question = $questionsResult->fetch_assoc()) {
        $questionTitle = $question['question'];
        $userAnswer = isset($_POST['answer_' . $question['id']]) ? $_POST['answer_' . $question['id']] : '';

        $userResponses[$questionTitle] = array(
            'userAnswer' => $question['option_' . strtolower($userAnswer)],
            'correctAnswer' => $question['option_' . strtolower($question['correct_option'])],
            'reason' => $question['reason']
        ); // Store user response with question title, correct answer, and reason

        if ($userAnswer === $question['correct_option']) {
            $correctAnswers++;
        }
    }

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Questions</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
    
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">Coding Legend Courses</a>
        <button class="navbar-toggler custom-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
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
                    <a class="nav-link" href="payment.php">Payment</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<section id="quiz-questions" class="container mt-5">
    <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST') : ?>
        <h2 class="quiz-title"><?php echo htmlspecialchars($selectedCategory); ?> Quiz</h2>
    <?php endif; ?>

    <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST') : ?>
        <div class="quiz-container">
            <form action="quiz_q.php?category=<?php echo urlencode($selectedCategory); ?>" method="post">
                <?php $questionsResult->data_seek(0); // Rewind result set pointer ?>
                <?php while ($question = $questionsResult->fetch_assoc()) : ?>
                    <div class="jumbotron">
                        <h4 class="display-5"><?php echo htmlspecialchars($question['question']); ?></h4>
                        <?php foreach (['a', 'b', 'c'] as $option) : ?>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="answer_<?php echo $question['id']; ?>" value="<?php echo $option; ?>" id="option<?php echo $option; ?>_<?php echo $question['id']; ?>" required>
                                <label class="form-check-label" for="option<?php echo $option; ?>_<?php echo $question['id']; ?>"><?php echo htmlspecialchars($question['option_' . strtolower($option)]); ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endwhile; ?>

                <div class="jumbotron text-center">
                    <label for="user_name">Would you Like to submit your results, If so Enter your name below:</label>
                    <input type="text" class="form-control" id="user_name" name="user_name" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-custom" name="submit">Submit Answers</button>
                </div>
            </form>
        </div>
    <?php else : ?>
        <div class="jumbotron text-center">
            <h4 class="display-4 quiz-result-title">Quiz Result</h4>
            <p class="lead">Correct Answers: <?php echo $correctAnswers; ?></p>
            <p class="lead">Total Questions: <?php echo $questionsResult->num_rows; ?></p>
            <p class="lead">Percentage: <?php echo number_format($resultPercentage, 2); ?>%</p>
        </div>
        <h5 class="mt-4 text-center">Your Responses:</h5>
        <?php foreach ($userResponses as $questionTitle => $responses) : ?>
            <div class="jumbotron text-center">
            <p><?php echo "{$questionTitle} <br><br> Your Answer - {$responses['userAnswer']} <br> Correct Answer - {$responses['correctAnswer']} <br><br> Reason - {$responses['reason']}"; ?></p>
            </div>
        <?php endforeach; ?>
        <div class="text-center">   
            <a href="quiz.php" class="btn btn-secondary btn-custom mt-4">Return</a>
        </div>

<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>