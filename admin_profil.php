<?php
// 1. KONEKSI DATABASE
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sip_bantutani";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Koneksi gagal: " . $conn->connect_error); }

// Mengambil data admin
$id_admin = 1; 
$result = $conn->query("SELECT * FROM admin_users WHERE id_user = $id_admin");
$admin = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SIP-BANTU TANI - Profil Admin</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fbc02d; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .dashboard-container { width: 100%; max-width: 1280px; height: 850px; max-height: 92vh; background-color: white; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); display: flex; overflow: hidden; }
        
        .sidebar { width: 280px; background-color: #0d7839; color: white; display: flex; flex-direction: column; align-items: center; padding: 40px 0; flex-shrink: 0; }
        .sidebar-logo-area { display: flex; flex-direction: column; align-items: center; text-align: center; padding: 0 20px; margin-bottom: 40px; }
        .sidebar-logo-bg { width: 90px; height: 90px; background-color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; overflow: hidden; border: 2px solid #13a851; margin-bottom: 12px; }
        .sidebar-logo-img { width: 100%; height: 100%; object-fit: cover; }
        .sidebar-title { font-size: 16px; font-weight: bold; letter-spacing: 0.5px; margin-bottom: 4px; }
        .sidebar-subtitle { font-size: 11px; color: #d1e7dd; font-weight: 500; }
        .sidebar-menu { width: 100%; list-style: none; display: flex; flex-direction: column; }
        .menu-item { display: flex; align-items: center; padding: 16px 28px; color: rgba(255, 255, 255, 0.8); font-size: 16px; font-weight: 600; text-decoration: none; gap: 15px; transition: all 0.2s; }
        .menu-item.active { background-color: rgba(255, 255, 255, 0.15); border-left: 5px solid #3bf789; color: white; }
        .menu-item:hover { background-color: rgba(255, 255, 255, 0.1); color: white; padding-left: 33px; }
        .menu-icon { font-size: 22px; }

        .main-content { flex-grow: 1; padding: 40px 50px; overflow-y: auto; display: flex; flex-direction: column; align-items: center; }
        .header-row { width: 100%; margin-bottom: 30px; border-bottom: 1px solid #dee2e6; padding-bottom: 15px; }
        .page-title { color: #0d7839; font-size: 32px; font-weight: 700; }
        
        .profile-card { width: 100%; max-width: 600px; text-align: center; }
        .profile-icon { font-size: 100px; color: #0d7839; margin-bottom: 10px; }
        .data-box { width: 100%; border: 1px solid #dee2e6; border-radius: 12px; padding: 20px; margin-bottom: 20px; text-align: left; background: #fff; }
        .data-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #eee; }
        .action-link { display: flex; justify-content: space-between; align-items: center; padding: 15px; border: 1px solid #dee2e6; border-radius: 8px; margin-bottom: 10px; text-decoration: none; color: #333; font-weight: 500; }
        .btn-logout { display: flex; justify-content: center; align-items: center; gap: 8px; width: 100%; padding: 15px; background: #ffcdd2; color: #d32f2f; border-radius: 8px; text-decoration: none; font-weight: bold; margin-top: 20px; cursor: pointer; }

        /* Modal */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center; z-index: 1000; }
        .modal-box { background: white; padding: 30px; border-radius: 12px; width: 400px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.3); }
        .btn-confirm { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; margin: 5px; text-decoration: none; display: inline-block; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-logo-area">
                <div class="sidebar-logo-bg"><img src="logo.png" class="sidebar-logo-img"></div>
                <h2 class="sidebar-title">SIP-BANTU TANI</h2>
                <p class="sidebar-subtitle">Portal Resmi Bantuan Pertanian</p>
            </div>
            <ul class="sidebar-menu">
                <a href="admin_dashboard.php" class="menu-item"><span class="material-symbols-outlined menu-icon">home</span>Dashboard</a>
                <a href="admin_data_petani.php" class="menu-item"><span class="material-symbols-outlined menu-icon">person</span>Data Petani</a>
                <a href="admin_pengajuan.php" class="menu-item"><span class="material-symbols-outlined menu-icon">format_list_bulleted</span>Pengajuan</a>
                <a href="admin_bantuan.php" class="menu-item"><span class="material-symbols-outlined menu-icon">category</span>Bantuan</a>
                <a href="admin_laporan.php" class="menu-item"><span class="material-symbols-outlined menu-icon">description</span>Laporan</a>
                <a href="admin_profil.php" class="menu-item active"><span class="material-symbols-outlined menu-icon">account_circle</span>Profil</a>
            </ul>
        </aside>

        <main class="main-content">
            <div class="header-row"><h1 class="page-title">Profil Admin</h1></div>
            
            <div class="profile-card">
                <span class="material-symbols-outlined profile-icon">account_circle</span>
                <h2>M. Khamdan Azkiya</h2>
                <p style="color: #0d7839; font-weight: bold; margin-bottom: 20px;">Admin</p>

                <div class="data-box">
                    <div class="data-row"><span>Nama Lengkap</span> <strong>M. Khamdan Azkiya</strong></div>
                    <div class="data-row"><span>Email</span> <strong>khamdan123@gmail.com</strong></div>
                    <div class="data-row"><span>No. HP</span> <strong>0821345678910</strong></div>
                    <div class="data-row" style="border:none;"><span>Peran</span> <strong><?= $admin['role'] ?></strong></div>
                </div>

                <a href="admin_profiledit.php" class="action-link">Edit Profil <span>></span></a>
                <a href="admin_ubahpassword.php" class="action-link">Ubah Password <span>></span></a>
                
                <a href="#" onclick="document.getElementById('logoutModal').style.display='flex'" class="btn-logout">
                    <span class="material-symbols-outlined">logout</span>Keluar
                </a>
            </div>
        </main>
    </div>

    <div id="logoutModal" class="modal-overlay">
        <div class="modal-box">
            <span class="material-symbols-outlined" style="font-size: 50px; color: #d32f2f;">logout</span>
            <h3>Anda yakin ingin keluar?</h3>
            <p style="margin: 15px 0;">Sesi Anda akan berakhir jika Anda melanjutkan.</p>
            <button onclick="document.getElementById('logoutModal').style.display='none'" class="btn-confirm" style="background:#e0e0e0;">Batal</button>
            <a href="./admin_logout.php" class="btn-confirm" style="background:#d32f2f; color:white;">Ya, Keluar</a>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>