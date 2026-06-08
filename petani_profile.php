<?php
session_start();
include 'koneksi.php'; 

if (!isset($_SESSION['nik'])) {
    header("Location: petani_login.php");
    exit();
}

$nik_login = $_SESSION['nik'];
$query_user = "SELECT * FROM admin_datapetani WHERE nik = '$nik_login'";
$result_user = $conn->query($query_user);
$data = $result_user->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Petani - SIP-BANTU TANI</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; }
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
        .menu-item { display: flex; align-items: center; padding: 16px 28px; color: rgba(255, 255, 255, 0.8); font-size: 16px; font-weight: 600; transition: all 0.2s ease; gap: 15px; text-decoration: none; }
        .menu-item.active { background-color: rgba(255, 255, 255, 0.15); border-left: 5px solid #3bf789; color: white; }
        .menu-icon { font-size: 22px; }

        /* MAIN CONTENT */
        .main-content { flex-grow: 1; display: flex; flex-direction: column; overflow: hidden; }
        .page-header { padding: 40px 50px 20px 50px; background: white; z-index: 10; }
        .page-title { color: #0d7839; font-size: 28px; font-weight: 700; }
        
        .scrollable-body { flex-grow: 1; padding: 0 50px 40px 50px; overflow-y: auto; }
        
        .avatar-wrapper { display: flex; justify-content: center; margin-bottom: 20px; }
        .profile-avatar { width: 120px; height: 120px; background-color: #e8f5e9; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 50px; color: #0d7839; border: 3px solid #0d7839; }
        
        .profile-card { width: 100%; border: 1px solid #eee; padding: 30px; background: #fafafa; border-radius: 12px; margin-bottom: 20px; }
        .info-row { display: flex; margin-bottom: 12px; font-size: 15px; }
        .label { width: 150px; font-weight: bold; color: #555; }
        .value { flex: 1; color: #333; }
        
        .menu-list { width: 100%; }
        .menu-item-link { display: flex; justify-content: space-between; align-items: center; padding: 18px; border: 1px solid #ddd; margin-bottom: 12px; border-radius: 8px; text-decoration: none; color: #333; font-weight: 600; font-size: 15px; }
        .menu-item-link:hover { background-color: #f0f0f0; }
        .btn-logout { width: 100%; background: #d32f2f; color: white; padding: 15px; border-radius: 8px; border: none; cursor: pointer; font-weight: bold; font-size: 16px; margin-top: 10px; }

        /* MODAL */
        #logoutModal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center; }
        .notif-box { background: white; padding: 30px; border-radius: 20px; width: 350px; text-align: center; }
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
            <a href="petani_dashboard.php" class="menu-item"><span class="material-symbols-outlined menu-icon">home</span>Dashboard</a>
            <a href="petani_sip.php" class="menu-item"><span class="material-symbols-outlined menu-icon">format_list_bulleted</span>Pengajuan</a>
            <a href="petani_profile.php" class="menu-item active"><span class="material-symbols-outlined menu-icon">account_circle</span>Profil</a>
        </ul>
    </aside>

    <main class="main-content">
        <div class="page-header">
            <h1 class="page-title">Profil Petani</h1>
        </div>
        
        <div class="scrollable-body">
            <div class="avatar-wrapper">
                <div class="profile-avatar"><i class="fa-solid fa-user"></i></div>
            </div>
            
            <div class="profile-card">
                <h4 style="margin-bottom: 20px; font-size: 18px;">Informasi Lengkap</h4>
                <div class="info-row"><div class="label">Nama Lengkap</div><div class="value">: <?php echo htmlspecialchars($data['nama_lengkap']); ?></div></div>
                <div class="info-row"><div class="label">NIK</div><div class="value">: <?php echo htmlspecialchars($data['nik']); ?></div></div>
                <div class="info-row"><div class="label">No. Telepon</div><div class="value">: <?php echo htmlspecialchars($data['no_telp']); ?></div></div>
                <div class="info-row"><div class="label">Komoditas</div><div class="value">: <?php echo htmlspecialchars($data['komoditas']); ?></div></div>
                <div class="info-row"><div class="label">Alamat</div><div class="value">: <?php echo htmlspecialchars($data['alamat']); ?></div></div>
                <div class="info-row"><div class="label">Tempat Lahir</div><div class="value">: <?php echo htmlspecialchars($data['tempat_lahir']); ?></div></div>
                <div class="info-row"><div class="label">Tgl. Lahir</div><div class="value">: <?php echo htmlspecialchars($data['tanggal_lahir']); ?></div></div>
            </div>

            <div class="menu-list">
                <a href="petani_ubahpassword.php" class="menu-item-link"><span><i class="fa-solid fa-lock me-2"></i> Ubah Password</span><i class="fa-solid fa-chevron-right"></i></a>
                <a href="petani_riwayat.php" class="menu-item-link"><span><i class="fa-solid fa-history me-2"></i> Riwayat Pengajuan</span><i class="fa-solid fa-chevron-right"></i></a>
                <a href="petani_editprofile.php" class="menu-item-link"><span><i class="fa-solid fa-user-pen me-2"></i> Edit Profile</span><i class="fa-solid fa-chevron-right"></i></a>
            </div>

            <button type="button" onclick="document.getElementById('logoutModal').style.display='flex'" class="btn-logout">Keluar</button>
        </div>
    </main>
</div>

<div id="logoutModal">
    <div class="notif-box">
        <h3 style="margin-bottom: 20px;">Anda Yakin Ingin Keluar?</h3>
        <div style="display: flex; gap: 10px;">
            <button onclick="document.getElementById('logoutModal').style.display='none'" style="padding: 10px; width: 50%; border: 1px solid #ccc; background: white; border-radius: 8px; cursor: pointer;">Batal</button>
            <a href="petani_logout.php" style="padding: 10px; width: 50%; background: #d32f2f; color: white; border-radius: 8px; text-decoration: none; text-align: center;">Ya, Keluar</a>
        </div>
    </div>
</div>

</body>
</html>