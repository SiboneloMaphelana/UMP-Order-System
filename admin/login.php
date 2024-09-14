<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/login.css">
</head>

<body>

  <div class="container-fluid">
    <div class="main-content"> <!-- Main content -->
      <div class="text-center mb-4">
        <img src="../images/logo.png" alt="Logo" class="logo img-fluid rounded-circle">
      </div>

      <main class="card mx-auto">
        <?php if (isset($_SESSION['login_error'])) : ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['login_error'];
            unset($_SESSION['login_error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>
        <div class="card-body">
          <h3 class="card-title text-center">Login</h3>
          <p class="card-text">
            Welcome Back! Just Enter Your Details And See What We Can Serve You Today.
          </p>
          <form id="loginForm" action="model/login_process.php" method="post">
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="<?php echo isset($_COOKIE['admin_email']) ? $_COOKIE['admin_email'] : ''; ?>">
              <span id="emailError" class="error-message text-danger"></span>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" value="<?php echo isset($_COOKIE['admin_password']) ? $_COOKIE['admin_password'] : ''; ?>">
                <i class="fas fa-eye eye-icon" id="togglePassword"></i>
              </div>
              <span id="passwordError" class="error-message text-danger"></span>
            </div>
            <div class="mb-3">
              <label class="form-check-label">
                <input type="checkbox" class="form-check-input" name="remember"> Remember me
              </label>
            </div>
            <div class="mb-3">
              <p>
                Forgot Your Password? Click
                <a href="forgot_password.php" class="text-decoration-none">Here</a>
              </p>
            </div>
            <div class="mb-3 text-center">
              <button type="submit" class="btn btn-primary btn-rounded px-5">Login</button><br>
              <span class="text-muted">Don't have an account? <a href="register.php" class="text-decoration-none">Register here</a></span>
            </div>
          </form>
        </div>
      </main>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/validator/13.7.0/validator.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  <script src="js/login.js"></script>
</body>

</html>