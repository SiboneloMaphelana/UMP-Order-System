<?php
session_start();
include_once("../../connection/connection.php");
include_once("Food.php");

// Check if request method is POST and user is logged in
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize $_SESSION['cart'] if it doesn't exist or is null
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Sanitize and validate inputs
    $foodItemId = isset($_POST['foodItemId']) ? intval($_POST['foodItemId']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0.0;
    $name = isset($_POST['name']) ? $_POST['name'] : null;

    // Validate input
    if ($foodItemId <= 0 || $quantity <= 0 || $price <= 0.0 || !$name) {
        echo json_encode(['success' => false, 'message' => 'Invalid input parameters.']);
        exit;
    }

    // Check if item is already in the cart
    $itemFound = false;
    foreach ($_SESSION['cart'] as &$cartItem) {
        if ($cartItem['food_id'] == $foodItemId) {
            $cartItem['quantity'] += $quantity; 
            $itemFound = true;
            break;
        }
    }

    // If item is not found in the cart, add it
    if (!$itemFound) {
        $_SESSION['cart'][] = [
            'food_id' => $foodItemId,
            'quantity' => $quantity,
            'price' => $price,
            'name' => $name
        ];
    }

    echo json_encode(['success' => true, 'message' => 'Item added to cart successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']); //user not logged in
}
?>
