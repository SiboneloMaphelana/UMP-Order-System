<?php

require_once '../../connection/connection.php';
require_once 'Food.php'; 



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $food = new Food($conn);

    // Get form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $categoryId = $_POST['category'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $image = $_FILES['image']; 
    $adminId = $_SESSION['id'];

    $result = $food->addFoodItem($name, $description, $categoryId, $quantity, $price, $image, $adminId);

    // Check the result and redirect accordingly
    if ($result === true) {
        // Food item added successfully
        $_SESSION['success'] = "Food item added successfully!";
        header('Location: ../all_menus.php');
        exit();
    } else {
        $errorMessage = "Failed to add food item: $result";
        // Error occurred while adding food item
        $_SESSION['error'] = "Failed to add food item";
        header('Location: ../add_food.php?error=' . urlencode($errorMessage));
    }
} else {
    // If the form is not submitted, redirect back to the form page
    header('Location: ../add_food.php');
    exit();
}
?>
