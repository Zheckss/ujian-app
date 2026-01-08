<?php
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// Konfigurasi Akun Admin Baru
$username = "admin1";
$password_asli = "password123"; // Password ini nanti dipakai login
$nama = "Administrator Utama";

// Enkripsi Password (Wajib!)
$password_hash = password_hash($password_asli, PASSWORD_DEFAULT);

try {
    // 1. Cek apakah username admin1 sudah ada?
    $cek = $db->prepare("SELECT id_user FROM users WHERE username = :u");
    $cek->execute([':u' => $username]);
    
    if ($cek->rowCount() > 0) {
        // Jika sudah ada, kita update passwordnya saja biar ke-reset
        $query = "UPDATE users SET password = :p, role = 'admin' WHERE username = :u";
        $stmt = $db->prepare($query);
        $stmt->execute([':p' => $password_hash, ':u' => $username]);
        echo "<h1>UPDATE BERHASIL!</h1>";
        echo "Password admin telah di-reset.";
    } else {
        // 2. Jika belum ada, kita buat baru
        $query = "INSERT INTO users (username, password, nama_lengkap, role) VALUES (:u, :p, :n, 'admin')";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':u' => $username,
            ':p' => $password_hash,
            ':n' => $nama
        ]);
        echo "<h1>SUKSES! ✅</h1>";
        echo "Akun Admin baru berhasil dibuat.";
    }

    echo "<hr>";
    echo "Username: <b>$username</b><br>";
    echo "Password: <b>$password_asli</b><br><br>";
    echo "<a href='index.php'>Login Sebagai Admin Sekarang</a>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>