<?php
session_start();
// require_once "../config/Database.php";
require_once "../classes/Equipment.php";
// require_once "../classes/Request.php";

class Request {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }

    public function makeRequest($equipment_id, $requested_by) {
        $sql = "INSERT INTO requests (equipment_id, requested_by) VALUES (:equipment_id, :requested_by)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['equipment_id' => $equipment_id, 'requested_by' => $requested_by]);
    }

    public function getRequestsForApproval() {
        $sql = "SELECT requests.id, equipment.name, users.name AS requester
                FROM requests
                JOIN equipment ON requests.equipment_id = equipment.id
                JOIN users ON requests.requested_by = users.id
                WHERE requests.status = 'Pending'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function approveRequest($request_id) {
        $sql = "UPDATE requests SET status = 'Approved' WHERE id = :request_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['request_id' => $request_id]);
        $sql2 = "SELECT user_id FROM requests WHERE id = :request_id";
    $stmt2 = $this->db->prepare($sql2);
    $stmt2->execute(['request_id' => $request_id]);
    $request = $stmt2->fetch(PDO::FETCH_ASSOC);
    $user_id = $request['user_id'];

    // Insert notification
    $sql3 = "INSERT INTO notifications (user_id, message) VALUES (:user_id, 'Your request has been approved.')";
    $stmt3 = $this->db->prepare($sql3);
    return $stmt3->execute(['user_id' => $user_id]);
    }
}
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Coordinator') {
    header("Location: login.php");
    exit();
}
$equipment = new Equipment();
$request = new Request();

$availableEquipment = $equipment->getAllEquipment(50, 0); // Fetch all available equipment

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $equipment_id = $_POST['equipment_id'];
    $requested_by = $_SESSION['user_id'];

    if ($request->makeRequest($equipment_id, $requested_by)) {
        echo "Request submitted successfully.";
    } else {
        echo "Error submitting request.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Request Equipment</title>
</head>
<body>
    <h2>Request Equipment</h2>
    <form method="POST">
        <select name="equipment_id" required>
            <option value="">Select Equipment</option>
            <?php foreach ($availableEquipment as $eq) { ?>
                <option value="<?= $eq['id'] ?>"><?= $eq['name'] ?></option>
            <?php } ?>
        </select>
        <button type="submit">Request</button>
    </form>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
