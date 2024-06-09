<?php
include("../../connection/connection.php");
include("Admin.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        die("Invalid user id");
    }

    $adminModel = new Admin($conn);

    // Extract data from POST request
    $id = intval($_POST['id']);
    $name = isset($_POST['name']) ? $_POST['name'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : null;

    // Check if the user id exists in the database
    $existingUser = $adminModel->getUserById($id);
    if (!$existingUser) {
        header("Location: ../edit_profile.php?id=" . urlencode($id) . "&error=" . urlencode("User with ID $id does not exist."));
        exit;
    }

    // Update user details
    $data = [];
    if ($name !== null) {
        $data['name'] = $name;
    }
    if ($email !== null) {
        $data['email'] = $email;
    }
    if ($phone_number !== null) {
        $data['phone_number'] = $phone_number;
    }

    $success = $adminModel->updateAdmin($id, $data);
    if ($success) {
        // Redirect with success message
        header("Location: ../profile.php");
        exit;
    } else {
        header("Location: ../edit_profile.php?id=" . urlencode($id) . "&error=" . urlencode("Failed to update user details."));
        exit;
    }
}

?>