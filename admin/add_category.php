<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                    <?php
                    if (isset($_SESSION['fail-cat'])) {
                        echo '<div class="alert alert-danger">' . $_SESSION['fail-cat'] . '</div>';
                        unset($_SESSION['fail-cat']);
                    }
                    ?>
                    <h2 class="text-center">Add New Category</h2>
                    <form action="model/add_category_process.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="categoryName" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="categoryName" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="categoryImage" class="form-label">Category Image</label>
                            <input type="file" class="form-control" id="categoryImage" name="image" required>
                        </div>
                        <button type="submit" class="btn btn-success">Add Category</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

