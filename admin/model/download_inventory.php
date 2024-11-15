<?php

$databaseName = 'test';  
$localhost = 'localhost'; 
$username = 'root';       
$password = '';           

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

// SQL query to fetch inventory data
$sql = "SELECT 
            food_items.id AS item_id,
            food_items.name AS item_name,
            category.name AS category,
            food_items.quantity AS quantity_in_stock,
            food_items.price,
            (food_items.quantity * food_items.price) AS total_value
        FROM food_items
        LEFT JOIN category ON food_items.category_id = category.id
        ORDER BY food_items.name ASC";

$stmt = $db->prepare($sql);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Create a new Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set the Excel sheet headers
$sheet->setCellValue('A1', 'Item ID');
$sheet->setCellValue('B1', 'Item Name');
$sheet->setCellValue('C1', 'Category');
$sheet->setCellValue('D1', 'Quantity in Stock');
$sheet->setCellValue('E1', 'Unit Price');
$sheet->setCellValue('F1', 'Total Value');

// Fill data into the Excel sheet
$row = 2; // Start from the second row
while ($item = $result->fetch_assoc()) {
    // Populate the Excel sheet with inventory data
    $sheet->setCellValue('A' . $row, $item['item_id']);
    $sheet->setCellValue('B' . $row, $item['item_name']);
    $sheet->setCellValue('C' . $row, $item['category']);
    $sheet->setCellValue('D' . $row, $item['quantity_in_stock']);
    $sheet->setCellValue('E' . $row, number_format($item['price'], 2)); // Format unit price
    $sheet->setCellValue('F' . $row, number_format($item['total_value'], 2)); // Format total value
    
    $row++;
}

// Set headers for downloading the file as Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="inventory_report.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// Close database connection and statement
$stmt->close();
$db->close();
exit;

?>
