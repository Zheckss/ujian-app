<?php
class Admin {
    private $db;
    private $table_name = "soal";

    public function __construct($db_connection) {
        $this->db = $db_connection;
    }

    // 1. Mengambil semua soal
    public function getAllSoal() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id_soal DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Menambah soal baru (DENGAN GAMBAR & KATEGORI)
    public function tambahSoal($data, $file = null) {
        $gambar = "";
        
        // Cek apakah ada file gambar yang diupload?
        if ($file && $file['error'] === 0) {
            $target_dir = "../public/uploads/";
            // Cek folder, jika belum ada buat dulu (opsional, tapi aman)
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $nama_file = time() . "_" . basename($file['name']); // Nama unik
            $target_file = $target_dir . $nama_file;
            
            // Pindahkan file ke folder uploads
            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                $gambar = $nama_file;
            }
        }

        $query = "INSERT INTO " . $this->table_name . "
                  (pertanyaan, gambar, pilihan_a, pilihan_b, pilihan_c, pilihan_d, pilihan_e, kunci_jawaban, kategori)
                  VALUES (:p, :g, :a, :b, :c, :d, :e, :k, :kat)";

        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':p'   => $data['pertanyaan'],
            ':g'   => $gambar, // Masukkan nama gambar
            ':a'   => $data['pilihan_a'],
            ':b'   => $data['pilihan_b'],
            ':c'   => $data['pilihan_c'],
            ':d'   => $data['pilihan_d'],
            ':e'   => $data['pilihan_e'],
            ':k'   => $data['kunci_jawaban'],
            ':kat' => $data['kategori'] // Kategori dinamis
        ]);
    }

    // 3. Menghapus soal
    public function hapusSoal($id) {
        // Ambil info gambar dulu untuk dihapus dari folder (Opsional agar bersih)
        $q_img = "SELECT gambar FROM " . $this->table_name . " WHERE id_soal = :id";
        $stmt_img = $this->db->prepare($q_img);
        $stmt_img->execute([':id' => $id]);
        $data = $stmt_img->fetch(PDO::FETCH_ASSOC);

        // Jika ada gambar fisiknya, hapus
        if ($data && !empty($data['gambar'])) {
            $path = "../public/uploads/" . $data['gambar'];
            if (file_exists($path)) {
                unlink($path);
            }
        }

        // Hapus data di database
        $query = "DELETE FROM " . $this->table_name . " WHERE id_soal = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // 4. Rekap Nilai (Untuk Export Excel)
    public function getRekapNilai() {
        $query = "SELECT u.nama_lengkap, u.username, n.skor, n.jumlah_benar, n.tanggal_ujian 
                  FROM nilai n 
                  JOIN users u ON n.id_user = u.id_user 
                  ORDER BY n.tanggal_ujian DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
// --- TAMBAHAN BARU: MANAJEMEN SISWA ---

    // 1. Ambil Semua Data Siswa
    public function getAllSiswa() {
        $query = "SELECT * FROM users WHERE role = 'siswa' ORDER BY nama_lengkap ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Tambah Siswa Baru
    public function tambahSiswa($nama, $username, $password) {
        // Cek dulu apakah username sudah ada?
        $cek = $this->db->prepare("SELECT id_user FROM users WHERE username = :u");
        $cek->execute([':u' => $username]);
        if ($cek->rowCount() > 0) {
            return false; // Gagal, username kembar
        }

        // Enkripsi Password
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (nama_lengkap, username, password, role) VALUES (:n, :u, :p, 'siswa')";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':n' => $nama,
            ':u' => $username,
            ':p' => $hash
        ]);
    }

    // 3. Hapus Siswa
    public function hapusSiswa($id) {
        // Hapus dulu nilai ujiannya biar bersih
        $this->db->prepare("DELETE FROM nilai WHERE id_user = :id")->execute([':id' => $id]);
        
        // Baru hapus akunnya
        $stmt = $this->db->prepare("DELETE FROM users WHERE id_user = :id");
        return $stmt->execute([':id' => $id]);
    }
    // --- TAMBAHAN BARU: MONITOR HASIL & RESET ---
    
    // 1. Ambil Semua Hasil Ujian (Lengkap dengan Nama)
    public function getAllHasilUjian() {
        $query = "SELECT n.id_nilai, n.skor, n.jumlah_benar, n.tanggal_ujian, u.nama_lengkap, u.username 
                  FROM nilai n 
                  JOIN users u ON n.id_user = u.id_user 
                  ORDER BY n.tanggal_ujian DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Reset/Hapus Nilai Tertentu
    public function resetNilai($id_nilai) {
        $query = "DELETE FROM nilai WHERE id_nilai = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $id_nilai]);
    }
} 
?>