<?php
require_once '../HTML/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $orderId = (int) $_POST['order_id'];
    $status = $_POST['status'];

    if ($status === 'accept') {
        $statusText = 'Packing';
    } elseif ($status === 'delivered') {
        $statusText = 'Out for Delivery';
    } else {
        $statusText = $status;
    }

    $stmt = $conn->prepare("UPDATE order_tracking SET status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $statusText, $orderId);

    if ($stmt->execute()) {
        echo "Status updated to $statusText";
    } else {
        echo "Failed to update status.";
    }
}
?>
