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
$cartItems = $_SESSION['cart'];

// Retrieve subtotal from session 
$subtotal = isset($_SESSION['subtotal']) ? $_SESSION['subtotal'] : 0.0;

// If subtotal is not stored in session, calculate it
if ($subtotal === 0.0) {
    // Function to calculate subtotal
    function calculateSubtotal($cartItems) {
        $subtotal = 0.0;
        foreach ($cartItems as $item) {
            // Ensure item price and quantity are numeric
            $price = floatval($item['price']);
            $quantity = intval($item['quantity']);
            
            // Calculate subtotal for each item
            $subtotal += $price * $quantity;
        }
        return $subtotal;
    }

    // Calculate subtotal
    $subtotal = calculateSubtotal($cartItems);

    // Store subtotal in session
    $_SESSION['subtotal'] = $subtotal;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head>
<body>


<div class="container mt-5">
    <h2 class="text-center">Checkout</h2>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Order Summary</h5>
                    <?php if (!empty($cartItems)) : ?>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Total Items:
                                <span><?php echo count($cartItems); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Total:
                                <span>R<?php echo number_format($subtotal, 2); ?></span>
                            </li>
                        </ul>
                        <form method="POST" action="admin/model/process_checkout.php" class="mt-4">
                            <input type="hidden" name="subtotal" value="<?php echo $subtotal; ?>">
                            <div class="mb-3">
                                <label for="paymentMethod" class="form-label">Select Payment Method</label>
                                <select class="form-select" id="paymentMethod" name="payment_method" required>
                                    <option value="collection">Pay On Collection</option>
                                    <option value="paypal">PayPal</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Place Order</button>
                        </form>
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
