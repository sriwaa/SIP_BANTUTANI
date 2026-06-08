<?php
$conn = new mysqli("localhost", "root", "", "sip_bantutani");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $nik = $_POST['nik'];
    $komoditas = $_POST['komoditas'];
    $alamat = $_POST['alamat'];
    $tempat = $_POST['tempat_lahir']; // Input baru
    $tanggal = $_POST['tanggal_lahir']; // Input baru
    
    $update = "UPDATE admin_datapetani SET 
               nama_lengkap = '$nama', 
               nik = '$nik', 
               komoditas = '$komoditas', 
               alamat = '$alamat', 
               tempat_lahir = '$tempat', 
               tanggal_lahir = '$tanggal' 
               WHERE id = 10";

    if ($conn->query($update)) {
        echo "<script>alert('Profil berhasil diupdate!'); window.location='petani_profile.php';</script>";
    } else {
        echo "Gagal update: " . $conn->error;
    }
}
?>