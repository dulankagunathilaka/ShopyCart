<?php
require_once '../HTML/db_connection.php';

// Total Products
$productCountResult = $conn->query("SELECT COUNT(*) AS total FROM products");
$productCount = $productCountResult->fetch_assoc()['total'];

// Orders Today
$today = date('Y-m-d');
$ordersTodayResult = $conn->query("SELECT COUNT(*) AS total FROM order_tracking WHERE DATE(order_date) = '$today'");
$ordersToday = $ordersTodayResult->fetch_assoc()['total'];

// Revenue Today
$revenueResult = $conn->query("SELECT SUM(total_price) AS revenue FROM order_tracking WHERE DATE(order_date) = '$today'");
$revenueToday = $revenueResult->fetch_assoc()['revenue'] ?? 0.00;
?>
