<?php
// 1. KONEKSI DATABASE
$conn = new mysqli("localhost", "root", "", "sip_bantutani");
if ($conn->connect_error) { die("Koneksi gagal: " . $conn->connect_error); }

$id_pengajuan = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 2. QUERY AMBIL DATA LENGKAP
$sql = "SELECT ap.*, dp.*, pb.nama_program, hw.*, s.*
        FROM admin_pengajuanbantuan ap
        JOIN admin_datapetani dp ON ap.nik = dp.nik
        JOIN admin_programbantuan pb ON ap.id_program = pb.id_program
        LEFT JOIN admin_hasilwp hw ON ap.nik = hw.nik AND ap.id_program = pb.id_program
        LEFT JOIN admin_survey s ON ap.id_pengajuan = s.id_pengajuan
        WHERE ap.id_pengajuan = $id_pengajuan";

$res = $conn->query($sql);
$data = $res->fetch_assoc();

if (!$data) { echo "<script>alert('Data tidak ditemukan!'); window.location='admin_survey.php';</script>"; exit; }

// Mapping Kriteria
$kriteria = [
    ['k' => 'c1', 'label' => 'Luas Lahan',      'input' => 'c1_luas_lahan'],
    ['k' => 'c2', 'label' => 'Penghasilan',     'input' => 'c2_penghasilan'],
    ['k' => 'c3', 'label' => 'Komoditas',       'input' => 'c3_komoditas'],
    ['k' => 'c4', 'label' => 'Kondisi Lahan',   'input' => 'c4_kondisi_lahan'],
    ['k' => 'c5', 'label' => 'Kepemilikan Alat','input' => 'c5_kepemilikan_alat']
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SIP-BANTU TANI - Detail Laporan</title>
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
        .detail-box-container { border: 1px solid #dee2e6; border-radius: 12px; padding: 35px; width: 100%; }
        .section-title { font-size: 16px; font-weight: 700; color: #0d7839; margin: 30px 0 20px 0; border-bottom: 2px solid #e2f3e9; padding-bottom: 5px; display: flex; align-items: center; gap: 8px; }
        .table-profile { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table-profile th, .table-profile td { padding: 12px; border: 1px solid #dee2e6; font-size: 14px; text-align: left; }
        .table-profile th { background: #f8f9fa; width: 25%; color: #333; }
        .table-survey { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table-survey th, .table-survey td { padding: 12px; border: 1px solid #dee2e6; text-align: left; font-size: 13px; }
        .table-survey th { background: #0d7839; color: white; }
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
                <a href="javascript:history.back()" class="back-nav"><span class="material-symbols-outlined">arrow_back</span> Detail Laporan</a>
                <p class="sub-header-text">Analisis Data Petani & Hasil Survey</p>
            </div>

            <div class="scrollable-content">
                <div class="detail-box-container">
                    <div class="section-title"><span class="material-symbols-outlined">person</span> Profil Lengkap Petani</div>
                    <table class="table-profile">
                        <tr><th>Nama Lengkap</th><td><?= $data['nama_lengkap'] ?? '-' ?></td><th>NIK</th><td><?= $data['nik'] ?? '-' ?></td></tr>
                        <tr><th>Program</th><td><?= $data['nama_program'] ?? '-' ?></td><th>Komoditas</th><td><?= $data['komoditas'] ?? '-' ?></td></tr>
                        <tr><th>TTL</th><td><?= ($data['tempat_lahir'] ?? '-') . ', ' . ($data['tanggal_lahir'] ?? '-') ?></td><th>No. Telepon</th><td><?= $data['no_telp'] ?? '-' ?></td></tr>
                        <tr><th>Alamat</th><td colspan="3"><?= $data['alamat'] ?? '-' ?></td></tr>
                    </table>

                    <div class="section-title"><span class="material-symbols-outlined">calculate</span> Perhitungan Weighted Product (WP)</div>
                    <table class="table-survey">
                        <tr><th>Kriteria</th><th>Skor</th><th>Bobot</th><th>V<sub>s</sub></th><th>V<sub>k</sub></th></tr>
                        <?php foreach($kriteria as $item): $k = $item['k']; ?>
                        <tr>
                            <td><?= $item['label'] ?></td>
                            <td><?= $data[$k.'_skor'] ?? '-' ?></td>
                            <td><?= $data[$k.'_bobot'] ?? '-' ?></td>
                            <td><?= $data[$k.'_vs'] ?? '-' ?></td>
                            <td><?= $data[$k.'_vk'] ?? '-' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                    <p style="margin-top:10px; font-weight:bold;">Vektor V Total: <?= $data['vektor_v'] ?? '-' ?> | Ranking: <?= $data['ranking'] ?? '-' ?></p>

                    <div class="section-title"><span class="material-symbols-outlined">assignment_turned_in</span> Hasil Survey (Surveyor: <?= $data['nama_surveyor'] ?? '-' ?>)</div>
                    <table class="table-survey">
                        <thead><tr><th>Kriteria</th><th>Validasi</th><th>Catatan</th><th>Bukti Foto</th></tr></thead>
                        <tbody>
                            <?php foreach($kriteria as $item): 
                                $v = 'validasi_'.$item['input']; $c = 'catatan_'.$item['input']; $f = 'file_'.$item['input'];
                            ?>
                            <tr>
                                <td><?= $item['label'] ?></td>
                                <td><?= $data[$v] ?? '-' ?></td>
                                <td><?= $data[$c] ?? '-' ?></td>
                                <td><?= !empty($data[$f]) ? "<a href='uploads/".$data[$f]."' target='_blank'>Lihat Foto</a>" : '-' ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
<?php $conn->close(); ?>