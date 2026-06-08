<?php
// File: petani_logout.php
session_start();

// Menghapus semua variabel sesi
session_unset();

// Menghancurkan sesi
session_destroy();

// Mengarahkan kembali ke halaman index.php
header("Location: index.php");
exit;
?>