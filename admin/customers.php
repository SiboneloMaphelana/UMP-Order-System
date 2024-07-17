<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Check if user is staff
if ($_SESSION['role'] === 'staff') {
    $_SESSION['error'] = "Access denied. You are not authorized to view the page.";
    header("Location: dashboard.php"); // Redirect to dashboard or another page
    exit();
}

// Include necessary files and retrieve customer data
require_once '../connection/connection.php';
require_once 'model/Admin.php';

$admin = new Admin($conn);
$customers = $admin->getAllCustomers();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers</title>
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
                    <div class="col pt-4">
                        <div class="table-container">
                            <h1>Customer Information</h1>
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>NAME</th>
                                        <th>SURNAME</th>
                                        <th>EMAIL</th>
                                        <th>ROLE</th>
                                        <th>ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($customers as $customer) : ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($customer['name']); ?></td>
                                            <td><?php echo htmlspecialchars($customer['surname']); ?></td>
                                            <td><?php echo htmlspecialchars($customer['email']); ?></td>
                                            <td><?php echo htmlspecialchars($customer['role']); ?></td>
                                            <td>
                                                <a href="update_customer.php?id=<?php echo $customer['id']; ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="model/delete_customer.php" method="POST" style="display: inline-block;">
                                                    <input type="hidden" name="id" value="<?php echo $customer['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this customer?')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                </main>
                <footer class="row bg-light py-4 mt-auto">
                    <div class="col text-center text-muted">
                        &copy; 2024 Food Ordering Admin. All Rights Reserved.
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
</body>

</html>