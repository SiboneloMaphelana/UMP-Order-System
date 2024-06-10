<?php
include_once("../connection/connection.php");
include_once("User.php"); 

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new User($conn);

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Attempt to login
    if ($user->login($email, $password)) {
        $_SESSION['success'] = "Login successful!";
        $_SESSION['login'] = 'email';
        header("Location: ../index.php");
    } else {
        $_SESSION['error'] = "Invalid email or password!";
        header("Location: ../login.php");
    }
} else {
    header("Location: ../login.php");
    exit; 
}
?>