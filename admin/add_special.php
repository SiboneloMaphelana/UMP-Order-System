<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
    <title>Add Food Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <?php include_once('partials/sidebar.php'); ?>

    <div id="content">
        <div class="container mt-4">
            <h2 class="text-center">Add New Special</h2>
            <!-- Success and Error Messages -->
            <?php
            if (isset($_SESSION['menu_error'])) {
                echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['menu_error']) . '</div>';
                unset($_SESSION['menu_error']);
            }
            ?>
            <form action="model/add_special_process.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="foodName" class="form-label">Food Name</label>
                    <input type="text" class="form-control" id="foodName" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="foodDescription" class="form-label">Description</label>
                    <textarea class="form-control" id="foodDescription" name="description" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" required>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="text" class="form-control" id="price" name="price" required>
                </div>

                <div class="mb-3">
                    <label for="foodImage" class="form-label">Food Image</label>
                    <input type="file" class="form-control" id="foodImage" name="image" required>
                </div>

                <!-- Start Date -->
                <div class="mb-3">
                    <label for="startDate" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="startDate" name="start_date" required>
                </div>

                <!-- End Date -->
                <div class="mb-3">
                    <label for="endDate" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="endDate" name="end_date" required>
                </div>

                <button type="submit" class="btn btn-primary">Add Food Item</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
