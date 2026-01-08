<?php
require_once '../autoload.php';
require_once '../config/database.php';
require_once '../src/Auth.php';
require_once '../src/Ujian.php';

Auth::checkLogin();

// --- PENGATURAN WAKTU UJIAN DI SINI ---
// Ganti angka 60 ini dengan berapa menit yang Anda mau.
// Contoh: 60 = 1 Jam, 90 = 1.5 Jam, 120 = 2 Jam.
$durasi_menit = 60; 
// ---------------------------------------

$database = new Database();
$db = $database->getConnection();
$ujian = new Ujian($db);

// Ambil 10 soal acak (Anda bisa ubah angkanya)
$daftar_soal = $ujian->getDaftarSoal(10); 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ujian Sedang Berlangsung</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f9f9f9; }
        .soal-box { background: white; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        
        /* Timer Melayang agar selalu terlihat */
        #timer { 
            position: fixed; 
            top: 20px; 
            right: 20px; 
            background: #e74c3c; 
            color: white; 
            padding: 10px 20px; 
            border-radius: 5px; 
            font-weight: bold; 
            z-index: 1000; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        button { padding: 10px 20px; background: #27ae60; color: white; border: none; font-size: 16px; cursor: pointer; border-radius: 5px; width: 100%; }
        button:hover { background: #219150; }
        img.soal-img { max-width: 100%; height: auto; max-height: 300px; border: 1px solid #ddd; margin: 10px 0; border-radius: 5px; }
    </style>
</head>
<body>

    <div id="timer">
        Sisa Waktu: <span id="countdown">00:00</span>
    </div>
    
    <div style="max-width: 800px; margin: auto;">
        <h2>Lembar Jawab Komputer (LJK)</h2>
        <p>Silakan kerjakan soal di bawah ini dengan teliti.</p>

        <form id="formUjian" action="submit_ujian.php" method="POST">
            
            <?php foreach ($daftar_soal as $i => $s): ?>
                <div class="soal-box">
                    <span style="background:#eee; padding:3px 8px; font-size:12px; border-radius:3px; color: #555;">
                        Soal No. <?= $i+1 ?> (<?= $s['kategori'] ?>)
                    </span>
                    
                    <p style="font-size: 18px;"><strong><?= $s['pertanyaan']; ?></strong></p>
                    
                    <?php if (!empty($s['gambar'])): ?>
                        <div>
                            <img src="uploads/<?= $s['gambar'] ?>" class="soal-img">
                        </div>
                    <?php endif; ?>

                    <div style="margin-top: 15px; line-height: 1.8;">
                        <label><input type="radio" name="jawaban[<?= $s['id_soal'] ?>]" value="A"> A. <?= $s['pilihan_a'] ?></label><br>
                        <label><input type="radio" name="jawaban[<?= $s['id_soal'] ?>]" value="B"> B. <?= $s['pilihan_b'] ?></label><br>
                        <label><input type="radio" name="jawaban[<?= $s['id_soal'] ?>]" value="C"> C. <?= $s['pilihan_c'] ?></label><br>
                        <label><input type="radio" name="jawaban[<?= $s['id_soal'] ?>]" value="D"> D. <?= $s['pilihan_d'] ?></label><br>
                        <label><input type="radio" name="jawaban[<?= $s['id_soal'] ?>]" value="E"> E. <?= $s['pilihan_e'] ?></label>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyelesaikan ujian?')">Selesai & Kirim Jawaban</button>
        </form>
    </div>

<script>
        // 1. SETTING WAKTU
        let waktuDetik = <?= $durasi_menit * 60 ?>; 
        const display = document.querySelector('#countdown');
        const form = document.querySelector('#formUjian');
        let isSubmitting = false; // Penanda agar tidak muncul warning saat submit beneran

        // 2. FUNGSI TIMER
        const timer = setInterval(() => {
            let jam = Math.floor(waktuDetik / 3600);
            let sisaDetik = waktuDetik % 3600;
            let menit = Math.floor(sisaDetik / 60);
            let detik = sisaDetik % 60;

            if (jam > 0) {
                display.textContent = `${jam}:${menit < 10 ? '0'+menit : menit}:${detik < 10 ? '0'+detik : detik}`;
            } else {
                display.textContent = `${menit}:${detik < 10 ? '0'+detik : detik}`;
            }

            if (--waktuDetik < 0) {
                clearInterval(timer);
                alert("WAKTU HABIS! \nJawaban Anda akan dikirim otomatis.");
                submitForm();
            }
        }, 1000);

        // 3. FUNGSI KIRIM JAWABAN
        function submitForm() {
            isSubmitting = true; // Matikan alarm anti-curang
            form.submit();
        }

        // Handle tombol submit manual
        form.addEventListener('submit', function() {
            isSubmitting = true;
        });

        // --- 4. FITUR ANTI CURANG (SECURITY) ---
        
        // A. Peringatan jika mau Refresh / Tombol Back / Tutup Tab
        window.onbeforeunload = function(e) {
            if (!isSubmitting) {
                return "Yakin ingin keluar? Waktu akan terus berjalan dan jawaban mungkin hilang!";
            }
        };

        // B. (Opsional) Deteksi Ganti Tab / Minimize Browser
        document.addEventListener("visibilitychange", function() {
            if (document.hidden) {
                document.title = "⚠️ JANGAN MENCONTEK! ⚠️";
                alert("PERINGATAN: Dilarang membuka tab lain atau aplikasi lain selama ujian!");
            } else {
                document.title = "Ujian Sedang Berlangsung";
            }
        });
    </script>
</body>
</html>