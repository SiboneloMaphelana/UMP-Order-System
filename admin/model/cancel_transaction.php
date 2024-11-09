<?php
session_start(); // Start the session

include_once("../../connection/connection.php");
include("Order.php");
include("Notifications.php"); // Ensure Notifications class is included

if (isset($_SESSION['orderId'])) {
    $orderId = $_SESSION['orderId'];  // Retrieve the order ID from the session
    $food = new Order($conn);
    $notifications = new Notifications($conn); // Create an instance of Notifications

    // Retrieve the order details to get customer info
    $orderDetails = $food->getOrderById($orderId);
    $userId = $orderDetails['user_id'] ?? null;
    $guestPhone = isset($_SESSION['guest_phone']) ? $_SESSION['guest_phone'] : '';

    // Attempt to update the payment status to "cancelled"
    if ($food->updatePaymentStatus($orderId, 'cancelled')) {
        // Update the order status to "cancelled"
        if ($food->updateOrderStatus($orderId, 'cancelled')) {
            echo "Order $orderId has been successfully marked as cancelled.";

            // Send SMS notification for cancellation
            if (!empty($userId)) {
                // If user is logged in, get customer details
                $customer = $food->getCustomerById($userId);
                if (!empty($customer['phone'])) {
                    $notifications->orderCancellationSMS($customer['phone'], $orderDetails); // Send SMS for cancellation

                    // Log successful SMS notification
                    $logMessage = "SMS notification sent successfully to user ID: $userId for cancellation of order ID: $orderId\n";
                }
            } else {
                // For guests
                if (!empty($guestPhone)) {
                    $notifications->orderCancellationSMS($guestPhone, $orderDetails); // Send SMS for cancellation

                    // Log successful SMS notification
                    $logMessage = "SMS notification sent successfully to guest phone number: " . $guestPhone . " for cancellation of order ID: $orderId\n";
                    file_put_contents($logFile, $logMessage, FILE_APPEND);
                }
            }

            header("Location: https://bfaf-196-21-175-1.ngrok-free.app/UMP-Order-System/order_confirmation.php");
            exit(); // Ensure no further code is executed after the redirect
        } else {
            echo "Failed to update order status for Order $orderId.";
        }
    } else {
        echo "Failed to update payment status for Order $orderId.";
    }
} else {
    echo "No order ID found in session.";
}
