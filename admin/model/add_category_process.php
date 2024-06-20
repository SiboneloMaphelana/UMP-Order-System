<?php
include("../../connection/connection.php");
include("Food.php");

$food = new Food($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $image = $_FILES['image'];


    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($image["name"]);
    $imageName = basename($image['name']);
    
    if (move_uploaded_file($image["tmp_name"], $target_file)) {
        $result = $food->addCategory($name, $imageName);
        if ($result === true) {
            echo "New category added successfully";
            $_SESSION['success'] = "New category added successfully";
            header("Location: ../all_categories.php");
        } else {
            echo $result;
            $_SESSION['error'] = "Failed to add new category";
            header("Location: ../add_category.php");
        }
    } else {
        echo "Error uploading image.";
    }
}
?>
