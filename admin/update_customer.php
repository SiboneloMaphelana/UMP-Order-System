<?php 
session_start();
require_once '../connection/connection.php';
require_once 'model/Admin.php';

$admin = new Admin($conn);

// Check if customer ID is provided via GET parameter
if (!isset($_GET['id'])) {
    // Handle error: No customer ID provided
    echo "Customer ID not provided.";
    exit();
}

$customerId = intval($_GET['id']);

// Fetch customer details by ID
$customer = $admin->getCustomerById($customerId);

// Check if customer exists
if (!$customer) {
    // Handle error: Customer not found
    echo "Customer not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Customer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<!-- Header -->
<header class="header">
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
            <a class="nav-link" href="customers.php">
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
        <h2>Edit Customer</h2>
        <form action="model/update_customer_process.php" method="POST">
          <input type="hidden" name="id" value="<?php echo $customer['id']; ?>">
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($customer['name']); ?>" required>
          </div>
          <div class="mb-3">
            <label for="surname" class="form-label">Surname</label>
            <input type="text" class="form-control" id="surname" name="surname" value="<?php echo htmlspecialchars($customer['surname']); ?>" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($customer['email']); ?>" required>
          </div>
          <div class="mb-3">
                        <label for="role" class="form-label text-success">Role</label>
                        <select class="form-select form-control" id="role" name="role">
                            <option selected disabled>Select Role</option>
                            <option value="student">Student</option>
                            <option value="lecturer">Lecturer</option>
                            <option value="guest">Guest</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
          <div class="mb-3">
            <label for="registration_number" class="form-label">Registration Number</label>
            <input type="text" class="form-control" id="registration_number" name="registration_number" value="<?php echo htmlspecialchars($customer['registration_number']); ?>" required>
          </div>
          <button type="submit" class="btn btn-primary">Update Customer</button>
        </form>
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
