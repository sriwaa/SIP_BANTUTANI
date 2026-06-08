<?php
session_start();
include 'koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = $conn->query("SELECT r.*, p.nama_program, p.gambar 
                       FROM admin_pengajuanbantuan r 
                       JOIN admin_programbantuan p ON r.id_program = p.id_program 
                       WHERE r.id_pengajuan = '$id'");
$data = $query->fetch_assoc();

if (!$data) {
    die("Data tidak ditemukan.");
}

$status = $data['status_pengajuan'];
$status_class = "status-diproses";
if ($status == "Tahap Survey") $status_class = "status-survey";
elseif ($status == "Diterima") $status_class = "status-diterima";
elseif ($status == "Selesai") $status_class = "status-selesai";
elseif ($status == "Ditolak") $status_class = "status-ditolak";

$mapping = [
    'c1_luas_lahan' => [1 => "< 1 Hektar", 2 => "1 - 2 Hektar", 3 => "> 2 Hektar"],
    'c2_penghasilan' => [1 => "< Rp1.000.000", 2 => "Rp1.000.000 - Rp2.000.000", 3 => "Rp2.000.000 - Rp3.000.000", 4 => "> Rp3.000.000"],
    'c3_komoditas' => [1 => "Padi", 2 => "Palawija", 3 => "Hortikultura"],
    'c4_kondisi_lahan' => [1 => "Lahan Irigasi Teknis", 2 => "Lahan Tadah Hujan", 3 => "Lahan Kering"],
    'c5_kepemilikan_alat' => [1 => "Traktor", 2 => "Pompa Air", 3 => "Tidak memiliki alat sama sekali"]
];

function getLabel($field, $val, $mapping) {
    return isset($mapping[$field][$val]) ? $mapping[$field][$val] : $val;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pengajuan - SIP-BANTU TANI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        .scrollable-content { flex-grow: 1; padding: 0 50px 40px 50px; overflow-y: auto; }
        
        .section-title { font-size: 18px; color: #333; margin: 25px 0 15px 0; border-left: 4px solid #0d7839; padding-left: 10px; }
        .detail-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .detail-table th, .detail-table td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        .detail-table th { color: #555; width: 40%; font-weight: 600; background-color: #fafafa; }
        .detail-table td { color: #333; font-weight: 500; }
        
        .status-badge { padding: 8px 20px; border-radius: 10px; font-size: 15px; font-weight: bold; text-transform: uppercase; }
        .status-diproses { background: #fff3e0; color: #ef6c00; }
        .status-survey { background: #f3e5f5; color: #7b1fa2; }
        .status-diterima { background: #e3f2fd; color: #1976d2; }
        .status-selesai { background: #e8f5e9; color: #2e7d32; }
        .status-ditolak { background: #ffebee; color: #c62828; }

        .notification-box { background-color: #f1f8e9; border: 1px solid #c5e1a5; padding: 20px; border-radius: 10px; margin-top: 30px; }
        .notif-title { color: #2e7d32; margin-bottom: 10px; font-size: 16px; }
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
            <a href="petani_riwayat.php" class="back-nav"><span class="material-symbols-outlined" style="font-size: 32px;">arrow_back</span> Detail Pengajuan</a>
        </div>

        <div class="scrollable-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <h3 style="margin:0; font-size: 22px;">Program: <?php echo $data['nama_program']; ?></h3>
                <span class="status-badge <?php echo $status_class; ?>"><?php echo $status; ?></span>
            </div>

            <h4 class="section-title">Informasi Dasar</h4>
            <table class="detail-table">
                <tr><th>ID Pengajuan</th><td><?php echo $data['id_pengajuan']; ?></td></tr>
                <tr><th>Tanggal Pengajuan</th><td><?php echo $data['tanggal_pengajuan']; ?></td></tr>
            </table>

            <h4 class="section-title">Jawaban Kriteria</h4>
            <table class="detail-table">
                <tr><th>Luas Lahan</th><td><?php echo getLabel('c1_luas_lahan', $data['c1_luas_lahan'], $mapping); ?></td></tr>
                <tr><th>Penghasilan</th><td><?php echo getLabel('c2_penghasilan', $data['c2_penghasilan'], $mapping); ?></td></tr>
                <tr><th>Komoditas</th><td><?php echo getLabel('c3_komoditas', $data['c3_komoditas'], $mapping); ?></td></tr>
                <tr><th>Kondisi Lahan</th><td><?php echo getLabel('c4_kondisi_lahan', $data['c4_kondisi_lahan'], $mapping); ?></td></tr>
                <tr><th>Kepemilikan Alat</th><td><?php echo getLabel('c5_kepemilikan_alat', $data['c5_kepemilikan_alat'], $mapping); ?></td></tr>
            </table>

            <div class="notification-box">
                <h4 class="notif-title"><i class="fa-solid fa-circle-info"></i> Panduan Selanjutnya</h4>
                <p style="color: #333; line-height: 1.6;">
                    <?php
                    if ($status == "Tahap Survey") {
                        echo "<strong>Status: Tahap Survey.</strong> Petugas akan segera datang ke lokasi Anda. Mohon pastikan lahan siap dan dokumen pendukung tersedia untuk diverifikasi.";
                    } elseif ($status == "Selesai") {
                        echo "<strong>Status: Selesai Tahap Survey.</strong> Proses pengecekan lapangan telah selesai. Saat ini data Anda sedang dalam tahap evaluasi akhir oleh tim. Mohon tunggu notifikasi selanjutnya mengenai hasil pengajuan.";
                    } elseif ($status == "Diterima") {
                        echo "<strong>Status: Diterima.</strong> Selamat! Pengajuan Anda disetujui. Silakan cek informasi pengambilan bantuan di halaman utama.";
                        echo '<br><br><a href="petani_dashboard.php" style="background-color: #2e7d32; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;"><i class="fa-solid fa-house"></i> Cek Dashboard Utama</a>';
                    } elseif ($status == "Ditolak") {
                        echo "<strong>Status: Ditolak.</strong> Mohon maaf, pengajuan Anda belum memenuhi kriteria program ini. Silakan periksa kembali kriteria atau mencoba program bantuan lainnya.";
                    } else {
                        echo "<strong>Status: $status.</strong> Mohon tunggu pembaruan informasi dari sistem secara berkala.";
                    }
                    ?>
                </p>
            </div>
        </div>
    </main>
</div>

</body>
</html>