<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Optional: redirect to login if not logged in
    header("Location: ../index.php");
    exit;
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


    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../css/style.css" rel="stylesheet">

    <style>
        .wishlist-card img {
            width: 100px;
            /* Fixed width */
            height: 100px;
            /* Fixed height */
            object-fit: cover;
            /* Ensures proper image display */
        }

        .stock-status {
            font-weight: bold;
        }
    </style>
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
                <a href="../HTML/index.php" class="navbar-brand">
                    <h1 class="text-primary display-6">Shopy Cart</h1>
                </a>
                <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>
                <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                    <div class="navbar-nav mx-auto">
                        <a href="../HTML/index.php" class="nav-item nav-link">Home</a>
                        <a href="../HTML/freshfinds.php" class="nav-item nav-link">Fresh Finds</a>
                        <a href="#fresh-finds" class="nav-item nav-link active">Wishlist</a>
                    </div>
                    <div class="d-flex m-3 me-0">
                        <button class="btn-search btn border border-secondary btn-md-square rounded-circle bg-white me-4" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fas fa-search text-primary"></i></button>
                        <a href="cart.html" class="position-relative me-4 my-auto">
                            <i class="fa fa-shopping-bag fa-2x"></i>
                            <span class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1" style="top: -5px; left: 15px; height: 20px; min-width: 20px;">3</span>
                        </a>
                        <a href="#" class="my-auto">
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link" data-bs-toggle="dropdown"><i class="fas fa-user fa-2x"></i></a>
                                <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                    <a href="../HTML/checkout.php" class="dropdown-item">My Orders</a>
                                    <a href="../HTML/wishlist.php" class="dropdown-item">Wishlist</a>
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
                        <input type="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                        <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Search End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>
    <div class="container-fluid py-5 mb-4 mt-5">
        <div class="row">
            <!-- Product 1 -->
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card wishlist-card text-center p-2">
                    <img src="../img/Apple.jpg" class="card-img-top mx-auto" alt="Apple">
                    <div class="card-body">
                        <h5 class="card-title">Apple</h5>
                        <p class="card-text">100g - Rs.250</p>
                        <p class="stock-status text-success" data-stock="in">In Stock</p>
                        <button class="btn btn-sm btn-primary"><i class="fas fa-cart-plus"></i> Add to Cart</button>
                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                    </div>
                </div>
            </div>

            <!-- Product 2 (Out of Stock Example) -->
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card wishlist-card text-center p-2">
                    <img src="../img/Banana.jpg" class="card-img-top mx-auto" alt="Banana">
                    <div class="card-body">
                        <h5 class="card-title">Banana</h5>
                        <p class="card-text">100g - Rs.150</p>
                        <p class="stock-status text-danger" data-stock="out">Out of Stock</p>
                        <button class="btn btn-sm btn-primary" disabled><i class="fas fa-cart-plus"></i> Add to Cart</button>
                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                    </div>
                </div>
            </div>

            <!-- Product 3 -->
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card wishlist-card text-center p-2">
                    <img src="../img/Orange.jpg" class="card-img-top mx-auto" alt="Orange">
                    <div class="card-body">
                        <h5 class="card-title">Orange</h5>
                        <p class="card-text">100g - Rs.180</p>
                        <p class="stock-status text-success" data-stock="in">In Stock</p>
                        <button class="btn btn-sm btn-primary"><i class="fas fa-cart-plus"></i> Add to Cart</button>
                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                    </div>
                </div>
            </div>

            <!-- Product 4 -->
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card wishlist-card text-center p-2">
                    <img src="../img/blueberry.jpg" class="card-img-top mx-auto" alt="Apple">
                    <div class="card-body">
                        <h5 class="card-title">Blueberry</h5>
                        <p class="card-text">100g - Rs.240</p>
                        <p class="stock-status text-success" data-stock="in">In Stock</p>
                        <button class="btn btn-sm btn-primary"><i class="fas fa-cart-plus"></i> Add to Cart</button>
                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                    </div>
                </div>
            </div>

            <!-- Product 5 -->
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card wishlist-card text-center p-2">
                    <img src="../img/Carrot.jpg" class="card-img-top mx-auto" alt="Apple">
                    <div class="card-body">
                        <h5 class="card-title">Carrot</h5>
                        <p class="card-text">100g - Rs.250</p>
                        <p class="stock-status text-danger" data-stock="out">Out of Stock</p>
                        <button class="btn btn-sm btn-primary" disabled><i class="fas fa-cart-plus"></i> Add to Cart</button>
                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Go to Shop Button -->
    <div class="text-center mt-4">
        <a href="../HTML/index.php" class="btn btn-outline-success"><i class="fas fa-store"></i> Continue Shopping</a>
    </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/lightbox/js/lightbox.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>
</body>

</html>