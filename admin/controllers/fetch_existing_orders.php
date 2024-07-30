<?php

header('Content-Type: application/json');

require_once '../../connection/connection.php';
include_once("../model/Order.php");

$orderModel = new Order($conn);

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10;

$orders = $orderModel->getExistingOrders($page, $limit);
$totalOrders = $orderModel->getTotalOrderCount();

$totalPages = ceil($totalOrders / $limit);

$response = [
    'orders' => $orders,
    'totalPages' => $totalPages
];

echo json_encode($response);

?>
