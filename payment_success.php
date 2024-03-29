<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Set the title of the page -->
    <title>Payment Success</title>
    <!-- Link to Bootstrap CSS for styling -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Link to custom CSS file for additional styling -->
    <link rel="stylesheet" href="./css/styles.css">
</head>

<body>

    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <!-- Brand/logo -->
            <a class="navbar-brand" href="#">Coding Legend Courses</a>
            <!-- Toggle button for small screens -->
            <button class="navbar-toggler custom-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Navigation links -->
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
        <!-- Loading container -->
        <div id="loadingContainer">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Processing payment...</span>
            </div>
            <p class="mt-2">Processing payment...</p>
        </div>

        <!-- Contacting container (initially hidden) -->
        <div id="contactingContainer" style="display: none;">
            <div class="spinner-border text-warning" role="status">
                <span class="sr-only">Contacting Payment Provider...</span>
            </div>
            <p class="mt-2">Contacting Payment Provider...</p>
        </div>

        <!-- Success container (initially hidden) -->
        <div id="successContainer" style="display: none;">
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">Payment Success!</h4>
                <p>Your payment was successful. Thank you!</p>
                <hr>
                <a href="payment.php" class="btn btn-success">Return to Payment Page</a>
            </div>
        </div>
    </div>

    <!-- JavaScript to simulate a delay for each stage of payment processing -->
    <script>
        // Simulate a delay for the contacting stage
        setTimeout(function () {
            document.getElementById('loadingContainer').style.display = 'none';
            document.getElementById('contactingContainer').style.display = 'block';
        }, 3000);
        // Simulate a delay for the contacting stage
        setTimeout(function () {
            document.getElementById('contactingContainer').style.display = 'none';
            document.getElementById('successContainer').style.display = 'block';
        }, 3000);
    </script>

</body>

</html>
