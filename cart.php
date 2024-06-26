<?php
session_start();
include_once("connection/connection.php");
include_once("admin/model/Food.php");

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Function to calculate subtotal
function calculateSubtotal($cartItems) {
    $subtotal = 0.0;
    foreach ($cartItems as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    return $subtotal;
}

// Function to calculate total items in the cart
function calculateTotalItems($cartItems) {
    $totalItems = 0;
    foreach ($cartItems as $item) {
        $totalItems += $item['quantity'];
    }
    return $totalItems;
}

// Retrieve cart items from session
$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Calculate subtotal
$subtotal = calculateSubtotal($cartItems);

// Store subtotal in session
$_SESSION['subtotal'] = $subtotal;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css"> 
</head>
<body>
<header class="header fixed-top d-flex justify-content-between align-items-center px-2 py-1">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-9 order-md-2">
                <nav class="navbar navbar-expand-lg navbar-light justify-content-end">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link text-success" href="index.php"><i class="bi bi-house-fill"></i> Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-success" href="#notifications"><i class="bi bi-bell-fill"></i> Notifications</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-success" href="#orders"><i class="bi bi-list-check"></i> Orders</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-success" href="cart.php"><i class="bi bi-cart-fill"></i> Cart</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-success" href="#about"><i class="bi bi-info-square-fill"></i> About Us</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="col-md-3 order-md-1">
                <img src="images/logo.jpeg" alt="UMP logo" class="logo img-fluid">
            </div>
        </div>
    </div>

    <div class="dropdown ms-auto">
        <button class="btn btn-light border-0 dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-fill text-success" style="font-size: 1.5rem;"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
            <li><a class="dropdown-item text-danger" href="model/logout.php">Logout</a></li>
        </ul>
    </div>
</header>

<div class="container mt-5">
    <h2 class="text-center">My Cart</h2>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($cartItems)) : ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Item</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cartItems as $index => $item) : ?>
                                    <tr>
                                        <th scope="row"><?php echo $index + 1; ?></th>
                                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td>R<?php echo number_format($item['price'], 2); ?></td>
                                        <td>R<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <p>Your cart is empty.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Cart Summary</h5>
                    <?php if (!empty($cartItems)) : ?>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Total Items:
                                <span><?php echo calculateTotalItems($cartItems); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Subtotal:
                                <span>R<?php echo number_format($subtotal, 2); ?></span>
                            </li>
                        </ul>
                        <a href="checkout.php" class="btn btn-success w-100 mt-3">Proceed to Checkout</a>
                    <?php else : ?>
                        <p>Your cart is empty.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="footer mt-auto py-3 bg-light">
    <div class="container text-center">
        <p>&copy; 2024 TechCafeSolutions. All rights reserved.</p>
        <p>Contact: info@techcafesolutions.com</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
