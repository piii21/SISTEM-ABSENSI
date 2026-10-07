<?php
include 'config.php';

// Cek jika user sudah login, langsung lempar ke dashboard
if (isset($_SESSION['nim'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";
$success = "";

// Proses Login saat tombol diklik
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = $_POST['nim'];
    $password = md5($_POST['password']); 

    $sql = "SELECT * FROM mahasiswa WHERE nim='$nim' AND password='$password'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['nim'] = $row['nim'];
        $_SESSION['nama'] = $row['nama'];
        
        // Set success message sebelum redirect
        $_SESSION['login_success'] = true;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "NIM atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Absensi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #eef2f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-card {
            background: white;
            width: 350px;
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .logo-placeholder {
            width: 60px;
            height: 70px;
            margin: 0 auto 20px;
            background: linear-gradient(to bottom, #0056b3 50%, #28a745 50%);
            clip-path: polygon(0 0, 100% 0, 100% 75%, 50% 100%, 0 75%);
        }

        h2 {
            color: #000;
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 20px;
        }

        /* Pesan Error dengan Animasi */
        .error-msg {
            color: #dc3545;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            background-color: #f8d7da;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #f5c6cb;
            display: none;
            animation: slideDown 0.4s ease-out;
        }

        .error-msg.show {
            display: block;
        }

        .error-msg i {
            margin-right: 8px;
            animation: shake 0.5s ease-in-out;
        }

        /* Pesan Success */
        .success-msg {
            color: #28a745;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            background-color: #d4edda;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #c3e6cb;
            display: none;
            animation: slideDown 0.4s ease-out;
        }

        .success-msg.show {
            display: block;
        }

        .success-msg i {
            margin-right: 8px;
            animation: checkmark 0.6s ease-out;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
            position: relative;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
            background-color: #fcfcfc;
        }

        input:focus {
            border-color: #007bff;
        }

        /* Animasi Input Error */
        input.error {
            border-color: #dc3545;
            background-color: #fff5f5;
            animation: shake 0.5s ease-in-out;
        }

        input.error:focus {
            border-color: #dc3545;
        }

        /* Animasi Input Success */
        input.success {
            border-color: #28a745;
            background-color: #f0fff4;
        }

        input.success:focus {
            border-color: #28a745;
        }

        /* Icon di dalam input */
        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .input-icon.show {
            opacity: 1;
        }

        .input-icon.error {
            color: #dc3545;
        }

        .input-icon.success {
            color: #28a745;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,123,255,0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Loading Animation */
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spinner 0.8s linear infinite;
        }

        .btn-kembali {
            display: block;
            margin-top: 15px;
            color: #666;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }

        .btn-kembali:hover {
            color: #007bff;
            text-decoration: underline;
        }

        /* Keyframe Animations */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes checkmark {
            0% {
                transform: scale(0) rotate(-45deg);
            }
            50% {
                transform: scale(1.2) rotate(0deg);
            }
            100% {
                transform: scale(1) rotate(0deg);
            }
        }

        @keyframes spinner {
            to {
                transform: rotate(360deg);
            }
        }

        /* Card shake animation */
        .login-card.shake {
            animation: cardShake 0.5s ease-in-out;
        }

        @keyframes cardShake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-8px); }
            20%, 40%, 60%, 80% { transform: translateX(8px); }
        }

        /* Success overlay */
        .success-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(40, 167, 69, 0.95);
            display: none;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            color: white;
            z-index: 10;
            animation: fadeIn 0.3s ease-out;
        }

        .success-overlay.show {
            display: flex;
        }

        .success-overlay i {
            font-size: 60px;
            margin-bottom: 15px;
            animation: checkmark 0.6s ease-out;
        }

        .success-overlay p {
            font-size: 18px;
            font-weight: bold;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>

    <div class="login-card" id="loginCard">
        <!-- Success Overlay -->
        <div class="success-overlay" id="successOverlay">
            <i class="fas fa-check-circle"></i>
            <p>Login Berhasil!</p>
        </div>

        <div class="logo-placeholder"></div>
        <h2>LOGIN MAHASISWA</h2>

        <!-- Pesan Error -->
        <div class="error-msg" id="errorMsg">
            <i class="fas fa-exclamation-circle"></i>
            <span id="errorText"><?php echo $error; ?></span>
        </div>

        <!-- Pesan Success (untuk demo) -->
        <div class="success-msg" id="successMsg">
            <i class="fas fa-check-circle"></i>
            <span>Login berhasil! Mengalihkan...</span>
        </div>

        <form action="" method="POST" id="loginForm">
            <div class="input-group">
                <input type="text" name="nim" id="nim" placeholder="NIM" required>
                <i class="fas fa-times-circle input-icon" id="nimIcon"></i>
            </div>
            
            <div class="input-group">
                <input type="password" name="password" id="password" placeholder="Password" required>
                <i class="fas fa-times-circle input-icon" id="passwordIcon"></i>
            </div>

            <button type="submit" class="btn-login" id="loginBtn">LOGIN</button>
        </form>

        <a href="index.php" class="btn-kembali">← Kembali ke Beranda</a>
    </div>

    <script>
        const loginForm = document.getElementById('loginForm');
        const loginCard = document.getElementById('loginCard');
        const errorMsg = document.getElementById('errorMsg');
        const errorText = document.getElementById('errorText');
        const successMsg = document.getElementById('successMsg');
        const successOverlay = document.getElementById('successOverlay');
        const loginBtn = document.getElementById('loginBtn');
        const nimInput = document.getElementById('nim');
        const passwordInput = document.getElementById('password');
        const nimIcon = document.getElementById('nimIcon');
        const passwordIcon = document.getElementById('passwordIcon');

        // Cek jika ada error dari PHP
        <?php if ($error != ""): ?>
        window.addEventListener('DOMContentLoaded', function() {
            showError('<?php echo $error; ?>');
        });
        <?php endif; ?>

        // Fungsi tampilkan error
        function showError(message) {
            errorText.textContent = message;
            errorMsg.classList.add('show');
            successMsg.classList.remove('show');
            
            // Tambah class error ke input
            nimInput.classList.add('error');
            passwordInput.classList.add('error');
            
            // Tambah icon error
            nimIcon.className = 'fas fa-times-circle input-icon error show';
            passwordIcon.className = 'fas fa-times-circle input-icon error show';
            
            // Shake animation pada card
            loginCard.classList.add('shake');
            
            // Hapus class shake setelah animasi selesai
            setTimeout(() => {
                loginCard.classList.remove('shake');
            }, 500);
        }

        // Fungsi tampilkan success
        function showSuccess() {
            successMsg.classList.add('show');
            errorMsg.classList.remove('show');
            
            // Tambah class success ke input
            nimInput.classList.remove('error');
            passwordInput.classList.remove('error');
            nimInput.classList.add('success');
            passwordInput.classList.add('success');
            
            // Ubah icon jadi success
            nimIcon.className = 'fas fa-check-circle input-icon success show';
            passwordIcon.className = 'fas fa-check-circle input-icon success show';
            
            // Tampilkan overlay
            successOverlay.classList.add('show');
        }

        // Reset error saat user mulai mengetik
        nimInput.addEventListener('input', function() {
            this.classList.remove('error', 'success');
            nimIcon.classList.remove('show');
            errorMsg.classList.remove('show');
        });

        passwordInput.addEventListener('input', function() {
            this.classList.remove('error', 'success');
            passwordIcon.classList.remove('show');
            errorMsg.classList.remove('show');
        });

        // Handle form submit
        loginForm.addEventListener('submit', function(e) {
            const nim = nimInput.value.trim();
            const password = passwordInput.value.trim();
            
            // Validasi client-side
            if (!nim || !password) {
                e.preventDefault();
                showError('NIM dan Password harus diisi!');
                return;
            }
            
            // Tambah loading state
            loginBtn.classList.add('loading');
            loginBtn.textContent = 'Memproses...';
            
            // Biarkan form submit ke server
            // Jika success, PHP akan redirect ke dashboard
            // Jika error, PHP akan reload dengan pesan error
        });

        // Cek jika login berhasil (dari session)
        <?php if (isset($_SESSION['login_success']) && $_SESSION['login_success']): ?>
        showSuccess();
        setTimeout(() => {
            window.location.href = 'dashboard.php';
        }, 1500);
        <?php endif; ?>
    </script>

</body>
</html>