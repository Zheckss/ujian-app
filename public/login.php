<!DOCTYPE html>
<html>
<head>
    <title>Login - Ujian Online</title>
    <style>
        body {
            margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh; display: flex; align-items: center; justify-content: center;
        }
        .login-box {
            background: white; padding: 40px; border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2); width: 100%; max-width: 350px;
        }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #666; font-size: 14px; }
        input {
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;
            box-sizing: border-box; font-size: 14px;
        }
        button {
            width: 100%; padding: 12px; background: #764ba2; color: white;
            border: none; border-radius: 5px; font-size: 16px; cursor: pointer;
            transition: 0.3s; font-weight: bold; margin-top: 10px;
        }
        button:hover { background: #5b3a82; }
        .error {
            background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px;
            margin-bottom: 15px; font-size: 14px; text-align: center; border: 1px solid #f5c6cb;
        }
        .back-link {
            display: block; text-align: center; margin-top: 15px;
            color: #888; text-decoration: none; font-size: 14px;
        }
        .back-link:hover { color: #333; }
    </style>
</head>
<body>

    <div class="login-box">
        <h2>🔐 Login Masuk</h2>

        <?php if (isset($_GET['error'])): ?>
            <div class="error"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <form action="login_proses.php" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required placeholder="Masukkan username" autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Masukkan password">
            </div>
            <button type="submit">LOGIN SEKARANG</button>
        </form>

        <a href="index.php" class="back-link">← Kembali ke Halaman Depan</a>
    </div>

</body>
</html>