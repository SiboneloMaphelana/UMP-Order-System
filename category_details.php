<?php
session_start();
include("connection/connection.php");
include_once("admin/model/Food.php");

$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$food = new Food($conn);

$category = $food->getCategoryById($category_id);
$categories = $food->getCategories(); 
$foodItems = $food->getFoodItemsByCategoryId($category_id);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMP - <?php echo isset($category['name']) ? htmlspecialchars($category['name']) : 'Category Not Found'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/new.css">
    
</head>

<body>
    <div class="container-fluid overflow-hidden">
        <div class="row vh-100 overflow-auto">
            <!-- Desktop Navigation -->
            <?php include("partials/navigation.php"); ?>

            <!-- Category Navigation -->
            <?php if (!empty($categories)): ?>
            <nav class="category-nav bg-light py-2">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <ul class="nav nav-pills">
                                <?php foreach ($categories as $category): ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="category_details.php?id=<?php echo $category['id']; ?>">
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            <?php endif; ?>
            
            <main class="col d-flex flex-column h-sm-100">   
                <div class="row">
                    <?php if ($category) : ?>
                        <h2 class="text-center"><?php echo htmlspecialchars($category['name']); ?></h2>
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mt-3">
                            <?php if (!empty($foodItems)) : ?>
                                <?php foreach ($foodItems as $item) : ?>
                                    <div class="col mb-4">
                                        <div class="card h-100" data-bs-toggle="modal" data-bs-target="#foodModal<?php echo $item['id']; ?>">
                                            <img src="admin/foods/<?php echo htmlspecialchars($item['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item['name']); ?>" style="height: 200px;">
                                            <div class="card-body text-center">
                                                <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h5>
                                                <p class="card-text text-success">R<?php echo htmlspecialchars($item['price']); ?></p>
                                            </div>
                                        </div>

                                        <!-- Modal -->
                                        <div class="modal fade" id="foodModal<?php echo $item['id']; ?>" tabindex="-1" aria-labelledby="foodModalLabel<?php echo $item['id']; ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="foodModalLabel<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['name']); ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <img src="admin/foods/<?php echo htmlspecialchars($item['image']); ?>" class="img-fluid mb-3" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                                        <p><?php echo htmlspecialchars($item['description']); ?></p>
                                                        <p class="text-success food-price">R<?php echo htmlspecialchars($item['price']); ?></p>
                                                        <div class="d-flex justify-content-center align-items-center mb-3">
                                                            <button class="btn btn-outline-secondary btn-sm me-2" onclick="changeQuantity(this, -1)">-</button>
                                                            <input type="text" class="form-control text-center quantity-input" value="1" style="width: 50px;" readonly>
                                                            <button class="btn btn-outline-secondary btn-sm ms-2" onclick="changeQuantity(this, 1)">+</button>
                                                        </div>
                                                        <button class="btn btn-success w-100" onclick="addToCart(<?php echo $item['id']; ?>, '<?php echo htmlspecialchars($item['name']); ?>')">Add to Cart</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Modal -->
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
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/addToCart.js"></script>
</body>

</html>

