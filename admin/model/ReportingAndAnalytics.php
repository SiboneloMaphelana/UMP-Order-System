<?php
class ReportingAndAnalytics {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Retrieves the total number of orders from the database.
     *
     * @return int The total number of orders.
     */
    public function getTotalOrders(): int {
        $stmt = $this->conn->prepare('SELECT COUNT(*) as total FROM orders');
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (int)$row['total'];
    }

    /**
     * Retrieves the total earnings from all orders.
     *
     * @return float The total earnings.
     */
    public function getTotalEarnings(): float {
        $stmt = $this->conn->prepare('SELECT SUM(total_amount) as earnings FROM orders WHERE status = ?');
        $completedStatus = 'completed';
        $stmt->bind_param('s', $completedStatus);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (float)$row['earnings'];
    }

    /**
     * Retrieves the number of orders for each status.
     *
     * @return array An associative array with order statuses as keys and their counts as values.
     */
    public function getOrderCountsByStatus(): array {
        $stmt = $this->conn->prepare('SELECT status, COUNT(*) as count FROM orders GROUP BY status');
        $stmt->execute();
        $result = $stmt->get_result();
        $orderCounts = [];
        while ($row = $result->fetch_assoc()) {
            $orderCounts[$row['status']] = (int)$row['count'];
        }
        return $orderCounts;
    }

    /**
     * Retrieves the number of orders per month.
     *
     * @return array An associative array with months as keys and order counts as values.
     */
    public function getOrdersPerMonth(): array {
        $stmt = $this->conn->prepare('SELECT MONTH(order_date) as month, COUNT(*) as count FROM orders GROUP BY MONTH(order_date)');
        $stmt->execute();
        $result = $stmt->get_result();
        $ordersPerMonth = [];
        while ($row = $result->fetch_assoc()) {
            $ordersPerMonth[(int)$row['month']] = (int)$row['count'];
        }
        return $ordersPerMonth;
    }

    

    /**
     * Retrieves the total number of customers.
     *
     * @return int The total number of customers.
     */
    public function getTotalCustomers(): int {
        $stmt = $this->conn->prepare('SELECT COUNT(*) as total FROM users WHERE role = ?');
        $customerRole = 'customer';
        $stmt->bind_param('s', $customerRole);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (int)$row['total'];
    }

    

    /**
     * Retrieves the average order value.
     *
     * @return float The average order value.
     */
    public function getAverageOrderValue(): float {
        $stmt = $this->conn->prepare('SELECT AVG(total_amount) as average FROM orders WHERE status = ?');
        $completedStatus = 'completed';
        $stmt->bind_param('s', $completedStatus);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (float)$row['average'];
    }

    /**
     * Retrieves the total number of canceled orders.
     *
     * @return int The total number of canceled orders.
     */
    public function getTotalCanceledOrders(): int {
        $stmt = $this->conn->prepare('SELECT COUNT(*) as total FROM orders WHERE status = ?');
        $canceledStatus = 'canceled';
        $stmt->bind_param('s', $canceledStatus);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (int)$row['total'];
    }
}
?>
