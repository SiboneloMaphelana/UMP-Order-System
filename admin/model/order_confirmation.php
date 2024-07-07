<?php
session_start();
include_once("Food.php");
include_once("../UMP-Order-System/connection/connection.php");

try {
    // Check if user is logged in
    if (!isset($_SESSION['id'])) {
        throw new Exception("User is not logged in.");
    }

    // Retrieve user ID from session
    $userId = $_SESSION['id'];

    // Initialize variables to store order details
    $order = [];
    $orderItems = [];

    // Check if order ID is set in the session
    if (!isset($_SESSION['orderId'])) {
        throw new Exception("Order ID is not set.");
    }

    $orderId = intval($_SESSION['orderId']);

    // Start transaction
    mysqli_autocommit($conn, false);

    // Fetch order details including status
    $orderQuery = $conn->prepare("SELECT id, order_date, total_amount, status FROM orders WHERE id = ? AND user_id = ?");
    $orderQuery->bind_param('ii', $orderId, $userId);
    $orderQuery->execute();
    $orderResult = $orderQuery->get_result();
    $order = $orderResult->fetch_assoc();

    // Check if order exists
    if (!$order) {
        throw new Exception("Order not found.");
    }

    // Fetch order items
    $orderItemsQuery = $conn->prepare("SELECT oi.*, fi.name, fi.quantity AS available_quantity, oi.status FROM order_items oi JOIN food_items fi ON oi.food_id = fi.id WHERE oi.order_id = ?");
    $orderItemsQuery->bind_param('i', $orderId);
    $orderItemsQuery->execute();
    $orderItemsResult = $orderItemsQuery->get_result();
    while ($row = $orderItemsResult->fetch_assoc()) {
        $orderItems[] = $row;
    }

    // Check if any food item has insufficient quantity
    foreach ($orderItems as $item) {
        $foodItemId = $item['food_id'];
        $quantityOrdered = $item['quantity'];
        $availableQuantity = $item['available_quantity'];

        // Ensure available quantity is sufficient
        if ($availableQuantity < $quantityOrdered) {
            throw new Exception("Insufficient quantity for food item: " . $item['name']);
        }

        // Reduce the quantity of each ordered food item
        $updateQuantityQuery = $conn->prepare("UPDATE food_items SET quantity = quantity - ? WHERE id = ? AND quantity >= ?");
        $updateQuantityQuery->bind_param('iii', $quantityOrdered, $foodItemId, $quantityOrdered);
        $updateQuantityQuery->execute();

        // Check if update was successful
        if ($updateQuantityQuery->affected_rows === 0) {
            throw new Exception("Failed to update quantity for food item ID: " . $foodItemId);
        }
    }

    // Commit transaction
    mysqli_commit($conn);

    // Clear orderId from session after displaying the order
    unset($_SESSION['orderId']);

    // Return order and order items for use in frontend
    return compact('order', 'orderItems');

} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($conn);

    // Log the exception
    error_log('Exception caught in place_order.php: ' . $e->getMessage());

    // Set error message in session and redirect
    $_SESSION['error'] = "Failed to place order: " . $e->getMessage();
    header("Location: ../cart.php");
    exit();
} finally {
    // Always close connections and free resources
    if (isset($orderQuery)) {
        $orderQuery->close();
    }
    if (isset($orderItemsQuery)) {
        $orderItemsQuery->close();
    }
    mysqli_autocommit($conn, true);
    mysqli_close($conn);
}
?>
