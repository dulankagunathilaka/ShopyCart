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
    <style>
        body {
            background-color: #f8f9fa;
        }

        .thankyou-card {
            max-width: 600px;
            margin: auto;
            margin-top: 80px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .thankyou-icon {
            font-size: 60px;
            color: #81c408;
        }

        .card-title {
            color: #81c408;
        }

        .btn-continue {
            background-color: white;
            border: 2px solid #81c408;
            color: #81c408;
            transition: all 0.3s ease;
        }

        .btn-continue:hover {
            background-color: #81c408;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card thankyou-card">
            <div class="card-body text-center">
                <div class="thankyou-icon mb-3">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2 class="card-title">Thank you, <?= htmlspecialchars($fullName) ?>!</h2>
                <p class="lead mb-4">Your order has been confirmed successfully.</p>
                <a href="../HTML/userpage.php" class="btn btn-continue px-4 py-2 rounded-pill">
                    <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                </a>
            </div>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html>