<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../connection/connection.php'; 

include 'Report.php'; 

$report = new Report($conn);

// Get the filter from the query string (default to 'today' if none is set)
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'today';

// Get the order frequency based on the filter
$orderFrequency = $report->getOrderFrequency($filter);

header('Content-Type: application/json');
echo json_encode($orderFrequency);
