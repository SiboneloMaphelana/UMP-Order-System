<?php
include_once("model/login_check.php");
include_once("../connection/connection.php");
include("model/Admin.php");

$adminModel = new Admin($conn);
$userDetails = $adminModel->getUserById($_SESSION['id']);

if (!$userDetails) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <?php include_once("partials/sidebar.php"); ?>

    <div id="content" class="container-fluid">
        <button class="btn btn-dark d-md-none" type="button" id="toggleSidebar">
            <i class="fas fa-bars"></i>
        </button>
        <div class="row vh-100">
            <div class="col-md-10 mx-auto mt-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white text-center">
                        <h4>User Profile</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['admin_success'])) : ?>
                            <div class="alert alert-success"><?php echo $_SESSION['admin_success']; ?></div>
                            <?php unset($_SESSION['admin_success']); ?>
                        <?php endif; ?>

                        <!-- Display user details -->
                        <div class="mb-3 row">
                            <label for="name" class="col-md-4 col-form-label text-md-start">Name</label>
                            <div class="col-md-8">
                                <span class="form-control-plaintext" id="name">
                                    <?php echo htmlspecialchars($userDetails['name']); ?>
                                </span>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="email" class="col-md-4 col-form-label text-md-start">Email</label>
                            <div class="col-md-8">
                                <span class="form-control-plaintext" id="email">
                                    <?php echo htmlspecialchars($userDetails['email']); ?>
                                </span>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="phone_number" class="col-md-4 col-form-label text-md-start">Phone Number</label>
                            <div class="col-md-8">
                                <span class="form-control-plaintext" id="phone_number">
                                    <?php echo htmlspecialchars($userDetails['phone_number']); ?>
                                </span>
                            </div>
                        </div>

                        <!-- Buttons for editing, logout, and delete account -->
                        <div class="d-grid gap-2 d-md-flex ">
                            <a href="model/logout.php" class="btn btn-danger me-2">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fas fa-trash-alt"></i> Delete Account
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Delete Account Modal -->
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete your account?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <form action="model/delete_account.php" method="post">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($userDetails['id']); ?>">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Profile Card -->
                <div class="card mt-4 shadow-sm">
                    <div class="card-header bg-dark text-white text-center">
                        <h4>Edit Profile</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION["admin_error"])) : ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION["admin_error"]); ?></div>
                        <?php endif; ?>
                        <form action="model/edit_profile_process.php" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($userDetails['name']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($userDetails['email']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone_number" value="<?php echo htmlspecialchars($userDetails['phone_number']); ?>">
                            </div>
                            <button type="submit" class="btn btn-dark d-block mx-auto">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
</body>

</html>