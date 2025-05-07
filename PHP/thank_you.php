<?php
session_start();
$fullName = $_SESSION['full_name'] ?? "Customer";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="text-center">
            <h1 class="text-success">Thank you, <?= htmlspecialchars($fullName) ?>!</h1>
            <p class="lead">Your order has been confirmed.</p>
            <a href="../HTML/userpage.php" class="btn btn-primary mt-3">Continue Shopping</a>
        </div>
    </div>
</body>

</html>