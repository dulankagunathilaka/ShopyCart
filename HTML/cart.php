<?php
session_start();
include '../HTML/db_connection.php';

// Get session cart (for guest users)
$cart = $_SESSION['cart'] ?? [];

// Reset total
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

$fullName = $_SESSION['full_name'] ?? 'Guest';
$cartCount = 0;

// If user is logged in, fetch cart count from database
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT COALESCE(SUM(quantity), 0) AS total_quantity FROM cart_items WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($totalQuantity);
    $stmt->fetch();
    $stmt->close();

    $cartCount = $totalQuantity;
} else {
    // Guest user: use session cart
    foreach ($cart as $item) {
        $cartCount += $item['quantity'];
    }
}
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

    <!-- Stylesheet -->
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
                        <a href="#fresh-finds" class="nav-item nav-link active">Cart</a>
                    </div>

                    <div class="d-flex m-3 me-0">
                        <a href="../HTML/cart.php" class="position-relative me-4 my-auto">
                            <i class="fa fa-shopping-bag fa-2x"></i>
                            <span class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1" style="top: -5px; left: 15px; height: 20px; min-width: 20px;">
                                <?php echo htmlspecialchars($cartCount); ?>
                            </span>
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

    <!-- Cart Page Start -->
    <div class="container-fluid py-5 mb-4 mt-5">
        <div class="container py-5">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Products</th>
                            <th scope="col">Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                            <th scope="col">Handle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart as $id => $item): ?>
                            <tr>
                                <th scope="row">
                                    <div class="d-flex align-items-center">
                                        <img src="<?= htmlspecialchars($item['image']) ?>" class="img-fluid me-5 rounded-circle" style="width: 80px; height: 80px;" alt="">
                                    </div>
                                </th>
                                <td>
                                    <p class="mb-0 mt-4"><?= htmlspecialchars($item['name']) ?></p>
                                </td>
                                <td>
                                    <p class="mb-0 mt-4">Rs.<?= number_format($item['price'], 2) ?></p>
                                </td>
                                <td>
                                    <div class="input-group quantity mt-4" style="width: 100px;">
                                        <form method="POST" action="../PHP/update_cart.php" style="display: flex;">
                                            <input type="hidden" name="product_id" value="<?= $id ?>">
                                            <input type="number" name="quantity" value="<?= $item['quantity'] ?>" class="form-control form-control-sm text-center border-0">
                                        </form>
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0 mt-4">Rs.<?= number_format($item['price'] * $item['quantity'], 2) ?></p>
                                </td>
                                <td>
                                    <form method="POST" action="../PHP/remove_from_cart.php">
                                        <input type="hidden" name="product_id" value="<?= $id ?>">
                                        <button class="btn btn-md rounded-circle bg-light border mt-4" type="submit">
                                            <i class="fa fa-times text-danger"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="row g-4 justify-content-end">
                <div class="col-8"></div>
                <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                    <div class="bg-light rounded">
                        <div class="p-4">
                            <h1 class="display-6 mb-4">Cart <span class="fw-normal">Total</span></h1>

                            <!-- Subtotal -->
                            <div class="d-flex justify-content-between mb-4">
                                <h5 class="mb-0 me-4">Subtotal:</h5>
                                <p class="mb-0">Rs.<?= number_format($total, 2) ?></p>
                            </div>

                            <!-- Shipping -->
                            <div class="d-flex justify-content-between">
                                <h5 class="mb-0 me-4">Shipping</h5>
                                <div class="">
                                    <p class="mb-0">Delivery Charge: Rs.250</p>
                                </div>
                            </div>
                            <p class="mb-0 text-end">From Colombo, Sri Lanka</p>
                        </div>

                        <!-- Total -->
                        <div class="py-4 mb-4 border-top border-bottom d-flex justify-content-between">
                            <h5 class="mb-0 ps-4 me-4">Total</h5>
                            <p class="mb-0 pe-4">Rs.<?= number_format($total + 250.00, 2) ?></p>
                        </div>

                        <form action="../HTML/checkout.php" method="post">
                            <div class="px-4 pb-4">
                                <button type="submit" class="btn btn-primary w-100 py-3 rounded">Proceed to Checkout</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cart Page End -->

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