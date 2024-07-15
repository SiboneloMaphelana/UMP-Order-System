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
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="css/navigation.css">
</head>

<body>

  <div class="container-fluid overflow-hidden">
    <div class="row vh-100 overflow-auto">
      <?php include("partials/navigation.php"); ?>
      <div class="col d-flex flex-column h-sm-100">
        <main class="row overflow-auto">
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
        </main>
        <footer class="row bg-light py-4 mt-auto">
          <div class="col">WE HAVE NO FOOTER, BEING GHOSTED</div>
        </footer>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
</body>

</html>