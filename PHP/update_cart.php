<?php
session_start();

if (isset($_POST['product_id'], $_POST['quantity'])) {
    $id = $_POST['product_id'];
    $qty = max(1, (int)$_POST['quantity']);

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity'] = $qty;
    }
}

header('Location: cart.php');
exit;
?>
