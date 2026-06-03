<?php
// 1. CEK APAKAH SURVEYOR SUDAH LOGIN
session_start();

if (!isset($_SESSION['surveyor_id']) || $_SESSION['role'] !== 'surveyor') {
    header("Location: surveyor_login.php");
    exit();
}

// 2. KONEKSI DATABASE
$host     = "localhost";
$db_user  = "root";
$db_pass  = "";
$db_name  = "sip_bantutani";

$koneksi = mysqli_connect($host, $db_user, $db_pass, $db_name);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
// 3. AMBIL DATA DENGAN JOIN AGAR BISA MENGAMBIL NAMA DAN ALAMAT PETANI
$query_petani = mysqli_query($koneksi, "
    SELECT p.nik, p.nama_lengkap, p.alamat 
    FROM admin_pengajuanbantuan a
    JOIN admin_datapetani p ON a.nik = p.nik
    WHERE a.status_pengajuan = 'Tahap Survey'
    LIMIT 3
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP-BANTU TANI - Tugas Lapangan</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fbc02d; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .dashboard-container { width: 100%; max-width: 1280px; height: 850px; max-height: 92vh; background-color: white; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); display: flex; overflow: hidden; }
        
        /* SIDEBAR (KONSISTEN) */
        .sidebar { width: 280px; background-color: #0d7839; color: white; display: flex; flex-direction: column; align-items: center; padding: 40px 0; flex-shrink: 0; }
        .sidebar-logo-area { display: flex; flex-direction: column; align-items: center; text-align: center; padding: 0 20px; margin-bottom: 40px; }
        .sidebar-logo-bg { width: 90px; height: 90px; background-color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; overflow: hidden; border: 2px solid #13a851; margin-bottom: 12px; }
        .sidebar-logo-img { width: 100%; height: 100%; object-fit: cover; }
        .sidebar-title { font-size: 16px; font-weight: bold; margin-bottom: 4px; }
        .sidebar-subtitle { font-size: 11px; color: #d1e7dd; font-weight: 500; }
        .sidebar-menu { width: 100%; list-style: none; display: flex; flex-direction: column; }
        .menu-item { display: flex; align-items: center; padding: 16px 28px; color: rgba(255, 255, 255, 0.8); font-size: 16px; font-weight: 600; gap: 15px; text-decoration: none; cursor: pointer; }
        .menu-item.active { background-color: rgba(255, 255, 255, 0.15); border-left: 5px solid #3bf789; color: white; }
        .menu-item:hover { background-color: rgba(255, 255, 255, 0.1); color: white; }
        .menu-icon { font-size: 22px; }

        /* MAIN CONTENT */
        .main-content { flex-grow: 1; background-color: white; padding: 40px 50px; overflow-y: auto; }
        .page-header { margin-bottom: 30px; border-bottom: 1px solid #dee2e6; padding-bottom: 15px; }
        .page-title { color: #0d7839; font-size: 28px; font-weight: 700; margin-bottom: 8px; }
        
        /* TABEL */
        .data-table-container { width: 100%; border: 1px solid #dee2e6; border-radius: 8px; overflow: hidden; }
        .data-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; }
        .data-table th { background-color: #e9ecef; color: #495057; padding: 14px 16px; font-weight: bold; }
        .data-table td { padding: 14px 16px; color: #212529; border-bottom: 1px solid #dee2e6; }
        .btn-aksi { background-color: #13a851; color: white; padding: 8px 12px; border-radius: 5px; text-decoration: none; font-size: 12px; font-weight: bold; }
        .btn-aksi:hover { background-color: #0f8a42; }
    </style>
</head>
<body>

    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-logo-area">
                <div class="sidebar-logo-bg"><img src="logo.png" alt="Logo" class="sidebar-logo-img"></div>
                <h2 class="sidebar-title">SIP-BANTU TANI</h2>
                <p class="sidebar-subtitle">Portal Surveyor</p>
            </div>

            <ul class="sidebar-menu">
                <a href="surveyor_dashboard.php" class="menu-item">
                    <span class="material-symbols-outlined menu-icon">home</span>Dashboard
                </a>
                <a href="surveyor_tugaslapangan.php" class="menu-item active">
                    <span class="material-symbols-outlined menu-icon">assignment</span>Tugas Lapangan
                </a>
                <a href="#" class="menu-item">
                    <span class="material-symbols-outlined menu-icon">history</span>Riwayat Survei
                </a>
                <a href="logout.php" class="menu-item">
                    <span class="material-symbols-outlined menu-icon">logout</span>Keluar
                </a>
            </ul>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1 class="page-title">Tugas Lapangan</h1>
                <p>Berikut adalah <strong>3 prioritas utama</strong> data petani yang menunggu survei:</p>
            </div>

            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama Petani</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if (mysqli_num_rows($query_petani) > 0) {
                            while ($row = mysqli_fetch_assoc($query_petani)) { ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['nik']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                                    <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                                    <td><a href="#" class="btn-aksi">Survei</a></td>
                                </tr>
                            <?php } 
                        } else { ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 20px; color: #6c757d;">Tidak ada tugas survei saat ini.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>