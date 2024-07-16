<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container-fluid overflow-hidden">
        <div class="row vh-100 overflow-auto">
            <?php include("partials/navigation.php"); ?>
            <div class="col d-flex flex-column h-sm-100">

                <h3 class="text-center mt-4">Update Account</h3>

                <div class="container mt-4">
                    <div class="card mx-auto" style="max-width: 400px;">
                        <div class="card-body">
                            <p class="card-text text-center">Update your profile.</p>

                            <form id="signupForm" action="model/edit_profile_process.php" method="POST" autocomplete="on">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id']); ?>">
                                <div class="mb-3">
                                    <label for="name" class="form-label text-success">First Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="surname" class="form-label text-success">Last Name</label>
                                    <input type="text" class="form-control" id="surname" name="surname" required>
                                </div>
                                <div class="mb-3">
                                    <label for="registration_number" class="form-label text-success">Registration Number</label>
                                    <input type="text" class="form-control" id="registration_number" name="registration_number" required>
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label text-success">Role</label>
                                    <select class="form-select form-control" id="role" name="role" required>
                                        <option selected disabled>Select Role</option>
                                        <option value="student">Student</option>
                                        <option value="lecturer">Lecturer</option>
                                        <option value="guest">Guest</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label text-success">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3 text-center">
                                    <button type="submit" class="btn btn-success rounded-pill px-5">Update</button><br>
                                </div>
                            </form>
                            <a href="profile.php" class="btn btn-success rounded-pill px-5 mt-4">Back to Profile</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"></script>
    <script src="js/signup.js"></script>
</body>
</html>
