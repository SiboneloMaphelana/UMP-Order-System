<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Alerts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/stocks.css">
</head>

<body>
    <?php include_once("partials/sidebar.php"); ?>

    <div id="content" class="container mt-4">
        <div class="notification-bell mb-4" id="bell">
            <span class="badge" id="badge">0</span>
        </div>
        <h2 class="text-center">Stock Levels</h2>
        <!-- Manage Inventory Button -->
        <div class="text-center mt-4">
            <a href="all_menus.php" class="btn btn-primary">
                <i class="fas fa-boxes"></i> Manage Inventory
            </a>
        </div>
        <div id="products" class="row">
            <!-- Low stock alerts will be inserted here -->
        </div>

    </div>

    <script src="js/stock.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/updateBell.js"></script>
</body>

</html>
