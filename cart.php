<?php
include_once("connection/connection.php");
include_once("admin/model/Food.php");
include_once("functions/cart_functions.php");

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Retrieve cart items from session
$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Calculate subtotal
$subtotal = calculateSubtotal($cartItems);

// Store subtotal in session
$_SESSION['subtotal'] = $subtotal;

// Handle item removal request
handleItemRemovalRequest();

// Handle item quantity update request
handleItemQuantityUpdateRequest();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['id']);
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
    <link rel="stylesheet" href="css/new.css">
    
</head>

<body>

    <div class="container-fluid overflow-hidden">
        <div class="row vh-100 overflow-auto">
            <?php include_once("partials/navigation.php"); ?>
            <div class="col d-flex flex-column h-sm-100">
                <main class="row overflow-auto main-content">
                    <h2 class="text-center">My Cart</h2>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="cart-container">
                                <?php if (!empty($cartItems)) : ?>
                                    <span class="text-muted">
                                        <a href="index.php" class="card-link text-decoration-none text-success">
                                            <i class="fas fa-arrow-left"></i>Continue Shopping
                                        </a>
                                    </span>

                                    <?php foreach ($cartItems as $index => $item) : ?>
                                        <div class="card mb-3">
                                            <div class="card-body bg-light">
                                                <h6 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h6>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="btn-group" role="group" aria-label="Quantity controls">
                                                        <form action="cart.php" method="post" class="d-inline">
                                                            <input type="hidden" name="item_id" value="<?php echo $index; ?>">
                                                            <input type="hidden" name="quantity" value="<?php echo $item['quantity'] - 1; ?>">
                                                            <button type="submit" name="update_quantity" class="btn btn-outline-secondary btn-sm rounded-circle text-white <?php echo $item['quantity'] <= 1 ? 'disabled' : ''; ?>" style="background-color: #8FBC8F;">-</button>
                                                        </form>
                                                        <span class="mx-2"><?php echo $item['quantity']; ?></span>
                                                        <form action="cart.php" method="post" class="d-inline">
                                                            <input type="hidden" name="item_id" value="<?php echo $index; ?>">
                                                            <input type="hidden" name="quantity" value="<?php echo $item['quantity'] + 1; ?>">
                                                            <button type="submit" name="update_quantity" class="btn btn-outline-secondary btn-sm rounded-circle text-white" style="background-color: #8FBC8F;">+</button>
                                                        </form>
                                                    </div>
                                                    <p class="mb-0">R<?php echo number_format($item['price'], 2); ?></p>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mt-2">
                                                    <a href="cart.php?remove=<?php echo $index; ?>" class="remove-item-link card-link text-decoration-none text-danger" data-item-name="<?php echo htmlspecialchars($item['name']); ?>"><i class="bi bi-trash fs-4"></i></a>
                                                    <span>Total: R<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endforeach; ?>
                                    <span class="text-muted"><a href="admin/model/clear_cart.php" class="card-link text-decoration-none text-danger"> <i class="bi bi-trash fs-4"></i> Clear Cart</a> </span>

                                <?php else : ?>
                                    <p>Your cart is empty! <a href="index.php" class="btn btn-success rounded-pill text-decoration-none">Start Order <i class="bi bi-cart"></i> </a></p>
                                <?php endif; ?>
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
                                        <?php if($isLoggedIn) : ?>
                                            <a href="checkout.php" class="btn btn-outline-secondary w-100 mt-3">Proceed to Checkout</a>
                                        <?php endif; ?>
                                        <?php if (!$isLoggedIn) : ?>
                                            <form action="admin/model/process_checkout.php" method="post" class="mt-3">
                                                <div class="mb-3">
                                                    <label for="guest_phone" class="form-label">Enter Phone Number to receive order confirmation</label>
                                                    <input type="tel" name="guest_phone" class="form-control" id="guest_phone" placeholder="+27XXXXXXXXX" pattern="^\+27[0-9]{9}$" required>
                                                </div>
                                                <button type="submit" name="guest_checkout" class="btn btn-outline-secondary w-100">Guest Checkout</button>
                                            </form>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <p>Your cart is empty.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </main>
                <!-- Footer -->
                <?php include_once("partials/footer.php"); ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
