<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/auth.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
  <div class="container d-flex align-items-center justify-content-center">
    <div class="col-md-10 col-lg-8 col-xl-6">
      <div class="text-center mb-4">
        <img src="../images/logo.png" alt="Logo" class="logo img-fluid rounded-circle">
      </div>
      <div class="card">
        <?php if (isset($_SESSION['register_error'])) : ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['register_error'];
            unset($_SESSION['register_error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['register_error']) && !empty($_SESSION['register_error'])) : ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
              <?php foreach ($_SESSION['register_error'] as $error) : ?>
                <li><?php echo $error; ?></li>
              <?php endforeach;
              unset($_SESSION['register_error']); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>
        <h2 class="card-title text-center">Registration Form</h2>
        <div class="card-body">
          <form id="registrationForm" action="model/register_process.php" method="post" enctype="multipart/form-data">
            <div class="row mb-3">
              <label for="name" class="col-sm-4 col-form-label">Name</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="name" name="name">
                <span id="nameError" class="text-danger"></span>
              </div>
            </div>
            <div class="row mb-3">
              <label for="email" class="col-sm-4 col-form-label">Email</label>
              <div class="col-sm-8">
                <input type="email" class="form-control" id="email" name="email">
                <span id="emailError" class="text-danger"></span>
              </div>
            </div>
            <div class="row mb-3">
              <label for="phone_number" class="col-sm-4 col-form-label">Phone Number</label>
              <div class="col-sm-8">
                <input type="tel" class="form-control" id="phone_number" name="phone_number">
                <span id="phoneNumberError" class="text-danger"></span>
              </div>
            </div>

            <div class="row mb-3">
              <label for="role" class="col-sm-4 col-form-label">Role</label>
              <div class="col-sm-8">
                <select class="form-select" id="role" name="role">
                  <option value="admin">Admin</option>
                  <option value="staff">Staff</option>
                </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="password" class="col-sm-4 col-form-label">Password</label>
              <div class="col-sm-8">
                <div class="input-group">
                  <input type="password" class="form-control" id="password" name="password">
                  <i class="far fa-eye eye-icon" id="togglePassword"></i>
                </div>
                <span id="passwordError" class="text-danger"></span>
                <span id="passwordStrength" class="form-text"></span>
              </div>
            </div>
            <div class="row mb-3">
              <label for="confirm_password" class="col-sm-4 col-form-label">Confirm Password</label>
              <div class="col-sm-8">
                <div class="input-group">
                  <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                  <i class="far fa-eye eye-icon" id="toggleConfirmPassword"></i>
                </div>
                <span id="confirmPasswordError" class="text-danger"></span>
              </div>
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/validator@13.7.0/validator.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"></script>
  <script src="js/register.js"></script>

</body>

</html>