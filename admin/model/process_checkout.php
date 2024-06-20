<?php
session_start();
include_once("../../connection/connection.php"); 
include_once("Food.php"); 

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Retrieve cart items for the logged-in user
$food = new Food($conn); 
$cartItems = $food->getCartItems($_SESSION['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $paymentMethod = $_POST['payment_method'];
    
    if ($paymentMethod === 'paypal') {
        // Redirect to PayPal for payment
        header("Location: paypal_payment.php");
        exit();
    } else if ($paymentMethod === 'collection') {
        header("Location: ../index.php");
        exit();
    }
}

// If the script reaches this point, something went wrong
header("Location: checkout.php");
exit();
?>
