<?php

include_once("../../connection/connection.php");
include_once("Order.php");

$order = new Order($conn);

// Get the average order value and percentage change
$data = $order->getAverageOrderValue();

// Return data as JSON for frontend
echo json_encode($data);