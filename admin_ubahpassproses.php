<?php
// 1. KONEKSI DATABASE
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sip_bantutani";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Koneksi gagal: " . $conn->connect_error); }

// 2. TANGKAP DATA DARI FORM
$id_admin = 1; // Sesuaikan dengan ID admin
$password_lama = $_POST['password_lama'];
$password_baru = $_POST['password_baru'];
$konfirmasi_password = $_POST['konfirmasi_password'];

// 3. CEK PASSWORD LAMA
$result = $conn->query("SELECT password FROM admin_users WHERE id_user = $id_admin");
$admin = $result->fetch_assoc();

if ($password_lama !== $admin['password']) {
    echo "<script>alert('Password lama salah!'); window.history.back();</script>";
    exit;
}

// 4. CEK KONFIRMASI PASSWORD BARU
if ($password_baru !== $konfirmasi_password) {
    echo "<script>alert('Password baru dan konfirmasi tidak cocok!'); window.history.back();</script>";
    exit;
}

// 5. UPDATE PASSWORD DI DATABASE
$sql_update = "UPDATE admin_users SET password = '$password_baru' WHERE id_user = $id_admin";
if ($conn->query($sql_update) === TRUE) {
    echo "<script>alert('Password berhasil diubah!'); window.location='admin_profil.php';</script>";
} else {
    echo "<script>alert('Gagal update: " . $conn->error . "'); window.history.back();</script>";
}

$conn->close();
?>