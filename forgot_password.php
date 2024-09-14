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
    <title>UMP - RESET PASSWORD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <?php if (isset($_SESSION["forgot_success"])) {
            echo '<div class="alert alert-success">' . $_SESSION["forgot_success"] . '</div>';
            unset($_SESSION["forgot_success"]);
        }

        if (isset($_SESSION["forgot_error"])) {
            echo '<div class="alert alert-danger">' . $_SESSION["forgot_error"] . '</div>';
            unset($_SESSION["forgot_error"]);
        }
        ?>
        <h3 class="text-center mt-4">RESET YOUR PASSWORD</h3>
        <p class="text-center mt-4">Forgot Your Password? Simply Enter Your Email To Request Password Reset</p>
        <form action="admin/model/forgot_password.php" method="post" class="mx-auto">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email">
            </div>
            <div class="mb-3 text-center">
                <button type="submit" class="btn btn-success rounded-pill px-5">Request</button><br>
                <a href="login.php" class="link-success text-decoration-none">Back to Login</a>
            </div>
        </form>
    </div>
</body>

</html>