<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include your database connection file
include '../../connection/connection.php'; // Adjust the path as needed

// Include the Report class
include 'Report.php'; // Adjust the path as needed

// Instantiate the Report class
$report = new Report($conn);

// Get the filter from the query string (default to 'today' if none is set)
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'today';

// Get the order frequency based on the filter
$orderFrequency = $report->getOrderFrequency($filter);

// Return the result as a JSON response
header('Content-Type: application/json');
echo json_encode($orderFrequency);
