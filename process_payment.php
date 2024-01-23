<?php
// process_payment.php

// Check if the request method is POST and if the "userName" parameter is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["userName"])) {
    // Retrieve user input from the POST data
    $userName = $_POST["userName"];
    $courseCost = $_POST["courseCost"];
    $email = $_POST["email"];

    // Update the 'paid' column to 'yes' in the database
    $updateSql = "UPDATE bookings SET paid = 'yes' WHERE userName = ?";
    $conn = new mysqli("localhost", "root", "root", "bookings_db");

    // Check if the database connection is successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare($updateSql);
    // Bind the parameter to the statement
    $stmt->bind_param("s", $userName);

    // Execute the statement
    $updateResult = $stmt->execute();

    // Check if the update was successful
    if ($updateResult) {
        // Send a payment successful email
        $to = "$email";
        $subject = "Payment Successful";
        $message = "Dear $userName,\n\nYour payment of $$courseCost has been successfully processed. Thank you for your payment.";

        // Additional headers
        $headers = "From: Coding Legend Courses";

        // Send the email
        mail($to, $subject, $message, $headers);

        // Redirect to the payment success page
        header("Location: payment_success.php");
        exit();
    } else {
        // Display an error message if updating payment status fails
        echo "Error updating payment status: " . $conn->error;
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
}
?>
