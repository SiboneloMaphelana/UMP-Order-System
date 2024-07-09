<?php
session_start();
include_once("../connection/connection.php");
include_once("model/Order.php"); 

$order = new Order($conn);

$orders = $order->getAllOrders();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container-fluid">
    <h1 class="mt-4">Manage Orders</h1>
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-table me-1"></i>
            Orders List
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User ID</th>
                            <th>Total Amount</th>
                            <th>Order Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order) : ?>
                            <tr>
                                <td><?php echo $order['id']; ?></td>
                                <td><?php echo $order['user_id']; ?></td>
                                <td>R<?php echo number_format($order['total_amount'], 2); ?></td>
                                <td><?php echo $order['order_date']; ?></td>
                                <td><?php echo strtoupper($order['status']); ?></td>
                                <td>
                                    <a href="view_order.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-primary">View Details</a>
                                    <a href="update_order.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-warning">Update Status</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
