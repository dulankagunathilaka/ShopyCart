<?php
require 'db_connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, full_name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    
    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['full_name'] = $user['full_name'];
            header("Location: ../HTML/userpage.php");
            exit;
        } else {
            echo "<script>alert('Invalid password'); window.location.href='../HTML/index.php';</script>";
        }
    } else {
        echo "<script>alert('Email not found'); window.location.href='../HTML/index.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
