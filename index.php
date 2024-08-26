<?php
include("connection/connection.php");
include_once("admin/model/Food.php");
$food = new Food($conn);

$categories = $food->getCategories();
$favorites = $food->getFavorites(); 
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/new.css">
</head>

<body>
    <?php include("partials/navigation.php"); ?>

    <div class="container-fluid overflow-hidden">
        <div class="row vh-100 overflow-auto">
            <div class="col d-flex flex-column h-sm-100">
                <main class="row overflow-auto main-content">
                    <h2 class="text-center text-muted">Here Is A Collection Of Our Delicious Meals </h2>
                    <!-- Category Cards -->
                    <div class="col-12">
                        <div class="container my-3">
                            <h2 class="text-center mb-4 text-muted">Explore Our Categories</h2>
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
                                <?php foreach ($categories as $category) : ?>
                                    <div class="col mb-4">
                                        <div class="card index-card h-100">
                                            <a href="category_details.php?id=<?php echo htmlspecialchars($category['id']); ?>" class="text-decoration-none text-muted">
                                                <img src="admin/uploads/<?php echo htmlspecialchars($category['imageName']); ?>" class="index-img" alt="<?php echo htmlspecialchars($category['name']); ?>">
                                                <div class="card-body text-center">
                                                    <h5 class="card-title">
                                                        <?php echo htmlspecialchars($category['name']); ?>
                                                    </h5>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Favorite Dishes -->
                    <div class="col-12">
                        <div class="container my-4">
                            <h2 class="text-center mb-4">Our Favorite Dishes</h2>
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
                                <?php foreach ($favorites as $favorite) : ?>
                                    <div class="col mb-4">
                                        <div class="card index-card h-100">
                                            <a href="category_details.php?id=<?php echo htmlspecialchars($favorite['id']); ?>" class="text-decoration-none text-muted">
                                                <img src="admin/foods/<?php echo htmlspecialchars($favorite['image']); ?>" class="index-img" alt="<?php echo htmlspecialchars($favorite['name']); ?>">
                                                <div class="index-card-body text-center">
                                                    <h5 class="card-title"><?php echo htmlspecialchars($favorite['name']); ?></h5>
                                                    <p class="card-text"><?php echo htmlspecialchars($favorite['description']); ?></p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </main>

                <!-- Footer -->
                <?php include("partials/footer.php"); ?>
            </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>


</body>

</html>