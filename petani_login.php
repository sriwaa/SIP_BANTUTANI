<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "sip_bantutani");
    
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $nik = mysqli_real_escape_string($conn, $_POST['nik']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM admin_datapetani WHERE nik = '$nik' AND password = '$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $_SESSION['nik'] = $data['nik'];
        $_SESSION['nama_lengkap'] = $data['nama_lengkap']; 
        header("Location: petani_dashboard.php");
        exit();
    } else {
        $error_message = "NIK atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP-BANTU TANI - Login Petani</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fbc02d; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }

        .login-container { 
            width: 100%; max-width: 1280px; height: 850px; max-height: 92vh; 
            background-color: white; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); 
            display: flex; overflow: hidden; position: relative; 
        }

        /* SIDEBAR IDENTIK ADMIN */
        .brand-side { 
            width: 320px; background-color: #0d7839; color: white; display: flex; flex-direction: column; 
            justify-content: center; align-items: center; padding: 40px 20px; text-align: center; flex-shrink: 0; position: relative;
        }
        .back-nav { 
            position: absolute; top: 20px; left: 20px; color: white; text-decoration: none; 
            font-size: 16px; font-weight: 600; display: flex; align-items: center; gap: 8px; opacity: 0.8; transition: opacity 0.2s;
        }
        .back-nav:hover { opacity: 1; }

        /* LOGO BULAT SEMPURNA */
        .logo-bg { 
            width: 140px; height: 140px; background-color: white; border-radius: 50%; 
            display: flex; justify-content: center; align-items: center; overflow: hidden; 
            border: 3px solid #13a851; margin-bottom: 25px; box-shadow: 0 8px 20px rgba(0,0,0,0.15); 
        }
        .logo-img { width: 100%; height: 100%; object-fit: cover; }
        
        .brand-title { font-size: 22px; font-weight: bold; letter-spacing: 0.5px; margin-bottom: 6px; }
        .brand-subtitle { font-size: 13px; color: #d1e7dd; font-weight: 500; }

        /* FORM SIDE */
        .form-side { flex-grow: 1; background-color: white; padding: 40px 80px; display: flex; flex-direction: column; justify-content: center; align-items: center; }
        .form-wrapper { width: 100%; max-width: 480px; }
        .welcome-text { color: #0d7839; font-size: 32px; font-weight: 700; margin-bottom: 8px; }
        .sub-welcome { color: #6c757d; font-size: 15px; margin-bottom: 45px; }
        .alert-error { background-color: #f8d7da; color: #842029; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; border: 1px solid #f5c2c7; }
        
        .input-group { width: 100%; margin-bottom: 25px; }
        .input-group label { display: block; font-size: 14px; font-weight: 600; color: #495057; margin-bottom: 10px; }
        .input-field { width: 100%; padding: 15px 18px; border: 1px solid #ced4da; border-radius: 8px; font-size: 15px; outline: none; background-color: #f8f9fa; }
        .input-field:focus { border-color: #13a851; background-color: white; box-shadow: 0 0 0 3px rgba(19, 168, 81, 0.15); }

        .actions-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 35px; font-size: 14px; }
        .forgot-password { color: #0d7839; text-decoration: none; font-weight: 600; }
        .btn-login { background-color: #13a851; color: white; border: none; width: 100%; padding: 15px; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer; transition: background-color 0.2s; }
        .btn-login:hover { background-color: #0f8a42; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="brand-side">
            <a href="index.php" class="back-nav"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
            <div class="logo-bg">
                <img src="logo.png" alt="Logo" class="logo-img">
            </div>
            <h2 class="brand-title">SIP-BANTU TANI</h2>
            <p class="brand-subtitle">Portal Resmi Bantuan Pertanian</p>
        </div>

        <div class="form-side">
            <div class="form-wrapper">
                <h1 class="welcome-text">Login Petani</h1>
                <p class="sub-welcome">Silahkan masukkan NIK dan password untuk mengakses dashboard.</p>
                <?php if (isset($error_message)) : ?>
                    <div class="alert-error"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <form action="" method="POST">
                    <div class="input-group">
                        <label>NIK</label>
                        <input type="text" name="nik" class="input-field" placeholder="Masukkan NIK" required>
                    </div>
                    <div class="input-group">
                        <label>Password</label>
                        <input type="password" name="password" class="input-field" placeholder="Masukkan password" required>
                    </div>
                    <div class="actions-row">
                        <label><input type="checkbox"> Ingat Saya</label>
                        <a href="petani_lupapasword.php" class="forgot-password">Lupa Password?</a>
                    </div>
                    <button type="submit" class="btn-login">Masuk ke Akun</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>