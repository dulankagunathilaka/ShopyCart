<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../HTML/index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the product details
    $productName = $_POST['product_name'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    // Handle the image upload
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $imageName = $_FILES['product_image']['name'];
        $imageTmpName = $_FILES['product_image']['tmp_name'];
        $imageSize = $_FILES['product_image']['size'];
        $imageError = $_FILES['product_image']['error'];

        // Get the file extension
        $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

        // Allowed file types
        $allowedExt = array('jpg', 'jpeg', 'png', 'gif');

        // Check if the file is an allowed image type
        if (in_array($imageExt, $allowedExt)) {
            // Check the file size (max 5MB)
            if ($imageSize <= 5000000) {
                // Create a unique name for the file to avoid conflicts
                $imageNewName = uniqid('', true) . "." . $imageExt;

                // Define the upload path
                $uploadDir = "../uploads/";
                $imagePath = $uploadDir . $imageNewName;

                // Move the uploaded file to the uploads directory
                if (move_uploaded_file($imageTmpName, $imagePath)) {
                    // You can save product data along with the image path in your database here
                    echo "Product uploaded successfully!";
                } else {
                    echo "Error uploading the image.";
                }
            } else {
                echo "File size is too large. Maximum size allowed is 5MB.";
            }
        } else {
            echo "Invalid file type. Allowed types: jpg, jpeg, png, gif.";
        }
    } else {
        echo "No image uploaded.";
    }
}
?>
