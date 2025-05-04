<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../HTML/index.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Database connection
$host = 'localhost';
$db = 'your_database_name';
$user = 'your_db_user';
$pass = 'your_db_password';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data from `users` table
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle POST request for updating full_name, email, and address
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $sql_update = "UPDATE users SET full_name = ?, email = ?, address = ? WHERE user_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sssi", $full_name, $email, $address, $userId);

    if ($stmt_update->execute()) {
        echo "<script>
            alert('Profile updated successfully!');
            window.location.href = 'lecturer_profile.php';
        </script>";
    } else {
        echo "<script>alert('Error updating profile: " . $conn->error . "');</script>";
    }

    $stmt_update->close();
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

    <!-- Bootstrap & Template CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
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
                        <a href="../HTML/index.php" class="nav-item nav-link active">Home</a>
                        <a href="#fresh-finds" class="nav-item nav-link">Fresh Finds</a>
                        <a href="../HTML/contact.php" class="nav-item nav-link">Contact</a>
                    </div>
                    <div class="d-flex m-3 me-0">
                        <a href="../HTML/cart.php" class="position-relative me-4 my-auto">
                            <i class="fa fa-shopping-bag fa-2x"></i>
                            <span class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1"
                                style="top: -5px; left: 15px; height: 20px; min-width: 20px;">3</span>
                        </a>
                        <a href="#" class="my-auto">
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link" data-bs-toggle="dropdown">
                                    <i class="fas fa-user fa-2x"></i>
                                </a>
                                <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                    <a href="#" class="btn border-secondary py-2 px-2 rounded-pill text-primary w-100 text-center"
                                        data-bs-toggle="modal" data-bs-target="#authModal">
                                        <h6><?php echo htmlspecialchars($user['full_name']); ?></h6>
                                    </a>
                                    <hr class="dropdown-divider">
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

    <!-- Profile Form -->
    <div class="container mt-5 pt-5">
        <div class="row justify-content-left">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="form-group mb-3">
                                <label for="full_name">Full Name</label>
                                <input type="text" id="full_name" class="form-control" name="full_name"
                                    value="<?php echo htmlspecialchars($user['full_name']); ?>" readonly>
                            </div>
                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <input type="email" id="email" class="form-control" name="email"
                                    value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                            </div>
                            <div class="form-group mb-3">
                                <label for="address">Address</label>
                                <textarea id="address" class="form-control" name="address" rows="2" readonly><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                            </div>
                            <div class="form-actions d-flex justify-content-between">
                                <button type="button" class="btn btn-primary" id="editButton" onclick="editProfile()">Edit</button>
                                <button type="submit" class="btn btn-success" id="saveButton" style="display: none;">Save</button>
                                <a href="../PHP/logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../JS/lecturer_profile.js"></script>
    <script>
        function editProfile() {
            document.querySelectorAll('.form-control').forEach(input => {
                input.removeAttribute('readonly');
                input.removeAttribute('disabled');
            });
            document.getElementById("editButton").style.display = "none";
            document.getElementById("saveButton").style.display = "inline-block";
        }
    </script>
</body>

</html>