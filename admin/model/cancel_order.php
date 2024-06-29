<?php
// Ensure session is started and user is authenticated
session_start();

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    // Redirect to login or appropriate error handling
    header('Location: login.php');
    exit();
}

// Include database connection and Food class
include_once("../../connection/connection.php");
include_once("Food.php");

// Initialize Food class with database connection
$food = new Food($conn);

// Check if orderId is provided via POST
if (isset($_POST['orderId'])) {
    $order_id = $_POST['orderId'];

    // Attempt to cancel the order
    $success = $food->cancelOrder($order_id);

    if ($success) {
        // Redirect with success message
        header('Location: ../../orders.php?cancelled=cancelled');
        exit();
    } else {
        // Redirect with error message
        header('Location: ../../orders.php?cancel_error=failed');
        exit();
    }
} else {
    // Redirect with error message if orderId is not provided
    header('Location: ../../orders.php?cancel_error=orderId_not_provided');
    exit();
}
?>
