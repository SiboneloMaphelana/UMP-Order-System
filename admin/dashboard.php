<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("model/login_check.php");
require_once '../connection/connection.php';
require_once 'model/Report.php';

$report = new Report($conn);

if ($_SESSION['role'] === 'staff') {
    $_SESSION['error'] = "Access denied. You are not authorized to view the page.";
    header("Location: orders.php");
    exit();
}

// Fetch report data
$ordersReport = $report->getOrdersReport();
$customerReport = $report->getCustomerReport();
$inventoryReport = $report->getInventoryReport();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/stocks.css">
</head>

<body>
    <?php include('partials/sidebar.php'); ?>

    <div id="content">
        <button class="btn btn-dark d-md-none" type="button" id="toggleSidebar">
            <i class="fas fa-bars"></i>
        </button>
        <div class="container mt-4">
            <div class="notification-bell" id="bell" title="Low stocks">
                <span class="badge" id="badge">0</span>
            </div>
            <div id="notification-container" class="text-center" style="display: none;"></div>
            <main class="row overflow-auto">

                <h2 class="mb-4">Reports</h2>

                <!-- Nav tabs -->
                <ul class="nav nav-tabs" id="reportTabs" role="tablist">

                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab" aria-controls="orders" aria-selected="true">Orders Report</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="customers-tab" data-bs-toggle="tab" data-bs-target="#customers" type="button" role="tab" aria-controls="customers" aria-selected="false">Customer Report</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory" type="button" role="tab" aria-controls="inventory" aria-selected="false">Inventory Report</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="revenue-tab" data-bs-toggle="tab" data-bs-target="#revenue" type="button" role="tab" aria-controls="revenue" aria-selected="false">Revenue Report</button>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content" id="reportTabsContent">

                    <!-- Orders Report Tab -->
                    <div class="tab-pane fade show active" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title">Order Status Summary</h5>
                                        <ul class="list-group list-group-flush">
                                            <?php foreach ($ordersReport as $order) : ?>
                                                <li class="list-group-item">
                                                    <span class="fw-bold"><?php echo ucfirst($order['status']); ?>:</span>
                                                    <span class="float-end"><?php echo $order['count']; ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <!-- Form to download Excel report -->
                                        <form method="GET" action="model/download_orders.php" class="mt-3">

                                            <!-- Input to select a specific month -->
                                            <label for="month">Select Month:</label>
                                            <input type="month" id="month" name="month">

                                            <!-- Checkbox to download all months' aggregated data -->
                                            <div>
                                                <input type="checkbox" id="all_months" name="all_months" value="1">
                                                <label for="all_months">Download Aggregated Data for All Months</label>
                                            </div>

                                            <!-- Button to submit the form -->
                                            <button type="submit" class="btn btn-primary">Download Report</button>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Report Tab -->
                    <div class="tab-pane fade" id="customers" role="tabpanel" aria-labelledby="customers-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Customers</h5>
                                        <p class="card-text"><?php echo $customerReport['total_customers']; ?></p>
                                    </div>

                                    <!-- Form to download Excel report -->
                                    <form method="GET" action="model/download_customers.php" class="mt-3">
                                        <button type="submit" class="btn btn-primary">Download Report</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory Report Tab -->
                    <div class="tab-pane fade" id="inventory" role="tabpanel" aria-labelledby="inventory-tab">
                        <form method="GET" action="model/download_inventory.php" class="mt-3">
                            <button type="submit" class="btn btn-primary">Download Report</button>
                        </form>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title">Inventory Summary</h5>
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Item</th>
                                                        <th>Quantity</th>
                                                        <th>Price</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($inventoryReport as $item) : ?>
                                                        <tr>
                                                            <td><?php echo $item['name']; ?></td>
                                                            <td><?php echo $item['quantity']; ?></td>
                                                            <td>R<?php echo number_format($item['price'], 2); ?></td>
                                                        </tr>
                                                        <?php if ($item['quantity'] <= 10) : ?>
                                                            <tr class="table-warning">
                                                                <td colspan="3">
                                                                    <strong><?php echo $item['name']; ?></strong> has reached a quantity of 10 or less.
                                                                    <a href="all_menus.php" class="btn btn-sm btn-warning float-end">Manage Inventory</a>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Revenue Report Tab -->
                    <div class="tab-pane fade" id="revenue" role="tabpanel" aria-labelledby="revenue-tab">
                        <div class="row mb-3">
                            <div class="col-auto">
                                <label for="filterType" class="form-label">Filter By:</label>
                            </div>
                            <div class="col-auto">
                                <select class="form-select" id="filterType" onchange="filterRevenue(this.value)">
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title">Revenue</h5>
                                        <ul class="list-group list-group-flush" id="revenueList">
                                            <!-- Revenue data will be dynamically populated here -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--
                        <form method="GET" action="model/download_sales.php" class="mt-3">
                            <button type="submit" class="btn btn-primary">Download Report</button>
                        </form>
                        -->

                    </div>

            </main>

        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/reports.js"></script>
    <script src="js/updateBell.js"></script>
</body>

</html>