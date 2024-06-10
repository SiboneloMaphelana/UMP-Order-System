<?php
include_once("../../connection/connection.php");
include("Food.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["id"]) || empty($_POST["id"])) {
        die("Invalid food ID.");
    }

    $foodModel = new Food($conn);

    $id = intval($_POST["id"]);
    $name = isset($_POST["name"]) ? $_POST["name"] : null;
    $description = isset($_POST["description"]) ? $_POST["description"] : null;
    $quantity = isset($_POST["quantity"]) ? intval($_POST["quantity"]) : null;
    $price = isset($_POST["price"]) ? floatval($_POST["price"]) : null;
    $image = isset($_FILES["image"]) ? $_FILES["image"] : null;
    $category = isset($_POST["category"]) ? intval($_POST["category"]) : null;

    if (!$foodModel->foodItemExists($id)){
        header("Location: ../update_food.php?id=" . urlencode($id) . "&error=" . urlencode("Food item with ID $id does not exist."));
        exit;
    }

    if (!empty($image["name"])) {
        $target_dir = "../foods/";
        $target_file = $target_dir . basename($image["name"]);
        $imageName = basename($image['name']);

        if (move_uploaded_file($image["tmp_name"], $target_file)) {
            $updateResult = $foodModel->updateFoodItem($id, $name, $quantity, $price, $description, $imageName, $category);
        } else {
            $updateResult = "Error uploading image.";
        }
    } else {
        $updateResult = $foodModel->updateFoodItem($id, $name, $quantity, $price, $description, null, $category);
    }

    if ($updateResult === true) {
        header("Location: ../all_menus.php?success=" . urlencode("Food item updated successfully"));
        exit;
    } else {
        header("Location: ../update_food.php?id=" . urlencode($id) . "&error=" . urlencode("Error updating food item: " . $updateResult));
        exit;
    }
} else {
    header("Location: ../update_food.php?id=" . urlencode($_POST["id"]));
    exit;
}
?>
