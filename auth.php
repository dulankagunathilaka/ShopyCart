<?php
session_start();
require 'db_connection.php';

// 🟢 Sign Up
if (isset($_POST['signup'])) {
    $fullName = trim($_POST['signupName']);
    $email = trim($_POST['signupEmail']);
    $password = $_POST['signupPassword'];
    $confirmPassword = $_POST['signupConfirmPassword'];

    // 1️⃣ Check for empty fields
    if (empty($fullName) || empty($email) || empty($password) || empty($confirmPassword)) {
        die("Please fill all the fields.");
    }

    // 2️⃣ Check if passwords match
    if ($password !== $confirmPassword) {
        die("Passwords do not match.");
    }

    // 3️⃣ Check if email already exists
    $check = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        die("Email is already registered.");
    }

    // 4️⃣ Insert new user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullName, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "Sign up successful! You can now sign in.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $check->close();
    $stmt->close();
    $conn->close();
    exit;
}

// 🔵 Sign In
if (isset($_POST['signin'])) {
    $email = trim($_POST['signinEmail']);
    $password = $_POST['signinPassword'];

    // 1️⃣ Check for empty fields
    if (empty($email) || empty($password)) {
        die("Please enter both email and password.");
    }

    // 2️⃣ Check user
    $stmt = $conn->prepare("SELECT user_id, full_name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($userId, $fullName, $hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['user_id'] = $userId;
            $_SESSION['full_name'] = $fullName;
            header("Location: index.html"); // ✅ Redirect on success
            exit;
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No account found with that email.";
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>
