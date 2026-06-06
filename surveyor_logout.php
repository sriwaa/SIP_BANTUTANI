<?php
session_start();
session_unset();   // Menghapus semua data session
session_destroy(); // Menghancurkan session agar user tidak bisa kembali lewat tombol back

// Arahkan ke index.php
header("Location: index.php"); 
exit();
?>