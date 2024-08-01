<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/auth.css">
</head>
<body>

  <div class="container-fluid">
      <div class="row flex-nowrap">
          <div class="col-md-3 sidebar"> <!-- Sidebar -->
              <img src="images/logo.png" alt="Logo" class="logo mt-5" />
          </div>
          <div class="col-md-9 main-content"> <!-- Main content -->

              <h3 class="text-center mt-4">Login</h3>

              <div class="container mt-4">
                  <main class="card mx-auto">
                      <div class="card-body">
                          <p class="card-text text-center">
                              Welcome Back! Just Enter Your Details And See What We Can Serve You Today.
                          </p>  
                          <form id="loginForm" action="model/login_process.php" method="post">
                              <div class="mb-3">
                                  <label for="email" class="form-label">Email</label>
                                  <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="<?php echo isset($_COOKIE['login_email']) ? $_COOKIE['login_email'] : ''; ?>">
                              </div>
                              <div class="mb-3">
                                  <label for="password" class="form-label">Password</label>
                                  <div class="input-group">
                                      <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" value="<?php echo isset($_COOKIE['login_password']) ? $_COOKIE['login_password'] : ''; ?>">
                                      <button class="btn" type="button" id="togglePassword">
                                          <i class="fas fa-eye"></i>
                                      </button>
                                  </div>
                              </div>
                              <div class="mb-3">
                                  <label class="form-check-label">
                                      <input type="checkbox" class="form-check-input" name="remember"> Remember me
                                  </label>
                              </div>
                              <div class="mb-3">
                                  <p>
                                      Forgot Your Password? Click
                                      <a href="forgot_password.php" class="link-success text-decoration-none">Here</a>
                                  </p>
                              </div>
                              <div class="mb-3 text-center">
                                  <button type="submit" class="btn btn-success rounded-pill px-5">Login</button><br>
                                  <a href="signup.php" class="link-success text-decoration-none">Don't have an account? Sign up here</a>
                              </div>
                          </form>
                      </div>
                  </main>
              </div>
          </div>
      </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  <script src="js/signup.js"></script>
  <script>
      // Wait for the DOM to load before executing the script
      document.addEventListener('DOMContentLoaded', function () {
          const passwordField = document.getElementById('password');
          const togglePassword = document.getElementById('togglePassword');

          // Toggle password visibility on click
          togglePassword.addEventListener('click', function () {
              const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
              passwordField.setAttribute('type', type);
              this.querySelector('i').classList.toggle('fa-eye-slash');
          });
      });
  </script>
</body>
</html>
