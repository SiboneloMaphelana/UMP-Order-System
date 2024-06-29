<?php
//session is started and user is authenticated
session_start();
include_once("../../connection/connection.php");
include_once("Food.php");

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    // Redirect to login
    header('Location: login.php');
    exit();
}

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
