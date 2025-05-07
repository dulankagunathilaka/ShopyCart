<?php
require '../HTML/db_connection.php'; // your DB connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Save token to DB
        $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, token_expires = ? WHERE email = ?");
        $stmt->execute([$token, $expires, $email]);

        $resetLink = "http://yourdomain.com/reset_password.php?token=$token";
        // Send email (use mail() or a library like PHPMailer)
        mail($email, "Password Reset", "Click to reset: $resetLink");

        echo "Reset link sent to your email.";
    } else {
        echo "No user found with that email.";
    }
}
?>
