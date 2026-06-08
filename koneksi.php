<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sip_bantutani";

// Membuat koneksi
$conn = new mysqli($host, $user, $pass, $db);

// Cek apakah koneksi berhasil
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
?>