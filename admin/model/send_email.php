<?php
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

    // Instantiate the Notifications class and send the email
    $notifications = new Notifications($conn);
    $sendResult = $notifications->sendBulkNotificationEmail($customers, $notificationType);

    // Check the result of the email sending process
    if ($sendResult === true) {
        echo "Emails have been successfully sent to all customers.";
    } else {
        echo "There was an error sending the emails: " . $sendResult;
    }
}

$conn->close();