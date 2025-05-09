<?php
session_start();
require_once '../HTML/db_connection.php';

// Check if the request is POST and product_id is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = (int) $_POST['product_id']; // Get product ID from POST data

    // Remove the product from session cart
    unset($_SESSION['cart'][$productId]);

    // If the user is logged in and not admin, remove the product from the database cart
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== "admin") {
        $userId = $_SESSION['user_id'];
        $stmt = $conn->prepare("DELETE FROM cart_items WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        $stmt->close();
    }

    header('Location:../HTML/cart.php');
    exit;
}
