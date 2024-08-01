<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/auth.css">
    
</head>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <div class="col-md-3 sidebar"> <!-- Sidebar starts here -->
                <img src="images/logo.png" alt="Logo" class="logo mt-5" />
            </div>
            <div class="col-md-9 col-lg-10 ms-auto main-content"> <!-- Main content -->
                <main class="p-4">
                    <h3 class="text-center mt-4">Create Account</h3>
                    <div class="container mt-4">
                        <p class="text-center">
                            Welcome to Sign-Up, Fill In Your Details Below To Create An Account With Us.
                        </p>
                        <form id="signupForm" action="model/signup_process.php" method="POST" autocomplete="on">
                            <div class="row mb-3">
                                <div class="col-12 col-md-6">
                                    <label for="name" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter First Name" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="surname" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="surname" name="surname" placeholder="Enter Last Name" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="role" class="form-label">Role</label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option selected disabled>Select Role</option>
                                        <option value="student">Student</option>
                                        <option value="lecturer">Lecturer</option>
                                        <option value="guest">Guest</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                
                                <div class="col-12 col-md-6">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" aria-label="Password" aria-describedby="togglePassword" minlength="8" placeholder="Enter Password" required>
                                        <button class="btn" type="button" id="togglePassword">
                                            <i class="fas fa-eye" id="eyeIcon"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" aria-label="Confirm Password" aria-describedby="toggleConfirmPassword" placeholder="Confirm Password" required>
                                        <button class="btn" type="button" id="toggleConfirmPassword">
                                            <i class="fas fa-eye" id="eyeIconConfirm"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div id="passwordMismatchMessage" class="mb-3 text-danger text-center" style="display: none;">
                                Passwords do not match
                            </div>
                            <div class="mb-3 text-center">
                                <button type="submit" class="btn btn-success rounded-pill px-5">Sign Up</button>
                                <br>
                                <a href="login.php" class="link-success text-decoration-none">Login instead</a>
                            </div>
                        </form>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"></script>
    <script src="js/signup.js"></script>
</body>

</html>
