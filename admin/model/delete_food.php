<?php
// Start the session
session_start();

// Include the necessary files
require_once '../../connection/connection.php';
require_once 'Food.php';

// Create a new instance of the Food class
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
        header("Location: ../all_menus.php");
    } else {
        // If there is an error, set an error message in the session and redirect to the all menus page
        $_SESSION['error'] = $result;
        header("Location: ../all_menus.php");
    }
} else {
    // If the food item ID is not set, set an error message in the session and redirect to the all menus page
    $_SESSION['error'] = "Invalid request";
    header("Location: ../all_menus.php");
}

// End the script
exit;
?>

