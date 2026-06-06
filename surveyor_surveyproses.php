<?php
session_start();
$conn = new mysqli("localhost", "root", "", "sip_bantutani");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nik = $_POST['nik'];
    $id_program = $_POST['id_program'];
    $action = $_POST['action']; // 'draft' atau 'final'

    // 1. Handle Upload File per Kriteria
    $uploaded_files = [];
    $kriteria = ['c1_luas_lahan', 'c2_penghasilan', 'c3_komoditas', 'c4_kondisi_lahan', 'c5_kepemilikan_alat'];
    
    foreach ($kriteria as $k) {
        $file_key = "file_$k";
        if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] == 0) {
            $ext = pathinfo($_FILES[$file_key]['name'], PATHINFO_EXTENSION);
            $new_name = $k . "_" . $nik . "_" . time() . "." . $ext;
            // Pastikan folder 'uploads/' sudah ada
            move_uploaded_file($_FILES[$file_key]['tmp_name'], "uploads/" . $new_name);
            $uploaded_files[$k] = $new_name;
        } else {
            $uploaded_files[$k] = null; 
        }
    }

    // 2. Update ke Tabel admin_survey
    // Kita mencari baris yang sesuai dengan id_pengajuan
    // Asumsi: id_pengajuan di admin_survey adalah relasi ke tabel pengajuan
    $sql = "UPDATE admin_survey SET 
            validasi_c1_luas_lahan = ?, catatan_c1_luas_lahan = ?, file_c1_luas_lahan = COALESCE(?, file_c1_luas_lahan),
            validasi_c2_penghasilan = ?, catatan_c2_penghasilan = ?, file_c2_penghasilan = COALESCE(?, file_c2_penghasilan),
            validasi_c3_komoditas = ?, catatan_c3_komoditas = ?, file_c3_komoditas = COALESCE(?, file_c3_komoditas),
            validasi_c4_kondisi_lahan = ?, catatan_c4_kondisi_lahan = ?, file_c4_kondisi_lahan = COALESCE(?, file_c4_kondisi_lahan),
            validasi_c5_kepemilikan_alat = ?, catatan_c5_kepemilikan_alat = ?, file_c5_kepemilikan_alat = COALESCE(?, file_c5_kepemilikan_alat)
            WHERE id_pengajuan = (SELECT id_pengajuan FROM admin_pengajuanbantuan WHERE nik = ? AND id_program = ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssssssii", 
        $_POST['validasi_c1_luas_lahan'], $_POST['catatan_c1_luas_lahan'], $uploaded_files['c1_luas_lahan'],
        $_POST['validasi_c2_penghasilan'], $_POST['catatan_c2_penghasilan'], $uploaded_files['c2_penghasilan'],
        $_POST['validasi_c3_komoditas'], $_POST['catatan_c3_komoditas'], $uploaded_files['c3_komoditas'],
        $_POST['validasi_c4_kondisi_lahan'], $_POST['catatan_c4_kondisi_lahan'], $uploaded_files['c4_kondisi_lahan'],
        $_POST['validasi_c5_kepemilikan_alat'], $_POST['catatan_c5_kepemilikan_alat'], $uploaded_files['c5_kepemilikan_alat'],
        $nik, $id_program
    );

    if ($stmt->execute()) {
        // 3. Update status di tabel utama
        $status = ($action == 'final') ? 'Selesai' : 'Draft';
        $conn->query("UPDATE admin_pengajuanbantuan SET status_pengajuan = '$status' WHERE nik = '$nik' AND id_program = '$id_program'");
        
        echo "<script>alert('Data berhasil disimpan sebagai $status!'); window.location='surveyor_tugaslapangan.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>