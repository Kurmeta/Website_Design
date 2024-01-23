<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
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
        <form action="payment.php" method="post">
            <div class="form-group">
                <div class="jumbotron">
                    <label for="search_username">Enter Name to Retrieve Unpaid Bookings:</label>
                    <input type="text" class="form-control" id="search_username" name="search_username" required>
                </div>
            </div>
            <button type="submit" class="btn btn-custom" name="results">Retrieve Bookings</button>
        </form>
    </div>

    <div class="container mt-5">
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "bookings_db";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $paymentMessage = "";
        $userName = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["search_username"])) {
                $userName = $_POST["search_username"];
                $sql = "SELECT * FROM bookings WHERE userName = '$userName' AND paid = 'no'";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
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
                    $paymentMessage = "<p>No unpaid bookings found for the given name.</p>";
                }
            }
        }
        ?>
        <?php echo $paymentMessage; ?>

        <?php if (isset($result) && $result->num_rows > 0 && !isset($_POST["processPayment"])) : ?>
            <h2>Make Payment</h2>

            <div class="row">
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
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cardPaymentModalLabel">Card Payment</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="process_payment.php">
                                <p>You are paying for <?php echo $result->num_rows; ?> course(s) for <?php echo $userName; ?></p>

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
                                    <label for="email">Paypal Email:</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>

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
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="PaypalPaymentModalLabel">Card Payment</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="process_payment.php">
                                <p>You are paying for <?php echo $result->num_rows; ?> course(s) for <?php echo $userName; ?></p>

                                <!-- Add card input fields here (card number, expiry date, CSV) -->
                                <div class="form-group">
                                    <label for="email">Paypal Email:</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password:</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>

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

    <!-- Bootstrap and jQuery scripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>

</html>
