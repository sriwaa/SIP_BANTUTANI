<?php
// 1. MEMULAI SESSION & KONEKSI DATABASE
session_start();
$host     = "localhost";
$db_user  = "root";
$db_pass  = "";
$db_name  = "sip_bantutani";

$koneksi = mysqli_connect($host, $db_user, $db_pass, $db_name);

// Cek koneksi database
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$error_message = "";
$success_login = false; 

// 2. PROSES SAAT TOMBOL LOGIN DIKLIK
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Menggunakan username sesuai permintaan
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password']; 

    // Query ke tabel surveyor
    $query  = "SELECT * FROM surveyor WHERE username='$username'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Verifikasi password
        if ($password == $row['password']) {
            $_SESSION['surveyor_id'] = $row['id_surveyor'];
            $_SESSION['nama']        = $row['nama_lengkap'];
            $_SESSION['role']        = 'surveyor';

            $success_login = true;
        } else {
            $error_message = "Username atau Password Surveyor salah!";
        }
    } else {
        $error_message = "Username tidak terdaftar!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP-BANTU TANI - Login Surveyor</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fbc02d; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .login-container { width: 100%; max-width: 1280px; height: 850px; max-height: 92vh; background-color: white; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); display: flex; overflow: hidden; }
        .brand-side { width: 320px; background-color: #0d7839; color: white; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 40px 20px; text-align: center; flex-shrink: 0; }
        .logo-bg { width: 140px; height: 140px; background-color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; overflow: hidden; border: 3px solid #13a851; margin-bottom: 25px; box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
        .logo-img { width: 100%; height: 100%; object-fit: cover; }
        .brand-title { font-size: 22px; font-weight: bold; letter-spacing: 0.5px; margin-bottom: 6px; }
        .brand-subtitle { font-size: 13px; color: #d1e7dd; font-weight: 500; }
        .form-side { flex-grow: 1; background-color: white; padding: 40px 80px; display: flex; flex-direction: column; justify-content: center; align-items: center; }
        .form-wrapper { width: 100%; max-width: 480px; }
        .welcome-text { color: #0d7839; font-size: 32px; font-weight: 700; margin-bottom: 8px; }
        .sub-welcome { color: #6c757d; font-size: 15px; margin-bottom: 45px; }
        .alert-error { background-color: #f8d7da; color: #842029; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; border: 1px solid #f5c2c7; width: 100%; }
        .alert-success { background-color: #d1e7dd; color: #0f5132; padding: 20px; border-radius: 8px; margin-bottom: 25px; font-size: 15px; border: 1px solid #badbcc; text-align: center; box-shadow: 0 4px 12px rgba(15, 81, 50, 0.1); }
        .btn-continue { display: block; background-color: #0d7839; color: white; padding: 12px 20px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 14px; margin-top: 15px; transition: background-color 0.2s; box-shadow: 0 4px 10px rgba(13, 120, 57, 0.2); }
        .btn-continue:hover { background-color: #095427; }
        .input-group { width: 100%; margin-bottom: 25px; }
        .input-group label { display: block; font-size: 14px; font-weight: 600; color: #495057; margin-bottom: 10px; }
        .input-field { width: 100%; padding: 15px 18px; border: 1px solid #ced4da; border-radius: 8px; font-size: 15px; outline: none; background-color: #f8f9fa; }
        .input-field:focus { border-color: #13a851; background-color: white; box-shadow: 0 0 0 3px rgba(19, 168, 81, 0.15); }
        .btn-login { background-color: #13a851; color: white; border: none; width: 100%; padding: 15px; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer; box-shadow: 0 4px 12px rgba(19, 168, 81, 0.2); }
        .btn-login:hover { background-color: #0f8a42; }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="brand-side">
            <div class="logo-bg">
                <img src="logo.png" alt="Logo SIP-BANTU TANI" class="logo-img">
            </div>
            <h2 class="brand-title">SIP-BANTU TANI</h2>
            <p class="brand-subtitle">Portal Resmi Bantuan Pertanian</p>
        </div>

        <div class="form-side">
            <div class="form-wrapper">
                <h1 class="welcome-text">Login Surveyor</h1>
                <p class="sub-welcome">Silahkan masuk untuk mulai melakukan survei lapangan.</p>

                <?php if (!empty($error_message)) : ?>
                    <div class="alert-error"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <?php if ($success_login) : ?>
                    <div class="alert-success">
                        <p style="font-weight: bold;">Anda berhasil masuk!</p>
                        <a href="surveyor_dashboard.php" class="btn-continue">Lanjutkan ke Dashboard</a>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="input-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="input-field" placeholder="Masukkan username surveyor" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="input-field" placeholder="Masukkan password" required>
                    </div>

                    <button type="submit" name="login" class="btn-login">Masuk ke Dashboard</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>