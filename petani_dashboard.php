<?php
session_start();
include 'koneksi.php'; 

// Cek session
if (!isset($_SESSION['nik'])) {
    header("Location: petani_login.php");
    exit();
}

$nama_petani = $_SESSION['nama_lengkap'] ?? "Petani";
$nik = $_SESSION['nik'];

// Ambil data ringkasan
$query_total = mysqli_query($conn, "SELECT COUNT(*) as total FROM admin_pengajuanbantuan WHERE nik = '$nik'");
$data_total = mysqli_fetch_assoc($query_total)['total'];

$query_diterima = mysqli_query($conn, "SELECT COUNT(*) as diterima FROM admin_pengajuanbantuan WHERE nik = '$nik' AND status_pengajuan = 'Diterima'");
$data_diterima = mysqli_fetch_assoc($query_diterima)['diterima'];

$query_ditolak = mysqli_query($conn, "SELECT COUNT(*) as ditolak FROM admin_pengajuanbantuan WHERE nik = '$nik' AND status_pengajuan = 'Ditolak'");
$data_ditolak = mysqli_fetch_assoc($query_ditolak)['ditolak'];

// Ambil program bantuan
$query_bantuan = mysqli_query($conn, "SELECT id_program AS id, nama_program AS nama_bantuan, gambar, deskripsi FROM admin_programbantuan ORDER BY id_program ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP-BANTU TANI - Dashboard Petani</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fbc02d; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .dashboard-container { width: 100%; max-width: 1280px; height: 850px; max-height: 92vh; background-color: white; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); display: flex; overflow: hidden; }

        /* SIDEBAR */
        .sidebar { width: 280px; background-color: #0d7839; color: white; display: flex; flex-direction: column; align-items: center; padding: 40px 0; flex-shrink: 0; }
        .sidebar-logo-area { display: flex; flex-direction: column; align-items: center; text-align: center; padding: 0 20px; margin-bottom: 40px; }
        .sidebar-logo-bg { width: 90px; height: 90px; background-color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; overflow: hidden; border: 2px solid #13a851; margin-bottom: 12px; }
        .sidebar-logo-img { width: 100%; height: 100%; object-fit: cover; }
        .sidebar-title { font-size: 16px; font-weight: bold; letter-spacing: 0.5px; margin-bottom: 4px; }
        .sidebar-subtitle { font-size: 11px; color: #d1e7dd; font-weight: 500; }
        .sidebar-menu { width: 100%; list-style: none; display: flex; flex-direction: column; }
        .menu-item { display: flex; align-items: center; padding: 16px 28px; color: rgba(255, 255, 255, 0.8); font-size: 16px; font-weight: 600; transition: all 0.2s ease; gap: 15px; text-decoration: none; cursor: pointer; }
        .menu-item.active { background-color: rgba(255, 255, 255, 0.15); border-left: 5px solid #3bf789; color: white; }
        .menu-item:hover { background-color: rgba(255, 255, 255, 0.1); color: white; padding-left: 33px; }
        .menu-icon { font-size: 22px; }

        /* MAIN CONTENT */
        .main-content { flex-grow: 1; background-color: white; padding: 40px 50px; display: flex; flex-direction: column; overflow: hidden; }
        .page-header { margin-bottom: 30px; border-bottom: 1px solid #dee2e6; padding-bottom: 15px; display: flex; justify-content: space-between; align-items: center; }
        .page-title { color: #0d7839; font-size: 28px; font-weight: 700; margin-bottom: 8px; }
        .welcome-text { color: #495057; font-size: 16px; font-weight: 500; }
        
        .stats-grid { display: flex; gap: 30px; margin-bottom: 40px; }
        .stat-card { flex: 1; background: white; border-radius: 8px; padding: 20px 25px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 15px rgba(0,0,0,0.06); border: 1px solid #e9ecef; }
        .stat-info { display: flex; flex-direction: column; gap: 5px; }
        .stat-label { color: #6c757d; font-size: 12px; font-weight: 600; }
        .stat-value { color: #212529; font-size: 32px; font-weight: 700; }
        .stat-icon-box { width: 50px; height: 50px; background-color: #d1e7dd; color: #0f8a42; border-radius: 50%; display: flex; justify-content: center; align-items: center; }

        /* NOTIFIKASI ICON */
        .notif-link { color: #0d7839; text-decoration: none; display: flex; align-items: center; justify-content: center; background: #f0f7f3; padding: 10px; border-radius: 50%; transition: 0.3s; }
        .notif-link:hover { background: #d1e7dd; }

        /* LIST SECTION */
        .list-section { display: flex; flex-direction: column; gap: 15px; flex-grow: 1; overflow: hidden; }
        .section-title { color: #6c757d; font-size: 14px; font-weight: bold; margin-bottom: 5px; }
        .list-scroll-area { width: 100%; overflow-y: auto; flex-grow: 1; }
        .bantuan-list-item { border: 1px solid #dee2e6; border-radius: 8px; background-color: #f8f9fa; padding: 15px; display: flex; align-items: center; gap: 20px; margin-bottom: 10px; }
        .item-img-box { width: 60px; height: 60px; background-color: white; border-radius: 8px; overflow: hidden; border: 1px solid #dee2e6; display: flex; justify-content: center; align-items: center; }
        .item-img-box img { max-width: 90%; max-height: 90%; object-fit: contain; }

        /* RESPONSIVE UNTUK HP */
        @media (max-width: 768px) {
            body { align-items: flex-start; padding: 0; }
            .dashboard-container { flex-direction: column; height: auto; min-height: 100vh; border-radius: 0; }
            .sidebar { width: 100%; padding: 20px 0; flex-direction: row; justify-content: space-around; }
            .sidebar-logo-area { display: none; }
            .sidebar-menu { flex-direction: row; width: 100%; }
            .menu-item { flex-direction: column; padding: 10px; font-size: 10px; text-align: center; }
            .main-content { padding: 20px; }
            .stats-grid { flex-direction: column; gap: 15px; }
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <aside class="sidebar">
        <div class="sidebar-logo-area">
            <div class="sidebar-logo-bg"><img src="logo.png" alt="Logo" class="sidebar-logo-img"></div>
            <h2 class="sidebar-title">SIP-BANTU TANI</h2>
            <p class="sidebar-subtitle">Portal Resmi Bantuan Pertanian</p>
        </div>
        <ul class="sidebar-menu">
            <a href="petani_dashboard.php" class="menu-item active"><span class="material-symbols-outlined menu-icon">home</span>Dashboard</a>
            <a href="petani_sip.php" class="menu-item"><span class="material-symbols-outlined menu-icon">format_list_bulleted</span>Pengajuan</a>
            <a href="petani_profile.php" class="menu-item"><span class="material-symbols-outlined menu-icon">account_circle</span>Profil</a>
        </ul>
    </aside>

    <main class="main-content">
        <div class="page-header">
            <div>
                <h1 class="page-title">Dashboard</h1>
                <p class="welcome-text">Halo, <strong><?php echo htmlspecialchars($nama_petani); ?></strong></p>
            </div>
            <!-- Tombol Notifikasi menuju petani_notifikasi.php -->
            <a href="petani_notifikasi.php" class="notif-link">
                <span class="material-symbols-outlined">notifications</span>
            </a>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info"><span class="stat-label">Total Pengajuan</span><span class="stat-value"><?php echo $data_total; ?></span></div>
                <div class="stat-icon-box"><span class="material-symbols-outlined">list_alt</span></div>
            </div>
            <div class="stat-card">
                <div class="stat-info"><span class="stat-label">Diterima</span><span class="stat-value"><?php echo $data_diterima; ?></span></div>
                <div class="stat-icon-box"><span class="material-symbols-outlined">check_circle</span></div>
            </div>
            <div class="stat-card">
                <div class="stat-info"><span class="stat-label">Ditolak</span><span class="stat-value"><?php echo $data_ditolak; ?></span></div>
                <div class="stat-icon-box"><span class="material-symbols-outlined">cancel</span></div>
            </div>
        </div>

        <div class="list-section">
            <h3 class="section-title">Program Bantuan Tersedia</h3>
            <div class="list-scroll-area">
                <?php while ($row = mysqli_fetch_assoc($query_bantuan)) : ?>
                <div class="bantuan-list-item">
                    <div class="item-img-box"><img src="images/<?= htmlspecialchars($row['gambar']); ?>" alt="Bantuan"></div>
                    <div>
                        <h6 style="font-size: 15px; font-weight: 700;"><?= htmlspecialchars($row['nama_bantuan']); ?></h6>
                        <p style="font-size: 13px; color: #6c757d;"><?= htmlspecialchars(substr($row['deskripsi'], 0, 100)); ?>...</p>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </main>
</div>

</body>
</html>