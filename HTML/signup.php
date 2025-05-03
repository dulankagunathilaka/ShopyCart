<?php
require 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match'); window.location.href='../HTML/index.php';</script>";
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (full_name, email, address, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $address, $hashedPassword);

    if ($stmt->execute()) {
        echo "<script>alert('Signup successful'); window.location.href='../HTML/index.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='../HTML/index.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
