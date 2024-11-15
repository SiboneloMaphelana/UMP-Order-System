<?php

use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;

require_once __DIR__ . '/../vendor/autoload.php';
class User
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function sanitizeUserDetails(array $data): array
    {
        $sanitizedData = [];
        $sanitizedData['name'] = filter_var($data['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sanitizedData['surname'] = filter_var($data['surname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sanitizedData['email'] = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $sanitizedData['phone'] = $this->formatPhoneNumber($data['phone']); // Format phone number
        $sanitizedData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $sanitizedData;
    }

    public function formatPhoneNumber($phone): ?string
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $defaultCountryCode = 'ZA'; // Default to South Africa
            $countryCode = isset($phoneInput['countryCode']) ? $phoneInput['countryCode'] : $defaultCountryCode;
            $parsedPhone = $phoneUtil->parse($phone, $countryCode);
            if ($phoneUtil->isValidNumber($parsedPhone)) {
                return $phoneUtil->format($parsedPhone, PhoneNumberFormat::E164);
            }
        } catch (NumberParseException $e) {
            // Handle parsing error
        }
        return null; // Return null if phone number is not valid
    }




    public function userExists(string $email): bool
    {
        $stmt = $this->conn->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['COUNT(*)'] > 0;
    }

    public function signup(array $data): bool
    {
        $sanitizedData = $this->sanitizeUserDetails($data);
        if ($this->userExists($sanitizedData['email'])) {
            return false;
        }
        $stmt = $this->conn->prepare('INSERT INTO users (name, surname, phone, email, password) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('sssss', $sanitizedData['name'], $sanitizedData['surname'],  $sanitizedData['phone'], $sanitizedData['email'], $sanitizedData['password']);
        return $stmt->execute();
    }

    public function validateSignup(array $data): array
    {
        $errors = [];
        if (empty($data['name'])) $errors['name'] = "First Name is required.";
        if (empty($data['surname'])) $errors['surname'] = "Last Name is required.";
        if (empty($data['email'])) {
            $errors['email'] = "Email is required.";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format.";
        }

        if (empty($data['phone'])) $errors['phone'] = "Phone is required.";

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

    public function login(string $email, string $password): bool
    {
        if (empty($email) || empty($password)) {
            return false;
        }
        $stmt = $this->conn->prepare('SELECT id, password, is_deleted, is_active FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row) {
            if ($row['is_deleted'] || !$row['is_active']) {
                return false; // Account is deactivated or not active
            }
            if (password_verify($password, $row['password'])) {
                $_SESSION['id'] = $row['id'];
                return true;
            }
        }
        return false;
    }

    public function getUserById($id): ?array
    {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function deleteUserAccount($userId)
    {
        try {
            // Begin transaction
            $this->conn->begin_transaction();

            // Soft delete user's orders
            $sqlOrders = "UPDATE orders SET is_deleted = TRUE WHERE user_id = ?";
            $stmtOrders = $this->conn->prepare($sqlOrders);
            $stmtOrders->bind_param("i", $userId);
            $stmtOrders->execute();

            // Soft delete user account
            $sqlUsers = "UPDATE users SET is_deleted = TRUE WHERE id = ?";
            $stmtUsers = $this->conn->prepare($sqlUsers);
            $stmtUsers->bind_param("i", $userId);
            $stmtUsers->execute();

            // Commit transaction
            $this->conn->commit();
            return true;
        } catch (mysqli_sql_exception $e) {
            // Rollback transaction on error
            $this->conn->rollback();
            error_log("Error deleting user account: " . $e->getMessage());
            return false;
        }
    }

    public function reactivateAccount($email): bool
{
    $stmt = $this->conn->prepare('SELECT id, is_deleted FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Check if the account exists and is deleted
    if ($row && $row['is_deleted'] == 1) {
        // Begin transaction
        $this->conn->begin_transaction();

        try {
            // Reactivate the user account
            $stmt = $this->conn->prepare('UPDATE users SET is_deleted = 0, is_active = 1 WHERE email = ?');
            $stmt->bind_param('s', $email);
            $stmt->execute();

            // Set is_deleted to false for orders related to this user
            $userId = $row['id'];
            $stmtOrders = $this->conn->prepare('UPDATE orders SET is_deleted = 0 WHERE user_id = ?');
            $stmtOrders->bind_param('i', $userId);
            $stmtOrders->execute();

            // Commit transaction
            $this->conn->commit();
            return true;
        } catch (mysqli_sql_exception $e) {
            // Rollback transaction on error
            $this->conn->rollback();
            error_log("Error reactivating account and updating orders: " . $e->getMessage());
            return false;
        }
    }
    return false; // Account not found or already active
}



    public function updateUser($id, $name = null, $surname = null, $email = null)
    {
        $id = intval($id); // Sanitize the ID as an integer

        // Check if the provided email already exists for another user
        if ($email !== null && $this->emailExists($id, $email)) {
            return "Email is already registered by another user.";
        }

        // Fetch existing user details
        $existingDetails = $this->getUserById($id);

        // Details remain unchanged if not updated
        $name = $name ?: $existingDetails['name'];
        $surname = $surname ?: $existingDetails['surname'];
        $email = $email ?: $existingDetails['email'];

        // Sanitize parameters
        $sanitizedDetails = $this->sanitizeUserDetails(['name' => $name, 'surname' => $surname, 'email' => $email]);
        $name = $sanitizedDetails['name'];
        $surname = $sanitizedDetails['surname'];
        $email = $sanitizedDetails['email'];

        // Prepare SQL query
        $update_sql = "UPDATE users SET name = ?, surname = ?, email = ? WHERE id = ?";

        // Prepare and bind parameters
        $stmt = $this->conn->prepare($update_sql);
        if (!$stmt) {
            return "Error preparing statement: " . $this->conn->error;
        }
        $stmt->bind_param('sssi', $name, $surname, $email, $id);

        // Execute the update query
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return "No rows updated. Either the user does not exist or the new values are the same as the old values.";
            }
        } else {
            return "Error updating user: " . $this->conn->error;
        }
    }

    private function emailExists($id, $email): bool
    {
        $stmt = $this->conn->prepare('SELECT COUNT(*) as count FROM users WHERE email = ? AND id <> ?');
        $stmt->bind_param('si', $email, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }
}
