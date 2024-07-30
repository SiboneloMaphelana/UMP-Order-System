<?php

header('Content-Type: application/json');

require_once("../../connection/connection.php");
require_once("../model/Order.php");

$orderModel = new Order($conn);

$order_id = isset($_GET['order_id']) && is_numeric($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($order_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid order ID']);
    exit;
}

try {
    $stmt = $conn->prepare('SELECT id, total_amount, status, order_date FROM orders WHERE id = ?');
    $stmt->bind_param('i', $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $orderDetails = [
            'id' => $row['id'],
            'total_amount' => $row['total_amount'],
            'status' => $row['status'],
            'order_date' => $row['order_date'],
            'items' => $orderModel->getOrderItems($row['id'])
        ];
        echo json_encode($orderDetails);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Order not found']);
    }
} catch (Exception $e) {
    error_log("Error fetching order details: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error']);
}

$stmt->close();
$conn->close();
