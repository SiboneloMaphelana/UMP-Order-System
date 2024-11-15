<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once("../../connection/connection.php");
include_once("Notifications.php");

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the selected notification type
    $notificationType = $_POST['notificationType'];

    // Fetch all customers from the database
    $query = "SELECT email, name FROM users";
    $result = $conn->query($query);
    $customers = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $customers[] = [
                'email' => $row['email'],
                'name' => $row['name']
            ];
        }
    }

    $notifications = new Notifications($conn);
    $sendResult = $notifications->sendBulkNotificationEmail($customers, $notificationType);

    // Check the result of the email sending process
    if ($sendResult === true) {
        $_SESSION['send_email_success'] = "Emails have been successfully sent to all customers.";
    } else {
        $_SESSION['send_email_error'] = "There was an error sending the emails.";
    }
}

// Close the database connection
$conn->close();

// Redirect to send_email page
header("Location: ../send_email.php");
exit;
