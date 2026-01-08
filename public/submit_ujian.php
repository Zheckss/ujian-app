<?php
require_once '../autoload.php';
require_once '../config/database.php';

Auth::checkLogin();

if ($_POST && isset($_POST['jawaban'])) {
    $database = new Database();
    $db = $database->getConnection();
    $ujian = new Ujian($db);

    // 1. Hitung Nilai
    $jawaban_siswa = $_POST['jawaban'];
    $hasil = $ujian->hitungNilai($jawaban_siswa);

    // 2. Simpan ke Database
    $ujian->simpanHasil($_SESSION['user_id'], $hasil['skor'], $hasil['benar']);

    // 3. Tampilkan Hasil Sederhana
    echo "<h1>Ujian Selesai!</h1>";
    echo "<p>Nilai Anda: <strong>" . number_format($hasil['skor'], 2) . "</strong></p>";
    echo "<p>Benar: " . $hasil['benar'] . " Soal</p>";
    echo "<a href='dashboard.php'>Kembali ke Dashboard</a>";
} else {
    header("Location: dashboard.php");
}
?>