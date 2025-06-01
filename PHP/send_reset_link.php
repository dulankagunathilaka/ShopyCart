<?php
session_start();
require '../HTML/db_connection.php';  // $conn is defined here (MySQLi)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Prepare statement to find user by email
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Update token and expiration
        $updateStmt = $conn->prepare("UPDATE users SET reset_token = ?, token_expires = ? WHERE email = ?");
        $updateStmt->bind_param("sss", $token, $expires, $email);
        $updateStmt->execute();
        $updateStmt->close();

        $resetLink = "http://localhost/ShopyCart/HTML/reset_password.php?token=$token";

        // For testing only; use a proper mailer in production
        mail($email, "Password Reset", "Click to reset your password: $resetLink");

        echo "Reset link sent to your email.";
    } else {
        echo "No user found with that email.";
    }

    $stmt->close();
}
?>
