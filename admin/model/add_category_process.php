<?php
// Include necessary files
include("../../connection/connection.php");
include("Food.php");

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Create a new Food object
$food = new Food($conn);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get the form data
    $name = $_POST['name'];
    $image = $_FILES['image'];

    // Validate the inputs
    if (empty($name) || empty($image['name'])) {
        $_SESSION['error'] = "Name and image are required.";
        header("Location: ../add_category.php");
        exit();
    }

    // Set the target directory for file upload
    $target_dir = "../uploads/";
    
    // Set the target file path
    $target_file = $target_dir . basename($image["name"]);
    
    // Get the name of the uploaded image
    $imageName = basename($image['name']);
    
    // Move the uploaded image to the target directory
    if (move_uploaded_file($image["tmp_name"], $target_file)) {
        try {
            // Add the category to the database
            $result = $food->addCategory($name, $imageName);
            
            // Check the result of the operation
            if ($result === true) {
                // Category added successfully
                $_SESSION['add-cat'] = "New category added successfully";
                header("Location: ../all_categories.php");
                exit();
            } else {
                // Failed to add the category
                $_SESSION['fail-cat'] = $result;
                header("Location: ../add_category.php");
                exit();
            }
        } catch (Exception $e) {
            // Handle any unexpected exceptions
            error_log("Error adding category: " . $e->getMessage());
            $_SESSION['fail-cat'] = "An error occurred while adding the category. Please try again.";
            header("Location: ../add_category.php");
            exit();
        }
    } else {
        // Error occurred while uploading the image
        $_SESSION['fail-cat'] = "Error uploading image.";
        header("Location: ../add_category.php");
        exit();
    }
}
?>
