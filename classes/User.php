<?php
require_once "../config/Database.php";

class User {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }

    public function register($username, $password, $role) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['username' => $username, 'password' => $hashedPassword, 'role' => $role]);
    }

    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?"); // Change email to username
        $stmt->execute([$username]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user && password_verify($password, $user['password'])) {
            return $user; // User found
        } else {
            return false; // Login failed
        }
    }
    public function changePassword($user_id, $current_password, $new_password) {
        $sql = "SELECT password FROM users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
    
        if (password_verify($current_password, $user['password'])) {
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE users SET password = ? WHERE id = ?";
            $update_stmt = $this->db->prepare($update_sql);
            return $update_stmt->execute([$new_hashed_password, $user_id]);
        } else {
            return false;
        }
    }
    
}
?>
