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
        $stmt = $this->conn->prepare('INSERT INTO users (name, surname, registration_number, email, password) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('ssiss', $sanitizedData['name'], $sanitizedData['surname'], $sanitizedData['registration_number'], $sanitizedData['email'], $sanitizedData['password']);
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

    public function getUserById(string $id): ?array {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

}

?>
