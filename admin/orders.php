<?php
require_once 'model/login_check.php';
require_once '../connection/connection.php';
require_once 'model/Food.php';

$food = new Food($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <?php include_once('partials/sidebar.php'); ?>

    <div id="content" class="container-fluid overflow-hidden">
        <button class="btn btn-dark d-md-none" type="button" id="toggleSidebar">
            <i class="fas fa-bars"></i>
        </button>

        <div class="row vh-100 overflow-auto">
            <div class="col d-flex flex-column h-sm-100">
                <main class="row overflow-auto mt-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1>Orders</h1>
                        <div class="notification-icon position-relative">
                            <i class="bi bi-bell fs-5 text-primary"></i>
                            <span id="notificationBadge" class="notification-badge bg-danger rounded-circle position-absolute top-0 start-75 translate-middle">
                                0
                            </span>
                        </div>
                    </div>

                    <div class="col pt-4">
                        <div class="table-container">
                            <table id="ordersTable" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Total Amount</th>
                                        <th>Order Status</th>
                                        <th>Order Date</th>
                                        <th>Completed At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be populated dynamically -->
                                </tbody>
                            </table>

                            <!-- Modal for View Order Details -->
                            <div class="modal fade" id="viewOrderModal" tabindex="-1" aria-labelledby="viewOrderModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewOrderModalLabel">Order Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Order details will be populated dynamically -->
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal for Update Status -->
                            <div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="updateStatusModalLabel">Update Order Status</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="updateStatusForm">
                                                <input type="hidden" id="orderIdToUpdate" name="order_id">
                                                <div class="mb-3">
                                                    <label for="orderStatus" class="form-label">Order Status</label>
                                                    <select class="form-select" id="orderStatus" name="status">
                                                        <option value="pending">Pending</option>
                                                        <option value="completed">Completed</option>
                                                        <option value="cancelled">Cancelled</option>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Update Status</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pagination Controls -->
                            <div class="pagination-controls d-flex justify-content-center mt-4">
                                <button id="prevPage" class="btn btn-secondary me-3" disabled>Previous</button>
                                <button id="nextPage" class="btn btn-secondary">Next</button>
                            </div>
                        </div>
                    </div>
                </main>

                <!-- Footer 
            <footer class="footer mt-auto py-3 bg-dark text-light">
                <div class="container text-center">
                    <span>&copy; 2024 Your Company. All rights reserved.</span>
                </div>
            </footer> -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin_dashboard.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
</body>

</html>