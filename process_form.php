<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $cardHolderName = $_POST['cardHolderName'];
    $cardNumber = $_POST['cardNumber'];
    $expiryDate = $_POST['expiryDate'];
    $cvc = $_POST['cvc'];
    $email = $_POST['email'];
    $selectedCourse = $_POST['select_course'];
    $courseCost = $_POST['courseCost'];
    $selectedDate = $_POST['selectedDate'];
    $selectedTime = $_POST['selectedTime'];
    $userName = $_POST['userName'];

    // Perform any additional processing or validation as needed

    // Send confirmation email
    $to = $email;
    $subject = "Booking Confirmation";
    $message = "Dear $userName,\n\nThank you for booking the $selectedCourse course on $selectedDate at $selectedTime.\n\nTotal cost: £$courseCost\n\nWe look forward to seeing you!";
    $headers = "From: owenkurmeta@gmail.com"; // Replace with your email address

    mail($to, $subject, $message, $headers);
    exit();
}

