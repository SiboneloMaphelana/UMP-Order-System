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
      <title>Admin Dashboard</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
      <link rel="stylesheet" href="css/styles.css">
    </head>

    <body>

      <?php include("partials/sidebar.php"); ?>
      <div id="content" class="container-fluid overflow-hidden">
        <button class="btn btn-dark d-md-none" type="button" id="toggleSidebar">
          <i class="fas fa-bars"></i>
        </button>
        <div class="container-fluid overflow-hidden">
          <div class="row vh-100 overflow-auto">
            <div class="col d-flex flex-column h-sm-100">
            <main class="row overflow-auto">
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
            </main>
            <footer class="row bg-light py-4 mt-auto">
      </div>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
      <script>
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
      <script src="js/admin.js"></script>
    </body>

    </html>
<?php

  } else {
    // Redirect if the category does not exist
    header("Location: all_categories.php");
    exit;
  }
} else {
  // Redirect if categoryId is not set or empty
  header("Location: all_categories.php");
  exit;
}
?>