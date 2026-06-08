<?php
// 1. AKTIFKAN SESSION & ERROR REPORTING
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// --- TAMBAHKAN KODE INI UNTUK MENYIMPAN HALAMAN ASAL ---
if (!isset($_SESSION['halaman_asal']) && isset($_SERVER['HTTP_REFERER'])) {
    $_SESSION['halaman_asal'] = $_SERVER['HTTP_REFERER'];
} elseif (!isset($_SESSION['halaman_asal'])) {
    $_SESSION['halaman_asal'] = 'petani_login.php'; 
}
// --------------------------------------------------------

// 2. KONEKSI DATABASE
$conn = new mysqli("localhost", "root", "", "sip_bantutani");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// 3. PROSES TAHAP 1: INPUT NO TELP
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_kirim'])) {
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);

    $query = "(SELECT no_telp, 'petani' as role FROM admin_datapetani WHERE no_telp = '$no_telp') 
              UNION 
              (SELECT no_hp as no_telp, 'admin' as role FROM admin_users WHERE no_hp = '$no_telp')";
    
    $hasil_cek = $conn->query($query);

    if ($hasil_cek && $hasil_cek->num_rows > 0) {
        $data = $hasil_cek->fetch_assoc();
        $_SESSION['reset_no_telp'] = $no_telp;
        $_SESSION['user_role']     = $data['role'];
        
        $otp_simulasi = rand(1000, 9999);
        $_SESSION['otp_code'] = $otp_simulasi;
        $_SESSION['otp_verified'] = false;
        
        echo "<script>alert('Simulasi OTP: Kode Anda adalah $otp_simulasi'); window.location.href='petani_lupapasword.php';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal! Nomor telepon tidak terdaftar di sistem.'); window.history.back();</script>";
        exit();
    }
}

// 4. PROSES TAHAP 2: CEK OTP
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_verifikasi_otp'])) {
    if (isset($_POST['otp_input']) && $_POST['otp_input'] == $_SESSION['otp_code']) {
        $_SESSION['otp_verified'] = true;
        header("Location: petani_lupapasword.php");
    } else {
        echo "<script>alert('Gagal! Kode OTP salah.'); window.history.back();</script>";
    }
    exit();
}

