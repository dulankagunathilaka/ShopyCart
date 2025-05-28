<?php
session_start();

require_once '../HTML/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../HTML/index.php");
    exit;
}

$userId = $_SESSION['user_id'];
$cartCount = 0;

// Get cart count
$stmt = $conn->prepare("SELECT COALESCE(SUM(quantity), 0) AS total_quantity FROM cart_items WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($totalQuantity);
$stmt->fetch();
$stmt->close();
$cartCount = $totalQuantity;

// Product loading logic
$product = null;

if (isset($_GET['product_id']) && !empty($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);

    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $product = $result->fetch_assoc();
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ShopyCart Super Market</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar Start -->
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
                        <a href="../HTML/product-details.php" class="nav-item nav-link active">Product Details</a>
                    </div>
                    <div class="d-flex m-3 me-0">
                        <button class="btn-search btn border border-secondary btn-md-square rounded-circle bg-white me-4" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fas fa-search text-primary"></i></button>
                        <a href="../HTML/cart.php" class="position-relative me-4 my-auto">
                            <i class="fa fa-shopping-bag fa-2x"></i>
                            <span class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1" style="top: -5px; left: 15px; height: 20px; min-width: 20px;">
                                <?php echo htmlspecialchars($cartCount); ?>
                            </span>
                        </a>
                        <a href="#" class="my-auto">
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link" data-bs-toggle="dropdown"><i class="fas fa-user fa-2x"></i></a>
                                <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                    <a href="../HTML/checkout.php" class="dropdown-item">Ready to checkout</a>
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

    <!-- Product Card Start -->
    <div class="container" style="margin-top: 120px; padding-top: 50px;">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden position-relative">

                    <!-- Category badge at top-right -->
                    <span class="position-absolute top-0 end-0 m-3 badge px-3 py-2 text-truncate"
                        style="background-color: #eafbe5; color: var(--bs-primary); max-width: 150px; font-size: 0.85rem;"
                        title="<?= htmlspecialchars($product['category']) ?>">
                        <?= htmlspecialchars($product['category']) ?>
                    </span>

                    <div class="row g-0">
                        <!-- Image Side -->
                        <div class="col-md-5 bg-light d-flex align-items-center justify-content-center p-3">
                            <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid rounded-3 shadow-sm" style="max-height: 300px; object-fit: contain;">
                        </div>

                        <!-- Details Side -->
                        <div class="col-md-7 p-4 d-flex flex-column">
                            <h2 class="fw-bold mb-1" style="color: #81c408;"><?= htmlspecialchars($product['name']) ?></h2>

                            <p class="text-muted mb-3" style="font-size: 0.95rem;"><?= htmlspecialchars($product['description']) ?></p>

                            <h4 class="text-dark mb-2">
                                Rs. <?= htmlspecialchars($product['price']) ?>
                                <small class="text-muted"> / <?= htmlspecialchars($product['quantity']) ?></small>
                            </h4>

                            <p id="totalPrice<?= $product['product_id'] ?>" class="fw-semibold text-success mb-2">
                                Total: Rs. <?= htmlspecialchars($product['price']) ?>
                            </p>

                            <p class="mb-3">
                                <strong>Status:</strong>
                                <?php if (strtolower($product['stock_status']) === 'in stock'): ?>
                                    <span class="text-success fw-semibold">In Stock</span>
                                <?php else: ?>
                                    <span class="text-danger fw-semibold">Out of Stock</span>
                                <?php endif; ?>
                            </p>

                            <div class="mb-3">
                                <label for="quantity<?= $product['product_id'] ?>" class="form-label">Quantity</label>
                                <input type="number" name="quantity" id="quantity<?= $product['product_id'] ?>"
                                    class="form-control form-control-sm w-50" min="1" value="1"
                                    onchange="updateTotalPrice(<?= $product['product_id'] ?>, <?= $product['price'] ?>)">
                            </div>

                            <div class="mt-auto d-flex flex-wrap gap-2">
                                <form id="addToCartForm<?= $product['product_id'] ?>" class="add-to-cart-form" data-product-id="<?= $product['product_id'] ?>" method="POST">
                                    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                    <button type="button" class="btn btn-sm text-white px-4" style="background-color: #81c408; border: none;"
                                        onclick="addToCart(<?= $product['product_id'] ?>)" <?= strtolower($product['stock_status']) !== 'in stock' ? 'disabled' : '' ?>>
                                        <i class="fas fa-cart-plus me-1"></i> Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div><!-- /card -->
            </div>
        </div>
    </div>
    <!-- Product Card End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/lightbox/js/lightbox.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="../js/main.js"></script>
    <script src="../js/product-details.js"></script>
    <script>
        function syncQuantityBeforeSubmit(productId) {
            var quantity = document.getElementById("quantity" + productId).value;
            document.getElementById("hiddenQuantity" + productId).value = quantity;
        }
    </script>


</body>

</html>