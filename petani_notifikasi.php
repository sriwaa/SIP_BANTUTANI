<?php
session_start();
include 'koneksi.php'; 

// Cek session
if (!isset($_SESSION['nik'])) {
    header("Location: petani_login.php");
    exit();
}

$nik = $_SESSION['nik'];
$nama_petani = $_SESSION['nama_lengkap'] ?? "Petani";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Notifikasi - SIP-BANTU TANI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fbc02d; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .dashboard-container { width: 100%; max-width: 1280px; height: 850px; max-height: 92vh; background-color: white; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); display: flex; overflow: hidden; }

        /* Sidebar Konsisten */
        .sidebar-brand-only { width: 280px; background-color: #0d7839; color: white; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 0; flex-shrink: 0; }
        .sidebar-logo-area { display: flex; flex-direction: column; align-items: center; text-align: center; padding: 0 20px; }
        .sidebar-logo-bg { width: 110px; height: 110px; background-color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; overflow: hidden; border: 2px solid #13a851; margin-bottom: 20px; }
        .sidebar-logo-img { width: 100%; height: 100%; object-fit: cover; }
        .sidebar-title { font-size: 20px; font-weight: bold; letter-spacing: 0.5px; margin-bottom: 6px; }
        .sidebar-subtitle { font-size: 12px; color: #d1e7dd; font-weight: 500; }

        .main-content { flex-grow: 1; display: flex; flex-direction: column; overflow: hidden; }
        .header-section { padding: 40px 50px 20px 50px; background: white; }
        .back-nav { display: flex; align-items: center; gap: 12px; color: #0d7839; text-decoration: none; font-size: 28px; font-weight: 700; }
        .scrollable-content { flex-grow: 1; padding: 0 50px 40px 50px; overflow-y: auto; }

        /* Notif Item */
        .notif-item { padding: 20px; border-left: 6px solid #ccc; background: #f9f9f9; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .Diterima { border-left-color: #28a745; background-color: #e8f5e9; }
        .Ditolak { border-left-color: #dc3545; background-color: #ffebee; }
        .Diproses { border-left-color: #ffc107; background-color: #fff3e0; }
        
        .notif-title { font-weight: bold; font-size: 18px; color: #333; margin-bottom: 8px; }
        .notif-status { font-weight: bold; padding: 4px 10px; border-radius: 5px; display: inline-block; margin-bottom: 10px; font-size: 13px; text-transform: uppercase; }
        .notif-pesan { font-size: 14px; color: #444; line-height: 1.6; background: rgba(255,255,255,0.6); padding: 12px; border-radius: 6px; }
        .notif-date { font-size: 12px; color: #888; margin-top: 10px; display: block; }
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
            <a href="petani_dashboard.php" class="back-nav"><span class="material-symbols-outlined" style="font-size: 32px;">arrow_back</span> Notifikasi Pengajuan</a>
        </div>

        <div class="scrollable-content">
            <?php
            $query = mysqli_query($conn, "SELECT r.status_pengajuan, p.nama_program, r.tanggal_pengajuan 
                                          FROM admin_pengajuanbantuan r 
                                          JOIN admin_programbantuan p ON r.id_program = p.id_program 
                                          WHERE r.nik = '$nik' ORDER BY r.id_pengajuan DESC");

            if (mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_assoc($query)) {
                    $status = $row['status_pengajuan'];
                    
                    if ($status == 'Diterima') {
                        $pesan = "<strong>SELAMAT!</strong> Pengajuan Anda diterima. Segera hubungi petugas atau datang ke Kantor Pertanian Pemerintah dengan <strong>membawa KTP asli</strong> dan <strong>menunjukkan bukti pengajuan diterima</strong> ini untuk proses pencairan.";
                    } elseif ($status == 'Ditolak') {
                        $pesan = "Mohon maaf, pengajuan Anda belum diterima. Silakan periksa kembali persyaratan dokumen Anda dan ajukan kembali melalui sistem.";
                    } else {
                        $pesan = "Pengajuan Anda sedang diperiksa oleh admin. Mohon tunggu informasi selanjutnya. Pantau terus halaman notifikasi ini.";
                    }

                    echo "<div class='notif-item $status'>
                            <div class='notif-title'>Program: {$row['nama_program']}</div>
                            <div class='notif-status $status'>Status: $status</div>
                            <div class='notif-pesan'>$pesan</div>
                            <span class='notif-date'><i class='fa-regular fa-calendar-days'></i> {$row['tanggal_pengajuan']}</span>
                          </div>";
                }
            } else {
                echo "<p style='text-align:center; color:#888; margin-top: 50px;'>Belum ada notifikasi pengajuan saat ini.</p>";
            }
            ?>
        </div>
    </main>
</div>

</body>
</html>