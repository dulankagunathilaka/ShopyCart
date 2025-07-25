<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../HTML/index.php");
    exit;
}

include '../PHP/dashboard_stats.php';

$userId = $_SESSION['user_id'];
$fullName = $_SESSION['full_name'];

// Fetch total cart count for logged-in user
$cartCount = 0;
if (isset($conn)) { // Assuming $conn is available in dashboard_stats.php
    $stmt = $conn->prepare("SELECT COALESCE(SUM(quantity), 0) AS total_quantity FROM cart_items WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($totalQuantity);
    $stmt->fetch();
    $stmt->close();
    $cartCount = $totalQuantity;
} else {
    // Handle case when $conn is not defined, maybe include db_connection here if needed
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

    <!-- Bootstrap & Font Awesome -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Libraries Stylesheet -->
    <link href="../lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Main CSS Stylesheet -->
    <link href="../css/style.css" rel="stylesheet">

    <!-- admin CSS Stylesheet -->
    <link href="../css/admin.css" rel="stylesheet">

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
                <a href="#messages"><i class="fas fa-envelope me-2"></i>Contact Messages</a>
                <a href="#resetpassword"><i class="fas fa-cog me-2"></i>Reset Password</a>
                <a href="../PHP/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
            </section>
        </div>
    </section>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Dashboard Overview -->
        <section id="dashboard" class="container py-8">
            <h2 class="mb-4 text-center" style="color: #81c408;">Welcome, Admin!</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card shadow h-100 text-white" style="background-color: #81c408;">
                        <div class="card-body text-center">
                            <i class="fas fa-box fa-2x mb-3"></i>
                            <h5 class="card-title">Total Products</h5>
                            <p class="fs-3 fw-bold mb-0"><?php echo $productCount; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow h-100 text-white" style="background-color: #81c408;">
                        <div class="card-body text-center">
                            <i class="fas fa-shopping-cart fa-2x mb-3"></i>
                            <h5 class="card-title">Orders Today</h5>
                            <p class="fs-3 fw-bold mb-0"><?php echo $ordersToday; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow h-100 text-white" style="background-color: #81c408;">
                        <div class="card-body text-center">
                            <i class="fas fa-dollar-sign fa-2x mb-3"></i>
                            <h5 class="card-title">Revenue</h5>
                            <p class="fs-3 fw-bold mb-0">Rs. <?php echo number_format($revenueToday, 2); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

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
                                <option value="Fresh Produce">Fresh Produce</option>
                                <option value="Meat & Seafood">Meat & Seafood</option>
                                <option value="Dairy & Eggs">Dairy & Eggs</option>
                                <option value="Bakery">Bakery</option>
                                <option value="Beverages">Beverages</option>
                                <option value="Packaged Foods">Packaged Foods</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="quantity" class="form-control" placeholder="Quantity" required>
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
                                        <div class="d-flex gap-2">
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
                                            <form method="POST" action="../PHP/remove_product.php" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                            </form>
                                        </div>
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
                                <option value="">Select Category</option>
                                <option value="All Products">All Products</option>
                                <option value="Fresh Produce">Fresh Produce</option>
                                <option value="Meat & Seafood">Meat & Seafood</option>
                                <option value="Dairy & Eggs">Dairy & Eggs</option>
                                <option value="Bakery">Bakery</option>
                                <option value="Beverages">Beverages</option>
                                <option value="Packaged Foods">Packaged Foods</option>
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
                            <input type="text" class="form-control" name="quantity" id="edit-quantity" required>
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
                                $results_per_page = 5;
                                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                $start_from = ($page - 1) * $results_per_page;

                                // Fetch current page of orders
                                $orderResults = $conn->query("SELECT * FROM order_tracking ORDER BY order_date DESC LIMIT $start_from, $results_per_page");

                                // Fetch total number of orders for pagination
                                $totalOrdersResult = $conn->query("SELECT COUNT(*) AS total FROM order_tracking");
                                $totalOrdersRow = $totalOrdersResult->fetch_assoc();
                                $total_orders = $totalOrdersRow['total'];
                                $total_pages = ceil($total_orders / $results_per_page);

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
                                            <?php
                                            $status = strtolower($order['status']);
                                            $acceptDisabled = ($status === 'packing' || $status === 'out for delivery') ? 'disabled' : '';
                                            $deliverDisabled = ($status === 'out for delivery') ? 'disabled' : '';
                                            ?>
                                            <div class="d-flex">
                                                <button
                                                    class="btn btn-sm <?= $status === 'packing' || $status === 'out for delivery' ? 'btn-secondary' : 'btn-warning' ?> me-2 flex-fill"
                                                    onclick="updateStatus(this, 'accept', <?= $order['order_id']; ?>)"
                                                    <?= $acceptDisabled ?>>
                                                    <?= $status === 'packing' || $status === 'out for delivery' ? 'Packing' : 'Accept' ?>
                                                </button>

                                                <button
                                                    class="btn btn-sm <?= $status === 'out for delivery' ? 'btn-dark' : 'btn-success' ?> flex-fill"
                                                    onclick="updateStatus(this, 'delivered', <?= $order['order_id']; ?>)"
                                                    <?= $deliverDisabled ?>>
                                                    <?= $status === 'out for delivery' ? 'Out for Delivery' : 'Delivered' ?>
                                                </button>
                                            </div>
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
                    <div class="pagination mt-3 d-flex flex-wrap gap-1">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1; ?>" class="btn btn-sm btn-secondary">Previous</a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=<?= $i; ?>" class="btn btn-sm <?= $i === $page ? 'btn-primary' : 'btn-outline-secondary' ?>">
                                <?= $i; ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?= $page + 1; ?>" class="btn btn-sm btn-secondary">Next</a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </section>

        <section id="messages" class="mt-5">
            <div class="card">
                <div class="card-header bg-primary text-white">User Contact Messages</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead class="table-success">
                                <tr>
                                    <th>ID</th>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Message</th>
                                    <th>Submitted At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include '../HTML/db_connection.php';
                                $result = $conn->query("SELECT * FROM contact_messages ORDER BY submitted_at DESC");
                                if ($result->num_rows > 0):
                                    while ($row = $result->fetch_assoc()):
                                ?>
                                        <tr>
                                            <td><?= $row['id']; ?></td>
                                            <td><?= htmlspecialchars($row['name']); ?></td>
                                            <td><?= htmlspecialchars($row['email']); ?></td>
                                            <td><?= nl2br(htmlspecialchars($row['message'])); ?></td>
                                            <td><?= $row['submitted_at']; ?></td>
                                        </tr>
                                <?php
                                    endwhile;
                                else:
                                    echo "<tr><td colspan='5'>No messages found.</td></tr>";
                                endif;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>


        <!-- Reset Password -->
        <section id="resetpassword" class="mt-5">
            <div class="card border-0">
                <div class="card-header border-0 text-white" style="background-color: #81c408;">
                    <h5 class="mb-0">Settings</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6>Reset Password</h6>
                            <p class="mb-1 text-muted">Change your account password for better security.</p>
                        </div>
                        <button class="btn text-white fw-semibold" style="background-color: #81c408;" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                            <i class="fas fa-key me-1"></i> Reset Password
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Reset Password Modal -->
        <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="../PHP/reset_admin_password.php" class="modal-content">
                    <div class="modal-header" style="background-color: #81c408;">
                        <h5 class="modal-title text-white" id="resetPasswordModalLabel">Reset Admin Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="old_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn text-white" style="background-color: #81c408;">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

    <!-- admin Javascript -->
    <script src="../js/admin.js"></script>

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

</body>

</html>