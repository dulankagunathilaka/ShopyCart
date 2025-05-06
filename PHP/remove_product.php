<?php
session_start();  // Start the session

require_once '../HTML/db_connection.php';  // Include database connection file

// Check if the product ID is passed through POST
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Prepare the delete statement
    $sql = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);  // Bind the product_id to the query

    // Execute the delete query
    $removeSuccessful = false;
    if ($stmt->execute()) {
        $removeSuccessful = true;
        $_SESSION['success_message'] = 'Product successfully removed!';  // Store success message in session
    } else {
        $_SESSION['error_message'] = 'Error while removing the product.';  // Optionally, store error message
    }

    $stmt->close();  // Close the statement
    $conn->close();  // Close the database connection

    // Redirect to admin.php to show the success message
    header('Location: ../HTML/admin.php');
    exit();  // Exit to prevent further code execution
} else {
    // If no product ID is provided, redirect with an error message
    $_SESSION['error_message'] = 'Product ID is missing.';
    header('Location: ../HTML/admin.php');
    exit();
}
?>
