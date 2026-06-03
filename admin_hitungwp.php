<?php
// 1. KONEKSI DATABASE
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sip_bantutani";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Koneksi gagal: " . $conn->connect_error); }

// FUNGSI PEMBANTU
function getKomoditasWP($conn, $nik) {
    $nik = $conn->real_escape_string($nik);
    $res = $conn->query("SELECT komoditas FROM admin_datapetani WHERE nik = '$nik'");
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        return $row['komoditas'];
    }
    return "-";
}

function konversiNilaiWP($kriteria, $nilai) {
    $nilai = trim($nilai);
    if ($kriteria == 'c1') {
        if ($nilai == '> 2 Hektar' || $nilai == '1,1 - 2 Hektar') return 5;
        if ($nilai == '0,5 - 1 Hektar') return 3;
        if ($nilai == '< 0,5 Hektar') return 1;
    }
    if ($kriteria == 'c2') {
        if ($nilai == '< Rp1.000.000') return 5;
        if ($nilai == 'Rp1.000.000 - Rp2.500.000') return 3;
        if ($nilai == 'Rp2.501.000 - Rp4.000.000' || $nilai == '> Rp4.000.000') return 1;
    }
    if ($kriteria == 'c3') {
        if ($nilai == 'Padi') return 5;
        if ($nilai == 'Jagung' || $nilai == 'Cabai') return 3;
        if ($nilai == 'Kedelai' || $nilai == 'Kopi' || $nilai == '-') return 1;
    }
    if ($kriteria == 'c4') {
        if ($nilai == 'Lahan Tadah Hujan' || $nilai == 'Lahan Irigasi Teknis') return 5;
        if ($nilai == 'Lahan Kering/Gambut') return 3;
        if ($nilai == 'Lahan Kritis') return 1;
    }
    if ($kriteria == 'c5') {
        if ($nilai == 'Hanya memiliki alat manual' || $nilai == 'Tidak memiliki alat sama sekali') return 5;
        if ($nilai == 'Memiliki alat semi-mekanis') return 3;
        if ($nilai == 'Sudah memiliki alat mesin') return 1;
    }
    return 1; 
}

// 2. AMBIL ID BANTUAN
$id_bantuan = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id_bantuan == 0) {
    echo "<script>alert('Pilih program bantuan terlebih dahulu!'); window.location='admin_pengajuan.php';</script>";
    exit;
}

$res_bantuan = $conn->query("SELECT * FROM admin_programbantuan WHERE id_program = $id_bantuan");
$bantuan = $res_bantuan->fetch_assoc();
if (!$bantuan) die("Data program bantuan tidak ditemukan.");

$w1 = (float)$bantuan['c1_luas']; $w2 = (float)$bantuan['c2_penghasilan']; $w3 = (float)$bantuan['c3_komoditas']; $w4 = (float)$bantuan['c4_kondisi']; $w5 = (float)$bantuan['c5_alat'];
$n_w1 = $w1; $n_w2 = -$w2; $n_w3 = $w3; $n_w4 = $w4; $n_w5 = -$w5;

// 3. PROSES HITUNG WEIGHTED PRODUCT
$sql_pendaftar = "SELECT ap.*, dp.nama_lengkap FROM admin_pengajuanbantuan ap INNER JOIN admin_datapetani dp ON ap.nik = dp.nik WHERE ap.id_program = $id_bantuan ORDER BY ap.id_pengajuan ASC";
$res_pendaftar = $conn->query($sql_pendaftar);
$alternatif = []; $total_vektor_s = 0; $sudah_dikirim = false;

