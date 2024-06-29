<?php
include("login_check.php"); // Check if user is logged in
include("../../connection/connection.php"); // Establish database connection
include("Food.php"); // Include Food class

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
    header("Location: ../all_categories.php?success=Category deleted successfully"); // Redirect to all categories page with success message
} else {
    die("Error deleting category."); // If category deletion fails, display error message 
}


