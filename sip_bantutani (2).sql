-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Jun 2026 pada 12.40
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sip_bantutani`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin_datapetani`
--

CREATE TABLE `admin_datapetani` (
  `id` int(11) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `komoditas` varchar(50) DEFAULT '-',
  `alamat` text DEFAULT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin_datapetani`
--

INSERT INTO `admin_datapetani` (`id`, `nik`, `nama_lengkap`, `no_telp`, `komoditas`, `alamat`, `tempat_lahir`, `tanggal_lahir`, `password`) VALUES
(1, '3305110101800001', 'Hilda Sava Alzena', '081111111111', 'Padi', 'Desa Kutosari, RT 02/RW 03, Kec. Kebumen, Kebumen', 'Kebumen', '1999-01-01', 'Hilda123'),
(2, '3305121503850002', 'Fahrzatul', '081222222222', 'Palawija', 'Desa Muktisari, RT 01/RW 04, Kec. Kebumen, Kebumen', 'Kebumen', '1999-03-15', 'Fahrzatul123'),
(3, '3305112109770003', 'Sri Wahyuningsih', '081333333333', 'Hortikultura', 'Desa Panjer, RT 03/RW 01, Kec. Kebumen, Kebumen', 'Kebumen', '1999-09-21', 'Sri123'),
(4, '3305150604700004', 'Syahriza', '081444444444', 'Palawija', 'Desa Pejagoan, RT 02/RW 02, Kec. Pejagoan, Kebumen', 'Kebumen', '1999-04-06', 'Syahriza123'),
(5, '3305110603910005', 'Alvira Libra Ramdhani', '081555555555', 'Padi', 'Desa Kawedusan, RT 04/RW 02, Kec. Kebumen, Kebumen', 'Kebumen', '1999-03-06', 'Alvira123'),
(6, '3305162701840006', 'Farhan Zhaldi', '081666666666', 'Perkebunan', 'Desa Karanganyar, RT 01/RW 01, Kec. Karanganyar, Kebumen', 'Kebumen', '1999-01-27', 'Farhan123'),
(7, '3305201012720007', 'Talita', '081777777777', 'Hortikultura', 'Desa Gombong, RT 05/RW 03, Kec. Gombong, Kebumen', 'Kebumen', '1999-12-10', 'Talita123'),
(10, '3305009987651123', 'Zen', '081212121212', 'Palawija', 'Desa Candiwulan, Kec. Kebumen Kab Kebumen, Jawa Tengah', 'Kebumen', '2000-06-06', 'Zen999');

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin_hasilwp`
--

CREATE TABLE `admin_hasilwp` (
  `id_hasil` int(11) NOT NULL,
  `id_program` int(11) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `vektor_s` double NOT NULL,
  `vektor_v` double NOT NULL,
  `ranking` int(11) NOT NULL,
  `tgl_hitung` timestamp NOT NULL DEFAULT current_timestamp(),
  `c1_skor` float DEFAULT NULL,
  `c1_bobot` float DEFAULT NULL,
  `c1_vs` float DEFAULT NULL,
  `c1_vk` float DEFAULT NULL,
  `c2_skor` float DEFAULT NULL,
  `c2_bobot` float DEFAULT NULL,
  `c2_vs` float DEFAULT NULL,
  `c2_vk` float DEFAULT NULL,
  `c3_skor` float DEFAULT NULL,
  `c3_bobot` float DEFAULT NULL,
  `c3_vs` float DEFAULT NULL,
  `c3_vk` float DEFAULT NULL,
  `c4_skor` float DEFAULT NULL,
  `c4_bobot` float DEFAULT NULL,
  `c4_vs` float DEFAULT NULL,
  `c4_vk` float DEFAULT NULL,
  `c5_skor` float DEFAULT NULL,
  `c5_bobot` float DEFAULT NULL,
  `c5_vs` float DEFAULT NULL,
  `c5_vk` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin_hasilwp`
--

INSERT INTO `admin_hasilwp` (`id_hasil`, `id_program`, `nik`, `vektor_s`, `vektor_v`, `ranking`, `tgl_hitung`, `c1_skor`, `c1_bobot`, `c1_vs`, `c1_vk`, `c2_skor`, `c2_bobot`, `c2_vs`, `c2_vk`, `c3_skor`, `c3_bobot`, `c3_vs`, `c3_vk`, `c4_skor`, `c4_bobot`, `c4_vs`, `c4_vk`, `c5_skor`, `c5_bobot`, `c5_vs`, `c5_vk`) VALUES
(953, 3, '3305110101800001', 1.6206565966928, 0.20879775322409, 1, '2026-06-05 07:09:36', 5, 0.15, 1.27305, 0.75, 1, -0.25, 1, -0.25, 5, 0.15, 1.27305, 0.75, 1, 0.15, 1, 0.15, 1, -0.3, 1, -0.3),
(954, 3, '3305121503850002', 0.78854355618519, 0.1015922331645, 2, '2026-06-05 07:09:36', 3, 0.15, 1.17915, 0.45, 5, -0.25, 0.66874, -1.25, 1, 0.15, 1, 0.15, 1, 0.15, 1, 0.15, 1, -0.3, 1, -0.3),
(955, 3, '3305112109770003', 0.75983568565159, 0.097893646505554, 3, '2026-06-05 07:09:36', 1, 0.15, 1, 0.15, 3, -0.25, 0.759836, -0.75, 1, 0.15, 1, 0.15, 1, 0.15, 1, 0.15, 1, -0.3, 1, -0.3),
(956, 3, '3305150604700004', 0.85133992252078, 0.10968262086802, 4, '2026-06-05 07:09:36', 5, 0.15, 1.27305, 0.75, 5, -0.25, 0.66874, -1.25, 1, 0.15, 1, 0.15, 1, 0.15, 1, 0.15, 1, -0.3, 1, -0.3),
(957, 3, '3305110603910005', 1.501114046581, 0.19339645480654, 5, '2026-06-05 07:09:36', 3, 0.15, 1.17915, 0.45, 1, -0.25, 1, -0.25, 5, 0.15, 1.27305, 0.75, 1, 0.15, 1, 0.15, 1, -0.3, 1, -0.3),
(958, 3, '3305162701840006', 0.96730890741506, 0.12462351799516, 6, '2026-06-05 07:09:36', 5, 0.15, 1.27305, 0.75, 3, -0.25, 0.759836, -0.75, 1, 0.15, 1, 0.15, 1, 0.15, 1, 0.15, 1, -0.3, 1, -0.3),
(959, 3, '3305201012720007', 1.2730501155464, 0.16401377343614, 7, '2026-06-05 07:09:36', 5, 0.15, 1.27305, 0.75, 1, -0.25, 1, -0.25, 1, 0.15, 1, 0.15, 1, 0.15, 1, 0.15, 1, -0.3, 1, -0.3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin_notifikasi`
--

CREATE TABLE `admin_notifikasi` (
  `id_notif` int(11) NOT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `pesan` text DEFAULT NULL,
  `is_read` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin_notifikasi`
--

INSERT INTO `admin_notifikasi` (`id_notif`, `nik`, `pesan`, `is_read`, `created_at`) VALUES
(4, '3305110101800001', 'Selamat! Pengajuan Anda telah berhasil diproses ke Tahap Survey.', 0, '2026-05-28 11:26:07'),
(5, '3305110603910005', 'Selamat! Pengajuan Anda telah berhasil diproses ke Tahap Survey.', 0, '2026-05-28 11:26:07'),
(6, '3305201012720007', 'Selamat! Pengajuan Anda telah berhasil diproses ke Tahap Survey.', 0, '2026-05-28 11:26:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin_pengajuanbantuan`
--

CREATE TABLE `admin_pengajuanbantuan` (
  `id_pengajuan` int(11) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `id_program` int(11) NOT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `c1_luas_lahan` varchar(100) DEFAULT NULL,
  `c2_penghasilan` varchar(100) DEFAULT NULL,
  `c3_komoditas` varchar(100) DEFAULT NULL,
  `c4_kondisi_lahan` varchar(100) DEFAULT NULL,
  `c5_kepemilikan_alat` varchar(100) DEFAULT NULL,
  `status_pengajuan` varchar(50) DEFAULT 'Diproses',
  `bukti_survey` varchar(255) DEFAULT NULL,
  `catatatan_survey` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin_pengajuanbantuan`
--

INSERT INTO `admin_pengajuanbantuan` (`id_pengajuan`, `nik`, `id_program`, `tanggal_pengajuan`, `c1_luas_lahan`, `c2_penghasilan`, `c3_komoditas`, `c4_kondisi_lahan`, `c5_kepemilikan_alat`, `status_pengajuan`, `bukti_survey`, `catatatan_survey`) VALUES
(29, '3305110101800001', 1, '2026-05-24', '> 2 Hektar', 'Rp2.501.000 - Rp4.000.000', 'Padi', 'Lahan Irigasi Teknis', 'Hanya memiliki alat manual', 'Diproses', NULL, NULL),
(30, '3305121503850002', 1, '2026-05-24', '0,5 - 1 Hektar', '< Rp1.000.000', 'Palawija', 'Lahan Kering/Gambut', 'Memiliki alat semi-mekanis', 'Diproses', NULL, NULL),
(31, '3305112109770003', 1, '2026-05-24', '< 0,5 Hektar', 'Rp1.000.000 - Rp2.500.000', 'Hortikultura', 'Lahan Tadah Hujan', 'Sudah memiliki alat mesin', 'Diproses', NULL, NULL),
(32, '3305150604700004', 1, '2026-05-24', '1,1 - 2 Hektar', '< Rp1.000.000', 'Palawija', 'Lahan Kritis', 'Hanya memiliki alat manual', 'Diproses', NULL, NULL),
(33, '3305110603910005', 1, '2026-05-24', '0,5 - 1 Hektar', '> Rp4.000.000', 'Padi', 'Lahan Tadah Hujan', 'Memiliki alat semi-mekanis', 'Diproses', NULL, NULL),
(34, '3305162701840006', 1, '2026-05-24', '> 2 Hektar', 'Rp1.000.000 - Rp2.500.000', 'Perkebunan', 'Lahan Irigasi Teknis', 'Tidak memiliki alat sama sekali', 'Diproses', NULL, NULL),
(35, '3305201012720007', 1, '2026-05-24', '1,1 - 2 Hektar', 'Rp2.501.000 - Rp4.000.000', 'Hortikultura', 'Lahan Kering/Gambut', 'Memiliki alat semi-mekanis', 'Diproses', NULL, NULL),
(36, '3305110101800001', 2, '2026-05-24', '1,1 - 2 Hektar', 'Rp1.000.000 - Rp2.500.000', 'Padi', 'Lahan Tadah Hujan', 'Hanya memiliki alat manual', 'Diproses', NULL, NULL),
(37, '3305121503850002', 2, '2026-05-24', '< 0,5 Hektar', '< Rp1.000.000', 'Palawija', 'Lahan Kritis', 'Sudah memiliki alat mesin', 'Diproses', NULL, NULL),
(38, '3305112109770003', 2, '2026-05-24', '0,5 - 1 Hektar', 'Rp2.501.000 - Rp4.000.000', 'Hortikultura', 'Lahan Kering/Gambut', 'Memiliki alat semi-mekanis', 'Diproses', NULL, NULL),
(39, '3305150604700004', 2, '2026-05-24', '> 2 Hektar', '< Rp1.000.000', 'Palawija', 'Lahan Irigasi Teknis', 'Tidak memiliki alat sama sekali', 'Diproses', NULL, NULL),
(40, '3305110603910005', 2, '2026-05-24', '1,1 - 2 Hektar', 'Rp2.501.000 - Rp4.000.000', 'Padi', 'Lahan Tadah Hujan', 'Hanya memiliki alat manual', 'Diproses', NULL, NULL),
(41, '3305162701840006', 2, '2026-05-24', '0,5 - 1 Hektar', 'Rp1.000.000 - Rp2.500.000', 'Perkebunan', 'Lahan Kritis', 'Memiliki alat semi-mekanis', 'Diproses', NULL, NULL),
(42, '3305201012720007', 2, '2026-05-24', '< 0,5 Hektar', '> Rp4.000.000', 'Hortikultura', 'Lahan Kering/Gambut', 'Sudah memiliki alat mesin', 'Diproses', NULL, NULL),
(43, '3305110101800001', 3, '2026-05-24', '> 2 Hektar', 'Rp2.501.000 - Rp4.000.000', 'Padi', 'Lahan Irigasi Teknis', 'Hanya memiliki alat manual', 'Tahap Survey', NULL, NULL),
(44, '3305121503850002', 3, '2026-05-24', '0,5 - 1 Hektar', '< Rp1.000.000', 'Palawija', 'Lahan Kering/Gambut', 'Memiliki alat semi-mekanis', 'Diproses', NULL, NULL),
(45, '3305112109770003', 3, '2026-05-24', '< 0,5 Hektar', 'Rp1.000.000 - Rp2.500.000', 'Hortikultura', 'Lahan Tadah Hujan', 'Sudah memiliki alat mesin', 'Diproses', NULL, NULL),
(46, '3305150604700004', 3, '2026-05-24', '1,1 - 2 Hektar', '< Rp1.000.000', 'Palawija', 'Lahan Kritis', 'Hanya memiliki alat manual', 'Diproses', NULL, NULL),
(47, '3305110603910005', 3, '2026-05-24', '0,5 - 1 Hektar', '> Rp4.000.000', 'Padi', 'Lahan Tadah Hujan', 'Memiliki alat semi-mekanis', 'Tahap Survey', NULL, NULL),
(48, '3305162701840006', 3, '2026-05-24', '> 2 Hektar', 'Rp1.000.000 - Rp2.500.000', 'Perkebunan', 'Lahan Irigasi Teknis', 'Tidak memiliki alat sama sekali', 'Diproses', NULL, NULL),
(49, '3305201012720007', 3, '2026-05-24', '1,1 - 2 Hektar', 'Rp2.501.000 - Rp4.000.000', 'Hortikultura', 'Lahan Kering/Gambut', 'Memiliki alat semi-mekanis', 'Tahap Survey', NULL, NULL),
(50, '3305110101800001', 4, '2026-05-24', '1,1 - 2 Hektar', 'Rp1.000.000 - Rp2.500.000', 'Padi', 'Lahan Tadah Hujan', 'Hanya memiliki alat manual', 'Diproses', NULL, NULL),
(51, '3305121503850002', 4, '2026-05-24', '< 0,5 Hektar', '< Rp1.000.000', 'Palawija', 'Lahan Kritis', 'Sudah memiliki alat mesin', 'Diproses', NULL, NULL),
(52, '3305112109770003', 4, '2026-05-24', '0,5 - 1 Hektar', 'Rp2.501.000 - Rp4.000.000', 'Hortikultura', 'Lahan Kering/Gambut', 'Memiliki alat semi-mekanis', 'Diproses', NULL, NULL),
(53, '3305150604700004', 4, '2026-05-24', '> 2 Hektar', '< Rp1.000.000', 'Palawija', 'Lahan Irigasi Teknis', 'Tidak memiliki alat sama sekali', 'Diproses', NULL, NULL),
(54, '3305110603910005', 4, '2026-05-24', '1,1 - 2 Hektar', 'Rp2.501.000 - Rp4.000.000', 'Padi', 'Lahan Tadah Hujan', 'Hanya memiliki alat manual', 'Diproses', NULL, NULL),
(55, '3305162701840006', 4, '2026-05-24', '0,5 - 1 Hektar', 'Rp1.000.000 - Rp2.500.000', 'Perkebunan', 'Lahan Kritis', 'Memiliki alat semi-mekanis', 'Diproses', NULL, NULL),
(56, '3305201012720007', 4, '2026-05-24', '< 0,5 Hektar', '> Rp4.000.000', 'Hortikultura', 'Lahan Kering/Gambut', 'Sudah memiliki alat mesin', 'Diproses', NULL, NULL),
(57, '3305110101800001', 5, '2026-05-24', '> 2 Hektar', 'Rp2.501.000 - Rp4.000.000', 'Padi', 'Lahan Irigasi Teknis', 'Hanya memiliki alat manual', 'Diproses', NULL, NULL),
(58, '3305121503850002', 5, '2026-05-24', '0,5 - 1 Hektar', '< Rp1.000.000', 'Palawija', 'Lahan Kering/Gambut', 'Memiliki alat semi-mekanis', 'Diproses', NULL, NULL),
(59, '3305112109770003', 5, '2026-05-24', '< 0,5 Hektar', 'Rp1.000.000 - Rp2.500.000', 'Hortikultura', 'Lahan Tadah Hujan', 'Sudah memiliki alat mesin', 'Diproses', NULL, NULL),
(60, '3305150604700004', 5, '2026-05-24', '1,1 - 2 Hektar', '< Rp1.000.000', 'Palawija', 'Lahan Kritis', 'Hanya memiliki alat manual', 'Diproses', NULL, NULL),
(61, '3305110603910005', 5, '2026-05-24', '0,5 - 1 Hektar', '> Rp4.000.000', 'Padi', 'Lahan Tadah Hujan', 'Memiliki alat semi-mekanis', 'Diproses', NULL, NULL),
(62, '3305162701840006', 5, '2026-05-24', '> 2 Hektar', 'Rp1.000.000 - Rp2.500.000', 'Perkebunan', 'Lahan Irigasi Teknis', 'Tidak memiliki alat sama sekali', 'Diproses', NULL, NULL),
(63, '3305201012720007', 5, '2026-05-24', '1,1 - 2 Hektar', 'Rp2.501.000 - Rp4.000.000', 'Hortikultura', 'Lahan Kering/Gambut', 'Memiliki alat semi-mekanis', 'Diproses', NULL, NULL),
(73, '3305009987651123', 1, '2026-06-06', '2', '4', '2', '1', '3', 'Diproses', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin_programbantuan`
--

CREATE TABLE `admin_programbantuan` (
  `id_program` int(11) NOT NULL,
  `nama_program` varchar(100) NOT NULL,
  `batas_akhir` date NOT NULL,
  `status` enum('Open','Closed') NOT NULL DEFAULT 'Open',
  `gambar` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `c1_luas` decimal(3,2) DEFAULT NULL,
  `c2_penghasilan` decimal(3,2) DEFAULT NULL,
  `c3_komoditas` decimal(3,2) DEFAULT NULL,
  `c4_kondisi` decimal(3,2) DEFAULT NULL,
  `c5_alat` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin_programbantuan`
--

INSERT INTO `admin_programbantuan` (`id_program`, `nama_program`, `batas_akhir`, `status`, `gambar`, `deskripsi`, `c1_luas`, `c2_penghasilan`, `c3_komoditas`, `c4_kondisi`, `c5_alat`) VALUES
(1, 'Pupuk Bersubsidi', '2026-06-30', 'Open', 'pupuk.png', 'Program penyaluran bantuan pupuk resmi dari pemerintah yang dialokasikan khusus bagi kelompok tani guna menekan biaya produksi dan meningkatkan produktivitas serta kualitas hasil panen secara berkelanjutan.', 0.25, 0.20, 0.30, 0.15, 0.10),
(2, 'Benih Unggul', '2026-07-15', 'Open', 'benih.png', 'Bantuan penyaluran benih bersertifikat dengan potensi hasil tinggi untuk membantu petani mendapatkan varietas tanaman yang tahan hama, adaptif terhadap cuaca, serta mampu mengoptimalkan hasil produksi.', 0.20, 0.20, 0.35, 0.15, 0.10),
(3, 'Alsintan (Alat & Mesin Pertanian)', '2026-05-31', 'Closed', 'traktor.png', 'Fasilitasi bantuan modernisasi alat dan mesin pertanian (seperti traktor, cultivator, atau mesin panen) guna mempercepat proses pengolahan lahan, efisiensi waktu kerja, serta meminimalisir rugi-panen (loss).', 0.15, 0.25, 0.15, 0.15, 0.30),
(4, 'Pestisida Nabati', '2026-06-20', 'Open', 'pestisida.png', 'Pemberian bantuan paket stimulan pestisida alami yang ramah lingkungan untuk mendukung sistem perlindungan tanaman terpadu, menjaga ekosistem lahan, serta mengendalikan serangan organisme pengganggu tumbuhan (OPT).', 0.15, 0.20, 0.20, 0.30, 0.15),
(5, 'Irigasi / Pompa Air', '2026-05-15', 'Open', 'pompa.png', 'Program bantuan sarana prasarana pengairan dan mesin pompa air untuk mengantisipasi musim kemarau, menjaga ketersediaan pasokan air, serta mengoptimalkan indeks pertanaman di lahan tadah hujan atau rawan kekeringan.', 0.15, 0.20, 0.15, 0.35, 0.15);

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin_skenariobantuan`
--

CREATE TABLE `admin_skenariobantuan` (
  `id_skenario` bigint(20) UNSIGNED NOT NULL,
  `nama_bantuan` varchar(100) NOT NULL,
  `c1_luas_lahan_bobot` int(11) NOT NULL CHECK (`c1_luas_lahan_bobot` between 1 and 5),
  `c2_penghasilan_bobot` int(11) NOT NULL CHECK (`c2_penghasilan_bobot` between 1 and 5),
  `c3_komoditas_bobot` int(11) NOT NULL CHECK (`c3_komoditas_bobot` between 1 and 5),
  `c4_kondisi_lahan_bobot` int(11) NOT NULL CHECK (`c4_kondisi_lahan_bobot` between 1 and 5),
  `c5_kepemilikan_alat_bobot` int(11) NOT NULL CHECK (`c5_kepemilikan_alat_bobot` between 1 and 5),
  `c1_tipe` varchar(10) NOT NULL CHECK (`c1_tipe` in ('benefit','cost')),
  `c2_tipe` varchar(10) NOT NULL CHECK (`c2_tipe` in ('benefit','cost')),
  `c3_tipe` varchar(10) NOT NULL CHECK (`c3_tipe` in ('benefit','cost')),
  `c4_tipe` varchar(10) NOT NULL CHECK (`c4_tipe` in ('benefit','cost')),
  `c5_tipe` varchar(10) NOT NULL CHECK (`c5_tipe` in ('benefit','cost'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin_skenariobantuan`
--

INSERT INTO `admin_skenariobantuan` (`id_skenario`, `nama_bantuan`, `c1_luas_lahan_bobot`, `c2_penghasilan_bobot`, `c3_komoditas_bobot`, `c4_kondisi_lahan_bobot`, `c5_kepemilikan_alat_bobot`, `c1_tipe`, `c2_tipe`, `c3_tipe`, `c4_tipe`, `c5_tipe`) VALUES
(1, 'Bantuan Alat Pertanian / Mesin (Alsintan)', 4, 3, 2, 3, 5, 'cost', 'cost', 'benefit', 'benefit', 'cost'),
(2, 'Bantuan Sarana Irigasi / Pompa Air / Sumur Bor', 3, 4, 2, 5, 3, 'cost', 'cost', 'benefit', 'benefit', 'cost'),
(3, 'Bantuan Subsidi Benih / Pupuk / Pestisida (Sektor Produksi)', 3, 5, 4, 3, 2, 'cost', 'cost', 'benefit', 'benefit', 'cost');

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin_users`
--

CREATE TABLE `admin_users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Surveyor','Petani') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin_users`
--

INSERT INTO `admin_users` (`id_user`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'admin123', 'Admin', '2026-05-24 12:02:54');

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_petani`
--

CREATE TABLE `data_petani` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(255) DEFAULT NULL,
  `nik` varchar(16) NOT NULL,
  `komoditas` varchar(50) NOT NULL,
  `alamat` text NOT NULL,
  `tempat_lahir` varchar(50) NOT NULL,
  `tanggal_lahir` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `data_petani`
--

INSERT INTO `data_petani` (`id`, `nama_lengkap`, `nik`, `komoditas`, `alamat`, `tempat_lahir`, `tanggal_lahir`) VALUES
(1, 'Hilda Sava Alzena', '3305110101800001', 'Padi', 'Desa Kutosari, RT 02/RW 03, Kec. Kebumen, Kebumen', 'Kebumen', '1999-01-01'),
(2, 'Fahrzatul', '3305121503850002', 'Palawija', 'Desa Muktisari, RT 01/RW 04, Kec. Kebumen, Kebumen', 'Kebumen', '1999-03-15'),
(3, 'Sri Wahyuningsih', '3305112109770003', 'Hortikultura', 'Desa Panjer, RT 03/RW 01, Kec. Kebumen, Kebumen', 'Kebumen', '1999-09-21'),
(4, 'Syahriza', '3305150604700004', 'Palawija', 'Desa Pejagoan, RT 02/RW 02, Kec. Pejagoan, Kebumen', 'Kebumen', '1999-04-06'),
(5, 'Alvira Libra Ramdhani', '3305110603910005', 'Padi', 'Desa Kawedusan, RT 04/RW 02, Kec. Kebumen, Kebumen', 'Kebumen', '1999-03-06'),
(6, 'Farhan Zhaldi', '3305162701840006', 'Perkebunan', 'Desa Karanganyar, RT 01/RW 01, Kec. Karanganyar, Kebumen', 'Kebumen', '1999-01-27'),
(7, 'Talita', '3305201012720007', 'Hortikultura', 'Desa Gombong, RT 05/RW 03, Kec. Gombong, Kebumen', 'Kebumen', '1999-12-10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria_bantuan`
--

CREATE TABLE `kriteria_bantuan` (
  `id_pengajuan` int(11) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `luas_lahan` varchar(50) DEFAULT NULL,
  `penghasilan` varchar(50) DEFAULT NULL,
  `komoditas` varchar(50) DEFAULT NULL,
  `kondisi_lahan` varchar(50) DEFAULT NULL,
  `kepemilikan_alat` varchar(50) DEFAULT NULL,
  `tanggal_input` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_petani`
--

CREATE TABLE `pengajuan_petani` (
  `id_pengajuan` bigint(20) UNSIGNED NOT NULL,
  `id_skenario` int(11) DEFAULT NULL,
  `nama_petani` varchar(100) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `tanggal_pengajuan` date DEFAULT curdate(),
  `c1_skor` int(11) NOT NULL,
  `c2_skor` int(11) NOT NULL,
  `c3_skor` int(11) NOT NULL,
  `c4_skor` int(11) NOT NULL,
  `c5_skor` int(11) NOT NULL,
  `nilai_vektor_s` decimal(10,4) DEFAULT 0.0000,
  `nilai_vektor_v` decimal(10,4) DEFAULT 0.0000,
  `ranking` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `petani`
--

CREATE TABLE `petani` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `komoditas` varchar(50) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `no_telepon` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `petani`
--

INSERT INTO `petani` (`id`, `nama_lengkap`, `nik`, `komoditas`, `alamat`, `tempat_lahir`, `tanggal_lahir`, `no_telepon`, `password`) VALUES
(1, 'Hilda Sava Alzena', '3305110101800001', 'Padi', 'Desa Kutosari, RT 02/RW 03, Kec. Kebumen, Kebumen', 'Kebumen', '1999-01-01', '081234567891', '1112223'),
(2, 'Fahrzatul', '3305121503850002', 'Palawija', 'Desa Muktisari, RT 01/RW 04, Kec. Kebumen, Kebumen', 'Kebumen', '1999-03-15', '081234567892', '2223334'),
(3, 'Sri Wahyuningsih', '3305112109770003', 'Hortikultura', 'Desa Panjer, RT 03/RW 01, Kec. Kebumen, Kebumen', 'Kebumen', '1999-09-21', '081234567893', '3334445'),
(4, 'Syahriza', '3305150604700004', 'Palawija', 'Desa Pejagoan, RT 02/RW 02, Kec. Pejagoan, Kebumen', 'Kebumen', '1999-04-06', '081234567894', '4445556'),
(5, 'Alvira Libra Ramdhani', '3305110603910005', 'Padi', 'Desa Kawedusan, RT 04/RW 02, Kec. Kebumen, Kebumen', 'Kebumen', '1999-03-06', '081234567895', '5556667'),
(6, 'Farhan Zhaldi', '3305162701840006', 'Perkebunan', 'Desa Karanganyar, RT 01/RW 01, Kec. Karanganyar, Kebumen', 'Kebumen', '1999-01-27', '081234567896', '6667778'),
(7, 'Talita', '3305201012720007', 'Hortikultura', 'Desa Gombong, RT 05/RW 03, Kec. Gombong, Kebumen', 'Kebumen', '1999-12-10', '081234567897', '7778889');

-- --------------------------------------------------------

--
-- Struktur dari tabel `petani_daftar`
--

CREATE TABLE `petani_daftar` (
  `NIK` varchar(20) NOT NULL,
  `Nama` varchar(100) NOT NULL,
  `No Telepon` varchar(15) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `surveyor`
--

CREATE TABLE `surveyor` (
  `id_surveyor` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `surveyor`
--

INSERT INTO `surveyor` (`id_surveyor`, `username`, `nama_lengkap`, `email`, `no_hp`, `password`, `created_at`) VALUES
(2, 'budi_surveyor', 'Budi Santoso', 'budi@surveyor.com', '081234567890', 'surveyor123', '2026-05-31 13:20:58');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin_datapetani`
--
ALTER TABLE `admin_datapetani`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nik` (`nik`);

--
-- Indeks untuk tabel `admin_hasilwp`
--
ALTER TABLE `admin_hasilwp`
  ADD PRIMARY KEY (`id_hasil`);

--
-- Indeks untuk tabel `admin_notifikasi`
--
ALTER TABLE `admin_notifikasi`
  ADD PRIMARY KEY (`id_notif`);

--
-- Indeks untuk tabel `admin_pengajuanbantuan`
--
ALTER TABLE `admin_pengajuanbantuan`
  ADD PRIMARY KEY (`id_pengajuan`),
  ADD KEY `nik` (`nik`),
  ADD KEY `id_program` (`id_program`);

--
-- Indeks untuk tabel `admin_programbantuan`
--
ALTER TABLE `admin_programbantuan`
  ADD PRIMARY KEY (`id_program`);

--
-- Indeks untuk tabel `admin_skenariobantuan`
--
ALTER TABLE `admin_skenariobantuan`
  ADD PRIMARY KEY (`id_skenario`);

--
-- Indeks untuk tabel `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `data_petani`
--
ALTER TABLE `data_petani`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nik` (`nik`);

--
-- Indeks untuk tabel `kriteria_bantuan`
--
ALTER TABLE `kriteria_bantuan`
  ADD PRIMARY KEY (`id_pengajuan`);

--
-- Indeks untuk tabel `pengajuan_petani`
--
ALTER TABLE `pengajuan_petani`
  ADD PRIMARY KEY (`id_pengajuan`);

--
-- Indeks untuk tabel `petani`
--
ALTER TABLE `petani`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nik` (`nik`);

--
-- Indeks untuk tabel `petani_daftar`
--
ALTER TABLE `petani_daftar`
  ADD PRIMARY KEY (`NIK`);

--
-- Indeks untuk tabel `surveyor`
--
ALTER TABLE `surveyor`
  ADD PRIMARY KEY (`id_surveyor`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin_datapetani`
--
ALTER TABLE `admin_datapetani`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `admin_hasilwp`
--
ALTER TABLE `admin_hasilwp`
  MODIFY `id_hasil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=960;

--
-- AUTO_INCREMENT untuk tabel `admin_notifikasi`
--
ALTER TABLE `admin_notifikasi`
  MODIFY `id_notif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `admin_pengajuanbantuan`
--
ALTER TABLE `admin_pengajuanbantuan`
  MODIFY `id_pengajuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT untuk tabel `admin_programbantuan`
--
ALTER TABLE `admin_programbantuan`
  MODIFY `id_program` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `admin_skenariobantuan`
--
ALTER TABLE `admin_skenariobantuan`
  MODIFY `id_skenario` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `data_petani`
--
ALTER TABLE `data_petani`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `kriteria_bantuan`
--
ALTER TABLE `kriteria_bantuan`
  MODIFY `id_pengajuan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pengajuan_petani`
--
ALTER TABLE `pengajuan_petani`
  MODIFY `id_pengajuan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `petani`
--
ALTER TABLE `petani`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `surveyor`
--
ALTER TABLE `surveyor`
  MODIFY `id_surveyor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `admin_pengajuanbantuan`
--
ALTER TABLE `admin_pengajuanbantuan`
  ADD CONSTRAINT `admin_pengajuanbantuan_ibfk_2` FOREIGN KEY (`id_program`) REFERENCES `admin_programbantuan` (`id_program`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
