<?php

header('Content-Type: application/json');

include_once("../../connection/connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];
    
    $sql = "UPDATE orders SET status = ?, completed_at = CASE WHEN ? = 'completed' THEN CURRENT_TIMESTAMP ELSE completed_at END WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $status, $status, $order_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Failed to update status"]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "error" => "Invalid request"]);
}

$conn->close();
?>
