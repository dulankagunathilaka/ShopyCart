<?php
session_start();
include '../HTML/db_connection.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;

// Calculate the total price of the cart
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

// If the form is submitted, process the order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form inputs
    $firstName = htmlspecialchars(trim($_POST['first_name']));
    $lastName = htmlspecialchars(trim($_POST['last_name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $address = htmlspecialchars(trim($_POST['address']));
    $paymentMethod = $_POST['payment_method'] ?? '';
    $contactNumber = $_POST['contact_number'] ?? ''; // Optional if added to form

    // Validate the form inputs
    if (!$firstName || !$lastName || !$email || !$address || !$paymentMethod) {
        echo "<script>alert('Please fill in all required fields.'); window.history.back();</script>";
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email address.'); window.history.back();</script>";
        exit;
    }

    // Validate contact number (if you are accepting phone numbers)
    if ($contactNumber && !preg_match('/^\d{10}$/', $contactNumber)) {
        echo "<script>alert('Invalid contact number.'); window.history.back();</script>";
        exit;
    }

    // Prepare order data
    $customerName = $firstName . ' ' . $lastName;
    $items = [];
    $quantities = [];

    // Gather items and their quantities from the cart
    foreach ($cart as $item) {
        $items[] = $item['name'];
        $quantities[] = $item['quantity'];
    }

    $itemsStr = implode(", ", $items);
    $quantitiesStr = implode(", ", $quantities);

    $stmt = $conn->prepare("INSERT INTO order_tracking (customer_name, email, address, contact_number, items, quantities, total_price, payment_method, status, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Add a default 'Pending' status and associate the order with the user_id from session
    $status = 'Pending'; // Default status
    $userId = $_SESSION['user_id'];

    // Update bind_param to match the number of placeholders (10 total)
    $stmt->bind_param("ssssssdsis", $customerName, $email, $address, $contactNumber, $itemsStr, $quantitiesStr, $total, $paymentMethod, $status, $userId);

    // Execute the query
    if ($stmt->execute()) {
        // Delete from persistent cart (DB)
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== "admin") {
            $deleteStmt = $conn->prepare("DELETE FROM cart_items WHERE user_id = ?");
            $deleteStmt->bind_param("i", $userId);
            $deleteStmt->execute();
            $deleteStmt->close();
        }

        // Clear the cart from session
        unset($_SESSION['cart']);
        $_SESSION['order_success'] = "Your order has been successfully placed!";
        header("Location:../PHP/thank_you.php");
        exit;
    } else {
        echo "<script>alert('Error placing order: " . $stmt->error . "'); window.history.back();</script>";
        exit;
    }
}