// 5. PROSES TAHAP 3: UPDATE PASSWORD
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_simpan_password'])) {
    if (!isset($_SESSION['reset_no_telp'])) {
        header("Location: petani_lupapasword.php");
        exit();
    }

    $no_telp = $_SESSION['reset_no_telp'];
    $role    = $_SESSION['user_role'];
    $pass    = mysqli_real_escape_string($conn, $_POST['password_baru']);
    $konf    = mysqli_real_escape_string($conn, $_POST['konfirmasi_password']);

    if ($pass !== $konf) {
        echo "<script>alert('Password tidak sesuai!'); window.history.back();</script>";
        exit();
    }

    $tabel = ($role == 'admin') ? "admin_users" : "admin_datapetani";
    $kolom_telp = ($role == 'admin') ? "no_hp" : "no_telp";

    $query_update = "UPDATE $tabel SET password = '$pass' WHERE $kolom_telp = '$no_telp'";

    if ($conn->query($query_update) === TRUE) {
        $tujuan_akhir = $_SESSION['halaman_asal'];
        session_destroy();
        echo "<script>
                alert('Password Berhasil Diperbarui!'); 
                window.location.href='$tujuan_akhir'; 
              </script>";
    } else {
        echo "Error: " . $conn->error;
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP-BANTU TANI - Lupa Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fbc02d; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .dashboard-container { width: 100%; max-width: 1280px; height: 850px; max-height: 92vh; background-color: white; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); display: flex; overflow: hidden; }
        .modal-visual-side { flex: 55; background-color: #0d7839; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 60px; color: white; position: relative; text-align: center; }
        .back-btn { position: absolute; top: 40px; left: 40px; color: white; text-decoration: none; font-size: 24px; cursor: pointer; border: none; background: none; }
        .lock-icon-large { font-size: 80px; margin-bottom: 25px; color: #ffffff; }
        .welcome-title { font-size: 42px; font-weight: 700; margin-bottom: 16px; }
        .welcome-subtitle { font-size: 18px; opacity: 0.9; line-height: 1.6; max-width: 440px; }
        .modal-form-side { flex: 45; background-color: #f7f9f6; display: flex; justify-content: center; align-items: center; padding: 40px; }
        .form-section-box { width: 100%; max-width: 440px; background-color: #ffffff; border: 1px solid rgba(13, 120, 57, 0.15); border-radius: 24px; padding: 45px 35px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); text-align: center; }
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group label { display: block; font-size: 15px; font-weight: 700; color: #000000; margin-bottom: 12px; }
        .input-wrapper { position: relative; display: flex; align-items: center; }
        .input-wrapper i.input-icon { position: absolute; left: 18px; color: #333333; font-size: 16px; }
        .input-wrapper input { width: 100%; padding: 15px 15px 15px 48px; border: 1px solid #dcdcdc; border-radius: 12px; font-size: 14px; outline: none; background-color: #ffffff; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03); }
        .info-text { font-size: 13px; color: #666666; line-height: 1.5; margin-bottom: 30px; padding: 0 10px; }
        .session-info-box { background-color: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 12px; font-size: 14px; text-align: left; margin-bottom: 20px; border-left: 5px solid #0d7839; }
        .session-info-box strong { color: #1b5e20; }
        .btn-submit { display: block; width: 100%; padding: 16px 0; border: none; border-radius: 12px; font-size: 16px; font-weight: 700; color: white; cursor: pointer; background: linear-gradient(135deg, #00ff37, #00db2e); box-shadow: 0 4px 15px rgba(0, 255, 55, 0.3); text-align: center; transition: all 0.2s ease; }
        .btn-submit:hover { transform: translateY(-2px); filter: brightness(1.05); }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="modal-visual-side">
            <a href="javascript:history.back()" class="back-btn"><i class="fa-solid fa-arrow-left"></i></a>
            <i class="fa-solid fa-lock lock-icon-large"></i>
            <h2 class="welcome-title">Lupa Password</h2>
            <p class="welcome-subtitle">Masukkan Nomor Telepon Anda untuk mereset password.</p>
        </div>
        <div class="modal-form-side">
            <div class="form-section-box">
                <?php if (!isset($_SESSION['reset_no_telp'])): ?>
                    <form action="" method="POST">
                        <div class="form-group">
                            <label>No Telepon</label>
                            <div class="input-wrapper">
                                <i class="fa-regular fa-user input-icon"></i>
                                <input type="tel" name="no_telp" placeholder="Masukkan No Telepon" required>
                            </div>
                        </div>
                        <button type="submit" name="btn_kirim" class="btn-submit">Kirim Kode</button>
                    </form>
                <?php elseif ($_SESSION['otp_verified'] == false): ?>
                    <div class="session-info-box">
                        <i class="fa-solid fa-phone"></i> No. HP: <strong><?php echo $_SESSION['reset_no_telp']; ?></strong><br>
                        <i class="fa-solid fa-key"></i> OTP: <strong style="color: #d32f2f;"><?php echo $_SESSION['otp_code']; ?></strong>
                    </div>
                    <form action="" method="POST">
                        <div class="form-group">
                            <label>Masukkan Kode OTP</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-shield-halved input-icon"></i>
                                <input type="number" name="otp_input" required>
                            </div>
                        </div>
                        <button type="submit" name="btn_verifikasi_otp" class="btn-submit">Verifikasi OTP</button>
                    </form>
                <?php else: ?>
                    <div class="session-info-box" style="background-color: #e3f2fd; color: #1565c0; border-left-color: #1976d2;">
                        <i class="fa-solid fa-user-check"></i> Mengubah password untuk: <strong><?php echo $_SESSION['user_role']; ?></strong>
                    </div>
                    <form action="" method="POST">
                        <div class="form-group">
                            <label>Password Baru</label>
                            <input type="password" name="password_baru" class="input-wrapper" style="width:100%; padding:15px; border-radius:12px; border:1px solid #dcdcdc;" required>
                        </div>
                        <div class="form-group">
                            <label>Konfirmasi Password</label>
                            <input type="password" name="konfirmasi_password" class="input-wrapper" style="width:100%; padding:15px; border-radius:12px; border:1px solid #dcdcdc;" required>
                        </div>
                        <button type="submit" name="btn_simpan_password" class="btn-submit">Simpan Password</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>