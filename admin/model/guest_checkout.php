<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include connection and model files
include_once("../../connection/connection.php");
include_once("admin/model/Food.php");

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guest_checkout'])) {
    // Get the guest phone number from the form
    $guestPhone = trim($_POST['guest_phone']);

    // Validate the phone number format
    if (preg_match("/^\+27[0-9]{9}$/", $guestPhone)) {
        // Store the guest phone number in the session
        $_SESSION['guest_phone'] = $guestPhone;

        // Redirect to checkout page
        header("Location: https://7ab7-105-0-2-186.ngrok-free.app/UMP-Order-System/checkout.php");
        exit();
    } else {
        // Set an error message and redirect back to cart
        $_SESSION['error'] = "Invalid phone number format. Please enter a valid South African phone number.";
        header("Location: https://7ab7-105-0-2-186.ngrok-free.app/UMP-Order-System/cart.php");
        exit();
    }
} else {
    // Redirect to cart if the request method is not POST or 'guest_checkout' is not set
    header("Location: https://7ab7-105-0-2-186.ngrok-free.app/UMP-Order-System/cart.php");
    exit();
}
