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

    /**
     * Adds an order to the database with the given user ID, total amount, and payment method.
     *
     * @param int $userId The ID of the user placing the order.
     * @param float $totalAmount The total amount of the order.
     * @param string $paymentMethod The payment method used for the order.
     * @return int|false The ID of the inserted order if successful, false otherwise.
     */
    public function addOrder($userId, $totalAmount, $paymentMethod)
    {
        $stmt = $this->conn->prepare("INSERT INTO orders (user_id, total_amount, payment_method) VALUES (?, ?, ?)");
        $stmt->bind_param("ids", $userId, $totalAmount, $paymentMethod);
        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            return false;
        }
    }

    /**
     * Adds an order item to the database with the given order ID, food ID, quantity, and price.
     *
     * @param int $orderId The ID of the order.
     * @param int $foodId The ID of the food item.
     * @param int $quantity The quantity of the food item.
     * @param float $price The price of the food item.
     * @return bool True if the order item was successfully added, false otherwise.
     */
    public function addOrderItem($orderId, $foodId, $quantity, $price)
    {
        $stmt = $this->conn->prepare("INSERT INTO order_items (order_id, food_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $orderId, $foodId, $quantity, $price);
        return $stmt->execute();
    }

    /**
     * Retrieves an order from the database by its ID.
     *
     * @param int $order_id The ID of the order to retrieve.
     * @return array|null The order data as an associative array, or null if the order is not found.
     */
    public function getOrderById($order_id)
    {
        $stmt = $this->conn->prepare('SELECT * FROM orders WHERE id = ?');
        $stmt->bind_param('i', $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Retrieves order items for a given order ID.
     *
     * @param int $orderId The ID of the order to retrieve items for.
     * @return array|false An array of order items if successful, false otherwise.
     */
    public function getOrderItems($orderId)
    {
        $stmt = $this->conn->prepare('SELECT oi.*, fi.name, fi.price FROM order_items oi JOIN food_items fi ON oi.food_id = fi.id WHERE oi.order_id = ?');
        $stmt->bind_param('i', $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Retrieves the customer information from the database by their ID.
     *
     * @param string $id The ID of the customer.
     * @return array|null The customer information as an associative array, or null if the customer is not found.
     *                   The array contains the keys 'name' and 'email'.
     */
    public function getCustomerById(string $id): ?array
    {
        $stmt = $this->conn->prepare('SELECT name, email FROM users WHERE id = ?');
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Updates the status of an order in the database with the given order ID and new status.
     *
     * @param string $order_id The ID of the order.
     * @param string $new_status The new status of the order.
     * @return bool True if the order status was successfully updated, false otherwise.
     */
    public function updateOrderStatus(string $order_id, string $new_status): bool
    {
        $stmt = $this->conn->prepare('UPDATE orders SET status = ?, completed_at = IF(? = "completed", NOW(), completed_at) WHERE id = ?');
        $stmt->bind_param('ssi', $new_status, $new_status, $order_id);
        return $stmt->execute();
    }

    /**
     * Retrieves orders from the database based on the user ID.
     *
     * @param int $user_id The ID of the user to retrieve orders for.
     * @return array An array of orders for the user.
     */
    public function getOrdersByUserId($user_id)
    {
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


    /**
     * Counts the number of orders associated with a specific user.
     *
     * @param int $user_id The ID of the user.
     * @return int The total number of orders for the user.
     */
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

    /**
     * Retrieves the email of a user from the database based on their ID.
     *
     * @param int $userId The ID of the user.
     * @return string|null The email of the user, or null if the user is not found.
     */
    public function getUserEmailById($userId)
    {
        $stmt = $this->conn->prepare("SELECT email FROM users WHERE id = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user['email'] ?? null;
    }

    /**
     * Retrieves all orders from the database with pagination.
     *
     * @param int $page The page number to retrieve.
     * @param int $itemsPerPage The number of items per page.
     * @return array An array of orders.
     */
    public function getAllOrdersPaginated($page, $itemsPerPage)
    {
        $offset = ($page - 1) * $itemsPerPage;
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE is_deleted = FALSE LIMIT ?, ?");
        $stmt->bind_param("ii", $offset, $itemsPerPage);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Counts the number of orders in the database that have not been deleted.
     *
     * @return int The total number of orders.
     */
    public function countOrders()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM orders WHERE is_deleted = FALSE");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }


    /**
     * Retrieves existing orders from the database based on the specified page and limit.
     *
     * @param int $page The page number to retrieve. Default is 1.
     * @param int $limit The number of orders to retrieve per page. Default is 10.
     * @return array An array of orders.
     */
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

    /**
     * Retrieves the total number of orders from the database.
     *
     * @return int The total number of orders.
     */
    public function getTotalOrderCount()
    {
        $sql = "SELECT COUNT(*) as total FROM orders";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }



    /**
     * Retrieves new orders from the database with IDs greater than the provided latest order ID.
     *
     * @param int $latestOrderId The ID of the latest order to retrieve new orders from.
     * @return array An array of new order records.
     */
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

    /**
     * Retrieves all orders from the database, sorted by ID in descending order.
     *
     * @return array An array of orders, each represented as an associative array.
     */
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


    /**
     * Retrieves new orders from the database based on the latest order ID and an array of statuses.
     *
     * @param int $latestOrderId The ID of the latest order to retrieve new orders from.
     * @param array $statuses An array of statuses to filter the orders by.
     * @return array An array of new order records.
     */
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
}
