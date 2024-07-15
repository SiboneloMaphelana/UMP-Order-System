<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/navigation.css">
</head>

<body>

    <div class="container-fluid overflow-hidden">
        <div class="row vh-100 overflow-auto">
            <?php include("partials/navigation.php"); ?>
            <div class="col d-flex flex-column h-sm-100">
                <main class="row overflow-auto">
                    <?php
                    if (isset($_SESSION['fail-cat'])) {
                        echo '<div class="alert alert-danger">' . $_SESSION['fail-cat'] . '</div>';
                        unset($_SESSION['fail-cat']);
                    }
                    ?>
                    <h2 class="text-center">Add New Category</h2>
                    <form action="model/add_category_process.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="categoryName" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="categoryName" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="categoryImage" class="form-label">Category Image</label>
                            <input type="file" class="form-control" id="categoryImage" name="image" required>
                        </div>
                        <button type="submit" class="btn btn-success">Add Category</button>
                    </form>
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