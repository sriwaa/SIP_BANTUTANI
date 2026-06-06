<?php
// 1. PROTEKSI HALAMAN (Wajib Login Admin)
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Admin') {
    header("Location: admin_login.php");
    exit();
}

// 2. KONEKSI DATABASE
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sip_bantutani";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Koneksi gagal: " . $conn->connect_error); }

$id_program = isset($_GET['id_program']) ? $_GET['id_program'] : '';

// 3. QUERY AMBIL DATA (Sudah ditambah ap.id_pengajuan)
$sql = "SELECT hw.ranking, hw.nik, dp.nama_lengkap, hw.vektor_v, pb.nama_program, 
               ap.tanggal_pengajuan, ap.status_pengajuan, ap.bukti_survey, ap.id_pengajuan 
        FROM admin_hasilwp hw 
        JOIN admin_datapetani dp ON hw.nik = dp.nik 
        JOIN admin_programbantuan pb ON hw.id_program = pb.id_program
        LEFT JOIN admin_pengajuanbantuan ap ON hw.nik = ap.nik AND hw.id_program = ap.id_program";

if (!empty($id_program)) {
    $sql .= " WHERE hw.id_program = " . intval($id_program);
}

$sql .= " ORDER BY hw.vektor_v DESC"; 
$result = $conn->query($sql);
$program_list = $conn->query("SELECT * FROM admin_programbantuan");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SIP-BANTU TANI - Laporan Hasil</title>
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
        .menu-item { display: flex; align-items: center; padding: 16px 28px; color: rgba(255, 255, 255, 0.8); font-size: 16px; font-weight: 600; text-decoration: none; gap: 15px; transition: all 0.2s; cursor: pointer; }
        .menu-item.active { background-color: rgba(255, 255, 255, 0.15); border-left: 5px solid #3bf789; color: white; }
        .menu-item:hover { background-color: rgba(255, 255, 255, 0.1); color: white; padding-left: 33px; }
        .menu-icon { font-size: 22px; }

        /* KONTEN */
        .main-content { flex-grow: 1; padding: 40px 50px; overflow-y: auto; display: flex; flex-direction: column; }
        .header-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #dee2e6; padding-bottom: 15px; }
        .page-title { color: #0d7839; font-size: 32px; font-weight: 700; }
        .filter-section { margin-bottom: 20px; }
        select { padding: 10px 15px; border-radius: 8px; border: 1px solid #ced4da; font-size: 14px; cursor: pointer; }
        .data-table-container { width: 100%; border: 1px solid #ced4da; border-radius: 12px; overflow: hidden; }
        .data-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; }
        .data-table th { background-color: #f8f9fa; color: #0d7839; padding: 16px; font-weight: 700; border-bottom: 2px solid #dee2e6; }
        .data-table td { padding: 16px; color: #1b1c1e; border-bottom: 1px solid #dee2e6; }
        .btn-print { background-color: #13a851; color: white; padding: 10px 20px; border-radius: 25px; text-decoration: none; font-weight: 600; border: none; cursor: pointer; }
        .btn-lihat { background-color: #0d7839; color: white; padding: 5px 12px; border-radius: 5px; text-decoration: none; font-size: 12px; }
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
                <a href="admin_bantuan.php" class="menu-item"><span class="material-symbols-outlined menu-icon">category</span>Bantuan</a>
                <a href="admin_survey.php" class="menu-item"><span class="material-symbols-outlined menu-icon">poll</span>Hasil Survey</a>
                <a href="admin_laporan.php" class="menu-item active"><span class="material-symbols-outlined menu-icon">description</span>Laporan</a>
                <a href="admin_profil.php" class="menu-item"><span class="material-symbols-outlined menu-icon">account_circle</span>Profil</a>
            </ul>
        </aside>

        <main class="main-content">
            <div class="header-row">
                <h1 class="page-title">Laporan Penilaian</h1>
                <button onclick="window.print()" class="btn-print">Cetak Laporan</button>
            </div>

            <form method="GET" class="filter-section">
                <select name="id_program" onchange="this.form.submit()">
                    <option value="">-- Semua Jenis Bantuan --</option>
                    <?php while($p = $program_list->fetch_assoc()): ?>
                        <option value="<?= $p['id_program'] ?>" <?= ($id_program == $p['id_program']) ? 'selected' : '' ?>>
                            <?= $p['nama_program'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </form>

            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Nama Petani</th>
                            <th>Bantuan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Bukti Survey</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): 
                            while ($row = $result->fetch_assoc()): 
                                $status = $row['status_pengajuan'] ?? 'Menunggu';
                                $bg = '#fff3cd'; $txt = '#856404'; 
                                if ($status == 'Diterima') { $bg = '#d4edda'; $txt = '#155724'; }
                                elseif ($status == 'Ditolak') { $bg = '#f8d7da'; $txt = '#721c24'; }
                                elseif ($status == 'Survey') { $bg = '#cce5ff'; $txt = '#004085'; }
                        ?>
                            <tr>
                                <td><strong><?= $row['ranking'] ?></strong></td>
                                <td><?= htmlspecialchars($row['nama_lengkap'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['nama_program'] ?? '-') ?></td>
                                <td><?= isset($row['tanggal_pengajuan']) ? date('d-m-Y', strtotime($row['tanggal_pengajuan'])) : '-' ?></td>
                                <td>
                                    <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; background: <?= $bg ?>; color: <?= $txt ?>;">
                                        <?= $status ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($row['id_pengajuan'])): ?>
                                        <a href="admin_laporandetail.php?id=<?= $row['id_pengajuan'] ?>" class="btn-lihat">Lihat Detail</a>
                                    <?php else: ?>
                                        <span style="color: #999; font-size: 12px;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= number_format($row['vektor_v'], 6) ?></td>
                            </tr>
                        <?php endwhile; else: ?>
                            <tr><td colspan="7" style="text-align:center;">Data tidak ditemukan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
<?php $conn->close(); ?>