<?php
session_start();
include_once("../../connection/connection.php"); // Adjust path as per your file structure
include_once("Food.php"); // Adjust path as per your file structure

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['id'])) {
    $foodItemId = isset($_POST['foodItemId']) ? intval($_POST['foodItemId']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0.0;
    $customerId = $_SESSION['id']; // Assuming you store customer ID in session

    // Validate input (you can add more validation as needed)
    if ($foodItemId <= 0 || $quantity <= 0 || $price <= 0.0) {
        echo "Invalid input parameters.";
        exit;
    }

    // Add item to cart (you'll need to implement this method in your Food class)
    $food = new Food($conn); // Assuming $conn is your database connection
    $result = $food->addToCart($customerId, $foodItemId, $quantity, $price);

    // Handle result and send response back to AJAX function
    if ($result === true) {
        echo "Item added to cart successfully.";
    } else {
        echo "Failed to add item to cart: " . $result;
    }
} else {
    echo "Unauthorized access."; // Handle cases where user is not logged in
}
?>
