<?php
include("../../connection/connection.php");
include("Notifications.php");
// reset_password.php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $newPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Fetch user by token
    $stmt = $conn->prepare("SELECT user_id FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $reset = $result->fetch_assoc();
    $stmt->close();

    if ($reset) {
        // Update user's password
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $newPassword, $reset['user_id']);
        $stmt->execute();
        $stmt->close();

        // Delete token
        $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->close();

        $_SESSION['success'] = "Password has been reset successfully.";
        header("Location: ../../login.php");
    } else {
        $_SESSION['error'] = "Invalid or expired token.";
        header("Location: reset_password.php?token=" . $token);
    }

    exit();
} else {
    $token = $_GET['token'];
}
