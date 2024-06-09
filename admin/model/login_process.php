<?php
include_once("../../connection/connection.php");
include_once("Admin.php"); 

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin = new Admin($conn);

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Attempt to login
    if ($admin->login($email, $password)) {
        $_SESSION['success'] = "Login successful!";
        $_SESSION['login'] = 'email';
        header("Location: ../dashboard.php");
    } else {
        $_SESSION['error'] = "Invalid email or password!";
        header("Location: ../login.php");
    }
} else {
    header("Location: ../login.php");
    exit; 
}
?>