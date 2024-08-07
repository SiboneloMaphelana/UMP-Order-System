<?php

/**
 * Calculates the subtotal of the given cart items.
 *
 * @param array $cartItems An array of cart items.
 * @return float The subtotal of the cart items.
 */
function calculateSubtotal($cartItems)
{
    $subtotal = 0.0;
    if (is_array($cartItems)) {
        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
    }
    return $subtotal;
}

/**
 * Calculates the total number of items in the cart.
 *
 * @param array $cartItems An array of cart items.
 * @return int The total number of items in the cart.
 */
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

/**
 * Removes an item from the cart.
 *
 * @param int $itemId The ID of the item to remove.
 * @return void
 */
function removeFromCart($itemId)
{
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && isset($_SESSION['cart'][$itemId])) {
        unset($_SESSION['cart'][$itemId]);
    }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $itemId The ID of the item to update.
 * @param int $quantity The new quantity of the item.
 * @return void
 */
function updateCartItemQuantity($itemId, $quantity)
{
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && isset($_SESSION['cart'][$itemId])) {
        $_SESSION['cart'][$itemId]['quantity'] = $quantity;
    }
}

/**
 * Handles the request to remove an item from the cart and recalculates the subtotal.
 *
 */
function handleItemRemovalRequest()
{
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

/**
 * Handles the request to update the quantity of an item in the cart.
 *
 * @throws Exception if the item ID or quantity is not numeric, or if the cart is not set or not an array.
 * @return void
 */
function handleItemQuantityUpdateRequest()
{
    if (isset($_POST['update_quantity']) && is_numeric($_POST['item_id']) && is_numeric($_POST['quantity']) && isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        $itemId = $_POST['item_id'];
        $quantity = $_POST['quantity'];
        updateCartItemQuantity($itemId, $quantity);
        header("Location: cart.php"); // Redirect to refresh the page after update
        exit();
    }
}
