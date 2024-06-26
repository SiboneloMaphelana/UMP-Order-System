<?php
// Include necessary files
include("../../connection/connection.php");
include("Food.php");

// Create a new Food object
$food = new Food($conn);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get the form data
    $name = $_POST['name'];
    $image = $_FILES['image'];

    // Set the target directory for file upload
    $target_dir = "../uploads/";
    
    // Set the target file path
    $target_file = $target_dir . basename($image["name"]);
    
    // Get the name of the uploaded image
    $imageName = basename($image['name']);
    
    // Move the uploaded image to the target directory
    if (move_uploaded_file($image["tmp_name"], $target_file)) {
        
        // Add the category to the database
        $result = $food->addCategory($name, $imageName);
        
        // Check the result of the operation
        if ($result === true) {
            
            // Category added successfully
            echo "New category added successfully";
            $_SESSION['success'] = "New category added successfully";
            
            // Redirect to the all categories page
            header("Location: ../all_categories.php");
        } else {
            
            // Failed to add the category
            echo $result;
            $_SESSION['error'] = "Failed to add new category";
            
            // Redirect to the add category page
            header("Location: ../add_category.php");
        }
    } else {
        
        // Error occurred while uploading the image
        echo "Error uploading image.";
    }
}
?>

