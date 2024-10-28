<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../../connection/connection.php';
require_once 'Food.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $food = new Food($conn);

    $name = $_POST['name'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $image = $_FILES['image'];

    $result = $food->addSpecial($name, $description, $quantity, $price, $image, $start_date, $end_date);

    if ($result === true) {
        $_SESSION['menu_success'] = "Food item added successfully!";
        header('Location: ../all_specials.php');
        exit();
    } else {
        $errorMessage = "Failed to add food item: $result";
        $_SESSION['menu_error'] = "Failed to add food item";
        error_log($errorMessage);
        header('Location: ../add_special.php');
        exit();
    }
} else {
    header('Location: ../add_special.php');
    exit();
}
