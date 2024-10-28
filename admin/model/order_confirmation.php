<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once("Food.php");
include_once("../UMP-Order-System/connection/connection.php");

// Global variable for the base URL
$baseUrl = "https://4db1-196-21-175-1.ngrok-free.app";

try {
    // Initialize variables to store order details
    $order = [];
    $orderItems = [];

    // Check if order ID is set in the session
    if (!isset($_SESSION['orderId'])) {
        throw new Exception("Order ID is not set.");
    }

    $orderId = intval($_SESSION['orderId']);

    // Fetch order details
    if (isset($_SESSION['id'])) {
        // Logged-in user
        $userId = $_SESSION['id'];

        $orderQuery = $conn->prepare("
            SELECT id, order_date, total_amount, status, payment_status
            FROM orders
            WHERE id = ? AND user_id = ?
        ");
        $orderQuery->bind_param('ii', $orderId, $userId);
    } else {
        // Guest user
        $orderQuery = $conn->prepare("
            SELECT id, order_date, total_amount, status, payment_status
            FROM orders
            WHERE id = ?
            AND user_id = 0
            LIMIT 1
        ");
        $orderQuery->bind_param('i', $orderId);
    }

    $orderQuery->execute();
    $orderResult = $orderQuery->get_result();
    $order = $orderResult->fetch_assoc();

    // Check if order exists
    if (!$order) {
        throw new Exception("Order not found.");
    }

    // Determine the payment status
    $paymentStatus = $order['payment_status'];

    // Update order status based on payment status
    $newOrderStatus = '';
    if ($paymentStatus === 'complete') {
        $newOrderStatus = 'pending';
    } elseif ($paymentStatus === 'cancelled') {
        $newOrderStatus = 'cancelled';
    } else {
        $newOrderStatus = 'pending';
    }

    // Update the status in the orders table
    $updateOrderStatusQuery = $conn->prepare("
        UPDATE orders
        SET status = ?
        WHERE id = ?
    ");
    $updateOrderStatusQuery->bind_param('si', $newOrderStatus, $orderId);
    $updateOrderStatusQuery->execute();

    // Update order items status based on payment status
    $newItemStatus = ($paymentStatus === 'complete') ? 'pending' : (($paymentStatus === 'cancelled') ? 'cancelled' : 'pending');

    // Update order items' status
    $updateItemsStatusQuery = $conn->prepare("
        UPDATE order_items 
        SET status = ? 
        WHERE order_id = ?
    ");
    $updateItemsStatusQuery->bind_param('si', $newItemStatus, $orderId);
    $updateItemsStatusQuery->execute();

    // Fetch order items
    $orderItemsQuery = $conn->prepare("
    SELECT oi.*, 
           fi.name AS food_name, 
           si.name AS special_name
    FROM order_items oi
    LEFT JOIN food_items fi ON oi.food_id = fi.id
    LEFT JOIN specials si ON oi.special_id = si.id
    WHERE oi.order_id = ?
");

    $orderItemsQuery->bind_param('i', $orderId);
    $orderItemsQuery->execute();
    $orderItemsResult = $orderItemsQuery->get_result();
    while ($row = $orderItemsResult->fetch_assoc()) {
        $orderItems[] = $row;
    }

    // Clear orderId from session after displaying the order
    unset($_SESSION['orderId']);

    // Return order and order items for use in the confirmation display
    return compact('order', 'orderItems');
} catch (Exception $e) {
    error_log('Exception caught: ' . $e->getMessage());

    // Use the global base URL variable in the header redirection
    header("Location: " . $baseUrl . "/UMP-Order-System/cart.php");
    exit();
} finally {
    if (isset($orderQuery)) {
        $orderQuery->close();
    }
    if (isset($orderItemsQuery)) {
        $orderItemsQuery->close();
    }
    if (isset($updateItemsStatusQuery)) {
        $updateItemsStatusQuery->close();
    }
    if (isset($updateOrderStatusQuery)) {
        $updateOrderStatusQuery->close();
    }
    mysqli_close($conn);
}
