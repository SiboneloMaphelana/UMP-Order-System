<?php

class Admin {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function sanitizeUserDetails(array $data): array {
        $sanitizedData = [];
        $sanitizedData['name'] = filter_var($data['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sanitizedData['email'] = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $sanitizedData['phone_number'] = filter_var($data['phone_number'], FILTER_SANITIZE_NUMBER_INT);
        $sanitizedData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $sanitizedData;
    }

    public function userExists(string $email): bool {
        $stmt = $this->conn->prepare('SELECT COUNT(*) FROM admins WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['COUNT(*)'] > 0;
    }

    public function signup(array $data): bool {
        $sanitizedData = $this->sanitizeUserDetails($data);
        if ($this->userExists($sanitizedData['email'])) {
            return false;
        }
        $stmt = $this->conn->prepare('INSERT INTO admins (name, email, phone_number, password) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssis', $sanitizedData['name'], $sanitizedData['email'], $sanitizedData['phone_number'], $sanitizedData['password']);
        return $stmt->execute();
    }

    public function validateSignup(array $data): array {
        $errors = [];
        if (empty($data['name'])) $errors['name'] = "Name is required.";
        if (empty($data['email'])) {
            $errors['email'] = "Email is required.";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format.";
        }
        if (empty($data['phone_number'])) $errors['phone_number'] = "Phone number is required.";
        if (empty($data['password'])) {
            $errors['password'] = "Password is required.";
        } elseif (strlen($data['password']) < 8) {
            $errors['password'] = "Password must be at least 8 characters long.";
        }
        if ($data['password'] !== $data['confirm_password']) {
            $errors['confirm_password'] = "Passwords do not match.";
        }
        return $errors;
    }

    public function login(string $email, string $password): bool {
        if (empty($email) || empty($password)) {
            return false;
        }
        $stmt = $this->conn->prepare('SELECT id, password FROM admins WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row && password_verify($password, $row['password'])) {
            $_SESSION['id'] = $row['id'];
            return true;
        }
        return false;
    }

    public function getUserById(string $id): ?array {
        $stmt = $this->conn->prepare('SELECT * FROM admins WHERE id = ?');
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateAdmin($id, $name = null, $email = null, $phone_number = null){
        $id = intval($this->sanitizeUserDetails($id));
        $name = $name ? $this->sanitizeUserDetails($name) : null;
        $email = $email ? $this->sanitizeUserDetails($email) : null;
        $phone_number = $phone_number ? $this->sanitizeUserDetails($phone_number) : null;
    
        $update_sql = "UPDATE admins SET ";
        $set_values = [];
        $params = [];
        $param_types = '';
    
        if ($name !== null) {
            $set_values[] = "name = ?";
            $params[] = $name;
            $param_types .= 's';
        }
    
        if ($email !== null) {
            $set_values[] = "email = ?";
            $params[] = $email;
            $param_types .= 's';
        }

        if ($phone_number != null){
            $set_values[] = "phone_number = ?";
            $params[] = $phone_number;
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
       
    
    

     
    
}
?>
