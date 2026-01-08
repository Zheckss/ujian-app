<?php
class Auth {
    private $db;
    private $table_name = "users";

    public function __construct($db_connection) {
        $this->db = $db_connection;
    }

    public function login($username, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password'])) {
                $this->createSession($row);
                return true;
            }
        }
        return false;
    }

    private function createSession($user) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['nama'] = $user['nama_lengkap'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['is_logged_in'] = true;
    }

    public static function checkLogin() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            // PERBAIKAN: Arahkan ke login.php, jangan ke index.php
            header("Location: ../public/login.php"); 
            exit;
        }
    }
    
    public function logout() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        session_destroy();
        header("Location: index.php");
        exit;
    }
}
?>