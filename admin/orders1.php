<?php
session_start();
include_once("../connection/connection.php");
include_once("model/Order.php");

$order = new Order($conn);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = 10;

$orders = $order->getAllOrdersPaginated($page, $items_per_page);
$total_orders = $order->countOrders();
$total_pages = ceil($total_orders / $items_per_page);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <?php include("partials/sidebar.php"); ?>

    <div id="content" class="container-fluid overflow-hidden">
        <button class="btn btn-dark d-md-none" type="button" id="toggleSidebar">
            <i class="fas fa-bars"></i>
        </button>
        <div class="container-fluid overflow-hidden">
            <div class="row vh-100 overflow-auto">
                <div class="col d-flex flex-column h-sm-100">
                    <main class="row overflow-auto">
                        <div class="col pt-4">
                            <div class="table-container">

                                <div class="card-body">
                                    <?php
                                    if (isset($_SESSION['success'])) {
                                        echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                                        unset($_SESSION['success']);
                                    }

                                    if (isset($_SESSION['error'])) {
                                        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                                        unset($_SESSION['error']);
                                    }

                                    if (isset($_SESSION['email_success'])) {
                                        echo '<div class="alert alert-success">' . $_SESSION['email_success'] . '</div>';
                                        unset($_SESSION['email_success']);
                                    }

                                    if (isset($_SESSION['email_error'])) {
                                        echo '<div class="alert alert-danger">' . $_SESSION['email_error'] . '</div>';
                                        unset($_SESSION['email_error']);
                                    }
                                    ?>

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

                                    <!-- Pagination Controls -->
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-center">
                                            <li class="page-item <?php if ($page <= 1) {
                                                                        echo 'disabled';
                                                                    } ?>">
                                                <a class="page-link" href="<?php if ($page > 1) {
                                                                                echo "?page=" . ($page - 1);
                                                                            } else {
                                                                                echo '#';
                                                                            } ?>">Previous</a>
                                            </li>
                                            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                                                <li class="page-item <?php if ($page == $i) {
                                                                            echo 'active';
                                                                        } ?>">
                                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                                </li>
                                            <?php endfor; ?>
                                            <li class="page-item <?php if ($page >= $total_pages) {
                                                                        echo 'disabled';
                                                                    } ?>">
                                                <a class="page-link" href="<?php if ($page < $total_pages) {
                                                                                echo "?page=" . ($page + 1);
                                                                            } else {
                                                                                echo '#';
                                                                            } ?>">Next</a>
                                            </li>
                                        </ul>
                                    </nav>

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
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
</body>

</html>