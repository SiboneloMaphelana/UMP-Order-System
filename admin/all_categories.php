<?php 
include("model/login_check.php"); 
include_once("../connection/connection.php");
include("model/food.php");

// Create an instance of the Food class and fetch categories
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
<header class="header">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <h1 class="logo">Admin Dashboard</h1>
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
    <div class="col-md-9">
      <div class="content">
        <h2>Categories</h2>
        <a href="add_category.php" class="btn btn-success mb-3">Add Category</a>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead class="table-light">
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
                <td><img src="uploads/<?php echo htmlspecialchars($category['imageName']); ?>" alt="<?php echo htmlspecialchars($category['name']); ?>" class="img-fluid" style="max-width: 100px;"></td>
                <td>
                  <a href="update_category.php?id=<?php echo htmlspecialchars($category['id']); ?>" class="btn btn-primary btn-sm">Edit</a>
                  <a href="model/delete_category.php?id=<?php echo htmlspecialchars($category['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
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
