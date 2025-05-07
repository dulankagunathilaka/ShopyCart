<?php
require_once '../HTML/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'];
    $statusInput = $_POST['status'];

    // Map status to human-readable message
    $statusText = '';
    if ($statusInput === 'accept') {
        $statusText = 'Your order is packing';
    } elseif ($statusInput === 'delivered') {
        $statusText = 'Your order out for delivery';
    }

    $stmt = $conn->prepare("UPDATE order_tracking SET status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $statusText, $orderId);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    echo "Status updated";
}
?>
