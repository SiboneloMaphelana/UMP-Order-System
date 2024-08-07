<?php
session_start();
include_once("../../connection/connection.php");
include_once("Order.php");

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$food = new Order($conn);

if (isset($_POST['orderId'])) {
    $order_id = $_POST['orderId'];

    $success = $food->cancelOrder($order_id);

    if ($success) {
        header('Location: ../../orders.php?cancelled=cancelled');
        exit();
    } else {
        header('Location: ../../orders.php?cancel_error=failed');
        exit();
    }
} else {
    header('Location: ../../orders.php?cancel_error=orderId_not_provided');
    exit();
}
?>
