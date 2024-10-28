<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once("connection/connection.php");
include_once("admin/model/Food.php");

$food = new Food($conn);

// Fetch all specials
$specials = $food->getSpecials(); // Assume this function retrieves all specials

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Specials - UMP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/new.css">
</head>

<body>
    <div class="container-fluid overflow-hidden">
        <div class="row vh-100 overflow-auto">
            <?php include_once("partials/navigation.php"); ?>
            <div class="col d-flex flex-column h-sm-100">
                <main class="row overflow-auto main-content">
                    <div class="col-12">
                        <div class="container my-4">
                            <h2 class="text-center">Specials</h2>
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 g-4 mt-3">
                                <?php if (!empty($specials)) : ?>
                                    <?php foreach ($specials as $special) : ?>
                                        <div class="col mb-4">
                                            <div class="card h-100" data-bs-toggle="modal" data-bs-target="#foodModal<?php echo $special['id']; ?>">
                                                <img src="admin/specials/<?php echo htmlspecialchars($special['image']); ?>" class="card-img-top lazyload" alt="<?php echo htmlspecialchars($special['name']); ?>" style="height: 200px;">
                                                <div class="card-body text-center">
                                                    <h5 class="card-title"><?php echo htmlspecialchars($special['name']); ?></h5>
                                                    <p class="card-text text-secondary">R<?php echo htmlspecialchars($special['price']); ?></p>
                                                </div>
                                            </div>

                                            <!-- Modal -->
                                            <div class="modal fade" id="foodModal<?php echo $special['id']; ?>" tabindex="-1" aria-labelledby="foodModalLabel<?php echo $special['id']; ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="foodModalLabel<?php echo $special['id']; ?>"><?php echo htmlspecialchars($special['name']); ?></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <img src="admin/specials/<?php echo htmlspecialchars($special['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($special['name']); ?>">
                                                            <p><?php echo htmlspecialchars($special['description']); ?></p>
                                                            <p class="text-secondary food-price">R<?php echo htmlspecialchars($special['price']); ?></p>
                                                            <div class="d-flex justify-content-center align-items-center mb-3">
                                                                <button class="btn btn-outline-secondary btn-sm me-2" onclick="changeQuantity(this, -1)">-</button>
                                                                <input type="text" class="form-control text-center quantity-input" value="1" style="width: 50px;" readonly>
                                                                <button class="btn btn-outline-secondary btn-sm ms-2" onclick="changeQuantity(this, 1)">+</button>
                                                            </div>
                                                            <button class="btn btn-outline-secondary w-100" onclick="addToCart(<?php echo $special['id']; ?>, '<?php echo htmlspecialchars($special['name']); ?>', 'specials')">Add to Cart</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Modal -->
                                        </div>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <p>No specials available at this time.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </main>
                <!-- Footer -->
                <?php include_once("partials/footer.php"); ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/addToCart.js"></script>
    <script>
        $(window).on('load', function() {
            $('.card-img-top').each(function() {
                var img = $(this);
                img.css('height', '200px'); // Set initial height
                img.css('object-fit', 'cover'); // Maintain aspect ratio
            });
        });
    </script>
</body>

</html>
