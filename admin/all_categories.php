<?php
include("model/login_check.php");
include_once("../connection/connection.php");
include("model/food.php");

$food = new Food($conn);
$categories = $food->getCategories();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/stocks.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .category-image {
            height: 150px;
            object-fit: cover;
            width: 100%;
        }
    </style>
</head>

<body>

    <?php include("partials/sidebar.php"); ?>

    <div id="content" class="container mt-4">
    <div class="notification-bell" id="bell">
                <span class="badge" id="badge">0</span>
            </div>

        <?php
        if (isset($_SESSION['add-cat'])) {
            echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['add-cat']) . '</div>';
            unset($_SESSION['add-cat']);
        }
        ?>
        <h1 class="text-center mb-4">Categories</h1>
        <div class="d-flex justify-content-center mb-3">
            <a href="add_category.php" class="btn btn-success">Add Category</a>
        </div>
        <div class="row">
            <?php foreach ($categories as $category) : ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card">
                        <img src="uploads/<?php echo htmlspecialchars($category['imageName']); ?>" class="card-img-top category-image img-fluid lazyload" alt="<?php echo htmlspecialchars($category['name']); ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo htmlspecialchars($category['name']); ?></h5>
                        </div>
                        <div class="card-footer text-center">
                            <a href="update_category.php?id=<?php echo htmlspecialchars($category['id']); ?>" class="btn btn-primary btn-sm mb-2" aria-label="Edit Category"><i class="fas fa-edit"></i></a>
                            <a href="model/delete_category.php?id=<?php echo htmlspecialchars($category['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this category?');" aria-label="Delete Category"><i class="fas fa-trash-alt"></i></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script src="js/updateBell.js"></script>
    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
</body>

</html>
