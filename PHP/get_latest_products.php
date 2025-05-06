<?php
include '../HTML/db_connection.php'; // Include your database connection

// Fetch latest 5 products
$query = "SELECT * FROM products ORDER BY created_at DESC LIMIT 5";
$result = $conn->query($query);

$products = [];
while ($product = $result->fetch_assoc()) {
    $products[] = $product;
}

// Return the results as JSON
echo json_encode($products);
?>
