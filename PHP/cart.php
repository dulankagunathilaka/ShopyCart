<?php
// Start the session
session_start();

// Include database connection (replace with your actual connection code)
include_once('db_connection.php'); // Make sure you connect to your database here

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from the POST request
    $user_id = 1; // You can dynamically get the user_id from the session or authentication
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity']; // Get quantity (default is 1, can be adjusted if needed)
    
    // Check if the item already exists in the cart for this user
    $sql = "SELECT * FROM cart_items WHERE user_id = ? AND product_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $product_id]);
    
    if ($stmt->rowCount() > 0) {
        // If the item exists, update the quantity
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        $new_quantity = $item['quantity'] + $quantity; // Increment the quantity

        $update_sql = "UPDATE cart_items SET quantity = ? WHERE cart_item_id = ?";
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->execute([$new_quantity, $item['cart_item_id']]);
    } else {
        // If the item doesn't exist in the cart, insert a new item
        $insert_sql = "INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $insert_stmt = $pdo->prepare($insert_sql);
        $insert_stmt->execute([$user_id, $product_id, $quantity]);
    }

    // Redirect the user to the cart page after adding the item
    header("Location: cart.php");
    exit();
}
?>
