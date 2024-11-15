<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <?php include_once('partials/sidebar.php'); ?>

    <div id="content">
        <button class="btn btn-dark d-md-none" type="button" id="toggleSidebar">
            <i class="fas fa-bars"></i>
        </button>

        <div class="container mt-4">
            <h2 class="text-center">Send Notifications to Customers</h2>

            <!-- Session messages -->
            <?php
            if (isset($_SESSION['send_email_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['send_email_success'];
                    unset($_SESSION['send_email_success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif (isset($_SESSION['send_email_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['send_email_error'];
                    unset($_SESSION['send_email_error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="model/send_email.php" method="POST">
                <div class="mb-3">
                    <label for="notificationType" class="form-label">Select Notification Type</label>
                    <select class="form-select" id="notificationType" name="notificationType" required>
                        <option value="" disabled selected>Select a notification type</option>
                        <option value="site_down">Website Down</option>
                        <option value="closed_stock_taking">Restaurant Closed for Stock-Taking</option>
                        <option value="maintenance">Scheduled Maintenance</option>
                        <option value="new_feature">New Feature Announcement</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Send Email</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>