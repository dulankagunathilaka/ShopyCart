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

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['full_name'], $_POST['email'], $_POST['address'])) {
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $address = $_POST['address'];

        $sql_update = "UPDATE users SET full_name = ?, email = ?, address = ? WHERE user_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sssi", $full_name, $email, $address, $userId);

        if ($stmt_update->execute()) {
            echo "<script>alert('Profile updated successfully!'); window.location.href = '../HTML/myaccount.php';</script>";
        } else {
            echo "<script>alert('Error updating profile: " . $conn->error . "');</script>";
        }
        $stmt_update->close();
    }

    // Handle profile picture upload
    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] == 0) {
        $target_dir = "../uploads/";
        $filename = basename($_FILES["profilePic"]["name"]);
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $target_file)) {
            $sql_pic = "UPDATE users SET profile_picture = ? WHERE user_id = ?";
            $stmt_pic = $conn->prepare($sql_pic);
            $stmt_pic->bind_param("si", $filename, $userId);
            $stmt_pic->execute();
            $stmt_pic->close();

            echo "Profile picture updated successfully!";
        } else {
            echo "Failed to upload image.";
        }
        exit;
    }
}

$conn->close();
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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
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
        #profileImage {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #ccc;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>


<!-- Centered Card -->
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="row w-100 justify-content-center">
        <div class="col-md-6">
            <div class="card p-4 shadow">
                <div class="card-body text-center">
                    <img id="profileImage" src="<?php echo isset($user['profile_picture']) ? '../uploads/' . $user['profile_picture'] : '../img/avatar.jpg'; ?>" alt="Profile Picture">
                    <input type="file" id="fileInput" class="form-control mt-2">
                </div>
                <div class="card-body">
                    <form method="POST" action="" enctype="multipart/form-data">
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
                            <button type="submit" class="btn" id="saveButton" style="background-color: green; color: white; display: none;">Save</button>
                            <a href="../HTML/index.php" class="btn" style="background-color: red; color: white;"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function editProfile() {
        document.querySelectorAll('.form-control').forEach(input => {
            input.removeAttribute('readonly');
        });
        document.getElementById("editButton").style.display = "none";
        document.getElementById("saveButton").style.display = "inline-block";
    }

    document.getElementById("fileInput").addEventListener("change", function () {
        if (this.files && this.files[0]) {
            let reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById("profileImage").src = e.target.result;
            };
            reader.readAsDataURL(this.files[0]);

            let formData = new FormData();
            formData.append("profilePic", this.files[0]);

            fetch("", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => alert(data))
            .catch(error => console.error("Error uploading image:", error));
        }
    });
</script>
</body>
</html>
