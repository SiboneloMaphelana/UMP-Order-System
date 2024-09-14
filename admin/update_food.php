<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../connection/connection.php';
require_once 'model/Food.php';


$food = new Food($conn);


$foodId = $_GET['id'];
$foodItem = $food->getFoodItemById($foodId);
$categories = $food->getCategories();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Food</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <?php include("partials/sidebar.php"); ?>
    <div id="content" class="container-fluid overflow-hidden">

        <div class="container-fluid overflow-hidden">
            <div class="row vh-100 overflow-auto">
                <div class="col d-flex flex-column h-sm-100">
                <main class="row overflow-auto">
                    <h2 class="mb-4">Edit Food Item</h2>
                    <form action="model/update_food_process.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($foodItem['id']); ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($foodItem['name']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity:</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" value="<?= htmlspecialchars($foodItem['quantity']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price:</label>
                            <input type="text" class="form-control" id="price" name="price" value="<?= htmlspecialchars($foodItem['price']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description:</label>
                            <textarea class="form-control" id="description" name="description"><?= htmlspecialchars($foodItem['description']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category" required>
                                <option selected disabled>Select category</option>
                                <?php foreach ($categories as $category) : ?>
                                    <option value="<?= $category['id']; ?>" <?= $category['id'] == $foodItem['category_id'] ? 'selected' : ''; ?>><?= htmlspecialchars($category['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image:</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </main>
                </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
</body>

</html>