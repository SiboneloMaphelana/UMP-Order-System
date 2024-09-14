<?php
include_once("../../connection/connection.php");
include_once("Order.php");

// Instantiate the RevenueData class
$revenueData = new Order($conn);

// Get data for each period
$todayData = $revenueData->getRevenue('today');
$weekData = $revenueData->getRevenue('week');
$monthData = $revenueData->getRevenue('month');

// Create the response array
$response = [
    'today' => $todayData,
    'week' => $weekData,
    'month' => $monthData
];

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);
