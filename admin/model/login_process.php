<?php
include_once("../../connection/connection.php");
include_once("Admin.php"); 

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin = new Admin($conn);

    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false; // Check if "Remember Me" is checked

    // Attempt to login
    if ($admin->login($email, $password)) {
        $_SESSION['login'] = 'email';
        if ($remember) {
            setcookie('admin_email', $email, time() + (86400 * 30), "/"); // 30 days expiration
            setcookie('admin_password', $password, time() + (86400 * 30), "/"); // 30 days expiration
        }
        header("Location: ../home.php");
    } else {
        $_SESSION['login_error'] = "Invalid email or password!";
        header("Location: ../login.php");
    }
} else {
    header("Location: ../login.php");
    exit; 
}
?>