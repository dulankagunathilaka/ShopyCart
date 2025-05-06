<?php
session_start();

if (isset($_POST['product_id'])) {
    $id = $_POST['product_id'];
    unset($_SESSION['cart'][$id]);
}

header('Location: ../HTML/cart.php');
exit;
?>
