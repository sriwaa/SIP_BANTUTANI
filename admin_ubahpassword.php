<?php
// 1. KONEKSI DATABASE
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sip_bantutani";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Koneksi gagal: " . $conn->connect_error); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SIP-BANTU TANI - Ubah Password</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fbc02d; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        
        .dashboard-container { width: 100%; max-width: 1280px; height: 850px; max-height: 92vh; background-color: white; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); display: flex; overflow: hidden; }
        
        /* Sidebar Branding */
        .sidebar-brand-only { width: 280px; background-color: #0d7839; color: white; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 0; flex-shrink: 0; }
        .brand-logo-bg { width: 90px; height: 90px; background-color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; overflow: hidden; border: 2px solid #13a851; margin-bottom: 12px; }
        .brand-logo-img { width: 100%; height: 100%; object-fit: cover; }
        .brand-title { font-size: 16px; font-weight: bold; text-align: center; margin-bottom: 4px; letter-spacing: 0.5px; }
        .brand-subtitle { font-size: 11px; color: #d1e7dd; text-align: center; font-weight: 500; }

        /* Main Content */
        .main-content { flex-grow: 1; padding: 40px 50px; overflow-y: auto; display: flex; flex-direction: column; }
        .header-section { margin-bottom: 30px; border-bottom: 1px solid #dee2e6; padding-bottom: 15px; }
        .page-title { color: #0d7839; font-size: 28px; font-weight: 700; display: flex; align-items: center; gap: 15px; }
        
        .form-box-container { border: 1px solid #dee2e6; border-radius: 12px; padding: 35px; width: 100%; max-width: 600px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: 600; color: #1b1c1e; margin-bottom: 8px; font-size: 14px; }
        input { width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 8px; font-size: 14px; }
        
        .btn-row { display: flex; gap: 15px; margin-top: 30px; }
        .btn-action { flex: 1; padding: 14px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; text-align: center; text-decoration: none; }
        .btn-save { background-color: #0d7839; color: white; }
        .btn-save:hover { background-color: #0b6631; }
        .btn-cancel { background-color: #f8f9fa; color: #333; border: 1px solid #ced4da; }
    </style>
</head>
<body>

    <div class="dashboard-container">
        <div class="sidebar-brand-only">
            <div class="brand-logo-bg"><img src="logo.png" class="brand-logo-img"></div>
            <h2 class="brand-title">SIP-BANTU TANI</h2>
            <p class="brand-subtitle">Portal Resmi Bantuan Pertanian</p>
        </div>

        <main class="main-content">
            <div class="header-section">
                <h1 class="page-title">
                    <a href="admin_profil.php" style="color: #0d7839; text-decoration: none;"><span class="material-symbols-outlined">arrow_back</span></a>
                    Ubah Password
                </h1>
            </div>
            
            <div class="form-box-container">
                <form action="admin_ubahpassproses.php" method="POST">
                    <div class="form-group">
                        <label>Password Lama</label>
                        <input type="password" name="password_lama" required>
                    </div>
                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" name="password_baru" required>
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password Baru</label>
                        <input type="password" name="konfirmasi_password" required>
                    </div>
                    
                    <div class="btn-row">
                        <a href="admin_profil.php" class="btn-action btn-cancel">Batal</a>
                        <button type="submit" class="btn-action btn-save">Update Password</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

</body>
</html>
<?php $conn->close(); ?>