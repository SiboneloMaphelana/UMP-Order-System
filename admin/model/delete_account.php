<?php
session_start();
include_once("../../connection/connection.php");
include("Admin.php");

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$adminModel = new Admin($conn);

$id = $_POST['id'];

if ($adminModel->deleteAccount($id)) {
    session_destroy(); 
    header("Location: ../login.php");
    exit;
} else {
    $_SESSION['error'] = "Failed to delete the account.";
    header("Location: ../profile.php");
    exit;
}
?>

