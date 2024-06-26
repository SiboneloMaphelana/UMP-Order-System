<?php

// Require the necessary files
require_once '../../connection/connection.php';
require_once 'Food.php'; 

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create a new instance of the Food class
    $food = new Food($conn);

    // Get form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $categoryId = $_POST['category'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $image = $_FILES['image']; 
    $adminId = $_SESSION['id'];

    // Add the food item to the database
    $result = $food->addFoodItem($name, $description, $categoryId, $quantity, $price, $image, $adminId);

    // Check the result and redirect accordingly
    if ($result === true) {
        // Food item added successfully
        $_SESSION['success'] = "Food item added successfully!";
        header('Location: ../all_menus.php');
        exit();
    } else {
        // Error occurred while adding food item
        $errorMessage = "Failed to add food item: $result";
        $_SESSION['error'] = "Failed to add food item";
        header('Location: ../add_food.php?error=' . urlencode($errorMessage));
    }
} else {
    // If the form is not submitted, redirect back to the form page
    header('Location: ../add_food.php');
    exit();
}

