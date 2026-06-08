<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SIP - SIP-BANTU TANI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #fbc02d; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        
        .dashboard-container { width: 100%; max-width: 1280px; height: 850px; max-height: 92vh; background-color: white; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); display: flex; overflow: hidden; }
        
        .modal-visual-side { flex: 55; background-color: #0d7839; display: flex; flex-direction: column; justify-content: center; padding: 60px; color: white; }
        
        .modal-form-side { flex: 45; padding: 30px; background-color: #f7f9f6; display: flex; flex-direction: column; position: relative; }
        .main-content { flex-grow: 1; overflow-y: auto; scrollbar-width: none; }
        .main-content::-webkit-scrollbar { display: none; }

        .card-bantuan { border-radius: 15px; border: none; padding: 15px; background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 15px; display: flex; align-items: center; }
        .btn-detail { background-color: #0d7839; color: white; font-size: 12px; padding: 5px 15px; border-radius: 5px; text-decoration: none; }

        .nav-bottom { padding-top: 15px; padding-bottom: 5px; background: white; display: flex; justify-content: space-around; border-top: 1px solid #eee; margin-top: auto; }
    </style>
</head>
<body>

<div class="dashboard-container">
    <div class="modal-visual-side">
        <h2>Pilih Jenis Bantuan</h2>
        <p>Silakan pilih kategori bantuan yang ingin Anda ajukan.</p>
    </div>
    
    <div class="modal-form-side">
        <div class="main-content">
            <h5 class="text-center mb-4">Pilih jenis bantuan yang tersedia</h5>
            
            <?php
            // Array bantuan dengan link file detail yang spesifik
            $list_bantuan = [
                ["pupuk.jpeg", "Pupuk Bersubsidi", "Bantuan pupuk untuk meningkatkan produktivitas tanam.", "petani_dtlpupuk.php"],
                ["benih.jpeg", "Benih Unggul", "Bantuan benih unggul bersertifikat untuk hasil panen berkualitas.", "petani_dtlbenih.php"],
                ["alsintan.jpeg", "Alsintan", "Bantuan alat dan mesin pertanian untuk efisiensi usaha tani.", "petani_dtlalsintan.php"],
                ["pestisida.jpeg", "Pestisida nabati", "Bantuan pestisida nabati ramah lingkungan untuk perlindungan tanaman.", "petani_dtlpestisida.php"],
                ["irigasi.jpeg", "Irigasi / Pompa Air", "Bantuan sarana irigasi atau pompa air untuk kebutuhan air pertanian", "petani_dtlirigasi.php"]
            ];
            
            foreach ($list_bantuan as $item) {
                echo '<div class="card-bantuan">
                        <img src="images/'.$item[0].'" style="width: 50px; height: 50px; object-fit: contain;" alt="Icon">
                        <div class="ms-3 flex-grow-1">
                            <h6 class="fw-bold mb-0" style="font-size: 14px;">'.$item[1].'</h6>
                            <small class="text-muted" style="font-size: 11px;">'.$item[2].'</small>
                        </div>
                        <a href="'.$item[3].'" class="btn-detail">Detail</a>
                      </div>';
            }
            ?>
        </div>

        <div class="nav-bottom">
            <a href="petani_dashboard.php" style="color: #666; text-decoration: none; text-align: center;">
                <i class="fa-solid fa-house" style="display: block; font-size: 20px;"></i>
                <span style="font-size: 10px; font-weight: bold;">Home</span>
            </a>
            <a href="petani_sip.php" style="color: #0d7839; text-decoration: none; text-align: center;">
                <i class="fa-solid fa-file-lines" style="display: block; font-size: 20px;"></i>
                <span style="font-size: 10px; font-weight: bold;">SIP</span>
            </a>
            <a href="profile.php" style="color: #666; text-decoration: none; text-align: center;">
                <i class="fa-solid fa-user" style="display: block; font-size: 20px;"></i>
                <span style="font-size: 10px; font-weight: bold;">Profile</span>
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>