<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../HTML/index.php");
    exit;
}
include '../HTML/db_connection.php';

$userId = $_SESSION['user_id'];
$fullName = $_SESSION['full_name'];

// Fetch categories from the database (to ensure the products can be filtered)
$category_query = "SELECT DISTINCT category FROM products";
$category_result = $conn->query($category_query);

// Prepare base query
$all_products_query = "SELECT * FROM products WHERE 1=1";

$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$selectedCategory = isset($_GET['category']) ? trim($_GET['category']) : '';

if ($searchTerm !== '') {
    $escaped = $conn->real_escape_string($searchTerm);
    $all_products_query .= " AND name LIKE '%$escaped%'";
}

if ($selectedCategory !== '') {
    $escapedCategory = $conn->real_escape_string($selectedCategory);
    $all_products_query .= " AND category = '$escapedCategory'";
}

$all_products_result = $conn->query($all_products_query);

// Fetch featured products (latest 5 products)
$featured_query = "SELECT * FROM products ORDER BY created_at DESC LIMIT 5";
$featured_result = $conn->query($featured_query);

// Fetch total cart count for logged-in user
$cartCount = 0;
$stmt = $conn->prepare("SELECT COALESCE(SUM(quantity), 0) AS total_quantity FROM cart_items WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($totalQuantity);
$stmt->fetch();
$stmt->close();
$cartCount = $totalQuantity;
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
                        <a href="#fresh-finds" class="nav-item nav-link active">Fresh Finds</a>
                    </div>

                    <!-- Search Bar -->
                    <form method="GET" action="" class="d-flex mx-3" style="flex: 1; max-width: 400px;">
                        <div class="input-group rounded-pill bg-light overflow-hidden shadow-sm w-100">
                            <input
                                type="text"
                                name="search"
                                class="form-control border-0 bg-light px-4"
                                placeholder="Search for products..."
                                value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                                style="border-radius: 50px 0 0 50px;">
                            <button
                                class="btn btn-primary px-4"
                                type="submit"
                                style="border-radius: 0 50px 50px 0;">
                                <i class="fas fa-search text-white"></i>
                            </button>
                        </div>
                    </form>

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

    <!-- Shop Start -->
    <?php
    // Define tab ID cleaner function
    function cleanTabId($name)
    {
        return strtolower(preg_replace('/[^a-z0-9]+/', '-', $name)); // replace non-alphanumeric with hyphens
    }
    ?>

    <div id="fresh-finds" class="container-fluid fruite py-5">
        <div class="container py-5">
            <div class="tab-class text-center">
                <div class="row g-4">
                    <div class="col-lg-12 text-center">
                        <ul class="nav nav-pills d-inline-flex text-center mb-5">
                            <?php
                            $categories = ['All Products', 'Fresh Produce', 'Meat & Seafood', 'Dairy & Eggs', 'Bakery', 'Beverages', 'Packaged Foods'];
                            foreach ($categories as $index => $category):
                                $id = cleanTabId($category);
                            ?>
                                <li class="nav-item">
                                    <a class="d-flex m-2 px-1 py-2 bg-light rounded-pill <?= $index === 0 ? 'active' : '' ?>" data-bs-toggle="pill" href="#tab-<?= $id ?>">
                                        <span class="text-dark" style="width: 130px;"><?= $category ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <div class="tab-content">
                    <!-- All Products Tab -->
                    <div id="tab-<?= cleanTabId('All Products') ?>" class="tab-pane fade show active p-0">
                        <div class="row g-4">
                            <?php if ($all_products_result->num_rows > 0): ?>
                                <?php while ($product = $all_products_result->fetch_assoc()): ?>
                                    <?= renderProductCard($product); ?>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="col-12 text-center">
                                    <p class="text-muted">No products found<?= htmlspecialchars($searchTerm) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Individual Category Tabs -->
                    <?php
                    foreach ($categories as $categoryName):
                        $tabId = cleanTabId($categoryName);
                        $stmt = $conn->prepare("SELECT * FROM products WHERE category = ?");
                        $stmt->bind_param("s", $categoryName);
                        $stmt->execute();
                        $result = $stmt->get_result();
                    ?>
                        <div id="tab-<?= $tabId ?>" class="tab-pane fade p-0">
                            <div class="row g-4">
                                <?php if ($result->num_rows > 0): ?>
                                    <?php while ($product = $result->fetch_assoc()): ?>
                                        <?= renderProductCard($product); ?>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <div class="col-12 text-center">
                                        <p class="text-muted">No products found in <?= htmlspecialchars($categoryName) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Shop End -->


    <?php
    // Reusable product card rendering function
    function renderProductCard($product)
    {
        ob_start();
        $isOutOfStock = $product['stock_status'] === 'Out of Stock';
    ?>
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="rounded position-relative fruite-item h-100 d-flex flex-column border border-warning">

                <!-- Only image, badges, name, and description are clickable -->
                <a href="product-details.php?product_id=<?= $product['product_id'] ?>" class="text-decoration-none flex-grow-1 d-flex flex-column">
                    <div class="fruite-img">
                        <img src="<?= htmlspecialchars($product['image_url']) ?>" class="img-fluid w-100 rounded-top" alt="">
                    </div>

                    <!-- Category Badge -->
                    <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">
                        <?= htmlspecialchars($product['category']) ?>
                    </div>

                    <!-- Out of Stock Badge -->
                    <?php if ($isOutOfStock): ?>
                        <div class="text-dark px-3 py-1 rounded position-absolute" style="top: 10px; right: 10px; background-color: rgba(255, 255, 255, 0.7); font-weight: bold;">
                            Out of Stock
                        </div>
                    <?php endif; ?>

                    <div class="p-4 border-top-0 rounded-bottom d-flex flex-column justify-content-between flex-grow-1">
                        <h4><?= htmlspecialchars($product['name']) ?></h4>
                        <p><?= htmlspecialchars($product['description']) ?></p>
                        <p class="text-dark fs-5 fw-bold mb-0">
                            Rs.<?= htmlspecialchars($product['price']) ?> / <?= htmlspecialchars($product['quantity']) ?>
                        </p>
                    </div>
                </a>

                <!-- Add to cart button outside the link -->
                <div class="p-3 pt-0">
                    <form id="addToCartForm<?= $product['product_id'] ?>" class="add-to-cart-form" data-product-id="<?= $product['product_id'] ?>" method="POST">
                        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                        <button type="button" class="btn border border-secondary rounded-pill px-3 text-primary w-100"
                            onclick="addToCart(<?= $product['product_id'] ?>)" <?= $isOutOfStock ? 'disabled' : '' ?>>
                            <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php
        return ob_get_clean();
    }
    ?>
    <!-- Shop End-->


    <!-- Featured Section Start -->
    <div class="container-fluid vesitable py-5">
        <div class="container py-5">
            <h1 class="mb-0">Featured Products</h1>
            <div class="owl-carousel vegetable-carousel justify-content-center">
                <?php while ($product = $featured_result->fetch_assoc()):
                    $isOutOfStock = $product['stock_status'] === 'Out of Stock';
                ?>
                    <div class="border border-primary rounded position-relative vesitable-item <?= $isOutOfStock ? 'opacity-50 pointer-events-none' : '' ?>">

                        <!-- ✅ Product Image Wrapped in <a> -->
                        <div class="vesitable-img">
                            <a href="product-details.php?product_id=<?= $product['product_id'] ?>">
                                <img src="<?= htmlspecialchars($product['image_url']) ?>" class="img-fluid w-100 rounded-top" alt="">
                            </a>
                        </div>

                        <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">
                            <?= htmlspecialchars($product['category']) ?>
                        </div>

                        <!-- Out of Stock Badge -->
                        <?php if ($isOutOfStock): ?>
                            <div class="text-dark px-3 py-1 rounded position-absolute" style="top: 10px; right: 10px; background-color: rgba(255, 255, 255, 0.7); font-weight: bold;">
                                Out of Stock
                            </div>
                        <?php endif; ?>

                        <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                            <h4><?= htmlspecialchars($product['name']) ?></h4>
                            <p><?= htmlspecialchars($product['description']) ?></p>
                            <div class="d-flex justify-content-between flex-lg-wrap">
                                <p class="text-dark fs-5 fw-bold mb-0">
                                    Rs.<?= htmlspecialchars($product['price']) ?> /
                                    <?= htmlspecialchars($product['quantity']) ?>
                                </p>

                                <!-- Add to cart form with AJAX -->
                                <form id="addToCartForm<?= $product['product_id'] ?>" class="add-to-cart-form" data-product-id="<?= $product['product_id'] ?>" method="POST">
                                    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                    <button type="button" class="btn border border-secondary rounded-pill px-3 text-primary"
                                        onclick="addToCart(<?= $product['product_id'] ?>)" <?= $isOutOfStock ? 'disabled' : '' ?>>
                                        <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <!-- Featured Section End -->

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

    <!-- freshfinds Javascript -->
    <script src="../js/freshfinds.js"></script>

</body>

</html>