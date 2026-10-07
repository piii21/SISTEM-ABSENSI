<?php
session_start();
// Jika sudah login, langsung ke dashboard
if (isset($_SESSION['nim'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi Mahasiswa</title>
    <!-- Font Awesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* Reset & Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
            color: #333;
        }

        /* Navbar Sederhana */
        .navbar {
            width: 100%;
            max-width: 1200px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            margin-bottom: 40px;
        }

        .navbar .logo {
            color: white;
            font-size: 20px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar .logo i {
            font-size: 24px;
        }

        .navbar .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: 500;
            transition: opacity 0.3s;
        }

        .navbar .nav-links a:hover {
            opacity: 0.7;
        }

        /* Hero Section */
        .hero {
            text-align: center;
            color: white;
            max-width: 700px;
            margin-bottom: 50px;
        }

        .hero h1 {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 15px;
            line-height: 1.2;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .hero h1 span {
            color: #ffd700;
        }

        .hero p {
            font-size: 18px;
            font-weight: 300;
            opacity: 0.95;
            line-height: 1.6;
        }

        /* Main Card */
        .main-card {
            background: white;
            width: 100%;
            max-width: 900px;
            border-radius: 25px;
            padding: 50px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            margin-bottom: 40px;
        }

        .welcome-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .logo-icon i {
            font-size: 40px;
            color: white;
        }

        .welcome-section h2 {
            font-size: 32px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 10px;
        }

        .welcome-section p {
            color: #718096;
            font-size: 16px;
        }

        /* Fitur Cards */
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .feature-card {
            background: #f7fafc;
            padding: 25px 20px;
            border-radius: 15px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            border: 2px solid transparent;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-color: #667eea;
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 24px;
            color: white;
        }

        .feature-card:nth-child(1) .feature-icon { background: linear-gradient(135deg, #667eea, #764ba2); }
        .feature-card:nth-child(2) .feature-icon { background: linear-gradient(135deg, #f093fb, #f5576c); }
        .feature-card:nth-child(3) .feature-icon { background: linear-gradient(135deg, #4facfe, #00f2fe); }

        .feature-card h3 {
            font-size: 16px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .feature-card p {
            font-size: 13px;
            color: #718096;
            line-height: 1.5;
        }

        /* Tombol Masuk */
        .action-section {
            text-align: center;
        }

        .btn-masuk {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-decoration: none;
            padding: 18px 50px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .btn-masuk:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.6);
        }

        .btn-masuk i {
            transition: transform 0.3s;
        }

        .btn-masuk:hover i {
            transform: translateX(5px);
        }

        /* Info Login */
        .login-info {
            margin-top: 20px;
            padding: 15px;
            background: #f7fafc;
            border-radius: 10px;
            font-size: 13px;
            color: #718096;
        }

        .login-info strong {
            color: #2d3748;
        }

        /* Footer */
        .footer {
            color: rgba(255,255,255,0.8);
            text-align: center;
            font-size: 14px;
            margin-top: 20px;
        }

        .footer i {
            color: #ff6b6b;
        }

        /* Responsif */
        @media (max-width: 768px) {
            .hero h1 { font-size: 32px; }
            .main-card { padding: 30px 20px; }
            .welcome-section h2 { font-size: 24px; }
            .navbar { flex-direction: column; gap: 10px; }
            .navbar .nav-links a { margin: 0 10px; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <i class="fas fa-graduation-cap"></i>
            <span>Absensi Rpl 1B</span>
        </div>
        <div class="nav-links">
            <a href="#fitur">Fitur</a>
            <a href="login.php">Login</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Sistem Absensi <span>Mahasiswa</span> RPL 1B</h1>
        <p>Presensi kuliah lebih mudah, cepat, dan terintegrasi. Pantau kehadiran Anda secara real-time di mana saja.</p>
    </section>

    <!-- Main Card -->
    <div class="main-card" id="fitur">
        <div class="welcome-section">
            <div class="logo-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <h2>Selamat Datang!</h2>
            <p>Platform absensi digital untuk mahasiswa yang praktis dan terpercaya</p>
        </div>

        <!-- Fitur Unggulan -->
        <div class="features">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3>Cepat & Mudah</h3>
                <p>Absen hanya dengan satu klik, tanpa ribet dan antri.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Aman & Terpercaya</h3>
                <p>Data tersimpan aman dengan sistem autentikasi terenkripsi.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Real-time Monitoring</h3>
                <p>Pantau riwayat kehadiran Anda kapan saja.</p>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="action-section">
            <a href="login.php" class="btn-masuk">
                Masuk Sekarang <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>Dibuat oleh rpl1b untuk Mahasiswa rpl1b &copy; 2026</p>
    </footer>

</body>
</html>