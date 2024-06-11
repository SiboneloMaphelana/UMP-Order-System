<?php
include("../../connection/connection.php");
include("Admin.php");

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

    if (empty($updateData)) {
        $error = "No data to update.";
    } else {
        $success = $adminModel->updateAdmin($id, $updateData);
        if ($success) {
            header("Location: ../profile.php");
            exit;
        } else {
            $error = "Failed to update user details.";
        }
    }
}

?>