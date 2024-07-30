<?php

// sse_server.php

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

// Include database connection
include_once("../../connection/connection.php");
include_once("../model/Order.php");

// Create an instance of Order model
$orderModel = new Order($conn);

// Fetch new orders from the database
$latestOrderId = isset($_SESSION['latest_order_id']) ? $_SESSION['latest_order_id'] : 0;
$orders = $orderModel->getNewOrders($latestOrderId);

if (!empty($orders)) {
    foreach ($orders as $order) {
        echo "data: " . json_encode($order) . "\n\n";
        ob_flush();
        flush();

        // Update the latest order ID in the session
        $_SESSION['latest_order_id'] = $order['id'];
    }
} else {
    echo "data: {\"message\": \"No new orders.\"}\n\n";
    ob_flush();
    flush();
}
