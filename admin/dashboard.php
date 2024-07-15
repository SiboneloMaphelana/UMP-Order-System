<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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