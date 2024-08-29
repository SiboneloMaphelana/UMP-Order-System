<?php
session_start();
include_once("../../connection/connection.php");
include("../../model/User.php");
include("Order.php");
include("Notifications.php");

require '../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
// Define global variables for the base URL
$baseUrl = "https://a3b5-41-13-118-47.ngrok-free.app";
$payfastNotifyUrl = $baseUrl . "/UMP-Order-System/admin/model/notify.php";
$payfastReturnUrl = $baseUrl . "/UMP-Order-System/order_confirmation.php";
$payfastCancelUrl = $baseUrl . "/UMP-Order-System/index.php";

// Function to handle errors
function handleError($message, $baseUrl)
{
    $_SESSION['error'] = $message;
    header("Location: " . $baseUrl . "/UMP-Order-System/checkout.php");
    exit();
}

try {
    // Check if cart is set in session
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        throw new Exception("Cart is empty or session expired.");
    }

    // Check if the user is logged in or a guest
    $isGuest = !isset($_SESSION['id']); // Check if the user is not logged in
    $userId = $isGuest ? "" : $_SESSION['id']; // Set userId to null if guest

    // Retrieve form data
    $paymentMethod = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';
    $totalAmount = $_SESSION['subtotal'] > 0.0 ? $_SESSION['subtotal'] : 0.0;
    $cartItems = $_SESSION['cart'];

    // Retrieve phone number from session
    $guestPhone = isset($_SESSION['guest_phone']) ? $_SESSION['guest_phone'] : null;

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
        $merchantId = $_ENV['MERCHANT_ID'];
        $merchantKey = $_ENV['MERCHANT_KEY'];
        $payfastUrl = $_ENV['PAYFAST_URL'];

        // Store order in database and retrieve the orderId
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

        // Clear the cart
        unset($_SESSION['cart']);

        // Store orderId in session for later use if needed
        $_SESSION['orderId'] = $orderId;

        // PayFast payment data
        $payfastData = array(
            'merchant_id' => $merchantId,
            'merchant_key' => $merchantKey,
            'return_url' => $payfastReturnUrl,
            'cancel_url' => $payfastCancelUrl,
            'notify_url' => $payfastNotifyUrl,
            'm_payment_id' => $orderId, // Order ID from database will be used as the item name
            'amount' => number_format($totalAmount, 2, '.', ''),
            'item_name' => 'Order #' . $orderId, // Order ID from database will be used as the item name
            'item_description' => $itemDescription,
            'custom_str1' => $itemDescription,
            'custom_str2' => $userId,
        );

        // Include guest phone number only if it's a guest user
        if ($isGuest && $guestPhone) {
            $payfastData['custom_str3'] = $guestPhone;
        }

        // Generate signature for PayFast
        ksort($payfastData); // Ensure data is sorted by keys
        $signatureString = '';
        foreach ($payfastData as $key => $val) {
            $signatureString .= $key . "=" . urlencode(trim($val)) . "&";
        }
        $signatureString = rtrim($signatureString, '&'); // Remove the trailing '&'
        $signature = md5($signatureString); // Generate the signature
        $payfastData['signature'] = $signature;

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

        // Send order completion email and SMS
        if (!$isGuest) {
            // For logged-in users
            $customer = $food->getCustomerById($userId);
            $emailSent = $notifications->orderPlacementEmail($orderDetails, $customer, $orderItems);

            // Send SMS notification if phone number is available
            if ($customer['phone']) {
                $smsSent = $notifications->orderPlacementSMS($customer['phone'], $orderDetails);
                if (!$smsSent) {
                    throw new Exception("Failed to send SMS notification.");
                }
            }

            if (!$emailSent) {
                throw new Exception("Failed to send order completion email.");
            }
        } else {
            // For guests
            // Send SMS notification if phone number is available
            if ($guestPhone) {
                $smsSent = $notifications->orderPlacementSMS($guestPhone, $orderDetails);
                if (!$smsSent) {
                    throw new Exception("Failed to send SMS notification.");
                }
            }
        }

        // Clear the cart
        unset($_SESSION['cart']);

        // Store orderId in session
        $_SESSION['orderId'] = $orderId;

        // Redirect to order confirmation page
        header($baseUrl . "/UMP-Order-System/order_confirmation.php");
        exit();
    } else {
        throw new Exception("Invalid payment method.");
    }
} catch (Exception $e) {
    handleError($e->getMessage(), $baseUrl);
}
