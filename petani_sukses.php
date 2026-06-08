<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP-BANTU TANI - Akun Berhasil Dibuat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fbc02d; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        
        /* UKURAN BINGKAI DISAMAKAN PERSIS DENGAN INDEX & REGISTER */
        .success-container { 
            width: 100%; max-width: 1280px; height: 850px; max-height: 92vh; 
            background-color: #0d7839; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); 
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            text-align: center; color: white; padding: 40px;
        }

        /* CIRKULAR ICON CHECK BESAR */
        .check-icon-circle {
            width: 140px; height: 140px; border: 8px solid white; border-radius: 50%;
            display: flex; justify-content: center; align-items: center;
            font-size: 65px; color: white; margin-bottom: 45px;
        }

        /* GAYA TEKS */
        .success-title { font-size: 38px; font-weight: 700; margin-bottom: 12px; }
        .success-subtitle { font-size: 18px; opacity: 0.9; margin-bottom: 45px; font-weight: 400; }

        /* TOMBOL LANJUTKAN DENGAN WARNA HIJAU TERANG FIGMA */
        .btn-continue { 
            display: block; width: 100%; max-width: 380px; padding: 18px 0; 
            text-decoration: none; border-radius: 12px; font-size: 16px; font-weight: 700; 
            color: white; background: linear-gradient(135deg, #00ff37, #00db2e);
            box-shadow: 0 4px 15px rgba(0, 255, 55, 0.3); text-align: center; 
            transition: all 0.2s ease;
        }
        .btn-continue:hover { transform: translateY(-2px); filter: brightness(1.05); }
    </style>
</head>
<body>

    <div class="success-container">
        <div class="check-icon-circle">
            <i class="fa-solid fa-check"></i>
        </div>

        <h2 class="success-title">Akun Anda Berhasil Dibuat!</h2>
        <p class="success-subtitle">Silakan masuk untuk melanjutkan</p>

        <a href="petani_login.php" class="btn-continue">Lanjutkan</a>
    </div>

</body>
</html>