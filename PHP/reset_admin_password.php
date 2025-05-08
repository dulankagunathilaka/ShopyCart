<?php
session_start();
require_once '../HTML/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $old = $_POST['old_password'];
    $new = $_POST['new_password'];

    // Fetch admin user
    $sql = "SELECT * FROM admin_users WHERE email = 'admin@123'";
    $result = $conn->query($sql);
    $admin = $result->fetch_assoc();

    if ($admin && $admin['password'] === $old) {
        // Update password
        $update = $conn->prepare("UPDATE admin_users SET password = ? WHERE email = 'admin@123'");
        $update->bind_param("s", $new);
        if ($update->execute()) {
            echo "<script>alert('Password updated successfully!'); window.location.href='../HTML/admin.php';</script>";
        } else {
            echo "<script>alert('Update failed'); history.back();</script>";
        }
    } else {
        echo "<script>alert('Old password is incorrect'); history.back();</script>";
    }
}
?>
