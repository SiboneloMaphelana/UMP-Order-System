<?php
include("model/login_check.php");
include_once("../connection/connection.php");
include("model/food.php");

$food = new Food($conn);
$categories = $food->getCategories();
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

          <?php
          if (isset($_SESSION['add-cat'])) {
            echo '<div class="alert alert-success">' . $_SESSION['add-cat'] . '</div>';
            unset($_SESSION['add-cat']);
          }
          if (isset($_SESSION['fail-cat'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['fail-cat'] . '</div>';
            unset($_SESSION['fail-cat']);
          }
          ?>

<h1 class="text-center">Categories</h1>
          <div class="d-flex justify-content-center mb-3">
            <a href="add_category.php" class="btn btn-success mb-3">Add Category</a>
          </div>
          <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped table-hover">
              <thead class="table-dark">
                <tr>
                  <th scope="col">Name</th>
                  <th scope="col">Image</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($categories as $category) : ?>
                  <tr>
                    <td><?php echo htmlspecialchars($category['name']); ?></td>
                    <td><img src="uploads/<?php echo htmlspecialchars($category['imageName']); ?>" alt="<?php echo htmlspecialchars($category['name']); ?>" class="img-fluid"></td>
                    <td>
                      <div class="btn-group-vertical">
                        <a href="update_category.php?id=<?php echo htmlspecialchars($category['id']); ?>" class="btn btn-primary btn-sm mb-2"><i class="fas fa-edit"></i></a>
                        <a href="model/delete_category.php?id=<?php echo htmlspecialchars($category['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this category?');"><i class="fas fa-trash-alt"></i></a>
                      </div>
                    </td>

                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
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