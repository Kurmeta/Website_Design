<?php
// Configuration for the database connection
$hostname = 'localhost';
$username = 'root';
$password = 'root';
$database = 'quiz_db';

// Create a new mysqli instance for database connection
$mysqli = new mysqli($hostname, $username, $password, $database);

// Check if the database connection is successful
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if the form is submitted using POST and if the 'results' parameter is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['results'])) {
    // Collect and sanitize the username from the form
    $searchUsername = isset($_POST['search_username']) ? $mysqli->real_escape_string($_POST['search_username']) : '';

    // Query to retrieve the last 10 results for the specified username
    $query = "SELECT * FROM quiz_results WHERE user_name = ? ORDER BY timestamp DESC LIMIT 10";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $searchUsername);
    $stmt->execute();

    // Get the result set
    $results = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results</title>

    <!-- Link to Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Link to custom CSS file for additional styling -->
    <link rel="stylesheet" href="css\styles.css">
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

    <!-- Content container -->
    <div class="container mt-5 text-center">
        <!-- Form to enter username and retrieve results -->
        <form action="results.php" method="post">
            <div class="form-group">
                <div class="jumbotron">
                    <label for="search_username">Enter Username:</label>
                    <input type="text" class="form-control" id="search_username" name="search_username" required>
                </div>
            </div>
            <button type="submit" class="btn btn-custom" name="results">Retrieve Results</button>
        </form>

        <!-- Display quiz results if available -->
        <?php if (isset($results)) : ?>
            <h2 class="mt-4">Last 10 Quiz Results for <?php echo htmlspecialchars($searchUsername); ?></h2>
            <!-- Table to display quiz results -->
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Correct Answers</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through each quiz result and display in table rows -->
                    <?php while ($row = $results->fetch_assoc()) : ?>
                        <tr>
                            <!-- Format the timestamp using the date function -->
                            <td><?php echo date('Y-m-d H:i:s', strtotime($row['timestamp'])); ?></td>
                            <td><?php echo htmlspecialchars($row['quiz_category']); ?></td>
                            <td><?php echo htmlspecialchars($row['correct_answers']); ?></td>
                            <td><?php echo number_format($row['percentage']) . '%'; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- JavaScript and Bootstrap dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
