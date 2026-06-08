<?php
// Koneksi ke database
$conn = mysqli_connect('localhost', 'root', '', 'sip_bantutani');

// Cek jika ada data yang dikirim melalui metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil data dari form (pastikan 'name' di input HTML sesuai dengan ini)
    $nik = $_POST['nik'];
    $nama = $_POST['nama'];
    $telepon = $_POST['telepon'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Mengamankan password

    // Query untuk menyimpan data
    // Pastikan nama tabel di bawah 'petani_daftar' dan kolomnya sesuai
    $query = "INSERT INTO petani_daftar (NIK, Nama, `No Telepon`, Password) VALUES ('$nik', '$nama', '$telepon', '$password')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Pendaftaran Berhasil!'); window.location='index.php';</script>";
    } else {
        // Jika ada error, ini akan memunculkan pesan errornya
        echo "Error: " . mysqli_error($conn);
    }
}
?>