<?php
session_start();
require_once "../classes/Equipment.php";

if (!isset($_SESSION['user_id']) ) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $equipment = new Equipment();
    if ($equipment->registerEquipment($name, $description, $_SESSION['user_id'])) {
        echo "<script>alert('Equipment registered successfully!'); window.location.href='dashboard.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error registering equipment! Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Equipment</title>
    <link rel="stylesheet" href="../assets/style.css"> <!-- Ensure the CSS file exists -->
</head>
<body>
    <h2>Register Equipment</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Equipment Name" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <button type="submit">Register</button>
    </form>
    <a href="dashboard.php">Back to Dashboard</a>

    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Inventory Management System. All rights reserved.</p>
    </footer>
</body>
</html>
