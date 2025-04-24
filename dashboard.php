<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html"); // Redirect to login if not signed in
    exit;
}
echo "Welcome, " . $_SESSION['full_name'];
?>
