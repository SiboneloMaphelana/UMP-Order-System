<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/admin.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header text-center bg-primary text-white">
          <h2 class="card-title">Registration Form</h2>
          <?php
          session_start();
          if (isset($_SESSION['errors'])) {
              foreach ($_SESSION['errors'] as $error) {
                  echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
              }
              unset($_SESSION['errors']);
          }
          if (isset($_SESSION['success'])) {
              echo '<div class="alert alert-success" role="alert">' . $_SESSION['success'] . '</div>';
              unset($_SESSION['success']);
          }
          if (isset($_SESSION['error'])) {
              echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error'] . '</div>';
              unset($_SESSION['error']);
          }
          ?>
        </div>
        <div class="card-body">
          <form id="registrationForm" action="model/register_process.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
              <label for="phone_number" class="form-label">Phone Number</label>
              <input type="tel" class="form-control" id="phone_number" name="phone_number" required>
              <div id="phoneError" class="invalid-feedback"></div>
            </div>
            <div class="mb-3">
              <label for="image" class="form-label">Upload Profile Image</label>
              <input type="file" class="form-control" id="image" name="image">
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                  <i class="far fa-eye" id="eyeIcon"></i>
                </button>
              </div>
              <div class="strength-meter mt-2">
                <div id="passwordStrength" class="strength-meter-bar"></div>
                <div id="passwordStrengthText" class="strength-meter-text"></div>
              </div>
              <div id="passwordSuggestion" class="text-muted mt-2" style="display: none;"></div>
            </div>
            <div class="mb-3">
              <label for="confirm_password" class="form-label">Confirm Password</label>
              <div class="input-group">
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                  <i class="far fa-eye" id="eyeIconConfirm"></i>
                </button>
              </div>
              <div id="passwordMismatch" class="text-danger mt-2" style="display: none;">Passwords do not match!</div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Submit</button>
          </form>
        </div>
        <div class="card-footer text-center">
          <small class="text-muted">Already have an account? <a href="login.php">Login</a></small>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"></script>
<script src="js/admin.js"></script>
</body>
</html>
