<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian Online Sekolah</title>
    <style>
        body { margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; background: #f0f2f5; }
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh; display: flex; align-items: center; justify-content: center; text-align: center; color: white;
        }
        .container { max-width: 800px; padding: 20px; }
        h1 { font-size: 3.5rem; margin-bottom: 10px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); }
        p { font-size: 1.2rem; margin-bottom: 30px; opacity: 0.9; }
        .btn-start {
            background: #fff; color: #764ba2; padding: 15px 40px; font-size: 1.2rem; font-weight: bold;
            text-decoration: none; border-radius: 50px; transition: 0.3s; box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .btn-start:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.3); background: #f8f9fa; }
        .features { display: flex; justify-content: center; gap: 20px; margin-top: 50px; }
        .feature-box { background: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px; backdrop-filter: blur(5px); width: 200px; }
    </style>
</head>
<body>
    <div class="hero">
        <div class="container">
            <h1>Sistem Ujian Online</h1>
            <p>Platform ujian berbasis komputer yang aman, cepat, dan terpercaya.<br>Siap menghadapi masa depan pendidikan digital.</p>
            
            <a href="login.php" class="btn-start">🚀 Mulai Ujian / Login</a>

            <div class="features">
                <div class="feature-box">⏱️ <br>Timer Otomatis</div>
                <div class="feature-box">🔒 <br>Anti Curang</div>
                <div class="feature-box">📊 <br>Hasil Real-time</div>
            </div>
        </div>
    </div>
</body>
</html>