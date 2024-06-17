<?php
session_start();
require_once '../../connection/connection.php';
require_once 'Admin.php';

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    echo "Unauthorized access.";
    exit();
}

// Validate incoming customer ID and data
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo "Invalid customer ID.";
    exit();
}

$customerId = intval($_POST['id']);
$name = $_POST['name'];
$surname = $_POST['surname'];
$email = $_POST['email'];
$role = $_POST['role'];
$registrationNumber = $_POST['registration_number'];

// Instantiate Admin class
$admin = new Admin($conn);

// Attempt to update customer details
if ($admin->updateCustomer($customerId, $name, $surname, $email, $role, $registrationNumber)) {
    // Redirect to customers page or display success message
    header("Location: ../customers.php");
    exit();
} else {
    // Handle update failure
    echo "Failed to update customer.";
}
?>
