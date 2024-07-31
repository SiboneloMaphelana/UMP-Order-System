<?php
session_start();
include_once("../../connection/connection.php");
include("../../model/User.php");
include("Order.php");
include("Notifications.php");

// Function to handle errors
function handleError($message) {
    $_SESSION['error'] = $message;
    header("Location: ../../checkout.php");
    exit();
}

try {
    // Check if user is logged in
    if (!isset($_SESSION['id'])) {
        throw new Exception("User is not logged in.");
    }

    // Check if cart is set in session
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        throw new Exception("Cart is empty or session expired.");
    }

    // Retrieve form data
    $userId = $_SESSION['id'];
    $paymentMethod = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';
    $totalAmount = $_SESSION['subtotal'] > 0.0 ? $_SESSION['subtotal'] : 0.0;
    $cartItems = $_SESSION['cart'];

    // Validate total amount
    if ($totalAmount <= 0.0) {
        throw new Exception("Invalid total amount.");
    }

    // Generate a description of the items for PayFast
    $itemDescriptions = [];
    foreach ($cartItems as $item) {
        $itemDescriptions[] = $item['name'] . ' x ' . $item['quantity'] . ' (R' . number_format($item['price'], 2) . ')';
    }
    $itemDescription = implode(', ', $itemDescriptions);

    $food = new Order($conn);
    $notifications = new Notifications($conn); 

    // Process the payment
    if ($paymentMethod == 'cash on collection') {
        // Handle Cash on Collection payment method
        $orderId = $food->addOrder($userId, $totalAmount, $paymentMethod);

        if (!$orderId) {
            throw new Exception("Error adding order.");
        }

        // Insert order items into database
        foreach ($cartItems as $item) {
            $result = $food->addOrderItem($orderId, $item['food_id'], $item['quantity'], $item['price']);
            if (!$result) {
                throw new Exception("Error adding order items.");
            }
        }

        // Retrieve order details
        $orderDetails = $food->getOrderById($orderId);
        $orderItems = $food->getOrderItems($orderId);
        $customer = $food->getCustomerById($userId);

        // Send order completion email
        $emailSent = $notifications->orderPlacementEmail($orderDetails, $customer, $orderItems);

        if (!$emailSent) {
            throw new Exception("Failed to send order completion email.");
        }

        // Clear the cart
        unset($_SESSION['cart']);

        // Store orderId in session
        $_SESSION['orderId'] = $orderId;

        // Redirect to order confirmation page
        header("Location: ../../order_confirmation.php");
        exit();
    } else {
        throw new Exception("Invalid payment method.");
    }
} catch (Exception $e) {
    handleError($e->getMessage());
}
?>
