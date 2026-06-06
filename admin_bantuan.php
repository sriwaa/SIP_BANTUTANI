<?php
// 1. KONEKSI DATABASE
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sip_bantutani";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// 2. QUERY AMBIL DATA
$sql = "SELECT id_program AS id, nama_program AS nama_bantuan, gambar, deskripsi 
        FROM admin_programbantuan 
        ORDER BY id_program ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP-BANTU TANI - Jenis Bantuan</title>
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
        .menu-item { display: flex; align-items: center; padding: 16px 28px; color: rgba(255, 255, 255, 0.8); font-size: 16px; font-weight: 600; transition: all 0.2s ease; gap: 15px; text-decoration: none; }
        .menu-item.active { background-color: rgba(255, 255, 255, 0.15); border-left: 5px solid #3bf789; color: white; }
        .menu-item:hover { background-color: rgba(255, 255, 255, 0.1); color: white; padding-left: 33px; }
        .menu-icon { font-size: 22px; }

        /* MAIN CONTENT FIXED & SCROLLABLE */
        .main-content { flex-grow: 1; background-color: white; padding: 40px 50px; display: flex; flex-direction: column; overflow: hidden; }
        .header-fixed { flex-shrink: 0; margin-bottom: 20px; }
        .header-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; border-bottom: 1px solid #dee2e6; padding-bottom: 15px; }
        .page-title { color: #0d7839; font-size: 32px; font-weight: 700; }
        .btn-tambah-bantuan { background-color: #13a851; color: white; border: none; padding: 12px 24px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 10px rgba(19, 168, 81, 0.15); }
        .sub-header-text { color: #6c757d; font-size: 16px; font-weight: 500; margin-top: 10px; }

        .list-scroll-area { flex-grow: 1; overflow-y: auto; padding-right: 10px; }
        .bantuan-list-wrapper { display: flex; flex-direction: column; gap: 15px; }
        .bantuan-list-item { border: 1px solid #ced4da; border-radius: 12px; background-color: #e2f3e9; padding: 15px 25px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 10px rgba(0,0,0,0.02); }
        .item-left-side { display: flex; align-items: center; gap: 20px; }
        .item-img-box { width: 75px; height: 75px; background-color: white; border-radius: 8px; display: flex; justify-content: center; align-items: center; overflow: hidden; border: 1px solid #ced4da; }
        .item-img-box img { max-width: 90%; max-height: 90%; object-fit: contain; }
        .item-text-info { display: flex; flex-direction: column; gap: 4px; }
        .item-title { font-size: 18px; font-weight: 700; color: #1b1c1e; }
        .item-desc { font-size: 13px; color: #6c757d; font-weight: 500; max-width: 500px; } 
        .btn-detail-item { background-color: #0d7839; color: white; text-decoration: none; padding: 10px 30px; border-radius: 20px; font-size: 14px; font-weight: bold; }
        .no-data { text-align: center; padding: 40px; color: #6c757d; font-style: italic; }
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
                <a href="admin_dashboard.php" class="menu-item"><span class="material-symbols-outlined menu-icon">home</span>Dashboard</a>
                <a href="admin_data_petani.php" class="menu-item"><span class="material-symbols-outlined menu-icon">person</span>Data Petani</a>
                <a href="admin_pengajuan.php" class="menu-item"><span class="material-symbols-outlined menu-icon">format_list_bulleted</span>Pengajuan</a>
                <a href="admin_bantuan.php" class="menu-item active"><span class="material-symbols-outlined menu-icon">category</span>Bantuan</a>
                <a href="admin_survey.php" class="menu-item"><span class="material-symbols-outlined menu-icon">poll</span>Hasil Survey</a>
                <a href="admin_laporan.php" class="menu-item"><span class="material-symbols-outlined menu-icon">description</span>Laporan</a>
                <a href="admin_profil.php" class="menu-item"><span class="material-symbols-outlined menu-icon">account_circle</span>Profil</a>
            </ul>
        </aside>

        <main class="main-content">
            <div class="header-fixed">
                <div class="header-row">
                    <h1 class="page-title">Jenis Bantuan</h1>
                    <a href="admin_tambahbantuan.php" class="btn-tambah-bantuan">+ Tambah Bantuan</a>
                </div>
                <p class="sub-header-text">Daftar Bantuan yang Terdaftar</p>
            </div>

            <div class="list-scroll-area">
                <div class="bantuan-list-wrapper">
                    <?php 
                    if ($result && $result->num_rows > 0):
                        while ($row = $result->fetch_assoc()): 
                            $deskripsi = (!empty($row['deskripsi'])) ? $row['deskripsi'] : "Program penyaluran bantuan resmi untuk mengoptimalkan hasil panen pertanian daerah.";
                    ?>
                        <div class="bantuan-list-item">
                            <div class="item-left-side">
                                <div class="item-img-box"><img src="<?= htmlspecialchars($row['gambar']); ?>" alt="<?= htmlspecialchars($row['nama_bantuan']); ?>"></div>
                                <div class="item-text-info">
                                    <h3 class="item-title"><?= htmlspecialchars($row['nama_bantuan']); ?></h3>
                                    <p class="item-desc"><?= htmlspecialchars($deskripsi); ?></p>
                                </div>
                            </div>
                            <div><a href="admin_detailbantuan.php?id=<?= $row['id']; ?>" class="btn-detail-item">Detail</a></div>
                        </div>
                    <?php endwhile; 
                    else: ?>
                        <p class="no-data">Belum ada daftar jenis bantuan saat ini.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
<?php $conn->close(); ?>