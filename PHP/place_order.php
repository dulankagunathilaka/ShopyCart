<?php
require_once '../HTML/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get customer input
    $customerName = $_POST['customer_name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $contactNumber = $_POST['contact_number'];
    $items = $_POST['items']; // e.g., "Product A, Product B"
    $quantities = $_POST['quantities']; // e.g., "2,1"
    $totalPrice = $_POST['total_price'];
    $paymentMethod = $_POST['payment_method'];
    $orderDate = date('Y-m-d H:i:s'); // Current date and time

    // Insert into order_tracking
    $stmt = $conn->prepare("INSERT INTO order_tracking (customer_name, email, address, contact_number, items, quantities, total_price, payment_method, order_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssdss", $customerName, $email, $address, $contactNumber, $items, $quantities, $totalPrice, $paymentMethod, $orderDate);

    if ($stmt->execute()) {
        echo "Order placed successfully!";
        // Redirect or display confirmation
    } else {
        echo "Failed to place order.";
    }

    $stmt->close();
    $conn->close();
}
