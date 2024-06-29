<?php
include_once("partials/header.php");
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
    <title>Main Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
</head>
<body style="padding-top: 100px;">
    
    <div class="container mt-5">
        <h2 class="text-center">Here Is A Collection Of Our Delicious Meals</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($categories as $category) : ?>
                <div class="col mb-4">
                    <div class="card h-100">
                        <a href="category_details.php?id=<?php echo htmlspecialchars($category['id']); ?>" class="text-decoration-none text-success">
                            <img src="admin/uploads/<?php echo htmlspecialchars($category['imageName']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($category['name']); ?>" style="height: 200px;">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?php echo htmlspecialchars($category['name']); ?></h5>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Today's Specials -->
    <section class="container mt-5">
        <h2 class="text-center">Today's Specials</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <div class="col">
                <div class="card h-100">
                    <img src="images/dinner.jpeg" class="card-img-top" alt="Special 1">
                    <div class="card-body text-center">
                        <h5 class="card-title">Special 1</h5>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                    <img src="images/dinner.jpeg" class="card-img-top" alt="Special 2">
                    <div class="card-body text-center">
                        <h5 class="card-title">Special 2</h5>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                    <img src="images/dinner.jpeg" class="card-img-top" alt="Special 3">
                    <div class="card-body text-center">
                        <h5 class="card-title">Special 3</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>
   <?php include_once("partials/footer.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
