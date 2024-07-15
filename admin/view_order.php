<?php
session_start();
include_once("../connection/connection.php");
include_once("model/Order.php"); // Include your Food class file

// Initialize Order class with database connection
$food = new Order($conn);

// Check if order ID is provided in the query string
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Order ID is required.";
    header("Location: manage_orders.php"); // Redirect to manage orders page if ID is missing
    exit();
}

$order_id = $_GET['id'];

// Retrieve order details based on order ID
$orderDetails = $food->getOrderById($order_id);

// Check if order exists
if (!$orderDetails) {
    $_SESSION['error'] = "Order not found.";
    header("Location: orders.php"); // Redirect to manage orders page if order not found
    exit();
}

// Retrieve order items
$orderItems = $food->getOrderItems($order_id);

// Retrieve customer details based on user_id
$customer = $food->getCustomerById($orderDetails['user_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Order #<?php echo $orderDetails['id']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/navigation.css">
</head>

<body>

    <div class="container-fluid overflow-hidden">
        <div class="row vh-100 overflow-auto">
            <?php include("partials/navigation.php"); ?>
            <div class="col d-flex flex-column h-sm-100">
                <main class="row overflow-auto">
                    <h1 class="mt-4">Order Details - Order #<?php echo $orderDetails['id']; ?></h1>

                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-file-alt me-1"></i>
                            Order Information
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Order ID:</strong> <?php echo $orderDetails['id']; ?></p>
                                    <p><strong>Customer Name:</strong> <?php echo $customer['name']; ?></p>
                                    <p><strong>Total Amount:</strong> R<?php echo number_format($orderDetails['total_amount'], 2); ?></p>
                                    <p><strong>Order Date:</strong> <?php echo $orderDetails['order_date']; ?></p>
                                    <p><strong>Status:</strong> <?php echo ucfirst($orderDetails['status']); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-shopping-cart me-1"></i>
                            Order Items
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orderItems as $item) : ?>
                                            <tr>
                                                <td><?php echo $item['name']; ?></td>
                                                <td><?php echo $item['quantity']; ?></td>
                                                <td>R<?php echo number_format($item['price'], 2); ?></td>
                                                <td>R<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <a href="orders.php" class="btn btn-primary">Back to Orders</a>
                </main>
                <footer class="row bg-light py-4 mt-auto">
                    <div class="col">WE HAVE NO FOOTER, BEING GHOSTED</div>
                </footer>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
</body>

</html>