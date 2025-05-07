<?php
require_once '../HTML/db_connection.php';
session_start();

// Check if the user is logged in (session check)
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location:../HTML/index.php");
    exit();
}

// Get the user_id from the session
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get customer input from the form
    $customerName = $_POST['customer_name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $contactNumber = $_POST['contact_number'];
    $items = $_POST['items']; // e.g., "Product A, Product B"
    $quantities = $_POST['quantities']; // e.g., "2,1"
    $totalPrice = $_POST['total_price'];
    $paymentMethod = $_POST['payment_method'];
    $orderDate = date('Y-m-d H:i:s'); // Current date and time

    // Insert the order into the order_tracking table
    $stmt = $conn->prepare("INSERT INTO order_tracking (user_id, customer_name, email, address, contact_number, items, quantities, total_price, payment_method, order_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind parameters: "i" for integer (user_id), "s" for string (all other values)
    $stmt->bind_param("issssssdsd", $user_id, $customerName, $email, $address, $contactNumber, $items, $quantities, $totalPrice, $paymentMethod, $orderDate);

    // Execute the query
    if ($stmt->execute()) {
        echo "Order placed successfully!";
        // Optionally, redirect the user or show a confirmation message
    } else {
        echo "Failed to place order. Please try again.";
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
}
?>
