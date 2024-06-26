<?php
// Include backend logic to retrieve order data
$data = include('admin/model/order_confirmation.php');

// Extract order and order items from included data
$order = $data['order'];
$orderItems = $data['orderItems'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Order Confirmation</h1>
        <p>Thank you for your order! Here are your order details:</p>

        <h2>Order #<?php echo ($order['id']); ?></h2>
        <p>Order Date: <?php echo ($order['order_date']); ?></p>
        <p>Total Amount: R<?php echo (number_format($order['total_amount'], 2)); ?></p>

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
                    <td><?php echo ($item['name']); ?></td>
                    <td><?php echo ($item['quantity']); ?></td>
                    <td>R<?php echo (number_format($item['price'], 2)); ?></td>
                    <td>R<?php echo (number_format($item['price'] * $item['quantity'], 2)); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="index.php">Back to Home</a>
    </div>
</body>
</html>
