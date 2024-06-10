<?php
include_once("../../connection/connection.php");
include("Food.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if categoryId is set and not empty
    if (!isset($_POST['categoryId']) || empty($_POST['categoryId'])) {
        die("Invalid categoryId.");
    }

    $foodModel = new Food($conn);

    // Extract data from POST request
    $categoryId = intval($_POST['categoryId']);
    $name = isset($_POST['name']) ? $_POST['name'] : null;
    $image = isset($_FILES['image']) ? $_FILES['image'] : null;

    // Check if the category ID exists in the database
    if (!$foodModel->isCategoryExists($categoryId)) {
        header("Location: ../update_category.php?id=" . urlencode($categoryId) . "&error=" . urlencode("Category with ID $categoryId does not exist."));
        exit;
    }

    // Handle image upload if a new image is provided
    if (!empty($image['name'])) {
        // Handle image upload if a new image is provided
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($image["name"]);
        $imageName = basename($image['name']);
    
        // Check if file is uploaded successfully
        if (move_uploaded_file($image["tmp_name"], $target_file)) {
            // Update category with new image name
            $updateResult = $foodModel->updateCategory($categoryId, $name, $imageName);
        } else {
            $updateResult = "Error uploading image.";
        }
    } else {
        // Update category without changing the image name
        $updateResult = $foodModel->updateCategory($categoryId, $name);
    }

    // Check the result of the category update operation
    if ($updateResult === true) {
        header("Location: ../all_categories.php?success=" . urlencode("Category updated successfully"));
        exit;
    } else {
        header("Location: ../update_category.php?id=" . urlencode($categoryId) . "&error=" . urlencode("Error updating category: " . $updateResult));
        exit;
    }
} else {
    // Redirect if the request method is not POST
    header("Location: ../update_category.php?id=" . urlencode($_POST['categoryId']));
    exit;
}
?>
