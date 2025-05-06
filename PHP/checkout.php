<?php
session_start();
include '../HTML/db_connection.php'; // <-- Make sure this connects to your DB

$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = htmlspecialchars(trim($_POST['first_name']));
    $lastName = htmlspecialchars(trim($_POST['last_name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $address = htmlspecialchars(trim($_POST['address']));
    $paymentMethod = $_POST['payment_method'] ?? '';
    $contactNumber = $_POST['contact_number'] ?? ''; // optional if added to form

    if (!$firstName || !$lastName || !$email || !$address || !$paymentMethod) {
        echo "<script>alert('Please fill in all required fields.'); window.history.back();</script>";
        exit;
    }

    // Prepare order data
    $customerName = $firstName . ' ' . $lastName;
    $items = [];
    $quantities = [];

    foreach ($cart as $item) {
        $items[] = $item['name'];
        $quantities[] = $item['quantity'];
    }

    $itemsStr = implode(", ", $items);
    $quantitiesStr = implode(", ", $quantities);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO order_tracking (customer_name, email, address, contact_number, items, quantities, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $customerName, $email, $address, $contactNumber, $itemsStr, $quantitiesStr, $paymentMethod);
    $stmt->execute();
    $stmt->close();

    // Clear cart and redirect
    unset($_SESSION['cart']);
    $_SESSION['order_success'] = "Your order has been successfully placed!";
    header("Location:../PHP/thank_you.php");
    exit;
}
?>
