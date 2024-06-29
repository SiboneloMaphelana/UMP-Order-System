<?php
class Admin {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Sanitizes the user details by filtering and sanitizing the input data.
     *
     * @param array $data The user details to sanitize.
     * @return array The sanitized user details.
     */
    public function sanitizeUserDetails(array $data): array {
        $sanitizedData = [];
        $sanitizedData['name'] = filter_var($data['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sanitizedData['email'] = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $sanitizedData['phone_number'] = filter_var($data['phone_number'], FILTER_SANITIZE_NUMBER_INT);
        return $sanitizedData;
    }

    /**
     * Checks if the user with the given email exists in the `admins` table.
     *
     * @param string $email The email to check.
     * @return bool Returns true if the user exists, false otherwise.
     */
    public function userExists(string $email): bool {
        $stmt = $this->conn->prepare('SELECT COUNT(*) FROM admins WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['COUNT(*)'] > 0;
    }

    /**
     * Checks if an email exists in the `admins` table, excluding a specific ID.
     *
     * @param string $email The email to check.
     * @param int $excludeId The ID to exclude from the check.
     * @return bool Returns `true` if the email exists, `false` otherwise.
     */
    public function emailExistsExcludingId(string $email, int $excludeId): bool {
        $stmt = $this->conn->prepare('SELECT COUNT(*) FROM admins WHERE email = ? AND id != ?');
        $stmt->bind_param('si', $email, $excludeId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['COUNT(*)'] > 0;
    }

    /**
     * Signs up a new admin user.
     *
     * @param array $data The signup data containing the user's name, email, phone number, and password.
     * @return bool Returns true if the user is successfully signed up, false otherwise.
     */
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
    

    /**
     * Validates the signup data and returns an array of errors if any.
     *
     * @param array $data The signup data to be validated.
     *                    The array should contain the following keys:
     *                    - 'name': The name of the user.
     *                    - 'email': The email of the user.
     *                    - 'phone_number': The phone number of the user.
     *                    - 'password': The password of the user.
     *                    - 'confirm_password': The confirmed password of the user.
     * @return array An array of errors.
     *               The array keys represent the field names with errors,
     *               and the values represent the corresponding error messages.
     *               If there are no errors, an empty array is returned.
     */
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
    

    /**
     * Logs in an admin user with the provided email and password.
     *
     * @param string $email The email of the admin user.
     * @param string $password The password of the admin user.
     * @return bool Returns true if the login is successful, false otherwise.
     */
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

    /**
     * Retrieves a user from the database by their ID.
     *
     * @param string $id The ID of the user to retrieve.
     * @return array|null The user data as an associative array, or null if no user was found.
     */
    public function getUserById(string $id): ?array {
        $stmt = $this->conn->prepare('SELECT * FROM admins WHERE id = ?');
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Updates an admin record in the database.
     *
     * @param int $id The ID of the admin to update.
     * @param array $data An associative array containing the updated data for the admin.
     *                     The keys of the array should match the column names in the admins table.
     *                     The values can be either strings or null.
     * @return bool Returns true if the update was successful, false otherwise.
     *              If the email already exists for another admin except the one being updated, returns false.
     * @throws None
     */
    public function updateAdmin(int $id, array $data): bool {
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
     * Deletes an account from the admins table in the database.
     *
     * @param int $id The ID of the account to delete.
     * @return bool Returns true if the deletion was successful, false otherwise.
     */
    public function deleteAccount(int $id): bool {
        $stmt = $this->conn->prepare('DELETE FROM admins WHERE id = ?');
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    /**
     * Retrieves all customers from the database.
     *
     * @return array An array of customer data in the format of associative arrays.
     */
    public function getAllCustomers(): array {
        $stmt = $this->conn->prepare('SELECT * FROM users');
        $stmt->execute();
        $result = $stmt->get_result();
        $customers = $result->fetch_all(MYSQLI_ASSOC);
        return $customers;
    }

    /**
     * Deletes a customer from the users table in the database.
     *
     * @param int $customerId The ID of the customer to delete.
     * @return bool Returns true if the deletion was successful, false otherwise.
     */
    public function deleteCustomer($customerId) {
        $sql = "DELETE FROM users WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $customerId); 
        
        if ($stmt->execute()) {
            return true; 
        } else {
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
    public function updateCustomer($customerId, $name, $surname, $email, $role, $registrationNumber) {
        try {
            $stmt = $this->conn->prepare("UPDATE users SET name=?, surname=?, email=?, role=?, registration_number=? WHERE id=?");
            $stmt->bind_param("sssssi", $name, $surname, $email, $role, $registrationNumber, $customerId);
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
     * Retrieves a customer from the database by their ID.
     *
     * @param string $id The ID of the customer to retrieve.
     * @return array|null The customer data as an associative array, or null if no customer was found.
     */
    public function getCustomerById(string $id): ?array {
        $stmt = $this->conn->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>