if ($res_pendaftar && $res_pendaftar->num_rows > 0) {
    while ($row = $res_pendaftar->fetch_assoc()) {
        $komoditas_petani = getKomoditasWP($conn, $row['nik'] ?? '');
        $nc1 = konversiNilaiWP('c1', $row['c1_luas_lahan'] ?? '');
        $nc2 = konversiNilaiWP('c2', $row['c2_penghasilan'] ?? '');
        $nc3 = konversiNilaiWP('c3', $komoditas_petani);
        $nc4 = konversiNilaiWP('c4', $row['c3_kondisi_lahan'] ?? ''); 
        $nc5 = konversiNilaiWP('c5', $row['c4_kepemilikan_alat'] ?? '');

        $vs1 = pow($nc1, $n_w1); $vs2 = pow($nc2, $n_w2); $vs3 = pow($nc3, $n_w3); $vs4 = pow($nc4, $n_w4); $vs5 = pow($nc5, $n_w5);
        $vektor_s = $vs1 * $vs2 * $vs3 * $vs4 * $vs5;
        
        $alternatif[] = ['nik' => $row['nik'], 'nama' => $row['nama_lengkap'], 'status_sekarang' => $row['status_pengajuan'] ?? 'Diproses', 'vektor_s' => $vektor_s, 'detail' => ['c1'=>[$nc1,$n_w1,$vs1,($nc1*$n_w1)], 'c2'=>[$nc2,$n_w2,$vs2,($nc2*$n_w2)], 'c3'=>[$nc3,$n_w3,$vs3,($nc3*$n_w3)], 'c4'=>[$nc4,$n_w4,$vs4,($nc4*$n_w4)], 'c5'=>[$nc5,$n_w5,$vs5,($nc5*$n_w5)]]];
        $total_vektor_s += $vektor_s;
    }
    if (!empty($alternatif)) {
        $conn->query("DELETE FROM admin_hasilwp WHERE id_program = $id_bantuan");
        $rank_counter = 1;
        foreach ($alternatif as &$alt) {
            $alt['vektor_v'] = $total_vektor_s > 0 ? ($alt['vektor_s'] / $total_vektor_s) : 0;
            $sql_insert = "INSERT INTO admin_hasilwp (id_program, nik, vektor_s, vektor_v, ranking, c1_skor, c1_bobot, c1_vs, c1_vk, c2_skor, c2_bobot, c2_vs, c2_vk, c3_skor, c3_bobot, c3_vs, c3_vk, c4_skor, c4_bobot, c4_vs, c4_vk, c5_skor, c5_bobot, c5_vs, c5_vk) VALUES ($id_bantuan, '{$alt['nik']}', {$alt['vektor_s']}, {$alt['vektor_v']}, $rank_counter, {$alt['detail']['c1'][0]}, {$alt['detail']['c1'][1]}, {$alt['detail']['c1'][2]}, {$alt['detail']['c1'][3]}, {$alt['detail']['c2'][0]}, {$alt['detail']['c2'][1]}, {$alt['detail']['c2'][2]}, {$alt['detail']['c2'][3]}, {$alt['detail']['c3'][0]}, {$alt['detail']['c3'][1]}, {$alt['detail']['c3'][2]}, {$alt['detail']['c3'][3]}, {$alt['detail']['c4'][0]}, {$alt['detail']['c4'][1]}, {$alt['detail']['c4'][2]}, {$alt['detail']['c4'][3]}, {$alt['detail']['c5'][0]}, {$alt['detail']['c5'][1]}, {$alt['detail']['c5'][2]}, {$alt['detail']['c5'][3]})";
            $conn->query($sql_insert);
            $rank_counter++;
        }
        usort($alternatif, function($a, $b) { return $b['vektor_v'] <=> $a['vektor_v']; });
    }
}
$check = $conn->query("SELECT id_pengajuan FROM admin_pengajuanbantuan WHERE id_program = $id_bantuan AND status_pengajuan = 'Tahap Survey'");
if ($check && $check->num_rows > 0) $sudah_dikirim = true;

// ... kode yang sudah ada ...
$check = $conn->query("SELECT id_pengajuan FROM admin_pengajuanbantuan WHERE id_program = $id_bantuan AND status_pengajuan = 'Tahap Survey'");
if ($check && $check->num_rows > 0) $sudah_dikirim = true;

