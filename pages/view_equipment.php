<?php
session_start();
require_once "../classes/Equipment.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Coordinator') {
    header("Location: login.php");
    exit();
}

$equipment = new Equipment();

$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$allEquipment = $equipment->getAllEquipment($limit, $offset);
$totalEquipment = $equipment->countEquipment();
$totalPages = ceil($totalEquipment / $limit);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Equipment</title>
</head>
<body>
    <h2>Equipment List</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
        </tr>
        <?php foreach ($allEquipment as $eq) { ?>
            <tr>
                <td><?= $eq['id'] ?></td>
                <td><?= $eq['name'] ?></td>
                <td><?= $eq['description'] ?></td>
            </tr>
        <?php } ?>
    </table>

    <div>
        <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
            <a href="?page=<?= $i ?>"><?= $i ?></a>
        <?php } ?>
    </div>

    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
