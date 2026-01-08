<?php
require_once '../autoload.php';
require_once '../config/database.php';
require_once '../src/Auth.php';
require_once '../src/Admin.php';

Auth::checkLogin();

if ($_SESSION['role'] !== 'admin') {
    die("Akses Ditolak.");
}

$database = new Database();
$db = $database->getConnection();
$admin = new Admin($db);

$rekap = $admin->getRekapNilai();
$filename = "rekap_nilai_" . date('Y-m-d_H-i') . ".csv";

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=$filename");

$output = fopen("php://output", "w");
fputcsv($output, ['No', 'Nama Siswa', 'Username', 'Skor', 'Benar', 'Tanggal']);

foreach ($rekap as $i => $row) {
    fputcsv($output, [
        $i + 1,
        $row['nama_lengkap'],
        $row['username'],
        $row['skor'],
        $row['jumlah_benar'],
        $row['tanggal_ujian']
    ]);
}
fclose($output);
exit;
?>