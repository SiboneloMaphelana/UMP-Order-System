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

// Check if month or all months option is selected from the form
$month = isset($_GET['month']) ? $_GET['month'] : null;
$all_months = isset($_GET['all_months']) ? $_GET['all_months'] : null;

// Create a new Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Logic for specific month or all months
if ($all_months) {
    // SQL query to fetch aggregated data for all months, including favorite food
    $sql = "SELECT 
                DATE_FORMAT(orders.order_date, '%M %Y') AS month, 
                COUNT(orders.id) AS total_orders, 
                SUM(orders.total_amount) AS total_revenue, 
                AVG(orders.total_amount) AS avg_order_value, 
                GROUP_CONCAT(orders.payment_method SEPARATOR ',') AS payment_methods,
                ROUND(AVG(TIMESTAMPDIFF(SECOND, orders.order_date, orders.completed_at))) AS avg_completion_time_seconds,
                (
                    SELECT food_items.name
                    FROM order_items
                    JOIN food_items ON order_items.food_id = food_items.id
                    JOIN orders o ON order_items.order_id = o.id
                    WHERE DATE_FORMAT(o.order_date, '%Y-%m') = DATE_FORMAT(orders.order_date, '%Y-%m')
                    GROUP BY food_items.name
                    ORDER BY SUM(order_items.quantity) DESC
                    LIMIT 1
                ) AS favorite_food
            FROM orders
            INNER JOIN users ON orders.user_id = users.id
            GROUP BY DATE_FORMAT(orders.order_date, '%Y-%m')
            ORDER BY orders.order_date ASC";

    $stmt = $db->prepare($sql);
    
    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Set the Excel sheet headers for aggregated data
    $sheet->setCellValue('A1', 'Month');
    $sheet->setCellValue('B1', 'Total Orders');
    $sheet->setCellValue('C1', 'Total Revenue');
    $sheet->setCellValue('D1', 'Average Order Value');
    $sheet->setCellValue('E1', 'Favorite Payment Method');
    $sheet->setCellValue('F1', 'Favorite Food'); 
    $sheet->setCellValue('G1', 'Average Completion Time');
    
    // Fill data into the Excel sheet
    $row = 2; // Start from the second row
    while ($order = $result->fetch_assoc()) {
        // Parse payment methods
        $paymentMethods = array_count_values(explode(',', $order['payment_methods']));
        $mostCommonPaymentMethod = array_search(max($paymentMethods), $paymentMethods);

        // Convert completion time in seconds to HH:mm:ss format
        $avgCompletionTime = gmdate('H:i:s', $order['avg_completion_time_seconds']);
    
        // Populate the Excel sheet with aggregated data
        $sheet->setCellValue('A' . $row, $order['month']);
        $sheet->setCellValue('B' . $row, $order['total_orders']);
        $sheet->setCellValue('C' . $row, number_format($order['total_revenue'], 2));
        $sheet->setCellValue('D' . $row, number_format($order['avg_order_value'], 2));
        $sheet->setCellValue('E' . $row, $mostCommonPaymentMethod); // Most common payment method
        $sheet->setCellValue('F' . $row, $order['favorite_food']); // Favorite food
        $sheet->setCellValue('G' . $row, $avgCompletionTime); // Completion time in HH:mm:ss format
    
        $row++;
    }

} elseif ($month) {
    // SQL query to fetch detailed data for the specific month
    $sql = "SELECT orders.id AS order_id, users.name AS customer_name, orders.order_date, orders.total_amount, 
                   orders.payment_method, orders.status, orders.completed_at
            FROM orders
            INNER JOIN users ON orders.user_id = users.id
            WHERE DATE_FORMAT(orders.order_date, '%Y-%m') = ?";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param('s', $month);
    
    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Set the Excel sheet headers for detailed data
    $sheet->setCellValue('A1', 'Order ID');
    $sheet->setCellValue('B1', 'Customer Name');
    $sheet->setCellValue('C1', 'Order Date');
    $sheet->setCellValue('D1', 'Total Cost');
    $sheet->setCellValue('E1', 'Payment Method');
    $sheet->setCellValue('F1', 'Order Status');
    $sheet->setCellValue('G1', 'Completion Time');
    
    // Fill data into the Excel sheet
    $row = 2; // Start from the second row
    while ($order = $result->fetch_assoc()) {
        // Calculate the delivery time in seconds
        $completionTimeSeconds = strtotime($order['completed_at']) - strtotime($order['order_date']);
    
        // Convert completion time in seconds to HH:mm:ss format
        $completionTimeFormatted = gmdate('H:i:s', $completionTimeSeconds);
    
        // Populate the Excel sheet with order data
        $sheet->setCellValue('A' . $row, $order['order_id']);
        $sheet->setCellValue('B' . $row, $order['customer_name']);
        $sheet->setCellValue('C' . $row, $order['order_date']);
        $sheet->setCellValue('D' . $row, number_format($order['total_amount'], 2)); 
        $sheet->setCellValue('E' . $row, $order['payment_method']);
        $sheet->setCellValue('F' . $row, $order['status']);
        $sheet->setCellValue('G' . $row, $completionTimeFormatted); // Completion time in HH:mm:ss format
    
        $row++;
    }
}

// Set headers for downloading the file as Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="orders_report.xlsx"');
header('Cache-Control: max-age=0');

// Write the file and output it to the browser
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// Close database connection and statement
$stmt->close();
$db->close();
exit;

?>
