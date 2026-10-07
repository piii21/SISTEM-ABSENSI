<?php 
// 1. Panggil file koneksi database
include 'config.php';

// 2. Cek apakah user sudah login
if (!isset($_SESSION['nim'])) {
    header("Location: index.php");
    exit();
}

// 3. PROSES ABSEN SAAT TOMBOL DITEKAN
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['absen_matkul'])) {
    $nim = $_SESSION['nim'];
    $matkul = $_POST['absen_matkul'];
    $waktu_sekarang = date("Y-m-d H:i:s");
    $jam_sekarang = date("H:i");
    
    // Cek apakah hari ini sudah absen matkul ini
    $cek = $conn->query("SELECT * FROM absensi WHERE nim='$nim' AND mata_kuliah='$matkul' AND DATE(waktu_absen) = CURDATE()");
    
    if ($cek->num_rows == 0) {
        // Insert data absensi
        $sql = "INSERT INTO absensi (nim, mata_kuliah, waktu_absen, status) VALUES ('$nim', '$matkul', '$waktu_sekarang', 'HADIR')";
        if ($conn->query($sql)) {
            // Redirect ke halaman sukses sambil bawa data
            header("Location: sukses.php?matkul=".urlencode($matkul)."&waktu=".$jam_sekarang);
            exit();
        } else {
            echo "<script>alert('Terjadi kesalahan saat menyimpan data!');</script>";
        }
    } else {
        echo "<script>alert('Anda sudah absen mata kuliah ini hari ini!');</script>";
    }
}

// 4. DATA JADWAL MATA KULIAH
// (Bisa juga diambil dari tabel database jika ada tabel jadwal)
$jadwal = [
    [
        'matkul' => 'Algrytma', 
        'hari' => 'Senin', 
        'jam' => '08.00-10.00', 
        'ruang' => 'R. 301', 
        'color' => 'blue'
    ],
    [
        'matkul' => 'Basis Data', 
        'hari' => 'Selasa', 
        'jam' => '10.00-12.00', 
        'ruang' => 'R. 205', 
        'color' => 'green'
    ],
    [
        'matkul' => 'Jaringan Komputer', 
        'hari' => 'Kamis', 
        'jam' => '13.00-15.00', 
        'ruang' => 'R. 407', 
        'color' => 'yellow'
    ]
];

// 5. Hitung total absensi mahasiswa
$nim = $_SESSION['nim'];
$total_absen = $conn->query("SELECT COUNT(*) as total FROM absensi WHERE nim='$nim'")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Absensi Mahasiswa</title>
    <!-- Mengambil Font Awesome untuk Ikon Gear/Settings -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Reset Dasar */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f0f2f5; /* Background abu-abu sangat muda */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* Container Utama */
        .main-container {
            width: 100%;
            max-width: 950px;
        }
/* --- Header Section --- */
.header-bar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 15px;
    margin-bottom: 40px;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.user-info {
    display: flex;
    flex-direction: column;
}

.user-info h1 {
    font-size: 24px;
    color: white;
    font-weight: 700;
    letter-spacing: -0.5px;
    margin-bottom: 5px;
}

.user-info p {
    font-size: 13px;
    color: rgba(255,255,255,0.8);
    font-weight: 400;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 15px;
}

.settings-icon {
    width: 40px;
    height: 40px;
    background: rgba(255,255,255,0.2);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s;
}

.settings-icon i {
    font-size: 20px;
    color: white;
}

.settings-icon:hover {
    background: rgba(255,255,255,0.3);
    transform: rotate(90deg);
}

/* Tombol Logout yang Baru */
.logout-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.2);
    color: white;
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s;
    border: 2px solid rgba(255,255,255,0.3);
}

.logout-btn:hover {
    background: #ff4757;
    border-color: #ff4757;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 71, 87, 0.4);
}

.logout-btn i {
    font-size: 16px;
}

        /* --- Container Kartu --- */
        .cards-container {
            display: flex;
            justify-content: center;
            gap: 30px; /* Jarak antar kartu */
            flex-wrap: wrap; /* Agar turun ke bawah jika layar kecil */
        }

        /* --- Styling Kartu Mata Kuliah --- */
        .card {
            background-color: #ffffff;
            width: 280px;
            padding: 30px 25px;
            border-radius: 25px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.08); /* Bayangan lembut & dalam */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 330px; /* Tinggi tetap agar seragam */
            position: relative;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        /* Indikator Warna Bulat */
        .color-dot {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-bottom: 20px;
        }
        
        .blue { background-color: #5D9CEC; }
        .green { background-color: #48CFAD; }
        .yellow { background-color: #FFCE54; }

        /* Teks di dalam Kartu */
        .card h2 {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 15px;
            font-weight: 800;
            line-height: 1.2;
        }

        .card p {
            font-size: 15px;
            color: #555;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .card .room {
            font-size: 16px;
            color: #333;
            font-weight: bold;
            margin-top: auto; /* Mendorong ruang dan tombol ke bawah */
            margin-bottom: 20px;
        }

        /* Tombol Absen */
        .btn-absen {
            background-color: #5cb876; /* Hijau tombol */
            color: white;
            border: none;
            padding: 15px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s, transform 0.1s;
            width: 100%;
            letter-spacing: 1px;
        }

        .btn-absen:hover {
            background-color: #4cae66; /* Hijau lebih gelap saat hover */
        }
        
        .btn-absen:active {
            transform: scale(0.98);
        }

        /* Statistik Kecil */
        .stats {
            background-color: #fff;
            padding: 15px 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .stats h3 {
            font-size: 16px;
            color: #666;
            font-weight: 500;
        }

        .stats span {
            font-size: 24px;
            color: #007bff;
            font-weight: bold;
        }

    </style>
</head>
<body>

    <div class="main-container">
        <!-- Bagian Header Atas -->
        <div class="header-bar">
            <div class="user-info">
                <h1>Halo, <?php echo htmlspecialchars($_SESSION['nama']); ?></h1>
                <p>NIM: <?php echo htmlspecialchars($_SESSION['nim']); ?></p>
            </div>
            <div>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>

        <!-- Statistik Absensi -->
        <div class="stats">
            <h3>Total Absensi Hari Ini: <span><?php echo $total_absen; ?></span></h3>
        </div>

        <!-- Bagian Kartu Mata Kuliah -->
        <div class="cards-container">
            
            <?php foreach($jadwal as $item): ?>
            <!-- Kartu <?php echo $item['matkul']; ?> -->
            <div class="card">
                <div class="color-dot <?php echo $item['color']; ?>"></div>
                <h2><?php echo $item['matkul']; ?></h2>
                <p><?php echo $item['hari']; ?>, <?php echo $item['jam']; ?></p>
                <p class="room"><?php echo $item['ruang']; ?></p>
                
                <!-- Form untuk absen -->
                <form action="" method="POST">
                    <input type="hidden" name="absen_matkul" value="<?php echo $item['matkul']; ?>">
                    <button type="submit" class="btn-absen">ABSEN</button>
                </form>
            </div>
            <?php endforeach; ?>

        </div>
    </div>

</body>
</html>