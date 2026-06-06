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

// Query diupdate untuk mengambil nik dan id_program agar bisa digunakan di tombol detail
$query_riwayat = mysqli_query($koneksi, "
    SELECT p.nik, p.nama_lengkap, b.nama_program, b.id_program, a.status_pengajuan, s.tanggal_survey
    FROM admin_survey s
    JOIN admin_pengajuanbantuan a ON s.id_pengajuan = a.id_pengajuan
    JOIN admin_datapetani p ON a.nik = p.nik
    JOIN admin_programbantuan b ON a.id_program = b.id_program
    WHERE a.status_pengajuan = 'Selesai' 
    AND s.tanggal_survey IS NOT NULL
    ORDER BY s.tanggal_survey DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SIP-BANTU TANI - Riwayat Survey</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fbc02d; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .dashboard-container { width: 100%; max-width: 1280px; height: 850px; max-height: 92vh; background-color: white; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); display: flex; overflow: hidden; }
        
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

        .main-content { flex-grow: 1; background-color: #f8f9fa; padding: 40px 50px; overflow-y: auto; }
        .page-header { margin-bottom: 30px; border-bottom: 1px solid #dee2e6; padding-bottom: 15px; }
        .page-title { color: #0d7839; font-size: 32px; font-weight: 700; margin-bottom: 8px; }
        
        .data-table-container { width: 100%; background: white; border: 1px solid #dee2e6; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .data-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; }
        .data-table th { background-color: #f8f9fa; color: #495057; padding: 16px 20px; font-weight: bold; border-bottom: 2px solid #dee2e6; }
        .data-table td { padding: 16px 20px; color: #212529; border-bottom: 1px solid #dee2e6; }
        .badge { background-color: #d4edda; color: #155724; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
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
                <a href="surveyor_dashboard.php" class="menu-item"><span class="material-symbols-outlined">home</span>Dashboard</a>
                <a href="surveyor_tugaslapangan.php" class="menu-item"><span class="material-symbols-outlined">assignment</span>Tugas Lapangan</a>
                <a href="surveyor_riwayatsurvey.php" class="menu-item active"><span class="material-symbols-outlined">history</span>Riwayat Survey</a>
                <a href="surveyor_profil.php" class="menu-item"><span class="material-symbols-outlined">person</span>Profil</a>
                <a href="surveyor_logout.php" class="menu-item" onclick="return confirm('Apakah kamu yakin ingin keluar dari sistem?');">
                    <span class="material-symbols-outlined">logout</span>Keluar
                </a>
            </ul>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1 class="page-title">Riwayat Survey</h1>
                <p style="color: #6c757d;">Daftar survey yang telah diselesaikan.</p>
            </div>
            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Petani</th>
                            <th>Program</th>
                            <th>Tgl Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if (mysqli_num_rows($query_riwayat) > 0) {
                            while ($row = mysqli_fetch_assoc($query_riwayat)) { ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= htmlspecialchars($row['nama_lengkap']) ?></strong></td>
                                    <td><?= htmlspecialchars($row['nama_program']) ?></td>
                                    <td><?= date('d M Y', strtotime($row['tanggal_survey'])) ?></td>
                                    <td><span class="badge"><?= $row['status_pengajuan'] ?></span></td>
                                    <td>
                                        <a href="surveyor_detailriwayat.php?nik=<?= $row['nik'] ?>&id_program=<?= $row['id_program'] ?>" 
                                           style="background-color: #0d7839; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: bold;">
                                           Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php } 
                        } else { ?>
                            <tr><td colspan="6" style="text-align: center; padding: 40px; color: #6c757d;">Belum ada riwayat survey.</td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>