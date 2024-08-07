<?php

class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Sanitizes user details by filtering out malicious input.
     *
     * @param array $data An array containing user details.
     * @return array An array with sanitized user details.
     */
    public function sanitizeUserDetails(array $data): array {
        $sanitizedData = [];
        $sanitizedData['name'] = filter_var($data['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sanitizedData['surname'] = filter_var($data['surname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sanitizedData['email'] = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $sanitizedData['role'] = filter_var($data['role'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sanitizedData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $sanitizedData;
    }
    /**
     * Check if a user with the given email exists in the database.
     *
     * @param string $email The email of the user to check.
     * @return bool Returns true if a user with the given email exists, false otherwise.
     */
    public function userExists(string $email): bool {
        $stmt = $this->conn->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['COUNT(*)'] > 0;
    }

    /**
     * Sign up a new user with the provided details.
     *
     * @param array $data An array containing the user's details (name, surname, role, email, password).
     * @return bool Returns true if the sign up is successful, false otherwise.
     */
    public function signup(array $data): bool {
        $sanitizedData = $this->sanitizeUserDetails($data);
        if ($this->userExists($sanitizedData['email'])) {
            return false;
        }
        $stmt = $this->conn->prepare('INSERT INTO users (name, surname, role, email, password) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('sssss', $sanitizedData['name'], $sanitizedData['surname'], $sanitizedData['role'] ,$sanitizedData['email'], $sanitizedData['password']);
        return $stmt->execute();
    }

    /**
     * Validates the sign-up data and returns an array of errors.
     *
     * @param array $data The sign-up data to be validated.
     *                    - 'name' (string): The first name of the user.
     *                    - 'surname' (string): The last name of the user.
     *                    - 'email' (string): The email of the user.
     *                    - 'role' (string): The role of the user.
     *                    - 'password' (string): The password of the user.
     *                    - 'confirmPassword' (string): The confirmation password of the user.
     * @return array An array containing the validation errors.
     *               - 'name' (string): Error message if the first name is empty.
     *               - 'surname' (string): Error message if the last name is empty.
     *               - 'email' (string): Error message if the email is empty or has an invalid format.
     *               - 'role' (string): Error message if the role is empty.
     *               - 'password' (string): Error message if the password is empty or less than 8 characters long.
     *               - 'confirmPassword' (string): Error message if the passwords do not match.
     */
    public function validateSignup(array $data): array {
        $errors = [];
        if (empty($data['name'])) $errors['name'] = "First Name is required.";
        if (empty($data['surname'])) $errors['surname'] = "Last Name is required.";
        if (empty($data['email'])) {
            $errors['email'] = "Email is required.";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format.";
        }

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

    /**
     * Logs in a user with the given email and password.
     *
     * @param string $email The email of the user to log in.
     * @param string $password The password of the user to log in.
     * @return bool Returns true if the login is successful, false otherwise.
     */
    public function login(string $email, string $password): bool {
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
    
    /**
     * Retrieves a user from the database by their ID.
     *
     * @param int $id The ID of the user to retrieve.
     * @return array|null An associative array representing the user, or null if no user is found.
     */
    public function getUserById( $id): ?array {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Deletes a user account by soft-deleting all their orders and the user account itself.
     *
     * @param int $userId The ID of the user to delete.
     * @throws mysqli_sql_exception If there is an error executing the SQL statements.
     * @return bool Returns true if the deletion is successful, false otherwise.
     */
    public function deleteUserAccount($userId) {
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

    /**
     * Reactivates a user account by updating the is_active column in the users table.
     *
     * @param int $userId The ID of the user to reactivate.
     * @return bool Returns true if the account is successfully reactivated, false otherwise.
     */
    public function reactivateAccount($userId): bool {
        $stmt = $this->conn->prepare("UPDATE users SET is_active = TRUE WHERE id = ?");
        $stmt->bind_param('i', $userId);
        if (!$stmt->execute()) {
            error_log("Error reactivating user account: " . $stmt->error);
            return false;
        }
        return true;
    }
    
    /**
     * Updates a user account with the provided details.
     *
     * @param int $id The ID of the user to update.
     * @param string|null $name The new name of the user (optional).
     * @param string|null $surname The new surname of the user (optional).
     * @param string|null $email The new email of the user (optional).
     * @param string|null $role The new role of the user (optional).
     * @throws mysqli_sql_exception If there is an error executing the SQL statements.
     * @return bool|string Returns true if the update is successful, an error message otherwise.
     */
    public function updateUser($id, $name = null, $surname = null, $email = null, $role = null) {
        $id = intval($id); // Sanitize the ID as an integer

        // Check if the provided email already exists for another user
        if ($email !== null && $this->emailExists($id, $email)) {
            return "Email is already registered by another user.";
        }

        // Fetch existing user details
        $existingDetails = $this->getUserById($id);

        // Retain existing details if not updated
        $name = $name ?: $existingDetails['name'];
        $surname = $surname ?: $existingDetails['surname'];
        $email = $email ?: $existingDetails['email'];
        $role = $role ?: $existingDetails['role'];

        // Sanitize parameters
        $sanitizedDetails = $this->sanitizeUserDetails(['name' => $name, 'surname' => $surname, 'email' => $email, 'role' => $role]);
        $name = $sanitizedDetails['name'];
        $surname = $sanitizedDetails['surname'];
        $email = $sanitizedDetails['email'];
        $role = $sanitizedDetails['role'];

        // Prepare SQL query
        $update_sql = "UPDATE users SET name = ?, surname = ?, email = ?, role = ? WHERE id = ?";

        // Prepare and bind parameters
        $stmt = $this->conn->prepare($update_sql);
        if (!$stmt) {
            return "Error preparing statement: " . $this->conn->error;
        }
        $stmt->bind_param('ssssi', $name, $surname, $email, $role, $id);

        // Execute the update query
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return "No rows updated.";
            }
        } else {
            return "Error updating user: " . $this->conn->error;
        }
    }
    
    /**
     * Checks if an email address already exists in the database for a user with a different ID.
     *
     * @param int $id The ID of the user to exclude from the search.
     * @param string $email The email address to search for.
     * @return bool True if the email address exists for another user, false otherwise.
     */
    private function emailExists($id, $email): bool {
        $stmt = $this->conn->prepare('SELECT COUNT(*) as count FROM users WHERE email = ? AND id <> ?');
        $stmt->bind_param('si', $email, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }

    
    
    

    
}
