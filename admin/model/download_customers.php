<?php

$databaseName = 'test';
$localhost = 'localhost';
$username = 'root';
$password = '';

// Create a connection to the database
$db = new mysqli($localhost, $username, $password, $databaseName);

if ($db->connect_error) {
    die('Connection failed: ' . $db->connect_error);
}

// Include PhpSpreadsheet
require_once '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Avoid any previous output
ob_clean();

// SQL query to fetch customer data
$sql = "SELECT 
            users.id AS customer_id, 
            users.name AS customer_name, 
            COUNT(orders.id) AS total_orders, 
            SUM(orders.total_amount) AS total_spent, 
            AVG(orders.total_amount) AS avg_order_value, 
            GROUP_CONCAT(orders.payment_method SEPARATOR ',') AS payment_methods,
            MAX(orders.order_date) AS last_order_date
        FROM users
        LEFT JOIN orders ON users.id = orders.user_id
        GROUP BY users.id, users.name
        ORDER BY users.name ASC";

$stmt = $db->prepare($sql);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Create a new Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set the Excel sheet headers
$sheet->setCellValue('A1', 'Customer ID');
$sheet->setCellValue('B1', 'Customer Name');
$sheet->setCellValue('C1', 'Total Orders');
$sheet->setCellValue('D1', 'Total Spent');
$sheet->setCellValue('E1', 'Average Order Value');
$sheet->setCellValue('F1', 'Most Frequent Payment Method');
$sheet->setCellValue('G1', 'Last Order Date');

// Fill data into the Excel sheet
$row = 2; // Start from the second row
while ($customer = $result->fetch_assoc()) {
    // Parse payment methods to get the most frequent payment method
    $paymentMethods = array_count_values(explode(',', $customer['payment_methods']));
    $mostCommonPaymentMethod = array_search(max($paymentMethods), $paymentMethods);

    // Populate the Excel sheet with customer data
    $sheet->setCellValue('A' . $row, $customer['customer_id']);
    $sheet->setCellValue('B' . $row, $customer['customer_name']);
    $sheet->setCellValue('C' . $row, $customer['total_orders']);
    $sheet->setCellValue('D' . $row, number_format($customer['total_spent'], 2));
    $sheet->setCellValue('E' . $row, number_format($customer['avg_order_value'], 2));
    $sheet->setCellValue('F' . $row, $mostCommonPaymentMethod);
    $sheet->setCellValue('G' . $row, $customer['last_order_date']);
    
    $row++;
}

// Set headers for downloading the file as Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="customers_report.xlsx"');
header('Cache-Control: max-age=0');

// Write the file and output it to the browser
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// Close database connection and statement
$stmt->close();
$db->close();
exit;

?>
