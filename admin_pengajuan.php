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

// 2. QUERY MENGAMBIL DATA JENIS BANTUAN DAN KONTRIBUSI TOTAL PENGAJUAN
// Perbaikan: Menghindari ambiguitas kolom id_program dengan mendefinisikannya secara jelas
$sql = "SELECT jb.id_program, jb.nama_program, jb.batas_akhir, jb.status, jb.gambar,
               COUNT(pb.id_pengajuan) AS total_pengajuan 
        FROM admin_programbantuan jb
        LEFT JOIN admin_pengajuanbantuan pb ON jb.id_program = pb.id_program
        GROUP BY jb.id_program, jb.nama_program, jb.batas_akhir, jb.status, jb.gambar";

// EKSEKUSI QUERY DATABASE
$result = $conn->query($sql); 

// 3. LOGIKA UNTUK STATISTIK FOOTER
$total_program = 0;
$total_pengajuan_all = 0;
$pengajuan_open = 0;
$pengajuan_closed = 0;

// Ambil semua data ke dalam array terlebih dahulu agar bisa dipakai berulang
$bantuan_list = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bantuan_list[] = $row;
        
        // Hitung statistik otomatis
        $total_program++;
        $total_pengajuan_all += $row['total_pengajuan'];
        if (isset($row['status']) && strtolower($row['status']) == 'open') {
            $pengajuan_open++;
        } else {
            $pengajuan_closed++;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP-BANTU TANI - Pengajuan</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #fbc02d; /* Kuning latar belakang figma */
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

        /* SIDEBAR NAVIGASI (KIRI) */
        .sidebar {
            width: 280px;
            background-color: #0d7839;
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

        /* AREA KONTEN UTAMA (KANAN) */
        .main-content {
            flex-grow: 1;
            background-color: white;
            padding: 40px 50px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .page-title {
            color: #0d7839;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 15px;
        }

        .sub-header-text {
            color: #6c757d;
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 30px;
        }

        /* CONTAINER GRID KARTU PROGRAM BANTUAN */
        .cards-grid-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-bottom: 25px;
        }

        .program-card {
            border: 1px solid #ced4da;
            border-radius: 12px;
            background-color: #ffffff;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            display: flex;
            flex-direction: column;
        }

        .card-top-section {
            background-color: #e2f3e9;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 110px;
            border-bottom: 1px solid #ced4da;
        }

        .program-name {
            font-size: 18px;
            font-weight: 700;
            color: #1b1c1e;
            max-width: 140px;
            line-height: 1.3;
        }

        .program-img-box {
            width: 70px;
            height: 70px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .program-img-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .card-info-body {
            padding: 15px 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            font-size: 13px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-label {
            color: #7d848c;
            font-weight: 500;
        }

        .info-value {
            font-weight: 600;
            color: #212529;
        }

        /* PERUBAHAN WARNA DINAMIS BATAS AKHIR */
        .info-value.date-open { color: #0d7839; }
        .info-value.date-closed { color: #dc3545; }

        /* Badge Status (Open / Closed) */
        .status-badge {
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-badge.open {
            background-color: #c9ebd6;
            color: #0d7839;
        }

        .status-badge.closed {
            background-color: #f8d7da;
            color: #dc3545;
        }

        .card-action-area {
            padding: 0 20px 20px 20px;
            margin-top: auto;
        }

        .btn-lihat-detail {
            width: 100%;
            background-color: #0d7839;
            color: white;
            border: none;
            padding: 10px;
            font-size: 13px;
            font-weight: bold;
            border-radius: 25px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
            text-decoration: none;
            transition: background 0.2s;
        }

        .btn-lihat-detail:hover {
            background-color: #0f8a42;
        }

        /* BARIS RANGKUMAN STATISTIK (BAWAH) */
        .summary-stats-bar {
            border: 1px solid #ced4da;
            border-radius: 12px;
            padding: 20px 30px;
            background-color: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }

        .stat-item-box {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .stat-icon-circle {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f1f3f5;
            color: #495057;
        }

        .stat-icon-circle.total-prog { background-color: #e2f3e9; color: #0d7839; }
        .stat-icon-circle.total-peng { background-color: #fff3cd; color: #f5b041; }
        .stat-icon-circle.peng-open { background-color: #d1e7dd; color: #0f8a42; }
        .stat-icon-circle.peng-closed { background-color: #f8d7da; color: #dc3545; }

        .stat-icon-circle .material-symbols-outlined { font-size: 24px; }
        .stat-text-data { display: flex; flex-direction: column; }
        .stat-label-title { font-size: 11px; color: #7d848c; font-weight: 600; }
        .stat-count-value { font-size: 20px; font-weight: 800; color: #212529; line-height: 1.2; }
        .stat-desc-detail { font-size: 11px; color: #7d848c; }
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
                <a href="admin_dashboard.php" class="menu-item">
                    <span class="material-symbols-outlined menu-icon">home</span>Dashboard
                </a>
                <a href="admin_data_petani.php" class="menu-item">
                    <span class="material-symbols-outlined menu-icon">person</span>Data Petani
                </a>
                <a href="admin_pengajuan.php" class="menu-item active">
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
            <h1 class="page-title">Pengajuan</h1>
            <p class="sub-header-text">Kelola dan pantau pengajuan bantuan dari petani</p>

            <div class="cards-grid-container">
                
                <?php foreach ($bantuan_list as $bantuan): 
                    $is_open = (isset($bantuan['status']) && strtolower($bantuan['status']) == 'open');
                    
                    // Format tanggal biar rapi
                    $bulan_indo = [
                        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ];
                    
                    $tgl_format = "-";
                    if (!empty($bantuan['batas_akhir'])) {
                        $time = strtotime($bantuan['batas_akhir']);
                        $bln_index = (int)date('m', $time);
                        $tgl_format = date('d', $time) . ' ' . ($bulan_indo[$bln_index] ?? '') . ' ' . date('Y', $time);
                    }
                    
                    // Mengarahkan parameter ID detail ke id_program hasil SQL query
                    $url_detail = "admin_detailpengajuan.php?id=" . ($bantuan['id_program'] ?? 0);
                    
                    // Menggunakan nama_program sesuai dengan isi tabel admin_programbantuan kamu
                    $nama_bantuan_tampil = $bantuan['nama_program'] ?? ($bantuan['nama_bantuan'] ?? 'Tanpa Nama Bantuan');
                ?>
                <div class="program-card">
                    <div class="card-top-section">
                        <h2 class="program-name"><?= htmlspecialchars($nama_bantuan_tampil); ?></h2>
                        <div class="program-img-box">
                            <img src="<?= htmlspecialchars($bantuan['gambar'] ?? 'default.png'); ?>" alt="<?= htmlspecialchars($nama_bantuan_tampil); ?>">
                        </div>
                    </div>
                    <div class="card-info-body">
                        <div class="info-row">
                            <span class="info-label">Batas Akhir</span>
                            <span class="info-value <?= $is_open ? 'date-open' : 'date-closed'; ?>">
                                <?= $tgl_format; ?>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Total Pengajuan</span>
                            <span class="info-value"><?= htmlspecialchars($bantuan['total_pengajuan'] ?? 0); ?> Orang</span> 
                        </div>
                        <div class="info-row">
                            <span class="info-label">Status</span>
                            <span class="status-badge <?= $is_open ? 'open' : 'closed'; ?>">
                                <?= ucfirst(htmlspecialchars($bantuan['status'] ?? 'Closed')); ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-action-area">
                        <a href="<?= $url_detail; ?>" class="btn-lihat-detail">Lihat Detail ></a>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>

            <footer class="summary-stats-bar">
                <div class="stat-item-box">
                    <div class="stat-icon-circle total-prog">
                        <span class="material-symbols-outlined">format_list_bulleted</span>
                    </div>
                    <div class="stat-text-data">
                        <span class="stat-label-title">Total Program</span>
                        <span class="stat-count-value"><?= $total_program; ?></span>
                        <span class="stat-desc-detail">Bantuan tersedia</span>
                    </div>
                </div>

                <div class="stat-item-box">
                    <div class="stat-icon-circle total-peng">
                        <span class="material-symbols-outlined">groups</span>
                    </div>
                    <div class="stat-text-data">
                        <span class="stat-label-title">Total Pengajuan</span>
                        <span class="stat-count-value"><?= $total_pengajuan_all; ?></span>
                        <span class="stat-desc-detail">Dari semua program</span>
                    </div>
                </div>

                <div class="stat-item-box">
                    <div class="stat-icon-circle peng-open">
                        <span class="material-symbols-outlined">check_circle</span>
                    </div>
                    <div class="stat-text-data">
                        <span class="stat-label-title">Pengajuan Open</span>
                        <span class="stat-count-value"><?= $pengajuan_open; ?></span>
                        <span class="stat-desc-detail">Sedang dibuka</span>
                    </div>
                </div>

                <div class="stat-item-box">
                    <div class="stat-icon-circle peng-closed">
                        <span class="material-symbols-outlined">lock</span>
                    </div>
                    <div class="stat-text-data">
                        <span class="stat-label-title">Pengajuan Closed</span>
                        <span class="stat-count-value"><?= $pengajuan_closed; ?></span>
                        <span class="stat-desc-detail">Telah ditutup</span>
                    </div>
                </div>
            </footer>

        </main>
    </div>

</body>
</html>
<?php
$conn->close();
?>