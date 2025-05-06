<?php
session_start();

$cart = $_SESSION['cart'] ?? [];
$total = $_SESSION['cart_total'] ?? 0;
$shipping = 3.00;
$grand_total = $total + $shipping;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';
    $payment = $_POST['payment_method'] ?? '';

    // TODO: Validate input

    // TODO: Save to database (orders table and order_items table)

    // Clear cart
    unset($_SESSION['cart']);
    unset($_SESSION['cart_total']);

    // Redirect to success page
    header("Location: ../HTML/order_success.php");
    exit;
} else {
    echo "Invalid request.";
}
?>
