<?php
session_start();
include 'koneksi.php';

// Cek session
if (!isset($_SESSION['nik'])) {
    header("Location: petani_login.php");
    exit();
}

$nik_login = $_SESSION['nik'];
// Mengambil data berdasarkan NIK session yang sedang login
$query = "SELECT * FROM admin_datapetani WHERE nik = '$nik_login'";
$data = $conn->query($query)->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil - SIP-BANTU TANI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fbc02d; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .dashboard-container { width: 100%; max-width: 1280px; height: 850px; max-height: 92vh; background-color: white; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); display: flex; overflow: hidden; }

        /* SIDEBAR KONSISTEN */
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

        /* FORM */
        .form-card { max-width: 600px; background: #fff; padding: 0; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; }
        .form-group input { width: 100%; padding: 14px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9; font-size: 15px; }
        .btn-save { background: #0d7839; color: white; padding: 15px 30px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 16px; width: 100%; }
        .btn-save:hover { background: #0b6631; }
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
            <a href="petani_profile.php" class="back-nav"><span class="material-symbols-outlined" style="font-size: 32px;">arrow_back</span> Edit Profil</a>
        </div>

        <div class="scrollable-content">
            <form class="form-card" method="POST" action="proses_update_profile.php">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" value="<?php echo htmlspecialchars($data['nama_lengkap']); ?>" required>
                </div>
                <div class="form-group">
                    <label>NIK</label>
                    <input type="text" name="nik" value="<?php echo htmlspecialchars($data['nik']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label>No. Telepon</label>
                    <input type="text" name="no_telp" value="<?php echo htmlspecialchars($data['no_telp']); ?>">
                </div>
                <div class="form-group">
                    <label>Komoditas</label>
                    <input type="text" name="komoditas" value="<?php echo htmlspecialchars($data['komoditas']); ?>">
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <input type="text" name="alamat" value="<?php echo htmlspecialchars($data['alamat']); ?>">
                </div>
                <div class="form-group">
                    <label>Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="<?php echo htmlspecialchars($data['tempat_lahir']); ?>">
                </div>
                <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="<?php echo htmlspecialchars($data['tanggal_lahir']); ?>">
                </div>

                <button type="submit" class="btn-save"><i class="fa-solid fa-save"></i> Simpan Perubahan</button>
            </form>
        </div>
    </main>
</div>

</body>
</html>