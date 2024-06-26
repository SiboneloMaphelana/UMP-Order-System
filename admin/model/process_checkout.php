<?php
session_start();
include_once("../../connection/connection.php");
include("../../model/User.php");
include("Food.php");

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

// Check if cart is set in session
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $_SESSION['error'] = "Cart is empty or session expired.";
    header("Location: ../../checkout.php");
    exit();
}

// Retrieve form data
$userId = $_SESSION['id'];
$paymentMethod = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';
$totalAmount = $_SESSION['subtotal'] > 0.0 ? $_SESSION['subtotal'] : 0.0;
$cartItems = $_SESSION['cart'];

// Validate total amount
if ($totalAmount <= 0.0) {
    $_SESSION['error'] = "Invalid total amount.";
    header("Location: ../../checkout.php");
    exit();
}

$food = new Food($conn);

// Process the payment
$paymentSuccess = true; 

if ($paymentSuccess) {
    // Add order to database
    $orderId = $food->addOrder($userId, $totalAmount, $paymentMethod);

    if ($orderId) {
        // Insert order items into database
        foreach ($cartItems as $item) {
            $result = $food->addOrderItem($orderId, $item['food_id'], $item['quantity'], $item['price']);
            if (!$result) {
                $_SESSION['error'] = "Error adding order items.";
                header("Location: ../../checkout.php");
                exit();
            }
        }

        // Clear the cart
        unset($_SESSION['cart']);

        // Store orderId in session
        $_SESSION['orderId'] = $orderId;

        // Redirect to order confirmation page
        header("Location: ../../order_confirmation.php");
        exit();
    } else {
        // Error adding order
        $_SESSION['error'] = "Error adding order.";
        header("Location: ../../checkout.php");
        exit();
    }
} else {
    // Payment failed
    $_SESSION['error'] = "Payment failed. Please try again.";
    header("Location: ../../checkout.php");
    exit();
}
?>
