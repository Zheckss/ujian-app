<?php
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Kita akan membuat user baru / mereset user yang ada
$username = "siswa1";
$password_asli = "password123";

// 1. Enkripsi password menggunakan algoritma server Anda saat ini
$password_hash = password_hash($password_asli, PASSWORD_DEFAULT);

try {
    // Hapus user lama jika ada (biar bersih)
    $stmt = $db->prepare("DELETE FROM users WHERE username = :u");
    $stmt->execute([':u' => $username]);

    // Masukkan user baru dengan password yang SUDAH DI-HASH
    $query = "INSERT INTO users (username, password, nama_lengkap, role) VALUES (:u, :p, 'Siswa Percobaan', 'siswa')";
    $stmt = $db->prepare($query);
    $stmt->execute([
        ':u' => $username,
        ':p' => $password_hash
    ]);

    echo "<h1>SUKSES! ✅</h1>";
    echo "<p>User <b>$username</b> berhasil dibuat ulang.</p>";
    echo "<p>Password baru (Hash): $password_hash</p>";
    echo "<hr>";
    echo "Silakan coba login sekarang dengan password: <b>$password_asli</b><br><br>";
    echo "<a href='index.php'>Ke Halaman Login</a>";

} catch (PDOException $e) {
    echo "Gagal: " . $e->getMessage();
}
?>