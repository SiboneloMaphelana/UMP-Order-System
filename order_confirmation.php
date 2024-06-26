<?php
session_start();
include_once("connection/connection.php");

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['id'];
$orderId = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($orderId === 0) {
    header("Location: profile.php");
    exit();
}

// Fetch order details
$orderQuery = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$orderQuery->bind_param('ii', $orderId, $userId);
$orderQuery->execute();
$orderResult = $orderQuery->get_result();
$order = $orderResult->fetch_assoc();

if (!$order) {
    $_SESSION['error'] = "Order not found.";
    header("Location: profile.php");
    exit();
}

// Fetch order items
$orderItemsQuery = $conn->prepare("SELECT oi.*, fi.name FROM order_items oi JOIN food_items fi ON oi.food_id = fi.id WHERE oi.order_id = ?");
$orderItemsQuery->bind_param('i', $orderId);
$orderItemsQuery->execute();
$orderItemsResult = $orderItemsQuery->get_result();
$orderItems = [];
while ($row = $orderItemsResult->fetch_assoc()) {
    $orderItems[] = $row;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Order Confirmation</h1>
        <p>Thank you for your order! Here are your order details:</p>

        <h2>Order #<?php echo htmlspecialchars($order['id']); ?></h2>
        <p>Order Date: <?php echo htmlspecialchars($order['order_date']); ?></p>
        <p>Total Amount: $<?php echo htmlspecialchars(number_format($order['total_amount'], 2)); ?></p>

        <h3>Order Items</h3>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderItems as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                    <td>$<?php echo htmlspecialchars(number_format($item['price'], 2)); ?></td>
                    <td>$<?php echo htmlspecialchars(number_format($item['price'] * $item['quantity'], 2)); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="profile.php">Back to Profile</a>
    </div>
</body>
</html>
