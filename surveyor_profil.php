<?php
// 1. CEK LOGIN
session_start();
if (!isset($_SESSION['surveyor_id']) || $_SESSION['role'] !== 'surveyor') {
    header("Location: surveyor_login.php");
    exit();
}

// 2. KONEKSI DATABASE
$host = "localhost"; $user = "root"; $pass = ""; $db = "sip_bantutani";
$koneksi = mysqli_connect($host, $user, $pass, $db);

// 3. AMBIL DATA SURVEYOR
$id_surveyor = $_SESSION['surveyor_id'];
$query = mysqli_query($koneksi, "SELECT * FROM surveyor WHERE id_surveyor = '$id_surveyor'");
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SIP-BANTU TANI - Profil Surveyor</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fbc02d; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .dashboard-container { width: 100%; max-width: 1280px; height: 850px; max-height: 92vh; background-color: white; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); display: flex; overflow: hidden; }
        
        /* Sidebar */
        .sidebar { width: 280px; background-color: #0d7839; color: white; display: flex; flex-direction: column; padding: 40px 0; flex-shrink: 0; }
        .sidebar-logo-area { display: flex; flex-direction: column; align-items: center; text-align: center; padding: 0 20px; margin-bottom: 40px; }
        .sidebar-logo-bg { width: 90px; height: 90px; background-color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; overflow: hidden; border: 2px solid #13a851; margin-bottom: 12px; }
        .sidebar-logo-img { width: 100%; height: 100%; object-fit: cover; }
        .sidebar-title { font-size: 16px; font-weight: bold; margin-bottom: 4px; }
        .sidebar-subtitle { font-size: 11px; color: #d1e7dd; font-weight: 500; }
        .menu-item { display: flex; align-items: center; padding: 16px 28px; color: rgba(255, 255, 255, 0.8); font-size: 15px; font-weight: 600; gap: 15px; text-decoration: none; transition: 0.2s; }
        .menu-item.active { background-color: rgba(255, 255, 255, 0.15); border-left: 5px solid #3bf789; color: white; }
        .menu-item:hover { background-color: rgba(255, 255, 255, 0.1); color: white; }

        /* Main Content */
        .main-content { flex-grow: 1; background-color: #f8f9fa; padding: 40px; overflow-y: auto; display: flex; flex-direction: column; align-items: center; }
        .page-header { width: 100%; margin-bottom: 30px; border-bottom: 1px solid #dee2e6; padding-bottom: 15px; }
        .page-title { color: #0d7839; font-size: 32px; font-weight: 700; }
        
        .profile-card { background: white; padding: 30px; border-radius: 12px; border: 1px solid #dee2e6; width: 100%; max-width: 600px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; }
        .profile-icon { font-size: 80px; color: #0d7839; margin-bottom: 10px; }
        .user-name { font-size: 24px; font-weight: bold; color: #212529; }
        .user-role { color: #6c757d; font-weight: 600; margin-bottom: 25px; }
        
        .info-grid { width: 100%; text-align: left; margin-bottom: 20px; }
        .info-row { display: flex; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #eee; }
        .label { font-weight: bold; color: #555; }
        .value { font-weight: 600; color: #000; }
        
        .nav-btn { display: flex; justify-content: space-between; align-items: center; width: 100%; padding: 15px; border: 1px solid #dee2e6; border-radius: 8px; margin-top: 10px; text-decoration: none; color: #333; font-weight: 600; }
        .nav-btn:hover { background-color: #f8f9fa; }
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
            <ul style="list-style: none;">
                <a href="surveyor_dashboard.php" class="menu-item"><span class="material-symbols-outlined">home</span>Dashboard</a>
                <a href="surveyor_tugaslapangan.php" class="menu-item"><span class="material-symbols-outlined">assignment</span>Tugas Lapangan</a>
                <a href="surveyor_riwayatsurvey.php" class="menu-item"><span class="material-symbols-outlined">history</span>Riwayat Survey</a>
                <a href="surveyor_profil.php" class="menu-item active"><span class="material-symbols-outlined">person</span>Profil</a>
                <a href="surveyor_logout.php" class="menu-item" onclick="return confirm('Apakah kamu yakin ingin keluar dari sistem?');">
                    <span class="material-symbols-outlined">logout</span>Keluar
                </a>
            </ul>
        </aside>

        <main class="main-content">
            <div class="page-header"><h1 class="page-title">Profil Saya</h1></div>
            
            <div class="profile-card">
                <span class="material-symbols-outlined profile-icon">account_circle</span>
                <!-- Mengambil data dari kolom 'nama_lengkap' -->
                <div class="user-name"><?= htmlspecialchars($data['nama_lengkap'] ?? 'Nama Tidak Ada') ?></div>
                <div class="user-role">Surveyor</div>

                <div class="info-grid">
                    <div class="info-row"><span class="label">Username</span><span class="value"><?= htmlspecialchars($data['username'] ?? '-') ?></span></div>
                    <div class="info-row"><span class="label">Email</span><span class="value"><?= htmlspecialchars($data['email'] ?? '-') ?></span></div>
                    <div class="info-row"><span class="label">ID Surveyor</span><span class="value"><?= htmlspecialchars($data['id_surveyor'] ?? '-') ?></span></div>
                </div>

                <a href="surveyor_editprofil.php" class="nav-btn">Edit Profil <span class="material-symbols-outlined">chevron_right</span></a>
                <a href="surveyor_ubahpassword.php" class="nav-btn">Ubah Password <span class="material-symbols-outlined">chevron_right</span></a>
            </div>
        </main>
    </div>
</body>
</html>