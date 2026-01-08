<?php
require_once '../autoload.php';
require_once '../config/database.php';
require_once '../src/Auth.php';
require_once '../src/Dashboard.php';

Auth::checkLogin();

$database = new Database();
$db = $database->getConnection();
$dash = new Dashboard($db);
$riwayat = $dash->getRiwayatNilai($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Utama</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0; padding: 0;
            
            /* --- PENGATURAN BACKGROUND GAMBAR DI SINI --- */
            /* Anda bisa mengganti link di dalam url('...') dengan link gambar lain */
            background-image: url('https://images.unsplash.com/photo-1497633762265-9d179a990aa6?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
            
            /* Agar gambar full screen dan tidak gerak saat discroll */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            
            /* Agar konten berada di tengah */
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        /* Kotak Utama (Container) */
        .container {
            width: 90%;
            max-width: 1000px;
            background: rgba(255, 255, 255, 0.95); /* Putih dengan transparan dikit */
            padding: 40px;
            margin-top: 50px;
            margin-bottom: 50px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5); /* Bayangan biar timbul */
        }

        h1 { color: #2c3e50; margin-top: 0; }
        
        .role-badge { 
            background: #34495e; color: white; padding: 4px 10px; 
            border-radius: 15px; font-size: 14px; font-weight: bold; letter-spacing: 1px;
        }
        
        /* Tabel Keren */
        table { width: 100%; border-collapse: collapse; margin-top: 30px; overflow: hidden; border-radius: 8px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #27ae60; color: white; text-transform: uppercase; font-size: 14px; letter-spacing: 0.5px; }
        tr:hover { background-color: #f1f1f1; }

        /* Menu Navigasi */
        .menu-container { margin: 30px 0; display: flex; gap: 10px; flex-wrap: wrap; }
        .btn { 
            padding: 12px 20px; text-decoration: none; border-radius: 8px; 
            color: white; font-weight: bold; transition: 0.3s; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .btn-ujian { background: linear-gradient(to right, #2980b9, #3498db); } 
        .btn-soal  { background: linear-gradient(to right, #d35400, #e67e22); } 
        .btn-siswa { background: linear-gradient(to right, #8e44ad, #9b59b6); } 
        .btn-profil{ background: linear-gradient(to right, #2c3e50, #34495e); } 
        .btn-logout{ background: linear-gradient(to right, #c0392b, #e74c3c); } 

        .btn:hover { transform: translateY(-2px); box-shadow: 0 6px 12px rgba(0,0,0,0.15); opacity: 0.95; }
    </style>
</head>
<body>

    <div class="container">
        
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1>Selamat Datang, <?= $_SESSION['nama'] ?>! 👋</h1>
                <span class="role-badge"><?= strtoupper($_SESSION['role']) ?></span>
            </div>
            <div style="text-align: right; color: #7f8c8d;">
                <small><?= date('l, d F Y') ?></small>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

        <div class="menu-container">
            
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="admin_soal.php" class="btn btn-soal">🔧 Kelola Soal</a>
                <a href="admin_siswa.php" class="btn btn-siswa">👥 Kelola Siswa</a>
                <a href="admin_hasil.php" class="btn" style="background: linear-gradient(to right, #c0392b, #e74c3c);">🛡️ Reset Ujian</a> <?php endif; ?>

            <a href="profil.php" class="btn btn-profil">👤 Profil Saya</a>
            <a href="kerjakan.php" class="btn btn-ujian">📝 Mulai Ujian</a>
            <a href="pembahasan.php" class="btn" style="background: linear-gradient(to right, #16a085, #1abc9c);">📚 Lihat Pembahasan</a> <a href="logout.php" class="btn btn-logout">Logout</a>
        </div>

        <h3>📊 Riwayat Nilai Anda</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal Ujian</th>
                    <th>Jawaban Benar</th>
                    <th>Skor Akhir</th>
                    <th>Status / Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($riwayat)): ?>
                    <tr><td colspan="5" style="text-align:center; padding: 30px; color: #999;">belum ada riwayat ujian.</td></tr>
                <?php else: ?>
                    <?php foreach ($riwayat as $i => $r): ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <td><?= date('d M Y - H:i', strtotime($r['tanggal_ujian'])) ?></td>
                        <td><?= $r['jumlah_benar'] ?> Soal</td>
                        <td><strong style="font-size: 1.2em; color: #27ae60;"><?= number_format($r['skor'], 0) ?></strong></td>
                        <td>
                            <?php if ($r['skor'] >= 70): ?>
                                <a href="sertifikat.php?id=<?= $r['id_nilai'] ?>" target="_blank" style="background: #f1c40f; color: #333; padding: 5px 10px; text-decoration: none; border-radius: 50px; font-size: 12px; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                    🏆 CETAK SERTIFIKAT
                                </a>
                            <?php else: ?>
                                <span style="color: #e74c3c; font-size: 12px; font-weight: bold;">TIDAK LULUS</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>