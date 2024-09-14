<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once("../connection/connection.php");
include("User.php");

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

$user = new User($conn);
$userId = intval($_SESSION['id']);

if ($user->deleteUserAccount($userId)) {
    // Account deleted successfully, destroy session and redirect to login
    session_destroy();
    header("Location: ../login.php");
    exit();
} else {
    // Error occurred while deleting the account
    $_SESSION['error'] = "Error deleting account. Please try again.";
    header("Location: ../profile.php");
    exit();
}
