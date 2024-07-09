<?php
// Include necessary files
include("login_check.php"); // Check if user is logged in
include("../../connection/connection.php"); // Establish database connection
include("Food.php"); // Include Food class

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if category ID is set and not empty
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid categoryId."); // If category ID is not set or empty, display error message
}

$foodModel = new Food($conn);

// Get category ID from URL parameter
$categoryId = intval($_GET['id']);

// Delete category with specified ID
$deleteResult = $foodModel->deleteCategory($categoryId);

// Check if category deletion was successful
if ($deleteResult === true) {
    $_SESSION['success'] = "Category deleted successfully";
    header("Location: ../all_categories.php"); // Redirect to all categories page with success message
    exit();
} else {
    $_SESSION['error'] = "Error deleting category. Please try again.";
    header("Location: ../all_categories.php"); // Redirect to all categories page with error message
    exit();
}
?>
