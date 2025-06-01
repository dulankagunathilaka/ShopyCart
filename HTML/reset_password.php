<?php
session_start();
require '../HTML/db_connection.php';

$token = $_GET['token'] ?? '';

if (!$token) {
    die('Invalid or missing token.');
}

// Verify token and expiration
$stmt = $pdo->prepare("SELECT user_id, token_expires FROM users WHERE reset_token = ?");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    die('Invalid token.');
}

if (new DateTime() > new DateTime($user['token_expires'])) {
    die('Token expired. Please request a new password reset.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Hash new password
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update password and clear reset token
        $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expires = NULL WHERE user_id = ?");
        $stmt->execute([$passwordHash, $user['user_id']]);

        $_SESSION['message'] = "Password reset successful. You can now log in.";
        header('Location: ../HTML/signin.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Reset Password</title></head>
<body>
<h2>Reset Password</h2>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST">
    <label>New Password:</label><br>
    <input type="password" name="new_password" required><br><br>
    <label>Confirm Password:</label><br>
    <input type="password" name="confirm_password" required><br><br>
    <button type="submit">Reset Password</button>
</form>
</body>
</html>
