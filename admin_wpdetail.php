<?php
// 1. KONEKSI DATABASE
$host = "localhost"; $user = "root"; $pass = ""; $db = "sip_bantutani";
$conn = new mysqli($host, $user, $pass, $db);

// 2. AMBIL DATA DARI URL
$nik = $_GET['nik'] ?? '';
$id_program = $_GET['id_program'] ?? '';
// 3. AMBIL DATA
// Menggunakan subquery untuk menghitung ranking secara dinamis berdasarkan vektor_v
$sql = "SELECT ap.*, dp.nama_lengkap, dp.no_telp, dp.alamat, dp.komoditas,
               hw.c1_skor, hw.c1_bobot, hw.c1_vs, hw.c1_vk,
               hw.c2_skor, hw.c2_bobot, hw.c2_vs, hw.c2_vk,
               hw.c3_skor, hw.c3_bobot, hw.c3_vs, hw.c3_vk,
               hw.c4_skor, hw.c4_bobot, hw.c4_vs, hw.c4_vk,
               hw.c5_skor, hw.c5_bobot, hw.c5_vs, hw.c5_vk,
               hw.vektor_s, hw.vektor_v,
               (SELECT COUNT(*)+1 FROM admin_hasilwp hw2 
                WHERE hw2.vektor_v > hw.vektor_v AND hw2.id_program = hw.id_program) AS ranking
        FROM admin_pengajuanbantuan ap
        INNER JOIN admin_datapetani dp ON ap.nik = dp.nik
        LEFT JOIN admin_hasilwp hw ON ap.nik = hw.nik AND ap.id_program = hw.id_program
        WHERE ap.nik = '$nik' AND ap.id_program = '$id_program'";

$res = $conn->query($sql);
$data = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP-BANTU TANI - Detail Perhitungan WP</title>
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
        .main-content { flex-grow: 1; background-color: white; padding: 30px 40px; overflow-y: auto; display: flex; flex-direction: column; }
        .page-header { margin-bottom: 20px; border-bottom: 1px solid #dee2e6; padding-bottom: 15px; display: flex; align-items: center; gap: 15px; }
        .btn-back { color: #0d7839; text-decoration: none; display: flex; align-items: center; }
        .back-icon { font-size: 32px; font-weight: bold; }
        .page-title { color: #0d7839; font-size: 28px; font-weight: 700; }
        .info-box { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; border-left: 5px solid #0d7839; }
        .info-row { margin-bottom: 8px; }
        .label { font-weight: bold; color: #555; width: 150px; display: inline-block; }
        .table-detail { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px; }
        .table-detail th { background: #0d7839; color: white; padding: 12px; text-align: left; }
        .table-detail td { padding: 12px; border: 1px solid #dee2e6; }
        .result-box { background: #e3f2fd; padding: 15px; border-radius: 8px; font-weight: bold; display: flex; gap: 20px; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar-brand-only">
            <div class="brand-logo-bg"><img src="logo.png" class="brand-logo-img"></div>
            <h2 class="brand-title">SIP-BANTU TANI</h2>
            <p class="brand-subtitle">Portal Resmi Bantuan Pertanian</p>
        </div>
        <main class="main-content">
            <div class="page-header">
                <a href="admin_hitungwp.php?id=<?= $id_program ?>" class="btn-back"><span class="material-symbols-outlined back-icon">arrow_back</span></a>
                <h1 class="page-title">Detail Perhitungan WP</h1>
            </div>
            <?php if($data): ?>
                <div class="info-box">
                    <div class="info-row"><span class="label">Nama Lengkap</span>: <?= htmlspecialchars($data['nama_lengkap'] ?? '-') ?></div>
                    <div class="info-row"><span class="label">NIK</span>: <?= htmlspecialchars($data['nik'] ?? '-') ?></div>
                    <div class="info-row"><span class="label">No. Telp</span>: <?= htmlspecialchars($data['no_telp'] ?? '-') ?></div>
                    <div class="info-row"><span class="label">Alamat</span>: <?= htmlspecialchars($data['alamat'] ?? '-') ?></div>
                    <div class="info-row"><span class="label">Komoditas</span>: <?= htmlspecialchars($data['komoditas'] ?? '-') ?></div>
                </div>

                <h3>Data Perhitungan WP</h3><br>
                <table class="table-detail">
                    <thead>
                        <tr><th>Kriteria</th><th>Jawaban</th><th>Skor</th><th>Bobot</th><th>V<sub>s</sub></th><th>V<sub>k</sub></th></tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Menambahkan kunci kolom jawaban dari tabel admin_pengajuanbantuan
                        $kriteria = [
                            ['Luas Lahan', 'c1_luas_lahan', 'c1_skor', 'c1_bobot', 'c1_vs', 'c1_vk'],
                            ['Penghasilan', 'c2_penghasilan', 'c2_skor', 'c2_bobot', 'c2_vs', 'c2_vk'],
                            ['Komoditas', 'c3_komoditas', 'c3_skor', 'c3_bobot', 'c3_vs', 'c3_vk'],
                            ['Kondisi Lahan', 'c4_kondisi_lahan', 'c4_skor', 'c4_bobot', 'c4_vs', 'c4_vk'],
                            ['Kepemilikan Alat', 'c5_kepemilikan_alat', 'c5_skor', 'c5_bobot', 'c5_vs', 'c5_vk']
                        ];
                        foreach($kriteria as $k): ?>
                            <tr>
                                <td><?= $k[0] ?></td>
                                <td><?= htmlspecialchars($data[$k[1]] ?? '-') ?></td>
                                <td><?= htmlspecialchars($data[$k[2]] ?? '-') ?></td>
                                <td><?= htmlspecialchars($data[$k[3]] ?? '-') ?></td>
                                <td><?= htmlspecialchars($data[$k[4]] ?? '-') ?></td>
                                <td><?= htmlspecialchars($data[$k[5]] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="result-box">
                    <span>Vektor S: <?= htmlspecialchars($data['vektor_s'] ?? '-') ?></span>
                    <span>Vektor V: <?= htmlspecialchars($data['vektor_v'] ?? '-') ?></span>
                    <span>Ranking: Ke-<?= htmlspecialchars($data['ranking'] ?? '-') ?></span>
                </div>
            <?php else: ?>
                <p>Data tidak ditemukan.</p>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>