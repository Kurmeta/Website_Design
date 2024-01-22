<?php
$hostname = 'localhost';
$username = 'root';
$password = 'root';
$database = 'quiz_db';

$mysqli = new mysqli($hostname, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$result = $mysqli->query("SELECT * FROM quiz");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Page</title>

    <link rel="stylesheet" href="css/styles.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Your custom CSS file (if you have one) -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <a class="navbar-brand" href="#">CodeCraft Tutorials</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                <a class="nav-link" href="payment.php">Payment</a>
            </li>
        </ul>
    </div>
</nav>

<!-- Quiz Section -->
<section id="quiz" class="container mt-5">
    <h2>Quiz Section</h2>
    <p>Test your knowledge with our range of quizzes covering various computing concepts.</p>

    <!-- Display quiz questions from the database -->
   <div class="quiz-container">
    <?php while ($row = $result->fetch_assoc()) : ?>
        <div class="quiz-question">
            <p><?php echo $row['question']; ?></p>
            <ul>
                <li><?php echo $row['option_a']; ?></li>
                <li><?php echo $row['option_b']; ?></li>
                <li><?php echo $row['option_c']; ?></li>
            </ul>
            <!-- Add form for user to submit their answer -->
            <form action="process_quiz.php" method="post">
                <input type="hidden" name="question_id" value="<?php echo $row['id']; ?>">
                <label for="user_answer">Your Answer:</label>
                <input type="text" name="user_answer" required>
                <button type="submit">Submit Answer</button>
            </form>
        </div>
    <?php endwhile; ?>
</div>

</section>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

</body>
</html>

<?php
// Close the database connection
$mysqli->close();
?>
