<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <?php include_once('partials/sidebar.php'); ?>

    <div id="content">
        <div class="container mt-4">
            <?php
            if (isset($_SESSION['fail-cat'])) {
                echo '<div class="alert alert-danger">' . $_SESSION['fail-cat'] . '</div>';
                unset($_SESSION['fail-cat']);
            }
            ?>
            <h2 class="text-center">Add New Category</h2>
            <form action="model/add_category_process.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="categoryName" class="form-label">Category Name</label>
                    <input type="text" class="form-control" id="categoryName" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="categoryImage" class="form-label">Category Image</label>
                    <input type="file" class="form-control" id="categoryImage" name="image" required>
                </div>
                <button type="submit" class="btn btn-success">Add Category</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>