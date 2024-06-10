<?php
include("login_check.php"); 
include("../../connection/connection.php");
include("Food.php");

// Check if category Id is set
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid categoryId.");
}


$foodModel = new Food($conn);

$categoryId = intval($_GET['id']);
$deleteResult = $foodModel->deleteCategory($categoryId);

if ($deleteResult === true) {
    header("Location: ../all_categories.php?success=Category deleted successfully");
} else {
    die("Error deleting category.");
}
?>
