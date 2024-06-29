<?php
session_start();
include_once("../../connection/connection.php");
include("Food.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if categoryId is set and not empty
    if (!isset($_POST['categoryId']) || empty($_POST['categoryId'])) {
        $_SESSION['error'] = "Invalid category ID.";
        header("Location: ../update_category.php");
        exit;
    }

    $foodModel = new Food($conn);

    $categoryId = intval($_POST['categoryId']);
    $name = isset($_POST['name']) ? $_POST['name'] : null;
    $image = isset($_FILES['image']) ? $_FILES['image'] : null;

    // Check if the category ID exists in the database
    if (!$foodModel->isCategoryExists($categoryId)) {
        $_SESSION['error'] = "Category with ID $categoryId does not exist.";
        header("Location: ../update_category.php?id=" . urlencode($categoryId));
        exit;
    }

    // Handles image upload if a new image is provided
    if (!empty($image['name'])) {
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
        $_SESSION['success'] = "Category updated successfully.";
        header("Location: ../all_categories.php");
        exit;
    } else {
        $_SESSION['error'] = "Failed to update category. try again.";
        header("Location: ../update_category.php?id=" . urlencode($categoryId));
        exit;
    }

    header("Location: ../update_category.php?id=" . urlencode($categoryId));
    exit;
} else {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: ../update_category.php");
    exit;
}
?>
