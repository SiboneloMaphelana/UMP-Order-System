<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Clear cart items from session
unset($_SESSION['cart']);

header("Location: ../../cart.php");
exit();
?>
