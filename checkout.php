<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php"); 
    exit();
}

// Validate session cart
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $_SESSION['error'] = "Your cart is empty."; 
    header("Location: cart.php");
    exit();
}

// Retrieve cart items from session
$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Retrieve subtotal from session 
$subtotal = isset($_SESSION['subtotal']) ? $_SESSION['subtotal'] : 0.0;

include_once("partials/header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/index.css"> 
    <link rel="stylesheet" href="css/cart.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Checkout</h2>
    <div class="row">
        <div class="col-md-8">
            <div class="cart-container">
                <?php if (!empty($cartItems)) : ?>
                    <?php foreach ($cartItems as $item) : ?>
                        <div class="card mb-3">
                            <div class="card-body bg-light">
                                <h6 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h6>
                                <p class="card-text">Quantity: <?php echo $item['quantity']; ?></p>
                                <p class="card-text">Price: R<?php echo number_format($item['price'], 2); ?></p>
                                <p class="card-text">Total: R<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>Your cart is empty.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Order Summary</h5>
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
                    <form action="admin/model/process_checkout.php" method="POST">
                        <div class="mt-3">
                            <h6>Select Payment Method:</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_paypal" value="paypal" required>
                                <label class="form-check-label" for="payment_paypal">
                                    PayPal
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_cash_on_delivery" value="cash_on_delivery" required>
                                <label class="form-check-label" for="payment_cash_on_delivery">
                                    Cash on Collection
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100 mt-3">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once("partials/footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
// Function to calculate total items in the cart
function calculateTotalItems($cartItems) {
    $totalItems = 0;
    if (is_array($cartItems)) {
        foreach ($cartItems as $item) {
            $totalItems += $item['quantity'];
        }
    }
    return $totalItems;
}
?>
