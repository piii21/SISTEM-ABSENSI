<?php
$host = "localhost";
$user = "root";
$pass = ""; // Default XAMPP kosong
$db   = "absensi_mahasiswa";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mulai session di setiap halaman
session_start();
?>