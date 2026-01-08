<?php
require_once '../autoload.php';
require_once '../config/database.php';
require_once '../src/Auth.php';
require_once '../src/Dashboard.php';

Auth::checkLogin();

// Ambil ID dari URL (?id_nilai=...)
$id_nilai = isset($_GET['id']) ? $_GET['id'] : 0;

$database = new Database();
$db = $database->getConnection();

// Ambil Detail Nilai & Nama Siswa
$query = "SELECT n.*, u.nama_lengkap 
          FROM nilai n 
          JOIN users u ON n.id_user = u.id_user 
          WHERE n.id_nilai = :id AND n.id_user = :uid";

$stmt = $db->prepare($query);
$stmt->execute([':id' => $id_nilai, ':uid' => $_SESSION['user_id']]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

// Validasi: Kalau data tidak ada atau Nilai di bawah 70, tolak!
if (!$data || $data['skor'] < 70) {
    die("<h1 style='color:red; text-align:center; margin-top:50px;'>Maaf, Anda tidak lulus atau data tidak ditemukan.<br>Sertifikat hanya untuk nilai 70 ke atas.</h1>");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sertifikat Kelulusan</title>
    <style>
        body { background: #555; font-family: 'Times New Roman', serif; display: flex; justify-content: center; padding: 50px; }
        .sertifikat-box {
            width: 800px; height: 600px; background: #fff; padding: 20px; text-align: center;
            border: 10px solid #787878; position: relative;
        }
        .border-inner {
            border: 5px double #E67E22; height: 98%; padding: 20px;
        }
        h1 { font-size: 50px; color: #34495e; margin: 20px 0; }
        p { font-size: 20px; color: #555; }
        .name { font-size: 40px; color: #E67E22; border-bottom: 2px solid #ddd; display: inline-block; padding-bottom: 10px; margin: 20px 0; font-weight: bold; }
        .score { font-size: 25px; font-weight: bold; color: #27ae60; }
        .footer { margin-top: 50px; font-size: 18px; }
        
        /* Tombol Print (Hilang saat diprint) */
        .btn-print {
            position: fixed; top: 20px; right: 20px; padding: 10px 20px;
            background: blue; color: white; border: none; cursor: pointer; font-size: 16px; border-radius: 5px;
        }
        @media print {
            .btn-print { display: none; }
            body { background: white; padding: 0; }
            .sertifikat-box { border: 5px solid #333; width: 100%; height: 100vh; }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print">🖨️ Cetak / Simpan PDF</button>

    <div class="sertifikat-box">
        <div class="border-inner">
            <br>
            <span style="font-size: 25px; font-weight: bold; color: #888;">SERTIFIKAT KELULUSAN</span>
            
            <h1>PRESTASI GEMILANG</h1>
            
            <p>Diberikan kepada:</p>
            <div class="name"><?= strtoupper($data['nama_lengkap']) ?></div>
            
            <p>Telah berhasil menyelesaikan Ujian Online Berbasis Komputer<br>dengan hasil yang SANGAT BAIK.</p>
            
            <p class="score">NILAI AKHIR: <?= number_format($data['skor'], 0) ?></p>
            
            <div class="footer">
                <p>Diterbitkan pada: <?= date('d F Y', strtotime($data['tanggal_ujian'])) ?></p>
                <br><br>
                <p>_______________________<br>Kepala Sekolah</p>
            </div>
        </div>
    </div>

</body>
</html>