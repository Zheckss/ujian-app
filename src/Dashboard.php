<?php
class Dashboard {
    private $db;

    public function __construct($db_connection) {
        $this->db = $db_connection;
    }

    public function getRiwayatNilai($id_user) {
        $query = "SELECT * FROM nilai WHERE id_user = :id ORDER BY tanggal_ujian DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id_user);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Tambahkan fungsi ini agar tidak error saat dipanggil
    public function getRataRataNilai($id_user) {
        $query = "SELECT AVG(skor) as rata_rata FROM nilai WHERE id_user = :id_user";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return number_format($res['rata_rata'], 2);
    }
}
?>