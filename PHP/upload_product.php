<?php
session_start();
require_once '../HTML/db_connection.php'; // This file should contain DB connection logic

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productName = $_POST['product_name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price']; // ✅ Get price from POST
    $stockStatus = ($quantity > 0) ? 'In Stock' : 'Out of Stock';
    $imagePath = '';

    // Handle image upload
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../PHP/uploads/';
        $imageName = basename($_FILES['product_image']['name']);
        $targetFile = $uploadDir . $imageName;

        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $targetFile)) {
            $imagePath = $targetFile;
        } else {
            die("Error uploading the image.");
        }
    } else {
        die("Image not uploaded.");
    }

    // ✅ Insert into products table (with price included)
    $stmt = $conn->prepare("INSERT INTO products (name, category, description, price, quantity, stock_status, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssiss", $productName, $category, $description, $price, $quantity, $stockStatus, $imagePath);

    if ($stmt->execute()) {
        // Optional: Add to order_tracking table with dummy values
        $orderId = uniqid('ORD');
        $customer = 'Admin Preview';
        $status = 'Processing';
        $progress = 0;

        $insertOrder = $conn->prepare("INSERT INTO order_tracking (order_id, customer, status, progress) VALUES (?, ?, ?, ?)");
        $insertOrder->bind_param("sssi", $orderId, $customer, $status, $progress);
        $insertOrder->execute();
    }

    $stmt->close();
    $conn->close();

    header("Location:../HTML/admin.php"); // Redirect to admin panel
    exit;
}
?>
