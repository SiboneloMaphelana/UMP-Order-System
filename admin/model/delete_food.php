<?php
require_once '../../connection/connection.php';
require_once 'Food.php';

$food = new Food($conn);

// Get the food item ID from the URL
if (isset($_GET['id'])) {
    $foodItemId = intval($_GET['id']);

    // Delete the food item
    $result = $food->deleteFoodItem($foodItemId);

    if ($result === true) {
        header("Location: ../all_menus.php?msg=Food item deleted successfully");
    } else {
        // Redirect to the page with an error message
        header("Location: ../all_menus.php?msg=" . urlencode($result));
    }
} else {
    header("Location: ../all_menus.php?msg=Invalid request");
}
exit;
?>
