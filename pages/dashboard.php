<?php
session_start();

require_once "../config/Database.php"; 
require_once "../classes/Request.php";




// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$db = new Database();
$conn = $db->conn;

// Fetch unread notifications
$notifStmt = $conn->prepare("SELECT id, message FROM notifications WHERE user_id = :user_id AND status = 'unread'");
$notifStmt->execute(['user_id' => $user_id]);
$notifications = $notifStmt->fetchAll(PDO::FETCH_ASSOC);


$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'User';
$role = $_SESSION['role'] ?? 'Guest';

// Fetch notifications
$db = new Database();
$conn = $db->conn;

$sql = "SELECT message FROM notifications WHERE user_id = :user_id AND status = 'unread'";
$stmt = $conn->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<h2>Welcome <?= htmlspecialchars($username); ?> To Inventory Management System</h2>
    <nav class="navbar">
        
        
        <ul>
            <li><a href="dashboard.php" class="btn">Dashboard</a></li>
            <?php if ($role === 'Coordinator') { ?>
                <li><a href="request_equipment.php" class="btn">Request Equipment</a></li>
                <li><a href="view_equipment.php" class="btn">View Equipment</a></li>
            <?php } ?>
            <?php if ($role === 'HoD') { ?>
                <li><a href="approve_request.php" class="btn">Approve Requests</a></li>
            <?php } ?>
            <?php if ($role === 'Logistic Officer') { ?>
                <li><a href="register_equipment.php" class="btn">Register Equipment</a></li>
            <?php } ?>
            <li><a href="change_password.php" class="btn">Change Password</a></li>
            <li><a href="../logout.php" class="btn logout">Logout</a></li>
        </ul>
    </nav>

    <div class="container1">
        <h2>Welcome, <?= htmlspecialchars($role); ?>!</h2>
        <p>Select an option from the menu above:</p>
        <?php if ($role === 'Coordinator') { ?>
            <ul>
                <li><a href="request_equipment.php">🛠 Request New Equipment</a></li>
                <li><a href="view_equipment.php">👀 View Equipment</a></li>
                <li><a href="change_password.php">🔑 Change Password</a></li>
            </ul>
        <?php } elseif ($role === 'HoD') { ?>
            <ul>
                <li><a href="approve_request.php">📝 Approve Equipment Requests</a></li>
                <li><a href="change_password.php">🔑 Change Password</a></li>
            </ul>
        <?php } elseif ($role === 'Logistic Officer') { ?>
            <ul>
                <li><a href="register_equipment.php">🛠 Register New Equipment</a></li>
                <li><a href="change_password.php">🔑 Change Password</a></li>
            </ul>
        <?php } ?>
    </div>

</body>
<footer>
    <p>&copy; <?= date("Y"); ?> Inventory Management System. All rights reserved.</p>
</footer>
</html>
