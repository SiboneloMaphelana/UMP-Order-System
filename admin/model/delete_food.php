<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../../connection/connection.php';
require_once 'Food.php';

$food = new Food($conn);

if (isset($_GET['id'])) {
    $foodItemId = intval($_GET['id']);

    $result = $food->deleteFoodItem($foodItemId);

    if ($result === true) {
        $_SESSION['success'] = "Food item deleted successfully";
    } else {
        $_SESSION['error'] = $result;
    }
} else {
    $_SESSION['error'] = "Invalid request";
}

header("Location: ../all_menus.php");
exit();
