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
    <link rel="stylesheet" href="css/navigation.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>

    <div class="container-fluid overflow-hidden">
        <div class="row vh-100 overflow-auto">
            <?php include("partials/navigation.php"); ?>
            <div class="col d-flex flex-column h-sm-100">
                <main class="row overflow-auto">
                    <div class="personal-card card">
                        <div class="card-header">
                            <h5 class="card-title text-center">User Profile</h5>
                        </div>
                        <div class="card-body">
                            <?php if (isset($_SESSION['success'])) : ?>
                                <div class="alert alert-success"><?php echo $_SESSION['success']; ?></div>
                                <?php unset($_SESSION['success']); ?>
                            <?php endif; ?>
                            <?php if (isset($_SESSION['error'])) : ?>
                                <div class="alert alert-danger"><?php echo $_SESSION['error']; ?></div>
                                <?php unset($_SESSION['error']); ?>
                            <?php endif; ?>
                            <!-- Display user details -->
                            <div class="mb-3 row">
                                <label for="name" class="col-md-3 col-form-label text-md-end">Name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="name" value="<?php echo htmlspecialchars($userDetails['name']); ?>" readonly>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="email" class="col-md-3 col-form-label text-md-end">Email</label>
                                <div class="col-md-9">
                                    <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($userDetails['email']); ?>" readonly>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="phone_number" class="col-md-3 col-form-label text-md-end">Phone Number</label>
                                <div class="col-md-9">
                                    <input type="tel" class="form-control" id="phone_number" value="<?php echo htmlspecialchars($userDetails['phone_number']); ?>" readonly>
                                </div>
                            </div>
                            <!-- Buttons for editing, logout, and delete account -->
                            <div class="text-center">
                                <a href="edit_profile.php?id=<?php echo htmlspecialchars($userDetails['id']); ?>" class="btn btn-primary edit-profile-button"><i class="fas fa-user-edit"></i> Edit Profile</a><br>
                                <a href="model/logout.php" class="btn btn-danger edit-profile-button"><i class="fas fa-sign-out-alt"></i> Logout</a><br>
                                <button type="button" class="btn btn-danger edit-profile-button" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fas fa-trash-alt"></i> Delete Account</button>
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

                    <div class="card">
            <h2 class="text-center mb-4">Edit Profile</h2>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form action="model/edit_profile_process.php" method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" placeholder="Enter your name" name="name" value="<?php echo htmlspecialchars($userDetails['name']); ?>">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter your email" name="email" value="<?php echo htmlspecialchars($userDetails['email']); ?>">
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" placeholder="Enter your phone number" name="phone_number" value="<?php echo htmlspecialchars($userDetails['phone_number']); ?>">
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Profile Picture</label>
                    <input type="file" class="form-control" id="image" name="image">
                </div>
                <button type="submit" class="btn btn-primary d-block mx-auto">Save Changes</button>
            </form>
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