<?php
session_start();
include_once("Food.php");
include_once("../UMP-Order-System/connection/connection.php");

//global variable for the base URL
$baseUrl = "";


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
            SELECT id, order_date, total_amount, status
            FROM orders
            WHERE id = ? AND user_id = ?
        ");
        $orderQuery->bind_param('ii', $orderId, $userId);
    } else {
        // Guest user
        $orderQuery = $conn->prepare("
            SELECT id, order_date, total_amount, status
            FROM orders
            WHERE id = ?
            AND user_id IS NULL
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

    // Fetch order items
    $orderItemsQuery = $conn->prepare("
        SELECT oi.*, fi.name
        FROM order_items oi
        JOIN food_items fi ON oi.food_id = fi.id
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
    mysqli_close($conn);
}
