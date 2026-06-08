<?php
// Sambungkan ke database
$conn = new mysqli("localhost", "root", "", "db_namamu"); // Ganti dengan nama database kamu

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nik = $_POST['nik'];
    $nama = $_POST['nama_lengkap'];
    $telp = $_POST['no_telp'];
    $password = $_POST['password']; // Simpan langsung atau pakai password_hash()

    $sql = "INSERT INTO admin_datapetani (nik, nama_lengkap, no_telp, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nik, $nama, $telp, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Berhasil daftar! Silahkan login.'); window.location='petani_login.php';</script>";
    } else {
        echo "Gagal daftar: " . $conn->error;
    }
}
?>