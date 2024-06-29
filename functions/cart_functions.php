<?php
// Function to calculate subtotal
function calculateSubtotal($cartItems) {
    $subtotal = 0.0;
    if (is_array($cartItems)) {
        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
    }
    return $subtotal;
}

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

// Function to remove an item from cart
function removeFromCart($itemId) {
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && isset($_SESSION['cart'][$itemId])) {
        unset($_SESSION['cart'][$itemId]);
    }
}

// Function to update quantity of an item in the cart
function updateCartItemQuantity($itemId, $quantity) {
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && isset($_SESSION['cart'][$itemId])) {
        $_SESSION['cart'][$itemId]['quantity'] = $quantity;
    }
}

// Handle item removal request
function handleItemRemovalRequest() {
    if (isset($_GET['remove']) && isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        $index = $_GET['remove'];
        if (isset($_SESSION['cart'][$index])) {
            unset($_SESSION['cart'][$index]);
        }
    }
    // Recalculate subtotal after removing item(s)
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        $_SESSION['subtotal'] = calculateSubtotal($_SESSION['cart']);
    }
}

// Handle item quantity update request
function handleItemQuantityUpdateRequest() {
    if (isset($_POST['update_quantity']) && is_numeric($_POST['item_id']) && is_numeric($_POST['quantity']) && isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        $itemId = $_POST['item_id'];
        $quantity = $_POST['quantity'];
        updateCartItemQuantity($itemId, $quantity);
        header("Location: cart.php"); // Redirect to refresh the page after update
        exit();
    }
}
?>
