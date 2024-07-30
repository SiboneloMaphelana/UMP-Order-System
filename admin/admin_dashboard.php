<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/navigation.css">
</head>

<body>

    <div class="container-fluid overflow-hidden">
        <div class="row vh-100 overflow-auto">
            <?php include("partials/navigation.php"); ?>
            <div class="col d-flex flex-column h-sm-100">
                <main class="row overflow-auto">
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <h1>Admin Dashboard</h1>
                        <div class="notification-icon">
                            <i class="bi bi-bell" style="font-size: 2rem;"></i> <!-- Bootstrap Icon -->
                            <span id="notificationBadge" class="notification-badge">0</span>
                        </div>
                    </div>
                    <div class="col pt-4">
                        <div class="table-container">
                            <div class="card-body">
                                <h1 class="card-title">Orders</h1>
                            </div>

                            <table id="ordersTable" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Order Date</th>
                                        <th>Completed At</th>
                                        <th>Actions</th> <!-- New column for actions -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Existing orders will be displayed here -->
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
                                            <!-- Order details will be loaded here dynamically -->
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
                                                        <option value="processing">Processing</option>
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
                <footer class="row bg-light py-4 mt-auto">
                    <div class="col">WE HAVE NO FOOTER, BEING GHOSTED</div>
                </footer>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin_dashboard.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>


</body>

</html>