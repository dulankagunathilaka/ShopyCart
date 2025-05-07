<?php
session_start();
require_once '../HTML/db_connection.php'; // DB connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productName = $_POST['product_name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
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

    // Insert into products table
    $stmt = $conn->prepare("INSERT INTO products (name, category, description, price, quantity, stock_status, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $productName, $category, $description, $price, $quantity, $stockStatus, $imagePath);

    if ($stmt->execute()) {
        // Product added successfully — nothing else to do
    }

    $stmt->close();
    $conn->close();

    header("Location:../HTML/admin.php");
    exit;
}
?>
