<?php
include("../../connection/connection.php");
include("Food.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$food = new Food($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $name = $_POST['name'];
    $image = $_FILES['image'];

    if (empty($name) || empty($image['name'])) {
        $_SESSION['fail-cat'] = "Name and image are required.";
        header("Location: ../add_category.php");
        exit();
    }

    $target_dir = "../uploads/";
    
    $target_file = $target_dir . basename($image["name"]);
    
    $imageName = basename($image['name']);
    
    if (move_uploaded_file($image["tmp_name"], $target_file)) {
        try {
            $result = $food->addCategory($name, $imageName);
            
            if ($result === true) {
                $_SESSION['add-cat'] = "New category added successfully";
                header("Location: ../all_categories.php");
                exit();
            } else {
                $_SESSION['fail-cat'] = $result;
                header("Location: ../add_category.php");
                exit();
            }
        } catch (Exception $e) {
            error_log("Error adding category: " . $e->getMessage());
            $_SESSION['fail-cat'] = "An error occurred while adding the category. Please try again.";
            header("Location: ../add_category.php");
            exit();
        }
    } else {
        $_SESSION['fail-cat'] = "Error uploading image.";
        header("Location: ../add_category.php");
        exit();
    }
}
?>
