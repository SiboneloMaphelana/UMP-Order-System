<?php
require_once '../connection/connection.php';
require_once 'model/Food.php';

$food = new Food($conn);
$categories = $food->getCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Food Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
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
                            <a class="nav-link dropdown-toggle" href="#"
                               id="menuDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-utensils"></i> Menu
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="menuDropdown">
                                <li><a class="dropdown-item"
                                       href="all_categories.php">All Categories</a>
                                </li>
                                <li><a class="dropdown-item" href="add_category.php">Add Category</a>
                                </li>
                                <li><a class="dropdown-item" href="add_menu.php">Add Menu</a></li>
                                <li><a class="dropdown-item" href="all_menus.php">All Menus</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-md-9">
                <div class="content">
                    <h2 class="text-center">Add New Food Item</h2>
                    <form action="model/add_food_process.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="foodName" class="form-label">Food Name</label>
                            <input type="text" class="form-control" id="foodName"
                                   name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="foodDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="foodDescription"
                                      name="description" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category"
                                    name="category" required>
                                <option selected disabled>Select category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id']; ?>"><?= htmlspecialchars($category['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity"
                                   name="quantity" required>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="text" class="form-control" id="price"
                                   name="price" required>
                        </div>

                        <div class="mb-3">
                            <label for="foodImage" class="form-label">Food Image</label>
                            <input type="file" class="form-control" id="foodImage"
                                   name="image" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Add Food Item</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

