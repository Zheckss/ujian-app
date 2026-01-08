<?php
require_once '../autoload.php';
require_once '../config/database.php';
require_once '../src/Auth.php';
require_once '../src/Admin.php';

Auth::checkLogin();
if ($_SESSION['role'] !== 'admin') { die("Akses Ditolak."); }

$database = new Database();
$db = $database->getConnection();
$admin = new Admin($db);

// Logic Reset
if (isset($_GET['reset'])) {
    $admin->resetNilai($_GET['reset']);
    header("Location: admin_hasil.php");
}

$hasil = $admin->getAllHasilUjian();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Monitor Hasil Ujian</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f4f4f4; }
        table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #2c3e50; color: white; }
        .btn-reset { background: #c0392b; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 12px; }
        .btn-back { background: #34495e; color: white; padding: 10px; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h1>🛡️ Monitor & Reset Nilai Siswa</h1>
        <a href="dashboard.php" class="btn-back">Kembali ke Dashboard</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Tanggal Ujian</th>
                <th>Skor</th>
                <th>Aksi (Admin)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($hasil as $i => $h): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td>
                    <strong><?= $h['nama_lengkap'] ?></strong><br>
                    <small style="color:grey;">@<?= $h['username'] ?></small>
                </td>
                <td><?= $h['tanggal_ujian'] ?></td>
                <td style="font-weight:bold; color: <?= $h['skor']>=70 ? 'green':'red' ?>">
                    <?= number_format($h['skor'],0) ?>
                </td>
                <td>
                    <a href="?reset=<?= $h['id_nilai'] ?>" class="btn-reset" onclick="return confirm('Yakin ingin mereset ujian siswa ini? Siswa harus ujian ulang.')">
                        🔄 Reset / Hapus
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>