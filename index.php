<?php
session_start();
require_once "config/Database.php";

$db = new Database();
$conn = $db->conn;

// Fetch equipment
$stmt = $conn->query("SELECT * FROM equipment");
$equipment = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System</title>
    <link rel="stylesheet" href="assets/style1.css"> 
</head>
<body>

    <!-- Header Section -->
    <header>
        <div class="container">
            <h1>Inventory Management System</h1>
            <nav>
    <ul>
        <?php if (!isset($_SESSION['user_id'])) { ?>
            <li><a href="pages/login.php" class="btn">Login</a></li>
            <li><a href="pages/register.php" class="btn">Register</a></li>
        <?php } else { ?>
            <li><a href="pages/dashboard.php" class="btn">Dashboard</a></li>
        <?php } ?>
    </ul>
</nav>

        </div>
    </header>

    <!-- Main Content Section -->
    <div class="container">
        <?php if (!isset($_SESSION['user_id'])) { ?>
            <div class="welcome-message">
                <h2>Welcome to the Inventory Management System</h2>
                <p>Please login or register to access the system.</p>
            </div>
        <?php } ?>

        <h2>Available Equipment</h2>
        <ul class="equipment-list">
            <?php foreach ($equipment as $eq) { ?>
                <li class="equipment-item">
                    <div>
                        <h3><?= $eq['name'] ?></h3>
                        <p><?= $eq['description'] ?></p>
                    </div>
                    <span>Status: <?= $eq['status'] ?></span>
                </li>
            <?php } ?>
        </ul>
    </div>

    <!-- Footer Section -->
    <footer>
        <div class="container">
            <p>&copy; 2025 Inventory Management System. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>
