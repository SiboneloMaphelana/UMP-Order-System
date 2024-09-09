<?php
session_start();

// Validate session cart
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $_SESSION['error'] = "Your cart is empty.";
    header("Location: cart.php");
    exit();
}

// Retrieve subtotal from session 
$subtotal = isset($_SESSION['subtotal']) ? $_SESSION['subtotal'] : 0.0;

// Calculate total items in the cart
$totalItems = calculateTotalItems($_SESSION['cart']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/new.css">
</head>

<body>

    <div class="container-fluid overflow-hidden">
        <div class="row vh-100 overflow-auto">
            <?php include_once("partials/navigation.php"); ?>
            <div class="col d-flex flex-column h-sm-100">
                <main class="row overflow-auto main-content">
                    <div class="col-md-6 mx-auto">
                        <h2 class="text-center my-4">Order Summary</h2>
                        <div class="card mb-4">
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Total Items:
                                        <span><?php echo $totalItems; ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Total Price:
                                        <span>R<?php echo number_format($subtotal, 2); ?></span>
                                    </li>
                                </ul>
                                <form action="admin/model/process_checkout.php" method="POST" class="mt-3">
                                    <div>
                                        <h6>Select Payment Method:</h6>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" id="payment_payfast" value="payfast" >
                                            <label class="form-check-label" for="payment_payfast">
                                                <i class="fas fa-credit-card"></i> PayFast
                                            </label>
                                        </div>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="radio" name="payment_method" id="cash_on_collection" value="cash on collection" >
                                            <label class="form-check-label" for="cash_on_collection">
                                                <i class="fas fa-money-bill-wave"></i> Cash on Collection
                                            </label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100 mt-3">Place Order</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

<?php
// Function to calculate total items in the cart
function calculateTotalItems($cartItems)
{
    $totalItems = 0;
    if (is_array($cartItems)) {
        foreach ($cartItems as $item) {
            $totalItems += $item['quantity'];
        }
    }
    return $totalItems;
}
?>
