<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] === 'staff') {
    $_SESSION['error'] = "Access denied. You are not authorized to view the page.";
    header("Location: dashboard.php");
    exit();
}

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
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <?php include('partials/sidebar.php'); ?>

    <div id="content">
        <button class="btn btn-dark d-md-none" type="button" id="toggleSidebar">
            <i class="fas fa-bars"></i>
        </button>
        <div class="container mt-4">
            <h1>Customer Information</h1>
            <div class="table-container">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>NAME</th>
                            <th>SURNAME</th>
                            <th>EMAIL</th>
                            <th>ROLE</th>
                            <th>PHONE</th>
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
                                <td><?php echo htmlspecialchars($customer['phone']); ?></td>
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
        </div>

        <footer class="footer mt-auto py-3 bg-dark text-light">
            <div class="container text-center">
                <span>&copy; 2024 Food Ordering Admin. All Rights Reserved.</span>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
</body>

</html>