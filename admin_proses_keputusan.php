<?php
// 1. KONEKSI DATABASE
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sip_bantutani";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Koneksi gagal: " . $conn->connect_error); }

// 2. CEK APAKAH DATA POST DITERIMA
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    // Validasi status agar hanya bisa 'Diterima' atau 'Ditolak'
    if (($status === 'Diterima' || $status === 'Ditolak') && $id > 0) {
        
        // 3. UPDATE STATUS DI TABEL PENGAJUAN
        // Asumsi nama tabelnya adalah admin_pengajuanbantuan
        $stmt = $conn->prepare("UPDATE admin_pengajuanbantuan SET status_pengajuan = ? WHERE id_pengajuan = ?");
        $stmt->bind_param("si", $status, $id);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Status pengajuan berhasil diubah menjadi: $status');
                    window.location='admin_survey.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal memperbarui data!');
                    window.location='admin_surveydetail.php?id=$id';
                  </script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Data tidak valid!'); window.location='admin_survey.php';</script>";
    }
} else {
    // Jika diakses langsung tanpa POST, tendang balik ke list
    header("Location: admin_survey.php");
}

$conn->close();
?>