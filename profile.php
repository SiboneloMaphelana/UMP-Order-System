<?php
include_once("connection/connection.php");
include("model/User.php");

$user = new User($conn);
$userDetails = $user->getUserById($_SESSION['id']);

// Check if user details are found
if (!$userDetails) {
    // Redirect to the login page if user details are not found
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile Page</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/styles.css">
  <!-- Bootstrap Icons CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
  <div class="container-fluid overflow-hidden">
    <div class="row vh-100 overflow-auto">
      <?php include("partials/navigation.php"); ?>
      <div class="col d-flex flex-column h-sm-100">

        <main class="row overflow-auto">
          <div class="container profile-container mt-5 d-flex justify-content-center">
            <div class="personal-card card">
              <div class="card-header">
                <h5 class="card-title text-center">User Profile</h5>
              </div>
              <div class="card-body">
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
                <div class="row mb-3">
                  <div class="col-md-3 d-flex align-items-center">
                    <label for="name" class="form-label me-3">Name</label>
                  </div>
                  <div class="col-md-9">
                    <input type="text" class="form-control" id="name" value="<?php echo htmlspecialchars($userDetails['name']); ?>" readonly>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-3 d-flex align-items-center">
                    <label for="surname" class="form-label me-3">Surname</label>
                  </div>
                  <div class="col-md-9">
                    <input type="text" class="form-control" id="surname" value="<?php echo htmlspecialchars($userDetails['surname']); ?>" readonly>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-3 d-flex align-items-center">
                    <label for="email" class="form-label me-3">Email</label>
                  </div>
                  <div class="col-md-9">
                    <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($userDetails['email']); ?>" readonly>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-3 d-flex align-items-center">
                    <label for="role" class="form-label me-3">Role</label>
                  </div>
                  <div class="col-md-9">
                    <input type="text" class="form-control" id="role" value="<?php echo htmlspecialchars($userDetails['role']); ?>" readonly>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-3 d-flex align-items-center">
                    <label for="registration_number" class="form-label me-3">Registration Number</label>
                  </div>
                  <div class="col-md-9">
                    <input type="tel" class="form-control" id="registration_number" value="<?php echo htmlspecialchars($userDetails['registration_number']); ?>" readonly>
                  </div>
                </div>
              </div>
              <div class="text-center">
                <a href="edit_profile.php?id=<?php echo htmlspecialchars($userDetails['id']); ?>" class="btn btn-primary edit-profile-button">
                  <i class="bi bi-pencil-fill"></i> Edit Profile
                </a>
                <a href="model/logout.php" class="btn btn-danger edit-profile-button ms-3">
                  <i class="bi bi-box-arrow-left"></i> Logout
                </a>
                <br>
                <a href="model/delete_account.php?id=<?php echo htmlspecialchars($userDetails['id']); ?>" class="btn btn-danger edit-profile-button ms-3">
                  <i class="bi bi-trash-fill"></i> Delete Account
                </a>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

</body>
</html>
