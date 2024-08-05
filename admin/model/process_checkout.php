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
    $notifications = new Notifications($conn); // Create an instance of Notifications

    // Process the payment
    if ($paymentMethod == 'payfast') {
        // PayFast sandbox credentials
        $merchantId = '10034560';
        $merchantKey = '7ihweq67use4r';
        $payfastUrl = 'https://sandbox.payfast.co.za/eng/process';

        // PayFast payment data
        $payfastData = array(
            'merchant_id' => $merchantId,
            'merchant_key' => $merchantKey,
            'return_url' => 'http://localhost/UMP-Order-System/order_confirmation.php',
            'cancel_url' => 'http://localhost/UMP-Order-System/index.php',
            'notify_url' => 'http://localhost/UMP-Order-System/notify.php',
            'm_payment_id' => uniqid(), // Unique payment ID to identify the payment
            'amount' => number_format($totalAmount, 2, '.', ''),
            'item_name' => 'Order #' . uniqid(), // Generic order name for cases of multiple products ordered
            'item_description' => $itemDescription,
            'custom_str1' => $itemDescription,
        );

        // Generate signature for PayFast
        ksort($payfastData); // Ensure data is sorted by keys
        $signatureString = '';
        foreach ($payfastData as $key => $val) {
            $signatureString .= $key . '=' . urlencode(trim($val)) . '&';
        }
        $signatureString = rtrim($signatureString, '&');
        $signature = md5($signatureString);
        $payfastData['signature'] = $signature;

        // Store order in database
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

        // Redirect to PayFast payment page
        $queryString = http_build_query($payfastData);
        header("Location: $payfastUrl?$queryString");
        exit();
    } else if ($paymentMethod == 'cash on collection') {
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
    } else if ($paymentMethod == 'card') {
        // Handle Card payment method
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
    }
    else {
        throw new Exception("Invalid payment method.");
    }
} catch (Exception $e) {
    handleError($e->getMessage());
}
?>