<?php
class Report {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }


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
    
    
    public function getOrdersReport() {
        $sql = "SELECT status, COUNT(id) AS count FROM orders WHERE status = 'completed' OR status = 'cancelled' OR status = 'pending' GROUP BY status";
        $result = $this->conn->query($sql);
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        return $orders;
    }


    public function getCustomerReport() {
        $sql = "SELECT COUNT(id) AS total_customers FROM users";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }


    public function getInventoryReport() {
        $sql = "SELECT name, quantity,  price FROM food_items";
        $result = $this->conn->query($sql);
        $inventory = [];
        while ($row = $result->fetch_assoc()) {
            $inventory[] = $row;
        }
        return $inventory;
    }


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

