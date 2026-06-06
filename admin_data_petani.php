<?php
// 1. PROTEKSI HALAMAN (Wajib Login Admin)
session_start();
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
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 3. LOGIKA PENCARIAN DATA PETANI
$keyword = "";
if (isset($_GET['cari'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_GET['cari']);
    $query = "SELECT * FROM admin_datapetani 
              WHERE nama_lengkap LIKE '%$keyword%' 
              OR nik LIKE '%$keyword%' 
              ORDER BY id ASC";
} else {
    $query = "SELECT * FROM admin_datapetani ORDER BY id ASC";
}

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP-BANTU TANI - Data Petani</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
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
        .menu-item { display: flex; align-items: center; padding: 16px 28px; color: rgba(255, 255, 255, 0.8); font-size: 16px; font-weight: 600; transition: all 0.2s ease; gap: 15px; text-decoration: none; cursor: pointer; }
        .menu-item.active { background-color: rgba(255, 255, 255, 0.15); border-left: 5px solid #3bf789; color: white; }
        .menu-item:hover { background-color: rgba(255, 255, 255, 0.1); color: white; padding-left: 33px; }
        .menu-icon { font-size: 22px; }

        /* KONTEN UTAMA - TIDAK DI-SCROLL */
        .main-content { flex-grow: 1; background-color: white; padding: 40px 50px; display: flex; flex-direction: column; overflow: hidden; }
        .page-header { margin-bottom: 30px; border-bottom: 1px solid #dee2e6; padding-bottom: 15px; }
        .page-title { color: #0d7839; font-size: 28px; font-weight: 700; margin-bottom: 8px; }
        
        .search-add-bar { display: flex; justify-content: space-between; align-items: center; gap: 20px; margin-bottom: 20px; }
        .search-form { flex-grow: 1; max-width: 600px; }
        .search-box-container { position: relative; width: 100%; }
        .search-input { width: 100%; padding: 12px 20px 12px 45px; font-size: 14px; border: 1px solid #adb5bd; border-radius: 30px; background-color: #f1f3f5; outline: none; }
        .search-icon-inside { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 20px; }
        .btn-tambah { background-color: #13a851; color: white; border: none; padding: 12px 24px; border-radius: 25px; font-size: 14px; font-weight: bold; display: flex; align-items: center; gap: 8px; cursor: pointer; text-decoration: none; }
        
        .list-info-text { font-size: 16px; font-weight: bold; color: #212529; margin-bottom: 15px; }

        /* TABEL DATA PETANI - DI-SCROLL */
        .data-table-container { width: 100%; border: 1px solid #dee2e6; border-radius: 8px; overflow-y: auto; flex-grow: 1; }
        .data-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; }
        .data-table thead th { position: sticky; top: 0; background-color: #f8f9fa; color: #13a851; padding: 14px 16px; font-weight: bold; border-bottom: 2px solid #dee2e6; z-index: 1; }
        .data-table td { padding: 14px 16px; color: #212529; border-bottom: 1px solid #dee2e6; font-weight: 500; }
        .text-center { text-align: center; }
        .alert-info { padding: 20px; background-color: #e2f0d9; color: #385723; text-align: center; font-weight: bold; }
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
                <a href="admin_dashboard.php" class="menu-item">
                    <span class="material-symbols-outlined menu-icon">home</span>Dashboard
                </a>
                <a href="admin_data_petani.php" class="menu-item active">
                    <span class="material-symbols-outlined menu-icon">person</span>Data Petani
                </a>
                <a href="admin_pengajuan.php" class="menu-item">
                    <span class="material-symbols-outlined menu-icon">format_list_bulleted</span>Pengajuan
                </a>
                <a href="admin_bantuan.php" class="menu-item">
                    <span class="material-symbols-outlined menu-icon">category</span>Bantuan
                </a>
                <a href="admin_survey.php" class="menu-item">
                    <span class="material-symbols-outlined menu-icon">poll</span>Hasil Survey
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
                <h1 class="page-title">Data Petani</h1>
            </div>

            <div class="search-add-bar">
                <form action="admin_data_petani.php" method="GET" class="search-form">
                    <div class="search-box-container">
                        <span class="material-symbols-outlined search-icon-inside">search</span>
                        <input type="text" name="cari" class="search-input" placeholder="Cari Berdasarkan Nama atau NIK..." value="<?php echo htmlspecialchars($keyword); ?>">
                    </div>
                </form>
                <a href="admin_tambah_petani.php" class="btn-tambah">
                    <span class="material-symbols-outlined">add</span>Tambah Petani
                </a>
            </div>

            <h3 class="list-info-text">
                <?php echo (!empty($keyword)) ? "Hasil Pencarian: '" . htmlspecialchars($keyword) . "'" : "Daftar Petani yang Terdaftar"; ?>
            </h3>

            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;" class="text-center">No</th>
                            <th style="width: 20%;">Nama</th>
                            <th style="width: 18%;" class="text-center">NIK</th>
                            <th style="width: 12%;">Komoditas</th>
                            <th style="width: 30%;">Alamat</th>
                            <th style="width: 15%;">TTL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if (mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)) { 
                                $tanggal_format = date('d F Y', strtotime($row['tanggal_lahir']));
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row['nama_lengkap'] ?? ''); ?></td>
                            <td class="text-center"><strong><?php echo htmlspecialchars($row['nik']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['komoditas']); ?></td>
                            <td style="font-size: 12px;"><?php echo htmlspecialchars($row['alamat']); ?></td>
                            <td style="font-size: 12px;">
                                <?php echo htmlspecialchars($row['tempat_lahir']); ?>,<br>
                                <?php echo $tanggal_format; ?>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else { 
                            echo "<tr><td colspan='6' class='alert-info'>Data tidak ditemukan.</td></tr>";
                        } 
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>