<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require __DIR__ . '/../../vendor/autoload.php';


class Report
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    public function getSalesReport()
    {
        $sql = "SELECT DATE_FORMAT(order_date, '%Y-%m') AS month_year, SUM(total_amount) AS total_sales, COUNT(id) AS total_orders 
                FROM orders 
                WHERE status = 'completed' 
                GROUP BY DATE_FORMAT(order_date, '%Y-%m')";
        $result = $this->conn->query($sql);

        $salesReport = array();
        while ($row = $result->fetch_assoc()) {
            $salesReport[] = array(
                'date' => $row['month_year'],
                'total_sales' => $row['total_sales'],
                'total_orders' => $row['total_orders']
            );
        }

        return $salesReport;
    }


    public function getOrdersReport()
    {
        $sql = "SELECT status, COUNT(id) AS count FROM orders WHERE status = 'completed' OR status = 'cancelled' OR status = 'pending' GROUP BY status";
        $result = $this->conn->query($sql);
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        return $orders;
    }


    public function getCustomerReport()
    {
        $sql = "SELECT COUNT(id) AS total_customers FROM users";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }

    public function getCustomers()
    {
        $sql = "SELECT * FROM users";
        $result = $this->conn->query($sql);
        $customers = [];
        while ($row = $result->fetch_assoc()) {
            $customers[] = $row;
        }
        return $customers;
    }


    public function getInventoryReport()
    {
        $sql = "SELECT name, quantity,  price FROM food_items";
        $result = $this->conn->query($sql);
        $inventory = [];
        while ($row = $result->fetch_assoc()) {
            $inventory[] = $row;
        }
        return $inventory;
    }


    public function getRevenueReport($filterType)
    {
        switch ($filterType) {
            case 'daily':
                $groupBy = "DATE_FORMAT(order_date, '%Y-%m-%d')";
                break;
            case 'weekly':
                $groupBy = "DATE_FORMAT(order_date, '%Y-%U')";
                break;
            case 'monthly':
            default:
                $groupBy = "DATE_FORMAT(order_date, '%Y-%m')";
                break;
        }

        $sql = "SELECT DATE_FORMAT(order_date, '%Y-%m-%d') AS date, SUM(total_amount) AS revenue 
                FROM orders 
                WHERE status = 'completed' 
                GROUP BY $groupBy";

        $result = $this->conn->query($sql);

        $revenueReport = array();
        while ($row = $result->fetch_assoc()) {
            $revenueReport[] = array(
                'date' => $row['date'],
                'revenue' => $row['revenue']
            );
        }

        return $revenueReport;
    }

    public function getRevenueByCategory()
    {
        $sql = "SELECT category.name AS category_name, SUM(orders.total_amount) AS revenue 
            FROM orders 
            JOIN order_items ON orders.id = order_items.order_id
            JOIN food_items ON order_items.food_id = food_items.id
            JOIN category ON food_items.category_id = category.id
            WHERE orders.status = 'completed'
            GROUP BY category.name";

        $result = $this->conn->query($sql);

        $revenueReport = array();
        while ($row = $result->fetch_assoc()) {
            $revenueReport[] = array(
                'category' => $row['category_name'],
                'revenue' => $row['revenue']
            );
        }

        return $revenueReport; // Return array with category names and revenue
    }

    // Function to get order frequency based on a filter (today, week, month)
    public function getOrderFrequency($filter)
    {
        // Set the date condition based on the filter
        switch ($filter) {
            case 'today':
                $dateCondition = "DATE(order_date) = CURDATE()";
                break;
            case 'week':
                $dateCondition = "YEARWEEK(order_date, 1) = YEARWEEK(CURDATE(), 1)";
                break;
            case 'month':
            default:
                $dateCondition = "MONTH(order_date) = MONTH(CURDATE()) AND YEAR(order_date) = YEAR(CURDATE())";
                break;
        }

        // Query to get order frequency based on the date condition
        $sql = "SELECT DATE(order_date) AS date, COUNT(id) AS order_count 
                FROM orders 
                WHERE $dateCondition
                GROUP BY DATE(order_date) 
                ORDER BY order_date ASC";

        $result = $this->conn->query($sql);

        $orderFrequency = array();
        while ($row = $result->fetch_assoc()) {
            $orderFrequency[] = array(
                'date' => $row['date'],
                'order_count' => $row['order_count']
            );
        }

        return $orderFrequency;
    }

    // Function to get revenue by payment method
    public function getPaymentMethodRevenue()
    {
        $sql = "SELECT payment_method, SUM(total_amount) AS revenue
                FROM orders
                WHERE status = 'completed'
                GROUP BY payment_method";

        $result = $this->conn->query($sql);

        $paymentMethodRevenue = array();
        while ($row = $result->fetch_assoc()) {
            $paymentMethodRevenue[] = array(
                'payment_method' => $row['payment_method'],
                'revenue' => $row['revenue']
            );
        }

        return $paymentMethodRevenue;
    }

    public function getOrderComparison()
    {
        $sql = "SELECT DATE_FORMAT(order_date, '%Y-%m') AS month_year,
                       SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed_orders,
                       SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) AS cancelled_orders
                FROM orders
                GROUP BY DATE_FORMAT(order_date, '%Y-%m')
                ORDER BY month_year";

        $result = $this->conn->query($sql);

        $orderComparison = array();
        while ($row = $result->fetch_assoc()) {
            $orderComparison[] = array(
                'month' => $row['month_year'],
                'completed_orders' => $row['completed_orders'],
                'cancelled_orders' => $row['cancelled_orders']
            );
        }

        return $orderComparison; // Return array with month, completed orders, and cancelled orders
    }

    public function getCheckoutComparison()
    {
        $sql = "SELECT DATE_FORMAT(order_date, '%Y-%m') AS month_year,
                       SUM(CASE WHEN user_id = 0 AND status = 'completed' THEN 1 ELSE 0 END) AS guest_checkouts,
                       SUM(CASE WHEN user_id > 0 AND status = 'completed' THEN 1 ELSE 0 END) AS registered_user_checkouts,
                       COUNT(*) AS total_orders
                FROM orders
                WHERE status = 'completed'
                GROUP BY DATE_FORMAT(order_date, '%Y-%m')
                ORDER BY month_year";

        $result = $this->conn->query($sql);

        $checkoutComparison = array();
        while ($row = $result->fetch_assoc()) {
            $checkoutComparison[] = array(
                'month' => $row['month_year'],
                'guest_checkouts' => $row['guest_checkouts'],
                'registered_user_checkouts' => $row['registered_user_checkouts'],
                'total_orders' => $row['total_orders']
            );
        }

        return $checkoutComparison; // Return array with month, guest checkouts, registered user checkouts, and total orders
    }

    public function downloadOrdersReport()
    {
        // Fetch orders data
        $orders = $this->getOrdersReport();

        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();

        // Set Excel sheet headers
        $sheet->setCellValue('A1', 'Status');
        $sheet->setCellValue('B1', 'Count');

        // Populate data from the database
        $rowCount = 2; // Start from row 2 (1 is for headers)
        foreach ($orders as $order) {
            $sheet->setCellValue('A' . $rowCount, ucfirst($order['status']));
            $sheet->setCellValue('B' . $rowCount, $order['count']);
            $rowCount++;
        }

        // Set headers for download
        $filename = "orders.xlsx";

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $filename);
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output'); // Save to PHP output
        exit(); // Always exit after outputting the file
    }
}
