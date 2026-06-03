<?php
// 1. KONEKSI KE DATABASE (sip_bantutani)
$host     = "localhost";
$db_user  = "root";
$db_pass  = "";
$db_name  = "sip_bantutani";

$koneksi = mysqli_connect($host, $db_user, $db_pass, $db_name);

if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

$pesan_error = "";
$status_sukses = false; // Flag untuk memicu pop-up JavaScript

// 2. PROSES KETIKA TOMBOL SIMPAN DIKLIK
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nik           = trim($_POST['nik']);
    $nama          = trim($_POST['nama']); // Menggunakan nama kolom database kamu ('nama')
    $komoditas     = $_POST['komoditas'];
    $alamat        = trim($_POST['alamat']);
    $tempat_lahir  = trim($_POST['tempat_lahir']);
    $tanggal_lahir = $_POST['tanggal_lahir'];

    // VALIDASI FORMULIR
    if (strlen($nik) !== 16) {
        $pesan_error = "Gagal menyimpan! NIK harus berjumlah tepat 16 digit.";
    } elseif (substr($nik, 0, 4) !== "3305") {
        $pesan_error = "Gagal menyimpan! NIK harus diawali kode '3305' untuk wilayah Kebumen.";
    } elseif (empty($nama) || empty($komoditas) || empty($alamat) || empty($tempat_lahir) || empty($tanggal_lahir)) {
        $pesan_error = "Semua kolom formulir wajib diisi!";
    } else {
        // Cek apakah NIK sudah pernah terdaftar di database
        $cek_nik = mysqli_query($koneksi, "SELECT nik FROM data_petani WHERE nik = '$nik'");
        if (mysqli_num_rows($cek_nik) > 0) {
            $pesan_error = "Gagal! NIK tersebut sudah terdaftar di dalam sistem.";
        } else {
            // JIKA VALID, MASUKKAN KE TABEL DATA_PETANI
            $query = "INSERT INTO data_petani (nik, nama, komoditas, alamat, tempat_lahir, tanggal_lahir) 
                      VALUES ('$nik', '$nama', '$komoditas', '$alamat', '$tempat_lahir', '$tanggal_lahir')";
            
            if (mysqli_query($koneksi, $query)) {
                $status_sukses = true; // Sinyal sukses ke JavaScript untuk memunculkan pop-up
            } else {
                $pesan_error = "Terjadi kesalahan sistem saat menyimpan data ke database.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP-BANTU TANI - Tambah Petani</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #fbc02d; /* Latar belakang kuning luar */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .dashboard-container {
            width: 100%;
            max-width: 1280px;
            height: 850px;
            max-height: 92vh;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            display: flex;
            overflow: hidden;
            position: relative; 
        }

        /* SIDEBAR KIRI */
        .sidebar-clean {
            width: 280px;
            background-color: #0d7839; /* Hijau tua khas SIP-BANTU TANI */
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            flex-shrink: 0;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-logo-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .sidebar-logo-bg {
            width: 120px;
            height: 120px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            border: 4px solid #13a851;
            margin-bottom: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .sidebar-logo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sidebar-title {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .sidebar-subtitle {
            font-size: 12px;
            color: #d1e7dd;
            font-weight: 500;
            opacity: 0.9;
        }

        /* AREA KONTEN UTAMA (KANAN) */
        .main-content {
            flex-grow: 1;
            background-color: white;
            padding: 40px 50px;
            overflow-y: auto;
        }

        /* HEADER DENGAN TOMBOL KEMBALI */
        .page-header-back {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 12px;
        }

        .btn-back {
            color: #0d7839;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            transition: transform 0.2s ease;
        }

        .btn-back:hover {
            transform: translateX(-3px);
        }

        .btn-back .material-symbols-outlined {
            font-size: 32px;
            font-weight: bold;
        }

        .page-title {
            color: #0d7839;
            font-size: 28px;
            font-weight: 700;
        }

        .sub-header-text {
            color: #6c757d;
            font-size: 14px;
            margin-left: 47px;
            margin-bottom: 30px;
        }

        /* FORM BOX CONTAINER */
        .form-container-box {
            border: 1px solid #dee2e6;
            border-radius: 12px;
            padding: 35px 40px;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }

        .form-group {
            margin-bottom: 22px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-size: 15px;
            font-weight: bold;
            color: #212529;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            font-size: 14px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            outline: none;
            color: #495057;
            transition: border-color 0.2s ease;
        }

        .form-control:focus {
            border-color: #13a851;
        }

        .form-control::placeholder {
            color: #adb5bd;
        }

        .form-row-flex {
            display: flex;
            gap: 25px;
        }

        .form-row-flex .form-group {
            flex: 1;
        }

        /* ACTION BUTTONS */
        .form-actions-area {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 35px;
        }

        .btn-form {
            padding: 12px 35px;
            font-size: 15px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-batal {
            background-color: #ffffff;
            color: #212529;
            border: 1px solid #ced4da;
        }

        .btn-batal:hover {
            background-color: #f8f9fa;
        }

        .btn-simpan {
            background-color: #0d7839;
            color: white;
            border: none;
            box-shadow: 0 4px 10px rgba(13, 120, 57, 0.2);
        }

        .btn-simpan:hover {
            background-color: #0f8a42;
        }

        /* GAYA ERROR MESSAGE */
        .alert-error-banner {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 14px;
            font-weight: bold;
        }

        /* CUSTOM POP-UP OVERLAY */
        .alert-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 999;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .alert-overlay.show {
            opacity: 1;
            pointer-events: auto;
        }

        .alert-card {
            background-color: white;
            padding: 40px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            max-width: 400px;
            width: 85%;
            transform: scale(0.8);
            transition: all 0.3s ease;
        }

        .alert-overlay.show .alert-card {
            transform: scale(1);
        }

        .success-icon-circle {
            width: 75px;
            height: 75px;
            background-color: #d1e7dd;
            color: #0f8a42;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 20px auto;
        }

        .success-icon-circle .material-symbols-outlined {
            font-size: 45px;
            font-weight: bold;
        }

        .alert-title-text {
            color: #212529;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .alert-desc-text {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 30px;
            line-height: 1.5;
        }

        .btn-modal-ok {
            background-color: #0d7839;
            color: white;
            border: none;
            padding: 12px 45px;
            font-size: 15px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s;
            box-shadow: 0 4px 10px rgba(13, 120, 57, 0.2);
        }

        .btn-modal-ok:hover {
            background-color: #0a5c2c;
        }
    </style>
</head>
<body>

    <div class="dashboard-container">
        
        <aside class="sidebar-clean">
            <div class="sidebar-logo-area">
                <div class="sidebar-logo-bg">
                    <img src="logo.png" alt="Logo SIP-BANTU TANI" class="sidebar-logo-img">
                </div>
                <h2 class="sidebar-title">SIP-BANTU TANI</h2>
                <p class="sidebar-subtitle">Portal Resmi Bantuan Pertanian</p>
            </div>
        </aside>

        <main class="main-content">
            <div class="page-header-back">
                <a href="admin_data_petani.php" class="btn-back">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <h1 class="page-title">Tambah Petani</h1>
            </div>
            <p class="sub-header-text">Lengkapi data petani pada formulir dibawah ini</p>

            <?php if (!empty($pesan_error)): ?>
                <div class="alert-error-banner"><?php echo $pesan_error; ?></div>
            <?php endif; ?>

            <div class="form-container-box">
                <form action="admin_tambah_petani.php" method="POST">
                    
                    <div class="form-group">
                        <label for="nik">NIK</label>
                        <input type="text" id="nik" name="nik" class="form-control" placeholder="Masukkan NIK Kebumen (16 digit)" maxlength="16" required>
                    </div>

                    <div class="form-group">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan Nama Lengkap" required>
                    </div>

                    <div class="form-group">
                        <label for="komoditas">Komoditas</label>
                        <select id="komoditas" name="komoditas" class="form-control" required>
                            <option value="">-- Pilih Komoditas --</option>
                            <option value="Padi">Padi</option>
                            <option value="Palawija">Palawija</option>
                            <option value="Hortikultura">Hortikultura</option>
                            <option value="Perkebunan">Perkebunan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" id="alamat" name="alamat" class="form-control" placeholder="Masukkan Alamat Lengkap (Desa, RT/RW, Kecamatan)" required>
                    </div>

                    <div class="form-row-flex">
                        <div class="form-group">
                            <label for="tempat_lahir">Tempat Lahir</label>
                            <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control" placeholder="Masukkan Tempat Lahir" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-actions-area">
                        <a href="admin_data_petani.php" class="btn-form btn-batal">Batal</a>
                        <button type="submit" class="btn-form btn-simpan">Simpan</button>
                    </div>

                </form>
            </div>
        </main>

        <div class="alert-overlay" id="successModal">
            <div class="alert-card">
                <div class="success-icon-circle">
                    <span class="material-symbols-outlined">check_circle</span>
                </div>
                <h3 class="alert-title-text">Berhasil Disimpan!</h3>
                <p class="alert-desc-text">Data petani baru telah sukses ditambahkan ke dalam sistem database.</p>
                <button class="btn-modal-ok" onclick="alihkanHalaman()">OK</button>
            </div>
        </div>

    </div>

    <script>
        // Mengambil status sukses dari backend PHP
        const statusSukses = <?php echo $status_sukses ? 'true' : 'false'; ?>;
        const successModal = document.getElementById('successModal');

        // Jika PHP menyatakan sukses insert, jalankan modal pop-up tampil
        if (statusSukses) {
            successModal.classList.add('show');
        }

        function alihkanHalaman() {
            successModal.classList.remove('show');
            window.location.href = 'admin_data_petani.php';
        }
    </script>

</body>
</html>