<?php
session_start();
include_once("../../connection/connection.php");
include_once("Order.php"); 

$food = new Order($conn);

// Check if order ID is provided and new status is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    // Update order status in the database
    if ($food->updateOrderStatus($order_id, $new_status)) {
        $_SESSION['success'] = "Order status updated successfully.";
    } else {
        $_SESSION['error'] = "Failed to update order status.";
    }
} else {
    $_SESSION['error'] = "Invalid request.";
}

// Redirect back to the view order details page
if (isset($order_id)) {
    header("Location: ../orders.php?id=" . $order_id);
} else {
    header("Location: ../orders.php");
}
exit();
?>
