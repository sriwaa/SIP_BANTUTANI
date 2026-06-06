<?php
// 1. KONEKSI DATABASE
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sip_bantutani";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Koneksi gagal: " . $conn->connect_error); }

// 2. AMBIL ID DARI URL
$id_pengajuan = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 3. QUERY AMBIL DATA LENGKAP
$sql = "SELECT s.*, dp.*, apb.nama_program, pb.*
        FROM admin_survey s
        JOIN admin_pengajuanbantuan pb ON s.id_pengajuan = pb.id_pengajuan
        JOIN admin_datapetani dp ON pb.nik = dp.nik
        JOIN admin_programbantuan apb ON pb.id_program = apb.id_program
        WHERE s.id_pengajuan = $id_pengajuan";

$result = $conn->query($sql);
$data = $result->fetch_assoc();

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='admin_survey.php';</script>";
    exit;
}

// Mapping Kriteria
$kriteria_map = [
    'c1_luas_lahan'       => 'Luas Lahan',
    'c2_penghasilan'      => 'Penghasilan',
    'c3_komoditas'        => 'Komoditas',
    'c4_kondisi_lahan'    => 'Kondisi Lahan',
    'c5_kepemilikan_alat' => 'Kepemilikan Alat'
];

// LOGIKA PENGECEKAN KELENGKAPAN (Untuk tombol)
$semua_lengkap = true;
foreach($kriteria_map as $key => $label) {
    $val_col = "validasi_" . $key;
    $cat_col = "catatan_" . $key;
    $foto_col = "file_" . $key;
    
    // Jika validasi kosong, catatan kosong, atau foto kosong, maka belum lengkap
    if(empty($data[$val_col]) || empty($data[$cat_col]) || empty($data[$foto_col])) {
        $semua_lengkap = false;
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP-BANTU TANI - Detail Survey</title>
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
        .sub-header-text { color: #6c757d; font-size: 14px; font-weight: 500; margin-left: 44px; }
        .scrollable-content { flex-grow: 1; padding: 0 50px 40px 50px; overflow-y: auto; }
        .detail-box-container { border: 1px solid #dee2e6; border-radius: 12px; padding: 35px; width: 100%; max-width: 950px; }
        .section-title { font-size: 16px; font-weight: 700; color: #0d7839; margin-bottom: 20px; border-bottom: 2px solid #e2f3e9; padding-bottom: 5px; display: flex; align-items: center; gap: 8px; }
        .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px; }
        .info-label { font-size: 12px; font-weight: 700; color: #6c757d; text-transform: uppercase; }
        .info-value { font-size: 15px; font-weight: 600; color: #1b1c1e; margin-top: 5px; }
        .table-survey { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table-survey th, .table-survey td { padding: 12px; border: 1px solid #dee2e6; text-align: left; font-size: 13px; }
        .table-survey th { background: #0d7839; color: white; }
        .btn-action { padding: 12px 30px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; color: white; }
        .btn-terima { background: #0d7839; }
        .btn-tolak { background: #dc3545; }
        .alert-box { padding: 15px; background: #fff3cd; color: #856404; border-radius: 6px; font-weight: bold; margin-top: 20px; }
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
                <a href="admin_survey.php" class="back-nav"><span class="material-symbols-outlined">arrow_back</span> Detail Survey</a>
                <p class="sub-header-text">Informasi hasil observasi lapangan petani</p>
            </div>

            <div class="scrollable-content">
                <div class="detail-box-container">
                    <div class="section-title"><span class="material-symbols-outlined">person</span> Data Petani & Bantuan</div>
                    <div class="info-grid">
                        <div><div class="info-label">Nama Lengkap</div><div class="info-value"><?= htmlspecialchars($data['nama_lengkap'] ?? ''); ?></div></div>
                        <div><div class="info-label">NIK</div><div class="info-value"><?= htmlspecialchars($data['nik'] ?? ''); ?></div></div>
                        <div><div class="info-label">No Telp</div><div class="info-value"><?= htmlspecialchars($data['no_telp'] ?? ''); ?></div></div>
                        <div><div class="info-label">Komoditas</div><div class="info-value"><?= htmlspecialchars($data['komoditas'] ?? '-'); ?></div></div>
                        <div><div class="info-label">Alamat</div><div class="info-value"><?= htmlspecialchars($data['alamat'] ?? '-'); ?></div></div>
                        <div><div class="info-label">Tempat, Tgl Lahir</div><div class="info-value"><?= htmlspecialchars($data['tempat_lahir'] . ', ' . $data['tanggal_lahir']); ?></div></div>
                        <div><div class="info-label">Program Bantuan</div><div class="info-value"><?= htmlspecialchars($data['nama_program'] ?? ''); ?></div></div>
                    </div>

                    <div class="section-title"><span class="material-symbols-outlined">analytics</span> Hasil Observasi Lapangan</div>
                    <table class="table-survey">
                        <thead>
                            <tr><th>Kriteria</th><th>Data Pengajuan</th><th>Validasi</th><th>Catatan</th><th>Bukti Foto</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach($kriteria_map as $key => $label): 
                                $val_col = "validasi_" . $key;
                                $cat_col = "catatan_" . $key;
                                $foto_col = "file_" . $key;
                            ?>
                            <tr>
                                <td><?= $label ?></td>
                                <td><?= htmlspecialchars($data[$key] ?? '-') ?></td>
                                <td><?= htmlspecialchars($data[$val_col] ?? '-') ?></td>
                                <td><?= htmlspecialchars($data[$cat_col] ?? '-') ?></td>
                                <td>
                                    <?php if (!empty($data[$foto_col])): ?>
                                        <a href="uploads/<?= $data[$foto_col] ?>" target="_blank">Lihat</a>
                                    <?php else: ?> - <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <?php if ($data['status_pengajuan'] === 'Selesai'): ?>
                        <?php if ($semua_lengkap): ?>
                            <div style="margin-top:20px; display:flex; gap:15px;">
                                <form action="admin_proses_keputusan.php" method="POST" onsubmit="return confirm('Terima pengajuan ini?')">
                                    <input type="hidden" name="id" value="<?= $id_pengajuan ?>">
                                    <button type="submit" name="status" value="Diterima" class="btn-action btn-terima">Terima Pengajuan</button>
                                </form>
                                <form action="admin_proses_keputusan.php" method="POST" onsubmit="return confirm('Tolak pengajuan ini?')">
                                    <input type="hidden" name="id" value="<?= $id_pengajuan ?>">
                                    <button type="submit" name="status" value="Ditolak" class="btn-action btn-tolak">Tolak Pengajuan</button>
                                </form>
                            </div>
                        <?php else: ?>
                            <div class="alert-box">Tombol keputusan terkunci: Harap lengkapi semua data validasi, catatan, dan upload bukti foto terlebih dahulu.</div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div style="margin-top:20px; font-weight:bold; color:#0d7839;">Status Akhir: <?= htmlspecialchars($data['status_pengajuan']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
<?php $conn->close(); ?>