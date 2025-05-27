<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../HTML/index.php");
    exit;
}

$userId = $_SESSION['user_id'];
require_once '../HTML/db_connection.php';

// Fetch user info
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['full_name'], $_POST['email'], $_POST['address'])) {
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $address = $_POST['address'];

        $sql_update = "UPDATE users SET full_name = ?, email = ?, address = ? WHERE user_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sssi", $full_name, $email, $address, $userId);
        $stmt_update->execute();
        $stmt_update->close();
    }

    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] == 0) {
        $target_dir = "../uploads/";
        $ext = pathinfo($_FILES["profilePic"]["name"], PATHINFO_EXTENSION);
        $filename = "user_" . $userId . "_" . time() . "." . $ext;  // unique filename
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $target_file)) {
            $sql_pic = "UPDATE users SET profile_picture = ? WHERE user_id = ?";
            $stmt_pic = $conn->prepare($sql_pic);
            $stmt_pic->bind_param("si", $filename, $userId);
            $stmt_pic->execute();
            $stmt_pic->close();
        } else {
            echo "<script>alert('Image upload failed!');</script>";
        }
    }

    echo "<script>alert('Profile updated successfully!'); window.location.href = '../HTML/myaccount.php';</script>";
    exit;
}

// Full name
$fullName = $_SESSION['full_name'] ?? 'Guest';

// Cart count
$cart = $_SESSION['cart'] ?? [];
$cartCount = 0;
foreach ($cart as $item) {
    $cartCount += $item['quantity'];
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ShopyCart Super Market</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

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

    <!-- myaccount CSS Stylesheet -->
    <link href="../css/myaccount.css" rel="stylesheet">
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
                        <a href="../HTML/myaccount.php" class="nav-item nav-link active">My Account</a>
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

    <!-- Profile Form Section Start -->
    <section style="padding-top: 120px; background-color: #f8f9fa;">
        <div class="container d-flex justify-content-center align-items-center" style="min-height: calc(100vh - 120px);">
            <div class="row w-100 justify-content-center">
                <div class="col-md-6">
                    <div class="card p-4 shadow-lg border-0 rounded-4">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="card-body text-center">
                                <div class="position-relative mb-3">
                                    <img
                                        id="profileImage"
                                        src="<?php echo (!empty($user['profile_picture']) && file_exists("../uploads/" . $user['profile_picture'])) ? '../uploads/' . $user['profile_picture'] : '../img/avatar.jpg'; ?>"
                                        alt="Profile Picture"
                                        class="rounded-circle shadow"
                                        style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #fff;">
                                    <label for="fileInput" id="editPicLabel" class="btn btn-sm" style="pointer-events: none; opacity: 0.5; cursor: default;">
                                        <i class="bi bi-pencil-square"></i>
                                    </label>
                                    <input type="file" name="profilePic" id="fileInput" class="form-control d-none" disabled onchange="document.getElementById('saveButton').style.display = 'inline-block';">
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="full_name">Full Name</label>
                                    <input type="text" id="full_name" class="form-control" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" readonly>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="address">Address</label>
                                    <input type="text" id="address" class="form-control" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" readonly>
                                </div>

                                <div class="form-actions d-flex justify-content-between mt-3">
                                    <button type="button" class="btn" id="editButton" style="background-color: orange; color: white;" onclick="editProfile()">Edit</button>
                                    <button type="submit" class="btn" id="saveButton" style="background-color: #81c408; color: white; display: none;">Save</button>
                                    <a href="../PHP/logout.php" class="btn" style="background-color: #81c408; color: white;"><i class="fas fa-sign-out-alt"></i> Logout</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Profile Form Section End -->

    <!-- Footer Start -->
    <!-- Back to Top -->
    <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/lightbox/js/lightbox.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- main Javascript -->
    <script src="../js/main.js"></script>

    <!-- myaccount Javascript -->
    <script src="../js/myaccount.js"></script>

</body>

</html>