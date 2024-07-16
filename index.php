<?php
include("connection/connection.php");
include_once("admin/model/Food.php");
$food = new Food($conn);

$categories = $food->getCategories();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/navigation.css">
    <link rel="stylesheet" href="css/home.css">
</head>

<body>
    <div class="container-fluid overflow-hidden">
        <div class="row vh-100 overflow-auto">
            <?php include("partials/navigation.php"); ?>
            <div class="col d-flex flex-column h-sm-100">
                <main class="row overflow-auto">
                    <h1 class="mt-4 text-center">Welcome to the Restaurant</h1>
                    <div class="col-12">
                        <div class="container my-4">
                            <!-- Search Button -->
                            <div class="row justify-content-center mb-4">
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search categories...">
                                        <button class="btn btn-outline-success" type="button"><i class="fas fa-search"></i> Search</button>
                                    </div>
                                </div>
                            </div>
                            <!-- Category Cards -->
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
                                <?php foreach ($categories as $category) : ?>
                                    <div class="col mb-4">
                                        <div class="card h-100">
                                            <a href="category_details.php?id=<?php echo htmlspecialchars($category['id']); ?>" class="text-decoration-none text-success">
                                                <img src="admin/uploads/<?php echo htmlspecialchars($category['imageName']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($category['name']); ?>">
                                                <div class="card-body text-center">
                                                    <h5 class="card-title"><?php echo htmlspecialchars($category['name']); ?></h5>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </main>
                <footer class="row bg-light py-4 mt-auto">
                    <div class="col">Footer content here...</div>
                </footer>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
</body>

</html>
