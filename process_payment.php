<?php
// process_payment.php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["userName"])) {
    $userName = $_POST["userName"];
    $courseCost = $_POST["courseCost"];
    $email = $_POST["email"];

    // Update 'paid' column to 'yes'
    $updateSql = "UPDATE bookings SET paid = 'yes' WHERE userName = ?";
    $conn = new mysqli("localhost", "root", "root", "bookings_db");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("s", $userName);

    // Execute the statement
    $updateResult = $stmt->execute();

    if ($updateResult) {
        // Send payment successful email
        $to = "$email";
        $subject = "Payment Successful";
        $message = "Dear $userName,\n\nYour payment of $$courseCost has been successfully processed. Thank you for your payment.";

        // Additional headers
        $headers = "From: Coding Legend Courses";

        // Send the email
        mail($to, $subject, $message, $headers);

        // Redirect to payment success page
        header("Location: payment_success.php");
        exit();
    } else {
        echo "Error updating payment status: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
