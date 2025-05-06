<?php
// Include the database connection
require_once 'db_connection.php';

// Fetch the total number of products from the database
$productCountResult = $conn->query("SELECT COUNT(*) AS total FROM products");

// Check if the query was successful and fetch the result
if ($productCountResult) {
    $productCount = $productCountResult->fetch_assoc()['total'];
} else {
    // If the query fails, set $productCount to 0
    $productCount = 0;
}

// Return the product count
return $productCount;
?>
