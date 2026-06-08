<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP-BANTU TANI - Kode OTP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fbc02d; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        
        /* FRAME UTAMA DASHBOARD WIDE (Sama persis dengan format login & lupa password kamu) */
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
        
        /* Ikon Gembok di Atas Teks Sesuai Gambar Mockup 57 */
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
            text-align: center;
        }
        .form-group { margin-bottom: 25px; text-align: left; }
        .form-group label { display: block; font-size: 16px; font-weight: 700; color: #000000; margin-bottom: 20px; }
        
        /* CONTAINER 4 KOTAK INPUT OTP (Berjejer Sesuai Gambar Mockup) */
        .otp-inputs-container {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 20px;
        }
        .otp-inputs-container input {
            width: 70px;
            height: 70px;
            border: 1px solid #cccccc;
            border-radius: 14px;
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            color: #000000;
            outline: none;
            background-color: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
            transition: border-color 0.2s;
        }
        .otp-inputs-container input:focus {
            border-color: #0d7839;
        }

        /* TEKS KETERANGAN & LINK KIRIM ULANG KODE (Hijau Sesuai Gambar) */
        .info-text { font-size: 12px; color: #666666; margin-bottom: 6px; }
        .resend-link { display: block; margin-bottom: 35px; font-size: 13px; color: #0d7839; font-weight: 600; text-decoration: none; }
        .resend-link:hover { text-decoration: underline; }
        
        /* TOMBOL VERIFIKASI (Hijau Terang Menyala) */
        .btn-submit { 
            display: block; width: 100%; padding: 16px 0; border: none; border-radius: 12px; 
            font-size: 16px; font-weight: 700; color: white; cursor: pointer; 
            background: linear-gradient(135deg, #00ff37, #00db2e);
            box-shadow: 0 4px 15px rgba(0, 255, 55, 0.3); text-align: center; 
            transition: all 0.2s ease;
        }
        .btn-submit:hover { transform: translateY(-2px); filter: brightness(1.05); }
    </style>
</head>
<body>

    <div class="dashboard-container">
        
        <!-- SISI KIRI: BANNER DENGAN IKON GEMBOK & TEKS SAKTI -->
        <div class="modal-visual-side">
            <!-- Panah kembali ke halaman lupa password -->
            <a href="petani_lupapasword.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i></a>
            
            <i class="fa-solid fa-lock lock-icon-large"></i>
            <h2 class="welcome-title">Kode OTP</h2>
            <p class="welcome-subtitle">Masukkan kode OTP yang dikirim</p>
        </div>
        
        <!-- SISI KANAN: FORM DENGAN 4 KOTAK INPUT OTP -->
        <div class="modal-form-side">
            <div class="form-section-box">
                <!-- 🛠️ DI SINI: Action sudah ditambahkan mengarah ke file petani_resetpassword.php kamu -->
                <form action="petani_resetpassword.php" method="POST">
                    
                    <div class="form-group">
                        <label>Kode OTP</label>
                        
                        <!-- 4 Kotak Input OTP Berjejer Sesuai Gambar Mockup -->
                        <div class="otp-inputs-container">
                            <input type="text" maxlength="1" oninput="moveNext(this, 'otp2')" id="otp1" required autocomplete="off">
                            <input type="text" maxlength="1" oninput="moveNext(this, 'otp3')" id="otp2" required autocomplete="off">
                            <input type="text" maxlength="1" oninput="moveNext(this, 'otp4')" id="otp3" required autocomplete="off">
                            <input type="text" maxlength="1" id="otp4" required autocomplete="off">
                        </div>
                    </div>

                    <!-- KETERANGAN BAWAH -->
                    <p class="info-text">Belum menerima kode?</p>
                    <a href="#" class="resend-link">Kirim ulang kode</a>
                    
                    <!-- TOMBOL VERIFIKASI -->
                    <button type="submit" class="btn-submit">Verifikasi</button>
                </form>
            </div>
        </div>

    </div>

    <!-- Script auto-focus pindah kotak otomatis saat diketik -->
    <script>
        function moveNext(current, nextInputId) {
            if (current.value.length >= 1) {
                document.getElementById(nextInputId).focus();
            }
        }
    </script>
</body>
</html>