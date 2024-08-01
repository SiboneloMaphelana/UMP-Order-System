<?php
class Food
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

    private function checkDbConnection()
    {
        if (!$this->conn) {
            throw new Exception("Database connection failed: " . mysqli_connect_error());
        }
    }

    public function addCategory($name, $imagePath)
    {
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



    public function getCategories()
    {
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

    public function deleteCategory($categoryId)
    {
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
            return false;
        }
    }


    public function updateCategory($id, $name = null, $imageName = null)
    {
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


    public function isCategoryExists($categoryId)
    {
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


    public function getCategoryById($categoryId)
    {
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


    public function addFoodItem($name, $description, $categoryId, $quantity, $price, $image, $adminId)
    {
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


    public function getAllFoodItems()
    {
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

    public function foodItemExists($id)
    {
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

    public function deleteFoodItem($foodItemId)
    {
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


    public function getFoodItemById($foodItemId)
    {
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

    public function getFoodItemsByCategoryId($categoryId)
    {
        // Sanitize the input
        $categoryId = intval($this->sanitizeInput($categoryId));

        // SQL query with additional WHERE clause for quantity > 10
        $select_sql = "SELECT * FROM food_items WHERE category_id=? AND quantity > 10";
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


    public function updateFoodItem($id, $name = null, $quantity = null, $price = null, $description = null, $image = null, $category = null)
    {
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

    public function getFavorites()
    {
        $query = "SELECT f.id, f.name, f.description, f.image, COUNT(oi.food_id) AS order_count
                  FROM food_items f
                  JOIN order_items oi ON f.id = oi.food_id
                  GROUP BY f.id, f.name, f.description, f.image
                  ORDER BY order_count DESC
                  LIMIT 3";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $result = $stmt->get_result();
        $favorites = [];

        while ($row = $result->fetch_assoc()) {
            $favorites[] = $row;
        }

        return $favorites;
    }

    public function searchFoodItems($searchTerm)
    {
        // Sanitize the search term
        $searchTerm = $this->sanitizeInput($searchTerm);
        $searchTerm = "%" . $searchTerm . "%"; // Add wildcards for partial matching

        try {
            // Prepare the SQL query
            $sql = "SELECT * FROM food_items WHERE name LIKE ? OR description LIKE ?";
            $stmt = $this->conn->prepare($sql);

            // Bind the search term to the placeholders
            $stmt->bind_param("ss", $searchTerm, $searchTerm);

            // Execute the query
            if ($stmt->execute()) {
                // Get the result
                $result = $stmt->get_result();
                $foodItems = $result->fetch_all(MYSQLI_ASSOC);
                return $foodItems;
            } else {
                throw new Exception("Error executing search query: " . $stmt->error);
            }
        } catch (Exception $e) {
            error_log("Exception caught: " . $e->getMessage());
            return [];
        }
    }
}
