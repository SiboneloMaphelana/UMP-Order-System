<?php
include("connection/connection.php");
include_once("admin/model/Food.php");
$food = new Food($conn);

$categories = $food->getCategories();
$favorites = $food->getFavorites(); // Assuming you have a method to get favorite dishes

// Get the current time
$currentTime = new DateTime();

// Define meal times
$breakfastStart = new DateTime('07:00');
$breakfastEnd = new DateTime('11:30');
$lunchStart = new DateTime('12:00');
$lunchEnd = new DateTime('14:30');
$dinnerStart = new DateTime('17:00');
$dinnerEnd = new DateTime('19:30');

// Determine clickability of categories and display time
$categoryStatus = [];
foreach ($categories as $category) {
    $isClickable = true; // Default to true for unrestricted categories
    $displayTime = '';

    if ($category['name'] === 'Breakfast') {
        $displayTime = 'Available from 07:00 to 11:30';
        if ($currentTime < $breakfastStart || $currentTime > $breakfastEnd) {
            $isClickable = false;
            $displayTime = 'Not available now';
        }
    } elseif ($category['name'] === 'Lunch') {
        $displayTime = 'Available from 12:00 to 14:30';
        if ($currentTime < $lunchStart || $currentTime > $lunchEnd) {
            $isClickable = false;
            $displayTime = 'Not available now';
        }
    } elseif ($category['name'] === 'Dinner') {
        $displayTime = 'Available from 17:00 to 19:30';
        if ($currentTime < $dinnerStart || $currentTime > $dinnerEnd) {
            $isClickable = false;
            $displayTime = 'Not available now';
        }
    } else {
        $displayTime = 'Available all day'; // Unrestricted categories
    }

    $categoryStatus[] = [
        'category' => $category,
        'isClickable' => $isClickable,
        'displayTime' => $displayTime
    ];
}
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
                                <?php foreach ($categoryStatus as $status) : ?>
                                    <div class="col mb-4">
                                        <div class="card index-card h-100">
                                            <a href="category_details.php?id=<?php echo htmlspecialchars($status['category']['id']); ?>" class="text-decoration-none <?php echo $status['isClickable'] ? 'text-muted' : 'disabled-link'; ?>">
                                                <img src="admin/uploads/<?php echo htmlspecialchars($status['category']['imageName']); ?>" class="index-img" alt="<?php echo htmlspecialchars($status['category']['name']); ?>">
                                                <div class="card-body text-center">
                                                    <h5 class="card-title">
                                                        <?php echo htmlspecialchars($status['category']['name']); ?>
                                                        <small class="text-muted d-block"><?php echo htmlspecialchars($status['displayTime']); ?></small>
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
                <footer class="row bg-light py-4 mt-auto">
                    <div class="col text-center">
                        <p>&copy; 2024 TechCafe Solutions. All rights reserved.</p>
                        <a href="about.php" class="text-dark">About Us</a> |
                        <a href="contact.php" class="text-dark">Contact</a> |
                        <a href="privacy.php" class="text-dark">Privacy Policy</a>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JavaScript after Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>


</body>

</html>