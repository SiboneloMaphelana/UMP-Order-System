<?php 
include_once("../connection/connection.php");
include("model/Food.php");

$food = new Food($conn);

// Check if categoryId is set and not empty
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Get the category ID from the URL
    $categoryId = intval($_GET['id']);

    // Check if the category exists
    if ($food->isCategoryExists($categoryId)) {
        // Fetch the category details
        $category = $food->getCategoryById($categoryId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Category</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<!-- Header -->
<header class="header">
  <!-- Header content here -->
</header>

<!-- Main Content -->
<div class="container-fluid mt-4">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-3">
      <!-- Sidebar content here -->
    </div>

    <!-- Main Content Area -->
    <div class="col-md-9">
      <div class="content">
        <h2>Update Category</h2>
        <form action="model/update_category_process.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="categoryId" value="<?php echo $category['id']; ?>">
          <div class="mb-3">
            <label for="name" class="form-label">Category Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($category['name']); ?>">
          </div>
          <div class="mb-3">
            <label for="image" class="form-label">New Image</label>
            <input type="file" class="form-control" id="image" name="image">
          </div>
          <button type="submit" class="btn btn-primary">Update</button>
          <a href="all_categories.php" class="btn btn-secondary">Cancel</a>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="footer">
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/admin.js"></script>
</body>
</html>
<?php
      
    } else {
        // Redirect if the category does not exist
        header("Location: all_categories.php?error=" . urlencode("Category with ID $categoryId does not exist."));
        exit;
    }
} else {
    // Redirect if categoryId is not set or empty
    header("Location: all_categories.php?error=" . urlencode("Invalid category ID."));
    exit;
}
?>
