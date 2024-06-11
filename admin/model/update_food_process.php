<?php
session_start();
include_once("../../connection/connection.php");
include("Food.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    if (!$foodModel->foodItemExists($id)) {
        $_SESSION['error'] = "Food item with ID $id does not exist.";
        header("Location: ../update_food.php?id=" . urlencode($id));
        exit;
    }

    if (!empty($image["name"])) {
        $target_dir = "../foods/";
        $target_file = $target_dir . basename($image["name"]);
        $imageName = basename($image['name']);

        if (move_uploaded_file($image["tmp_name"], $target_file)) {
            $updateResult = $foodModel->updateFoodItem($id, $name, $quantity, $price, $description, $imageName, $category);
        } else {
            $_SESSION['error'] = "Error uploading image.";
            header("Location: ../update_food.php?id=" . urlencode($id));
            exit;
        }
    } else {
        $updateResult = $foodModel->updateFoodItem($id, $name, $quantity, $price, $description, null, $category);
    }

    if ($updateResult === true) {
        $_SESSION['success'] = "Food item updated successfully.";
        header("Location: ../all_menus.php");
        exit;
    } else {
        $_SESSION['error'] = "Error updating food item: " . $updateResult;
        header("Location: ../update_food.php?id=" . urlencode($id));
        exit;
    }
} else {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: ../update_food.php");
    exit;
}
?>
