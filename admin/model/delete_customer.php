<?php
session_start();
require_once '../../connection/connection.php'; 
require_once 'Admin.php'; 

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    echo "Unauthorized access.";
    exit();
}

// Validate incoming customer ID
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo "Invalid customer ID.";
    exit();
}

$customerId = intval($_POST['id']);

$admin = new Admin($conn);

// Attempt to soft delete the customer
if ($admin->deleteCustomer($customerId)) {
    // Redirect to customers page or display success message
    header("Location: ../customers.php");
    exit();
} else {
    // Handle deletion failure
    echo "Failed to delete customer.";
}
?>
