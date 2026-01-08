<?php
require_once '../autoload.php';
require_once '../config/database.php';
require_once '../src/Auth.php';
require_once '../src/Admin.php'; // Kita pinjam fungsi getAllSoal dari Admin

Auth::checkLogin();

$database = new Database();
$db = $database->getConnection();
// Kita gunakan class Admin untuk mengambil semua soal (logic-nya sama)
$admin = new Admin($db);
$semua_soal = $admin->getAllSoal();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pembahasan & Kunci Jawaban</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #eef2f7; }
        .soal-card { background: white; padding: 20px; margin-bottom: 20px; border-radius: 8px; border-left: 5px solid #3498db; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .jawaban-benar { background: #d4edda; color: #155724; padding: 5px 10px; border-radius: 5px; font-weight: bold; display: inline-block; margin-top: 10px; border: 1px solid #c3e6cb; }
        .back-btn { background: #34495e; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-bottom: 20px; }
        img { max-width: 200px; border: 1px solid #ddd; margin: 10px 0; border-radius: 5px; }
    </style>
</head>
<body>

    <div style="max-width: 800px; margin: auto;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h1>📚 Kunci Jawaban & Pembahasan</h1>
            <a href="dashboard.php" class="back-btn">⬅ Kembali</a>
        </div>
        
        <p style="background: #fff3cd; padding: 10px; border-radius: 5px; border: 1px solid #ffeeba; color: #856404;">
            <strong>Catatan:</strong> Halaman ini menampilkan kunci jawaban yang benar untuk bahan belajar.
        </p>

        <?php foreach ($semua_soal as $i => $s): ?>
            <div class="soal-card">
                <p><strong>Soal No. <?= $i+1 ?> (<?= $s['kategori'] ?>)</strong></p>
                <p><?= $s['pertanyaan'] ?></p>

                <?php if (!empty($s['gambar'])): ?>
                    <img src="uploads/<?= $s['gambar'] ?>">
                <?php endif; ?>

                <div style="margin: 10px 0; color: #555;">
                    A. <?= $s['pilihan_a'] ?><br>
                    B. <?= $s['pilihan_b'] ?><br>
                    C. <?= $s['pilihan_c'] ?><br>
                    D. <?= $s['pilihan_d'] ?><br>
                    E. <?= $s['pilihan_e'] ?>
                </div>

                <div class="jawaban-benar">
                    ✅ Kunci Jawaban: <?= $s['kunci_jawaban'] ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>