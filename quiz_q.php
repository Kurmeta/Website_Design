<?php
$hostname = 'localhost';
$username = 'root';
$password = 'root';
$database = 'quiz_db';

$mysqli = new mysqli($hostname, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if a category is selected
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : null;

// If a category is selected, fetch questions from that category
if ($selectedCategory) {
    $questionsResult = $mysqli->query("SELECT * FROM quiz WHERE category = '$selectedCategory'");
}

// Initialize variables for result calculation
$totalQuestions = $questionsResult->num_rows;
$correctAnswers = 0;

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize variables for result calculation
    $totalQuestions = $questionsResult->num_rows;
    $correctAnswers = 0;

    // Loop through submitted answers and check correctness
    while ($question = $questionsResult->fetch_assoc()) {
        $questionId = $question['id'];
        $userAnswer = isset($_POST['answer_' . $questionId]) ? $_POST['answer_' . $questionId] : '';

        // Check if the user's answer is correct
        if ($userAnswer === $question['correct_option']) {
            $correctAnswers++;
        }
    }

    // Calculate the result percentage
    $resultPercentage = ($correctAnswers / $totalQuestions) * 100;
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
    <!-- Your custom CSS file (if you have one) -->
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">CodeCraft Tutorials</a>
        <button class="navbar-toggler custom-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="booking.php">Booking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="quiz.php">Quiz</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="payment.php">Payment</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Quiz Questions Section -->
<section id="quiz-questions" class="container mt-5">
    <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST') : ?>
        <!-- Display quiz title if the form is not submitted -->
        <h2 class="quiz-title"><?php echo htmlspecialchars($selectedCategory); ?> Quiz</h2>
    <?php endif; ?>

    <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST') : ?>
        <!-- Display quiz questions with Bootstrap radios -->
        <div class="quiz-container">
            <form action="quiz_q.php?category=<?php echo urlencode($selectedCategory); ?>" method="post">
                <?php while ($question = $questionsResult->fetch_assoc()) : ?>
                    <div class="jumbotron">
                        <h4 class="display-5"><?php echo htmlspecialchars($question['question']); ?></h4>
                        <?php foreach (['A', 'B', 'C'] as $option) : ?>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="answer_<?php echo $question['id']; ?>" value="<?php echo $option; ?>" id="option<?php echo $option; ?>_<?php echo $question['id']; ?>" required>
                                <label class="form-check-label" for="option<?php echo $option; ?>_<?php echo $question['id']; ?>"><?php echo htmlspecialchars($question['option_' . strtolower($option)]); ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endwhile; ?>
                <button type="submit" class="btn btn-primary btn-custom">Submit Answers</button>
            </form>
        </div>
    <?php else : ?>
        <!-- Display quiz result -->
        <div class="jumbotron text-center">
            <h4 class="display-4 quiz-result-title">Quiz Result</h4>
            <p class="lead">Correct Answers: <?php echo $correctAnswers; ?></p>
            <p class="lead">Total Questions: <?php echo $totalQuestions; ?></p>
            <p class="lead">Percentage: <?php echo number_format($resultPercentage, 2); ?>%</p>
            <!-- Return button to go back to quiz category selection -->
            <a href="quiz.php" class="btn btn-secondary btn-custom">Return</a>
        </div>
    <?php endif; ?>
</section>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>