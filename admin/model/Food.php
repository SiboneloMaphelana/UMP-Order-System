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

        // Check if the category already exists
        $check_sql = "SELECT * FROM category WHERE name=?";
        $check_stmt = mysqli_prepare($this->conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "s", $name);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);

        if (mysqli_num_rows($check_result) > 0) {
            return "Category already exists.";
        }

        // Insert category into the database
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
        // Sanitize the input
        $categoryId = intval($this->sanitizeInput($categoryId));

        $delete_sql = "DELETE FROM category WHERE id=?";
        $delete_stmt = mysqli_prepare($this->conn, $delete_sql);
        mysqli_stmt_bind_param($delete_stmt, "i", $categoryId);
        return mysqli_stmt_execute($delete_stmt) ? true : "Error deleting category.";
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
    
    
    
    
}
?>
