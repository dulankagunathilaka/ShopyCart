<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../HTML/index.php");
    exit;
}
$fullName = $_SESSION['full_name'];
?>

<!DOCTYPE html>
<html lang="en">

<<head>
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

    <!-- Orderhistory CSS Stylesheet -->
    <link href="../css/orderhistory.css" rel="stylesheet">
 
</head>

<body>
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
                        <a href="#fresh-finds" class="nav-item nav-link active">Your Shopping</a>
                    </div>
                    <div class="d-flex m-3 me-0">
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
                </div>
            </nav>
        </div>
    </div>

    <!-- Order History Section -->
    <div class="container-fluid py-5 mb-4 order-history-section">
        <div class="row">
            <?php
            require_once '../HTML/db_connection.php';
            $userId = $_SESSION['user_id'];

            $stmt = $conn->prepare("SELECT * FROM order_tracking WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($order = $result->fetch_assoc()) {
                    $status = $order['status'];
                    echo '<div class="col-md-6 col-lg-4">';
                    echo '<div class="order-card card shadow-lg">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">Order #' . $order['order_id'] . '</h5>';
                    echo '<p><strong>Status:</strong> <span class="order-status">' . htmlspecialchars($status ?? 'Pending') . '</span></p>';
                    echo '<p><strong>Items:</strong> ' . htmlspecialchars($order['items']) . '</p>';
                    echo '<p><strong>Total:</strong> Rs. ' . number_format($order['total_price'], 2) . '</p>';
                    echo '<p><strong>Date:</strong> ' . $order['order_date'] . '</p>';

                    // Button logic based on status
                    if ($status === 'Pending' || $status === NULL || $status === '') {
                        echo '<button class="btn btn-warning me-2" onclick="updateStatus(this, \'accept\', ' . $order['order_id'] . ')">Accept</button>';
                        echo '<button class="btn btn-success" onclick="updateStatus(this, \'delivered\', ' . $order['order_id'] . ')">Delivered</button>';
                    } elseif ($status === 'Your order is packing') {
                        echo '<button class="btn btn-secondary" disabled>Packing</button>';
                    } elseif ($status === 'Your order out for delivery') {
                        echo '<button class="btn btn-dark" disabled>Out for Delivery</button>';
                    } else {
                        echo '<button class="btn btn-light" disabled>' . htmlspecialchars($status) . '</button>';
                    }

                    echo '</div></div>';
                    echo '</div>';
                }
            } else {
                echo "<p class='text-center'>You have no orders yet.</p>";
            }
            ?>
            <div class="text-center mt-4">
                <a href="../HTML/userpage.php" class="btn continue-shopping-btn"><i class="fas fa-store"></i> Continue Shopping</a>
            </div>
        </div>
    </div>

    <!-- Javascript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/lightbox/js/lightbox.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- main Javascript -->
    <script src="../js/main.js"></script>

    <!-- orderhistory Javascript -->
    <script src="../js/orderhistory.js"></script>
    
</body>

</html>