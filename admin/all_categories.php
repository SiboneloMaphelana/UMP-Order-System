<?php 
include("model/login_check.php"); 
include_once("../connection/connection.php");
include("model/food.php");

$food = new Food($conn);
$categories = $food->getCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="css/admin.css">
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
<div class="container-fluid mt-4">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-3 position-fixed">
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
      <div class="content">
        
                    <!-- Alerts -->
                    <?php
                    if (isset($_SESSION['add-cat'])) {
                        echo '<div class="alert alert-success">' . $_SESSION['add-cat'] . '</div>';
                        unset($_SESSION['add-cat']);
                    }
                    if (isset($_SESSION['fail-cat'])) {
                        echo '<div class="alert alert-danger">' . $_SESSION['fail-cat'] . '</div>';
                        unset($_SESSION['fail-cat']);
                    }
                    ?>
        <h2 class="text-center">Categories</h2>
        <div class="d-flex justify-content-center mb-3">
          <a href="add_category.php" class="btn btn-success mb-3">Add Category</a>
        </div>
        <div class="table-responsive mt-4">
          <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
              <tr>
                <th scope="col">Name</th>
                <th scope="col">Image</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($categories as $category): ?>
              <tr>
                <td><?php echo htmlspecialchars($category['name']); ?></td>
                <td><img src="uploads/<?php echo htmlspecialchars($category['imageName']); ?>" alt="<?php echo htmlspecialchars($category['name']); ?>" class="img-fluid"></td>
                <td>
                    <div class="btn-group-vertical">
                        <a href="update_category.php?id=<?php echo htmlspecialchars($category['id']); ?>" class="btn btn-primary btn-sm mb-2"><i class="fas fa-edit"></i></a>
                        <a href="model/delete_category.php?id=<?php echo htmlspecialchars($category['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this category?');"><i class="fas fa-trash-alt"></i></a>
                    </div>
                </td>

              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="footer">
  <div class="container">
    <p>&copy; 2024 Admin Dashboard</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="admin.js"></script>
</body>
</html>
