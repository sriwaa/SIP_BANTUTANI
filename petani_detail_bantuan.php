<?php
session_start();
include 'koneksi.php'; 

if (!isset($_SESSION['nik'])) {
    header("Location: petani_login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = "SELECT * FROM admin_programbantuan WHERE id_program = $id";
$result = $conn->query($query);
$bantuan = $result->fetch_assoc();
if (!$bantuan) { die("Data tidak ditemukan!"); }

// --- BAGIAN TAMBAHAN UNTUK CEK DEADLINE ---
$today = date('Y-m-d');
$batas_akhir = $bantuan['batas_akhir'];
$is_expired = ($today > $batas_akhir);
// ------------------------------------------

// --- CEK APAKAH SUDAH PERNAH MENGAJUKAN ---
$nik_login = $_SESSION['nik'];
$sql_cek = "SELECT status_pengajuan FROM admin_pengajuanbantuan WHERE nik = '$nik_login' AND id_program = $id";
$res_cek = $conn->query($sql_cek);
$sudah_aju = $res_cek->fetch_assoc();
// ------------------------------------------
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Bantuan - <?php echo $bantuan['nama_program']; ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fbc02d; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .dashboard-container { width: 100%; max-width: 1280px; height: 850px; max-height: 92vh; background-color: white; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); display: flex; overflow: hidden; }

        /* SIDEBAR BRAND ONLY */
        .sidebar-brand-only { width: 280px; background-color: #0d7839; color: white; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 0; flex-shrink: 0; }
        .sidebar-logo-area { display: flex; flex-direction: column; align-items: center; text-align: center; padding: 0 20px; }
        .sidebar-logo-bg { width: 110px; height: 110px; background-color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; overflow: hidden; border: 2px solid #13a851; margin-bottom: 20px; }
        .sidebar-logo-img { width: 100%; height: 100%; object-fit: cover; }
        .sidebar-title { font-size: 20px; font-weight: bold; letter-spacing: 0.5px; margin-bottom: 6px; }
        .sidebar-subtitle { font-size: 12px; color: #d1e7dd; font-weight: 500; }

        /* MAIN CONTENT */
        .main-content { flex-grow: 1; display: flex; flex-direction: column; overflow: hidden; }
        .header-section { padding: 40px 50px 20px 50px; background: white; }
        .back-nav { display: flex; align-items: center; gap: 12px; color: #0d7839; text-decoration: none; font-size: 28px; font-weight: 700; margin-bottom: 5px; }
        .scrollable-content { flex-grow: 1; padding: 0 50px 40px 50px; overflow-y: auto; }
        .detail-box-container { border: 1px solid #dee2e6; border-radius: 12px; padding: 35px; width: 100%; }
        
        .img-detail { width: 120px; height: 120px; object-fit: cover; border-radius: 10px; margin-left: 20px; border: 1px solid #eee; }
        .custom-select { border: 2px solid #0d7839; background-color: #ffffff; color: #333; font-weight: 600; cursor: pointer; }
        .btn-ajukan { background: #0d7839; color: white; width: 100%; padding: 15px; border-radius: 12px; border: none; font-weight: bold; margin-top: 20px; }
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
            <a href="petani_sip.php" class="back-nav"><span class="material-symbols-outlined">arrow_back</span> Detail Bantuan</a>
        </div>

        <div class="scrollable-content">
            <div class="detail-box-container">
                <div class="d-flex align-items-start mb-4">
                    <div class="flex-grow-1">
                        <h4 class="fw-bold"><?php echo $bantuan['nama_program']; ?></h4>
                        <p class="text-muted" style="font-size: 13px;">Bantuan untuk kebutuhan <?php echo strtolower($bantuan['nama_program']); ?> bagi petani.</p>
                    </div>
                    <img src="images/<?php echo $bantuan['gambar']; ?>" class="img-detail" alt="Foto">
                </div>

                <div style="background: #f8f9fa; padding: 15px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #dee2e6;">
                    <h6 class="fw-bold text-success" style="font-size: 14px;"><i class="fa-solid fa-file-lines me-2"></i> Deskripsi</h6>
                    <p style="font-size: 13px; color: #555; text-align: justify; margin: 0;"><?php echo $bantuan['deskripsi']; ?></p>
                </div>

                <h6 class="fw-bold mb-3" style="font-size: 14px;"><i class="fa-solid fa-tag me-2"></i> Lengkapi Data Kriteria</h6>
                <form action="proses_pengajuan.php" method="POST">
                    <input type="hidden" name="id_program" value="<?php echo $id; ?>">

                    <div class="mb-3">
                        <label class="fw-bold" style="font-size: 12px;">1. Luas Lahan</label>
                        <select name="c1_luas" class="form-select form-select-sm custom-select" required <?php echo $sudah_aju ? 'disabled' : ''; ?>>
                            <option value="">-- Pilih Luas Lahan --</option>
                            <option value="1">< 0,5 Hektar</option>
                            <option value="2">0,5 - 1 Hektar</option>
                            <option value="3">1,1 - 2 Hektar</option>
                            <option value="4">> 2 Hektar</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold" style="font-size: 12px;">2. Penghasilan</label>
                        <select name="c2_penghasilan" class="form-select form-select-sm custom-select" required <?php echo $sudah_aju ? 'disabled' : ''; ?>>
                            <option value="">-- Pilih Penghasilan --</option>
                            <option value="4">< Rp1.000.000</option>
                            <option value="3">Rp1.000.000 - Rp2.500.000</option>
                            <option value="2">Rp2.501.000 - Rp4.000.000</option>
                            <option value="1">> Rp4.000.000</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold" style="font-size: 12px;">3. Komoditas</label>
                        <select name="c3_komoditas" class="form-select form-select-sm custom-select" required <?php echo $sudah_aju ? 'disabled' : ''; ?>>
                            <option value="">-- Pilih Komoditas --</option>
                            <option value="1">Padi</option>
                            <option value="2">Palawija</option>
                            <option value="3">Hortikultura</option>
                            <option value="4">Perkebunan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold" style="font-size: 12px;">4. Kondisi Lahan</label>
                        <select name="c4_kondisi" class="form-select form-select-sm custom-select" required <?php echo $sudah_aju ? 'disabled' : ''; ?>>
                            <option value="">-- Pilih Kondisi Lahan --</option>
                            <option value="1">Irigasi Teknis</option>
                            <option value="2">Tadah Hujan</option>
                            <option value="3">Kering/Gambut</option>
                            <option value="4">Kritis</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold" style="font-size: 12px;">5. Kepemilikan Alat</label>
                        <select name="c5_alat" class="form-select form-select-sm custom-select" required <?php echo $sudah_aju ? 'disabled' : ''; ?>>
                            <option value="">-- Pilih Kepemilikan Alat --</option>
                            <option value="4">Tidak punya alat</option>
                            <option value="3">Alat Manual</option>
                            <option value="2">Semi-mekanis</option>
                            <option value="1">Mesin</option>
                        </select>
                    </div>

                    <?php if ($sudah_aju): ?>
                        <button type="button" class="btn btn-warning" style="width: 100%; padding: 15px; border-radius: 12px; font-weight: bold; cursor: default; margin-top: 20px;">
                            Anda sudah mengajukan bantuan ini (Status: <?php echo $sudah_aju['status_pengajuan']; ?>)
                        </button>
                    <?php elseif ($is_expired): ?>
                        <button type="button" class="btn btn-secondary" style="width: 100%; padding: 15px; border-radius: 12px; font-weight: bold; cursor: not-allowed; margin-top: 20px;">
                            Periode Pengajuan Berakhir (<?php echo $batas_akhir; ?>)
                        </button>
                    <?php else: ?>
                        <button type="submit" class="btn-ajukan">Ajukan Bantuan Sekarang</button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </main>
</div>
</body>
</html>