<?php
require_once '../autoload.php';
require_once '../config/database.php';
require_once '../src/Auth.php';

Auth::checkLogin();

$database = new Database();
$db = $database->getConnection();

$error = "";
$success = "";

// PROSES GANTI PASSWORD
if (isset($_POST['ganti_password'])) {
    $pass_lama = $_POST['pass_lama'];
    $pass_baru = $_POST['pass_baru'];
    $konfirmasi = $_POST['konfirmasi'];
    $id_user = $_SESSION['user_id'];

    // 1. Cek Password Lama Benar tidak?
    $stmt = $db->prepare("SELECT password FROM users WHERE id_user = :id");
    $stmt->execute([':id' => $id_user]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (password_verify($pass_lama, $user['password'])) {
        // 2. Cek Password Baru vs Konfirmasi
        if ($pass_baru === $konfirmasi) {
            // 3. Update Password
            $hash_baru = password_hash($pass_baru, PASSWORD_DEFAULT);
            $update = $db->prepare("UPDATE users SET password = :p WHERE id_user = :id");
            if ($update->execute([':p' => $hash_baru, ':id' => $id_user])) {
                $success = "Password berhasil diubah! Silakan ingat password baru Anda.";
            } else {
                $error = "Gagal mengupdate database.";
            }
        } else {
            $error = "Konfirmasi password baru tidak cocok.";
        }
    } else {
        $error = "Password lama Anda salah.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profil Saya</title>
    <style>
        body { font-family: sans-serif; padding: 30px; background: #f4f7f6; }
        .box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); max-width: 500px; margin: auto; }
        input { width: 100%; padding: 10px; margin: 5px 0 15px; border: 1px solid #ddd; box-sizing: border-box; border-radius: 4px; }
        label { font-weight: bold; font-size: 14px; color: #555; }
        button { width: 100%; padding: 12px; background: #2980b9; color: white; border: none; cursor: pointer; border-radius: 5px; font-size: 16px; }
        button:hover { background: #2471a3; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 5px; text-align: center; }
        .success { background: #d4edda; color: #155724; }
        .danger { background: #f8d7da; color: #721c24; }
        .header-profil { text-align: center; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
    </style>
</head>
<body>

    <div class="box">
        <div class="header-profil">
            <h2>👤 Profil Pengguna</h2>
            <p>Nama: <strong><?= $_SESSION['nama'] ?></strong></p>
            <p>Role: <span style="background:#eee; padding:2px 6px; border-radius:4px;"><?= ucfirst($_SESSION['role']) ?></span></p>
        </div>

        <h3>🔐 Ganti Password</h3>

        <?php if ($error): ?>
            <div class="alert danger"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Password Lama:</label>
            <input type="password" name="pass_lama" required placeholder="Masukkan password saat ini">

            <label>Password Baru:</label>
            <input type="password" name="pass_baru" required placeholder="Minimal 6 karakter">

            <label>Ulangi Password Baru:</label>
            <input type="password" name="konfirmasi" required placeholder="Ketik ulang password baru">

            <button type="submit" name="ganti_password">Simpan Password Baru</button>
        </form>
        
        <br>
        <div style="text-align: center;">
            <a href="dashboard.php" style="color: #666; text-decoration: none;">&larr; Kembali ke Dashboard</a>
        </div>
    </div>

</body>
</html> 