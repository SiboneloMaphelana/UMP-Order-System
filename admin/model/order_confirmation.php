<?php
session_start();
include_once("Food.php");
include_once("../UMP-Order-System/connection/connection.php");

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: ../../login.php");
    exit();
}

// Retrieve user ID from session
$userId = $_SESSION['id'];

// Initialize variables to store order details
$order = [];
$orderItems = [];

// Check if order ID is set in the session
if (isset($_SESSION['orderId'])) {
    $orderId = $_SESSION['orderId'];

    // Fetch order details
    $orderQuery = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
    $orderQuery->bind_param('ii', $orderId, $userId);
    $orderQuery->execute();
    $orderResult = $orderQuery->get_result();
    $order = $orderResult->fetch_assoc();

    // Check if order exists
    if (!$order) {
        $_SESSION['error'] = "Order not found.";
        header("Location: ../../profile.php");
        exit();
    }

    // Fetch order items
    $orderItemsQuery = $conn->prepare("SELECT oi.*, fi.name FROM order_items oi JOIN food_items fi ON oi.food_id = fi.id WHERE oi.order_id = ?");
    $orderItemsQuery->bind_param('i', $orderId);
    $orderItemsQuery->execute();
    $orderItemsResult = $orderItemsQuery->get_result();
    while ($row = $orderItemsResult->fetch_assoc()) {
        $orderItems[] = $row;
    }

    // Reduce the quantity of each ordered food item
    foreach ($orderItems as $item) {
        $foodItemId = $item['food_id'];
        $quantityOrdered = $item['quantity'];

        $updateQuantityQuery = $conn->prepare("UPDATE food_items SET quantity = quantity - ? WHERE id = ?");
        $updateQuantityQuery->bind_param('ii', $quantityOrdered, $foodItemId);
        $updateQuantityQuery->execute();

        // Check if update was successful
        if ($updateQuantityQuery->affected_rows === 0) {
            $_SESSION['error'] = "Failed to update quantity for food item ID: " . $foodItemId;
            header("Location: ../../cart.php");
            exit();
        }
    }

    // Clear orderId from session after displaying the order
    unset($_SESSION['orderId']);
} else {
    $_SESSION['error'] = "Invalid order ID.";
    header("Location: ../../index.php");
    exit();
}


// Return order and order items for use in frontend
return compact('order', 'orderItems');
?>
