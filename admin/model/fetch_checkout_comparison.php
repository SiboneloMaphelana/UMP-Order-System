<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include your database connection file
include '../../connection/connection.php'; // Adjust the path as needed

// Include the Report class
include 'Report.php'; // Adjust the path as needed

// Instantiate the Report class and pass the database connection
$report = new Report($conn);

// Call the method to get checkout comparison data
$data = $report->getCheckoutComparison();

header('Content-Type: application/json');
echo json_encode($data);
?>
