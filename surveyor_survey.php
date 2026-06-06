<?php
// 1. CEK LOGIN
session_start();
if (!isset($_SESSION['surveyor_id']) || $_SESSION['role'] !== 'surveyor') {
    header("Location: surveyor_login.php");
    exit();
}

// 2. KONEKSI DATABASE
$host = "localhost"; $user = "root"; $pass = ""; $db = "sip_bantutani";
$conn = new mysqli($host, $user, $pass, $db);

// 3. AMBIL DATA DARI URL
$nik = $_GET['nik'] ?? '';
$id_program = $_GET['id_program'] ?? '';

if (empty($nik) || empty($id_program)) {
    die("Error: Parameter NIK atau ID Program tidak ditemukan. Silakan kembali ke <a href='surveyor_tugaslapangan.php'>Tugas Lapangan</a>.");
}

// QUERY DIPERBAIKI: Mengambil NIK dengan alias dan data kriteria
$sql = "SELECT 
            ap.nik as nik_petani, ap.c1_luas_lahan as val_c1, ap.c2_penghasilan as val_c2, 
            ap.c3_komoditas as val_c3, ap.c4_kondisi_lahan as val_c4, ap.c5_kepemilikan_alat as val_c5,
            ap.status_pengajuan, ap.id_pengajuan,
            dp.nama_lengkap, dp.alamat, dp.no_telp, dp.komoditas, dp.tempat_lahir, dp.tanggal_lahir, 
            s.* FROM admin_pengajuanbantuan ap
        INNER JOIN admin_datapetani dp ON ap.nik = dp.nik
        LEFT JOIN admin_survey s ON ap.id_pengajuan = s.id_pengajuan
        WHERE ap.nik = '$nik' AND ap.id_program = '$id_program'";

$res = $conn->query($sql);
$data = $res->fetch_assoc();

