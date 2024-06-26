<?php
include_once("../connection/connection.php");
include_once("User.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user = new User($conn);

    // Get form data
    $id = isset($_POST["id"]) ? $_POST["id"] : null;
    $name = isset($_POST["name"]) ? $_POST["name"] : null;
    $surname = isset($_POST["surname"]) ? $_POST["surname"] : null;
    $email = isset($_POST["email"]) ? $_POST["email"] : null;
    $registration_number = isset($_POST["registration_number"]) ? $_POST["registration_number"] : null;

    // Check if the user exists
    if (!$user->getUserById($id)) {
        header("Location: ../edit_profile.php?id=" . urlencode($id) . "&error=" . urlencode("User with ID $id does not exist."));
        exit;
    }

    // Update the user details
    $updateResult = $user->updateUser($id, $name, $surname, $email, $registration_number);

    // Check the result of the update operation
    if ($updateResult === true) {
        $_SESSION['success'] = "User details updated successfully.";
        header("Location: ../profile.php?success=" . urlencode("User details updated successfully"));
        exit;
    } else {
        $_SESSION['error'] = "Email or registration number already exists.";
        header("Location: ../profile.php?id=" . urlencode($id) . "&error=" . urlencode("Error updating user details: " . $updateResult));
        exit;
    }
} else {
    // If the form is not submitted, redirect back to the edit profile page
    header("Location: ../edit_profile.php?id=" . urlencode($_POST["id"]));
    exit;
}
?>
