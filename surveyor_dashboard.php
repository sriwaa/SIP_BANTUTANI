<?php
session_start();

if (!isset($_SESSION['surveyor_id']) || $_SESSION['role'] !== 'surveyor') {
    header("Location: surveyor_login.php");
    exit();
}

$host     = "localhost";
$db_user  = "root";
$db_pass  = "";
$db_name  = "sip_bantutani";

$koneksi = mysqli_connect($host, $db_user, $db_pass, $db_name);
$query_tugas = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM admin_pengajuanbantuan WHERE status_pengajuan = 'Tahap Survey'");
$data_tugas  = mysqli_fetch_assoc($query_tugas);
$total_tugas = $data_tugas['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP-BANTU TANI - Dashboard Surveyor</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fbc02d; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .dashboard-container { width: 100%; max-width: 1280px; height: 850px; max-height: 92vh; background-color: white; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); display: flex; overflow: hidden; }
        
        /* SIDEBAR */
        .sidebar { width: 280px; background-color: #0d7839; color: white; display: flex; flex-direction: column; padding: 40px 0; flex-shrink: 0; }
        .sidebar-logo-area { display: flex; flex-direction: column; align-items: center; text-align: center; padding: 0 20px; margin-bottom: 40px; }
        .sidebar-logo-bg { width: 90px; height: 90px; background-color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; overflow: hidden; border: 2px solid #13a851; margin-bottom: 12px; }
        .sidebar-logo-img { width: 100%; height: 100%; object-fit: cover; }
        .sidebar-title { font-size: 16px; font-weight: bold; margin-bottom: 4px; }
        .sidebar-subtitle { font-size: 11px; color: #d1e7dd; font-weight: 500; }
        
        .sidebar-menu { width: 100%; list-style: none; }
        .menu-item { display: flex; align-items: center; padding: 16px 28px; color: rgba(255, 255, 255, 0.8); font-size: 15px; font-weight: 600; gap: 15px; text-decoration: none; cursor: pointer; transition: 0.2s; }
        .menu-item.active { background-color: rgba(255, 255, 255, 0.15); border-left: 5px solid #3bf789; color: white; }
        .menu-item:hover { background-color: rgba(255, 255, 255, 0.1); color: white; }

        /* MAIN CONTENT */
        .main-content { flex-grow: 1; background-color: #f8f9fa; padding: 40px 50px; overflow-y: auto; }
        .page-header { margin-bottom: 40px; border-bottom: 1px solid #dee2e6; padding-bottom: 20px; }
        .page-title { color: #0d7839; font-size: 32px; font-weight: 700; margin-bottom: 8px; }
        .welcome-text { color: #495057; font-size: 16px; }
        
        .stats-grid { display: flex; gap: 20px; margin-bottom: 40px; }
        .stat-card { flex: 1; background: white; border-radius: 12px; padding: 25px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-left: 5px solid #0d7839; }
        .stat-value { color: #212529; font-size: 40px; font-weight: 800; }
        
        .quick-access { display: flex; gap: 20px; }
        .quick-btn { text-decoration: none; padding: 25px; background: white; border: 1px solid #dee2e6; border-radius: 12px; color: #333; width: 220px; text-align: center; }
        .quick-btn:hover { border-color: #0d7839; background: #f1fcf4; }
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
                <a href="surveyor_dashboard.php" class="menu-item active">
                    <span class="material-symbols-outlined">home</span>Dashboard
                </a>
                <a href="surveyor_tugaslapangan.php" class="menu-item">
                    <span class="material-symbols-outlined">assignment</span>Tugas Lapangan
                </a>
                <a href="surveyor_riwayatsurvey.php" class="menu-item">
                    <span class="material-symbols-outlined">history</span>Riwayat Survey
                </a>
                <a href="surveyor_profil.php" class="menu-item">
                    <span class="material-symbols-outlined">person</span>Profil
                </a>
                <a href="surveyor_logout.php" class="menu-item" onclick="return confirm('Apakah kamu yakin ingin keluar dari sistem?');">
                    <span class="material-symbols-outlined">logout</span>Keluar
                </a>
            </ul>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1 class="page-title">Dashboard Surveyor</h1>
                <p class="welcome-text">Halo, <strong><?php echo htmlspecialchars($_SESSION['nama']); ?></strong>. Tetap semangat melakukan verifikasi!</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div>
                        <span style="color: #6c757d; font-size: 14px;">Tugas Tersedia</span>
                        <span class="stat-value"><?php echo $total_tugas; ?></span>
                    </div>
                    <span class="material-symbols-outlined" style="font-size: 50px; color: #0d7839;">assignment</span>
                </div>
            </div>

            <h3 style="color: #495057; margin-bottom: 20px;">Akses Cepat</h3>
            <div class="quick-access">
                <a href="surveyor_tugaslapangan.php" class="quick-btn">
                    <span class="material-symbols-outlined" style="font-size: 40px; color: #0d7839; display: block;">map</span>
                    Mulai Survei
                </a>
                <a href="surveyor_riwayatsurvei.php" class="quick-btn">
                    <span class="material-symbols-outlined" style="font-size: 40px; color: #0d7839; display: block;">history</span>
                    Lihat Riwayat
                </a>
            </div>
        </main>
    </div>
</body>
</html>