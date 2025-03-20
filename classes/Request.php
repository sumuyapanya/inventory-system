<?php
require_once "../config/Database.php";
class Request {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }

    // Method to fetch pending requests for approval
    public function getRequestsForApproval() {
        $sql = "SELECT * FROM requests WHERE status = 'pending'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to approve the request
    public function approveRequest($request_id) {
        $sql = "UPDATE requests SET status = 'approved' WHERE id = :request_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":request_id", $request_id, PDO::PARAM_INT);
        return $stmt->execute();
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
    public function getPendingRequests() {
        // Assuming you have a PDO connection set up
        $stmt = $this->db->prepare("SELECT * FROM requests WHERE status = 'pending'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}

?>