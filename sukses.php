<?php 
// 1. Panggil file koneksi database
include 'config.php';

// 2. Cek apakah user sudah login
if (!isset($_SESSION['nim'])) {
    header("Location: index.php");
    exit();
}

// 3. Ambil data dari URL yang dikirim oleh dashboard.php
$matkul = isset($_GET['matkul']) ? htmlspecialchars($_GET['matkul']) : 'Mata Kuliah';
$waktu = isset($_GET['waktu']) ? htmlspecialchars($_GET['waktu']) : '00:00';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Berhasil</title>
    
    <style>
        /* Reset Dasar */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f4f6f9; /* Background abu-abu muda */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Styling Kartu Utama */
        .success-card {
            background-color: #ffffff;
            width: 380px;
            padding: 50px 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08); /* Bayangan lembut */
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Styling Ikon Centang Hijau */
        .check-icon {
            width: 100px;
            height: 100px;
            margin-bottom: 20px;
        }

        .check-icon svg {
            width: 100%;
            height: 100%;
            fill: #2eaa4b; /* Warna hijau centang */
        }

        /* Judul SUKSES! */
        h1 {
            color: #000;
            font-size: 36px;
            font-weight: 900;
            margin-bottom: 25px;
            letter-spacing: -1px;
        }

        /* Detail Informasi */
        .details {
            margin-bottom: 35px;
            line-height: 1.8;
            color: #000;
            font-size: 17px;
        }

        .details strong {
            font-weight: 700;
        }

        /* Tombol OK */
        .btn-ok {
            background-color: #2eaa4b; /* Hijau tombol */
            color: white;
            border: none;
            padding: 15px 60px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 80%;
            text-decoration: none; /* Hilangkan garis bawah link */
            display: inline-block;
        }

        .btn-ok:hover {
            background-color: #258a3c; /* Hijau lebih gelap saat hover */
        }
    </style>
</head>
<body>

    <div class="success-card">
        <!-- Ikon Centang Menggunakan SVG Inline -->
        <div class="check-icon">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/>
            </svg>
        </div>

        <!-- Judul -->
        <h1>SUKSES!</h1>

        <!-- Detail Data (Diisi otomatis dari PHP) -->
        <div class="details">
            <p><strong>Mata Kuliah:</strong> <?php echo $matkul; ?></p>
            <p><strong>Waktu:</strong> <?php echo $waktu; ?> WIB</p>
            <p><strong>Status:</strong> HADIR</p>
        </div>

        <!-- Tombol Kembali ke Dashboard -->
        <a href="dashboard.php" class="btn-ok">OK</a>
    </div>

</body>
</html>