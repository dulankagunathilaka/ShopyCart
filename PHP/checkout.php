<?php
session_start();

// Ensure the cart exists
$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and collect form data
    $firstName = htmlspecialchars(trim($_POST['first_name']));
    $lastName = htmlspecialchars(trim($_POST['last_name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $address = htmlspecialchars(trim($_POST['address']));
    $paymentMethod = $_POST['payment_method'] ?? '';

    // Simple validation
    if (!$firstName || !$lastName || !$email || !$address || !$paymentMethod) {
        echo "<script>alert('Please fill in all required fields.'); window.history.back();</script>";
        exit;
    }

    // (Optional) Save order to DB or send confirmation email here

    // Clear cart
    unset($_SESSION['cart']);
   
    // Set a flag for displaying the thank you message
    $_SESSION['order_success'] = "Your order has been successfully placed! Thank you for shopping with us.";

    // Redirect to userpage.php
    header("Location:../HTML/userpage.php");
    exit; // Ensure the script stops here
}
?>
