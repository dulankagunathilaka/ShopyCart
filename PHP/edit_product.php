<?php
session_start();  // Start the session

// Check if the form is submitted with the required fields
if (isset($_POST['product_id'], $_POST['product_name'], $_POST['category'], $_POST['description'], $_POST['price'], $_POST['quantity'], $_POST['stock_status'])) {

    // Capture the form data
    $product_id = $_POST['product_id'];
    $name = $_POST['product_name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $stock_status = $_POST['stock_status'];

    // Database connection
    require_once '../HTML/db_connection.php';

    // Prepare the update query
    $query = "UPDATE products SET name = ?, category = ?, description = ?, price = ?, quantity = ?, stock_status = ? WHERE product_id = ?";
    $stmt = $conn->prepare($query);

    // Bind the parameters to the query
    $stmt->bind_param("ssssssi", $name, $category, $description, $price, $quantity, $stock_status, $product_id);

    // Execute the update query and check if it was successful
    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'Product successfully updated!';  // Set success message in session
    } else {
        $_SESSION['error_message'] = 'Error updating the product.';  // Optionally, set error message in session
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Redirect back to admin.php
    header('Location: ../HTML/admin.php');
    exit();  // Exit to stop further code execution
} else {
    // If the required data is not provided, redirect with an error message
    $_SESSION['error_message'] = 'Missing required data.';
    header('Location: ../HTML/admin.php');
    exit();
}
?>
