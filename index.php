<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP-BANTU TANI - Selamat Datang</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #fbc02d; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        
        .dashboard-container { 
            width: 100%; max-width: 1280px; height: 850px; max-height: 92vh; 
            background-color: white; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); 
            display: flex; overflow: hidden; 
        }

        .visual-side {
            flex: 55; background: url('bg-sawah.jpeg') no-repeat center center;
            background-size: cover; display: flex; justify-content: center;
            align-items: center; position: relative;
        }
        .visual-side::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.05);
        }

        .logo-bg {
            width: 280px; height: 280px; background-color: white; border-radius: 50%;
            display: flex; justify-content: center; align-items: center; overflow: hidden;
            border: 6px solid #13a851; box-shadow: 0 12px 35px rgba(0,0,0,0.25);
            position: relative; z-index: 2;
        }
        .logo-img { width: 100%; height: 100%; object-fit: cover; }

        .selection-side {
            flex: 45; background-color: #f7f9f6; display: flex;
            justify-content: center; align-items: center; padding: 40px;
        }
        .selection-box {
            width: 100%; max-width: 420px; background-color: #fffdf6;
            border: 1px solid rgba(13, 120, 57, 0.15); border-radius: 24px;
            padding: 40px 30px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        .selection-title { color: #0d7839; font-size: 20px; font-weight: 700; margin-bottom: 35px; }
        .button-group { display: flex; flex-direction: column; gap: 18px; }

        .btn-role {
            display: block; text-decoration: none; text-align: center;
            padding: 16px 0; border-radius: 12px; font-size: 16px; font-weight: 700;
            color: white; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .btn-petani { background: linear-gradient(135deg, #00ff37, #00db2e); }
        .btn-admin { background: linear-gradient(135deg, #0d7839, #0a5c2c); }
        .btn-surveyor { background: linear-gradient(135deg, #2eff94, #24db7f); }
        .btn-role:hover { transform: translateY(-2px); filter: brightness(1.1); }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="visual-side">
            <div class="logo-bg">
                <img src="logo.png" alt="Logo SIP-BANTU TANI" class="logo-img">
            </div>
        </div>
        <div class="selection-side">
            <div class="selection-box">
                <h2 class="selection-title">Silahkan pilih masuk sebagai:</h2>
                <div class="button-group">
                    <a href="#" class="btn-role btn-petani">Petani</a>
                    <a href="admin_login.php" class="btn-role btn-admin">Admin</a>
                    <a href="surveyor_login.php" class="btn-role btn-surveyor">Surveyor</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>