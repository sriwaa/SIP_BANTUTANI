<?php
// 1. KONEKSI DATABASE
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sip_bantutani";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Koneksi gagal: " . $conn->connect_error); }

// 2. QUERY: Ambil program yang memiliki pengajuan aktif (Status yang relevan DAN deadline belum lewat)
$sql_bantuan = "SELECT DISTINCT jb.id_program, jb.nama_program 
                FROM admin_programbantuan jb
                JOIN admin_pengajuanbantuan pb ON jb.id_program = pb.id_program
                LEFT JOIN admin_survey s ON pb.id_pengajuan = s.id_pengajuan
                WHERE pb.status_pengajuan IN ('Tahap Survey', 'Selesai', 'Diterima', 'Ditolak')
                AND (s.deadline_survey >= CURDATE() OR s.deadline_survey IS NULL)";
$res_bantuan = $conn->query($sql_bantuan);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SIP-BANTU TANI - Hasil Survey</title>
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

        /* MAIN CONTENT */
        .main-content { flex-grow: 1; background-color: white; padding: 40px 50px; display: flex; flex-direction: column; overflow: hidden; }
        .header-fixed { flex-shrink: 0; margin-bottom: 20px; border-bottom: 1px solid #dee2e6; padding-bottom: 15px; }
        .page-title { color: #0d7839; font-size: 32px; font-weight: 700; }
        .sub-header-text { color: #6c757d; font-size: 16px; font-weight: 500; margin-top: 10px; }
        
        /* SCROLL AREA */
        .list-scroll-area { flex-grow: 1; overflow-y: auto; padding-right: 10px; }
        .bantuan-group { margin-bottom: 30px; }
        .bantuan-title { color: #0d7839; font-size: 18px; font-weight: 700; margin-bottom: 10px; background: #e2f3e9; padding: 10px; border-radius: 8px; }
        
        /* TABLE STYLE */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background: #f8f9fa; padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6; color: #0d7839; font-size: 14px; }
        td { padding: 12px; border-bottom: 1px solid #eee; font-size: 13px; }
        .btn-detail { background-color: #0d7839; color: white; text-decoration: none; padding: 6px 15px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .status-badge { padding: 4px 10px; border-radius: 15px; font-size: 11px; font-weight: 600; }
        .text-muted { color: #888; font-style: italic; }
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
                <a href="admin_survey.php" class="menu-item active"><span class="material-symbols-outlined menu-icon">poll</span>Hasil Survey</a>
                <a href="admin_laporan.php" class="menu-item"><span class="material-symbols-outlined menu-icon">description</span>Laporan</a>
                <a href="admin_profil.php" class="menu-item"><span class="material-symbols-outlined menu-icon">account_circle</span>Profil</a>
            </ul>
        </aside>

        <main class="main-content">
            <div class="header-fixed">
                <h1 class="page-title">Hasil Survey</h1>
                <p class="sub-header-text">Daftar hasil survei lapangan dikelompokkan per bantuan</p>
            </div>

            <div class="list-scroll-area">
                <?php if ($res_bantuan->num_rows > 0): ?>
                    <?php while ($bantuan = $res_bantuan->fetch_assoc()): ?>
                        <div class="bantuan-group">
                            <h3 class="bantuan-title"><?= htmlspecialchars($bantuan['nama_program']); ?></h3>
                            <table>
                                <thead>
                                    <tr><th>No</th><th>Nama Petani</th><th>Surveyor</th><th>Deadline</th><th>Status</th><th>Aksi</th></tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $id_p = $bantuan['id_program'];
                                    $sql_petani = "SELECT s.deadline_survey, s.id_pengajuan, pb.status_pengajuan, dp.nama_lengkap AS nama_petani, 
                                                   (SELECT nama_lengkap FROM surveyor WHERE id_surveyor = 2) AS nama_surveyor
                                                   FROM admin_pengajuanbantuan pb
                                                   LEFT JOIN admin_survey s ON pb.id_pengajuan = s.id_pengajuan
                                                   JOIN admin_datapetani dp ON pb.nik = dp.nik
                                                   WHERE pb.id_program = '$id_p' 
                                                   AND pb.status_pengajuan IN ('Tahap Survey', 'Selesai', 'Diterima', 'Ditolak')
                                                   AND (s.deadline_survey >= CURDATE() OR s.deadline_survey IS NULL)";
                                    $res_petani = $conn->query($sql_petani);
                                    $no = 1;
                                    while ($p = $res_petani->fetch_assoc()): 
                                        $is_selesai = ($p['status_pengajuan'] == 'Selesai' || $p['status_pengajuan'] == 'Diterima' || $p['status_pengajuan'] == 'Ditolak');
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= htmlspecialchars($p['nama_petani']); ?></td>
                                            <td><?= htmlspecialchars($p['nama_surveyor'] ?? '-'); ?></td>
                                            <td><?= !empty($p['deadline_survey']) ? htmlspecialchars($p['deadline_survey']) : '<span class="text-muted">-</span>'; ?></td>
                                            <td>
                                                <span class="status-badge" style="background: <?= $is_selesai ? '#d4edda' : '#e2f3e9'; ?>; color: <?= $is_selesai ? '#155724' : '#0d7839'; ?>;">
                                                    <?= htmlspecialchars($p['status_pengajuan']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="admin_surveydetail.php?id=<?= $p['id_pengajuan']; ?>" class="btn-detail">
                                                    <?= $is_selesai ? 'Lihat Hasil' : 'Kelola'; ?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="color: #6c757d; text-align: center; margin-top: 50px;">Tidak ada data hasil survey yang ditemukan.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
<?php $conn->close(); ?>