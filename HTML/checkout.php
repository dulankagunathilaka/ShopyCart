<?php
session_start();
$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
$cart = $_SESSION['cart'] ?? [];
$total = $_SESSION['cart_total'] ?? 0;

$fullName = $_SESSION['full_name'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ShopyCart Super Market</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="../lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Main CSS Stylesheet -->
    <link href="../css/style.css" rel="stylesheet">
</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar start -->
    <div class="container-fluid fixed-top">
        <div class="container px-0">
            <nav class="navbar navbar-light bg-white navbar-expand-xl">
                <a href="../HTML/userpage.php" class="navbar-brand">
                    <h1 class="text-primary display-6">Shopy Cart</h1>
                </a>
                <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>
                <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                    <div class="navbar-nav mx-auto">
                        <a href="../HTML/userpage.php" class="nav-item nav-link">Home</a>
                        <a href="../HTML/freshfinds.php" class="nav-item nav-link">Fresh Finds</a>
                        <a href="#fresh-finds" class="nav-item nav-link active">Checkout</a>
                    </div>

                    <div class="d-flex m-3 me-0">
                        <a href="../HTML/cart.php" class="position-relative me-4 my-auto">
                            <i class="fa fa-shopping-bag fa-2x"></i>
                            <span class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1" style="top: -5px; left: 15px; height: 20px; min-width: 20px;">3</span>
                        </a>
                        <a href="#" class="my-auto">
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link" data-bs-toggle="dropdown">
                                    <i class="fas fa-user fa-2x"></i>
                                </a>
                                <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                    <a href="#" class="btn border-secondary py-2 px-2 rounded-pill text-primary w-100 text-center"
                                        data-bs-toggle="modal" data-bs-target="#authModal">

                                        <h6><?php echo htmlspecialchars($fullName); ?></h6>

                                    </a>
                                    <hr class="dropdown-divider">
                                    <a href="../HTML/cart.php" class="dropdown-item">Ready to Checkout</a>
                                    <a href="../HTML/orderhistory.php" class="dropdown-item">Order History</a>
                                    <a href="../HTML/myaccount.php" class="dropdown-item">My Account</a>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->

    <!-- Checkout Page Start -->
    <div class="container-fluid py-5 mb-4 mt-5">
        <div class="container py-5">
            <h1 class="mb-4">Billing Details</h1>

            <form method="POST" action="../PHP/checkout.php">
                <div class="row g-5">
                    <div class="col-md-12 col-lg-6 col-xl-7">
                        <div class="row">
                            <div class="col-md-12 col-lg-6">
                                <label class="form-label my-3">First Name<sup>*</sup></label>
                                <input name="first_name" type="text" class="form-control" required>
                            </div>
                            <div class="col-md-12 col-lg-6">
                                <label class="form-label my-3">Last Name<sup>*</sup></label>
                                <input name="last_name" type="text" class="form-control" required>
                            </div>
                        </div>

                        <label class="form-label my-3">Email<sup>*</sup></label>
                        <input name="email" type="email" class="form-control" required>

                        <label class="form-label my-3">Address<sup>*</sup></label>
                        <input name="address" type="text" class="form-control" required>

                        <label class="form-label my-3">Contact Number<sup>*</sup></label>
                        <input name="contact_number" type="text" class="form-control" required>

                        <hr class="my-4">
                        <h5>Payment Method</h5>
                        <div class="form-check my-2">
                            <input type="radio" name="payment_method" value="Bank Transfer" class="form-check-input" required>
                            <label class="form-check-label">Bank Transfer</label>
                        </div>
                        <div class="form-check my-2">
                            <input type="radio" name="payment_method" value="Check Payments" class="form-check-input">
                            <label class="form-check-label">Check Payments</label>
                        </div>
                        <div class="form-check my-2">
                            <input type="radio" name="payment_method" value="Cash On Delivery" class="form-check-input">
                            <label class="form-check-label">Cash On Delivery</label>
                        </div>
                        <div class="form-check my-2">
                            <input type="radio" name="payment_method" value="Paypal" class="form-check-input">
                            <label class="form-check-label">PayPal</label>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">Place Order</button>
                    </div>

                    <div class="col-md-12 col-lg-6 col-xl-5">
                        <h4 class="mb-4">Your Order</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $subtotal = 0; // Initialize subtotal
                                foreach ($cart as $item):
                                    $itemTotal = $item['price'] * $item['quantity'];
                                    $subtotal += $itemTotal; // Add item total to subtotal
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['name']) ?></td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td>Rs.<?= number_format($itemTotal, 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="2"><strong>Subtotal</strong></td>
                                    <td><strong>Rs.<?= number_format($subtotal, 2) ?></strong></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Shipping</td>
                                    <td>Rs.250.00</td>
                                </tr>
                                <tr>
                                    <td colspan="2"><strong>Total</strong></td>
                                    <td><strong>Rs.<?= number_format($subtotal + 250.00, 2) ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Checkout Page End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/lightbox/js/lightbox.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- main Javascript -->
    <script src="../js/main.js"></script>
</body>

</html>