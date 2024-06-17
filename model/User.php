<?php

class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function sanitizeUserDetails(array $data): array {
        $sanitizedData = [];
        $sanitizedData['name'] = filter_var($data['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sanitizedData['surname'] = filter_var($data['surname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sanitizedData['registration_number'] = filter_var($data['registration_number'], FILTER_SANITIZE_NUMBER_INT);
        $sanitizedData['email'] = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $sanitizedData['role'] = filter_var($data['role'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sanitizedData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $sanitizedData;
    }

    public function userExists(string $email): bool {
        $stmt = $this->conn->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
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
        $stmt = $this->conn->prepare('INSERT INTO users (name, surname, registration_number, role, email, password) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('ssisss', $sanitizedData['name'], $sanitizedData['surname'], $sanitizedData['registration_number'], $sanitizedData['role'] ,$sanitizedData['email'], $sanitizedData['password']);
        return $stmt->execute();
    }

    public function validateSignup(array $data): array {
        $errors = [];
        if (empty($data['name'])) $errors['name'] = "First Name is required.";
        if (empty($data['surname'])) $errors['surname'] = "Last Name is required.";
        if (empty($data['email'])) {
            $errors['email'] = "Email is required.";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format.";
        }
        if (empty($data['registration_number'])) $errors['registration_number'] = "Registration number is required.";

        if (empty($data['role'])) $errors['role'] = "Role is required.";
        
        if (empty($data['password'])) {
            $errors['password'] = "Password is required.";
        } elseif (strlen($data['password']) < 8) {
            $errors['password'] = "Password must be at least 8 characters long.";
        }
        if ($data['password'] !== $data['confirmPassword']) {
            $errors['confirmPassword'] = "Passwords do not match.";
        }
        return $errors;

    }

    public function login(string $email, string $password): bool {
        if (empty($email) || empty($password)) {
            return false;
        }
        $stmt = $this->conn->prepare('SELECT id, password FROM users WHERE email = ?');
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

    public function getUserById( $id): ?array {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateUser($id, $name = null, $surname = null, $email = null, $registration_number = null) {
        $id = intval($id); // Sanitize the ID as an integer directly
        
        // Check if the provided email already exists for another user
        if ($email !== null && $this->emailExists($id, $email)) {
            return "Email is already registered by another user.";
        }
    
        // Check if the provided registration number already exists for another user
        if ($registration_number !== null && $this->registrationNumberExists($id, $registration_number)) {
            return "Registration number is already registered by another user.";
        }
    
        // Fetch existing user details
        $existingDetails = $this->getUserById($id);
    
        // Retain existing details if not updated
        $name = $name ?: $existingDetails['name'];
        $surname = $surname ?: $existingDetails['surname'];
        $email = $email ?: $existingDetails['email'];
        $registration_number = $registration_number ?: $existingDetails['registration_number'];
    
        // Sanitize parameters
        $name = $this->sanitizeUserDetails(['name' => $name])['name'];
        $surname = $this->sanitizeUserDetails(['surname' => $surname])['surname'];
        $email = $this->sanitizeUserDetails(['email' => $email])['email'];
        $registration_number = $this->sanitizeUserDetails(['registration_number' => $registration_number])['registration_number'];
    
        // Prepare SQL query
        $update_sql = "UPDATE users SET name = ?, surname = ?, email = ?, registration_number = ? WHERE id = ?";
        
        // Prepare and bind parameters
        $stmt = mysqli_prepare($this->conn, $update_sql);
        if (!$stmt) {
            return "Error preparing statement: " . mysqli_error($this->conn);
        }
        mysqli_stmt_bind_param($stmt, 'ssssi', $name, $surname, $email, $registration_number, $id);
    
        // Execute the update query
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                return true;
            } else {
                return "No rows updated. Either the user does not exist or the new values are the same as the old values.";
            }
        } else {
            return "Error updating user: " . mysqli_error($this->conn);
        }
        
    }
    

    private function emailExists($id, $email): bool {
        $stmt = $this->conn->prepare('SELECT COUNT(*) FROM users WHERE email = ? AND id <> ?');
        $stmt->bind_param('si', $email, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['COUNT(*)'] > 0;
    }
    
    private function registrationNumberExists($id, $registration_number): bool {
        $stmt = $this->conn->prepare('SELECT COUNT(*) FROM users WHERE registration_number = ? AND id <> ?');
        $stmt->bind_param('ii', $registration_number, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['COUNT(*)'] > 0;
    }
    
    
    

}


