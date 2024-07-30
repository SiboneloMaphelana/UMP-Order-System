<?php

header('Content-Type: application/json');

// Include database connection
require_once '../../connection/connection.php';
include_once("../model/Order.php");

// Create an instance of Order model
$orderModel = new Order($conn);

// Get page number from query parameter
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10;

// Fetch existing orders
$orders = $orderModel->getExistingOrders($page, $limit);
$totalOrders = $orderModel->getTotalOrderCount();

// Calculate total pages
$totalPages = ceil($totalOrders / $limit);

$response = [
    'orders' => $orders,
    'totalPages' => $totalPages
];

echo json_encode($response);

?>
