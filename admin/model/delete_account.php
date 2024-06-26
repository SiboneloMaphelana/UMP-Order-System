<?php
// Start the session
session_start();

// Include the necessary files
include_once("../../connection/connection.php");
include("Admin.php");

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}

// Instantiate the Admin class
$adminModel = new Admin($conn);

// Get the ID of the account to be deleted
$id = $_POST['id'];

// Attempt to delete the account
if ($adminModel->deleteAccount($id)) {
    // Account deleted successfully, destroy session and redirect to login
    session_destroy(); // End the session for the user
    header("Location: ../login.php");
    exit;
} else {
    // Failed to delete the account, display an error message
    $_SESSION['error'] = "Failed to delete the account.";
    header("Location: ../profile.php");
    exit;
}
?>

