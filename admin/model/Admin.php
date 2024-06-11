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
        $sanitizedData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
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

    public function updateAdmin(int $id, array $data): bool {
        $sanitizedData = $this->sanitizeUserDetails($data);
        $setValues = [];
        $params = [];
        $paramTypes = '';

        foreach ($sanitizedData as $key => $value) {
            if ($value !== null) {
                $setValues[] = "$key = ?";
                $params[] = $value;
                $paramTypes .= 's';
            }
        }

        if (empty($setValues)) {
            return false;
        }

        $updateSql = "UPDATE admins SET " . implode(', ', $setValues) . " WHERE id = ?";
        $params[] = $id;
        $paramTypes .= 'i';

        $stmt = $this->conn->prepare($updateSql);
        if (!$stmt) {
            error_log("Prepare failed: (" . $this->conn->errno . ") " . $this->conn->error);
            return false;
        }

        $stmt->bind_param($paramTypes, ...$params);

        if (!$stmt->execute()) {
            error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            return false;
        }

        return true;
    }
}
?>

