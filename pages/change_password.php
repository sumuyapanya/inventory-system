<?php
session_start();
require_once "../config/Database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$db = new Database();
$conn = $db->conn;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_id = $_SESSION['user_id'];

    // Get current password from DB
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = :id");
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($current_password, $user['password'])) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
            if ($stmt->execute(['password' => $hashed_password, 'id' => $user_id])) {
                echo "Password changed successfully.";
            } else {
                echo "Error updating password.";
            }
        } else {
            echo "New passwords do not match.";
        }
    } else {
        echo "Current password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <link rel="stylesheet" href="../assets/style.css"> <!-- Link external CSS -->
</head>
<body>
    <h2>Change Password</h2>
    <form method="POST">
        <input type="password" name="current_password" placeholder="Current Password" required>
        <input type="password" name="new_password" placeholder="New Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
        <button type="submit">Change Password</button>
    </form>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
