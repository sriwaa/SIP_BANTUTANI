<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['nik'])) {
    header("Location: petani_login.php");
    exit();
}

$nik_login = $_SESSION['nik'];

$query = "SELECT r.*, p.nama_program, p.gambar 
          FROM admin_pengajuanbantuan r 
          JOIN admin_programbantuan p ON r.id_program = p.id_program 
          WHERE r.nik = '$nik_login' 
          ORDER BY r.id_pengajuan DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pengajuan - SIP-BANTU TANI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fbc02d; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .dashboard-container { width: 100%; max-width: 1280px; height: 850px; max-height: 92vh; background-color: white; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); display: flex; overflow: hidden; }

        .sidebar-brand-only { width: 280px; background-color: #0d7839; color: white; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 0; flex-shrink: 0; }
        .sidebar-logo-area { display: flex; flex-direction: column; align-items: center; text-align: center; padding: 0 20px; }
        .sidebar-logo-bg { width: 110px; height: 110px; background-color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; overflow: hidden; border: 2px solid #13a851; margin-bottom: 20px; }
        .sidebar-logo-img { width: 100%; height: 100%; object-fit: cover; }
        .sidebar-title { font-size: 20px; font-weight: bold; letter-spacing: 0.5px; margin-bottom: 6px; }
        .sidebar-subtitle { font-size: 12px; color: #d1e7dd; font-weight: 500; }

        .main-content { flex-grow: 1; display: flex; flex-direction: column; overflow: hidden; }
        .header-section { padding: 40px 50px 20px 50px; background: white; }
        .back-nav { display: flex; align-items: center; gap: 12px; color: #0d7839; text-decoration: none; font-size: 28px; font-weight: 700; margin-bottom: 5px; }
        .scrollable-content { flex-grow: 1; padding: 0 50px 40px 50px; overflow-y: auto; }

        .history-card { border: 1px solid #dee2e6; border-radius: 12px; padding: 20px; margin-bottom: 15px; display: flex; align-items: center; transition: 0.3s; cursor: pointer; color: inherit; text-decoration: none; }
        .history-card:hover { border-color: #0d7839; background-color: #f9f9f9; }
        .history-img { width: 70px; height: 70px; object-fit: cover; border-radius: 8px; margin-right: 20px; border: 1px solid #eee; }
        .history-info { flex: 1; }
        .history-info h4 { margin: 0; font-size: 16px; color: #333; font-weight: 700; }
        .history-info p { margin: 2px 0; font-size: 13px; color: #666; }
        
        .status-badge { padding: 5px 12px; border-radius: 8px; font-size: 12px; font-weight: bold; margin-bottom: 5px; display: inline-block; }
        
        /* Warna Status Dinamis */
        .status-diproses { background: #fff3e0; color: #ef6c00; } /* Oranye */
        .status-survey { background: #f3e5f5; color: #7b1fa2; }  /* Ungu */
        .status-diterima { background: #e3f2fd; color: #1976d2; } /* Biru */
        .status-selesai { background: #e8f5e9; color: #2e7d32; }  /* Hijau */
        .status-ditolak { background: #ffebee; color: #c62828; }  /* Merah */
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
            <a href="petani_profile.php" class="back-nav"><span class="material-symbols-outlined" style="font-size: 32px;">arrow_back</span> Riwayat Pengajuan</a>
        </div>

        <div class="scrollable-content">
            <?php 
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $status = $row['status_pengajuan'];
                    $status_class = "status-diproses";
                    
                    if ($status == "Tahap Survey") $status_class = "status-survey";
                    elseif ($status == "Diterima") $status_class = "status-diterima";
                    elseif ($status == "Selesai") $status_class = "status-selesai";
                    elseif ($status == "Ditolak") $status_class = "status-ditolak";
                    
                    echo '<a href="petani_dtlpengajuan.php?id='.$row['id_pengajuan'].'" class="history-card">
                            <img src="images/'.$row['gambar'].'" class="history-img" alt="Bantuan">
                            <div class="history-info">
                                <h4>'.$row['nama_program'].'</h4>
                                <p>Tanggal Pengajuan: '.$row['tanggal_pengajuan'].'</p>
                            </div>
                            <div style="text-align: right;">
                                <div class="status-badge '.$status_class.'">'.$status.'</div>
                                <div style="font-size: 11px; color: #0d7839; font-weight: 600;">Lihat Detail ></div>
                            </div>
                          </a>';
                }
            } else {
                echo "<p style='text-align:center; color:#999; margin-top: 50px;'>Belum ada riwayat pengajuan yang ditemukan.</p>";
            }
            ?>
        </div>
    </main>
</div>

</body>
</html>