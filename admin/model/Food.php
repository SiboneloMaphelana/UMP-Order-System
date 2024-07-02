<?php
class Food {
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
     * Adds a new category to the database.
     *
     * @param string $name The name of the category.
     * @param string $imagePath The path to the image for the category.
     * @return string|bool Returns "Category already exists." if the category already exists,
     *                     true if the category is successfully added,
     *                     or "Error adding category." if there is an error adding the category.
     */
    public function addCategory($name, $imagePath) {
        $name = $this->sanitizeInput($name);

        $check_sql = "SELECT * FROM category WHERE name=?";
        $check_stmt = mysqli_prepare($this->conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "s", $name);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);

        if (mysqli_num_rows($check_result) > 0) {
            return "Category already exists.";
        }

        $insert_sql = "INSERT INTO category (name, imageName) VALUES (?, ?)";
        $insert_stmt = mysqli_prepare($this->conn, $insert_sql);
        mysqli_stmt_bind_param($insert_stmt, "ss", $name, $imagePath);
        return mysqli_stmt_execute($insert_stmt) ? true : "Error adding category.";
    }

    /**
     * Retrieves all the categories from the database.
     *
     * @return array An array of category objects.
     */
    public function getCategories() {
        $query = "SELECT * FROM category";
        $result = mysqli_query($this->conn, $query);
        $categories = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $categories[] = $row;
            }
        }

        return $categories;
    }

    /**
     * Deletes a category from the database and optionally deletes the associated image file.
     *
     * @param int $categoryId The ID of the category to delete.
     * @return string|bool Returns true if the category was deleted successfully, false otherwise. 
     *                     If an error occurred, a string with the error message is returned.
     */
    public function deleteCategory($categoryId) {
        $categoryId = intval($this->sanitizeInput($categoryId));
        
        // Retrieve the image name to delete the image file
        $query = "SELECT imageName FROM category WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $categoryId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $imageName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        
        // Update the category_id to NULL for related food items
        $update_sql = "UPDATE food_items SET category_id = NULL WHERE category_id = ?";
        $update_stmt = mysqli_prepare($this->conn, $update_sql);
        mysqli_stmt_bind_param($update_stmt, "i", $categoryId);
        
        if (!mysqli_stmt_execute($update_stmt)) {
            return "Error updating food items: " . mysqli_error($this->conn);
        }
        
        // Delete the category
        $delete_sql = "DELETE FROM category WHERE id = ?";
        $delete_stmt = mysqli_prepare($this->conn, $delete_sql);
        mysqli_stmt_bind_param($delete_stmt, "i", $categoryId);
        
        if (mysqli_stmt_execute($delete_stmt)) {
            // Delete the image file if it exists
            if (!empty($imageName) && file_exists("../uploads/" . $imageName)) {
                unlink("../uploads/" . $imageName);
            }
            return true;
        } else {
            return "Error deleting category: " . mysqli_error($this->conn);
        }
    }
    
    /**
     * Updates a category in the database with the given ID.
     *
     * @param int $id The ID of the category to update.
     * @param string|null $name The new name of the category (optional).
     * @param string|null $imageName The new image name of the category (optional).
     * @return string|bool Returns true if the category was updated successfully, 
     *                     "No fields to update." if no fields were provided, 
     *                     "No rows updated. Either the category does not exist or the new values are the same as the old values." if no rows were updated, 
     *                     "Error updating category: " . mysqli_error($this->conn) if there was an error updating the category.
     */
    public function updateCategory($id, $name = null, $imageName = null) {
        $id = intval($this->sanitizeInput($id));
        $name = $name ? $this->sanitizeInput($name) : null;
        $imageName = $imageName ? $this->sanitizeInput($imageName) : null;
    
        $update_sql = "UPDATE category SET ";
        $set_values = [];
        $params = [];
        $param_types = '';
    
        if ($name !== null) {
            $set_values[] = "name = ?";
            $params[] = $name;
            $param_types .= 's';
        }
    
        if ($imageName !== null) {
            $set_values[] = "imageName = ?";
            $params[] = $imageName;
            $param_types .= 's';
        }
    
        if (empty($set_values)) {
            return "No fields to update.";
        }
    
        $update_sql .= implode(", ", $set_values);
        $update_sql .= " WHERE id = ?";
        $params[] = $id;
        $param_types .= 'i';
    
        $stmt = mysqli_prepare($this->conn, $update_sql);
        mysqli_stmt_bind_param($stmt, $param_types, ...$params);
    
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                return true;
            } else {
                return "No rows updated. Either the category does not exist or the new values are the same as the old values.";
            }
        } else {
            return "Error updating category: " . mysqli_error($this->conn);
        }
    }
    

    /**
     * Checks if a category exists in the database.
     *
     * @param int $categoryId The ID of the category to check.
     * @return bool Returns true if the category exists, false otherwise.
     */
    public function isCategoryExists($categoryId) {
        $categoryId = intval($this->sanitizeInput($categoryId));
    
        $query = "SELECT COUNT(*) as count FROM category WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $categoryId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    
        return $count > 0;
    }

    /**
     * Retrieves a category from the database by its ID.
     *
     * @param int $categoryId The ID of the category to retrieve.
     * @return array|null Returns an associative array representing the category if found,
     *                    or null if no category with the given ID exists.
     */
    public function getCategoryById($categoryId) {
        // Sanitize the input
        $categoryId = intval($this->sanitizeInput($categoryId));

        $select_sql = "SELECT * FROM category WHERE id=?";
        $select_stmt = mysqli_prepare($this->conn, $select_sql);
        mysqli_stmt_bind_param($select_stmt, "i", $categoryId);
        mysqli_stmt_execute($select_stmt);
        $result = mysqli_stmt_get_result($select_stmt);

        $category = mysqli_fetch_assoc($result);
        mysqli_stmt_close($select_stmt);

        return $category;
    }

    /**
     * Adds a food item to the database.
     *
     * @param string $name The name of the food item.
     * @param string $description The description of the food item.
     * @param int $categoryId The ID of the category the food item belongs to.
     * @param int $quantity The quantity of the food item.
     * @param float $price The price of the food item.
     * @param array $image The image of the food item.
     * @param int $adminId The ID of the admin adding the food item.
     * @return bool|string Returns true if the food item was added successfully, false otherwise.
     *                     If an error occurred, a string with the error message is returned.
     */
    public function addFoodItem($name, $description, $categoryId, $quantity, $price, $image, $adminId) {
        // Sanitize input data
        $name = $this->sanitizeInput($name);
        $description = $this->sanitizeInput($description);
        $categoryId = intval($this->sanitizeInput($categoryId));
        $quantity = intval($this->sanitizeInput($quantity));
        $price = floatval($this->sanitizeInput($price));
        $adminId = intval($this->sanitizeInput($adminId));
        $imageName = ''; 

        // Handle image upload
        if (isset($image['name']) && !empty($image['name'])) {
            $targetDir = '../foods/'; //target directory
            $imageName = basename($image['name']);
            $targetPath = $targetDir . $imageName;

            // Move uploaded file to target directory
            if (!move_uploaded_file($image['tmp_name'], $targetPath)) {
                return "Failed to upload image.";
            }
        }

        // Debugging output
        echo "Debug: name=$name, description=$description, category_id=$categoryId, quantity=$quantity, price=$price, imageName=$imageName, admin_id=$adminId\n";

        // Insert food item into the database
        $sql = "INSERT INTO food_items (name, description, category_id, quantity, price, image, admin_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssiiisi", $name, $description, $categoryId, $quantity, $price, $imageName, $adminId);
        
        if (mysqli_stmt_execute($stmt)) {
            return true; // Return true if success
        } else {
            return "Error adding food item: " . mysqli_error($this->conn); // Return error message if failure
        }
    }

    /**
     * Retrieves all the food items from the database, along with their category names.
     * If a food item does not have a category, it is assigned the category 'Uncategorized'.
     *
     * @return array An array of associative arrays representing the food items,
     *               each containing the following keys: id, name, description, quantity, price, image, and Category.
     */
    public function getAllFoodItems() {
        $query = "SELECT F.id, F.name, F.description, F.quantity, F.price, F.image, 
                  IFNULL(C.name, 'Uncategorized') AS Category
                  FROM food_items F
                  LEFT JOIN category C ON F.category_id = C.id";
        $result = mysqli_query($this->conn, $query);
        $foodItems = [];
    
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $foodItems[] = $row;
            }
        }
    
        return $foodItems;
    }
    

    /**
     * Checks if a food item with the given ID exists in the database.
     *
     * @param int $id The ID of the food item to check.
     * @return bool Returns true if the food item exists, false otherwise.
     */
    public function foodItemExists($id) {
        $query = "SELECT id FROM food_items WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
    

    /**
     * Deletes a food item from the database and optionally deletes the associated image file.
     *
     * @param int $foodItemId The ID of the food item to delete.
     * @return bool|string Returns true if the food item was deleted successfully, false otherwise. 
     *                     If an error occurred, a string with the error message is returned.
     */
    public function deleteFoodItem($foodItemId) {
        // Sanitize the input
        $foodItemId = intval($this->sanitizeInput($foodItemId));
    
        // Retrieve the image name to delete the image file
        $query = "SELECT image FROM food_items WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $foodItemId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $imageName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    
        // Delete the food item from the database
        $delete_sql = "DELETE FROM food_items WHERE id = ?";
        $delete_stmt = mysqli_prepare($this->conn, $delete_sql);
        mysqli_stmt_bind_param($delete_stmt, "i", $foodItemId);
    
        if (mysqli_stmt_execute($delete_stmt)) {
            // Delete the image file if it exists
            if (!empty($imageName) && file_exists("../foods/" . $imageName)) {
                unlink("../foods/" . $imageName);
            }
            return true;
        } else {
            return "Error deleting food item: " . mysqli_error($this->conn);
        }
    }
    

    /**
     * Retrieves a food item from the database by its ID.
     *
     * @param int $foodItemId The ID of the food item to retrieve.
     * @return array|null Returns an associative array representing the food item if found,
     *                    or null if no food item with the given ID exists.
     */
    public function getFoodItemById($foodItemId) {
        // Sanitize the input
        $foodItemId = intval($this->sanitizeInput($foodItemId));
    
        $select_sql = "SELECT * FROM food_items WHERE id=?";
        $select_stmt = mysqli_prepare($this->conn, $select_sql);
        mysqli_stmt_bind_param($select_stmt, "i", $foodItemId);
        mysqli_stmt_execute($select_stmt);
        $result = mysqli_stmt_get_result($select_stmt);
    
        $foodItem = mysqli_fetch_assoc($result);
        mysqli_stmt_close($select_stmt);
    
        return $foodItem;
    }

    /**
     * Retrieves food items from the database based on the provided category ID.
     *
     * @param int $categoryId The ID of the category to retrieve food items from.
     * @return array An array of associative arrays representing the food items,
     *               each containing the following keys: id, name, description,
     *               quantity, price, image.
     */
    public function getFoodItemsByCategoryId($categoryId) {
        // Sanitize the input
        $categoryId = intval($this->sanitizeInput($categoryId));
    
        $select_sql = "SELECT * FROM food_items WHERE category_id=?";
        $select_stmt = mysqli_prepare($this->conn, $select_sql);
        mysqli_stmt_bind_param($select_stmt, "i", $categoryId);
        mysqli_stmt_execute($select_stmt);
        $result = mysqli_stmt_get_result($select_stmt);
    
        $foodItems = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $foodItems[] = $row;
        }
    
        mysqli_stmt_close($select_stmt);
    
        return $foodItems;
    }
    
    /**
     * Updates a food item in the database.
     *
     * @param int $id The ID of the food item to update.
     * @param string|null $name The new name of the food item (optional).
     * @param int|null $quantity The new quantity of the food item (optional).
     * @param float|null $price The new price of the food item (optional).
     * @param string|null $description The new description of the food item (optional).
     * @param string|null $image The new image of the food item (optional).
     * @param int|null $category The new category ID of the food item (optional).
     * @return bool|string Returns true if the food item was successfully updated,
     *                     or a string describing the error if the update failed.
     */
    public function updateFoodItem($id, $name = null, $quantity = null, $price = null, $description = null, $image = null, $category = null) {
        $id = intval($this->sanitizeInput($id));
        $name = $name ? $this->sanitizeInput($name) : null;
        $quantity = $quantity !== null ? intval($this->sanitizeInput($quantity)) : null;
        $price = $price !== null ? floatval($this->sanitizeInput($price)) : null;
        $description = $description ? $this->sanitizeInput($description) : null;
        $image = $image ? $this->sanitizeInput($image) : null;
        $category = $category !== null ? intval($this->sanitizeInput($category)) : null;
    
        $update_sql = "UPDATE food_items SET ";
        $set_values = [];
        $params = [];
        $param_types = '';
    
        if ($name !== null) {
            $set_values[] = "name = ?";
            $params[] = $name;
            $param_types .= 's';
        }
        if ($quantity !== null) {
            $set_values[] = "quantity = ?";
            $params[] = $quantity;
            $param_types .= 'i';
        }
        if ($price !== null) {
            $set_values[] = "price = ?";
            $params[] = $price;
            $param_types .= 'd';
        }
        if ($description !== null) {
            $set_values[] = "description = ?";
            $params[] = $description;
            $param_types .= 's';
        }
        if ($image !== null) {
            $set_values[] = "image = ?";
            $params[] = $image;
            $param_types .= 's';
        }
        if ($category !== null) {
            $set_values[] = "category_id = ?";
            $params[] = $category;
            $param_types .= 'i';
        }
    
        if (empty($set_values)) {
            return "No fields to update.";
        }
    
        $update_sql .= implode(", ", $set_values);
        $update_sql .= " WHERE id = ?";
        $params[] = $id;
        $param_types .= 'i';
    
        $stmt = mysqli_prepare($this->conn, $update_sql);
        if (!$stmt) {
            return "Error preparing statement: " . mysqli_error($this->conn);
        }
        mysqli_stmt_bind_param($stmt, $param_types, ...$params);
    
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                return true;
            } else {
                return "No rows updated. Either the food item does not exist or the new values are the same as the old values.";
            }
        } else {
            return "Error updating food item: " . mysqli_error($this->conn);
        }
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
