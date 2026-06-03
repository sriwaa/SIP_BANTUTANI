<?php
// 1. CEK APAKAH ADMIN SUDAH LOGIN ATAU BELUM
session_start();

// Jika tidak ada session username atau rolenya bukan Admin, tendang kembali ke login
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Admin') {
    header("Location: admin_login.php");
    exit();
}

// 2. KONEKSI DATABASE
$host     = "localhost";
$db_user  = "root";
$db_pass  = "";
$db_name  = "sip_bantutani";

$koneksi = mysqli_connect($host, $db_user, $db_pass, $db_name);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// 3. AMBIL TOTAL PETANI SECARA DINAMIS DARI DATABASE
$query_petani = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM data_petani");
$data_petani  = mysqli_fetch_assoc($query_petani);
$total_petani = $data_petani['total'];

// 4. AMBIL TOTAL PENGAJUAN SECARA DINAMIS DARI TABEL PENGAJUAN BANTUAN
$query_pengajuan = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM admin_pengajuanbantuan");
$data_pengajuan  = mysqli_fetch_assoc($query_pengajuan);
$total_pengajuan = $data_pengajuan['total'];

$sql_terbaru = "SELECT ap.nik, dp.nama_lengkap, ap.tanggal_pengajuan 
                FROM admin_pengajuanbantuan ap
                INNER JOIN admin_datapetani dp ON ap.nik = dp.nik
                ORDER BY ap.id_pengajuan DESC 
                LIMIT 10";
$query_terbaru = mysqli_query($koneksi, $sql_terbaru);

