<?php
class Database {
    private $host = "localhost";
    private $dbname = "inventory_db";
    private $username = "root"; // Change if necessary
    private $password = ""; // Change if necessary
    public $conn;

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database Connection Failed: " . $e->getMessage());
        }
    }
}
?>
