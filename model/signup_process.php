<?php
include_once("../connection/connection.php");
include_once("User.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new User($conn);

    $errors = $user->validateSignup($_POST);

    // Check if there are any validation errors
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: ../signup.php");
        exit;
    } else {
        if ($user->userExists($_POST['email'])) {
            $_SESSION['error'] = "User already exists!";
            header("Location: ../signup.php");
        } else {
            if ($user->signup($_POST)) {
                $_SESSION['success'] = "Signup successful!";
                header("Location: ../login.php");
            } else {
                $_SESSION['error'] = "Signup failed!";
                header("Location: ../signup.php");
            }
        }
    }
} else {
    // If the form is not submitted, redirect back to the signup form
    header("Location: ../signup.php");
    exit; 
}