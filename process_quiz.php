<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get user's answer and question ID
    $userAnswer = $_POST['user_answer'];
    $questionId = $_POST['question_id'];

    // Validate and process the user's answer as needed
    // ...

    // For simplicity, you might insert user answers into another table in the database
    // or perform any other required actions.

    // Redirect the user back to the quiz page after submission
    header("Location: quiz.php");
    exit();
} else {
    // Redirect unauthorized access attempts
    header("Location: quiz.php");
    exit();
}
?>
