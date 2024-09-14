<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once("../connection/connection.php");
include_once("model/Order.php"); 


$food = new Order($conn);

// Check if order ID is provided in the query string
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Order ID is required.";
    header("Location: orders.php"); // Redirect to manage orders page if ID is missing
    exit();
}

$order_id = $_GET['id'];

// Retrieve order details based on order ID
$orderDetails = $food->getOrderById($order_id);

// Check if order exists
if (!$orderDetails) {
    $_SESSION['error'] = "Order not found.";
    header("Location: orders.php"); // Redirect to manage orders page if order not found
    exit();
}
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
    <?php include("partials/sidebar.php"); ?>
    <div id="content" class="container-fluid overflow-hidden">
        <button class="btn btn-dark d-md-none" type="button" id="toggleSidebar">
            <i class="fas fa-bars"></i>
        </button>
        <div class="container-fluid overflow-hidden">
            <div class="row vh-100 overflow-auto">
                <div class="col d-flex flex-column h-sm-100">
                    <main class="row overflow-auto">
                        <h1 class="mt-4">Update Order Status</h1>

                        <div class="card mb-4">
                            <div class="card-body">
                                <form method="POST" action="model/update_order.php">
                                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Select New Status</label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="pending" <?php echo ($orderDetails['status'] === 'pending') ? 'selected' : ''; ?>>Pending</option>
                                            <option value="processing" <?php echo ($orderDetails['status'] === 'processing') ? 'selected' : ''; ?>>Processing</option>
                                            <option value="completed" <?php echo ($orderDetails['status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
                                            <option value="cancelled" <?php echo ($orderDetails['status'] === 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update Status</button>
                                    <a href="orders.php?id=<?php echo $order_id; ?>" class="btn btn-secondary">Cancel</a>
                                </form>
                            </div>
                        </div>
                    </main>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
</body>

</html>