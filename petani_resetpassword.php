<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP-BANTU TANI - Reset Password</title>
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
        .back-btn { 
            position: absolute; top: 40px; left: 40px; color: white; 
            text-decoration: none; font-size: 24px; cursor: pointer; 
            border: none; background: none; transition: transform 0.2s;
        }
        .back-btn:hover { transform: translateX(-5px); }
        
        /* Ikon Gembok di Sisi Kiri Sesuai Atribut Mockup */
        .lock-icon-large { font-size: 80px; margin-bottom: 25px; color: #ffffff; }
        .welcome-title { font-size: 42px; font-weight: 700; margin-bottom: 16px; }
        .welcome-subtitle { font-size: 18px; opacity: 0.9; line-height: 1.6; max-width: 440px; }

        /* SISI KANAN (BOX FORM INPUT) */
        .modal-form-side { 
            flex: 45; background-color: #f7f9f6; display: flex; 
            justify-content: center; align-items: center; padding: 40px; 
        }
        .form-section-box { 
            width: 100%; max-width: 440px; background-color: #ffffff; 
            border: 1px solid rgba(13, 120, 57, 0.15); border-radius: 24px; 
            padding: 45px 35px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .form-group { margin-bottom: 24px; text-align: left; }
        .form-group label { display: block; font-size: 15px; font-weight: 700; color: #000000; margin-bottom: 10px; }
        .input-wrapper { position: relative; display: flex; align-items: center; }
        
        /* Ikon Gembok Line Art di Dalam Input Sesuai Gambar Mockup 58 */
        .input-wrapper i.input-icon { position: absolute; left: 18px; color: #333333; font-size: 16px; }
        .input-wrapper i.toggle-password { position: absolute; right: 18px; color: #666666; cursor: pointer; font-size: 16px; }
        .input-wrapper input { 
            width: 100%; padding: 15px 15px 15px 48px; border: 1px solid #dcdcdc; 
            border-radius: 12px; font-size: 14px; outline: none; background-color: #ffffff; 
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03); 
        }
        
        /* TOMBOL SIMPAN PASSWORD (Hijau Terang Menyala Sesuai Mockup) */
        .btn-submit { 
            display: block; width: 100%; padding: 16px 0; border: none; border-radius: 12px; 
            font-size: 16px; font-weight: 700; color: white; cursor: pointer; 
            background: linear-gradient(135deg, #00ff37, #00db2e);
            box-shadow: 0 4px 15px rgba(0, 255, 55, 0.3); text-align: center; 
            transition: all 0.2s ease; margin-top: 35px;
        }
        .btn-submit:hover { transform: translateY(-2px); filter: brightness(1.05); }
    </style>
</head>
<body>

    <div class="dashboard-container">
        
        <div class="modal-visual-side">
            <a href="petani_otp.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i></a>
            
            <i class="fa-solid fa-lock lock-icon-large"></i>
            <h2 class="welcome-title">Reset Password</h2>
            <p class="welcome-subtitle">Silahkan masukkan password baru Anda untuk memperbarui akses akun</p>
        </div>
        
        <div class="modal-form-side">
            <div class="form-section-box">
                <form action="petani_simpenpassword.php" method="POST">
                    
                    <div class="form-group">
                        <label>Password Baru</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-lock input-icon"></i>
                            <input type="password" id="pass_baru" name="pass_baru" placeholder="Masukkan Password Baru" required>
                            <i class="fa-regular fa-eye toggle-password" id="eye1" onclick="togglePass('pass_baru', 'eye1')"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Konfirmasi Password</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-lock input-icon"></i>
                            <input type="password" id="konfirmasi_pass" name="konfirmasi_pass" placeholder="Konfirmasi Password" required>
                            <i class="fa-regular fa-eye toggle-password" id="eye2" onclick="togglePass('konfirmasi_pass', 'eye2')"></i>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-submit">Simpan Password</button>
                </form>
            </div>
        </div>

    </div>

    <script>
        function togglePass(inputId, iconId) {
            var x = document.getElementById(inputId);
            var icon = document.getElementById(iconId);
            if (x.type === "password") {
                x.type = "text";
                icon.className = "fa-solid fa-eye-slash toggle-password";
            } else {
                x.type = "password";
                icon.className = "fa-regular fa-eye toggle-password";
            }
        }
    </script>
</body>
</html>