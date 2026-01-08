<?php
class Ujian {
    private $db;
    private $table_name = "soal";

    public function __construct($db_connection) {
        $this->db = $db_connection;
    }

    // Ambil soal secara acak
    public function getDaftarSoal($limit = 10) {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY RAND() LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- TAMBAHAN BARU DI BAWAH INI ---

    // Menghitung skor ujian
    public function hitungNilai($jawaban_siswa) {
        $benar = 0;
        $total_soal = count($jawaban_siswa);
        
        foreach ($jawaban_siswa as $id_soal => $jawaban) {
            // Cek kunci jawaban di database
            $query = "SELECT kunci_jawaban FROM " . $this->table_name . " WHERE id_soal = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id_soal);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($data && $data['kunci_jawaban'] == $jawaban) {
                $benar++;
            }
        }
        
        // Rumus Nilai: (Benar / Total) * 100
        $skor = ($total_soal > 0) ? ($benar / $total_soal) * 100 : 0;
        return ['benar' => $benar, 'skor' => $skor];
    }

    // Simpan nilai ke database
    public function simpanHasil($id_user, $skor, $benar) {
        $query = "INSERT INTO nilai (id_user, skor, jumlah_benar) VALUES (:id_user, :skor, :benar)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->bindParam(':skor', $skor);
        $stmt->bindParam(':benar', $benar);
        return $stmt->execute();
    }
}
?>