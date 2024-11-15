<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$data = include_once('admin/model/order_confirmation.php');

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/new.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <div class="container-fluid overflow-hidden">
        <div class="row vh-100 overflow-auto">
            <?php include_once("partials/navigation.php"); ?>
            <div class="col d-flex flex-column h-sm-100">
                <main class="row overflow-auto">
                    <div class="col-lg-8 mx-auto">
                        <div class="text-center my-4">
                            <h1 class="mb-3">Order Confirmation</h1>
                            <p class="lead">Thank you for your order! Here are your order details:</p>
                        </div>
                        <div class="card mb-4">
                            <div class="card-body">
                                <h2 class="card-title">Order #<?php echo $order['id']; ?></h2>
                                <p>Order Date: <?php echo $order['order_date']; ?></p>
                                <p>Total Amount: R<?php echo number_format($order['total_amount'], 2); ?></p>
                            </div>
                        </div>

                        <div class="card">
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
                                                    <td>
                                                        <?php
                                                        // Check if it's a food item or a special
                                                        if (!empty($item['food_name'])) {
                                                            echo htmlspecialchars($item['food_name']); 
                                                        } elseif (!empty($item['special_name'])) {
                                                            echo htmlspecialchars($item['special_name']); 
                                                        } else {
                                                            echo "Unknown Item";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                                    <td>R<?php echo number_format($item['price'], 2); ?></td>
                                                    <td>R<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                                    <td><?php echo ucfirst(htmlspecialchars($item['status'])); ?></td>
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
                </main>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>