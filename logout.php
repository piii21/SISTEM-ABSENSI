<?php
// 1. Mulai session untuk mengakses data sesi saat ini
session_start();

// 2. Hapus semua variabel session
$_SESSION = array();

// 3. Hancurkan session secara total
session_destroy();

// 4. Redirect (pindahkan) pengguna kembali ke halaman login
header("Location: index.php");
exit();
?>