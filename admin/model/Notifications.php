<?php

use Infobip\Configuration;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsAdvancedTextualRequest;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

class Notifications
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    private function sendEmail($to, $subject, $body, $altBody)
    {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['EMAIL_USERNAME']; // SMTP username
            $mail->Password   = $_ENV['EMAIL_PASSWORD']; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            //Recipients
            $mail->setFrom($_ENV['EMAIL_USERNAME'], 'TechCafe Solutions');
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = $altBody;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public function orderPlacementSMS($phone, $orderDetails)
{
    // Check if phone and orderDetails are valid
    if (is_null($phone) || empty($phone)) {
        error_log("Error: Phone number is missing.");
        return false;
    }

    if (is_null($orderDetails) || !isset($orderDetails['id'])) {
        error_log("Error: Order details are missing.");
        return false;
    }

    $messageText = "Thank you for your order. Your order number is " . $orderDetails['id']. ". Your order will be ready in 15 minutes.";

    // Environment configurations
    $base_url = $_ENV['BASE_URL'];
    $api_key = $_ENV['API_KEY'];

    try {
        // SMS API
        $config = new Configuration(host: $base_url, apiKey: $api_key);
        $api = new SmsApi($config);

        // destination and message
        $destination = new SmsDestination(to: $phone);
        $message = new SmsTextualMessage(
            destinations: [$destination],
            text: $messageText
        );

        // Create and send the request
        $request = new SmsAdvancedTextualRequest(messages: [$message]);
        $response = $api->sendSmsMessage($request);

        // Check if the response indicates success
        if ($response) {
            return true;
        } else {
            error_log("SMS  Response: " . print_r($response, true));
            return false;
        }
    } catch (Exception $e) {
        error_log("Exception occurred: " . $e->getMessage());
        return false;
    }
}


    public function orderCompletionEmail($orderDetails, $customer, $orderItems)
    {
        $subject = 'Order Completed';

        $completedAt = date('F j, Y, g:i A', strtotime($orderDetails['completed_at']));
        $orderDate = date('F j, Y, g:i A', strtotime($orderDetails['order_date']));

        $body = "
    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #dddddd; border-radius: 10px; background-color: #f9f9f9;'>
        <div style='text-align: center;'>
            <h2 style='color: #004080;'>TechCafe Solutions</h2>
        </div>
        <p>Dear " . htmlspecialchars($customer['name']) . ",</p>
        <p>Your order <strong>#{$orderDetails['id']}</strong> has been completed.</p>
        <p><strong>Order Details:</strong></p>
        <ul style='list-style-type: none; padding: 0;'>
            <li style='margin-bottom: 10px;'><strong style='color: #004080;'>Order Date:</strong> {$orderDate}</li>
            <li style='margin-bottom: 10px;'><strong style='color: #004080;'>Completed At:</strong> {$completedAt}</li>
        </ul>
        <p><strong>Ordered Items:</strong></p>
        <table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse;'>
            <thead style='background-color: #004080; color: #ffffff;'>
                <tr>
                    <th style='padding: 10px;'>Food Item</th>
                    <th style='padding: 10px;'>Quantity</th>
                    <th style='padding: 10px;'>Price</th>
                </tr>
            </thead>
            <tbody>";

        foreach ($orderItems as $item) {
            $body .= "<tr>
            <td style='text-align: center; padding: 10px; border-bottom: 1px solid #dddddd;'>" . htmlspecialchars($item['name']) . "</td>
            <td style='text-align: center; padding: 10px; border-bottom: 1px solid #dddddd;'>{$item['quantity']}</td>
            <td style='text-align: center; padding: 10px; border-bottom: 1px solid #dddddd;'>R" . number_format($item['price'], 2) . "</td>
        </tr>";
        }

        $body .= "</tbody>
        </table>
        <p><strong style='color: #004080;'>Total Amount:</strong> R" . number_format($orderDetails['total_amount'], 2) . "</p>
        <p><strong style='color: #004080;'>Payment Method:</strong> " . htmlspecialchars($orderDetails['payment_method']) . "</p>
        <p style='text-align: center; color: #004080;'>Thank you for shopping with us!</p>
        <p style='text-align: center; color: #004080;'>Best Regards,<br>TechCafe Solutions</p>
    </div>";

        $altBody = "Dear " . htmlspecialchars($customer['name']) . ",\n\nYour order #{$orderDetails['order_id']} has been completed.\n\nOrder Date: {$orderDate}\nCompleted At: {$completedAt}\n\nOrdered Items:\n";

        foreach ($orderItems as $item) {
            $altBody .= "{$item['name']} - Quantity: {$item['quantity']} - Price: R" . number_format($item['price'], 2) . "\n";
        }

        $altBody .= "\nTotal Amount: R" . number_format($orderDetails['total_amount'], 2) . "\nPayment Method: " . htmlspecialchars($orderDetails['payment_method']) . "\n";

        return $this->sendEmail($customer['email'], $subject, $body, $altBody);
    }

    public function orderPlacementEmail($orderDetails, $customer, $orderItems)
    {
        $subject = 'Order Placed';

        $orderDate = date('F j, Y, g:i A', strtotime($orderDetails['order_date']));

        $body = "
    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #dddddd; border-radius: 10px; background-color: #f9f9f9;'>
        <div style='text-align: center;'>
            <h2 style='color: #004080;'>TechCafe Solutions</h2>
        </div>
        <p>Dear " . htmlspecialchars($customer['name']) . ",</p>
        <p>Your order <strong>#{$orderDetails['id']}</strong> has been placed successfully.</p>
        <p><strong>Order Details:</strong></p>
        <ul style='list-style-type: none; padding: 0;'>
            <li style='margin-bottom: 10px;'><strong style='color: #004080;'>Order Date:</strong> {$orderDate}</li>
        </ul>
        <p><strong>Ordered Items:</strong></p>
        <table cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse;'>
            <thead style='background-color: #004080; color: #ffffff;'>
                <tr>
                    <th style='padding: 10px;'>Food Item</th>
                    <th style='padding: 10px;'>Quantity</th>
                    <th style='padding: 10px;'>Price</th>
                </tr>
            </thead>
            <tbody>";

        foreach ($orderItems as $item) {
            $body .= "<tr>
            <td style='text-align: center; padding: 10px; border-bottom: 1px solid #dddddd;'>" . htmlspecialchars($item['name']) . "</td>
            <td style='text-align: center; padding: 10px; border-bottom: 1px solid #dddddd;'>{$item['quantity']}</td>
            <td style='text-align: center; padding: 10px; border-bottom: 1px solid #dddddd;'>R" . number_format($item['price'], 2) . "</td>
        </tr>";
        }

        $body .= "</tbody>
        </table>
        <p><strong style='color: #004080;'>Total Amount:</strong> R" . number_format($orderDetails['total_amount'], 2) . "</p>
        <p><strong style='color: #004080;'>Payment Method:</strong> " . htmlspecialchars($orderDetails['payment_method']) . "</p>
        <p style='text-align: center; color: #004080;'>Thank you for shopping with us!</p>
        <p style='text-align: center; color: #004080;'>Best Regards,<br>TechCafe Solutions</p>
    </div>";

        $altBody = "Dear " . htmlspecialchars($customer['name']) . ",\n\nYour order #{$orderDetails['id']} has been placed successfully.\n\nOrder Date: {$orderDate}\n\nOrdered Items:\n";

        foreach ($orderItems as $item) {
            $altBody .= "{$item['name']} - Quantity: {$item['quantity']} - Price: R" . number_format($item['price'], 2) . "\n";
        }

        $altBody .= "\nTotal Amount: R" . number_format($orderDetails['total_amount'], 2) . "\nPayment Method: " . htmlspecialchars($orderDetails['payment_method']) . "\n";

        return $this->sendEmail($customer['email'], $subject, $body, $altBody);
    }

    function generateToken($length = 50)
    {
        return bin2hex(random_bytes($length));
    }

    public function sendPasswordResetEmail($user)
    {

        $token = $this->generateToken();
        $userId = $user['id'];

        // Insert token into database
        $stmt = $this->conn->prepare("INSERT INTO password_resets (user_id, token) VALUES (?, ?)");
        $stmt->bind_param("is", $userId, $token);
        $stmt->execute();
        $stmt->close();

        $resetLink = "http://localhost/UMP-Order-System/reset_password.php?token=" . $token;

        $subject = "Password Reset Request";
        $body = "
    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #dddddd; border-radius: 10px; background-color: #f9f9f9;'>
        <div style='text-align: center;'>
            <h2 style='color: #004080;'>TechCafe Solutions</h2>
        </div>
        <p>Dear " . htmlspecialchars($user['name']) . ",</p>
        <p>You requested a password reset. Click the link below to reset your password:</p>
        <p><a href='" . $resetLink . "' style='color: #004080;'>Reset Password</a></p>
        <p>If you did not request a password reset, please ignore this email.</p>
        <p style='text-align: center; color: #004080;'>Best Regards,<br>TechCafe Solutions</p>
    </div>";

        $altBody = "Dear " . htmlspecialchars($user['name']) . ",\n\nYou requested a password reset. Click the link below to reset your password:\n" . $resetLink . "\n\nIf you did not request a password reset, please ignore this email.\n\nBest Regards,\nTechCafe Solutions";

        return $this->sendEmail($user['email'], $subject, $body, $altBody);
    }
}
