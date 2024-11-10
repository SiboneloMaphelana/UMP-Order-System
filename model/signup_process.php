<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once("../connection/connection.php");
include_once("User.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new User($conn);

    $errors = $user->validateSignup($_POST);

    // Check if there are any validation errors
    if (!empty($errors)) {
        $_SESSION['signup_errors'] = $errors;
        header("Location: ../signup.php");
        exit;
    } else {
        if ($user->userExists($_POST['email'])) {
            $_SESSION['signup_user_errors'] = "User already exists!";
            header("Location: ../signup.php");
        } else {
            if ($user->signup($_POST)) {
                $_SESSION['signup_success'] = "Signup successful!, Login to continue";
                header("Location: ../login.php");
            } else {
                $_SESSION['signup_user_errors'] = "Signup failed!";
                header("Location: ../signup.php");
            }
        }
    }
} else {
    // If the form is not submitted, redirect back to the signup form
    header("Location: ../signup.php");
    exit;
}
