<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php'; // Path to PHPMailer autoload file

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
            $mail->Username   = 'maphelanasibonelo46@gmail.com'; // SMTP username
            $mail->Password   = 'qwcldigwpmfkgxrd'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            //Recipients
            $mail->setFrom('maphelanasibonelo46@gmail.com', 'TechCafe Solutions');
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
        <p>Your order <strong>#{$orderDetails['order_id']}</strong> has been placed successfully.</p>
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

    $altBody = "Dear " . htmlspecialchars($customer['name']) . ",\n\nYour order #{$orderDetails['order_id']} has been placed successfully.\n\nOrder Date: {$orderDate}\n\nOrdered Items:\n";

    foreach ($orderItems as $item) {
        $altBody .= "{$item['name']} - Quantity: {$item['quantity']} - Price: R" . number_format($item['price'], 2) . "\n";
    }

    $altBody .= "\nTotal Amount: R" . number_format($orderDetails['total_amount'], 2) . "\nPayment Method: " . htmlspecialchars($orderDetails['payment_method']) . "\n";

    return $this->sendEmail($customer['email'], $subject, $body, $altBody);
}


}
