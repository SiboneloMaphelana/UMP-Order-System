<?php
class Report {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Retrieves the sales report data from the database.
     *
     * @return array The sales report data containing date, total sales, and total orders.
     */
    public function getSalesReport() {
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
    
    
    /**
     * Retrieves the orders report data from the database.
     *
     * @return array The orders report data containing status and count of orders.
     */
    public function getOrdersReport() {
        $sql = "SELECT status, COUNT(id) AS count FROM orders GROUP BY status";
        $result = $this->conn->query($sql);
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        return $orders;
    }

    /**
     * Retrieves the total number of customers from the users table in the database.
     *
     * @return array An associative array containing the total number of customers.
     */
    public function getCustomerReport() {
        $sql = "SELECT COUNT(id) AS total_customers FROM users";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }

    /**
     * Retrieves the inventory report data from the database.
     *
     * This function executes a SQL query to retrieve the name, quantity, and price of all food items from the database.
     * It then fetches the results and stores them in an array.
     *
     * @return array The inventory report data containing the name, quantity, and price of each food item.
     */
    public function getInventoryReport() {
        $sql = "SELECT name, quantity,  price FROM food_items";
        $result = $this->conn->query($sql);
        $inventory = [];
        while ($row = $result->fetch_assoc()) {
            $inventory[] = $row;
        }
        return $inventory;
    }

    /**
     * Retrieves the revenue report data from the database based on the filter type.
     *
     * @param string $filterType The type of filter for the report (daily, weekly, monthly).
     * @return array The revenue report data containing date and revenue.
     */
    public function getRevenueReport($filterType) {
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
    
    
}
?>

