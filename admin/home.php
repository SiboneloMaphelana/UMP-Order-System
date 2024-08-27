<?php
session_start();
include_once("../connection/connection.php");
include_once("model/Order.php");

$order = new Order($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <?php include('partials/sidebar.php'); ?>

    <div id="content">
        <button class="btn btn-dark d-md-none" type="button" id="toggleSidebar">
            <i class="fas fa-bars"></i>
        </button>
        <div class="container mt-4">
            <h1>Welcome to the Admin Dashboard</h1>
            <p>Your central hub for managing the application.</p>

            <!-- KPIs Overview -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Total Orders</h5>
                            <p class="card-text">Today: <?= $order->getTotalOrders('today'); ?></p>
                            <p class="card-text">This Week: <?= $order->getTotalOrders('week'); ?></p>
                            <p class="card-text">This Month: <?= $order->getTotalOrders('month'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Total Revenue</h5>
                            <p class="card-text">Today: R<?= number_format($order->getTotalRevenue('today'), 2); ?></p>
                            <p class="card-text">This Week: R<?= number_format($order->getTotalRevenue('week'), 2); ?></p>
                            <p class="card-text">This Month: R<?= number_format($order->getTotalRevenue('month'), 2); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-white mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Average Order Value</h5>
                            <p class="card-text">R<?= number_format($order->getAverageOrderValue(), 2); ?></p>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Quick Links -->
            <h2>Quick Links</h2>
            <div class="row mb-4">
                <div class="col-md-3">
                    <a class="btn btn-primary btn-block" href="add_menu.php"><i class="fas fa-plus"></i> Add Menu Item</a>
                </div>
                <div class="col-md-3">
                    <a class="btn btn-success btn-block" href="orders.php"><i class="fas fa-list"></i> View Orders</a>
                </div>
                <div class="col-md-3">
                    <a class="btn btn-warning btn-block" href="customers.php"><i class="fas fa-users"></i> Manage Users</a>
                </div>
                <div class="col-md-3">
                    <a class="btn btn-info btn-block" href="settings.php"><i class="fas fa-cogs"></i> Settings</a>
                </div>
            </div>

            <!-- Notifications -->
            <h2>Notifications</h2>
            <div id="notifications">
                <!-- Low stock alerts will be inserted here -->
                
            </div>

            <!-- Footer -->
            <footer class="footer mt-auto py-3 bg-dark text-light">
                <div class="container text-center">
                    <span>&copy; 2024 TechCafe Solutions. All rights reserved.</span>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
</body>

</html>