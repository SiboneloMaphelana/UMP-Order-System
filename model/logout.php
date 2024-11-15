<?php
// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the home page
header("Location: ../index.php");
exit; // Stop execution
?>
