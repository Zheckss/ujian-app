<?php
require_once '../autoload.php';
require_once '../config/database.php';
require_once '../src/Auth.php';
require_once '../src/Admin.php';

Auth::checkLogin();

// Cek apakah user adalah admin
if ($_SESSION['role'] !== 'admin') {
    die("Akses Ditolak.");
}

$database = new Database();
$db = $database->getConnection();
$admin = new Admin($db);

// --- 1. PROSES SIMPAN SOAL (PHP) ---
if (isset($_POST['tambah'])) {
    // Kita kirim $_POST (data teks) DAN $_FILES (data gambar) ke fungsi tambahSoal
    // Pastikan 'foto_soal' sesuai dengan name di input HTML
    $file_gambar = isset($_FILES['foto_soal']) ? $_FILES['foto_soal'] : null;

    if ($admin->tambahSoal($_POST, $file_gambar)) {
        echo "<script>alert('Berhasil! Soal & Gambar disimpan.'); window.location='admin_soal.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan soal.');</script>";
    }
}

// --- 2. PROSES HAPUS SOAL (PHP) ---
if (isset($_GET['hapus'])) {
    $admin->hapusSoal($_GET['hapus']);
    header("Location: admin_soal.php");
}

$daftar_soal = $admin->getAllSoal();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Kelola Soal</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f0f2f5; }
        .box { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        input[type="text"], textarea, select { width: 100%; padding: 10px; margin: 5px 0 15px; border: 1px solid #ddd; box-sizing: border-box; }
        .btn-green { background: #27ae60; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; }
        .btn-red { background: #c0392b; color: white; text-decoration: none; padding: 5px 10px; border-radius: 3px; font-size: 12px; }
        .badge { background: #34495e; color: white; padding: 3px 8px; font-size: 11px; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; vertical-align: top; }
        th { background: #2c3e50; color: white; }
        img.preview { max-width: 80px; max-height: 80px; border: 1px solid #ddd; border-radius: 4px; }
    </style>
</head>
<body>

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
        <h1>Panel Admin: Input Soal</h1>
        <div>
            <a href="export_excel.php" style="background:#2980b9; color:white; padding:10px; text-decoration:none; border-radius:5px; margin-right: 10px;">
                📥 Download Nilai
            </a>
            <a href="dashboard.php" style="background:#34495e; color:white; padding:10px; text-decoration:none; border-radius:5px;">Kembali</a>
        </div>
    </div>

    <div class="box">
        <h3>Tambah Soal Baru</h3>
        <form method="POST" enctype="multipart/form-data">
            
            <label>Kategori (Pelajaran):</label>
            <select name="kategori" required>
                <option value="Umum">Pengetahuan Umum</option>
                <option value="Matematika">Matematika</option>
                <option value="B.Inggris">Bahasa Inggris</option>
                <option value="IPA">IPA</option>
                <option value="IPS">IPS</option>
            </select>

            <label>Pertanyaan:</label>
            <textarea name="pertanyaan" rows="3" required placeholder="Tulis pertanyaan di sini..."></textarea>

            <div style="background: #eef2f7; padding: 10px; border-left: 4px solid #2980b9; margin-bottom: 15px;">
                <label style="font-weight: bold;">Upload Gambar (Opsional):</label><br>
                <input type="file" name="foto_soal" accept="image/*">
                <br><small style="color: #666;">Format: JPG, PNG. Biarkan kosong jika soal hanya teks.</small>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <div><label>Pilihan A:</label><input type="text" name="pilihan_a" required></div>
                <div><label>Pilihan B:</label><input type="text" name="pilihan_b" required></div>
                <div><label>Pilihan C:</label><input type="text" name="pilihan_c" required></div>
                <div><label>Pilihan D:</label><input type="text" name="pilihan_d" required></div>
                <div><label>Pilihan E:</label><input type="text" name="pilihan_e" required></div>
                <div>
                    <label>Kunci Jawaban:</label>
                    <select name="kunci_jawaban">
                        <option value="A">A</option><option value="B">B</option>
                        <option value="C">C</option><option value="D">D</option>
                        <option value="E">E</option>
                    </select>
                </div>
            </div>
            <br>
            <button type="submit" name="tambah" class="btn-green">💾 Simpan Soal</button>
        </form>
    </div>

    <div class="box">
        <h3>Bank Soal (Total: <?= count($daftar_soal) ?>)</h3>
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="10%">Gambar</th>
                    <th>Pertanyaan & Kategori</th>
                    <th width="5%">Kunci</th>
                    <th width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($daftar_soal as $i => $s): ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td>
                        <?php if (!empty($s['gambar'])): ?>
                            <img src="uploads/<?= $s['gambar'] ?>" class="preview">
                        <?php else: ?>
                            <span style="color:#ccc; font-size:12px;">(Teks)</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge"><?= $s['kategori'] ?></span><br>
                        <?= htmlspecialchars(substr($s['pertanyaan'], 0, 150)) ?>...
                    </td>
                    <td style="text-align:center; font-weight:bold;"><?= $s['kunci_jawaban'] ?></td>
                    <td>
                        <a href="?hapus=<?= $s['id_soal'] ?>" class="btn-red" onclick="return confirm('Yakin hapus soal ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>