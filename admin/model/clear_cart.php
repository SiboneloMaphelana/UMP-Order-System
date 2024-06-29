<?php
session_start();

// Clear cart items from session
unset($_SESSION['cart']);

// Redirect back to cart page or any other appropriate page
header("Location: ../../cart.php");
exit();
?>
