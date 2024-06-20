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
    <title>UMP - <?php echo isset($category['name']) ? htmlspecialchars($category['name']) : 'Category Not Found'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
<header class="header fixed-top d-flex justify-content-between align-items-center px-2 py-1">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-9 order-md-2">
                <nav class="navbar navbar-expand-lg navbar-light justify-content-end">
                    <div class="container-fluid">
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
                                    <a class="nav-link text-success" href="cart.php"><i class="bi bi-cart-fill"></i> Cart</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-success" href="#about"><i class="bi bi-info-square-fill"></i> About Us</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="col-md-3 order-md-1">
                <img src="images/logo.jpeg" alt="UMP logo" class="logo img-fluid">
            </div>
        </div>
    </div>
    <!-- User dropdown -->
    <div class="dropdown ms-auto">
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
        <h3 class="mt-4">Available Meals</h3>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
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
                                        <button class="btn btn-success w-100" onclick="addToCart(<?php echo $item['id']; ?>)">Add to Cart</button>
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

<footer class="footer mt-auto py-3 bg-light">
    <div class="container text-center">
        <p>&copy; 2024 TechCafeSolutions. All rights reserved.</p>
        <p>Contact: info@techcafesolutions.com</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function addToCart(foodItemId) {
    var quantity = parseInt($('#foodModal' + foodItemId).find('.quantity-input').val());

    var price = parseFloat($('#foodModal' + foodItemId).find('.food-price').text().replace(/[^\d.-]/g, '')); 

    // Calculate total price
    var totalPrice = price * quantity;

    // Add item to cart
    $.ajax({
        url: 'admin/model/temp_cart.php', 
        method: 'POST',
        data: {
            foodItemId: foodItemId,
            quantity: quantity,
            price: totalPrice
        },
        success: function(response) {
            alert(response); // Show success or error message
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText); // Log any errors to the console
        }
    });
}

function changeQuantity(element, change) {
    var input = $(element).siblings('.quantity-input');
    var currentValue = parseInt(input.val());
    var newValue = currentValue + change;

    if (newValue < 1) {
        newValue = 1;
    }

    input.val(newValue);
}
</script>

</body>
</html>

