<?php
include("model/login_check.php");
require_once '../connection/connection.php';
require_once 'model/ReportingAndAnalytics.php';

// Initialize the ReportingAndAnalytics class
$reporting = new ReportingAndAnalytics($conn);

// Fetch data using the methods
$totalOrdersCount = $reporting->getTotalOrders();
$totalEarnings = $reporting->getTotalEarnings();
$orderCountsByStatus = $reporting->getOrderCountsByStatus();
$ordersPerMonth = $reporting->getOrdersPerMonth();
$totalCustomersCount = $reporting->getTotalCustomers();
$averageOrderValue = $reporting->getAverageOrderValue();
$totalCanceledOrdersCount = $reporting->getTotalCanceledOrders();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<header class="header">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="logo">Admin Dashboard</h1>
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="#">Settings</a></li>
                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="model/logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</header>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger" role="alert"><?php echo $_SESSION['error']; ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="container-fluid mt-4">
    <div class="row">

        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="sidebar">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="customers.php">
                            <i class="fas fa-users"></i> Customers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="orders.php">
                            <i class="fas fa-clipboard-list"></i> Orders
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="menuDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-utensils"></i> Menu
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="menuDropdown">
                            <li><a class="dropdown-item" href="all_categories.php">All Categories</a></li>
                            <li><a class="dropdown-item" href="add_category.php">Add Category</a></li>
                            <li><a class="dropdown-item" href="add_menu.php">Add Menu</a></li>
                            <li><a class="dropdown-item" href="all_menus.php">All Menus</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="col-md-9">
            <div class="container">
                <div class="row g-4">
                    <!-- Total Orders Section -->
                    <div class="col-md-6">
                        <div class="card border-primary">
                            <div class="card-body">
                                <h5 class="card-title">Total Orders</h5>
                                <p class="card-text"><?php echo $totalOrdersCount; ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Earnings Section -->
                    <div class="col-md-6">
                        <div class="card border-success">
                            <div class="card-body">
                                <h5 class="card-title">Total Earnings</h5>
                                <p class="card-text">$<?php echo number_format($totalEarnings, 2); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Counts by Status Section -->
                    <div class="col-md-6">
                        <div class="card border-info">
                            <div class="card-body">
                                <h5 class="card-title">Order Counts by Status</h5>
                                <ul class="list-group">
                                    <?php foreach ($orderCountsByStatus as $status => $count): ?>
                                        <li class="list-group-item"><?php echo ucfirst($status); ?>: <?php echo $count; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <p>&copy; 2024 Admin Dashboard</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="admin.js"></script>

</body>
</html>