// GANTI LOGIKA POST INI DI admin_hitungwp.php:
if (isset($_POST['kirim_surveyor']) && !$sudah_dikirim) {
    $top_3 = array_slice($alternatif, 0, 3);
    foreach ($top_3 as $farmer) {
        $nik_survey = $farmer['nik'];
        
        // 1. Update status pengajuan
        $conn->query("UPDATE admin_pengajuanbantuan SET status_pengajuan = 'Tahap Survey' WHERE id_program = $id_bantuan AND nik = '$nik_survey'");
        
        // 2. Simpan notifikasi ke database agar bisa dibaca petani
        $pesan = "Selamat! Pengajuan Anda telah berhasil diproses ke Tahap Survey.";
        $conn->query("INSERT INTO admin_notifikasi (nik, pesan) VALUES ('$nik_survey', '$pesan')");
    }
    echo "<script>alert('Berhasil! Status diupdate dan notifikasi dikirim ke petani.'); window.location.href='admin_hitungwp.php?id=$id_bantuan';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SIP-BANTU TANI - Hasil Analisis WP</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fbc02d; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        
        .dashboard-container { width: 100%; max-width: 1280px; height: 850px; max-height: 92vh; background-color: white; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); display: flex; overflow: hidden; }
        
        .sidebar-brand-only { width: 280px; background-color: #0d7839; color: white; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 0; flex-shrink: 0; }
        .brand-logo-bg { width: 90px; height: 90px; background-color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; overflow: hidden; border: 2px solid #13a851; margin-bottom: 12px; }
        .brand-logo-img { width: 100%; height: 100%; object-fit: contain; }
        .brand-title { font-size: 16px; font-weight: bold; text-align: center; margin-bottom: 4px; letter-spacing: 0.5px; }
        .brand-subtitle { font-size: 11px; color: #d1e7dd; text-align: center; font-weight: 500; }

        .main-content { flex-grow: 1; padding: 30px 40px; overflow-y: auto; }
        .page-header { margin-bottom: 20px; border-bottom: 1px solid #dee2e6; padding-bottom: 15px; display: flex; align-items: center; gap: 15px; }
        .page-title { color: #0d7839; font-size: 28px; font-weight: 700; }
        .btn-back { color: #0d7839; display: flex; align-items: center; text-decoration: none; }
        
        .data-table-container { border: 1px solid #dee2e6; border-radius: 8px; overflow-x: auto; margin-bottom: 20px; }
        .data-table { width: 100%; border-collapse: collapse; text-align: center; font-size: 14px; }
        .data-table th { background-color: #f8f9fa; color: #0d7839; padding: 16px; border-bottom: 2px solid #dee2e6; }
        .data-table td { padding: 16px; border-bottom: 1px solid #dee2e6; }
        .score-badge { font-weight: bold; color: #0d7839; background: #e6f4ea; padding: 4px 8px; border-radius: 4px; }
        .status-badge { font-size: 11px; padding: 4px 10px; border-radius: 20px; font-weight: bold; }
        .status-survey { background-color: #e3f2fd; color: #0d47a1; }
        .status-proses { background-color: #fff3e0; color: #e65100; }
        .btn-detail { background-color: #0d7839; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; }
        .surveyor-section { background: #f8f9fa; border: 1px solid #e0e0e0; border-radius: 10px; padding: 20px; }
        .surveyor-item { background: white; padding: 10px; border-radius: 6px; margin-bottom: 6px; border-left: 5px solid #13a851; display: flex; justify-content: space-between; }
        .btn-kirim-surveyor { background-color: #0288d1; color: white; padding: 12px; border-radius: 8px; border: none; cursor: pointer; }
    </style>
</head>
<body>

    <div class="dashboard-container">
        <div class="sidebar-brand-only">
            <div class="brand-logo-bg">
                <img src="logo.png" alt="Logo" class="brand-logo-img">
            </div>
            <h2 class="brand-title">SIP-BANTU TANI</h2>
            <p class="brand-subtitle">Portal Resmi Bantuan Pertanian</p>
            </div>
        
        <main class="main-content">
            <div class="page-header">
                <a href="admin_detailpengajuan.php" class="btn-back"><span class="material-symbols-outlined" style="font-size:32px;">arrow_back</span></a>
                <h1 class="page-title">Hasil Perhitungan WP</h1>
            </div>
            
            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr><th>Rank</th><th>NIK</th><th>Nama Petani</th><th>Vektor S</th><th>Vektor V</th><th>Status</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php 
                        $rank = 1;
                        if (!empty($alternatif)):
                            foreach ($alternatif as $row): 
                                $badge = ($row['status_sekarang'] == 'Tahap Survey') ? 'status-survey' : 'status-proses';
                        ?>
                            <tr>
                                <td><?= $rank++; ?></td>
                                <td><?= htmlspecialchars($row['nik']); ?></td>
                                <td><?= htmlspecialchars($row['nama']); ?></td>
                                <td><?= number_format($row['vektor_s'], 4); ?></td>
                                <td><span class="score-badge"><?= number_format($row['vektor_v'], 4); ?></span></td>
                                <td><span class="status-badge <?= $badge; ?>"><?= htmlspecialchars($row['status_sekarang']); ?></span></td>
                                <td><a href="admin_wpdetail.php?nik=<?= $row['nik']; ?>&id_program=<?= $id_bantuan; ?>" class="btn-detail">Detail</a></td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="7">Data tidak ditemukan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($alternatif)): ?>
                <form method="POST" action="">
                    <div class="surveyor-section">
                        <h3>Rekomendasi 3 Besar</h3>
                        <div class="surveyor-list" style="margin-top:10px;">
                            <?php 
                            $top_3 = array_slice($alternatif, 0, 3);
                            foreach ($top_3 as $top_farmer): ?>
                                <div class="surveyor-item">
                                    <span><?= htmlspecialchars($top_farmer['nama']); ?></span>
                                    <span>Vektor V: <?= number_format($top_farmer['vektor_v'], 4); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if ($sudah_dikirim): ?>
                            <p style="margin-top:15px; color:#0d7839; font-weight:bold;">✓ Data telah dikirim ke tim Surveyor.</p>
                        <?php else: ?>
                            <button type="submit" name="kirim_surveyor" class="btn-kirim-surveyor" style="margin-top:15px;">Kirim ke Surveyor</button>
                        <?php endif; ?>
                    </div>
                </form>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
<?php $conn->close(); ?>