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
    <title>Orders</title>
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
                    <div class="col pt-4">
                        <div class="table-container">

                            <div class="card-body">
                                <h1 class="card-title">Orders</h1>
                                
                                    <table class="table table-bordered table-striped table-hover" id="dataTable">
                                        <thead class="table-dark">
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