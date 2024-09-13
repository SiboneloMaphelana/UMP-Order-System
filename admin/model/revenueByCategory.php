<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../connection/connection.php';

include 'Report.php'; 

$report = new Report($conn);

// Call the method to get revenue by category and store the result
$revenueData = $report->getRevenueByCategory();

// Output the result as JSON
header('Content-Type: application/json');
echo json_encode($revenueData);
?>
