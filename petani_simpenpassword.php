<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP-BANTU TANI - Sukses Reset</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fbc02d; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        
        /* FRAME UTAMA DASHBOARD WIDE (Konsisten 1280x850 seperti halaman sebelumnya) */
        .dashboard-container { 
            width: 100%; max-width: 1280px; height: 850px; max-height: 92vh; 
            background-color: white; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); 
            display: flex; overflow: hidden; 
        }

        /* SISI KIRI (HIJAU TUA FIGMA) */
        .modal-visual-side {
            flex: 55; background-color: #0d7839; display: flex; flex-direction: column;
            justify-content: center; align-items: center; padding: 60px; color: white; position: relative;
            text-align: center;
        }
        
        /* Ikon Gembok Besar Sisi Kiri */
        .lock-icon-large { font-size: 80px; margin-bottom: 25px; color: #ffffff; }
        .welcome-title { font-size: 42px; font-weight: 700; margin-bottom: 16px; }
        .welcome-subtitle { font-size: 18px; opacity: 0.9; line-height: 1.6; max-width: 440px; }

        /* SISI KANAN (TAMPILAN CARD NOTIFIKASI SUKSES) */
        .modal-form-side { 
            flex: 45; background-color: #f7f9f6; display: flex; 
            justify-content: center; align-items: center; padding: 40px; 
        }
        .form-section-box { 
            width: 100%; max-width: 440px; background-color: #ffffff; 
            border: 1px solid rgba(13, 120, 57, 0.15); border-radius: 24px; 
            padding: 45px 35px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        /* CARD HIJAU TERANG (Sesuai Gambar Mockup Sukses) */
        .success-card {
            background-color: #52f075; /* Hijau muda cerah figma */
            border-radius: 36px; /* Lengkungan tebal khas desain figma */
            padding: 40px 25px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(82, 240, 117, 0.25);
            margin-bottom: 15px;
        }
        .success-card h3 {
            font-size: 21px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 30px;
            line-height: 1.3;
        }
        .success-check-icon {
            font-size: 90px; 
            color: #1ed760; /* Warna centang hijau kontras cerah */
            margin: 0 auto 30px auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .success-card p {
            font-size: 14px;
            color: #000000;
            font-weight: 500;
            line-height: 1.6;
        }
        
        /* TOMBOL MASUK SEKARANG (Hijau Terang Menyala) */
        .btn-submit { 
            display: block; width: 100%; padding: 16px 0; border: none; border-radius: 12px; 
            font-size: 16px; font-weight: 700; color: white; cursor: pointer; 
            background: linear-gradient(135deg, #00ff37, #00db2e);
            box-shadow: 0 4px 15px rgba(0, 255, 55, 0.3); text-align: center; 
            transition: all 0.2s ease; margin-top: 35px; text-decoration: none;
        }
        .btn-submit:hover { transform: translateY(-2px); filter: brightness(1.05); }
    </style>
</head>
<body>

    <div class="dashboard-container">
        
        <div class="modal-visual-side">
            <i class="fa-solid fa-lock lock-icon-large"></i>
            <h2 class="welcome-title">Reset Password</h2>
            <p class="welcome-subtitle">Silahkan masukkan password baru Anda untuk memperbarui akses akun</p>
        </div>
        
        <div class="modal-form-side">
            <div class="form-section-box">
                
                <div class="success-card">
                    <h3>Kata Sandi Berhasil Diperbarui</h3>
                    
                    <div class="success-check-icon">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    
                    <p>Sekarang anda bisa masuk kembali menggunakan password baru untuk mengakses layanan SIP-Bantu Tani</p>
                </div>
                
                <a href="petani_login.php" class="btn-submit">Masuk Sekarang</a>
                
            </div>
        </div>

    </div>

</body>
</html>