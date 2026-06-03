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

// 2. LOGIKA SIMPAN DATA
if (isset($_POST['simpan_bantuan'])) {
    $nama_bantuan   = $_POST['nama_bantuan'];
    $batas_akhir    = $_POST['batas_akhir'];
    $status         = $_POST['status'];
    $gambar         = $_POST['gambar'];
    $deskripsi      = !empty($_POST['deskripsi']) ? $_POST['deskripsi'] : "Program penyaluran bantuan resmi untuk mengoptimalkan hasil panen pertanian daerah.";
    
    // Ambil Nilai Bobot Kriteria WP (Desimal)
    $c1_luas        = (float)$_POST['c1_luas'];
    $c2_penghasilan = (float)$_POST['c2_penghasilan'];
    $c3_komoditas   = (float)$_POST['c3_komoditas'];
    $c4_kondisi     = (float)$_POST['c4_kondisi'];
    $c5_alat        = (float)$_POST['c5_alat'];

    // Validasi Total
    $total = $c1_luas + $c2_penghasilan + $c3_komoditas + $c4_kondisi + $c5_alat;
    if (abs($total - 1.0) > 0.001) {
        echo "<script>alert('Gagal! Total bobot harus 1.00 (100%). Total input Anda: $total'); window.history.back();</script>";
        exit;
    }

    $sql_insert = "INSERT INTO admin_programbantuan (nama_program, batas_akhir, status, gambar, deskripsi, c1_luas, c2_penghasilan, c3_komoditas, c4_kondisi, c5_alat) 
                   VALUES ('$nama_bantuan', '$batas_akhir', '$status', '$gambar', '$deskripsi', $c1_luas, $c2_penghasilan, $c3_komoditas, $c4_kondisi, $c5_alat)";

    if ($conn->query($sql_insert) === TRUE) {
        echo "<script>alert('Bantuan baru sukses ditambahkan!'); window.location='admin_bantuan.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan: " . $conn->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP-BANTU TANI - Tambah Bantuan</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        /* Menggunakan style yang identik dengan halaman Detail agar seragam */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fbc02d; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .dashboard-container { width: 100%; max-width: 1280px; height: 850px; max-height: 92vh; background-color: white; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); display: flex; overflow: hidden; }
        
        .sidebar-brand-only { width: 280px; background-color: #0d7839; color: white; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 0; flex-shrink: 0; }
        .sidebar-logo-area { display: flex; flex-direction: column; align-items: center; text-align: center; padding: 0 20px; }
        .sidebar-logo-bg { width: 110px; height: 110px; background-color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; overflow: hidden; border: 2px solid #13a851; margin-bottom: 20px; }
        .sidebar-logo-img { width: 100%; height: 100%; object-fit: cover; }
        .sidebar-title { font-size: 20px; font-weight: bold; letter-spacing: 0.5px; margin-bottom: 6px; }
        .sidebar-subtitle { font-size: 12px; color: #d1e7dd; font-weight: 500; }

        .main-content { flex-grow: 1; background-color: white; padding: 40px 50px; overflow-y: auto; display: flex; flex-direction: column; }
        .back-nav { display: flex; align-items: center; gap: 12px; color: #0d7839; text-decoration: none; font-size: 28px; font-weight: 700; margin-bottom: 5px; }
        .sub-header-text { color: #6c757d; font-size: 14px; font-weight: 500; margin-left: 44px; margin-bottom: 25px; }

        .form-box-container { border: 1px solid #dee2e6; border-radius: 12px; padding: 35px; width: 100%; max-width: 950px; }
        .form-section-title { font-size: 16px; font-weight: 700; color: #0d7839; margin-bottom: 15px; border-bottom: 2px solid #e2f3e9; padding-bottom: 5px; }
        .form-grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }
        .form-grid-5 { display: grid; grid-template-columns: repeat(5, 1fr); gap: 12px; margin-bottom: 15px; }
        
        .form-group { display: flex; flex-direction: column; margin-bottom: 15px; gap: 6px; }
        .form-group label { font-size: 13px; font-weight: 700; color: #1b1c1e; }
        .form-group input, .form-group select { padding: 10px 14px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; width: 100%; }
        
        .kriteria-label { display: flex; justify-content: space-between; align-items: center; }
        .badge-kriteria { font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: bold; }
        .badge-benefit { background-color: #c9ebd6; color: #0d7839; }
        .badge-cost { background-color: #f8d7da; color: #dc3545; }

        .total-bobot-bar { background-color: #f8f9fa; padding: 12px 18px; border-radius: 8px; border: 1px solid #dee2e6; display: flex; justify-content: space-between; align-items: center; font-weight: bold; font-size: 14px; }
        .status-valid { color: #0d7839; }
        .status-invalid { color: #dc3545; }

        .btn-row { display: flex; justify-content: flex-end; gap: 15px; margin-top: 20px; }
        .btn-simpan { background-color: #0d7839; color: white; border: none; padding: 10px 35px; border-radius: 6px; font-weight: bold; cursor: pointer; }
        .btn-simpan:disabled { background-color: #6c757d; cursor: not-allowed; }
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
            <a href="admin_bantuan.php" class="back-nav"><span class="material-symbols-outlined">arrow_back</span> Tambah Bantuan</a>
            <p class="sub-header-text">Lengkapi data master program beserta bobot perhitungan kriteria WP (Desimal)</p>
            <div class="form-box-container">
                <form id="formTambah" action="admin_tambahbantuan.php" method="POST">
                    <div class="form-section-title">Data Umum Program</div>
                    <div class="form-grid-2">
                        <div class="form-group"><label>Nama Program Bantuan</label><input type="text" name="nama_bantuan" required></div>
                        <div class="form-group"><label>Batas Akhir (Deadline)</label><input type="date" name="batas_akhir" required></div>
                        <div class="form-group"><label>Status</label><select name="status"><option value="Open">Open</option><option value="Closed">Closed</option></select></div>
                        <div class="form-group"><label>Nama File Gambar</label><input type="text" name="gambar" placeholder="Contoh: pupuk.png" required></div>
                    </div>
                    <div class="form-group"><label>Deskripsi Singkat</label><input type="text" name="deskripsi" placeholder="Masukkan deskripsi program"></div>
                    
                    <div class="form-section-title" style="margin-top: 15px;">Setting Bobot Kriteria (Total 1.00)</div>
                    <div class="form-grid-5">
                        <div class="form-group"><div class="kriteria-label"><label>C1: Luas</label><span class="badge-kriteria badge-benefit">Benefit</span></div><input type="number" class="input-bobot" name="c1_luas" step="0.01" value="0.20" required></div>
                        <div class="form-group"><div class="kriteria-label"><label>C2: Penghasilan</label><span class="badge-kriteria badge-cost">Cost</span></div><input type="number" class="input-bobot" name="c2_penghasilan" step="0.01" value="0.20" required></div>
                        <div class="form-group"><div class="kriteria-label"><label>C3: Komoditas</label><span class="badge-kriteria badge-benefit">Benefit</span></div><input type="number" class="input-bobot" name="c3_komoditas" step="0.01" value="0.20" required></div>
                        <div class="form-group"><div class="kriteria-label"><label>C4: Kondisi</label><span class="badge-kriteria badge-benefit">Benefit</span></div><input type="number" class="input-bobot" name="c4_kondisi" step="0.01" value="0.20" required></div>
                        <div class="form-group"><div class="kriteria-label"><label>C5: Alat</label><span class="badge-kriteria badge-cost">Cost</span></div><input type="number" class="input-bobot" name="c5_alat" step="0.01" value="0.20" required></div>
                    </div>
                    
                    <div class="total-bobot-bar">
                        <span>Total Alokasi Bobot:</span>
                        <span id="displayTotal" class="status-valid">1.00 (100%)</span>
                    </div>

                    <div class="btn-row">
                        <button type="submit" id="btnSimpan" name="simpan_bantuan" class="btn-simpan">Simpan Program</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
    <script>
        const bobotInputs = document.querySelectorAll('.input-bobot');
        const displayTotal = document.getElementById('displayTotal');
        const btnSimpan = document.getElementById('btnSimpan');

        function hitung() {
            let total = 0;
            bobotInputs.forEach(i => total += parseFloat(i.value) || 0);
            total = Math.round(total * 100) / 100;
            displayTotal.innerText = `${total.toFixed(2)} (${Math.round(total * 100)}%)`;
            btnSimpan.disabled = (total !== 1.00);
            displayTotal.className = (total === 1.00) ? "status-valid" : "status-invalid";
        }
        bobotInputs.forEach(i => i.addEventListener('input', hitung));
    </script>
</body>
</html>
<?php $conn->close(); ?>