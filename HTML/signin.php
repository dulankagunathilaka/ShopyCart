<?php
require '../HTML/db_connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Admin login check
    if ($email === "admin@123" && $password === "123") {
        $_SESSION['user_id'] = "admin";
        $_SESSION['full_name'] = "Administrator";
        header("Location: ../HTML/admin.php");
        exit;
    }

    // Regular user login
    $stmt = $conn->prepare("SELECT user_id, full_name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['full_name'] = $user['full_name'];

            // ✅ Load cart from 'cart_items' table into session
            $cartStmt = $conn->prepare("
                SELECT ci.product_id, ci.quantity, p.name, p.price, p.image_url 
                FROM cart_items ci
                JOIN products p ON ci.product_id = p.product_id 
                WHERE ci.user_id = ?
            ");
            $cartStmt->bind_param("i", $user['user_id']);
            $cartStmt->execute();
            $cartResult = $cartStmt->get_result();

            $_SESSION['cart'] = [];
            while ($row = $cartResult->fetch_assoc()) {
                $_SESSION['cart'][$row['product_id']] = [
                    'name' => $row['name'],
                    'price' => $row['price'],
                    'image' => $row['image_url'],
                    'quantity' => $row['quantity']
                ];
            }

            $cartStmt->close();

            header("Location: ../HTML/userpage.php");
            exit;
        } else {
            echo "<script>alert('Invalid password'); window.location.href='../HTML/index.php';</script>";
        }
    } else {
        echo "<script>alert('Email not found'); window.location.href='../HTML/index.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
