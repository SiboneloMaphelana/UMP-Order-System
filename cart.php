<?php
include_once("model/login_check.php");
include_once("connection/connection.php");
include_once("admin/model/Food.php");
include_once("functions/cart_functions.php");

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
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="css/navigation.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/cart.css">

</head>

<body>

    <div class="container-fluid overflow-hidden">
        <div class="row vh-100 overflow-auto">
            <?php include("partials/navigation.php"); ?>
            <div class="col d-flex flex-column h-sm-100">
                <main class="row overflow-auto">
                    <h2 class="text-center">My Cart</h2>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="cart-container">
                                <?php if (!empty($cartItems)) : ?>
                                    <span class="text-muted"><a href="index.php" class="card-link text-decoration-none text-success">Back To Menu</a></span>
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
                                    <p>Your cart is empty. Let's start an Order! <a href="index.php" class="btn btn-success rounded-pill text-decoration-none">Start Order <i class="bi bi-cart"></i> </a></p>
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
                                        <a href="checkout.php" class="btn btn-success w-100 mt-3">Proceed to Checkout</a>
                                    <?php else : ?>
                                        <p>Your cart is empty.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <footer class="row bg-light py-4 mt-auto">
                    
                </footer>
            </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>