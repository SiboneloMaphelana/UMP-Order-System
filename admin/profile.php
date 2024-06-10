<?php
include_once("model/login_check.php");
include_once("../connection/connection.php");
include("model/Admin.php");

$adminModel = new Admin($conn);
$userDetails = $adminModel->getUserById($_SESSION['id']);

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
  <style>
    .profile-container {
      max-width: 500px;
      margin: auto;
      padding: 20px;
    }
    .profile-picture {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
    }
    .edit-profile-button {
      display: block;
      margin: auto;
      width: 150px;
    }
  </style>
</head>
<body>
  <div class="container profile-container mt-5">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title text-center">User Profile</h5>
      </div>
      <div class="card-body">
        <div class="text-center mb-3">
          <img src="https://via.placeholder.com/150" alt="Profile Picture" class="profile-picture">
          <input type="file" id="profile-image" class="form-control-file mt-2" style="display: none;">
        </div>
        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input type="text" class="form-control" id="name" value="<?php echo htmlspecialchars($userDetails['name']); ?>" readonly>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($userDetails['email']); ?>" readonly>
        </div>
        <div class="mb-3">
          <label for="phone_number" class="form-label">Phone Number</label>
          <input type="tel" class="form-control" id="phone_number" value="<?php echo htmlspecialchars($userDetails['phone_number']); ?>" readonly>
        </div>
        <div class="text-center">
          <a href="edit_profile.php?id=<?php echo htmlspecialchars($userDetails['id']); ?>" class="btn btn-primary edit-profile-button">Edit Profile</a><br>
          <a href="model/logout.php" class="btn btn-danger edit-profile-button">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
