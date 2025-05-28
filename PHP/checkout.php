<?php
session_start();
include '../HTML/db_connection.php';

// Get the full cart from session
$cart = $_SESSION['cart'] ?? [];
$total = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Check if Buy Now was used
    if (isset($_POST['buy_now']) && $_POST['buy_now'] == 1) {
        $productId = intval($_POST['product_id']);
        $quantity = intval($_POST['quantity']);

        // Fetch product details from database
        $stmt = $conn->prepare("SELECT name, price FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $stmt->bind_result($productName, $productPrice);
        $stmt->fetch();
        $stmt->close();

        // Get customer info
        $firstName = htmlspecialchars(trim($_POST['first_name']));
        $lastName = htmlspecialchars(trim($_POST['last_name']));
        $email = htmlspecialchars(trim($_POST['email']));
        $address = htmlspecialchars(trim($_POST['address']));
        $paymentMethod = $_POST['payment_method'] ?? '';
        $contactNumber = $_POST['contact_number'] ?? '';

        if (!$firstName || !$lastName || !$email || !$address || !$paymentMethod) {
            echo "<script>alert('Please fill in all required fields.'); window.history.back();</script>";
            exit;
        }

        $customerName = $firstName . ' ' . $lastName;
        $total = $productPrice * $quantity;
        $status = 'Pending';
        $userId = $_SESSION['user_id'];

        $stmt = $conn->prepare("INSERT INTO order_tracking 
            (customer_name, email, address, contact_number, items, quantities, total_price, payment_method, status, user_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $itemsStr = $productName;
        $quantitiesStr = $quantity;

        $stmt->bind_param(
            "ssssssdsis",
            $customerName,
            $email,
            $address,
            $contactNumber,
            $itemsStr,
            $quantitiesStr,
            $total,
            $paymentMethod,
            $status,
            $userId
        );

        if ($stmt->execute()) {
            $_SESSION['order_success'] = "Your order has been successfully placed!";
            header("Location: ../PHP/thank_you.php");
            exit;
        } else {
            echo "<script>alert('Error placing order: " . $stmt->error . "'); window.history.back();</script>";
            exit;
        }
    }

    // (rest of your existing cart-based checkout code continues here...)
}
