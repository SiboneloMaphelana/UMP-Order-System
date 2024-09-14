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

$data = $report->getOrderComparison();

header('Content-Type: application/json');
echo json_encode($data);
