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

if ($admin->deleteCustomer($customerId)) {

    header("Location: ../customers.php");
    exit();
} else {
 
    echo "Failed to delete customer.";
}
?>
