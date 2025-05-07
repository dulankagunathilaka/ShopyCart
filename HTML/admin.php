<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../HTML/index.php");
    exit;
}
$productCount = include '../PHP/get_product_count.php';

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
    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            background-color: #81c408;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            padding-top: 20px;
        }

        .sidebar a {
            color: #fff;
            padding: 12px;
            display: block;
            text-decoration: none;
            font-weight: 500;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #6aa304;
        }

        .main-content {
            margin-left: 230px;
            padding: 30px;
        }

        .card-header {
            background-color: #81c408;
            color: #fff;
            font-weight: bold;
        }

        .btn-primary,
        .btn-success,
        .btn-warning,
        .btn-danger {
            background-color: #81c408;
            border: none;
        }

        .btn-primary:hover,
        .btn-success:hover,
        .btn-warning:hover,
        .btn-danger:hover {
            background-color: #6aa304;
        }

        .btn {
            border-radius: 10px;
        }

        .btn-instock {
            background-color: #28a745;
            color: white;
        }

        .btn-outofstock {
            background-color: #dc3545;
            color: white;
        }

        .btn-status {
            background-color: #007bff;
            color: white;
        }

        .btn-status:hover,
        .btn-instock:hover,
        .btn-outofstock:hover {
            opacity: 0.8;
        }

        @media (max-width: 992px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .main-content {
                margin-left: 0;
            }
        }

        .img-thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
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
                <a href="../HTML/userpage.php" class="navbar-brand">
                    <h1 class="text-primary display-6">Shopy Cart</h1>
                </a>
                <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>
                <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                    <div class="navbar-nav mx-auto">
                        <a href="../HTML/userpage.php" class="nav-item nav-link">Home</a>
                        <a href="#" class="nav-item nav-link active">Admin</a>
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
    <!-- Sidebar -->
    <section style="padding-top: 100px;">
        <div class="sidebar">
            <section style="padding-top: 80px;">
                <a href="#dashboard" class="active"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
                <a href="#products"><i class="fas fa-boxes me-2"></i>Manage Products</a>
                <a href="#orders"><i class="fas fa-truck me-2"></i>Order Tracking</a>
                <a href="#settings"><i class="fas fa-cog me-2"></i>Settings</a>
                <a href="#"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
            </section>
        </div>
    </section>
    <!-- Main Content -->
    <div class="main-content">
        <!-- Dashboard Overview -->
        <section id="dashboard">
            <h2>Welcome, Admin!</h2>
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Products</h5>
                            <p class="card-text fs-4"><?php echo $productCount; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Orders Today</h5>
                            <p class="card-text fs-4">8</p> <!-- Replace with dynamic if needed -->
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Revenue</h5>
                            <p class="card-text fs-4">Rs. 25,000</p> <!-- Replace with dynamic if needed -->
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <!-- Display Success or Error Message with JavaScript Alert -->
            <?php
            if (isset($_SESSION['success_message'])) {
                echo "<script>alert('" . $_SESSION['success_message'] . "');</script>";
                unset($_SESSION['success_message']);
            }

            if (isset($_SESSION['error_message'])) {
                echo "<script>alert('Error: " . $_SESSION['error_message'] . "');</script>";
                unset($_SESSION['error_message']);
            }
            ?>

            <?php
            require_once '../HTML/db_connection.php';
            ?>

            <!-- Manage Products -->
            <section id="products" class="mt-5">
                <div class="card">
                    <div class="card-header">Manage Products</div>
                    <div class="card-body">

                        <!-- Add Product Form -->
                        <form class="row g-3 mb-4" method="POST" action="../PHP/upload_product.php" enctype="multipart/form-data">
                            <div class="col-md-3">
                                <input type="text" name="product_name" class="form-control" placeholder="Product Name" required>
                            </div>
                            <div class="col-md-2">
                                <select name="category" class="form-select" required>
                                    <option value="">Select Category</option>
                                    <option value="All Products">All Products</option>
                                    <option value="Vegetables">Vegetables</option>
                                    <option value="Fruits">Fruits</option>
                                    <option value="Bakery">Bakery</option>
                                    <option value="Meat">Meat</option>
                                    <option value="Snacks">Snacks</option>
                                    <option value="Drinks">Drinks</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="quantity" class="form-control" placeholder="Quantity" min="1" required>
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="description" class="form-control" placeholder="Description" required>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="price" step="0.01" class="form-control" placeholder="Price" required>
                            </div>
                            <div class="col-md-2">
                                <input type="file" name="product_image" class="form-control" required>
                            </div>
                            <div class="col-md-2 d-grid">
                                <button type="submit" class="btn btn-primary">Add Product</button>
                            </div>
                        </form>

                        <!-- Success/Error Alerts -->
                        <?php
                        if (isset($_SESSION['success_message'])) {
                            echo "<script>alert('" . $_SESSION['success_message'] . "');</script>";
                            unset($_SESSION['success_message']);
                        }
                        if (isset($_SESSION['error_message'])) {
                            echo "<script>alert('Error: " . $_SESSION['error_message'] . "');</script>";
                            unset($_SESSION['error_message']);
                        }
                        ?>

                        <!-- Search Logic -->
                        <?php
                        $search = isset($_GET['search']) ? $_GET['search'] : '';
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $offset = ($page - 1) * 5;

                        $query = "SELECT * FROM products WHERE name LIKE ? LIMIT 5 OFFSET ?";
                        $stmt = $conn->prepare($query);
                        $searchTerm = '%' . $search . '%';
                        $stmt->bind_param("si", $searchTerm, $offset);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        ?>

                        <!-- Search Bar -->
                        <form class="mb-3" method="GET">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search by Product Name" value="<?php echo htmlspecialchars($search); ?>">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                        </form>

                        <!-- Product Table -->
                        <table class="table table-bordered text-center align-middle">
                            <thead class="table-success">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Stock Status</th>
                                    <th>Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['product_id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                                        <td><?php echo htmlspecialchars($row['price']); ?></td>
                                        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                                        <td>
                                            <button class="btn btn-sm <?php echo $row['stock_status'] == 'In Stock' ? 'btn-success' : 'btn-warning'; ?>">
                                                <?php echo htmlspecialchars($row['stock_status']); ?>
                                            </button>
                                        </td>
                                        <td><img src="<?php echo htmlspecialchars($row['image_url']); ?>" class="img-thumbnail" style="width: 70px; height: 70px;"></td>
                                        <td>
                                            <!-- Edit Button -->
                                            <button class="btn btn-sm btn-primary edit-btn"
                                                data-id="<?php echo $row['product_id']; ?>"
                                                data-name="<?php echo htmlspecialchars($row['name']); ?>"
                                                data-category="<?php echo htmlspecialchars($row['category']); ?>"
                                                data-description="<?php echo htmlspecialchars($row['description']); ?>"
                                                data-price="<?php echo htmlspecialchars($row['price']); ?>"
                                                data-quantity="<?php echo htmlspecialchars($row['quantity']); ?>"
                                                data-stock_status="<?php echo htmlspecialchars($row['stock_status']); ?>">
                                                Edit
                                            </button>

                                            <!-- Remove Button -->
                                            <form method="POST" action="../PHP/remove_product.php" style="display:inline;">
                                                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Edit Modal -->
            <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form class="modal-content" method="POST" action="../PHP/edit_product.php">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body row g-3">
                            <input type="hidden" name="product_id" id="edit-product-id">

                            <div class="col-md-6">
                                <label>Product Name</label>
                                <input type="text" class="form-control" name="product_name" id="edit-product-name" required>
                            </div>
                            <div class="col-md-6">
                                <label>Category</label>
                                <select class="form-select" name="category" id="edit-category" required>
                                    <option value="All Products">All Products</option>
                                    <option value="Vegetables">Vegetables</option>
                                    <option value="Fruits">Fruits</option>
                                    <option value="Bakery">Bakery</option>
                                    <option value="Meat">Meat</option>
                                    <option value="Snacks">Snacks</option>
                                    <option value="Drinks">Drinks</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label>Description</label>
                                <input type="text" class="form-control" name="description" id="edit-description" required>
                            </div>
                            <div class="col-md-4">
                                <label>Price</label>
                                <input type="number" class="form-control" name="price" id="edit-price" step="0.01" required>
                            </div>
                            <div class="col-md-4">
                                <label>Quantity</label>
                                <input type="number" class="form-control" name="quantity" id="edit-quantity" required>
                            </div>
                            <div class="col-md-4">
                                <label>Stock Status</label>
                                <select class="form-select" name="stock_status" id="edit-stock-status">
                                    <option value="In Stock">In Stock</option>
                                    <option value="Out of Stock">Out of Stock</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Save Changes</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bootstrap JS (Make sure it's included) -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

            <!-- Edit Button Script -->
            <script>
                document.querySelectorAll('.edit-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const modal = new bootstrap.Modal(document.getElementById('editProductModal'));
                        document.getElementById('edit-product-id').value = this.dataset.id;
                        document.getElementById('edit-product-name').value = this.dataset.name;
                        document.getElementById('edit-category').value = this.dataset.category;
                        document.getElementById('edit-description').value = this.dataset.description;
                        document.getElementById('edit-price').value = this.dataset.price;
                        document.getElementById('edit-quantity').value = this.dataset.quantity;
                        document.getElementById('edit-stock-status').value = this.dataset.stock_status;
                        modal.show();
                    });
                });
            </script>
        </section>


        <section id="orders" class="mt-5">
            <div class="card">
                <div class="card-header">Order Table</div>
                <div class="card-body">
                    <!-- Responsive Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle">
                            <thead class="table-success">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer Name</th>
                                    <th>Email</th>
                                    <th class="address-column">Address</th>
                                    <th>Contact Number</th>
                                    <th class="items-column">Items Ordered</th>
                                    <th>Total Price</th>
                                    <th class="hide-mobile">Payment Method</th>
                                    <th class="hide-mobile">Order Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $results_per_page = 10;
                                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                $start_from = ($page - 1) * $results_per_page;

                                $orderResults = $conn->query("SELECT * FROM order_tracking ORDER BY order_date DESC LIMIT $start_from, $results_per_page");

                                while ($order = $orderResults->fetch_assoc()):
                                    $items = explode(", ", $order['items']);
                                    $quantities = explode(", ", $order['quantities']);

                                    $combinedItems = [];
                                    for ($i = 0; $i < count($items); $i++) {
                                        $item = $items[$i] ?? '';
                                        $qty = $quantities[$i] ?? '';
                                        $combinedItems[] = htmlspecialchars("$item - $qty");
                                    }
                                ?>
                                    <tr>
                                        <td>#<?= htmlspecialchars($order['order_id']); ?></td>
                                        <td><?= htmlspecialchars($order['customer_name']); ?></td>
                                        <td><?= htmlspecialchars($order['email']); ?></td>
                                        <td class="address-column"><?= htmlspecialchars($order['address']); ?></td>
                                        <td><?= htmlspecialchars($order['contact_number']); ?></td>
                                        <td class="items-column"><?= implode("<br>", $combinedItems); ?></td>
                                        <td>Rs. <?= htmlspecialchars(number_format($order['total_price'], 2)); ?></td>
                                        <td class="hide-mobile"><?= htmlspecialchars($order['payment_method']); ?></td>
                                        <td class="hide-mobile"><?= htmlspecialchars($order['order_date']); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" onclick="updateStatus('accept', <?= $order['order_id']; ?>)">Accept</button>
                                            <button class="btn btn-sm btn-success" onclick="updateStatus('delivered', <?= $order['order_id']; ?>)">Delivered</button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Grand Total -->
                    <div class="mt-3 text-end">
                        <?php
                        $totalQuery = $conn->query("SELECT SUM(total_price) AS grand_total FROM order_tracking");
                        $totalRow = $totalQuery->fetch_assoc();
                        $grandTotal = number_format($totalRow['grand_total'], 2);
                        ?>
                        <h5><strong>Grand Total of All Orders: Rs. <?= $grandTotal; ?></strong></h5>
                    </div>

                    <!-- Pagination -->
                    <div class="pagination mt-3">
                        <a href="?page=<?= $page - 1; ?>" class="btn btn-sm btn-secondary <?= ($page <= 1) ? 'disabled' : ''; ?>">Previous</a>
                        <a href="?page=<?= $page + 1; ?>" class="btn btn-sm btn-secondary">Next</a>
                    </div>
                </div>
            </div>
        </section>




        <!-- Settings -->
        <section id="settings" class="mt-5">
            <div class="card">
                <div class="card-header">Settings</div>
                <div class="card-body">
                    <p>Here you can update admin preferences, theme colors, and account information.</p>
                </div>
            </div>
        </section>
    </div>

    <!-- Bootstrap & Font Awesome -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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

    <script>
        // JavaScript function to handle order status updates
        function updateStatus(status, orderId) {
            if (status === 'accept') {
                if (confirm('Are you sure you want to accept this order?')) {
                    // Send a request to the server to update the order status
                    window.location.href = `update_order_status.php?status=accept&order_id=${orderId}`;
                }
            } else if (status === 'delivered') {
                if (confirm('Are you sure this order is delivered?')) {
                    // Send a request to the server to update the order status
                    window.location.href = `update_order_status.php?status=delivered&order_id=${orderId}`;
                }
            }
        }
    </script>

</body>

</html>