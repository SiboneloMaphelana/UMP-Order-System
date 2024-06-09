<?php
include_once("../../connection/connection.php");
include_once("Admin.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin = new Admin($conn);

    $errors = $admin->validateSignup($_POST);

    // Check if there are any validation errors
    if (!empty($errors)) {
        // Store the validation errors in the session
        $_SESSION['errors'] = $errors;
        header("Location: ../register.php");
        exit;
    } else {
        if ($admin->userExists($_POST['email'])) {
            $_SESSION['error'] = "User already exists!";
            header("Location: ../register.php");
        } else {
            // Attempt to sign up the admin
            if ($admin->signup($_POST)) {
                $_SESSION['success'] = "Signup successful!";
                header("Location: ../login.php");
            } else {
                $_SESSION['error'] = "Signup failed!";
                header("Location: ../register.php");
            }
        }
    }
} else {
    // If the form is not submitted, redirect back to the signup form
    header("Location: ../register.php");
    exit; 
}