// Fungsi bantu untuk mengubah format tanggal ke teks Indonesia (ex: 2026-05-24 -> 24 Mei 2026)
function formatTanggalIndonesia($tanggal) {
    $bulan = [
        1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
        'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'
    ];
    $split = explode('-', $tanggal);
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP-BANTU TANI - Dashboard Admin</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #fbc02d; /* Latar belakang kuning luar */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .dashboard-container {
            width: 100%;
            max-width: 1280px;
            height: 850px;
            max-height: 92vh;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            display: flex;
            overflow: hidden;
        }

        /* SIDEBAR MENU (KIRI) */
        .sidebar {
            width: 280px;
            background-color: #0d7839; /* Hijau tua */
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 0;
            flex-shrink: 0;
        }

        .sidebar-logo-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 0 20px;
            margin-bottom: 40px;
        }

        .sidebar-logo-bg {
            width: 90px;
            height: 90px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            border: 2px solid #13a851;
            margin-bottom: 12px;
        }

        .sidebar-logo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sidebar-title {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .sidebar-subtitle {
            font-size: 11px;
            color: #d1e7dd;
            font-weight: 500;
        }

        .sidebar-menu {
            width: 100%;
            list-style: none;
            display: flex;
            flex-direction: column;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 16px 28px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 16px;
            font-weight: 600;
            transition: all 0.2s ease;
            gap: 15px;
            text-decoration: none; 
            cursor: pointer;
        }

        .menu-item.active {
            background-color: rgba(255, 255, 255, 0.15);
            border-left: 5px solid #3bf789;
            color: white;
        }

        .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            padding-left: 33px;
        }

        .menu-icon {
            font-size: 22px;
        }

        /* AREA KONTEN (KANAN) */
        .main-content {
            flex-grow: 1;
            background-color: white;
            padding: 40px 50px;
            overflow-y: auto;
        }

        .page-header {
            margin-bottom: 30px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 15px;
        }

        .page-title {
            color: #0d7839;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .welcome-text {
            color: #495057;
            font-size: 16px;
            font-weight: 500;
        }

        /* STATS CARD DASHBOARD */
        .stats-grid {
            display: flex;
            gap: 30px;
            margin-bottom: 40px;
            max-width: 600px;
        }

        .stat-card {
            flex: 1;
            background: white;
            border-radius: 8px;
            padding: 20px 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            border: 1px solid #e9ecef;
        }

        .stat-info {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .stat-label {
            color: #6c757d;
            font-size: 12px;
            font-weight: 600;
        }

        .stat-value {
            color: #212529;
            font-size: 32px;
            font-weight: 700;
        }

        .stat-icon-box {
            width: 50px;
            height: 50px;
            background-color: #d1e7dd;
            color: #0f8a42;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .table-section {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .section-title {
            color: #6c757d;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
        }

        /* TABEL DASHBOARD */
        .data-table-container {
            width: 100%;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 14px;
        }

        .data-table th {
            background-color: #e9ecef; 
            color: #495057;
            padding: 14px 16px;
            font-weight: bold;
        }

        .data-table td {
            padding: 14px 16px;
            color: #212529;
            border-bottom: 1px solid #dee2e6;
            font-weight: 500;
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        .text-center { text-align: center; }
    </style>
</head>
<body>

    <div class="dashboard-container">
        
        <aside class="sidebar">
            <div class="sidebar-logo-area">
                <div class="sidebar-logo-bg">
                    <img src="logo.png" alt="Logo SIP-BANTU TANI" class="sidebar-logo-img">
                </div>
                <h2 class="sidebar-title">SIP-BANTU TANI</h2>
                <p class="sidebar-subtitle">Portal Resmi Bantuan Pertanian</p>
            </div>

            <ul class="sidebar-menu">
                <a href="admin_dashboard.php" class="menu-item active">
                    <span class="material-symbols-outlined menu-icon">home</span>Dashboard
                </a>
                <a href="admin_data_petani.php" class="menu-item">
                    <span class="material-symbols-outlined menu-icon">person</span>Data Petani
                </a>
                <a href="admin_pengajuan.php" class="menu-item">
                    <span class="material-symbols-outlined menu-icon">format_list_bulleted</span>Pengajuan
                </a>
                <a href="admin_bantuan.php" class="menu-item">
                    <span class="material-symbols-outlined menu-icon">category</span>Bantuan
                </a>
                <a href="admin_laporan.php" class="menu-item">
                    <span class="material-symbols-outlined menu-icon">description</span>Laporan
                </a>
                <a href="admin_profil.php" class="menu-item">
                    <span class="material-symbols-outlined menu-icon">account_circle</span>Profil
                </a>
            </ul>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1 class="page-title">Dashboard</h1>
                <p class="welcome-text">Selamat datang kembali, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-info">
                        <span class="stat-label">Total Petani</span>
                        <span class="stat-value"><?php echo $total_petani; ?></span>
                    </div>
                    <div class="stat-icon-box">
                        <span class="material-symbols-outlined">group</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <span class="stat-label">Total Pengajuan</span>
                        <span class="stat-value"><?php echo $total_pengajuan; ?></span>
                    </div>
                    <div class="stat-icon-box">
                        <span class="material-symbols-outlined">list_alt</span>
                    </div>
                </div>
            </div>

            <div class="table-section">
                <h3 class="section-title">Pengajuan terbaru</h3>
                <div class="data-table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 10%;" class="text-center">No</th>
                                <th style="width: 30%;">NIK</th>
                                <th style="width: 35%;">Nama</th>
                                <th style="width: 25%;">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            if (mysqli_num_rows($query_terbaru) > 0) {
                                while ($row = mysqli_fetch_assoc($query_terbaru)) { ?>
                                    <tr>
                                        <td class="text-center"><?php echo $no++; ?></td>
                                        <td><?php echo htmlspecialchars($row['nik']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                                        <td><?php echo formatTanggalIndonesia($row['tanggal_pengajuan']); ?></td>
                                    </tr>
                                <?php } 
                            } else { ?>
                                <tr>
                                    <td colspan="4" class="text-center" style="color: #6c757d; font-style: italic;">Belum ada data pengajuan bantuan terbaru.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

</body>
</html>