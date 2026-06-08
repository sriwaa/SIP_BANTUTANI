<?php
// BAGIAN MESIN
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "sip_bantutani");

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $nik           = mysqli_real_escape_string($conn, $_POST['nik']);
    $nama          = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $password      = mysqli_real_escape_string($conn, $_POST['password']);
    $alamat        = mysqli_real_escape_string($conn, $_POST['alamat']);
    $komoditas     = mysqli_real_escape_string($conn, $_POST['komoditas']);
    $no_telp       = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $tempat_lahir  = mysqli_real_escape_string($conn, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);

    $sql = "INSERT INTO admin_datapetani (nik, nama_lengkap, no_telp, komoditas, alamat, tempat_lahir, tanggal_lahir, password) 
            VALUES ('$nik', '$nama', '$no_telp', '$komoditas', '$alamat', '$tempat_lahir', '$tanggal_lahir', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Daftar Akun Berhasil! Silakan Login.'); window.location.href='petani_login.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP-BANTU TANI - Daftar Akun</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fbc02d; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }

        .login-container { 
            width: 100%; max-width: 1280px; height: 850px; max-height: 92vh; 
            background-color: white; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); 
            display: flex; overflow: hidden; position: relative; 
        }

        .brand-side { 
            width: 320px; background-color: #0d7839; color: white; display: flex; flex-direction: column; 
            justify-content: center; align-items: center; padding: 40px 20px; text-align: center; flex-shrink: 0; position: relative;
        }
        .back-nav { 
            position: absolute; top: 20px; left: 20px; color: white; text-decoration: none; 
            font-size: 16px; font-weight: 600; display: flex; align-items: center; gap: 8px; opacity: 0.8; transition: opacity 0.2s;
        }
        .back-nav:hover { opacity: 1; }

        .logo-bg { 
            width: 140px; height: 140px; background-color: white; border-radius: 50%; 
            display: flex; justify-content: center; align-items: center; overflow: hidden; 
            border: 3px solid #13a851; margin-bottom: 25px; box-shadow: 0 8px 20px rgba(0,0,0,0.15); 
        }
        .logo-img { width: 100%; height: 100%; object-fit: cover; }
        .brand-title { font-size: 22px; font-weight: bold; letter-spacing: 0.5px; margin-bottom: 6px; }
        .brand-subtitle { font-size: 13px; color: #d1e7dd; font-weight: 500; }

        .form-side { flex-grow: 1; background-color: white; padding: 40px 80px; display: flex; flex-direction: column; justify-content: center; align-items: center; overflow-y: auto; }
        .form-wrapper { width: 100%; max-width: 550px; }
        .welcome-text { color: #0d7839; font-size: 32px; font-weight: 700; margin-bottom: 30px; }
        
        .grid-container { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .input-group { width: 100%; margin-bottom: 15px; }
        .input-group label { display: block; font-size: 14px; font-weight: 600; color: #495057; margin-bottom: 8px; }
        .input-field, select.input-field { width: 100%; padding: 12px 15px; border: 1px solid #ced4da; border-radius: 8px; font-size: 14px; background-color: #f8f9fa; outline: none; }
        .input-field:focus { border-color: #13a851; background-color: white; }

        .btn-login { background-color: #13a851; color: white; border: none; width: 100%; padding: 15px; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 10px; }
        .btn-login:hover { background-color: #0f8a42; }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="brand-side">
            <a href="javascript:history.back()" class="back-nav"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
            <div class="logo-bg">
                <img src="logo.png" alt="Logo" class="logo-img">
            </div>
            <h2 class="brand-title">SIP-BANTU TANI</h2>
            <p class="brand-subtitle">Portal Resmi Bantuan Pertanian</p>
        </div>

        <div class="form-side">
            <div class="form-wrapper">
                <h1 class="welcome-text">Daftar Akun Petani</h1>
                <form action="" method="POST">
                    <div class="grid-container">
                        <div class="input-group"><label>NIK</label><input type="text" name="nik" class="input-field" required></div>
                        <div class="input-group"><label>Nama Lengkap</label><input type="text" name="nama_lengkap" class="input-field" required></div>
                        <div class="input-group"><label>Tempat Lahir</label><input type="text" name="tempat_lahir" class="input-field" required></div>
                        <div class="input-group"><label>Tanggal Lahir</label><input type="date" name="tanggal_lahir" class="input-field" required></div>
                        
                        <div class="input-group">
                            <label>Komoditas</label>
                            <select name="komoditas" class="input-field" required>
                                <option value="" disabled selected>Pilih Komoditas</option>
                                <option value="Padi">Padi</option>
                                <option value="Palawija">Palawija</option>
                                <option value="Hortikultura">Hortikultura</option>
                                <option value="Perkebunan">Perkebunan</option>
                            </select>
                        </div>
                        <div class="input-group"><label>No. Telp</label><input type="text" name="no_telp" class="input-field" required></div>
                    </div>
                    <div class="input-group"><label>Alamat</label><input type="text" name="alamat" class="input-field" required></div>
                    <div class="input-group"><label>Password</label><input type="password" name="password" class="input-field" required></div>
                    
                    <button type="submit" class="btn-login">Daftar Akun</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>