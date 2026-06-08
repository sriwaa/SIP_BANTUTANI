<?php
session_start();
// Pastikan koneksi benar
$conn = new mysqli("localhost", "root", "", "sip_bantutani");

if (!isset($_SESSION['nik'])) {
    die("Error: Anda belum login!");
}

$nik = $_SESSION['nik']; 
$id_program = $_POST['id_program'];

// 1. Cek Deadline (Keamanan dari tabel admin_programbantuan)
$query_cek = $conn->query("SELECT batas_akhir FROM admin_programbantuan WHERE id_program = '$id_program'");
$bantuan = $query_cek->fetch_assoc();

if (!$bantuan || date('Y-m-d') > $bantuan['batas_akhir']) {
    echo "<script>alert('Maaf, periode pengajuan sudah berakhir.'); window.history.back();</script>";
    exit;
}

// 2. Insert ke tabel admin_pengajuanbantuan
// Pastikan nama kolom di INSERT ini SAMA PERSIS dengan struktur tabel di image_10ce01.png
$sql = "INSERT INTO admin_pengajuanbantuan 
        (nik, id_program, tanggal_pengajuan, c1_luas_lahan, c2_penghasilan, c3_komoditas, c4_kondisi_lahan, c5_kepemilikan_alat, status_pengajuan) 
        VALUES 
        ('$nik', '$id_program', CURDATE(), '{$_POST['c1_luas']}', '{$_POST['c2_penghasilan']}', '{$_POST['c3_komoditas']}', '{$_POST['c4_kondisi']}', '{$_POST['c5_alat']}', 'Diproses')";

if ($conn->query($sql) === TRUE) {
    echo "<script>
            alert('Berhasil! Pengajuan telah masuk ke sistem.');
            window.location.href = 'petani_riwayat.php';
          </script>";
} else {
    // Jika masih gagal, ini akan menampilkan apa penyebab error-nya
    echo "Gagal menyimpan: " . $conn->error;
}
?>