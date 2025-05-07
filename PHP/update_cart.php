<?php
session_start();
require_once '../HTML/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = (int) $_POST['product_id'];
    $quantity = max(1, (int) $_POST['quantity']); // minimum 1

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    }

    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== "admin") {
        $userId = $_SESSION['user_id'];

        $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("iii", $quantity, $userId, $productId);
        $stmt->execute();
        $stmt->close();
    }

    header('Location: ../HTML/cart.php');
    exit;
}
?>
