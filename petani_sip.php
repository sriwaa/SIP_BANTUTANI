<?php
session_start();
include 'koneksi.php'; 

if (!isset($_SESSION['nik'])) {
    header("Location: petani_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP - SIP-BANTU TANI</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fbc02d; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .dashboard-container { width: 100%; max-width: 1280px; height: 850px; max-height: 92vh; background-color: white; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); display: flex; overflow: hidden; }

        /* SIDEBAR (Sama dengan Dashboard) */
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
        .page-header { margin-bottom: 25px; }
        .page-title { color: #0d7839; font-size: 28px; font-weight: 700; margin-bottom: 8px; }
        .page-desc { color: #6c757d; font-size: 14px; margin-bottom: 25px; }
        
        /* LIST CONTAINER */
        .list-container { flex-grow: 1; overflow-y: auto; padding-right: 10px; }
        .card-bantuan { border: 1px solid #dee2e6; border-radius: 8px; background-color: #f8f9fa; padding: 20px; display: flex; align-items: center; gap: 20px; margin-bottom: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); }
        .btn-detail { background-color: #0d7839; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; }
        .btn-detail:hover { background-color: #0b6631; color: white; }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            body { align-items: flex-start; padding: 0; }
            .dashboard-container { flex-direction: column; height: auto; min-height: 100vh; border-radius: 0; }
            .sidebar { width: 100%; padding: 20px 0; flex-direction: row; justify-content: space-around; }
            .sidebar-logo-area { display: none; }
            .sidebar-menu { flex-direction: row; width: 100%; }
            .menu-item { flex-direction: column; padding: 10px; font-size: 10px; text-align: center; }
            .main-content { padding: 20px; }
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
            <a href="petani_dashboard.php" class="menu-item"><span class="material-symbols-outlined menu-icon">home</span>Dashboard</a>
            <a href="petani_sip.php" class="menu-item active"><span class="material-symbols-outlined menu-icon">format_list_bulleted</span>Pengajuan</a>
            <a href="petani_profile.php" class="menu-item"><span class="material-symbols-outlined menu-icon">account_circle</span>Profil</a>
        </ul>
    </aside>

    <main class="main-content">
        <div class="page-header">
            <h1 class="page-title">Bantuan Tersedia</h1>
            <p class="page-desc">Silakan pilih program bantuan di bawah ini yang sesuai dengan kebutuhan pertanian Anda untuk mengajukan permohonan.</p>
        </div>

        <div class="list-container">
            <?php
            $query = "SELECT * FROM admin_programbantuan";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="card-bantuan">
                            <img src="images/'.$row['gambar'].'" style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px; border: 1px solid #eee;" alt="Bantuan">
                            <div style="flex-grow: 1;">
                                <h6 style="font-size: 15px; font-weight: 700; margin-bottom: 5px;">'.$row['nama_program'].'</h6>
                                <p style="font-size: 13px; color: #6c757d; margin: 0;">'.substr($row['deskripsi'], 0, 80).'...</p>
                            </div>
                            <a href="petani_detail_bantuan.php?id='.$row['id_program'].'" class="btn-detail">Ajukan</a>
                          </div>';
                }
            } else {
                echo "<p class='text-center text-muted'>Belum ada bantuan tersedia.</p>";
            }
            ?>
        </div>
    </main>
</div>

</body>
</html>