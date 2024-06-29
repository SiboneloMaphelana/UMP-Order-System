<!-- update_status.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Order Status</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container-fluid">
    <h1 class="mt-4">Update Order Status</h1>

    <?php
    session_start();
    include_once("../connection/connection.php");
    include_once("model/Food.php"); // Include your Food class file

    // Initialize Food class with database connection
    $food = new Food($conn);

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

    // Process form submission to update order status
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_status = $_POST['status'];

        // Update order status in the database
        if ($food->updateOrderStatus($order_id, $new_status)) {
            $_SESSION['success'] = "Order status updated successfully.";
            header("Location: orders.php?id=" . $order_id); // Redirect to view order details page
            exit();
        } else {
            $_SESSION['error'] = "Failed to update order status.";
        }
    }
    ?>

    <div class="card mb-4">
        <div class="card-body">
            <form method="POST">
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
                <a href="view_order.php?id=<?php echo $order_id; ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
