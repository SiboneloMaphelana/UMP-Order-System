<?php
class Report
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    /**
     * Retrieves the sales report for completed orders grouped by month.
     *
     * @return array An array of sales reports, each containing the month year, total sales, and total orders.
     */
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


    /**
     * Retrieves the orders report grouped by status.
     *
     * @return array An array of orders reports, each containing the status and count of orders.
     */
    public function getOrdersReport()
    {
        $sql = "SELECT status, COUNT(id) AS count FROM orders GROUP BY status";
        $result = $this->conn->query($sql);
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        return $orders;
    }


    /**
     * Retrieves a report containing the total number of customers.
     *
     * @return array An associative array containing the total number of customers.
     */
    public function getCustomerReport()
    {
        $sql = "SELECT COUNT(id) AS total_customers FROM users";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }


    /**
     * Retrieves the inventory report for all food items.
     *
     * This function executes a SQL query to retrieve the name, quantity, and price of all food items.
     * It then iterates over the result set and adds each row to an array.
     * Finally, it returns the array containing the inventory report.
     *
     * @return array An array of inventory reports, each containing the name, quantity, and price of a food item.
     */
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


    /**
     * Retrieves a revenue report based on the given filter type.
     *
     * @param string $filterType The filter type for the report. Can be 'daily', 'weekly', or 'monthly'.
     * @return array An array of revenue reports, each containing the date and revenue.
     */
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
}
