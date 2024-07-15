<?php
include("model/login_check.php");
require_once '../connection/connection.php';
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

<header class="navbar navbar-dark bg-dark sticky-top flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Admin Dashboard</a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
    <div class="navbar-nav">
        <div class="nav-item text-nowrap">
            <a class="nav-link px-3" href="model/logout.php">Sign out</a>
        </div>
    </div>
</header>

<div class="container-fluid mt-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="sidebar">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="customers.php">
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
                    <!-- Users Section -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card border-primary h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">Users</h5>
                                    <i class="fas fa-users fa-2x text-primary"></i>
                                </div>
                                <p class="card-text">Manage users and their details.</p>
                                <a href="customers.php" class="btn btn-primary">View Users</a>
                            </div>
                        </div>
                    </div>

                    <!-- Food Items Section -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card border-success h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">Food Items</h5>
                                    <i class="fas fa-utensils fa-2x text-success"></i>
                                </div>
                                <p class="card-text">Manage food items and categories.</p>
                                <a href="all_menus.php" class="btn btn-success">View Food Items</a>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Section -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card border-info h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">Orders</h5>
                                    <i class="fas fa-clipboard-list fa-2x text-info"></i>
                                </div>
                                <p class="card-text">View and manage customer orders.</p>
                                <a href="orders.php" class="btn btn-info">View Orders</a>
                            </div>
                        </div>
                    </div>

                    <!-- Categories Section -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card border-warning h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">Categories</h5>
                                    <i class="fas fa-tags fa-2x text-warning"></i>
                                </div>
                                <p class="card-text">Manage food categories.</p>
                                <a href="all_categories.php" class="btn btn-warning">View Categories</a>
                            </div>
                        </div>
                    </div>

                    <!-- Reports Section -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card border-danger h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">Reports</h5>
                                    <i class="fas fa-chart-bar fa-2x text-danger"></i>
                                </div>
                                <p class="card-text">Generate and view reports.</p>
                                <a href="reports.php" class="btn btn-danger">View Reports</a>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Section -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card border-dark h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">Settings</h5>
                                    <i class="fas fa-cog fa-2x text-dark"></i>
                                </div>
                                <p class="card-text">Configure application settings.</p>
                                <a href="settings.php" class="btn btn-dark">View Settings</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Footer -->
<footer class="footer bg-dark text-white text-center py-3">
    <div class="container">
        <p class="mb-0">&copy; 2024 Admin Dashboard. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="admin.js"></script>

</body>
</html>
