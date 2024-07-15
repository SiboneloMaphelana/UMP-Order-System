<?php
require_once '../../connection/connection.php';
require_once 'Report.php'; 

$report = new Report($conn); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filter'])) {
    $filterType = $_POST['filter'];
    $revenueReport = $report->getRevenueReport($filterType);
    
    // Output HTML for the revenue data
    foreach ($revenueReport as $revenue) {
        echo '<li class="list-group-item">' . $revenue['date'] . '  :     R' . number_format($revenue['revenue'], 2) . '</li>';
    }
} else {
    echo 'Invalid request';
}
?>
