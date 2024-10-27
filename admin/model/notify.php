<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once("../../connection/connection.php");
include("../../model/User.php");
include("Order.php");
include("Notifications.php");

header('HTTP/1.0 200 OK');
flush();

// Ensure the script is receiving POST data from PayFast
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve PayFast data
    $payfastData = $_POST;

    $guestPhone = isset($_SESSION['guest_phone']) ? $_SESSION['guest_phone'] : '';

    // Directory and file for logging
    $logDir = '../../logs/';
    $logFile = $logDir . 'payfast_notify_log.txt';

    // Check if directory exists, if not, create it
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    // Log PayFast data to a file for debugging purposes
    $logData = "Received PayFast Notification:\n" . print_r($payfastData, true) . "\n\n";
    file_put_contents($logFile, $logData, FILE_APPEND);

    // m_payment_id is the transaction ID from PayFast
    $m_payment_id = $payfastData['m_payment_id'];

    // custom_str3 is the phone number from PayFast
    $custom_str3 = $payfastData['custom_str3'];

    // Validate PayFast's request and the payment status
    $signature = $payfastData['signature'];
    unset($payfastData['signature']);
    ksort($payfastData);

    $signatureString = '';
    foreach ($payfastData as $key => $val) {
        $signatureString .= $key . '=' . urlencode(trim($val)) . '&';
    }
    $signatureString = rtrim($signatureString, '&');
    $generatedSignature = md5($signatureString);

    if ($payfastData['payment_status'] == 'COMPLETE') {
        // The payment was successful
        $orderId = $payfastData['m_payment_id'];
        $userId = isset($payfastData['custom_str2']) ? $payfastData['custom_str2'] : '';
        $totalAmount = $payfastData['amount_gross'];

        $food = new Order($conn);
        $notifications = new Notifications($conn);

        // Update payment status to complete
        try {
            if (!$food->updatePaymentStatus($orderId, 'complete')) {
                throw new Exception("Failed to update payment status for order ID: $orderId");
            }
            $logMessage = "Payment status updated to 'complete' for order ID: $orderId\n";
            file_put_contents($logFile, $logMessage, FILE_APPEND);
        } catch (Exception $e) {
            error_log("Exception: " . $e->getMessage());
            file_put_contents($logFile, "Error: " . $e->getMessage() . "\n", FILE_APPEND);
            echo 'Failed to update payment status.';
            exit;
        }

        // Retrieve order details
        $orderDetails = $food->getOrderById($orderId);
        $orderItems = $food->getOrderItems($orderId);

        // Send order completion email and SMS
        if (!empty($userId)) {
            $customer = $food->getCustomerById($userId);
            $notifications->orderPlacementEmail($orderDetails, $customer, $orderItems);

            // Log successful email notification
            $logMessage = "Email notification sent successfully to user ID: $userId for order ID: $orderId\n";
            file_put_contents($logFile, $logMessage, FILE_APPEND);

            // Send SMS notification if phone number is available
            if (!empty($customer['phone'])) {
                $notifications->orderPlacementSMS($customer['phone'], $orderDetails);

                // Log successful SMS notification
                $logMessage = "SMS notification sent successfully to phone number: " . $customer['phone'] . " for order ID: $orderId\n";
                file_put_contents($logFile, $logMessage, FILE_APPEND);
            }
        } else {
            // For guests
            // Send SMS notification if phone number is available
            if (!empty($custom_str3)) {
                $smsSent = $notifications->orderPlacementSMS($custom_str3, $orderDetails);
                if (!$smsSent) {
                    $logMessage = "Failed to send SMS notification to guest phone number: " . $custom_str3 . " for order ID: $orderId\n";
                    file_put_contents($logFile, $logMessage, FILE_APPEND);
                    throw new Exception("Failed to send SMS notification.");
                } else {
                    $logMessage = "SMS notification sent successfully to guest phone number: " . $custom_str3 . " for order ID: $orderId\n";
                    file_put_contents($logFile, $logMessage, FILE_APPEND);
                }
            }
        }
    } else {
        // Payment was not successful
        echo 'Payment was not successful';
    }

    echo $payfastData['m_payment_id'];
}
