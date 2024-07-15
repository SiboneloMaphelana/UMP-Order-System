<?php

require_once '../connection/connection.php';
require_once 'model/Food.php';

$food = new Food($conn);


$foodItems = $food->getAllFoodItems();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/navigation.css">
    <link rel="stylesheet" href="css/admin.css">
</head>

<body>

    <div class="container-fluid overflow-hidden">
        <div class="row vh-100 overflow-auto">
            <?php include("partials/navigation.php"); ?>
            <div class="col d-flex flex-column h-sm-100">
                <main class="row overflow-auto">
                    <!-- Alerts -->
                    <?php
                    if (isset($_SESSION['success'])) {
                        echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                        unset($_SESSION['success']);
                    }
                    if (isset($_SESSION['error'])) {
                        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                        unset($_SESSION['error']);
                    }
                    ?>
                    <h2 class="text-center">Food Items</h2>
                    <div class="d-flex justify-content-center mb-3">
                        <a href="add_menu.php" class="btn btn-success">Add Food Item</a>
                    </div>

                    <div class="table-responsive mt-4">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Description</th>
                                    <th>Image</th>
                                    <th>Category</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($foodItems as $food) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($food['name']); ?></td>
                                        <td><?= htmlspecialchars($food['quantity']); ?></td>
                                        <td>R <?= htmlspecialchars($food['price']); ?></td>
                                        <td><?= htmlspecialchars($food['description']); ?></td>
                                        <td><img src="foods/<?= htmlspecialchars($food['image']); ?>" alt="Food Image" class="img-thumbnail"></td>
                                        <td><?= htmlspecialchars($food['Category']); ?></td>
                                        <td>
                                            <div class="btn-group-vertical">
                                                <a href="update_food.php?id=<?= htmlspecialchars($food['id']); ?>" class="btn btn-primary"><i class="fas fa-edit fa-xs"></i></a>
                                                <a href="model/delete_food.php?id=<?= htmlspecialchars($food['id']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this food item?')"><i class="fas fa-trash-alt fa-xs"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($foodItems)) : ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No food items found.</td>
                                    </tr>
                                <?php endif; ?>

                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    </div>
    </main>
    <footer class="row bg-light py-4 mt-auto">
        <div class="col">WE HAVE NO FOOTER, BEING GHOSTED</div>
    </footer>
    </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
</body>

</html>