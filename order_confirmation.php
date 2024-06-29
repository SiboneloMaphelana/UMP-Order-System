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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .order-summary {
            margin-top: 50px;
        }
        .order-details {
            margin-bottom: 50px;
        }
        .order-items {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center">
                    <img src="images/confirm_order.png" alt="Order Confirmation" class="img-fluid my-4">
                    <h1 class="mb-3">Order Confirmation</h1>
                    <p class="lead">Thank you for your order! Here are your order details:</p>
                </div>
                <div class="card order-summary">
                    <div class="card-body">
                        <h2 class="card-title">Order #<?php echo $order['id']; ?></h2>
                        <p>Order Date: <?php echo $order['order_date']; ?></p>
                        <p>Total Amount: R<?php echo number_format($order['total_amount'], 2); ?></p>
                    </div>
                </div>

                <div class="card order-details mt-4">
                    <div class="card-body">
                        <h3 class="card-title">Order Items</h3>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orderItems as $item): ?>
                                        <tr>
                                            <td><?php echo $item['name']; ?></td>
                                            <td><?php echo $item['quantity']; ?></td>
                                            <td>R<?php echo number_format($item['price'], 2); ?></td>
                                            <td>R<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                            <td><?php echo ucfirst($item['status']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="index.php" class="btn btn-primary">Back to Home</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
