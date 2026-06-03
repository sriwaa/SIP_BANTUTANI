<?php
session_start();      // Memulai session agar bisa diakses
session_unset();      // Menghapus semua variabel session
session_destroy();    // Menghancurkan session
header("Location: index.php"); // Mengarahkan kembali ke halaman login (sesuaikan jika nama file login kamu berbeda)
exit;
?>