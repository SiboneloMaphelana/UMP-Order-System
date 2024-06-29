<?php

class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Sanitizes user details by applying various filters.
     *
     * @param array $data The array containing user details to be sanitized.
     * @return array The array with sanitized user details.
     */
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

    /**
     * Checks if a user with the given email already exists in the database.
     *
     * @param string $email The email address to check for existence.
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
     * Signs up a user by inserting their details into the database.
     *
     * @param array $data An associative array containing the user's details.
     *                    The array should have the following keys:
     *                    - name: string
     *                    - surname: string
     *                    - registration_number: string
     *                    - role: string
     *                    - email: string
     *                    - password: string
     * @return bool Returns true if the user was successfully signed up, false otherwise.
     *              Returns false if the user already exists in the database.
     */
    public function signup(array $data): bool {
        $sanitizedData = $this->sanitizeUserDetails($data);
        if ($this->userExists($sanitizedData['email'])) {
            return false;
        }
        $stmt = $this->conn->prepare('INSERT INTO users (name, surname, registration_number, role, email, password) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('ssisss', $sanitizedData['name'], $sanitizedData['surname'], $sanitizedData['registration_number'], $sanitizedData['role'] ,$sanitizedData['email'], $sanitizedData['password']);
        return $stmt->execute();
    }

    /**
     * Validates the signup data and returns an array of errors.
     *
     * @param array $data The array containing the signup data.
     * @return array The array of errors.
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

    /**
     * Validates user credentials by checking the provided email and password.
     *
     * @param string $email The email of the user trying to log in.
     * @param string $password The password of the user trying to log in.
     * @return bool Returns true if the login is successful, false otherwise.
     */
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

    /**
     * Retrieves user details from the database based on the provided ID.
     *
     * @param mixed $id The unique identifier of the user.
     * @return array|null Returns an associative array of user details if found, null otherwise.
     */
    public function getUserById( $id): ?array {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Deletes a user account from the database.
     *
     * @param int $userId The ID of the user account to delete.
     * @return bool Returns true if the user account is successfully deleted, false otherwise.
     */
    public function deleteUserAccount($userId) {
        // Delete user account
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param('i', $userId);
        if (!$stmt->execute()) {
            error_log("Error deleting user account: " . $stmt->error);
            return false;
        }
        return true;
    }

    /**
     * Updates the user details with the provided ID.
     *
     * @param int $id The ID of the user to update.
     * @param string|null $name The new first name of the user. If null, retains the existing value.
     * @param string|null $surname The new last name of the user. If null, retains the existing value.
     * @param string|null $email The new email of the user. If null, retains the existing value.
     * @param string|null $registration_number The new registration number of the user. If null, retains the existing value.
     * @return bool|string Returns true if the user details were successfully updated, or an error message if the update failed.
     */
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
    

    /**
     * Checks if an email already exists in the users table for a given user ID.
     *
     * @param int $id The ID of the user to exclude from the search.
     * @param string $email The email address to check for existence.
     * @return bool Returns true if the email exists for a user other than the one specified by $id, false otherwise.
     */
    private function emailExists($id, $email): bool {
        $stmt = $this->conn->prepare('SELECT COUNT(*) FROM users WHERE email = ? AND id <> ?');
        $stmt->bind_param('si', $email, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['COUNT(*)'] > 0;
    }
    
    /**
     * Checks if a registration number already exists for a user, excluding a specific user ID.
     *
     * @param int $id The ID of the user to exclude from the check.
     * @param int $registration_number The registration number to check for.
     * @return bool Returns true if the registration number exists for another user, false otherwise.
     */
    private function registrationNumberExists($id, $registration_number): bool {
        $stmt = $this->conn->prepare('SELECT COUNT(*) FROM users WHERE registration_number = ? AND id <> ?');
        $stmt->bind_param('ii', $registration_number, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['COUNT(*)'] > 0;
    }
    
}
