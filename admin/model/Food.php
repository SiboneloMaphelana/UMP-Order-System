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

    private function checkDbConnection() {
        if (!$this->conn) {
            throw new Exception("Database connection failed: " . mysqli_connect_error());
        }
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
        // Sanitize input
        $name = $this->sanitizeInput($name);
        
        try {
            // Check if the category already exists
            $check_sql = "SELECT * FROM category WHERE name=?";
            $check_stmt = mysqli_prepare($this->conn, $check_sql);
            mysqli_stmt_bind_param($check_stmt, "s", $name);
            mysqli_stmt_execute($check_stmt);
            $check_result = mysqli_stmt_get_result($check_stmt);
            
            if (mysqli_num_rows($check_result) > 0) {
                return "Category already exists.";
            }
            
            // Insert the new category
            $insert_sql = "INSERT INTO category (name, imageName) VALUES (?, ?)";
            $insert_stmt = mysqli_prepare($this->conn, $insert_sql);
            mysqli_stmt_bind_param($insert_stmt, "ss", $name, $imagePath);
            
            if (mysqli_stmt_execute($insert_stmt)) {
                return true;
            } else {
                return "Error adding category.";
            }
        } catch (mysqli_sql_exception $e) {
            error_log("Database error: " . $e->getMessage());
            return "An error occurred while adding the category. Please try again.";
        }
    }
    


    public function getCategories() {
        $categories = [];
    
        try {
            // Check database connection
            $this->checkDbConnection();
    
            // Prepare the query
            $query = "SELECT * FROM category";
    
            // Prepare the statement
            if (!$stmt = mysqli_prepare($this->conn, $query)) {
                throw new Exception("Database query preparation failed: " . mysqli_error($this->conn));
            }
    
            // Execute the statement
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Database query execution failed: " . mysqli_stmt_error($stmt));
            }
    
            // Bind result variables
            $result = mysqli_stmt_get_result($stmt);
    
            // Fetch all categories
            $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
            // Free the result set
            mysqli_free_result($result);
    
            // Close the statement
            mysqli_stmt_close($stmt);
        } catch (Exception $e) {
            // Log the error
            error_log($e->getMessage());
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
        try {
            $categoryId = intval($categoryId);
            
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
                throw new Exception("Error updating food items: " . mysqli_error($this->conn));
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
                throw new Exception("Error deleting category: " . mysqli_error($this->conn));
            }
        } catch (Exception $e) {
            error_log("Exception caught: " . $e->getMessage());
            return false; // Return false or handle as needed
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
        try {
            $id = intval($this->sanitizeInput($id));
            $name = $name ? $this->sanitizeInput($name) : null;
            $imageName = $imageName ? $this->sanitizeInput($imageName) : null;
    
            // Prepare the UPDATE statement dynamically
            $update_sql = "UPDATE category SET ";
            $set_values = [];
            $params = [];
            $param_types = '';
    
            // Append fields to update based on provided parameters
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
    
            // Check if any fields to update were provided
            if (empty($set_values)) {
                return "No fields to update.";
            }
    
            // Construct the full UPDATE query
            $update_sql .= implode(", ", $set_values);
            $update_sql .= " WHERE id = ?";
            $params[] = $id;
            $param_types .= 'i';
    
            // Prepare and bind parameters to the statement
            $stmt = mysqli_prepare($this->conn, $update_sql);
            mysqli_stmt_bind_param($stmt, $param_types, ...$params);
    
            // Execute the update query
            if (mysqli_stmt_execute($stmt)) {
                // Check if any rows were affected
                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    return true;
                } else {
                    return "No rows updated. Either the category does not exist or the new values are the same as the old values.";
                }
            } else {
                throw new Exception("Error updating category: " . mysqli_error($this->conn));
            }
        } catch (Exception $e) {
            error_log("Exception caught: " . $e->getMessage());
            return false; // Return false or handle as needed
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
    try {
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
            $targetDir = '../uploads/'; // Target directory for file upload
            $imageName = basename($image['name']);
            $targetPath = $targetDir . $imageName;

            // Move uploaded file to target directory
            if (!move_uploaded_file($image['tmp_name'], $targetPath)) {
                return "Failed to upload image.";
            }
        }

        // Insert food item into the database
        $sql = "INSERT INTO food_items (name, description, category_id, quantity, price, image, admin_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssiiisi", $name, $description, $categoryId, $quantity, $price, $imageName, $adminId);

        // Execute the SQL statement
        if (mysqli_stmt_execute($stmt)) {
            return true; // Return true if success
        } else {
            throw new Exception("Error adding food item: " . mysqli_error($this->conn)); // Throw exception if failure
        }
    } catch (Exception $e) {
        error_log("Exception caught: " . $e->getMessage());
        return $e->getMessage(); // Return error message
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
        
        // Update the deleted status instead of deleting
        $update_sql = "UPDATE food_items SET deleted = 1 WHERE id = ?";
        $update_stmt = mysqli_prepare($this->conn, $update_sql);
        mysqli_stmt_bind_param($update_stmt, "i", $foodItemId);
        
        if (mysqli_stmt_execute($update_stmt)) {
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
    
        // Check if quantity is provided and not negative
        if ($quantity !== null && $quantity < 0) {
            return "Quantity cannot be negative.";
        }
    
        // Prepare SQL statement components
        $update_sql = "UPDATE food_items SET ";
        $set_values = [];
        $params = [];
        $param_types = '';
    
        // Build SQL statement dynamically based on provided parameters
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
    
        // Check if any fields are provided for update
        if (empty($set_values)) {
            return "No fields to update.";
        }
    
        // Construct the complete SQL query
        $update_sql .= implode(", ", $set_values);
        $update_sql .= " WHERE id = ?";
        $params[] = $id;
        $param_types .= 'i';
    
        // Prepare and execute the SQL statement
        $stmt = mysqli_prepare($this->conn, $update_sql);
        if (!$stmt) {
            return "Error preparing statement: " . mysqli_error($this->conn);
        }
        mysqli_stmt_bind_param($stmt, $param_types, ...$params);
    
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                return true; // Return true if update successful
            } else {
                return "No rows updated. Either the food item does not exist or the new values are the same as the old values.";
            }
        } else {
            return "Error updating food item: " . mysqli_error($this->conn);
        }
    }
    
    

  
    
}
?>
