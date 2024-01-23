<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Set character set and viewport for responsive design -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>

    <!-- Include Bootstrap CSS for styling -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- Include custom styles -->
    <link rel="stylesheet" href="./css/styles.css">
</head>

<body>

    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">Coding Legend Courses</a>
            <!-- Toggle button for mobile view -->
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

    <!-- Form for retrieving unpaid bookings -->
    <div class="container mt-5 text-center">
        <form action="payment.php" method="post">
            <div class="form-group">
                <!-- Input field for entering the name to retrieve unpaid bookings -->
                <div class="jumbotron">
                    <label for="search_username">Enter Name to Retrieve Unpaid Bookings:</label>
                    <input type="text" class="form-control" id="search_username" name="search_username" required>
                </div>
            </div>
            <!-- Button to submit the form and retrieve bookings -->
            <button type="submit" class="btn btn-custom" name="results">Retrieve Bookings</button>
        </form>
    </div>

    <!-- PHP code for retrieving and displaying unpaid bookings -->
    <div class="container mt-5">
        <?php
        // Database connection parameters
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "bookings_db";

        // Create a new MySQLi connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Initialize variables
        $paymentMessage = "";
        $userName = "";

        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["search_username"])) {
                // Get the entered username
                $userName = $_POST["search_username"];
                // Query to retrieve unpaid bookings for the specified username
                $sql = "SELECT * FROM bookings WHERE userName = '$userName' AND paid = 'no'";
                $result = $conn->query($sql);

                // Check if there are unpaid bookings
                if ($result && $result->num_rows > 0) {
                    // Generate HTML for displaying unpaid bookings in a table
                    $paymentMessage = "<h2>Unpaid Bookings for $userName</h2>";
                    $paymentMessage .= '<div class="table-responsive">';
                    $paymentMessage .= '<table class="table table-bordered">';
                    $paymentMessage .= '<thead><tr><th>Course</th><th>Date</th><th>Time</th><th>Cost</th></tr></thead>';
                    $paymentMessage .= '<tbody>';
                    while ($row = $result->fetch_assoc()) {
                        $paymentMessage .= "<tr><td>{$row['selectedCourse']}</td><td>{$row['selectedDate']}</td><td>{$row['selectedTime']}</td><td>{$row['courseCost']}</td></tr>";
                    }
                    $paymentMessage .= '</tbody></table></div>';
                } else {
                    // Display a message if no unpaid bookings are found
                    $paymentMessage = "<p>No unpaid bookings found for the given name.</p>";
                }
            }
        }
        ?>
        <!-- Display the payment message (unpaid bookings or no bookings) -->
        <?php echo $paymentMessage; ?>

        <!-- Display payment options if there are unpaid bookings -->
        <?php if (isset($result) && $result->num_rows > 0 && !isset($_POST["processPayment"])) : ?>
            <!-- Payment options section -->
            <h2>Make Payment</h2>

            <div class="row">
                <!-- Card payment option -->
                <div class="col-md-6">
                    <div class="card">
                        <img src="./img/credit_card_image.jpg" class="card-img-top" alt="Credit/Debit Card">
                        <div class="card-body">
                            <h5 class="card-title">Credit/Debit Card</h5>
                            <p class="card-text">Securely pay with your credit or debit card.</p>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cardPaymentModal">
                                Pay with Card
                            </button>
                        </div>
                    </div>
                </div>
                <!-- PayPal payment option -->
                <div class="col-md-6">
                    <div class="card">
                        <img src="./img/paypal_image.jpg" class="card-img-top" alt="Credit/Debit Card">
                        <div class="card-body">
                            <h5 class="card-title">PayPal</h5>
                            <p class="card-text">Securely pay with PayPal.</p>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#PaypalPaymentModal">
                                Pay with Paypal
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Payment Modal -->
            <div class="modal fade" id="cardPaymentModal" tabindex="-1" role="dialog" aria-labelledby="cardPaymentModalLabel" aria-hidden="true">
                <!-- Modal content for card payment -->
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cardPaymentModalLabel">Card Payment</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Form for card payment details -->
                            <form method="post" action="process_payment.php">
                                <p>You are paying for <?php echo $result->num_rows; ?> course(s) for <?php echo $userName; ?></p>

                                <!-- Input fields for card details -->
                                <div class="form-group">
                                    <label for="name">Name On Card:</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="cardNumber">Card Number:</label>
                                    <input type="text" class="form-control" id="cardNumber" name="cardNumber" required>
                                </div>
                                <div class="form-group">
                                    <label for="expirydate">Expiry Date:</label>
                                    <input type="text" class="form-control" id="expirydate" name="expirydate" placeholder="MM/YYYY" required>
                                </div>
                                <div class="form-group">
                                    <label for="CVC">CVC:</label>
                                    <input type="text" class="form-control" id="CVC" name="CVC" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>

                                <!-- Hidden fields for form submission -->
                                <input type="hidden" name="userName" value="<?php echo $userName; ?>">
                                <input type="hidden" name="courseCost" value="<?php echo $courseCost; ?>">
                                <button type="submit" class="btn btn-primary" name="processPayment">Submit Payment</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paypal Payment Modal -->
            <div class="modal fade" id="PaypalPaymentModal" tabindex="-1" role="dialog" aria-labelledby="PaypalPaymentModalLabel" aria-hidden="true">
                <!-- Modal content for PayPal payment -->
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="PaypalPaymentModalLabel">Card Payment</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Form for PayPal payment details -->
                            <form method="post" action="process_payment.php">
                                <p>You are paying for <?php echo $result->num_rows; ?> course(s) for <?php echo $userName; ?></p>

                                <!-- Input fields for PayPal details -->
                                <div class="form-group">
                                    <label for="email">Paypal Email:</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password:</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>

                                <!-- Hidden fields for form submission -->
                                <input type="hidden" name="userName" value="<?php echo $userName; ?>">
                                <input type="hidden" name="courseCost" value="<?php echo $courseCost; ?>">
                                <button type="submit" class="btn btn-primary" name="processPayment">Submit Payment</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Include Bootstrap and jQuery scripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>

</html>
