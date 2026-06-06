<?php
// 1. KONEKSI DATABASE
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sip_bantutani";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Koneksi gagal: " . $conn->connect_error); }

// 2. CEK APAKAH ADA PARAMETER NIK DAN STATUS
if (isset($_GET['nik']) && isset($_GET['status'])) {
    $nik = $conn->real_escape_string($_GET['nik']);
    $status = $conn->real_escape_string($_GET['status']);

    // Validasi status agar hanya bisa 'Diterima' atau 'Ditolak'
    if ($status === 'Diterima' || $status === 'Ditolak') {
        
        // Update status di tabel pengajuan bantuan
        $query = "UPDATE admin_pengajuanbantuan SET status_pengajuan = '$status' WHERE nik = '$nik'";
        
        if ($conn->query($query) === TRUE) {
            // Berhasil, redirect kembali ke halaman hasil survey dengan pesan sukses
            echo "<script>
                    alert('Status berhasil diupdate menjadi: $status');
                    window.location.href = 'admin_hasil_survey.php';
                  </script>";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "Status tidak valid.";
    }
} else {
    echo "Data tidak lengkap.";
}

$conn->close();
?>