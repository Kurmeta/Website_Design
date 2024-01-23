<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/styles.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">Coding Legend Courses</a>
            <button class="navbar-toggler custom-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
        <div id="loadingContainer">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Processing payment...</span>
            </div>
            <p class="mt-2">Processing payment...</p>
        </div>

        <div id="contactingContainer" style="display: none;">
            <div class="spinner-border text-warning" role="status">
                <span class="sr-only">Contacting Payment Provider...</span>
            </div>
            <p class="mt-2">Contacting Payment Provider...</p>
        </div>

        <div id="successContainer" style="display: none;">
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">Payment Success!</h4>
                <p>Your payment was successful. Thank you!</p>
                <hr>
                <a href="payment.php" class="btn btn-success">Return to Payment Page</a>
            </div>
        </div>
    </div>

    <script>
        // Simulate a delay for each stage
        setTimeout(function () {
            document.getElementById('loadingContainer').style.display = 'none';
            document.getElementById('contactingContainer').style.display = 'block';
        }, 3000);

        setTimeout(function () {
            document.getElementById('contactingContainer').style.display = 'none';
            document.getElementById('successContainer').style.display = 'block';
        }, 3000);
    </script>

</body>

</html>
