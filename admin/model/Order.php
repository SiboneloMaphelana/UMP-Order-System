<?php

class Order{
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function sanitizeInput($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    public function addOrder($userId, $totalAmount, $paymentMethod) {
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
    

    public function addOrderItem($orderId, $foodId, $quantity, $price) {
        $stmt = $this->conn->prepare("INSERT INTO order_items (order_id, food_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $orderId, $foodId, $quantity, $price);
        return $stmt->execute(); 
    }

    public function getOrderById($order_id) {
        $stmt = $this->conn->prepare('SELECT * FROM orders WHERE id = ?');
        $stmt->bind_param('i', $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getOrderItems($orderId) {
        $stmt = $this->conn->prepare('SELECT oi.*, fi.name, fi.price FROM order_items oi JOIN food_items fi ON oi.food_id = fi.id WHERE oi.order_id = ?');
        $stmt->bind_param('i', $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCustomerById(string $id): ?array {
        $stmt = $this->conn->prepare('SELECT name, email, phone FROM users WHERE id = ?');
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateOrderStatus(string $order_id, string $new_status): bool {
        $stmt = $this->conn->prepare('UPDATE orders SET status = ?, completed_at = IF(? = "completed", NOW(), completed_at) WHERE id = ?');
        $stmt->bind_param('ssi', $new_status, $new_status, $order_id);
        return $stmt->execute();
    }

    public function getOrdersByUserId($user_id) {
        // Prepare the SQL statement without pagination
        $stmt = $this->conn->prepare("
            SELECT o.*, GROUP_CONCAT(fi.name SEPARATOR ', ') AS food_items
            FROM orders o
            LEFT JOIN order_items oi ON o.id = oi.order_id
            LEFT JOIN food_items fi ON oi.food_id = fi.id
            WHERE o.user_id = ? AND o.is_deleted = FALSE
            GROUP BY o.id
        ");
    
        if (!$stmt) {
            // Handle the error, e.g., log it
            error_log("Error preparing statement: " . $this->conn->error);
            return false;
        }
    
        // Bind the user_id parameter
        $stmt->bind_param("i", $user_id);
    
        if (!$stmt->execute()) {
            // Handle the error, e.g., log it
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
    

    public function countOrdersByUserId($user_id) {
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

    public function cancelOrder($orderId) {
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

    public function getUserEmailById($userId) {
        $stmt = $this->conn->prepare("SELECT email FROM users WHERE id = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user['email'] ?? null;
    }

    public function getAllOrdersPaginated($page, $itemsPerPage) {
        $offset = ($page - 1) * $itemsPerPage;
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE is_deleted = FALSE LIMIT ?, ?");
        $stmt->bind_param("ii", $offset, $itemsPerPage);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function countOrders() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM orders WHERE is_deleted = FALSE");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }

     // Fetch all existing orders
     public function getExistingOrders($page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT * FROM orders ORDER BY id DESC LIMIT $limit OFFSET $offset";
        $result = $this->conn->query($sql);
        $orders = [];
    
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
    
        return $orders;
    }
    
    public function getTotalOrderCount() {
        $sql = "SELECT COUNT(*) as total FROM orders";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    

    // Fetch new orders based on the latest order ID
    public function getNewOrders($latestOrderId) {
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

    public function getAllOrders() {
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
    public function getNewOrdersByStatus($latestOrderId, array $statuses) {
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
    
}



?>
