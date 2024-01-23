<?php
$hostname = 'localhost';
$username = 'root';
$password = 'root';
$database = 'quiz_db';

$mysqli = new mysqli($hostname, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['results'])) {
    // Collect the username
    $searchUsername = isset($_POST['search_username']) ? $mysqli->real_escape_string($_POST['search_username']) : '';

    // Query to retrieve the last 10 results for the specified username
    $query = "SELECT * FROM quiz_results WHERE user_name = ? ORDER BY timestamp DESC LIMIT 10";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $searchUsername);
    $stmt->execute();
    $results = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retrieve Quiz Results</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css\styles.css">
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
                    <a class="nav-link" href="Payment.php">Payment</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="search.php">Search</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
    <div class="container mt-5 text-center">
        <form action="results.php" method="post">
            <div class="form-group">
                <div class="jumbotron">
                    <label for="search_username">Enter Username:</label>
                    <input type="text" class="form-control" id="search_username" name="search_username" required>
                </div>
            </div>
            <button type="submit" class="btn btn-custom" name="results">Retrieve Results</button>
        </form>

        <?php if (isset($results)) : ?>
            <h2 class="mt-4">Last 10 Quiz Results for <?php echo htmlspecialchars($searchUsername); ?></h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Correct Answers</th>
                            <th>Percentage</th>
                            <!-- Add more columns if needed -->
                        </tr>
                    </thead>
                <tbody>
            <?php while ($row = $results->fetch_assoc()) : ?>
                <tr>
                    <!-- Format the timestamp using the date function -->
                    <td><?php echo date('Y-m-d H:i:s', strtotime($row['timestamp'])); ?></td>
                    <td><?php echo htmlspecialchars($row['quiz_category']); ?></td>
                    <td><?php echo htmlspecialchars($row['correct_answers']); ?></td>
                    <td><?php echo number_format($row['percentage']) . '%'; ?></td>
                    <!-- Add more columns if needed -->
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
