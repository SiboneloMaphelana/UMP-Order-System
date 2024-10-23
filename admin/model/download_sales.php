<?php

$databaseName = 'test';
$localhost = 'localhost';
$username = 'root';
$password = '';

$db = new mysqli($localhost, $username, $password, $databaseName);

if ($db->connect_error) {
    die('Connection failed: ' . $db->connect_error);
}

require_once '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Function to convert a column index to an Excel column letter
function getExcelColumnLetter($index)
{
    return \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index);
}

// SQL query to fetch all monthly sales data
$query = "SELECT DATE_FORMAT(o.order_date, '%M %Y') AS period, 
                 f.name, 
                 SUM(oi.quantity) AS quantity_sold, 
                 SUM(oi.price * oi.quantity) AS total_sales
          FROM orders o
          INNER JOIN order_items oi ON o.id = oi.order_id
          INNER JOIN food_items f ON oi.food_id = f.id
          GROUP BY period, f.name
          ORDER BY o.order_date, f.name";

// Prepare and execute the statement
$stmt = $db->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all results into an array and store period names for dynamic columns
$reportData = [];
$periods = [];
$productNames = [];
while ($row = $result->fetch_assoc()) {
    $reportData[$row['name']][$row['period']] = [
        'quantity_sold' => $row['quantity_sold'],
        'total_sales' => $row['total_sales']
    ];
    if (!in_array($row['period'], $periods)) {
        $periods[] = $row['period']; // Add period to the list if it doesn't exist
    }
    if (!in_array($row['name'], $productNames)) {
        $productNames[] = $row['name']; // Add product name to the list if it doesn't exist
    }
}

$spreadsheet = new Spreadsheet();
$writer = new Xlsx($spreadsheet);
$activeSheet = $spreadsheet->getActiveSheet();

foreach ($activeSheet->getColumnDimensions() as $columnDimension) {
    $columnDimension->setWidth(200, "px");
}


// Set the header for the products
$activeSheet->setCellValue('A1', 'Product');

// Add dynamic headers for each period (month)
$colIndex = 2; // Start at column B
foreach ($periods as $period) {
    $activeSheet->setCellValue(getExcelColumnLetter($colIndex) . '1', $period);
    $colIndex += 1;
}

// Add a header for total sales at the end
$activeSheet->setCellValue(getExcelColumnLetter($colIndex) . '1', 'TOTAL');

// Create an array to hold totals across periods for each product
$totals = array_fill_keys($periods, 0);

// Populate report data starting from row 2
$rowIndex = 2;
foreach ($productNames as $productName) {
    // Set the product name in the first column
    $activeSheet->setCellValue('A' . $rowIndex, $productName);

    $colIndex = 2; // Start from column B for period data
    $productTotalSales = 0;

    // Loop through all periods and fill in data
    foreach ($periods as $period) {
        $quantity = isset($reportData[$productName][$period]) ? $reportData[$productName][$period]['quantity_sold'] : 0;
        $sales = isset($reportData[$productName][$period]) ? $reportData[$productName][$period]['total_sales'] : 0;

        // Set sales value
        $activeSheet->setCellValue(getExcelColumnLetter($colIndex) . $rowIndex, $sales);

        // Add sales to the product's total
        $productTotalSales += $sales;

        // Add sales to the cumulative total for the period
        $totals[$period] += $sales;

        $colIndex += 1; // Move to the next period column
    }

    // Set total sales for the product in the last column
    $activeSheet->setCellValue(getExcelColumnLetter($colIndex) . $rowIndex, $productTotalSales);

    $rowIndex++; // Move to the next row for the next product
}

// Add a total summary row at the bottom
$totalRowIndex = $rowIndex; // Track the row index for totals
$activeSheet->setCellValue('A' . $totalRowIndex, 'TOTAL');

$colIndex = 2; // Reset column index for total row
foreach ($periods as $period) {
    // Set cumulative totals for each period in the total row
    $activeSheet->setCellValue(getExcelColumnLetter($colIndex) . $totalRowIndex, $totals[$period]);
    $colIndex += 1; // Move to the next period column
}

$filename = "sales_report_all_months.xlsx";

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename=' . $filename);
header('Cache-Control: max-age=0');
$writer->save('php://output');

$stmt->close();
$db->close();
