<?php
include_once("../connection/connection.php");
include_once("User.php");

session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user = new User($conn);

    // Get form data
    $id = isset($_POST["id"]) ? $_POST["id"] : null;
    $name = isset($_POST["name"]) ? $_POST["name"] : null;
    $surname = isset($_POST["surname"]) ? $_POST["surname"] : null;
    $email = isset($_POST["email"]) ? $_POST["email"] : null;
    $role = isset($_POST["role"]) ? $_POST["role"] : null;

    // Check if the user exists
    if (!$user->getUserById($id)) {
        $_SESSION['update_error'] = "User not found.";
        header("Location: ../edit_profile.php?");
        exit;
    }

    // Update the user details
    $updateResult = $user->updateUser($id, $name, $surname, $email, $role);

    // Check the result of the update operation
    if ($updateResult === true) {
        $_SESSION['update_success'] = "Details updated successfully.";
        header("Location: ../profile.php");
        exit;
    } else {
        $_SESSION['update_error'] = $updateResult;
        header("Location: ../edit_profile.php");
        exit;
    }
} else {
    // If the form is not submitted, redirect back to the edit profile page
    $_SESSION['update_error'] = "Form not submitted.";
    header("Location: ../edit_profile.php" . urlencode($_POST["id"]));
    exit;
}
?>
