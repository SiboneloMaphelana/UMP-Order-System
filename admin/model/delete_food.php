<?php
session_start(); // Start the session

require_once '../../connection/connection.php';
require_once 'Food.php';

$food = new Food($conn);

// Get the food item ID from the URL
if (isset($_GET['id'])) {
    $foodItemId = intval($_GET['id']);

    // Delete the food item
    $result = $food->deleteFoodItem($foodItemId);

    if ($result === true) {
        $_SESSION['success'] = "Food item deleted successfully"; // Set success message
        header("Location: ../all_menus.php"); // Redirect to all_menus.php
    } else {
        $_SESSION['error'] = $result; // Set error message
        header("Location: ../all_menus.php"); // Redirect to all_menus.php
    }
} else {
    $_SESSION['error'] = "Invalid request"; // Set error message
    header("Location: ../all_menus.php"); // Redirect to all_menus.php
}
exit;
?>
