<?php

require_once '../connection/connection.php';
require_once 'model/Food.php';

$food = new Food($conn);


$foodItems = $food->getAllFoodItems();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Food Items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        

    </style>
</head>
<body>
    
    <!-- Header -->
    <header class="navbar-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <img src="../images/logo.jpeg" alt="UMP LOGO" class="img-fluid logo-img">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="model/logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container-fluid mt-3">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-5 position-fixed">
                <div class="sidebar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="customers.php">
                                <i class="fas fa-users"></i> Customers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
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
            <div class="col-md-9 offset-md-2">
                <div class="main-content">
                    <!-- Alerts -->
                    <?php
                    if (isset($_SESSION['success'])) {
                        echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                        unset($_SESSION['success']);
                    }
                    if (isset($_SESSION['error'])) {
                        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                        unset($_SESSION['error']);
                    }
                    ?>
                    <h2 class="text-center">Food Items</h2>
                    <div class="d-flex justify-content-center mb-3">
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
                            <?php foreach ($foodItems as $food): ?>
    <tr>
        <td><?= htmlspecialchars($food['name']); ?></td>
        <td><?= htmlspecialchars($food['quantity']); ?></td>
        <td>R <?= htmlspecialchars($food['price']); ?></td>
        <td><?= htmlspecialchars($food['description']); ?></td>
        <td><img src="foods/<?= htmlspecialchars($food['image']); ?>" alt="Food Image" class="img-thumbnail"></td>
        <td><?= htmlspecialchars($food['Category']); ?></td>
        <td>
            <div class="btn-group-vertical">
                <a href="update_food.php?id=<?= htmlspecialchars($food['id']); ?>" class="btn btn-primary"><i class="fas fa-edit fa-xs"></i></a>
                <a href="model/delete_food.php?id=<?= htmlspecialchars($food['id']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this food item?')"><i class="fas fa-trash-alt fa-xs"></i></a>
            </div>
        </td>
    </tr>
<?php endforeach; ?>
<?php if (empty($foodItems)): ?>
    <tr>
        <td colspan="7" class="text-center">No food items found.</td>
    </tr>
<?php endif; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


