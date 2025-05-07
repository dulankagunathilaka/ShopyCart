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

    <style>
        .order-card {
            border-left: 5px solid #81c408;
            transition: all 0.3s ease;
            border-radius: 15px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            background-color: #fff;
        }

        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.15);
        }

        .order-card .card-body {
            padding: 20px;
        }

        .order-card h5 {
            font-size: 1.25rem;
            font-weight: bold;
            color: #81c408;
        }

        .order-card p {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .order-card .btn {
            transition: all 0.3s ease;
        }

        .order-card .btn-warning:hover {
            background-color: #ffcc00;
            border-color: #ffcc00;
        }

        .order-card .btn-success:hover {
            background-color: #28a745;
            border-color: #28a745;
        }

        .order-card .btn-secondary:hover {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .order-card .btn-dark:hover {
            background-color: #343a40;
            border-color: #343a40;
        }

        .order-card .btn-light {
            background-color: #f8f9fa;
            color: #6c757d;
        }

        .order-card .order-status {
            font-weight: bold;
            color: #81c408;
            background-color: #e8f5e9;
            padding: 0.3rem 0.5rem;
            border-radius: 5px;
        }

        /* Add margin-top to avoid overlap with fixed navbar */
        .order-history-section {
            margin-top: 80px;
            /* Adjust this value if needed */
        }

        /* Style for "Continue Shopping" Button */
        .continue-shopping-btn {
            background-color: white;
            border: 2px solid #81c408;
            color: #81c408;
            padding: 12px 30px;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: bold;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .continue-shopping-btn:hover {
            background-color: #81c408;
            color: white;
        }
    </style>
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
            </nav>
        </div>
    </div>

    <!-- Modal Search -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title">Search by keyword</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body d-flex align-items-center">
                    <div class="input-group w-75 mx-auto d-flex">
                        <input type="search" class="form-control p-3" placeholder="keywords">
                        <span class="input-group-text p-3"><i class="fa fa-search"></i></span>
                    </div>
                </div>
            </div>
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

    <!-- JavaScript Libraries -->
    <script>
        function updateStatus(button, status, orderId) {
            fetch('../PHP/update_order_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `order_id=${orderId}&status=${status}`
                })
                .then(res => res.text())
                .then(response => {
                    if (status === 'accept') {
                        button.classList.remove('btn-warning');
                        button.classList.add('btn-secondary');
                        button.innerText = "Packing";
                    } else if (status === 'delivered') {
                        button.classList.remove('btn-success');
                        button.classList.add('btn-dark');
                        button.innerText = "Out for Delivery";
                    }
                    button.disabled = true;
                })
                .catch(err => {
                    alert("Error updating order status");
                    console.error(err);
                });
        }
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/lightbox/js/lightbox.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="../js/main.js"></script>
</body>

</html>