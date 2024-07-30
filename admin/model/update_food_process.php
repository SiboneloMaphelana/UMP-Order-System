<?php
session_start();
require_once '../../connection/connection.php';
require_once 'Food.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Check if the food ID is set and not empty
    if (!isset($_POST["id"]) || empty($_POST["id"])) {
        $_SESSION['error'] = "Invalid food ID.";
        header("Location: ../update_food.php");
        exit;
    }

    $foodModel = new Food($conn);

    $id = intval($_POST["id"]);
    $name = isset($_POST["name"]) ? $_POST["name"] : null;
    $description = isset($_POST["description"]) ? $_POST["description"] : null;
    $quantity = isset($_POST["quantity"]) ? intval($_POST["quantity"]) : null;
    $price = isset($_POST["price"]) ? floatval($_POST["price"]) : null;
    $image = isset($_FILES["image"]) ? $_FILES["image"] : null;
    $category = isset($_POST["category"]) ? intval($_POST["category"]) : null;

    // Check if the food item exists
    if (!$foodModel->foodItemExists($id)) {
        // Redirect with error message
        $_SESSION['error'] = "Food item with ID $id does not exist.";
        header("Location: ../update_food.php?id=" . urlencode($id));
        exit;
    }

    // Check if an image is uploaded
    if (!empty($image["name"])) {
        // Set the target directory and file name
        $target_dir = "../foods/";
        $target_file = $target_dir . basename($image["name"]);
        $imageName = basename($image['name']);

        // Move the uploaded image to the target directory
        if (move_uploaded_file($image["tmp_name"], $target_file)) {
            // Update the food item with the new details and image
            $updateResult = $foodModel->updateFoodItem($id, $name, $quantity, $price, $description, $imageName, $category);
        } else {
            // Redirect with error message
            $_SESSION['error'] = "Error uploading image.";
            header("Location: ../update_food.php?id=" . urlencode($id));
            exit;
        }
    } else {
        // Update the food item with the new details without image
        $updateResult = $foodModel->updateFoodItem($id, $name, $quantity, $price, $description, null, $category);
    }

    // Check the result of the update operation
    if ($updateResult === true) {
        // Redirect with success message
        $_SESSION['success'] = "Food item updated successfully.";
        header("Location: ../all_menus.php");
        exit;
    } else {
        // Redirect with error message
        $_SESSION['error'] = "Error updating food item: " . $updateResult;
        header("Location: ../update_food.php?id=" . urlencode($id));
        exit;
    }
} else {
    // Redirect with error message for invalid request method
    $_SESSION['error'] = "Invalid request method.";
    header("Location: ../update_food.php");
    exit;
}
?>
