<?php
include("connection/connection.php");
include_once("admin/model/Food.php");

$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$food = new Food($conn);

$category = $food->getCategoryById($category_id);

$foodItems = $food->getFoodItemsByCategoryId($category_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category['name']); ?> - Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
<header class="header d-flex justify-content-between align-items-center px-4 py-3">
    <div class="container">
        <div class="row">
            <div class="col-2">
                <img src="images/logo.png" alt="UMP logo" class="logo">
            </div>
            <div class="col-10">
                <nav class="navbar navbar-expand-lg justify-content-end">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link text-success" href="#home"><i class="bi bi-house-fill"></i> Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-success" href="#notifications"><i class="bi bi-bell-fill"></i> Notifications</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-success" href="#orders"><i class="bi bi-list-check"></i> Orders</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-success" href="#cart"><i class="bi bi-cart-fill"></i> Cart</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-success" href="#about"><i class="bi bi-info-square-fill"></i> About Us</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <div class="dropdown">
        <button class="btn btn-light border-0 dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-fill text-success" style="font-size: 1.5rem;"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
            <li><a class="dropdown-item text-danger" href="model/logout.php">Logout</a></li>
        </ul>
    </div>
</header>

<div class="container mt-5">
    <?php if ($category) : ?>
        <h2 class="text-center"><?php echo htmlspecialchars($category['name']); ?></h2>
        <div class="row">
            <div class="col-12">
            <img src="admin/uploads/<?php echo htmlspecialchars($category['imageName']); ?>" class="img-fluid mb-4" alt="<?php echo htmlspecialchars($category['name']); ?>" style="max-width: 300px; height: auto;">
            </div>
        </div>
        <h3 class="mt-4">Available Meals</h3>
        <div class="row justify-content-center row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php if (!empty($foodItems)) : ?>
                <?php foreach ($foodItems as $item) : ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card h-100">
                            <img src="admin/foods/<?php echo htmlspecialchars($item['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item['name']); ?>" style="height: 200px;">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($item['description']); ?></p>
                                <p class="card-text text-success">R<?php echo htmlspecialchars($item['price']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No meals found in this category.</p>
            <?php endif; ?>
        </div>
    <?php else : ?>
        <p>Category not found.</p>
    <?php endif; ?>
</div>

<footer class="footer mt-auto py-3 bg-light">
    <div class="container text-center">
        <p>&copy; 2024 TechCafeSolutions. All rights reserved.</p>
        <p>Contact: info@techcafesolutions.com</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
