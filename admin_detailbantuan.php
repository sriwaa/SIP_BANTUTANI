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

// 2. AMBIL ID BANTUAN DARI URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID Bantuan tidak ditemukan!'); window.location='admin_bantuan.php';</script>";
    exit;
}

$id_bantuan = (int)$_GET['id'];

// 3. PROSES UPDATE DATA (Jika Form Edit Disubmit)
if (isset($_POST['update_bantuan'])) {
    $nama_bantuan   = $_POST['nama_bantuan'];
    $batas_akhir    = $_POST['batas_akhir'];
    $status         = $_POST['status'];
    $gambar         = $_POST['gambar'];
    $deskripsi      = $_POST['deskripsi'];
    
    // Ambil data bobot sebagai float desimal
    $c1_luas        = (float)$_POST['c1_luas'];
    $c2_penghasilan = (float)$_POST['c2_penghasilan'];
    $c3_komoditas   = (float)$_POST['c3_komoditas'];
    $c4_kondisi     = (float)$_POST['c4_kondisi'];
    $c5_alat        = (float)$_POST['c5_alat'];

    // Validasi Sisi Server: Pastikan total bobot wajib 1.0
    $total_bobot = $c1_luas + $c2_penghasilan + $c3_komoditas + $c4_kondisi + $c5_alat;
    
    if (abs($total_bobot - 1.0) > 0.001) {
        echo "<script>alert('Gagal menyimpan! Total bobot kriteria harus pas 1.00 (100%). Total inputan Anda: $total_bobot'); window.history.back();</script>";
        exit;
    }

    $sql_update = "UPDATE admin_programbantuan SET 
                    nama_program = '$nama_bantuan', 
                    batas_akhir = '$batas_akhir', 
                    status = '$status', 
                    gambar = '$gambar', 
                    deskripsi = '$deskripsi',
                    c1_luas = $c1_luas,
                    c2_penghasilan = $c2_penghasilan,
                    c3_komoditas = $c3_komoditas,
                    c4_kondisi = $c4_kondisi,
                    c5_alat = $c5_alat
                   WHERE id_program = $id_bantuan";

    if ($conn->query($sql_update) === TRUE) {
        echo "<script>alert('Data bantuan berhasil diperbarui!'); window.location='admin_detailbantuan.php?id=$id_bantuan';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data: " . $conn->error . "');</script>";
    }
}

// 4. PROSES HAPUS DATA
if (isset($_POST['hapus_bantuan'])) {
    $sql_delete = "DELETE FROM admin_programbantuan WHERE id_program = $id_bantuan";
    if ($conn->query($sql_delete) === TRUE) {
        echo "<script>alert('Program bantuan berhasil dihapus!'); window.location='admin_bantuan.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal menghapus data: " . $conn->error . "');</script>";
    }
}

// 5. QUERY AMBIL DATA DETAIL
$sql = "SELECT id_program AS id, nama_program AS nama_bantuan, batas_akhir, status, gambar, deskripsi, 
               c1_luas, c2_penghasilan, c3_komoditas, c4_kondisi, c5_alat 
        FROM admin_programbantuan 
        WHERE id_program = $id_bantuan";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "<script>alert('Data program bantuan tidak ditemukan!'); window.location='admin_bantuan.php';</script>";
    exit;
}

$data = $result->fetch_assoc();

