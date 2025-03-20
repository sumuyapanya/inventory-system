<?php
session_start();
require_once "../classes/Request.php";  // Assuming you have a Request class

$db = new Database();
$conn = $db->conn;

if (isset($_GET['request_id'])) {
    $request_id = $_GET['request_id'];

    // Get the request details
    $stmt = $conn->prepare("SELECT * FROM equipment_requests WHERE id = :request_id");
    $stmt->execute(['request_id' => $request_id]);
    $request = $stmt->fetch(PDO::FETCH_ASSOC);
    

    if ($request) {
        $coordinator_id = $request['user_id']; // Coordinator who made the request

        // Update request status
        $updateStmt = $conn->prepare("UPDATE equipment_requests SET status = 'approved' WHERE id = :request_id");
        $updateStmt->execute(['request_id' => $request_id]);

        // Insert notification for the Coordinator
        $notifStmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (:user_id, :message)");
        $message = "Your equipment request (ID: $request_id) has been approved.";
        $notifStmt->execute(['user_id' => $coordinator_id, 'message' => $message]);

        $_SESSION['success'] = "Request approved and notification sent.";
    }
}

header("Location: view_requests.php");
exit();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HoD') {
    header("Location: login.php");
    exit();
}

// Fetch pending requests (implement this in your Request class)
$request = new Request();
// $pendingRequests = $request->getPendingRequests();
$pendingRequests = $request->getRequestsForApproval();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['approve'])) {
    $request_id = $_POST['request_id'];
    if ($request->approveRequest($request_id)) {
        $_SESSION['message'] = "Request approved successfully.";
        header("Location: approve_request.php");  // Redirect to prevent resubmission
        exit();
    } else {
        $_SESSION['message'] = "Error approving request.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve Requests</title>
</head>
<body>

<h2>Pending Equipment Requests</h2>

<!-- Display success or error message -->
<?php
if (isset($_SESSION['message'])) {
    echo "<p>{$_SESSION['message']}</p>";
    unset($_SESSION['message']); // Clear the message after displaying it
}
?>
 <?php if (isset($_SESSION['success'])): ?>
        <p style="color: green;"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php elseif (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
<table border="1">
    <tr>
        <th>Request ID</th>
        <th>Equipment</th>
        <th>Requester</th>
        <th>Action</th>
    </tr>

    <?php foreach ($pendingRequests as $req) { ?>
        <tr>
            <td><?= htmlspecialchars($req['id']) ?></td>
            <td><?= htmlspecialchars($req['equipment_id']) ?></td>
            <td><?= htmlspecialchars($req['requested_by']) ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="request_id" value="<?= htmlspecialchars($req['id']) ?>">
                    <button type="submit" name="approve">Approve</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>

<a href="dashboard.php">Back to Dashboard</a>

</body>
</html>
