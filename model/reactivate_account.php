<?php
session_start(); 
require_once "User.php";
require_once '../connection/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $user = new User($conn);

    if ($user->reactivateAccount($email)) {
        $_SESSION['reactivate_success'] = "Your account has been successfully reactivated. You can now log in.";
    } else {
        $_SESSION['reactivate_failure'] = "Reactivation failed. Either the account is already active, or it does not exist.";
    }

    header('Location: ../login.php');
    exit();
}
?>
