<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Validate input
if (isset($_POST['index']) && isset($_POST['quantity']) && isset($_POST['action'])) {
    $index = $_POST['index'];
    $quantity = $_POST['quantity'];
    $action = $_POST['action']; // 'increase' or 'decrease'

    // Ensure quantity is a positive integer
    $quantity = intval($quantity);
    if ($quantity <= 0) {
        $_SESSION['error'] = "Invalid quantity.";
        header("Location: cart.php");
        exit();
    }

    // Retrieve cart from session
    $cartItems = $_SESSION['cart'];

    // Update quantity based on action
    if ($action === 'increase') {
        $cartItems[$index]['quantity']++;
    } elseif ($action === 'decrease') {
        if ($cartItems[$index]['quantity'] > 1) {
            $cartItems[$index]['quantity']--;
        }
    }

    // Update session cart with modified item
    $_SESSION['cart'] = $cartItems;

    // Redirect back to cart page
    header("Location: cart.php");
    exit();
} else {
    // Redirect if parameters are not set
    header("Location: cart.php");
    exit();
}
?>
