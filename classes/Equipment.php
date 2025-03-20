<?php
require_once "../config/Database.php";

class Equipment {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }

    public function registerEquipment($name, $description, $added_by) {
        $sql = "INSERT INTO equipment (name, description, added_by) VALUES (:name, :description, :added_by)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['name' => $name, 'description' => $description, 'added_by' => $added_by]);
    }
    

    public function getAllEquipment($limit, $offset) {
        $sql = "SELECT * FROM equipment LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countEquipment() {
        $sql = "SELECT COUNT(*) as total FROM equipment";
        $stmt = $this->db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    public function requestEquipment($name, $reason, $requested_by) {
        $sql = "INSERT INTO equipment_requests (name, reason, requested_by) VALUES (:name, :reason, :requested_by)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['name' => $name, 'reason' => $reason, 'requested_by' => $requested_by]);
    }
    
}
?>
