<?php
// 1. KONEKSI DATABASE
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sip_bantutani";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// 2. AMBIL ID BANTUAN DARI URL
$bantuan_id = isset($_GET['id']) ? (int)$_GET['id'] : 3;

// 3. QUERY AMBIL DATA MASTER PROGRAM BANTUAN DARI ADMIN_PROGRAMBANTUAN
$sql_bantuan = "SELECT * FROM admin_programbantuan WHERE id_program = $bantuan_id";
$res_bantuan = $conn->query($sql_bantuan);

if (!$res_bantuan) {
    die("Error Query Master Bantuan: " . $conn->error);
}

$bantuan = $res_bantuan->fetch_assoc();

if (!$bantuan) {
    die("Data program bantuan tidak ditemukan.");
}

// Cek apakah data kuesioner program ini sudah pernah dihitung WP & dikirim ke surveyor
$cek_hitungan_wp = $conn->query("SELECT COUNT(*) as total FROM admin_pengajuanbantuan WHERE id_program = $bantuan_id AND status_pengajuan = 'Tahap Survey'");
$data_hitungan_wp = $cek_hitungan_wp->fetch_assoc();
$sudah_hitung_wp = $data_hitungan_wp['total'] > 0;

// Logika penentuan status pendaftaran & perubahan tampilan tombol secara dinamis
$status_database = strtolower($bantuan['status']);
$deadline_str = $bantuan['batas_akhir']; 
$deadline_time = strtotime($deadline_str);
$today_time = time(); 

if ($sudah_hitung_wp) {
    $status_pendaftaran = "Tahap Survey";
    $status_badge_class = "status-badge-survey"; 
    $pesan_status = "✓ Data rekomendasi 3 besar telah disimpan dan dikirim ke tim Surveyor lapangan.";
} elseif ($today_time > $deadline_time || $status_database == 'closed') {
    $status_pendaftaran = "Closed";
    $status_badge_class = "status-badge-closed";
    $pesan_status = "";
} else {
    $status_pendaftaran = "Open";
    $status_badge_class = "status-badge-open";
    $pesan_status = "Perhitungan WP baru bisa dimulai setelah masa pendaftaran ditutup (Closed).";
}
// LOGIKA RESET DATA
if (isset($_POST['reset_status'])) {
    // 1. Reset status semua petani di program ini menjadi 'Diproses'
    $conn->query("UPDATE admin_pengajuanbantuan SET status_pengajuan = 'Diproses' WHERE id_program = $bantuan_id");
    
    // 2. Hapus hasil perhitungan WP
    $conn->query("DELETE FROM admin_hasilwp WHERE id_program = $bantuan_id");
    
    // 3. Hapus notifikasi terkait Tahap Survey untuk program ini (Opsional, jika ada tabel notifikasi)
    $conn->query("DELETE FROM admin_notifikasi WHERE pesan LIKE '%Tahap Survey%'");

    echo "<script>alert('Data berhasil di-reset ke status awal.'); window.location.href='admin_detailpengajuan.php?id=$bantuan_id';</script>";
    exit;
}

// FORMAT TANGGAL INDONESIA UNTUK DEADLINE & TANGGAL PENGAJUAN
$bulan_indo = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$tgl_pecah = explode('-', $deadline_str);
$deadline_tampil = isset($tgl_pecah[2]) ? $tgl_pecah[2] . ' ' . $bulan_indo[(int)$tgl_pecah[1]] . ' ' . $tgl_pecah[0] : $deadline_str;

// 4. FIX QUERY DAFTAR PETANI (Mengambil ap.tanggal_pengajuan dari database)
$table_petani = "admin_datapetani";
$test_table = $conn->query("SHOW TABLES LIKE 'admin_datapetani'");
if ($test_table->num_rows == 0) {
    $table_petani = "data_petani";
}

$sql_petani = "SELECT ap.id_pengajuan, ap.nik, ap.tanggal_pengajuan, ap.c1_luas_lahan, ap.c2_penghasilan, ap.c3_komoditas, ap.c4_kondisi_lahan, ap.c5_kepemilikan_alat, ap.status_pengajuan,
                     dp.nama_lengkap, dp.alamat
               FROM admin_pengajuanbantuan ap
               INNER JOIN $table_petani dp ON ap.nik = dp.nik
               WHERE ap.id_program = $bantuan_id
               ORDER BY ap.id_pengajuan ASC";
