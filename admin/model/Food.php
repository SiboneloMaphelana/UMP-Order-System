<?php
class Food {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function sanitizeInput($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

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

    public function addOrder($userId, $totalAmount, $paymentMethod) {
        $stmt = $this->conn->prepare("INSERT INTO orders (user_id, total_amount, payment_method) VALUES (?, ?, ?)");
        $stmt->bind_param("ids", $userId, $totalAmount, $paymentMethod);
        if ($stmt->execute()) {
            return $stmt->insert_id; // Return the inserted order ID
        } else {
            return false; // Return false on failure
        }
    }

    // Function to add order items to the database
    public function addOrderItem($orderId, $foodId, $quantity, $price) {
        $stmt = $this->conn->prepare("INSERT INTO order_items (order_id, food_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $orderId, $foodId, $quantity, $price);
        return $stmt->execute(); // Return true or false based on execution
    }
    
    
    
    
    
}
?>
