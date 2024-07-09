<?php
session_start();
require_once '../../connection/connection.php';
require_once 'Food.php';

$food = new Food($conn);

// Check if the food item ID is set in the URL
if (isset($_GET['id'])) {
    // Get the food item ID from the URL
    $foodItemId = intval($_GET['id']);

    // Delete the food item
    $result = $food->deleteFoodItem($foodItemId);

    // Check the result of the deletion
    if ($result === true) {
        // If the deletion is successful, set a success message in the session and redirect to the all menus page
        $_SESSION['success'] = "Food item deleted successfully";
    } else {
        // If there is an error, set an error message in the session
        $_SESSION['error'] = $result;
    }
} else {
    // If the food item ID is not set, set an error message in the session
    $_SESSION['error'] = "Invalid request";
}

// Redirect back to all_menus.php regardless of success or failure
header("Location: ../all_menus.php");
exit();
?>