<?php
// Include necessary files and start the session
include("../../connection/connection.php");
include("Admin.php");

session_start();

$adminModel = new Admin($conn);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get the user ID from the session
    $id = $_SESSION['id'];

    // Get form data
    $name = $_POST['name'] ?? null; // Get name from form, default to null if not set
    $email = $_POST['email'] ?? null; // Get email from form, default to null if not set
    $phone_number = $_POST['phone_number'] ?? null; // Get phone number from form, default to null if not set
    
    // Create an array to store the data to be updated
    $updateData = [];
    if ($name !== null) $updateData['name'] = $name; // Add name to the update data if it is not null
    if ($email !== null) $updateData['email'] = $email; // Add email to the update data if it is not null
    if ($phone_number !== null) $updateData['phone_number'] = $phone_number; // Add phone number to the update data if it is not null

    // Get the uploaded image file
    $file = isset($_FILES['image']) ? $_FILES['image'] : null; // Get the image file from form, default to null if not set

    // Check if there is no data to be updated and no file is uploaded
    if (empty($updateData) && $file === null) {
        $_SESSION['error'] = "No data to update.";
        header("Location: ../profile.php");
        exit;
    }

    // Update admin details
    $success = $adminModel->updateAdmin($id, $updateData, $file); 
    if ($success) {
        $_SESSION['success'] = "User details updated successfully."; // Set success message
    } else {
        $_SESSION['error'] = "Failed to update user details."; // Set error message
    }
    
    // Redirect to profile page
    header("Location: ../profile.php");
    exit;
}
?>

