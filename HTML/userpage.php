<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../HTML/index.php");
    exit;
}
// Include your database connection
include '../HTML/db_connection.php';
// Fetch categories from the database (to ensure the products can be filtered)
$query = "SELECT DISTINCT category FROM products";
$category_result = $conn->query($query);

// Fetch all products for the All Products tab
$all_products_query = "SELECT * FROM products";
$all_products_result = $conn->query($all_products_query);

$query = "SELECT * FROM products ORDER BY created_at DESC LIMIT 5";
$featured_result = $conn->query($query);

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


    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
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
                        <a href="../HTML/userpage.php" class="nav-item nav-link active">Home</a>
                        <a href="#fresh-finds" class="nav-item nav-link">Fresh Finds</a>
                        <a href="../HTML/contact.php" class="nav-item nav-link">Contact</a>
                    </div>

                    <div class="d-flex m-3 me-0">
                        <button class="btn-search btn border border-secondary btn-md-square rounded-circle bg-white me-4" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fas fa-search text-primary"></i></button>
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

    <!-- Modal Search Start -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Search by keyword</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex align-items-center">
                    <div class="input-group w-75 mx-auto d-flex">
                        <label>
                            <input type="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                        </label>
                        <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Search End -->

    <!-- Hero Start -->
    <div class="container-fluid py-5 mb-5 hero-header">
        <div class="container py-5">
            <div class="row g-5 align-items-center">
                <div class="col-md-12 col-lg-7">
                    <h4 class="mb-3 text-secondary">Shopy Cart Super Market</h4>
                    <h1 class="mb-5 display-3 text-primary">Fresh Finds<br>Every Time!</h1>
                </div>
                <div class="col-md-12 col-lg-5">
                    <div id="carouselId" class="carousel slide position-relative" data-bs-ride="carousel">
                        <div class="carousel-inner" role="listbox">
                            <div class="carousel-item active rounded">
                                <img src="../img/hero-img-1.png" class="img-fluid w-100 h-100 bg-secondary rounded" alt="First slide">
                            </div>
                            <div class="carousel-item rounded">
                                <img src="../img/hero-img-2.jpg" class="img-fluid w-100 h-100 rounded" alt="Second slide">
                            </div>
                            <div class="carousel-item rounded">
                                <img src="../img/Bakery.jpg" class="img-fluid w-100 h-100 rounded" alt="Third slide">
                            </div>
                            <div class="carousel-item rounded">
                                <img src="../img/drinks.png" class="img-fluid w-100 h-100 rounded" alt="Fourth slide">
                            </div>
                            <div class="carousel-item rounded">
                                <img src="../img/snacks.jpg" class="img-fluid w-100 h-100 rounded" alt="Fifth slide">
                            </div>
                            <div class="carousel-item rounded">
                                <img src="../img/meat.jpg" class="img-fluid w-100 h-100 rounded" alt="Sixth slide">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselId" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselId" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero End -->

    <!-- Features Section Start -->
    <div class="container-fluid featurs py-5">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded bg-light p-4">
                        <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                            <i class="fas fa-car-side fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5>Free Shipping</h5>
                            <p class="mb-0">Free on order over Rs.2000</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded bg-light p-4">
                        <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                            <i class="fas fa-user-shield fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5>Security Payment</h5>
                            <p class="mb-0">100% security payment</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded bg-light p-4">
                        <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                            <i class="fas fa-exchange-alt fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5>30 Day Return</h5>
                            <p class="mb-0">30 day money guarantee</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded bg-light p-4">
                        <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                            <i class="fa fa-phone-alt fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5>24/7 Support</h5>
                            <p class="mb-0">Support every time fast</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Features Section End -->


    <!-- Shop Start -->
    <div id="fresh-finds" class="container-fluid fruite py-5">
        <div class="container py-5">
            <div class="tab-class text-center">
                <div class="row g-4">
                    <div class="col-lg-4 text-start">
                        <a href="freshfinds.html">
                            <h1>Fresh Finds</h1>
                        </a>
                    </div>
                    <div class="col-lg-8 text-end">
                        <ul class="nav nav-pills d-inline-flex text-center mb-5">
                            <?php
                            $categories = ['All Products', 'Fruits', 'Drinks', 'Meat', 'Snacks', 'Bakery', 'Vegetables'];
                            foreach ($categories as $index => $category):
                                $id = strtolower(str_replace(' ', '-', $category));
                            ?>
                                <li class="nav-item">
                                    <a class="d-flex m-2 py-2 bg-light rounded-pill <?= $index === 0 ? 'active' : '' ?>" data-bs-toggle="pill" href="#tab-<?= $id ?>">
                                        <span class="text-dark" style="width: 130px;"><?= $category ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <div class="tab-content">
                    <!-- All Products Tab -->
                    <div id="tab-all-products" class="tab-pane fade show active p-0">
                        <div class="row g-4">
                            <?php while ($product = $all_products_result->fetch_assoc()): ?>
                                <?= renderProductCard($product); ?>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <!-- Individual Category Tabs -->
                    <?php
                    $categoryNames = ['Fruits', 'Drinks', 'Meat', 'Snacks', 'Bakery', 'Vegetables'];
                    foreach ($categoryNames as $categoryName):
                        $stmt = $conn->prepare("SELECT * FROM products WHERE category = ?");
                        $stmt->bind_param("s", $categoryName);
                        $stmt->execute();
                        $result = $stmt->get_result();
                    ?>
                        <div id="tab-<?= strtolower($categoryName) ?>" class="tab-pane fade p-0">
                            <div class="row g-4">
                                <?php while ($product = $result->fetch_assoc()): ?>
                                    <?= renderProductCard($product); ?>
                                <?php endwhile; ?>
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
        ob_start(); ?>
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="rounded position-relative fruite-item h-100 d-flex flex-column border border-warning">
                <a href="#" onclick="showLoginMessage()" class="d-flex flex-column h-100 text-decoration-none">
                    <div class="fruite-img">
                        <img src="<?= htmlspecialchars($product['image_url']) ?>" class="img-fluid w-100 rounded-top" alt="">
                    </div>
                    <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">
                        <?= htmlspecialchars($product['category']) ?>
                    </div>
                    <div class="p-4 border-top-0 rounded-bottom d-flex flex-column justify-content-between flex-grow-1">
                        <h4><?= htmlspecialchars($product['name']) ?></h4>
                        <p><?= htmlspecialchars($product['description']) ?></p>
                        <div class="d-flex justify-content-between flex-wrap mt-auto">
                            <p class="text-dark fs-5 fw-bold mb-0">$<?= htmlspecialchars($product['price']) ?> / kg</p>
                            <!-- Add to cart form with AJAX -->
                            <form id="addToCartForm<?= $product['product_id'] ?>" class="add-to-cart-form" data-product-id="<?= $product['product_id'] ?>" method="POST">
                                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                <button type="button" class="btn border border-secondary rounded-pill px-3 text-primary" onclick="addToCart(<?= $product['product_id'] ?>)">
                                    <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                                </button>
                            </form>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    <?php return ob_get_clean();
    }
    ?>


    <!-- Fruits Shop End-->

    <!-- Features Start -->
    <div class="container-fluid service py-5">
        <div class="container py-5">
            <div class="row g-4 justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <a href="#">
                        <div class="service-item bg-secondary rounded border border-secondary">
                            <img src="../img/Freshness.jpg" class="img-fluid rounded-top w-100" alt="">
                            <div class="px-4 rounded-bottom">
                                <div class="service-content bg-primary text-center p-4 rounded">
                                    <h5 class="text-white">Freshness</h5>
                                    <h6 class="mb-0">Freshness is the quality of being new, natural, and not spoiled.</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 col-lg-4">
                    <a href="#">
                        <div class="service-item bg-dark rounded border border-dark">
                            <img src="../img/Delivery.jpg" class="img-fluid rounded-top w-100" alt="">
                            <div class="px-4 rounded-bottom">
                                <div class="service-content bg-light text-center p-4 rounded">
                                    <h5 class="text-primary">Fast Delivery</h5>
                                    <h6 class="mb-0">Speedy delivery right to your doorstep !</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 col-lg-4">
                    <a href="#">
                        <div class="service-item bg-primary rounded border border-primary">
                            <img src="../img/Discount.jpg" class="img-fluid rounded-top w-100" alt="">
                            <div class="px-4 rounded-bottom">
                                <div class="service-content bg-secondary text-center p-4 rounded">
                                    <h5 class="text-white">More Discounnts</h5>
                                    <h6 class="mb-0">Discount Up to 90%</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Features End -->

    <!-- Featured Section Start -->
    <div class="container-fluid vesitable py-5">
        <div class="container py-5">
            <h1 class="mb-0">Featured Products</h1>
            <div class="owl-carousel vegetable-carousel justify-content-center">
                <?php while ($product = $featured_result->fetch_assoc()): ?>
                    <div class="border border-primary rounded position-relative vesitable-item">
                        <div class="vesitable-img">
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="img-fluid w-100 rounded-top" alt="">
                        </div>
                        <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">
                            <?php echo htmlspecialchars($product['category']); ?>
                        </div>
                        <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                            <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                            <p><?php echo htmlspecialchars($product['description']); ?></p>
                            <div class="d-flex justify-content-between flex-lg-wrap">
                                <p class="text-dark fs-5 fw-bold mb-0">$<?php echo htmlspecialchars($product['price']); ?> / kg</p>
                                <!-- Add to cart form with AJAX -->
                                <form id="addToCartForm<?= $product['product_id'] ?>" class="add-to-cart-form" data-product-id="<?= $product['product_id'] ?>" method="POST">
                                    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                    <button type="button" class="btn border border-secondary rounded-pill px-3 text-primary" onclick="addToCart(<?= $product['product_id'] ?>)">
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


    <!-- Banner Section Start-->
    <div class="container-fluid banner bg-secondary my-5">
        <div class="container py-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                    <div class="py-4">
                        <h1 class="display-3 text-white">Fresh & Quality Products at</h1>
                        <p class="fw-normal display-3 text-dark mb-4">Shopy Cart</p>
                        <p class="mb-4 text-dark">Discover a wide selection of fresh, high-quality groceries and products, carefully sourced for your convenience and satisfaction.</p>
                        <a href="#fresh-finds" class="banner-btn btn border-2 border-white rounded-pill text-dark py-3 px-5">BUY</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="position-relative">
                        <img src="../img/baner-1.png" class="img-fluid w-100 rounded" alt="">
                        <div class="d-flex align-items-center justify-content-center bg-white rounded-circle position-absolute" style="width: 140px; height: 140px; top: 0; left: 0;">
                            <h1 style="font-size: 100px;">1</h1>
                            <div class="d-flex flex-column">
                                <span class="h2 mb-0">50$</span>
                                <span class="h4 text-muted mb-0">kg</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner Section End -->

    <!-- Fact Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="bg-light p-5 rounded">
                <div class="row g-4 justify-content-center">
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="counter bg-white rounded p-5">
                            <i class="fa fa-users text-secondary"></i>
                            <h4>satisfied customers</h4>
                            <h1>1963</h1>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="counter bg-white rounded p-5">
                            <i class="fa fa-users text-secondary"></i>
                            <h4>quality of service</h4>
                            <h1>99%</h1>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="counter bg-white rounded p-5">
                            <i class="fa fa-users text-secondary"></i>
                            <h4>quality certificates</h4>
                            <h1>33</h1>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="counter bg-white rounded p-5">
                            <i class="fa fa-users text-secondary"></i>
                            <h4>Available Products</h4>
                            <h1>789</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Fact Start -->

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
        <div class="container py-5">
            <div class="pb-4 mb-4" style="border-bottom: 1px solid rgba(226, 175, 24, 0.5) ;">
                <div class="row g-4">
                    <div class="col-lg-3">
                        <a href="#">
                            <h1 class="text-primary mb-0">Shopy Cart</h1>
                            <p class="text-secondary mb-0">Fresh Finds, Every Time !</p>
                        </a>
                    </div>
                    <div class="col-lg-9">
                        <div class="d-flex justify-content-end pt-3">
                            <a class="btn  btn-outline-secondary me-2 btn-md-square rounded-circle" href="+94 70 528 3688"><i class="fab fa-whatsapp"></i></a>
                            <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href="https://github.com/dulankagunathilaka"><i class="fab fa-github"></i></a>
                            <a class="btn btn-outline-secondary btn-md-square rounded-circle" href="https://l.facebook.com/l.php?u=https%3A%2F%2Flinkedin.com%2Fin%2Fdulanka-gunathilaka-93a184323%3Ffbclid%3DIwZXh0bgNhZW0CMTAAAR1NyYPNI4Xaf2F5q1fs8oMM4wN9RUCeqp9Fu4pVXcJ0MiqprUHW6fH-9rQ_aem_zr7h0A0cJoYeq9EDomuWLA&h=AT2WK_xj9Hm5rAlZ_obzmdYWAMD_0hKKwWoIPrIu96q4AqTv62qy17PsdduQPr8LUXy4F9E5taA43mtRUGxoW5VmjyHgk_FAgmjeUHhUXRlyn-Rx0f78s3SZymOCXg82uCBZ"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <div class="footer-item">
                        <h4 class="text-light mb-3">Why People Choose us!</h4>
                        <p class="mb-4"> Fresh & Quality Products<br>
                            Fast & Reliable Delivery<br>
                            Secure Payments<br>
                            24/7 Customer Support</p>
                        <a href="" class="btn border-secondary py-2 px-4 rounded-pill text-primary">Read More</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="d-flex flex-column text-start footer-item">
                        <h4 class="text-light mb-3">Home</h4>
                        <a class="btn-link" href="">Shop</a>
                        <a class="btn-link" href="">Deals & Offers</a>
                        <a class="btn-link" href="">Privacy Policy</a>
                        <a class="btn-link" href="">Contact</a>
                        <a class="btn-link" href="">Return Policy</a>
                        <a class="btn-link" href="">FAQs & Help</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="d-flex flex-column text-start footer-item">
                        <h4 class="text-light mb-3">Account</h4>
                        <a class="btn-link" href="">My Account</a>
                        <a class="btn-link" href="">Shopping Cart</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-item">
                        <h4 class="text-light mb-3">Contact</h4>
                        <p>Address: Colombo, Sri Lanka</p>
                        <p>Email: shopycartsupermarket@gmail.com</p>
                        <p>Phone: +94 70 528 3688</p>
                        <p>Payment Accepted</p>
                        <img src="../img/payment.png" class="img-fluid" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Copyright Start -->
    <div class="container-fluid copyright bg-dark py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <span class="text-light"><a href="#"><i class="fas fa-copyright text-light me-2"></i>Shopy Cart Super Market</a>, All right reserved.</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Copyright End -->



    <!-- Back to Top -->
    <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/lightbox/js/lightbox.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if (!empty($signupMessage)): ?>
                const message = <?= json_encode($signupMessage) ?>;
                const status = <?= json_encode($signupStatus) ?>;
                alert(message); // Show alert popup

                // Optionally switch to Sign In tab after successful sign up
                <?php if ($signupStatus === 'success'): ?>
                    const signInTab = document.querySelector('#signin-tab');
                    authModal = authModal || new bootstrap.Modal(document.getElementById('authModal'));
                    signInTab?.click();
                    authModal.show();
                <?php else: ?>
                    // Stay on Sign Up tab
                    const signUpTab = document.querySelector('#signup-tab');
                    const authModal = new bootstrap.Modal(document.getElementById('authModal'));
                    signUpTab?.click();
                    authModal.show();
                <?php endif; ?>
            <?php endif; ?>
        });
    </script>

    <!-- JavaScript for Add to Cart and Success Message -->
    <script>
        // Function to handle the Add to Cart functionality
        function addToCart(productId) {
            // Get the form using the product ID
            const form = document.getElementById('addToCartForm' + productId);

            // Create a FormData object to capture form data
            const formData = new FormData(form);

            // Use AJAX to send the form data to the PHP script without reloading the page
            fetch('../PHP/add_to_cart.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === 'Item added to cart') {
                        // Show the success message once the item is added to the cart
                        showSuccessMessage();
                    } else {
                        // Handle errors, if any
                        console.error('Error:', data);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        // Function to handle the Add to Cart functionality
        function addToCart(productId) {
            // Get the form using the product ID
            const form = document.getElementById('addToCartForm' + productId);

            // Create a FormData object to capture form data
            const formData = new FormData(form);

            // Use AJAX to send the form data to the PHP script without reloading the page
            fetch('../PHP/add_to_cart.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === 'Item added to cart') {
                        // Show the success message once the item is added to the cart
                        showSuccessMessage();
                    } else {
                        // Handle errors, if any
                        console.error('Error:', data);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        // Function to handle the Add to Cart functionality
        function addToCart(productId) {
            // Get the form using the product ID
            const form = document.getElementById('addToCartForm' + productId);

            // Create a FormData object to capture form data
            const formData = new FormData(form);

            // Use AJAX to send the form data to the PHP script without reloading the page
            fetch('../PHP/add_to_cart.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === 'Item added to cart') {
                        // Show the success message once the item is added to the cart
                        showSuccessMessage();
                    } else {
                        // Handle errors, if any
                        console.error('Error:', data);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        // Function to show a pop-up success message at the center with transparent background
        function showSuccessMessage() {
            // Create a div element for the pop-up
            const successMessage = document.createElement('div');
            successMessage.textContent = 'Item added successfully!';

            // Style the success message
            successMessage.style.position = 'fixed';
            successMessage.style.top = '50%';
            successMessage.style.left = '50%';
            successMessage.style.transform = 'translate(-50%, -50%)'; // Center the message
            successMessage.style.padding = '10px 20px'; // Smaller padding for a smaller message
            successMessage.style.backgroundColor = 'rgba(0, 0, 0, 0.5)'; // More transparent black background
            successMessage.style.color = 'white';
            successMessage.style.borderRadius = '8px'; // Smaller border radius
            successMessage.style.fontSize = '14px'; // Smaller font size
            successMessage.style.zIndex = '9999';
            successMessage.style.textAlign = 'center';
            successMessage.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.3)'; // Optional: Add shadow for better visibility
            successMessage.style.opacity = '1'; // Initially fully visible

            // Append the success message to the body
            document.body.appendChild(successMessage);

            // Add the animation to shrink and fade out
            successMessage.style.animation = 'shrinkAndFade 3s forwards'; // Apply animation

            // Remove the message after the animation completes (3 seconds)
            setTimeout(() => {
                successMessage.remove();
            }, 3000);
        }
    </script>

</body>

</html>