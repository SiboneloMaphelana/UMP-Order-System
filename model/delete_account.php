<?php
session_start();
include_once("../connection/connection.php");
include_once("User.php");

$user = new User($conn);
$userId = intval($_SESSION['id']);  // Use session id for self-deletion

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
?>
