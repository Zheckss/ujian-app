<!DOCTYPE html>
<html>
<head>
    <title>Login Ujian Online</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f4f7f6; }
        .box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); width: 300px; }
        input { width: 100%; padding: 10px; margin: 10px 0; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #4CAF50; color: white; border: none; cursor: pointer; }
        .error { color: red; font-size: 12px; margin-bottom: 10px; display: block; }
    </style>
</head>
<body>
    <div class="box">
        <h2 style="text-align:center;">Login Siswa</h2>
        
        <?php if (isset($_GET['error'])): ?>
            <span class="error">Username atau Password Salah!</span>
        <?php endif; ?>
        
        <form action="login_proses.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Masuk</button>
        </form>
    </div>
</body>
</html>