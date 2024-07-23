<?php

session_start();
include_once("../../connection/connection.php");
include_once("Order.php");
include_once("Notifications.php");

$food = new Order($conn);
$notifications = new Notifications($conn);

// Check if order ID and new status are provided and the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = $food->sanitizeInput($_POST['order_id']);
    $new_status = $food->sanitizeInput($_POST['status']);

    // Validate status
    $valid_statuses = ['pending', 'processing', 'completed', 'cancelled'];
    if (!in_array($new_status, $valid_statuses)) {
        $_SESSION['error'] = "Invalid status provided.";
        header("Location: ../orders.php");
        exit();
    }

    // Update order status in the database
    if ($food->updateOrderStatus($order_id, $new_status)) {
        $_SESSION['success'] = "Order status updated successfully.";

        // If the new status is 'completed', send an email
        if ($new_status === 'completed') {
            $orderDetails = $food->getOrderById($order_id);
            $customer = $food->getCustomerById($orderDetails['user_id']);
            $orderItems = $food->getOrderItems($order_id);

            $emailResult = $notifications->orderCompletionEmail($orderDetails, $customer, $orderItems);

            if ($emailResult === true) {
                $_SESSION['email_success'] = "Email notification sent successfully to the user.";
            } else {
                $_SESSION['email_error'] = $emailResult;
            }
        }
    } else {
        $_SESSION['error'] = "Failed to update order status.";
    }
} else {
    $_SESSION['error'] = "Invalid request.";
}

header("Location: ../orders.php");
exit();
