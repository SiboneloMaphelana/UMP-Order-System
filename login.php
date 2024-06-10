<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/header.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>

<header class="header">
  <img src="images/logo.png" alt="UMP logo"/>
  <a href="welcome.php" class="btn btn-success close-button">X</a>
</header>

<h3 class="text-center mt-4">Login</h3>

<div class="container mt-4">
  <div class="card mx-auto" style="max-width: 400px;">
    <div class="card-body">
      <p class="card-text text-center">Welcome Back! Just Enter Your Details And See What We Can Serve You Today.</p>   
      <form id="loginForm" action="model/login_process.php" method="post">
        <div class="mb-3">
          <label for="email" class="form-label text-success">Email</label>
          <input type="email" class="form-control" id="email" name="email">
        </div>
        <div class="mb-3">
          <label for="password" class="form-label text-success">Password</label>
          <div class="input-group">
            <input type="password" class="form-control" id="password" name="password">
            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
              <i class="fas fa-eye"></i>
            </button>
          </div>
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
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script src="js/login.js"></script>
</body>
</html>