$is_final = ($data && $data['status_pengajuan'] === 'Selesai');
$is_draft = ($data && $data['status_pengajuan'] === 'Draft');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SIP-BANTU TANI - Formulir Survei Lapangan</title>
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

        .main-content { flex-grow: 1; display: flex; flex-direction: column; background-color: #f8f9fa; overflow: hidden; }
        .header-section { padding: 40px 50px 20px 50px; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; z-index: 10; }
        .scrollable-area { flex-grow: 1; padding: 20px 50px 40px 50px; overflow-y: auto; }
        
        .page-title { color: #0d7839; font-size: 28px; font-weight: 700; display: flex; align-items: center; gap: 15px; }
        
        .info-box { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; border-left: 5px solid #0d7839; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .label { font-weight: bold; color: #555; width: 150px; display: inline-block; }
        .info-row { margin-bottom: 5px; }
        
        .table-survey { width: 100%; border-collapse: collapse; margin-bottom: 20px; background: white; }
        .table-survey th, .table-survey td { padding: 12px; border: 1px solid #dee2e6; text-align: left; }
        .table-survey th { background: #0d7839; color: white; }
        
        .btn-submit { background: #0d7839; color: white; padding: 12px 25px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 14px; }
        .btn-reset { background: #6c757d; color: white; padding: 12px 25px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 14px; margin-right: 10px; }
        .status-final { background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; border: 1px solid #c3e6cb; }
        .disabled-form { opacity: 0.8; pointer-events: none; }
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
            <div class="header-section">
                <h1 class="page-title">
                    <a href="surveyor_tugaslapangan.php" style="color: #0d7839; text-decoration: none;"><span class="material-symbols-outlined">arrow_back</span></a>
                    Formulir Survei Lapangan
                </h1>
            </div>
            
            <div class="scrollable-area">
                <?php if($data): ?>
                    <?php if ($is_final): ?>
                        <div class="status-final">Data sudah dikirim ke Admin (Status: Selesai). Data ini tidak dapat diubah lagi.</div>
                    <?php endif; ?>

                    <div class="info-box">
                        <div class="info-row"><span class="label">Nama Petani</span>: <?= htmlspecialchars($data['nama_lengkap']) ?></div>
                        <div class="info-row"><span class="label">NIK</span>: <?= htmlspecialchars($data['nik_petani']) ?></div>
                        <div class="info-row"><span class="label">Alamat</span>: <?= htmlspecialchars($data['alamat']) ?></div>
                        <div class="info-row"><span class="label">No. Telp</span>: <?= htmlspecialchars($data['no_telp']) ?></div>
                        <div class="info-row"><span class="label">Komoditas</span>: <?= htmlspecialchars($data['komoditas']) ?></div>
                        <div class="info-row"><span class="label">TTL</span>: <?= htmlspecialchars($data['tempat_lahir'] . ', ' . $data['tanggal_lahir']) ?></div>
                    </div>

                    <form action="surveyor_surveyproses.php" method="POST" enctype="multipart/form-data" class="<?= $is_final ? 'disabled-form' : '' ?>">
                        <input type="hidden" name="nik" value="<?= $data['nik_petani'] ?>">
                        <input type="hidden" name="id_program" value="<?= $id_program ?>">
                        
                        <table class="table-survey">
                            <thead>
                                <tr><th>Kriteria</th><th>Data Pengajuan</th><th>Validasi</th><th>Catatan</th><th>Bukti Foto</th></tr>
                            </thead>
                            <tbody>
                                <?php 
                                $kriteria_map = [
                                    'val_c1' => ['label' => 'Luas Lahan', 'v' => 'validasi_c1_luas_lahan', 'c' => 'catatan_c1_luas_lahan', 'f' => 'file_c1_luas_lahan'],
                                    'val_c2' => ['label' => 'Penghasilan', 'v' => 'validasi_c2_penghasilan', 'c' => 'catatan_c2_penghasilan', 'f' => 'file_c2_penghasilan'],
                                    'val_c3' => ['label' => 'Komoditas', 'v' => 'validasi_c3_komoditas', 'c' => 'catatan_c3_komoditas', 'f' => 'file_c3_komoditas'],
                                    'val_c4' => ['label' => 'Kondisi Lahan', 'v' => 'validasi_c4_kondisi_lahan', 'c' => 'catatan_c4_kondisi_lahan', 'f' => 'file_c4_kondisi_lahan'],
                                    'val_c5' => ['label' => 'Kepemilikan Alat', 'v' => 'validasi_c5_kepemilikan_alat', 'c' => 'catatan_c5_kepemilikan_alat', 'f' => 'file_c5_kepemilikan_alat']
                                ];
                                foreach($kriteria_map as $db_col => $col): ?>
                                    <tr>
                                        <td><?= $col['label'] ?></td>
                                        <td><?= htmlspecialchars($data[$db_col] ?? '-') ?></td>
                                        <td>
                                            <select name="<?= $col['v'] ?>" required>
                                                <option value="">-- Pilih --</option>
                                                <option value="Sesuai" <?= ($data[$col['v']] ?? '') == 'Sesuai' ? 'selected' : '' ?>>Sesuai</option>
                                                <option value="Tidak Sesuai" <?= ($data[$col['v']] ?? '') == 'Tidak Sesuai' ? 'selected' : '' ?>>Tidak Sesuai</option>
                                            </select>
                                        </td>
                                        <td><input type="text" name="<?= $col['c'] ?>" value="<?= htmlspecialchars($data[$col['c']] ?? '') ?>" style="width:100%; padding:5px;"></td>
                                        <td>
                                            <?php if(!empty($data[$col['f']])): ?>
                                                <small style="color:green">Tersimpan: <a href="uploads/<?= $data[$col['f']] ?>" target="_blank">Lihat Bukti</a></small><br>
                                            <?php endif; ?>
                                            <input type="file" name="<?= $col['f'] ?>" accept="image/*" <?= empty($data[$col['f']]) ? 'required' : '' ?>>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <?php if (!$is_final): ?>
                            <?php if ($is_draft): ?>
                                <button type="submit" name="action" value="final" class="btn-submit" onclick="return confirm('Yakin ingin mengirim? Data tidak dapat diubah setelah ini!')">Kirim ke Admin (Permanen)</button>
                                <button type="button" class="btn-reset" onclick="location.reload()">Batal Edit</button>
                            <?php else: ?>
                                <button type="submit" name="action" value="draft" class="btn-submit">Simpan Data</button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </form>
                <?php else: ?>
                    <p>Data pengajuan tidak ditemukan.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>