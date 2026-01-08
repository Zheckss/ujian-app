<?php
require_once '../autoload.php';
require_once '../config/database.php';
require_once '../src/Auth.php';
require_once '../src/Admin.php';

Auth::checkLogin();

// Cek Admin
if ($_SESSION['role'] !== 'admin') { die("Akses Ditolak."); }

$database = new Database();
$db = $database->getConnection();
$admin = new Admin($db);

// --- LOGIC TAMBAH SISWA ---
if (isset($_POST['tambah_siswa'])) {
    $nama = $_POST['nama'];
    $user = $_POST['username'];
    $pass = $_POST['password'];

    if ($admin->tambahSiswa($nama, $user, $pass)) {
        echo "<script>alert('Siswa berhasil didaftarkan!'); window.location='admin_siswa.php';</script>";
    } else {
        echo "<script>alert('Gagal! Username mungkin sudah dipakai.');</script>";
    }
}

// --- LOGIC HAPUS SISWA ---
if (isset($_GET['hapus'])) {
    $admin->hapusSiswa($_GET['hapus']);
    header("Location: admin_siswa.php");
}

$daftar_siswa = $admin->getAllSiswa();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Siswa</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f0f2f5; }
        .box { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 10px; margin: 5px 0 15px; border: 1px solid #ddd; box-sizing: border-box; }
        .btn-green { background: #27ae60; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; }
        .btn-red { background: #c0392b; color: white; text-decoration: none; padding: 5px 10px; border-radius: 3px; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #2c3e50; color: white; }
    </style>
</head>
<body>
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
        <h1>Kelola Data Siswa</h1>
        <a href="dashboard.php" style="background:#34495e; color:white; padding:10px; text-decoration:none; border-radius:5px;">Kembali</a>
    </div>

    <div class="box">
        <h3>Daftarkan Siswa Baru</h3>
        <form method="POST">
            <label>Nama Lengkap:</label>
            <input type="text" name="nama" required placeholder="Contoh: Budi Santoso">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <div>
                    <label>Username (untuk Login):</label>
                    <input type="text" name="username" required placeholder="Contoh: budi123">
                </div>
                <div>
                    <label>Password:</label>
                    <input type="text" name="password" required placeholder="Minimal 6 karakter">
                </div>
            </div>
            <button type="submit" name="tambah_siswa" class="btn-green">Simpan Siswa</button>
        </form>
    </div>

    <div class="box">
        <h3>Daftar Siswa Terdaftar (Total: <?= count($daftar_siswa) ?>)</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($daftar_siswa as $i => $s): ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><?= $s['nama_lengkap'] ?></td>
                    <td><?= $s['username'] ?></td>
                    <td>
                        <a href="?hapus=<?= $s['id_user'] ?>" class="btn-red" onclick="return confirm('Yakin hapus siswa ini? Semua nilai ujiannya juga akan terhapus.')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>