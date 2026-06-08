<?php
session_start();
include 'koneksi.php'; 

if (!isset($_SESSION['nik'])) {
    header("Location: petani_login.php");
    exit();
}

// Logika untuk proses update database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi = $_POST['konfirmasi'];
    $nik_login = $_SESSION['nik'];

    // Ambil password yang ada di database sekarang
    $query = "SELECT password FROM admin_datapetani WHERE nik = '$nik_login'";
    $result = $conn->query($query);
    $data = $result->fetch_assoc();

    // Cek apakah password lama benar
    if ($data['password'] == $password_lama) {
        // Cek apakah password baru dan konfirmasi sama
        if ($password_baru == $konfirmasi) {
            // Jalankan update ke database
            $update_query = "UPDATE admin_datapetani SET password = '$password_baru' WHERE nik = '$nik_login'";
            if ($conn->query($update_query)) {
                echo "<script>alert('Password berhasil diubah!'); window.location='petani_profile.php';</script>";
            } else {
                echo "<script>alert('Gagal update database!');</script>";
            }
        } else {
            echo "<script>alert('Konfirmasi password baru tidak cocok!');</script>";
        }
    } else {
        echo "<script>alert('Password lama Anda salah!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ubah Password - SIP-BANTU TANI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fbc02d; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .dashboard-container { width: 100%; max-width: 1280px; height: 850px; max-height: 92vh; background-color: white; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); display: flex; overflow: hidden; }

        /* SIDEBAR BRAND ONLY */
        .sidebar-brand-only { width: 280px; background-color: #0d7839; color: white; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 0; flex-shrink: 0; }
        .sidebar-logo-area { display: flex; flex-direction: column; align-items: center; text-align: center; padding: 0 20px; }
        .sidebar-logo-bg { width: 110px; height: 110px; background-color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; overflow: hidden; border: 2px solid #13a851; margin-bottom: 20px; }
        .sidebar-logo-img { width: 100%; height: 100%; object-fit: cover; }
        .sidebar-title { font-size: 20px; font-weight: bold; letter-spacing: 0.5px; margin-bottom: 6px; }
        .sidebar-subtitle { font-size: 12px; color: #d1e7dd; font-weight: 500; }

        /* MAIN CONTENT */
        .main-content { flex-grow: 1; display: flex; flex-direction: column; overflow: hidden; }
        .header-section { padding: 40px 50px 20px 50px; background: white; }
        .back-nav { display: flex; align-items: center; gap: 12px; color: #0d7839; text-decoration: none; font-size: 28px; font-weight: 700; margin-bottom: 5px; }
        .scrollable-content { flex-grow: 1; padding: 40px 50px; overflow-y: auto; display: flex; justify-content: center; align-items: flex-start; }

        .form-card { width: 100%; max-width: 450px; border: 1px solid #dee2e6; border-radius: 12px; padding: 30px; background: #ffffff; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
        
        .input-wrapper { position: relative; display: flex; align-items: center; }
        .input-wrapper input { width: 100%; padding: 12px 40px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        .input-wrapper i.fa-lock { position: absolute; left: 12px; color: #999; }
        .input-wrapper i.fa-eye { position: absolute; right: 12px; color: #999; cursor: pointer; }
        
        .btn-save { width: 100%; background: #0d7839; color: white; padding: 15px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; margin-top: 10px; }
    </style>
</head>
<body>

<div class="dashboard-container">
    <div class="sidebar-brand-only">
        <div class="sidebar-logo-area">
            <div class="sidebar-logo-bg"><img src="logo.png" alt="Logo" class="sidebar-logo-img"></div>
            <h2 class="sidebar-title">SIP-BANTU TANI</h2>
            <p class="sidebar-subtitle">Portal Resmi Bantuan Pertanian</p>
        </div>
    </div>

    <main class="main-content">
        <div class="header-section">
            <a href="petani_profile.php" class="back-nav"><span class="material-symbols-outlined" style="font-size: 32px;">arrow_back</span> Ubah Password</a>
        </div>

        <div class="scrollable-content">
            <form class="form-card" method="POST">
                <div class="form-group">
                    <label>Password Lama</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="password_lama" placeholder="Masukkan Password Lama" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Password Baru</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="password_baru" placeholder="Masukkan Password Baru" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="konfirmasi" placeholder="Konfirmasi Password" required>
                    </div>
                </div>

                <button type="submit" class="btn-save">Simpan Password</button>
            </form>
        </div>
    </main>
</div>

</body>
</html>