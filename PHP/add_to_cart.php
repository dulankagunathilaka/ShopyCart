<?php
session_start();

require_once '../HTML/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $productId = (int) $_POST['product_id'];

    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if ($product) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $quantity = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image_url'],
                'quantity' => $quantity
            ];
        }


        // Update cart_items in DB if user is logged in
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== "admin") {
            $userId = $_SESSION['user_id'];

            // Check if product is already in cart
            $checkStmt = $conn->prepare("SELECT quantity FROM cart_items WHERE user_id = ? AND product_id = ?");
            $checkStmt->bind_param("ii", $userId, $productId);
            $checkStmt->execute();
            $result = $checkStmt->get_result();

            if ($result && $result->num_rows > 0) {
                // Update quantity
                $updateStmt = $conn->prepare("UPDATE cart_items SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
                $updateStmt->bind_param("ii", $userId, $productId);
                $updateStmt->execute();
                $updateStmt->close();
            } else {
                // Insert new item
                $insertStmt = $conn->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, 1)");
                $insertStmt->bind_param("ii", $userId, $productId);
                $insertStmt->execute();
                $insertStmt->close();
            }
            $checkStmt->close();
        }
        echo 'Item added to cart';
    } else {
        echo 'Product not found';
    }
}
