<?php
include("../../connection/connection.php");
include("Admin.php");

session_start();

$adminModel = new Admin($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_SESSION['id'];

    $name = $_POST['name'] ?? null; 
    $email = $_POST['email'] ?? null; 
    $phone_number = $_POST['phone_number'] ?? null; 
    
    
    $updateData = [];
    if ($name !== null) $updateData['name'] = $name; 
    if ($email !== null) $updateData['email'] = $email; 
    if ($phone_number !== null) $updateData['phone_number'] = $phone_number; 
    $file = isset($_FILES['image']) ? $_FILES['image'] : null; 
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