// Logika default desimal jika di database kosong
$v_c1 = (!empty($data['c1_luas']) || $data['c1_luas'] === '0.00') ? (float)$data['c1_luas'] : 0.15;
$v_c2 = (!empty($data['c2_penghasilan']) || $data['c2_penghasilan'] === '0.00') ? (float)$data['c2_penghasilan'] : 0.20;
$v_c3 = (!empty($data['c3_komoditas']) || $data['c3_komoditas'] === '0.00') ? (float)$data['c3_komoditas'] : 0.20;
$v_c4 = (!empty($data['c4_kondisi']) || $data['c4_kondisi'] === '0.00') ? (float)$data['c4_kondisi'] : 0.15;
$v_c5 = (!empty($data['c5_alat']) || $data['c5_alat'] === '0.00') ? (float)$data['c5_alat'] : 0.30;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP-BANTU TANI - Detail Bantuan</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fbc02d; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }

        .dashboard-container {
            width: 100%; max-width: 1280px; height: 850px; max-height: 92vh;
            background-color: white; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            display: flex; overflow: hidden;
        }

        /* SIDEBAR BRANDING ONLY (SELARAS TOTAL DENGAN TAMBAH BANTUAN) */
        .sidebar-brand-only {
            width: 280px; background-color: #0d7839; color: white;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 40px 0; flex-shrink: 0;
        }
        .sidebar-logo-area { display: flex; flex-direction: column; align-items: center; text-align: center; padding: 0 20px; }
        .sidebar-logo-bg { width: 110px; height: 110px; background-color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; overflow: hidden; border: 2px solid #13a851; margin-bottom: 20px; }
        .sidebar-logo-img { width: 100%; height: 100%; object-fit: cover; }
        .sidebar-title { font-size: 20px; font-weight: bold; letter-spacing: 0.5px; margin-bottom: 6px; }
        .sidebar-subtitle { font-size: 12px; color: #d1e7dd; font-weight: 500; }

        /* AREA KONTEN KANAN */
        .main-content { flex-grow: 1; background-color: white; padding: 40px 50px; overflow-y: auto; display: flex; flex-direction: column; }
        .back-nav { display: flex; align-items: center; gap: 12px; color: #0d7839; text-decoration: none; font-size: 28px; font-weight: 700; margin-bottom: 5px; }
        .back-icon { font-size: 32px; font-weight: bold; cursor: pointer; }
        .sub-header-text { color: #6c757d; font-size: 14px; font-weight: 500; margin-left: 44px; margin-bottom: 25px; }

        .form-box-container { border: 1px solid #dee2e6; border-radius: 12px; padding: 35px; background-color: #ffffff; width: 100%; max-width: 950px; }
        .form-section-title { font-size: 16px; font-weight: 700; color: #0d7839; margin-bottom: 15px; border-bottom: 2px solid #e2f3e9; padding-bottom: 5px; }
        
        .form-grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }
        .form-grid-5 { display: grid; grid-template-columns: repeat(5, 1fr); gap: 12px; margin-bottom: 15px; }
        
        .form-group { display: flex; flex-direction: column; margin-bottom: 15px; gap: 6px; }
        .form-group label { font-size: 13px; font-weight: 700; color: #1b1c1e; }
        .form-group input, .form-group select, .form-group textarea { padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; color: #333; width: 100%; transition: all 0.2s; }
        
        /* Desain Saat Kunci Terbuka/Tertutup */
        .form-group input:disabled, .form-group select:disabled, .form-group textarea:disabled { background-color: #f8f9fa; color: #6c757d; border-color: #e9ecef; cursor: not-allowed; }

        .kriteria-label { display: flex; justify-content: space-between; align-items: center; }
        .badge-kriteria { font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: bold; }
        .badge-benefit { background-color: #c9ebd6; color: #0d7839; }
        .badge-cost { background-color: #f8d7da; color: #dc3545; }

        /* Kalkulator Bar */
        .total-bobot-bar { background-color: #f8f9fa; padding: 12px 18px; border-radius: 8px; border: 1px solid #dee2e6; display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; font-weight: bold; font-size: 14px; }
        .status-valid { color: #0d7839; }
        .status-invalid { color: #dc3545; }

        /* FOOTER ACTIONS */
        .action-footer-row { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #dee2e6; }
        .btn-danger-action { background-color: #dc3545; color: white; border: none; padding: 11px 22px; border-radius: 6px; font-size: 14px; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 6px; }
        .btn-danger-action:hover { background-color: #bd2130; }
        
        .right-buttons { display: flex; gap: 12px; }
        .btn-trigger-edit { background-color: #ffc107; color: #212529; border: none; padding: 11px 25px; border-radius: 6px; font-size: 14px; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 5px; }
        .btn-trigger-edit:hover { background-color: #e0a800; }
        
        .btn-save-update { background-color: #0d7839; color: white; border: none; padding: 11px 30px; border-radius: 6px; font-size: 14px; font-weight: bold; cursor: pointer; display: none; }
        .btn-save-update:hover { background-color: #0f8a42; }
        .btn-save-update:disabled { background-color: #6c757d; cursor: not-allowed; }
    </style>
</head>
<body>

    <div class="dashboard-container">
        
        <div class="sidebar-brand-only">
            <div class="sidebar-logo-area">
                <div class="sidebar-logo-bg">
                    <img src="logo.png" alt="Logo SIP-BANTU TANI" class="sidebar-logo-img">
                </div>
                <h2 class="sidebar-title">SIP-BANTU TANI</h2>
                <p class="sidebar-subtitle">Portal Resmi Bantuan Pertanian</p>
            </div>
        </div>

        <main class="main-content">
            <a href="admin_bantuan.php" class="back-nav">
                <span class="material-symbols-outlined back-icon">arrow_back</span> Detail Bantuan
            </a>
            <p class="sub-header-text">Review spesifikasi data master program beserta pembagian persentase bobot kriteria WP</p>

            <div class="form-box-container">
                <form id="formBantuan" action="admin_detailbantuan.php?id=<?= $id_bantuan; ?>" method="POST">
                    
                    <div class="form-section-title">Data Umum Program</div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label>Nama Program Bantuan</label>
                            <input type="text" name="nama_bantuan" value="<?= htmlspecialchars($data['nama_bantuan']); ?>" required disabled>
                        </div>
                        <div class="form-group">
                            <label>Batas Akhir (Deadline)</label>
                            <input type="date" name="batas_akhir" value="<?= $data['batas_akhir']; ?>" required disabled>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" disabled>
                                <option value="Open" <?= (strtolower($data['status']) == 'open') ? 'selected' : ''; ?>>Open</option>
                                <option value="Closed" <?= (strtolower($data['status']) == 'closed') ? 'selected' : ''; ?>>Closed</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nama File Gambar</label>
                            <input type="text" name="gambar" value="<?= htmlspecialchars($data['gambar']); ?>" required disabled>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Deskripsi Singkat (Opsional)</label>
                        <textarea name="deskripsi" rows="2" disabled><?= htmlspecialchars(!empty($data['deskripsi']) ? $data['deskripsi'] : "Program penyaluran bantuan resmi untuk mengoptimalkan hasil panen pertanian daerah."); ?></textarea>
                    </div>

                    <div class="form-section-title" style="margin-top: 15px;">Setting Bobot Kriteria Penilaian (Metode WP)</div>
                    
                    <div class="form-grid-5">
                        <div class="form-group">
                            <div class="kriteria-label">
                                <label>C1: Luas Lahan</label>
                                <span class="badge-kriteria badge-benefit">Benefit</span>
                            </div>
                            <input type="number" class="input-bobot" name="c1_luas" step="0.01" min="0" max="1" value="<?= number_format($v_c1, 2); ?>" required disabled>
                        </div>

                        <div class="form-group">
                            <div class="kriteria-label">
                                <label>C2: Penghasilan</label>
                                <span class="badge-kriteria badge-cost">Cost</span>
                            </div>
                            <input type="number" class="input-bobot" name="c2_penghasilan" step="0.01" min="0" max="1" value="<?= number_format($v_c2, 2); ?>" required disabled>
                        </div>

                        <div class="form-group">
                            <div class="kriteria-label">
                                <label>C3: Komoditas</label>
                                <span class="badge-kriteria badge-benefit">Benefit</span>
                            </div>
                            <input type="number" class="input-bobot" name="c3_komoditas" step="0.01" min="0" max="1" value="<?= number_format($v_c3, 2); ?>" required disabled>
                        </div>

                        <div class="form-group">
                            <div class="kriteria-label">
                                <label>C4: Kondisi Lahan</label>
                                <span class="badge-kriteria badge-benefit">Benefit</span>
                            </div>
                            <input type="number" class="input-bobot" name="c4_kondisi" step="0.01" min="0" max="1" value="<?= number_format($v_c4, 2); ?>" required disabled>
                        </div>

                        <div class="form-group">
                            <div class="kriteria-label">
                                <label>C5: Alat Kerja</label>
                                <span class="badge-kriteria badge-cost">Cost</span>
                            </div>
                            <input type="number" class="input-bobot" name="c5_alat" step="0.01" min="0" max="1" value="<?= number_format($v_c5, 2); ?>" required disabled>
                        </div>
                    </div>

                    <div class="total-bobot-bar">
                        <span>Total Alokasi Bobot Saat Ini:</span>
                        <span id="displayTotal" class="status-valid">0.00 (0%)</span>
                    </div>

                    <div class="action-footer-row">
                        <button type="submit" name="hapus_bantuan" class="btn-danger-action" onclick="return confirm('Apakah kamu yakin ingin MENGHAPUS program bantuan ini?')">
                            <span class="material-symbols-outlined" style="font-size: 18px;">delete</span> Hapus
                        </button>
                        
                        <div class="right-buttons">
                            <button type="button" id="btnEditToggle" class="btn-trigger-edit">
                                <span class="material-symbols-outlined" style="font-size: 18px;">edit</span> Edit Data
                            </button>
                            <button type="submit" id="btnSimpan" name="update_bantuan" class="btn-save-update">Simpan Perubahan</button>
                        </div>
                    </div>

                </form>
            </div>
        </main>
    </div>

    <script>
        const form = document.getElementById('formBantuan');
        const inputs = form.querySelectorAll('input, select, textarea');
        const bobotInputs = form.querySelectorAll('.input-bobot');
        const btnEditToggle = document.getElementById('btnEditToggle');
        const btnSimpan = document.getElementById('btnSimpan');
        const displayTotal = document.getElementById('displayTotal');

        let isEditMode = false;

        function hitungTotalBobot() {
            let total = 0;
            bobotInputs.forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            
            total = parseFloat(total.toFixed(2));
            const persen = Math.round(total * 100);
            
            displayTotal.innerText = `${total.toFixed(2)} (${persen}%)`;

            if (total === 1.00) {
                displayTotal.className = "status-valid";
                btnSimpan.disabled = false;
            } else {
                displayTotal.className = "status-invalid";
                btnSimpan.disabled = true;
            }
        }

        hitungTotalBobot();

        bobotInputs.forEach(input => {
            input.addEventListener('input', hitungTotalBobot);
        });

        btnEditToggle.addEventListener('click', () => {
            isEditMode = !isEditMode;

            if (isEditMode) {
                inputs.forEach(input => {
                    if(input.name !== 'hapus_bantuan') input.disabled = false;
                });
                btnEditToggle.innerHTML = '<span class="material-symbols-outlined" style="font-size: 18px;">close</span> Batal';
                btnEditToggle.style.backgroundColor = '#6c757d';
                btnEditToggle.style.color = '#fff';
                btnSimpan.style.display = 'block';
            } else {
                form.reset();
                inputs.forEach(input => input.disabled = true);
                btnEditToggle.innerHTML = '<span class="material-symbols-outlined" style="font-size: 18px;">edit</span> Edit Data';
                btnEditToggle.style.backgroundColor = '#ffc107';
                btnEditToggle.style.color = '#212529';
                btnSimpan.style.display = 'none';
                hitungTotalBobot();
            }
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>