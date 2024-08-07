<?php
class Admin
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    /**
     * Sanitizes user details by filtering out malicious input.
     *
     * @param array $data An array containing user details.
     * @return array An array with sanitized user details.
     */
    public function sanitizeUserDetails(array $data): array
    {
        $sanitizedData = [];
        $sanitizedData['name'] = filter_var($data['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sanitizedData['email'] = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $sanitizedData['phone_number'] = filter_var($data['phone_number'], FILTER_SANITIZE_NUMBER_INT);
        return $sanitizedData;
    }


    /**
     * Checks if a user with the given email already exists in the database.
     *
     * @param string $email The email of the user to check.
     * @return bool Returns true if a user with the given email already exists in the database, false otherwise.
     */
    public function userExists(string $email): bool
    {
        $stmt = $this->conn->prepare('SELECT COUNT(*) FROM admins WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['COUNT(*)'] > 0;
    }

    /**
     * Checks if a user with the given email exists in the database, excluding a specific ID.
     *
     * @param string $email The email of the user to check.
     * @param int $excludeId The ID to exclude from the search.
     * @return bool Returns true if a user with the given email exists in the database, excluding the specified ID, false otherwise.
     */
    public function emailExistsExcludingId(string $email, int $excludeId): bool
    {
        $stmt = $this->conn->prepare('SELECT COUNT(*) FROM admins WHERE email = ? AND id != ?');
        $stmt->bind_param('si', $email, $excludeId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['COUNT(*)'] > 0;
    }

    /**
     * Signs up a new admin user with the provided details.
     *
     * @param array $data An array containing the user's details (name, email, phone_number, password, role).
     * @return bool Returns true if the signup is successful, false otherwise.
     */
    public function signup(array $data): bool
    {
        $sanitizedData = $this->sanitizeUserDetails($data);
        $sanitizedData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $sanitizedData['role'] = isset($data['role']) ? $data['role'] : 'admin'; // Default role if not provided

        if ($this->userExists($sanitizedData['email'])) {
            return false; // User with this email already exists
        }

        $stmt = $this->conn->prepare('INSERT INTO admins (name, email, phone_number, password, role) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('ssiss', $sanitizedData['name'], $sanitizedData['email'], $sanitizedData['phone_number'], $sanitizedData['password'], $sanitizedData['role']);
        return $stmt->execute();
    }



    /**
     * Validates the sign-up data and returns an array of errors.
     *
     * @param array $data The sign-up data to be validated.
     *                    - 'name' (string): The name of the user.
     *                    - 'email' (string): The email of the user.
     *                    - 'phone_number' (string): The phone number of the user.
     *                    - 'password' (string): The password of the user.
     *                    - 'confirm_password' (string): The confirmation password of the user.
     *                    - 'role' (string): The role of the user (default: 'admin').
     * @return array An array containing the validation errors.
     *               - 'name' (string): Error message if the name is empty.
     *               - 'email' (string): Error message if the email is empty or has an invalid format.
     *               - 'phone_number' (string): Error message if the phone number is empty.
     *               - 'password' (string): Error message if the password is empty or less than 8 characters long.
     *               - 'confirm_password' (string): Error message if the passwords do not match.
     */
    public function validateSignup(array $data): array
    {
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
        if (empty($data['role'])) $data['role'] = 'admin';
        return $errors;
    }


    /**
     * Logs in an admin user with the provided email and password.
     *
     * @param string $email The email of the admin user.
     * @param string $password The password of the admin user.
     * @return bool Returns true if the login is successful, false otherwise.
     */
    public function login(string $email, string $password): bool
    {
        if (empty($email) || empty($password)) {
            return false;
        }
        $stmt = $this->conn->prepare('SELECT id, role, password FROM admins WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row && password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['id'] = $row['id'];
            $_SESSION['role'] = $row['role'];
            return true;
        }
        return false;
    }



    /**
     * Retrieves a user from the database by their ID.
     *
     * @param string $id The ID of the user to retrieve.
     * @return array|null The user data as an associative array, or null if no user is found.
     */
    public function getUserById(string $id): ?array
    {
        $stmt = $this->conn->prepare('SELECT * FROM admins WHERE id = ?');
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }


    /**
     * Updates an admin user with the provided data.
     *
     * @param int $id The ID of the admin user.
     * @param array $data The data to update the admin user.
     * @return bool Returns true if the update is successful, false otherwise.
     */
    public function updateAdmin(int $id, array $data): bool
    {
        if (isset($data['email']) && $this->emailExistsExcludingId($data['email'], $id)) {
            return false;
        }

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




    /**
     * Deletes an admin account from the database based on the provided ID.
     *
     * @param int $id The ID of the admin account to delete.
     * @return bool Returns true if the account is successfully deleted, false otherwise.
     */
    public function deleteAccount(int $id): bool
    {
        $stmt = $this->conn->prepare('DELETE FROM admins WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }


    /**
     * Retrieves a list of all customers from the database.
     *
     * @return array An array of customer data.
     */
    public function getAllCustomers(): array
    {
        $sql = "SELECT * FROM users WHERE is_deleted = FALSE";
        $result = $this->conn->query($sql);
        $users = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }

        return $users;
    }



    /**
     * Deletes a customer by updating the orders and users tables with a soft delete.
     *
     * @param int $customerId The ID of the customer to delete.
     * @throws mysqli_sql_exception If there is an error executing the SQL statements.
     * @return bool Returns true if the customer was successfully deleted, false otherwise.
     */
    public function deleteCustomer($customerId)
    {
        try {
            // Begin transaction
            $this->conn->begin_transaction();

            // Update orders table for soft delete
            $sqlOrders = "UPDATE orders SET is_deleted = TRUE WHERE user_id = ?";
            $stmtOrders = $this->conn->prepare($sqlOrders);
            $stmtOrders->bind_param("i", $customerId);
            $stmtOrders->execute();

            // Update users table for soft delete
            $sqlUsers = "UPDATE users SET is_deleted = TRUE WHERE id = ?";
            $stmtUsers = $this->conn->prepare($sqlUsers);
            $stmtUsers->bind_param("i", $customerId);
            $stmtUsers->execute();

            // Commit transaction
            $this->conn->commit();
            return true;
        } catch (mysqli_sql_exception $e) {
            // Rollback transaction on error
            $this->conn->rollback();
            return false;
        }
    }


    /**
     * Updates a customer's information in the database.
     *
     * @param int $customerId The ID of the customer to update.
     * @param string $name The new name of the customer.
     * @param string $surname The new surname of the customer.
     * @param string $email The new email of the customer.
     * @param string $role The new role of the customer.
     * @param string $registrationNumber The new registration number of the customer.
     * @return bool Returns true if the update was successful, false otherwise.
     */
    public function updateCustomer($customerId, $name, $surname, $email, $role)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE users SET name=?, surname=?, email=?, role=? WHERE id=?");
            $stmt->bind_param("ssssi", $name, $surname, $email, $role, $customerId);
            $stmt->execute();

            // Check if update was successful
            if ($stmt->affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            // Handle exceptions
            echo "Error: " . $e->getMessage();
            return false;
        }
    }


    /**
     * Retrieves a customer's information from the database by their ID.
     *
     * @param string $id The ID of the customer to retrieve.
     * @return array|null The customer's information as an associative array, or null if the customer is not found.
     */
    public function getCustomerById(string $id): ?array
    {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
