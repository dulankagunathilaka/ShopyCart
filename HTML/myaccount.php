<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../HTML/index.php");
    exit;
}

$userId = $_SESSION['user_id'];
require_once '../HTML/db_connection.php';

$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];

    $sql_update = "UPDATE users SET full_name = ?, email = ? WHERE user_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssi", $full_name, $email, $userId);

    if ($stmt_update->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location.href = '../HTML/myaccount.php';</script>";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <style>
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
<div class="container mt-5">
    <div class="row justify-content-left">
        <div class="col-md-6">
            <div class="card p-3">
                <div class="card-body text-center">
                    <img id="profileImage" src="<?php echo isset($user['profile_picture']) ? '../uploads/' . $user['profile_picture'] : '../img/avatar.jpg'; ?>" alt="Profile Picture">
                    <input type="file" id="fileInput" class="form-control mt-2">
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="form-group mb-3">
                            <label for="full_name">Full Name</label>
                            <input type="text" id="full_name" class="form-control" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" id="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-warning" id="editButton" onclick="editProfile()">Edit</button>
                            <button type="submit" class="btn btn-success" id="saveButton" style="display: none;">Save</button>
                            <a href="../HTML/index.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
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

            fetch("../HTML/myaccount.php", {
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