<?php
session_start();
include_once("../connection/connection.php");
include_once("User.php"); 

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new User($conn);

    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false; // Check if "Remember Me" is checked

    // Attempt to login
    if ($user->login($email, $password)) {
        $_SESSION['user_email'] = $email; // Store user email in session   
        // If "Remember Me" is checked, set a cookie with user credentials
        if ($remember) {
            setcookie('user_email', $email, time() + (86400 * 30), "/"); // 30 days expiration
            setcookie('user_password', $password, time() + (86400 * 30), "/"); // 30 days expiration
        }
        
        $_SESSION['login'] = 'email';
        header("Location: ../index.php");
    } else {
        $_SESSION['error'] = "Invalid email or password, or your account is deactivated!";
        header("Location: ../login.php");
    }
} else {
    header("Location: ../login.php");
    exit(); 
}
?>
