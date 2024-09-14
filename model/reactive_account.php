<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once("../connection/connection.php");
include_once("User.php");

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

$user = new User($conn);
$userId = intval($_GET['id']);

// Reactivate the user's account
if ($user->reactivateAccount($userId)) {
    $_SESSION['success'] = "Account reactivated successfully!";
    header("Location: ../login.php");
    exit();
} else {
    $_SESSION['error'] = "Failed to reactivate account. Please try again.";
    header("Location: ../profile.php");
    exit();
}
