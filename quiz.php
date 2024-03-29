<?php
// Database connection parameters
$hostname = 'localhost';
$username = 'root';
$password = 'root';
$database = 'quiz_db';

// Create a new MySQLi connection
$mysqli = new mysqli($hostname, $username, $password, $database);

// Check for connection errors
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch unique categories for the dropdown menu
$categoryResult = $mysqli->query("SELECT DISTINCT category FROM quiz");
$categories = $categoryResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Page</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg">
            <div class="container">
                <!-- Brand Logo and Toggle Button -->
                <a class="navbar-brand" href="#">Coding Legend Courses</a>
                <button class="navbar-toggler custom-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <!-- Navigation Links -->
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
                            <a class="nav-link" href="Payment.php">Payment</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="search.php">Search</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

<!-- Quiz Listing Section -->
<section id="quiz-listing" class="container mt-5">
    <!-- Display quizzes as cards with three cards per row -->
    <div class="row">
        <?php foreach ($categories as $category) : ?>
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <!-- Display category name as the quiz title -->
                        <h5 class="card-title"><?php echo htmlspecialchars($category['category']); ?> Quiz</h5>
                        <!-- Link to the quiz page with the selected category -->
                        <a href="quiz_q.php?category=<?php echo urlencode($category['category']); ?>" class="btn btn-primary btn-custom">Start Quiz</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Bootstrap and jQuery scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
