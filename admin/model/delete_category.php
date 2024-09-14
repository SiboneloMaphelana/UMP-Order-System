<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("login_check.php");
include("../../connection/connection.php");
include("Food.php");




if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid categoryId.");
}

$foodModel = new Food($conn);

$categoryId = intval($_GET['id']);

$deleteResult = $foodModel->deleteCategory($categoryId);


if ($deleteResult === true) {
    $_SESSION['success'] = "Category deleted successfully";
    header("Location: ../all_categories.php");
    exit();
} else {
    $_SESSION['error'] = "Error deleting category. Please try again.";
    header("Location: ../all_categories.php");
    exit();
}
