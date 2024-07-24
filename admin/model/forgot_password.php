<?php
include("../../connection/connection.php");
include("Notifications.php");
// forgot_password.php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Fetch user by email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user) {
        $notifications = new Notifications($conn);
        $notifications->sendPasswordResetEmail($user);
        $_SESSION['forgot_success'] = "Password reset email sent. Please check your inbox.";
        header("Location: ../../forgot_password.php");
    } else {
        $_SESSION['forgot_error'] = "No account found with that email address.";
        header("Location: ../../forgot_password.php");
    }

    header("Location: ../../forgot_password.php");
    exit();
}
