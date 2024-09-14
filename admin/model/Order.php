<?php

class Order
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function sanitizeInput($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    public function addOrder($userId, $totalAmount, $paymentMethod)
    {
        // Check if userId is null for guest checkout
        if (is_null($userId)) {
            // Use NULL for user_id when inserting a guest order
            $stmt = $this->conn->prepare("INSERT INTO orders (user_id, total_amount, payment_method) VALUES (NULL, ?, ?)");
            $stmt->bind_param("ds", $totalAmount, $paymentMethod);
        } else {
            $stmt = $this->conn->prepare("INSERT INTO orders (user_id, total_amount, payment_method) VALUES (?, ?, ?)");
            $stmt->bind_param("ids", $userId, $totalAmount, $paymentMethod);
        }

        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            return false;
        }
    }

    // Function to check quantity
    public function checkQuantity($foodId, $quantity)
    {
        $stmt = $this->conn->prepare("SELECT quantity FROM food_items WHERE id = ?");
        $stmt->bind_param("i", $foodId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['quantity'] >= $quantity;
    }

    // Function to update quantity
    public function updateItemQuantity($foodId, $quantityPurchased)
    {
        // Prepare SQL statement
        $sql = "UPDATE food_items SET quantity = quantity - ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        // Check if preparation was successful
        if (!$stmt) {
            throw new Exception("Error preparing the SQL statement: " . $this->conn->error);
        }

        // Bind parameters
        $stmt->bind_param("ii", $quantityPurchased, $foodId);

        // Execute statement
        $result = $stmt->execute();

        // Check if execution was successful
        if (!$result) {
            throw new Exception("Error executing the SQL statement: " . $stmt->error);
        }

        // Close statement
        $stmt->close();

        return $result;
    }



    public function addOrderItem($orderId, $foodId, $quantity, $price)
    {
        $stmt = $this->conn->prepare("INSERT INTO order_items (order_id, food_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $orderId, $foodId, $quantity, $price);
        return $stmt->execute();
    }

    public function getOrderById($order_id)
    {
        $stmt = $this->conn->prepare('SELECT * FROM orders WHERE id = ?');
        $stmt->bind_param('i', $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getOrderItems($orderId)
    {
        $stmt = $this->conn->prepare('SELECT oi.*, fi.name, fi.price FROM order_items oi JOIN food_items fi ON oi.food_id = fi.id WHERE oi.order_id = ?');
        $stmt->bind_param('i', $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCustomerById(string $id): ?array
    {
        $stmt = $this->conn->prepare('SELECT name, email, phone FROM users WHERE id = ?');
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $customer = $result->fetch_assoc();

        // Ensure the phone number has a '+' prefix if it does not have it
        if ($customer && $customer['phone'] && $customer['phone'][0] !== '+') {
            $customer['phone'] = '+' . $customer['phone'];
        }

        return $customer;
    }

    public function updateOrderStatus(string $order_id, string $new_status): bool
    {
        $stmt = $this->conn->prepare('UPDATE orders SET status = ?, completed_at = IF(? = "completed", NOW(), completed_at) WHERE id = ?');
        $stmt->bind_param('ssi', $new_status, $new_status, $order_id);
        return $stmt->execute();
    }

    public function getOrdersByUserId($user_id)
    {
        $stmt = $this->conn->prepare("
            SELECT o.*, GROUP_CONCAT(fi.name SEPARATOR ', ') AS food_items
            FROM orders o
            LEFT JOIN order_items oi ON o.id = oi.order_id
            LEFT JOIN food_items fi ON oi.food_id = fi.id
            WHERE o.user_id = ? AND o.is_deleted = FALSE
            GROUP BY o.id desc
        ");

        if (!$stmt) {
            error_log("Error preparing statement: " . $this->conn->error);
            return false;
        }

        // Bind the user_id parameter
        $stmt->bind_param("i", $user_id);

        if (!$stmt->execute()) {
            error_log("Error executing statement: " . $stmt->error);
            return false;
        }

        // Get the result
        $result = $stmt->get_result();
        $orders = [];

        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }

        // Close the statement
        $stmt->close();

        return $orders;
    }


    public function countOrdersByUserId($user_id)
    {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) AS total_orders
            FROM orders
            WHERE user_id = ? AND is_deleted = FALSE
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total_orders'];
    }

    public function cancelOrder($orderId)
    {
        $orderId = intval($this->sanitizeInput($orderId));
        $update_sql = "UPDATE orders SET status = 'cancelled' WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $update_sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $orderId);
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
            mysqli_stmt_close($stmt);
        } else {
            return false;
        }
    }

    public function getUserEmailById($userId)
    {
        $stmt = $this->conn->prepare("SELECT email FROM users WHERE id = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user['email'] ?? null;
    }

    public function getAllOrdersPaginated($page, $itemsPerPage)
    {
        $offset = ($page - 1) * $itemsPerPage;
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE is_deleted = FALSE ORDER BY order_date DESC LIMIT ?, ?");
        $stmt->bind_param("ii", $offset, $itemsPerPage);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public function countOrders()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM orders WHERE is_deleted = FALSE");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    // Fetch all existing orders
    public function getExistingOrders($page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT * FROM orders ORDER BY id DESC LIMIT $limit OFFSET $offset";
        $result = $this->conn->query($sql);
        $orders = [];

        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }

        return $orders;
    }

    public function getTotalOrderCount()
    {
        $sql = "SELECT COUNT(*) as total FROM orders";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }


    // Fetch new orders based on the latest order ID
    public function getNewOrders($latestOrderId)
    {
        $sql = "SELECT * FROM orders WHERE id > ? ORDER BY id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $latestOrderId);
        $stmt->execute();
        $result = $stmt->get_result();
        $orders = [];

        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }

        return $orders;
    }

    public function getAllOrders()
    {
        $sql = "SELECT * FROM orders ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $orders = [];

        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }

        return $orders;
    }

    // Fetch new orders by status based on the latest order ID
    public function getNewOrdersByStatus($latestOrderId, array $statuses)
    {
        $placeholders = implode(',', array_fill(0, count($statuses), '?'));
        $sql = "SELECT * FROM orders WHERE id > ? AND status IN ($placeholders) ORDER BY id ASC";
        $stmt = $this->conn->prepare($sql);
        $params = array_merge([$latestOrderId], $statuses);
        $stmt->bind_param(str_repeat('i', 1) . str_repeat('s', count($statuses)), ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $orders = [];

        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }

        return $orders;
    }
    // Function to get current and previous period total orders
    public function getTotalOrders($period)
    {
        $currentQuery = '';
        $previousQuery = '';

        switch ($period) {
            case 'today':
                // Only include completed orders
                $currentQuery = "SELECT COUNT(*) AS total FROM orders WHERE DATE(order_date) = CURDATE()";
                $previousQuery = "SELECT COUNT(*) AS total FROM orders WHERE DATE(order_date) = CURDATE() - INTERVAL 1 DAY";
                break;
            case 'week':
                // Only include completed orders
                $currentQuery = "SELECT COUNT(*) AS total FROM orders WHERE WEEK(order_date) = WEEK(CURDATE()) ";
                $previousQuery = "SELECT COUNT(*) AS total FROM orders WHERE WEEK(order_date) = WEEK(CURDATE()) - 1";
                break;
            case 'month':
                // Only include completed orders
                $currentQuery = "SELECT COUNT(*) AS total FROM orders WHERE MONTH(order_date) = MONTH(CURDATE())";
                $previousQuery = "SELECT COUNT(*) AS total FROM orders WHERE MONTH(order_date) = MONTH(CURDATE()) - 1";
                break;
        }


        $currentResult = $this->conn->query($currentQuery)->fetch_assoc();
        $previousResult = $this->conn->query($previousQuery)->fetch_assoc();

        return [
            'current' => $currentResult['total'],
            'previous' => $previousResult['total']
        ];
    }

    // Function to get data for today, this week, and this month
    public function getOrdersData()
    {
        $todayOrders = $this->getTotalOrders('today');
        $weekOrders = $this->getTotalOrders('week');
        $monthOrders = $this->getTotalOrders('month');

        echo json_encode([
            'today' => $todayOrders,
            'week' => $weekOrders,
            'month' => $monthOrders
        ]);
    }

    public function getTotalRevenue($period)
    {
        $query = '';
        switch ($period) {
            case 'today':
                $query = "SELECT SUM(total_amount) AS revenue FROM orders WHERE DATE(order_date) = CURDATE() AND status = 'completed'";
                break;
            case 'week':
                $query = "SELECT SUM(total_amount) AS revenue FROM orders WHERE WEEK(order_date) = WEEK(CURDATE()) AND status = 'completed'";
                break;
            case 'month':
                $query = "SELECT SUM(total_amount) AS revenue FROM orders WHERE MONTH(order_date) = MONTH(CURDATE()) AND status = 'completed'";
                break;
        }
        $stmt = $this->conn->query($query);
        $result = $stmt->fetch_assoc();
        return $result['revenue'];
    }

    // Method to get revenue for a given period
    public function getRevenue($period)
    {
        $query = '';
        switch ($period) {
            case 'today':
                $query = "SELECT SUM(total_amount) AS revenue FROM orders WHERE DATE(order_date) = CURDATE() AND status = 'completed'";
                $previousQuery = "SELECT SUM(total_amount) AS revenue FROM orders WHERE DATE(order_date) = CURDATE() - INTERVAL 1 DAY AND status = 'completed'";
                break;
            case 'week':
                $query = "SELECT SUM(total_amount) AS revenue FROM orders WHERE WEEK(order_date) = WEEK(CURDATE()) AND status = 'completed'";
                $previousQuery = "SELECT SUM(total_amount) AS revenue FROM orders WHERE WEEK(order_date) = WEEK(CURDATE()) - 1 AND status = 'completed'";
                break;
            case 'month':
                $query = "SELECT SUM(total_amount) AS revenue FROM orders WHERE MONTH(order_date) = MONTH(CURDATE()) AND status = 'completed'";
                $previousQuery = "SELECT SUM(total_amount) AS revenue FROM orders WHERE MONTH(order_date) = MONTH(CURDATE()) - 1 AND status = 'completed'";
                break;
        }

        // Fetch current period revenue
        $stmt = $this->conn->query($query);
        $current = $stmt->fetch_assoc();
        $currentRevenue = $current['revenue'] ? floatval($current['revenue']) : 0;

        // Fetch previous period revenue
        $stmt = $this->conn->query($previousQuery);
        $previous = $stmt->fetch_assoc();
        $previousRevenue = $previous['revenue'] ? floatval($previous['revenue']) : 0;

        return [
            'current' => $currentRevenue,
            'previous' => $previousRevenue
        ];
    }


    public function getAverageOrderValue()
    {
        // Query to get the current month's average order value
        $currentMonthQuery = "
        SELECT AVG(total_amount) AS average 
        FROM orders 
        WHERE MONTH(order_date) = MONTH(CURDATE()) 
        AND YEAR(order_date) = YEAR(CURDATE())";

        $stmtCurrent = $this->conn->query($currentMonthQuery);
        $currentMonthResult = $stmtCurrent->fetch_assoc();
        $currentMonthAverage = $currentMonthResult['average'] ? (float)$currentMonthResult['average'] : 0; // Cast to float

        // Query to get the previous month's average order value
        $previousMonthQuery = "
        SELECT AVG(total_amount) AS average 
        FROM orders 
        WHERE MONTH(order_date) = MONTH(CURDATE() - INTERVAL 1 MONTH) 
        AND YEAR(order_date) = YEAR(CURDATE() - INTERVAL 1 MONTH)";

        $stmtPrevious = $this->conn->query($previousMonthQuery);
        $previousMonthResult = $stmtPrevious->fetch_assoc();
        $previousMonthAverage = $previousMonthResult['average'] ? (float)$previousMonthResult['average'] : 0; // Cast to float

        // Calculate the percentage difference between the current and previous months
        if ($previousMonthAverage == 0) {
            $percentageChange = $currentMonthAverage > 0 ? 100 : 0;
        } else {
            $percentageChange = (($currentMonthAverage - $previousMonthAverage) / $previousMonthAverage) * 100;
        }

        // Return an array containing the current month's average and the percentage change
        return [
            'currentMonthAverage' => $currentMonthAverage,
            'percentageChange' => round($percentageChange, 2)
        ];
    }
}