$res_petani = $conn->query($sql_petani);

if (!$res_petani) {
    die("Error Query Pengajuan Petani: " . $conn->error);
}

// 5. QUERY UNTUK MENGAMBIL DATA KOMODITAS LANGSUNG DARI TABEL PENGAJUAN
function getKomoditasPetani($conn, $nik, $current_val, $table_petani) {
    if (!empty($current_val) && $current_val !== '-' && strtolower($current_val) !== 'null') {
        return $current_val;
    }
    $nik = $conn->real_escape_string($nik);
    $res = $conn->query("SELECT komoditas FROM $table_petani WHERE nik = '$nik'");
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        return $row['komoditas'];
    }
    return "-";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP-BANTU TANI - Detail <?= htmlspecialchars($bantuan['nama_program']); ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fbc02d; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        
        .dashboard-container { width: 100%; max-width: 1280px; height: 850px; max-height: 92vh; background-color: white; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); display: flex; overflow: hidden; }
        
        .sidebar-brand-only { width: 280px; background-color: #0d7839; color: white; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 0; flex-shrink: 0; }
        .brand-logo-bg { width: 90px; height: 90px; background-color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; overflow: hidden; border: 2px solid #13a851; margin-bottom: 12px; }
        .brand-logo-img { width: 100%; height: 100%; object-fit: cover; }
        .brand-title { font-size: 16px; font-weight: bold; text-align: center; margin-bottom: 4px; letter-spacing: 0.5px; }
        .brand-subtitle { font-size: 11px; color: #d1e7dd; text-align: center; font-weight: 500; }
        
        .main-content { flex-grow: 1; background-color: white; padding: 40px 50px; overflow-y: auto; display: flex; flex-direction: column; }
        
        .page-header { margin-bottom: 10px; border-bottom: 1px solid #dee2e6; padding-bottom: 15px; display: flex; align-items: center; gap: 15px; }
        .btn-back { color: #0d7839; text-decoration: none; display: flex; align-items: center; }
        .back-icon { font-size: 32px; font-weight: bold; }
        .page-title { color: #0d7839; font-size: 32px; font-weight: 700; }
        
        .status-info-bar { display: flex; align-items: center; font-size: 14px; font-weight: 600; margin-top: 20px; margin-bottom: 15px; gap: 8px; }
        .status-badge-open { background-color: #d1e7dd; color: #0f5132; padding: 4px 14px; border-radius: 30px; font-size: 13px; }
        .status-badge-closed { background-color: #f8d7da; color: #842029; padding: 4px 14px; border-radius: 30px; font-size: 13px; }
        .status-badge-survey { background-color: #cff4fc; color: #055160; padding: 4px 14px; border-radius: 30px; font-size: 13px; }
        .date-highlight { color: #dc3545; font-weight: 700; }
        .list-info-text { font-size: 16px; font-weight: 500; color: #6c757d; margin-bottom: 15px; }
        
        .data-table-container { width: 100%; border: 1px solid #dee2e6; border-radius: 8px; overflow-x: auto; margin-bottom: 25px; }
        .data-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 13px; min-width: 1250px; }
        .data-table th { background-color: #f8f9fa; color: #0d7839; padding: 12px 10px; font-weight: bold; border-bottom: 2px solid #dee2e6; text-align: center; }
        .data-table td { padding: 12px 10px; color: #212529; border-bottom: 1px solid #dee2e6; text-align: center; }
        .kriteria-text { display: block; font-size: 12px; color: #495057; font-weight: 500; background: #f1f3f5; padding: 4px 6px; border-radius: 4px; text-align: left; }
        .tgl-text { font-size: 12px; color: #495057; font-weight: 500; }
        
        .badge-status-petani { display: inline-block; padding: 5px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; text-align: center; text-transform: capitalize; }
        .status-diproses { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .status-survey { background-color: #e0f7fa; color: #006064; border: 1px solid #b2ebf2; }
        .status-diterima { background-color: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }
        .status-ditolak { background-color: #f8d7da; color: #842029; border: 1px solid #f5c6cb; }
        
        .spk-action-area { margin-top: auto; padding-top: 15px; border-top: 1px solid #dee2e6; display: flex; flex-direction: column; align-items: flex-end; gap: 10px; }
        .info-hint { font-size: 13px; color: #6c757d; font-style: italic; font-weight: 500; }
        .info-success-hint { font-size: 14px; color: #0f5132; background-color: #d1e7dd; padding: 10px 15px; border-radius: 6px; font-weight: 600; width: 100%; text-align: right; margin-bottom: 5px; }
        
        .btn-hitung-wp { background-color: #a3c7b1; color: #ffffff; text-decoration: none; padding: 12px 25px; border-radius: 8px; font-size: 14px; font-weight: bold; display: flex; align-items: center; gap: 10px; cursor: not-allowed; pointer-events: none; transition: all 0.2s ease; }
        .btn-hitung-wp.active-hitung { background-color: #13a851; cursor: pointer; pointer-events: auto; box-shadow: 0 4px 12px rgba(19, 168, 81, 0.3); }
        .btn-hitung-wp.active-hitung:hover { background-color: #0f8a42; transform: translateY(-2px); }
        .btn-hitung-wp.active-history { background-color: #0288d1; cursor: pointer; pointer-events: auto; box-shadow: 0 4px 12px rgba(2, 136, 209, 0.3); }
        .btn-hitung-wp.active-history:hover { background-color: #01579b; transform: translateY(-2px); }
        
        .no-data { text-align: center; padding: 25px; color: #6c757d; font-style: italic; }
        /* Tombol Reset Tambahan */
        .btn-reset { background-color: #dc3545; color: white; padding: 12px 25px; border-radius: 8px; font-size: 14px; font-weight: bold; border: none; cursor: pointer; display: flex; align-items: center; gap: 5px; }
    </style>
</head>
<body>

    <div class="dashboard-container">
        <div class="sidebar-brand-only">
            <div class="brand-logo-bg">
                <img src="logo.png" alt="Logo SIP-BANTU TANI" class="brand-logo-img">
            </div>
            <h2 class="brand-title">SIP-BANTU TANI</h2>
            <p class="brand-subtitle">Portal Resmi Bantuan Pertanian</p>
        </div>

        <main class="main-content">
            <div class="page-header">
                <a href="admin_pengajuan.php" class="btn-back">
                    <span class="material-symbols-outlined back-icon">arrow_back</span>
                </a>
                <h1 class="page-title">Detail <?= htmlspecialchars($bantuan['nama_program']); ?></h1>
            </div>

            <div class="status-info-bar">
                <span class="info-label">Status Pendaftaran:</span>
                <span class="<?= $status_badge_class; ?>"><?= $status_pendaftaran; ?></span>
                <span class="info-label" style="margin-left: 15px;">Batas Akhir Pengajuan:</span>
                <span class="date-highlight"><?= $deadline_tampil; ?></span>
            </div>

            <p class="list-info-text">Jawaban Kuesioner Kriteria Pendaftaran Petani:</p>

            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 4%;">No</th>
                            <th style="width: 10%; text-align: left;">NIK</th>
                            <th style="width: 13%; text-align: left;">Nama Petani</th>
                            <th style="width: 11%;">Tgl Pengajuan</th>
                            <th style="width: 11%;">C1 (Luas Lahan)</th>
                            <th style="width: 11%;">C2 (Penghasilan)</th>
                            <th style="width: 11%;">C3 (Komoditas)</th>
                            <th style="width: 11%;">C4 (Kondisi Lahan)</th>
                            <th style="width: 11%;">C5 (Kepemilikan Alat)</th>
                            <th style="width: 10%;">Status Pengajuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if ($res_petani && $res_petani->num_rows > 0):
                            while ($row = $res_petani->fetch_assoc()): 
                                $komoditas_petani = getKomoditasPetani($conn, $row['nik'] ?? '', $row['c3_komoditas'] ?? '', $table_petani);
                                
                                // Format tanggal pengajuan biar seragam format Indonesia
                                $tgl_p_raw = $row['tanggal_pengajuan'] ?? '';
                                if (!empty($tgl_p_raw) && $tgl_p_raw != '0000-00-00') {
                                    $p_pecah = explode('-', $tgl_p_raw);
                                    $tgl_pengajuan_tampil = isset($p_pecah[2]) ? (int)$p_pecah[2] . ' ' . $bulan_indo[(int)$p_pecah[1]] . ' ' . $p_pecah[0] : $tgl_p_raw;
                                } else {
                                    $tgl_pengajuan_tampil = "-";
                                }

                                $status_p = trim($row['status_pengajuan'] ?? 'Diproses');
                                $class_status_p = 'status-diproses';
                                
                                if (strcasecmp($status_p, 'Diproses') == 0) {
                                    $class_status_p = 'status-diproses';
                                } elseif (strcasecmp($status_p, 'Tahap Survey') == 0 || strcasecmp($status_p, 'Survey') == 0) {
                                    $class_status_p = 'status-survey';
                                } elseif (strcasecmp($status_p, 'Diterima') == 0 || strcasecmp($status_p, 'Disetujui') == 0) {
                                    $class_status_p = 'status-diterima';
                                } elseif (strcasecmp($status_p, 'Ditolak') == 0) {
                                    $class_status_p = 'status-ditolak';
                                }
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><span style="text-align: left; font-family: monospace; color: #495057; font-size: 12px;"><?= htmlspecialchars($row['nik'] ?? '-'); ?></span></td>
                                <td style="text-align: left;"><strong><?= htmlspecialchars($row['nama_lengkap'] ?? ''); ?></strong></td>
                                <td><span class="tgl-text"><?= $tgl_pengajuan_tampil; ?></span></td>
                                <td><span class="kriteria-text"><?= htmlspecialchars($row['c1_luas_lahan'] ?? '-'); ?></span></td>
                                <td><span class="kriteria-text"><?= htmlspecialchars($row['c2_penghasilan'] ?? '-'); ?></span></td>
                                <td><span class="kriteria-text"><?= htmlspecialchars($komoditas_petani); ?></span></td>
                                <td><span class="kriteria-text"><?= htmlspecialchars($row['c4_kondisi_lahan'] ?? '-'); ?></span></td>
                                <td><span class="kriteria-text"><?= htmlspecialchars($row['c5_kepemilikan_alat'] ?? '-'); ?></span></td>
                                <td>
                                    <span class="badge-status-petani <?= $class_status_p; ?>">
                                        <?= htmlspecialchars($status_p); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php 
                            endwhile; 
                        else: 
                        ?>
                            <tr>
                                <td></td>
                                <td colspan="9" class="no-data">Belum ada pendaftar untuk program bantuan ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="spk-action-area">
                <?php if ($sudah_hitung_wp): ?>
                    <div class="info-success-hint">
                        <?= $pesan_status; ?>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <form method="POST" onsubmit="return confirm('Yakin ingin mereset data? Tindakan ini akan menghapus semua hasil perhitungan WP dan mengembalikan status petani ke Diproses.');">
                            <button type="submit" name="reset_status" class="btn-reset">
                                <span class="material-symbols-outlined">restart_alt</span> Reset
                            </button>
                        </form>
                        <a href="admin_hitungwp.php?id=<?= $bantuan_id; ?>" class="btn-hitung-wp active-history">
                            <span class="material-symbols-outlined">history</span>
                            Lihat Riwayat Perhitungan WP
                        </a>
                    </div>
                <?php else: ?>
                    <?php if (!empty($pesan_status)): ?>
                        <span class="info-hint"><?= $pesan_status; ?></span>
                    <?php endif; ?>
                    
                    <?php if ($status_pendaftaran == 'Closed'): ?>
                        <a href="admin_hitungwp.php?id=<?= $bantuan_id; ?>" class="btn-hitung-wp active-hitung">
                            <span class="material-symbols-outlined">calculate</span>
                            Mulai Perhitungan WP
                        </a>
                    <?php else: ?>
                        <a href="#" class="btn-hitung-wp" disabled>
                            <span class="material-symbols-outlined">calculate</span>
                            Mulai Perhitungan WP
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>

</body>
</html>
<?php $conn->close(); ?>