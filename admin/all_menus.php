<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../connection/connection.php';
require_once 'model/Food.php';

$food = new Food($conn);
$categories = $food->getCategories();

// Check if a category filter has been applied
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';

// Fetch filtered or all food items
$foodItems = $food->getAllFoodItems($selectedCategory);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/stocks.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .table-responsive {
            overflow-x: auto;
        }

        .table th,
        .table td {
            vertical-align: middle;
            text-align: center;
        }

        .table img {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
        }

        @media (max-width: 992px) {

            .table th,
            .table td {
                font-size: 16px;
            }

            .table thead th {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>
    <?php include('partials/sidebar.php'); ?>

    <div id="content">
        <div class="container mt-4">
            <div class="notification-bell" id="bell" title="Low stocks">
                <span class="badge" id="badge">0</span>
            </div>

            <!-- Success and Error Messages -->
            <?php
            if (isset($_SESSION['menu_success'])) {
                echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['menu_success']) . '</div>';
                unset($_SESSION['menu_success']);
            }
            ?>

            <h2 class="text-center mb-4">Food Items</h2>

            <!-- Category Filter Form and Add Button in the Same Row -->
            <div class="d-flex justify-content-center mb-3">
                <form method="GET" class="d-flex align-items-center me-3">
                    <select name="category" class="form-select w-auto" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['id']); ?>" <?= $selectedCategory == $category['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>

                <a href="add_menu.php" class="btn btn-success">Add Food Item</a>
            </div>


            <div class="table-responsive mt-4">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($foodItems as $food) : ?>
                            <tr>
                                <td><?= htmlspecialchars($food['name']); ?></td>
                                <td><?= htmlspecialchars($food['quantity']); ?></td>
                                <td>R <?= htmlspecialchars($food['price']); ?></td>
                                <td><?= htmlspecialchars($food['description']); ?></td>
                                <td><img src="foods/<?= htmlspecialchars($food['image']); ?>" alt="Food Image" class="img-thumbnail img-fluid lazyload"></td>
                                <td><?= htmlspecialchars($food['Category']); ?></td>
                                <td>
                                    <div class="btn-group-vertical">
                                        <a href="update_food.php?id=<?= htmlspecialchars($food['id']); ?>" class="btn btn-primary"><i class="fas fa-edit fa-xs"></i></a>
                                        <a href="model/delete_food.php?id=<?= htmlspecialchars($food['id']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this food item?')"><i class="fas fa-trash-alt fa-xs"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($foodItems)) : ?>
                            <tr>
                                <td colspan="7" class="text-center">No food items found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/updateBell.js"></script>
</body>

</html>