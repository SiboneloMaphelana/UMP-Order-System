<?php

class Order{
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Sanitizes the input data by removing any HTML tags, slashes, and extra whitespace.
     *
     * @param mixed $data The data to be sanitized.
     * @return string The sanitized data.
     */
    public function sanitizeInput($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    /**
     * Adds an order to the database with the given user ID, total amount, and payment method.
     *
     * @param int $userId The ID of the user placing the order.
     * @param float $totalAmount The total amount of the order.
     * @param string $paymentMethod The payment method used for the order.
     * @return int|false The ID of the inserted order, or false on failure.
     */
    public function addOrder($userId, $totalAmount, $paymentMethod) {
        $stmt = $this->conn->prepare("INSERT INTO orders (user_id, total_amount, payment_method) VALUES (?, ?, ?)");
        $stmt->bind_param("ids", $userId, $totalAmount, $paymentMethod);
        if ($stmt->execute()) {
            return $stmt->insert_id; // Return the inserted order ID
        } else {
            return false; // Return false on failure
        }
    }


    /**
     * Adds an order item to the database with the given order ID, food ID, quantity, and price.
     *
     * @param int $orderId The ID of the order.
     * @param int $foodId The ID of the food item.
     * @param int $quantity The quantity of the food item.
     * @param float $price The price of the food item.
     * @return bool Returns true if the order item was added successfully, false otherwise.
     */
    public function addOrderItem($orderId, $foodId, $quantity, $price) {
        $stmt = $this->conn->prepare("INSERT INTO order_items (order_id, food_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $orderId, $foodId, $quantity, $price);
        return $stmt->execute(); // Return true or false based on execution
    }

    /**
     * Retrieves all orders from the database.
     *
     * @return array An array of associative arrays representing the orders,
     *               each containing the columns of the 'orders' table.
     */
    public function getAllOrders(): array {
        $sql = "SELECT * FROM orders WHERE is_deleted = FALSE";
        $result = $this->conn->query($sql);
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        return $orders;
    }
    

    /**
     * Retrieves an order from the database by its ID.
     *
     * @param int $orderId The ID of the order to retrieve.
     * @return array|null An associative array representing the order, or null if no order is found.
     */
    public function getOrderById($orderId) {
        $stmt = $this->conn->prepare('SELECT * FROM orders WHERE id = ?');
        $stmt->bind_param('i', $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }


    /**
     * Retrieves order items from the database by order ID.
     *
     * @param int $orderId The ID of the order to retrieve items for.
     * @return array An associative array representing the order items.
     */
    public function getOrderItems($orderId) {
        $stmt = $this->conn->prepare('SELECT oi.*, fi.name, fi.price FROM order_items oi JOIN food_items fi ON oi.food_id = fi.id WHERE oi.order_id = ?');
        $stmt->bind_param('i', $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Retrieves a customer from the database by their ID.
     *
     * @param string $id The ID of the customer to retrieve.
     * @return array|null The customer details as an associative array, or null if no customer was found.
     */
    public function getCustomerById(string $id): ?array {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Fetch customer details as an associative array
        $customer = $result->fetch_assoc();

        // Return customer details or null if not found
        return $customer;
    }


    /**
     * Updates the status of an order in the database.
     *
     * @param string $order_id The ID of the order to update.
     * @param string $new_status The new status to set for the order.
     * @return bool Returns true if the update was successful, false otherwise.
     */
    public function updateOrderStatus(string $order_id, string $new_status): bool {
        $stmt = $this->conn->prepare('UPDATE orders SET status = ? WHERE id = ?');
        $stmt->bind_param('si', $new_status, $order_id);
        
        if ($stmt->execute()) {
            return true; // Update successful
        } else {
            return false; // Failed to update
        }
    }

    public function getOrdersByUserId($user_id) {
        $orders = [];
    
        // Prepare SQL query with a join to fetch food item names
        $stmt = $this->conn->prepare("
            SELECT o.*, GROUP_CONCAT(fi.name SEPARATOR ', ') AS food_items
            FROM orders o
            LEFT JOIN order_items oi ON o.id = oi.order_id
            LEFT JOIN food_items fi ON oi.food_id = fi.id
            WHERE o.user_id = ? AND o.is_deleted = FALSE
            GROUP BY o.id
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
    
        return $orders;
    }
    


    /**
     * Cancels an order by updating its status to 'cancelled'.
     *
     * @param int $orderId The ID of the order to be cancelled.
     * @return bool Returns true if the order status was successfully updated, false otherwise.
     */
    public function cancelOrder($orderId) {
    // Sanitize the input (assuming you have a sanitizeInput method)
    $orderId = intval($this->sanitizeInput($orderId));
    
    // Update the order status to cancelled
    $update_sql = "UPDATE orders SET status = 'cancelled' WHERE id = ?";
    $stmt = mysqli_prepare($this->conn, $update_sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $orderId);
        if (mysqli_stmt_execute($stmt)) {
            // Check if any rows were affected
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                return true;
            } else {
                // No rows were updated (order might not exist or already cancelled)
                return false;
            }
        } else {
            // Execution of statement failed
            return false;
        }
        mysqli_stmt_close($stmt);
    } else {
        // Prepare statement failed
        return false;
    }
    }
}




?>