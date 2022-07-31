-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 31, 2022 at 01:46 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ags_ai`
--

-- --------------------------------------------------------

--
-- Table structure for table `access`
--

CREATE TABLE `access` (
  `id` varchar(36) NOT NULL,
  `parent_id` varchar(36) DEFAULT NULL,
  `bagian_id` varchar(36) DEFAULT NULL,
  `level` tinyint(3) UNSIGNED NOT NULL,
  `url` varchar(200) NOT NULL DEFAULT '#',
  `icon` varchar(150) NOT NULL DEFAULT 'fas fa-question-circle',
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `access`
--

INSERT INTO `access` (`id`, `parent_id`, `bagian_id`, `level`, `url`, `icon`, `name`) VALUES
('131899f9a9204e0baa1b23cd2eedff6a', NULL, 'f7e795b9ece54c6d82b0ed19f025a65e', 1, 'manajemen-web/users', 'fas fa-users-cog', 'Users'),
('30d0f70435904ad5b4e7cbfeb98fc021', NULL, '063203a5c5124b399ab76f8a03b93c0d', 1, 'penerbitan/naskah', 'fas fa-file-alt', 'Naskah'),
('4e1627c1489844f985cbe2c485b2e162', NULL, 'f7e795b9ece54c6d82b0ed19f025a65e', 1, 'manajemen-web/struktur-ao', 'fas fa-project-diagram', 'Struktur Organisasi'),
('583a723cf036449d80d3742dcf695e38', NULL, '063203a5c5124b399ab76f8a03b93c0d', 1, 'penerbitan/naskah/timeline', 'fas fa-question-circle', 'Timeline'),
('70410774a1e0433bb213a9625aceb0bb', NULL, '8a3ca046fb54492a86aaead53f36bec7', 1, 'produksi/order-cetak', 'fas fa-print', 'Order Cetak'),
('fb6c8f0dcc9e43199642f08a0fe1fd56', NULL, '063203a5c5124b399ab76f8a03b93c0d', 1, 'penerbitan/penulis', 'fas fa-pen', 'Penulis');

-- --------------------------------------------------------

--
-- Table structure for table `access_bagian`
--

CREATE TABLE `access_bagian` (
  `id` varchar(36) NOT NULL,
  `name` varchar(150) NOT NULL,
  `order_ab` tinyint(4) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `access_bagian`
--

INSERT INTO `access_bagian` (`id`, `name`, `order_ab`) VALUES
('04431b2b0e864cd4af41c87256cb92ef', 'Dashboard', 1),
('063203a5c5124b399ab76f8a03b93c0d', 'Penerbitan', 2),
('8a3ca046fb54492a86aaead53f36bec7', 'Produksi', 3),
('f7e795b9ece54c6d82b0ed19f025a65e', 'Manajemen Web', 4);

-- --------------------------------------------------------

--
-- Table structure for table `cabang`
--

CREATE TABLE `cabang` (
  `id` varchar(36) NOT NULL,
  `kode` varchar(5) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `telp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cabang`
--

INSERT INTO `cabang` (`id`, `kode`, `nama`, `telp`, `alamat`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
('ada2962f70ce45fd8b930f1babafeba8', '0000', 'Head Office', '0274123456', 'JL Beo ,Mrican Caturtunggal Depok Sleman', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-03-31 08:32:58', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `divisi`
--

CREATE TABLE `divisi` (
  `id` varchar(36) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `divisi`
--

INSERT INTO `divisi` (`id`, `nama`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
('646a4663aea14eb9915b718cbcc5e33b', 'Direksi', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-03-31 08:33:54', NULL, NULL),
('821ac200b1de45fdad7d533ce0190492', 'Produksi', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-03-31 08:33:36', NULL, NULL),
('d1946a0d285944488032d2dcd1a7882b', 'Operasional', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-03-31 08:33:48', NULL, NULL),
('df719b3e9de442b3ba21b1b414887ec7', 'Marketing', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-03-31 08:33:18', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--

CREATE TABLE `jabatan` (
  `id` varchar(36) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jabatan`
--

INSERT INTO `jabatan` (`id`, `nama`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
('02c4dfedf83a43cd89ba0a83de8445ed', 'Staff', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-03-31 08:34:02', NULL, NULL),
('5cacae63f0f94a91931ba4779879eab1', 'Asisten Manager', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-03-31 08:34:30', NULL, NULL),
('765e0ac17c8e4cabae01582b06da410e', 'Manager', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-03-31 08:34:11', NULL, NULL),
('a39f467d051f49db9508778f643fdd96', 'Direktur', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-03-31 08:34:45', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mm_select`
--

CREATE TABLE `mm_select` (
  `id` varchar(36) DEFAULT NULL,
  `keyword` varchar(50) NOT NULL,
  `options` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mm_select`
--

INSERT INTO `mm_select` (`id`, `keyword`, `options`) VALUES
(NULL, 'imprint', 'Andi'),
(NULL, 'imprint', 'Andi Pro Literasi'),
(NULL, 'imprint', 'Cahaya Harapan'),
(NULL, 'imprint', 'G-Media'),
(NULL, 'imprint', 'Garam Media'),
(NULL, 'imprint', 'Lautan Pustaka'),
(NULL, 'imprint', 'Lily Publisher'),
(NULL, 'imprint', 'MOU Lembaga'),
(NULL, 'imprint', 'Mou Perorangan'),
(NULL, 'imprint', 'NAIN'),
(NULL, 'imprint', 'Nigtoon Cookery'),
(NULL, 'imprint', 'NyoNyo'),
(NULL, 'imprint', 'PBMR Andi'),
(NULL, 'imprint', 'Pustaka Referensi'),
(NULL, 'imprint', 'Rainbow'),
(NULL, 'imprint', 'Rapha'),
(NULL, 'imprint', 'Rumah Baca'),
(NULL, 'imprint', 'Sheila'),
(NULL, 'imprint', 'Sigma'),
(NULL, 'imprint', 'YesCom');

-- --------------------------------------------------------

--
-- Table structure for table `notif`
--

CREATE TABLE `notif` (
  `id` varchar(36) NOT NULL,
  `section` enum('Penerbitan') NOT NULL,
  `type` enum('Penilaian Naskah','Timeline Naskah') NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `raw_data` text DEFAULT NULL,
  `permission_id` varchar(36) NOT NULL,
  `form_id` varchar(36) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expired` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notif`
--

INSERT INTO `notif` (`id`, `section`, `type`, `url`, `raw_data`, `permission_id`, `form_id`, `created_at`, `expired`) VALUES
('13dd1db47c4e40e7b23a9b1f89647404', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '12b852d92d284ab5a654c26e8856fffd', 'b9614d8eb16a40cb871a589f23507e19', '2022-07-27 08:23:22', NULL),
('1d86aaabc4254854b6a4accac129ae31', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'ebca07da8aad42c4aee304e3a6b81001', '1a71b26e23094c39a62f6c8d62ec6665', '2022-07-22 02:32:45', '2022-07-22 03:13:39'),
('25dd5547f92a4f51b1b7d664af480c3f', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '9beba245308543ce821efe8a3ba965e3', 'b9614d8eb16a40cb871a589f23507e19', '2022-07-27 08:23:22', NULL),
('4cfef58eb8a34b6cb64e072f9aeb0435', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'a213b689b8274f4dbe19b3fb24d66840', 'b9614d8eb16a40cb871a589f23507e19', '2022-07-27 08:23:22', NULL),
('9437081c0e324c1799b5b667acae8409', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '8791f143a90e42e2a4d1d0d6b1254bad', '1a71b26e23094c39a62f6c8d62ec6665', '2022-07-22 03:15:14', '2022-07-22 03:15:41'),
('96b5aadca95841c09335bbd6039354c7', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'ebca07da8aad42c4aee304e3a6b81001', 'b9614d8eb16a40cb871a589f23507e19', '2022-07-27 08:23:22', NULL),
('a12692b8f0f44c1490c848f84a43d6f3', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '12b852d92d284ab5a654c26e8856fffd', '1a71b26e23094c39a62f6c8d62ec6665', '2022-07-22 02:32:45', '2022-07-22 03:15:14'),
('b0c753d37e9e4e1e86a41f22c0129c96', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '9beba245308543ce821efe8a3ba965e3', '1a71b26e23094c39a62f6c8d62ec6665', '2022-07-22 02:32:45', '2022-07-22 03:11:56'),
('f5da33c2f628438abed5c1cf8045b86a', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'a213b689b8274f4dbe19b3fb24d66840', '1a71b26e23094c39a62f6c8d62ec6665', '2022-07-22 02:32:45', '2022-07-22 02:46:53');

-- --------------------------------------------------------

--
-- Table structure for table `notif_detail`
--

CREATE TABLE `notif_detail` (
  `notif_id` varchar(36) NOT NULL,
  `user_id` varchar(36) NOT NULL,
  `seen` enum('1','0') NOT NULL DEFAULT '0' COMMENT 'if seen(1) update(null)::: updated by naskah',
  `raw_data` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notif_detail`
--

INSERT INTO `notif_detail` (`notif_id`, `user_id`, `seen`, `raw_data`, `created_at`, `updated_at`) VALUES
('1d86aaabc4254854b6a4accac129ae31', 'e4ddf4d7c2b84cb69647f4dd63f9dbc2', '1', NULL, '2022-07-22 02:32:45', '2022-07-22 03:13:39'),
('96b5aadca95841c09335bbd6039354c7', '4fc80f443bfb4969b9a0272d9be08ef2', '0', NULL, '2022-07-27 08:23:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `penerbitan_m_kelompok_buku`
--

CREATE TABLE `penerbitan_m_kelompok_buku` (
  `id` varchar(36) NOT NULL,
  `kode` varchar(6) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penerbitan_m_kelompok_buku`
--

INSERT INTO `penerbitan_m_kelompok_buku` (`id`, `kode`, `nama`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
('01a5d4cbed244510bbac0c2b32ae872c', 'KB053', 'ArchitectPhotop', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('0242257c619e4f0f85f0a2d872359e95', 'KB086', 'Aplikasi Game', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('0d6b22630e41467a85f2764630b81033', 'KB089', 'Computing & Internet', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('0e3c62efe1c34fc595505860ddee1376', 'KB060', 'Home & Garden', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('119337b2f6db478faaced3693ba35e6b', 'KB087', 'Desain Produk', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('1598193526f34779babe8c6746fc73d9', 'KB072', 'Teks PERTI', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('19232a62008d40e89b45572ab768634b', 'KB115', 'Ensiklopedi', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('1bbf2aba2cb042da81bcca7682ab8a57', 'KB064', 'Teknik Arsitektur', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('1e3b3d45bb924b049e4d51e33e5df672', 'KB109', 'Studi Alkitab', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('1ec8fca6167844c8a1e2ebd2b94cc600', 'KB013', 'Hardware', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('218ed81833ca457994c87dd816e694cd', 'KB035', 'Agriculture', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('242ed37f94774090904b92718bd89eaf', 'KB046', 'Robotika & Embedded System', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('24b12e3f73f84975b33ed2f8202b40ca', 'KB032', 'Teknik', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('24ccf75fb7e8404893187b87ab23072e', 'KB066', 'Komp. Manajemen', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('271c528cdcce4842b1de3e8ea667b10a', 'KB111', 'Grja & Pelyn / Musik', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('273f86affab443769511cab17bebb7b1', 'KB012', 'Pemrograman Web', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('296eddd51adf4cbdbef16e4bbf4d2722', 'KB048', 'Komputer Akuntansi', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('2d370bf6aa894cb2a0d1e657d98bf152', 'KB031', 'Parenting & Family', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('2d9c3609ff634912ba565dba80917ff1', 'KB082', 'Magazines Tabloid & Journal', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('2dd28ad263b34d739c323a76a2e59e64', 'KB062', 'Enginering', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('2e79c672f8b949108166fcd006111442', 'KB011', 'Pemrograman', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('338668ba6f424786881b6e3db917be71', 'KB084', 'Pelajaran', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('34afadfae0d640b385d2e776a0415e51', 'KB025', 'Pariwt & Perhoteln', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('393188a935884e0dae6ee3e6335c8707', 'KB017', 'Kamus Komputer', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('3a3fe9be049c462487f50fc36099c946', 'KB040', 'Kamus Bahasa', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('3cf11d016ebe4323bcd2b22ad233c7d3', 'KB118', 'Rohani Kristen', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('3df79091d3814110917e84fb7df08227', 'KB085', 'Manajemen', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('4469f465d7cf474492c3ba285f9bd94c', 'KB061', 'Humor', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('47ac055ec3ee4c14829f31781db0d886', 'KB028', 'Schoolbooks Ind. Curr.', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('488697c7556a413eb433806b35bb635f', 'KB117', 'Aktivitas Anak', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('4a65f5011e744986baf2f90fb5842589', 'KB108', 'Alkitab', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('512bf0bb27214c529194eab4c2c6c9c1', 'KB019', 'Utillities', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('54d6166e1e954941aa0d9be3c01ff1cf', 'KB075', 'Teknik Mesin', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('5614b0b8061b4d1c87ebe91e4f70c35b', 'KB081', 'Pendidikan', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('56bf568647184c13b427bf0486cb1c01', 'KB105', 'Khpn Kristen/Pernikahan', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('573cdb0a99bb4b249aa90d7e21c12453', 'KB100', 'Grja & Pelyn / Injil', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('578f6c3e5f6049b199e0ce7dee2b088f', 'KB044', 'Budaya', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('5a32e36ffe714dcd98ea02dde7315706', 'KB068', 'IT for Kids', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('5fe4534943404b95aa66196a5bdcf997', 'KB037', 'Perpajakan', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('61de98a888f949138397db5fe09798c4', 'KB029', 'Self-Improvement', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('6321494f510d404d8e796ed2b9d7fe5a', 'KB049', 'Politik', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('638138f7f95a436db582e3a3c22398ce', 'KB102', 'Khdp Krist/Kshatn', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('6ab461e5927d41c681c3582b1d7f8603', 'KB052', 'Fiction Lit', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('6b85953dfc8646e48ec3a4e5f741eb04', 'KB091', 'Akuntansi', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('73b13cc8dc5345c88df9805a58c167cc', 'KB059', 'Religion', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('73f4ca6b206f47b9b0e80fd0335856c3', 'KB078', 'Buku Anak Rohani', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('76fd08f5630841798af53fa7d96a6b3b', 'KB002', 'Database', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('785efc5240ee4fc1af42c326e37e3c4d', 'KB077', 'Teknik Elektro', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('793cd76dac1942ea94642dfc2b9bdc78', 'KB093', 'Khpn Kristen/Wanita', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('7a1f09f191224cb3afc5fa3f9358a131', 'KB112', 'Khdpan Kristen-Parenting', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('7b86d8a512a8447492471c7a797fab98', 'KB106', 'Khdp Krist/PepRHN', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('7bdbd41b0279404694e3fcb6be3e04e7', 'KB006', 'Internet', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('7d75075342c24b838783541d176c46f7', 'KB103', 'Khdp Krist/Doa', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('7fd1b27c3dfc4de58387a39264b80574', 'KB005', 'Desain Teknik', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('81dcf6f0ceb4431992a6ffc98227c2d9', 'KB080', 'SDM', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('845b13d0f7a44246967cdf1e7a218087', 'KB067', 'Keamanan Komp.', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('87cf57e453044cb890784aacc59461f6', 'KB001', 'Aplikasi Office', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('89089725f8f848d7a440fd5f13e55736', 'KB047', 'Teknologi Pertanian', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('8adbefa7643b4f09826f470e55d633fc', 'KB023', 'Entertainment', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('8cb77e520cb94fe5834cb6a9eec73ade', 'KB014', 'Sistem Operasi', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('8ef157c329be4e56829d266634da46ec', 'KB110', 'Pust Muda', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('8f57c52805274840a486b380a0a9eeed', 'KB083', 'Hobi & Ketrampilan', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('901d1bc4ead949d19cb9b00f3143b020', 'KB054', 'Perikanan', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('9047cec642fd41c58aef22687a2d4d14', 'KB098', 'Khdp Krist/Iman', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('90b14e54faa64ec780e09e5c2efb90e0', 'KB003', 'Desain Grafis', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('94608407fc23464bb7251b8620425e98', 'KB018', 'Komputer Teks', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('95ffe625553546faa1d448013b8cc994', 'KB004', 'Desain Web', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('9abcda99a1f04b6c863c820c45839f91', 'KB095', 'Khdp Krist/Pert Rohani', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('9af3fa2505dc4ea7890f1bc5380dd358', 'KB007', 'Jaringan Komp', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('9bae79dfdc1a4399b34d41c3d1c31f86', 'KB016', 'Aplikasi Keuangan', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('9d6d47380dcd485ca8f44d46d2bf85bc', 'KB042', 'Cooking', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('a1a7800865bc4aeba22899dbff76a492', 'KB030', 'Sports & Adventure', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('a31ebb9d174a4023bcbc4fe8dc0fdaa9', 'KB104', 'Grja&Pelyn/Kepemimp', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('a5eb8603649047aca7bfd1098934de8f', 'KB021', 'Psychology', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('a70bbc337f9d42d1966746a8da084e12', 'KB039', 'Broadcasting', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('a77b77ac2c2b4ded99f8289ee256ed2f', 'KB015', 'Statistik', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('aabf079d3ff04fc8b0be43fac38d3252', 'KB074', 'Gadget', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('aeec8573e79b4014a873c7578d0b7260', 'KB114', 'Kesehatan', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('b38ae711fe0c46a6a3568adc8424f9f1', 'KB020', 'Non Komputer', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('b3f4f5787c574761b9c392d35b155d18', 'KB069', 'Teks Kedokteran', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('b7b77e55c93241d0aaee2bfcf3b78e50', 'KB056', 'Agrihobi', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('bab4337ddcf2489db1299a05abee254b', 'KB097', 'Studi Alktb/Tafsir PB', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('bccd38a17ea04ad695d7054e18306bdc', 'KB116', 'Lain-lain', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('befb00225f19422aa6fce62aa86eeb3d', 'KB008', 'Komputer Teknik', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('c103c8a7d7c04b59b02c39a99bb12c43', 'KB101', 'Teologi/Antrop', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('c16a55c4b1c54e12b3c79d292a340f84', 'KB055', 'Childrens Books', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('c39c02faa2ee42fe99103e42f1d19914', 'KB041', 'Refer & Dictio', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('c5b321e172e14debb7c71b7b3b719f1c', 'KB088', 'Bahasa', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('c82d2dd242494414bc01566e630fc66c', 'KB050', 'Medical', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('cff3e5b867544b0e8694e53a0981fef7', 'KB026', 'Teknik Sipil', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('d03285dc02204216862295e82afa82de', 'KB033', 'Sistem Informasi', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('d12fdd4026f1443baa17be28c98a659d', 'KB079', 'Komik', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('d19d9b1776bb46af957472388c52034e', 'KB045', 'Others', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('d1f4265cfa2c4566910e91c535e4da55', 'KB009', 'Multimedia', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('d22c65e563e84c9887cf301fecd2da77', 'KB065', 'Philosophy', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('d25238a0b57a40f5885b19f48240186d', 'KB096', 'Studi Alktb/Tafsir PL', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('d96347a9bc704888a2a7a72b3d4b97a6', 'KB010', 'Open Source', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('d9ba00c6ed6442fb8c707112eed95460', 'KB092', 'Bioteknologi', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('dab05d03e64a47c281e6b258e7fbead1', 'KB070', 'Psikologi Populer', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('dcc19113f13d4042bfb14c2f799f2240', 'KB076', 'Sosial Politik', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('e1de8167736c450b9c0563aac9965284', 'KB057', 'Science & Nature', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('e1fc8afbce7a493e97ff8f15d355f7a5', 'KB071', 'Teks Kesehatan', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('e453930caea34a1ca34565ed33dc9cdc', 'KB034', 'Marketing', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('e4cafe85224d4b8a9f822ea2e0cf35de', 'KB051', 'Komputer Populer', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('e53b0efaf68e47db90f66461e823eaaa', 'KB027', 'Ekonomi', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('e576a89f07bd4397b69b1e89e9566c98', 'KB113', 'Architerture', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('e61fd6049d7a4516908b7ec4058d7bed', 'KB043', 'Edu & Teach', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('e6855cc027aa4d1aa57fba6b0f2d1592', 'KB063', 'Biografi', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('f04cb5551c8042b79f2aa111be16973d', 'KB073', 'Public Relations', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('f11c5ef842ae4e68b32a2b04520fef81', 'KB107', 'Teol/Roh Kudus', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('f1b3290bcb15424dbaf3ce1d11bd3106', 'KB022', 'Diet& Health', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('f4f977eb93654039a958e10b29e99bbf', 'KB038', 'Fotografi', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('f9b757202fa444d8b711521412f09139', 'KB036', 'Law', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('fa853236a45c467a9d0c932c18322de3', 'KB094', 'Referensi', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('fbda0b3fa71049f0aebd50101c5a21cc', 'KB058', 'Social Sciences', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('fc8ffd28677d4688bccb687606da3b16', 'KB090', 'Khpn Kristen/Pria', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('fee5ad06baaf469cacbfb51a093ab2b8', 'KB024', 'Business & Econ.', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('ffcca04ba0894989b011c72ecf70591e', 'KB099', 'Khdp Krist / Insp', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `penerbitan_naskah`
--

CREATE TABLE `penerbitan_naskah` (
  `id` varchar(36) NOT NULL,
  `kode` varchar(13) NOT NULL,
  `judul_asli` varchar(255) NOT NULL,
  `tanggal_masuk_naskah` date DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `kelompok_buku_id` varchar(36) DEFAULT NULL,
  `jalur_buku` enum('Reguler','MoU','MoU-Reguler','SMK/NonSMK','Pro Literasi') DEFAULT NULL,
  `tentang_penulis` enum('1','0') NOT NULL DEFAULT '0' COMMENT '1:Ya|0:Tidak',
  `hard_copy` enum('1','0') NOT NULL DEFAULT '0' COMMENT '1:Ya|0:Tidak',
  `soft_copy` enum('1','0') NOT NULL DEFAULT '0' COMMENT '1:Ya|0:Tidak',
  `cdqr_code` enum('1','0') NOT NULL DEFAULT '0' COMMENT '1:Ya|0:Tidak',
  `keterangan` text DEFAULT NULL,
  `pic_prodev` varchar(36) DEFAULT NULL,
  `penilaian_naskah` enum('1','0') DEFAULT NULL,
  `date_pic_prodev` timestamp NULL DEFAULT NULL,
  `penilaian_prodev` enum('1','0') DEFAULT NULL,
  `penilaian_editset` enum('1','0') DEFAULT NULL,
  `penilaian_pemasaran` enum('1','0') DEFAULT NULL,
  `penilaian_penerbitan` enum('1','0') DEFAULT NULL,
  `penilaian_direksi` enum('1','0') DEFAULT NULL,
  `selesai_penilaian` enum('0','1','2') DEFAULT NULL COMMENT 'N:Default | 0:Belum Selesai | 1:Selesai Dinilai | 2:Tidak Dinilai',
  `selesai_penilaian_tgl` datetime DEFAULT NULL,
  `status_penilaian` enum('Reguler','eBook','Reguler-eBook','Revisi Minor','Revisi Mayor','Ditolak') DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penerbitan_naskah`
--

INSERT INTO `penerbitan_naskah` (`id`, `kode`, `judul_asli`, `tanggal_masuk_naskah`, `email`, `kelompok_buku_id`, `jalur_buku`, `tentang_penulis`, `hard_copy`, `soft_copy`, `cdqr_code`, `keterangan`, `pic_prodev`, `penilaian_naskah`, `date_pic_prodev`, `penilaian_prodev`, `penilaian_editset`, `penilaian_pemasaran`, `penilaian_penerbitan`, `penilaian_direksi`, `selesai_penilaian`, `selesai_penilaian_tgl`, `status_penilaian`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
('1a71b26e23094c39a62f6c8d62ec6665', 'NA20220722001', 'Lorem Ipsum Reguler', '2022-07-06', NULL, '0242257c619e4f0f85f0a2d872359e95', 'Reguler', '1', '0', '1', '0', NULL, 'e4ddf4d7c2b84cb69647f4dd63f9dbc2', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Reguler', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-07-22 02:32:45', NULL, NULL),
('b9614d8eb16a40cb871a589f23507e19', 'NA20220727002', 'coba', '2022-07-25', 'fdasdasda@gmail.com', '0242257c619e4f0f85f0a2d872359e95', 'MoU-Reguler', '0', '1', '0', '1', NULL, '4fc80f443bfb4969b9a0272d9be08ef2', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-07-27 08:23:22', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `penerbitan_naskah_files`
--

CREATE TABLE `penerbitan_naskah_files` (
  `id` varchar(36) NOT NULL,
  `naskah_id` varchar(36) NOT NULL,
  `kategori` enum('File Naskah Asli','File Tambahan Naskah','File Tambahan Naskah Prodev') NOT NULL,
  `file` varchar(100) NOT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penerbitan_naskah_files`
--

INSERT INTO `penerbitan_naskah_files` (`id`, `naskah_id`, `kategori`, `file`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
('064d3933431f496f96f94298e53fc14c', '1a71b26e23094c39a62f6c8d62ec6665', 'File Tambahan Naskah', 'g2fL1xUNZVmaZ3xxmMFXHwUrcNb32I6Ipi0Ofu50.rar', NULL, NULL, NULL, '2022-07-22 02:32:45', NULL, NULL),
('5892d56a88f64a528e0843ee47d3f2b7', 'b9614d8eb16a40cb871a589f23507e19', 'File Naskah Asli', 'fdfdYE9LTqAJ8kOlTwhEBPvXvmV12ln6FCZmmcuh.pdf', NULL, NULL, NULL, '2022-07-27 08:23:22', NULL, NULL),
('7276bbb0a725401c8c7725f154d733b8', '1a71b26e23094c39a62f6c8d62ec6665', 'File Naskah Asli', 'FgEkTY5YjgOz4loGbbDfKemiaG9FH29rZUGnpwUj.pdf', NULL, NULL, NULL, '2022-07-22 02:32:45', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `penerbitan_naskah_penulis`
--

CREATE TABLE `penerbitan_naskah_penulis` (
  `penulis_id` varchar(36) NOT NULL,
  `naskah_id` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penerbitan_naskah_penulis`
--

INSERT INTO `penerbitan_naskah_penulis` (`penulis_id`, `naskah_id`) VALUES
('90d2a75954c0442bb9f6b2578e83fc8b', '1a71b26e23094c39a62f6c8d62ec6665'),
('353a88a472d5438dacf5e7fb7d6271e3', 'b9614d8eb16a40cb871a589f23507e19');

-- --------------------------------------------------------

--
-- Table structure for table `penerbitan_penulis`
--

CREATE TABLE `penerbitan_penulis` (
  `id` varchar(36) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `kewarganegaraan` enum('WNI','WNA') DEFAULT NULL,
  `alamat_domisili` text DEFAULT NULL,
  `ponsel_domisili` varchar(20) DEFAULT NULL,
  `telepon_domisili` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nama_kantor` varchar(150) DEFAULT NULL,
  `jabatan_dikantor` varchar(150) DEFAULT NULL,
  `alamat_kantor` text DEFAULT NULL,
  `telepon_kantor` varchar(20) DEFAULT NULL,
  `sosmed_fb` varchar(150) DEFAULT NULL COMMENT 'facebook',
  `sosmed_ig` varchar(150) DEFAULT NULL COMMENT 'instagram',
  `sosmed_tw` varchar(150) DEFAULT NULL COMMENT 'twitter',
  `tentang_penulis` text DEFAULT NULL,
  `file_tentang_penulis` varchar(255) DEFAULT NULL,
  `foto_penulis` varchar(255) DEFAULT NULL,
  `bank` varchar(100) DEFAULT NULL,
  `bank_atasnama` varchar(150) DEFAULT NULL,
  `no_rekening` varchar(30) DEFAULT NULL,
  `npwp` varchar(30) DEFAULT NULL,
  `ktp` varchar(30) DEFAULT NULL,
  `scan_npwp` varchar(100) DEFAULT NULL,
  `scan_ktp` varchar(100) DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penerbitan_penulis`
--

INSERT INTO `penerbitan_penulis` (`id`, `nama`, `tanggal_lahir`, `tempat_lahir`, `kewarganegaraan`, `alamat_domisili`, `ponsel_domisili`, `telepon_domisili`, `email`, `nama_kantor`, `jabatan_dikantor`, `alamat_kantor`, `telepon_kantor`, `sosmed_fb`, `sosmed_ig`, `sosmed_tw`, `tentang_penulis`, `file_tentang_penulis`, `foto_penulis`, `bank`, `bank_atasnama`, `no_rekening`, `npwp`, `ktp`, `scan_npwp`, `scan_ktp`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
('0297e7cb197647a885098e96716bab0d', 'Asus', '2022-04-27', 'Jakarta', 'WNI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '9jJjMRWN8LNES0suQ6dBmBw4YilCEoP9Uv1eBxMG.pdf', 'P6AF18Ogv4xFDg39MdfoSTtSdU3PGw8qEdT2T9vs.png', NULL, NULL, NULL, NULL, NULL, 'oZimqdtfM3zdpXgb13LnyHU4OAEP5qZHIj1ibUnJ.png', 'hfDa3nNmQK7VPkUWIQip2vvp4g0VZlQkcmgSbW1F.png', 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-05-30 04:32:16', '2022-05-30 04:34:17', NULL),
('353a88a472d5438dacf5e7fb7d6271e3', 'Yohanes Hendra', '1994-03-01', 'Yogyakarta', 'WNI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default.jpg', NULL, NULL, NULL, NULL, NULL, 'c1E1DP4PJ9US7FHzrwApeDJIac7EDK5aWWfvh4zN.png', NULL, 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-05-30 03:50:38', '2022-05-30 04:18:29', NULL),
('90d2a75954c0442bb9f6b2578e83fc8b', 'Lorem Ipsum', '1985-03-15', 'Surabaya', 'WNI', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry', '085743172451', '0274551155', 'lorem@gamil.com', 'Andi Offset', 'Staff', 'Jl Beo, Condong catur. Sleman', '0274558787', 'Lorem Ipsum', '@loremipsum', '@loremipsum', '<div style=\"text-align: justify;\">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</div>', 'JAQ4X0wxDEegR06P3c7tJPPWZeB4Wmgs0mGD3Ggu.pdf', 'r6OlOVcNRmaPSGSOtHyEhU3VhVEoanXtxfKWANdJ.jpg', 'CDB', 'Lorem Ipsum', '88123321', '4777123890', '3471031704900005', 'knov8tsqXdnirPjRblMapTU8FQqOtzNao89v5sEa.png', 'ISxxQm249EIWbeOdGBdFLG6KnpyF9GD3t0lA4VL4.png', 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-03-18 06:26:59', '2022-05-30 04:22:48', NULL),
('986a4833c1c2406193299452bf7c3e91', 'Test Image', '2022-06-02', 'Yogyakarta', 'WNI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'p6TErMkr13ZbR3Q0gexBtDmUDg25nZgHpYukXAwJ.jpg', NULL, NULL, NULL, NULL, NULL, 'OepSJwJAbAUCrXbyTShowYtgAEfaLLNXWEs5D0EZ.jpg', 'ntf0eB938LLZvnPnp50wqtuyuLTKxpDSSCxtEtsN.jpg', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-06-03 06:42:21', NULL, NULL),
('d0789ba314354de4926b549233008ef6', 'JK Rowling Door', '1994-03-02', 'Inggris', 'WNA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<p><strong style=\"margin: 0px; padding: 0px; font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">Lorem Ipsum</strong><span style=\"font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span><br></p>', NULL, 'PXzUEK5uHNaUSQxu842S09ZL0Neub4XlLRTuh8v6.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-05-30 02:09:52', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `penerbitan_pn_direksi`
--

CREATE TABLE `penerbitan_pn_direksi` (
  `id` varchar(36) NOT NULL,
  `naskah_id` varchar(36) NOT NULL,
  `judul_final` varchar(255) NOT NULL,
  `sub_judul_final` varchar(255) NOT NULL,
  `keputusan_final` enum('Reguler','eBook','Reguler-eBook','Revisi Minor','Revisi Mayor','Ditolak') DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `updated_by` varchar(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penerbitan_pn_direksi`
--

INSERT INTO `penerbitan_pn_direksi` (`id`, `naskah_id`, `judul_final`, `sub_judul_final`, `keputusan_final`, `catatan`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
('11cf826afbf74a56ba83f07a030643b6', '1a71b26e23094c39a62f6c8d62ec6665', '', '', 'Reguler', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', '37aee684a9e447a6bef36cbf08222d5d', NULL, '2022-07-22 03:15:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `penerbitan_pn_editor_setter`
--

CREATE TABLE `penerbitan_pn_editor_setter` (
  `id` varchar(36) NOT NULL,
  `naskah_id` varchar(36) NOT NULL,
  `penilaian_editor_umum` text DEFAULT NULL,
  `penilaian_bahasa` enum('Baik','Cukup','Kurang') DEFAULT NULL,
  `catatan_bahasa` text DEFAULT NULL,
  `penilaian_sistematika` enum('Baik','Cukup','Kurang') DEFAULT NULL,
  `catatan_sistematika` text DEFAULT NULL,
  `penilaian_konsistensi` enum('Baik','Cukup','Kurang') DEFAULT NULL,
  `catatan_konsistensi` text DEFAULT NULL,
  `perlu_proses_edit` enum('Perlu','Tidak') DEFAULT NULL,
  `proses_editor` enum('Ringan','Sedang','Berat') DEFAULT NULL,
  `penilai_editing` varchar(36) DEFAULT NULL COMMENT 'Users_id',
  `editing_created_at` timestamp NULL DEFAULT NULL,
  `editing_updated_at` timestamp NULL DEFAULT NULL,
  `perlu_proses_setting` enum('Perlu','Tidak') DEFAULT NULL,
  `proses_setting` enum('Ringan','Sedang','Berat') DEFAULT NULL,
  `penilai_setting` varchar(36) DEFAULT NULL COMMENT 'Users_id',
  `setting_created_at` timestamp NULL DEFAULT NULL,
  `setting_updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `penerbitan_pn_mm`
--

CREATE TABLE `penerbitan_pn_mm` (
  `keyword` varchar(255) NOT NULL COMMENT 'lowercase',
  `id` varchar(36) DEFAULT NULL,
  `options` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penerbitan_pn_mm`
--

INSERT INTO `penerbitan_pn_mm` (`keyword`, `id`, `options`) VALUES
('potensi', NULL, 'Trend Global'),
('potensi', NULL, 'Kontroversial'),
('potensi', NULL, 'Menjawab Mimpi Masyarakat'),
('potensi', NULL, 'Menjawab Kebutuhan Masyarakat'),
('potensi', NULL, 'Menjawab Rasa Ingin Tahu'),
('potensi', NULL, 'Terkait Dengan Kondisi Aktual'),
('potensi', NULL, 'Mengandung Ide-ide Baru'),
('potensi', NULL, 'Reputasi Penulis'),
('potensi', NULL, 'Pencerahan dan Inspirasional'),
('potensi', NULL, 'Buku Abadi'),
('potensi', NULL, 'Bersejarah'),
('pilar', NULL, 'TOKO BUKU'),
('pilar', NULL, 'MOU KAMPUS'),
('pilar', NULL, 'MOU PERORANGAN BUKU UMUM'),
('pilar', NULL, 'MOU PERORANGAN BUKU ROHANI'),
('pilar', NULL, 'BUKU TEKS PERTI'),
('pilar', NULL, 'PERPUSTAKAAN'),
('pilar', NULL, 'PROYEK'),
('pilar', NULL, 'LAIN-LAIN'),
('pilar', NULL, 'ONLINE'),
('pilar', NULL, 'PAMERAN'),
('pilar', NULL, 'BUKU SMK'),
('pilar', NULL, 'BUKU PAK'),
('pilar', NULL, 'BUKU HET SD'),
('pilar', NULL, 'BUKU PENDAMPING SMA'),
('pilar', NULL, 'BUKU HET SMP'),
('pilar', NULL, 'BUKU HET SMA'),
('pilar', NULL, 'BUKU PENDAMPING SD'),
('pilar', NULL, 'BUKU PENDAMPING SMP'),
('pilar', NULL, 'PAUD DAN TK'),
('pilar', NULL, 'ALAT PERAGA EDUKASI'),
('pilar', NULL, 'INDOPUSTAKA'),
('pilar', NULL, 'SIS'),
('pilar', NULL, 'LAMPU'),
('pilar', NULL, 'ROHANI'),
('pilar', NULL, 'BUKU PENGGERAK');

-- --------------------------------------------------------

--
-- Table structure for table `penerbitan_pn_pemasaran`
--

CREATE TABLE `penerbitan_pn_pemasaran` (
  `id` varchar(36) NOT NULL,
  `naskah_id` varchar(36) NOT NULL,
  `pic` enum('M','D') NOT NULL COMMENT 'M:Manager|D:Direksi',
  `prospek_pasar` text DEFAULT NULL,
  `potensi_dana` text DEFAULT NULL,
  `ds_tb` text DEFAULT NULL,
  `pilar` text DEFAULT NULL,
  `potensi` text DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `updated_by` varchar(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penerbitan_pn_pemasaran`
--

INSERT INTO `penerbitan_pn_pemasaran` (`id`, `naskah_id`, `pic`, `prospek_pasar`, `potensi_dana`, `ds_tb`, `pilar`, `potensi`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
('1de7a872e6174a4b8a8ab2753fbe36bd', '1a71b26e23094c39a62f6c8d62ec6665', 'M', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '[\"TB\"]', '[\"MOU KAMPUS\",\"BUKU SMK\",\"BUKU PAK\",\"BUKU HET SD\"]', NULL, '5090c6d9e50449449b2edf23db64cdf5', NULL, '2022-07-22 02:33:54', NULL),
('4149aab55ed7466e88feb5645d03b06c', '1a71b26e23094c39a62f6c8d62ec6665', 'M', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '[\"DS\"]', '[\"MOU KAMPUS\",\"MOU PERORANGAN BUKU UMUM\",\"MOU PERORANGAN BUKU ROHANI\"]', NULL, 'ba7f70e69bf74fc29fe3154980f5f53e', NULL, '2022-07-22 02:46:53', NULL),
('a614541de4cf40b78e2d127ec4e06767', '1a71b26e23094c39a62f6c8d62ec6665', 'D', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', NULL, 'ef171a1a7bba4b81abdfe10ef8c6c0f8', NULL, '2022-07-22 03:11:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `penerbitan_pn_penerbitan`
--

CREATE TABLE `penerbitan_pn_penerbitan` (
  `id` varchar(36) NOT NULL,
  `naskah_id` varchar(36) NOT NULL,
  `penilaian_umum` text DEFAULT NULL,
  `saran` enum('Diterima','Ditolak','Revisi','eBook') DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `potensi` text DEFAULT NULL,
  `tanggapan_usulan_judul` text DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `updated_by` varchar(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penerbitan_pn_penerbitan`
--

INSERT INTO `penerbitan_pn_penerbitan` (`id`, `naskah_id`, `penilaian_umum`, `saran`, `catatan`, `potensi`, `tanggapan_usulan_judul`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
('ffaed342512f465c8dc9da01a1bd5da0', '1a71b26e23094c39a62f6c8d62ec6665', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Diterima', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum', NULL, '4fc80f443bfb4969b9a0272d9be08ef2', NULL, '2022-07-22 03:15:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `penerbitan_pn_prodev`
--

CREATE TABLE `penerbitan_pn_prodev` (
  `id` varchar(36) NOT NULL,
  `naskah_id` varchar(36) NOT NULL,
  `sistematika` enum('Baik','Cukup','Kurang') NOT NULL,
  `nilai_keilmuan` enum('Baik','Cukup','Kurang') NOT NULL,
  `kelompok_buku_id` varchar(36) DEFAULT NULL,
  `isi_materi` text DEFAULT NULL,
  `sasaran_keilmuan` text DEFAULT NULL,
  `sasaran_pasar` text DEFAULT NULL,
  `sumber_dana_pasar` text DEFAULT NULL,
  `skala_penilaian` enum('Baik','Cukup','Kurang') NOT NULL,
  `saran` enum('Diterima','Ditolak','Revisi','eBook') NOT NULL,
  `potensi` text DEFAULT NULL,
  `usulan_judul` text DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `updated_by` varchar(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penerbitan_pn_prodev`
--

INSERT INTO `penerbitan_pn_prodev` (`id`, `naskah_id`, `sistematika`, `nilai_keilmuan`, `kelompok_buku_id`, `isi_materi`, `sasaran_keilmuan`, `sasaran_pasar`, `sumber_dana_pasar`, `skala_penilaian`, `saran`, `potensi`, `usulan_judul`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
('28cdc37c9cec4db08204f46bf39ce2b8', '1a71b26e23094c39a62f6c8d62ec6665', 'Baik', 'Cukup', '0d6b22630e41467a85f2764630b81033', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing', 'Baik', 'Diterima', 'enim ipsam voluptatem quia voluptas sit', NULL, 'e4ddf4d7c2b84cb69647f4dd63f9dbc2', NULL, '2022-07-22 03:13:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `penerbitan_pn_stts`
--

CREATE TABLE `penerbitan_pn_stts` (
  `id` varchar(36) NOT NULL,
  `naskah_id` varchar(36) NOT NULL,
  `tgl_input_admin` datetime DEFAULT current_timestamp(),
  `tgl_naskah_masuk` datetime DEFAULT NULL,
  `tgl_pn_prodev` datetime DEFAULT NULL,
  `tgl_pn_editor` datetime DEFAULT NULL,
  `tgl_pn_setter` datetime DEFAULT NULL,
  `tgl_pn_m_pemasaran` datetime DEFAULT NULL,
  `tgl_pn_m_penerbitan` datetime DEFAULT NULL,
  `tgl_pn_d_pemasaran` datetime DEFAULT NULL,
  `tgl_pn_direksi` datetime DEFAULT NULL,
  `tgl_pn_selesai` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penerbitan_pn_stts`
--

INSERT INTO `penerbitan_pn_stts` (`id`, `naskah_id`, `tgl_input_admin`, `tgl_naskah_masuk`, `tgl_pn_prodev`, `tgl_pn_editor`, `tgl_pn_setter`, `tgl_pn_m_pemasaran`, `tgl_pn_m_penerbitan`, `tgl_pn_d_pemasaran`, `tgl_pn_direksi`, `tgl_pn_selesai`) VALUES
('930b73d33a8445f7b933643ad967aa5f', 'b9614d8eb16a40cb871a589f23507e19', '2022-07-27 15:23:22', '2022-07-25 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('9e81321a28cb4d3b91e9c39541f62547', '1a71b26e23094c39a62f6c8d62ec6665', '2022-07-22 09:32:45', '2022-07-06 00:00:00', '2022-07-22 10:13:39', NULL, NULL, '2022-07-22 09:46:53', '2022-07-22 10:15:14', '2022-07-22 10:11:56', '2022-07-22 10:15:41', '2022-07-22 10:15:41');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` varchar(36) NOT NULL,
  `access_id` varchar(36) NOT NULL,
  `url` varchar(200) NOT NULL,
  `type` enum('Create','Read','Update','Delete','Approval') NOT NULL,
  `raw` varchar(255) DEFAULT NULL COMMENT '!: Hardcode di script',
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `access_id`, `url`, `type`, `raw`, `name`) VALUES
('1098a56970114e18898367d334658b47', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/mengubah-naskah', 'Update', 'ubah-data-naskah', 'Ubah Data Naskah'),
('12b852d92d284ab5a654c26e8856fffd', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/penilaian', 'Update', 'naskah-pn-mpenerbitan', 'Penilaian M.Penerbitan'),
('1b89744217b04f79a8c1d7a967a46912', 'fb6c8f0dcc9e43199642f08a0fe1fd56', 'penerbitan/penulis', 'Read', NULL, 'Lihat Data Penulis'),
('1c1940da68fa4f8ba2325e83c303c47c', '131899f9a9204e0baa1b23cd2eedff6a', 'manajemen-web/user', 'Update', 'ubah-data-user', 'Ubah Data User'),
('1f4e5b3752b8475cb5261940ef62532d', 'fb6c8f0dcc9e43199642f08a0fe1fd56', 'penerbitan/penulis/membuat-penulis', 'Create', 'tambah-data-penulis', 'Buat Data Penulis'),
('33c3711d787d416082c0519356547b0c', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/penilaian', 'Update', 'naskah-pn-setter', 'Penilaian Setter'),
('358a13267bcb4608a14c851c3010f79b', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/membuat-naskah', 'Create', 'tambah-data-naskah', 'Tambah Data Naskah'),
('38645f82ae7c468abad1ab191e7a8ad9', '131899f9a9204e0baa1b23cd2eedff6a', 'manajemen-web/users', 'Read', NULL, 'Lihat Data Users'),
('4bb845580b464d7db3d7c3b3e4fd213b', '4e1627c1489844f985cbe2c485b2e162', 'manajemen-web/struktur-ao', 'Read', NULL, 'Lihat Struktur AO'),
('4d64a842e08344b9aeec88ed9eb2eb72', '70410774a1e0433bb213a9625aceb0bb', '', 'Update', 'update-produksi', 'Mengubah Data Produksi Order Cetak'),
('5d793b19c75046b9a4d75d067e8e33b2', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/penilaian', 'Update', 'naskah-pn-editor', 'Penilaian Editor'),
('6903e82e7e94478f87df3cf80de6b587', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah', 'Read', NULL, 'Lihat Data Naskah'),
('6b95b4e041e04d61a91422fe3d06fd8d', '70410774a1e0433bb213a9625aceb0bb', '', 'Approval', 'menyetujui-order', 'Menyetujui Produksi Order Cetak'),
('8791f143a90e42e2a4d1d0d6b1254bad', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/penilaian', 'Update', 'naskah-pn-direksi', 'Penilaian Direksi'),
('89bc4b0ef1dd4306a3217cbf24551071', 'fb6c8f0dcc9e43199642f08a0fe1fd56', 'penerbitan/penulis/hapus-penulis', 'Delete', 'hapus-data-penulis', 'Hapus Data Penulis'),
('8d9b1da4234f46eb858e1ea490da6348', '583a723cf036449d80d3742dcf695e38', 'penerbitan/naskah/timeline', 'Update', 'timeline-naskah-update-date', 'Ubah Tanggal Timeline Naskah'),
('9beba245308543ce821efe8a3ba965e3', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/penilaian', 'Update', 'naskah-pn-dpemasaran', 'Penilaian D.Pemasaran'),
('a213b689b8274f4dbe19b3fb24d66840', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/penilaian', 'Update', 'naskah-pn-mpemasaran', 'Penilaian M.Pemasaran'),
('a6034d814d7e4671b4cc8a98433f8fb2', '583a723cf036449d80d3742dcf695e38', 'penerbitan/naskah/timeline', 'Create', 'timeline-naskah-add', 'Buat Timeline Naskah'),
('c64802952e504f4ab25a6b1241232f85', '70410774a1e0433bb213a9625aceb0bb', 'produksi/order-cetak', 'Read', NULL, 'Lihat Data Order Cetak'),
('cc93223a47764195ac15aacf266673d9', 'fb6c8f0dcc9e43199642f08a0fe1fd56', 'penerbitan/penulis/mengubah-penulis', 'Update', 'ubah-data-penulis', 'Ubah Data Penulis'),
('e0860766d564483e870b5974a601649c', '70410774a1e0433bb213a9625aceb0bb', '', 'Create', 'tambah-produksi-cetak', 'Membuat Data Order Cetak'),
('ebca07da8aad42c4aee304e3a6b81001', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/penilaian', 'Update', 'naskah-pn-prodev', 'Penilaian Prodev');

-- --------------------------------------------------------

--
-- Table structure for table `produksi_order_cetak`
--

CREATE TABLE `produksi_order_cetak` (
  `id` varchar(36) NOT NULL,
  `kode_order` varchar(8) NOT NULL,
  `tipe_order` enum('1','2','3') NOT NULL COMMENT '1:Umum | 2:Rohani | 3:POD',
  `status_cetak` enum('1','2','3') NOT NULL COMMENT '1:Buku Baru | 2:Cetak Ulang Revisi | 3:Cetak Ulang',
  `pilihan_terbit` enum('1','2','3') DEFAULT NULL COMMENT '1 = Cetak Fisik,\r\n2 = E-Book,\r\n3 = Cetak Fisik & E-Book',
  `urgent` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0:Tidak | 1:Ya',
  `judul_buku` varchar(255) DEFAULT NULL,
  `sub_judul` varchar(255) DEFAULT NULL,
  `penulis` varchar(255) DEFAULT NULL,
  `isbn` varchar(255) DEFAULT NULL,
  `eisbn` varchar(255) DEFAULT NULL,
  `penerbit` varchar(255) DEFAULT NULL,
  `imprint` varchar(255) DEFAULT NULL,
  `platform_digital` text DEFAULT NULL COMMENT 'array',
  `status_buku` enum('1','2') DEFAULT NULL COMMENT '1= Reguler,\r\n2= MOU',
  `kelompok_buku` varchar(255) DEFAULT NULL,
  `edisi_cetakan` varchar(255) DEFAULT NULL,
  `posisi_layout` enum('1','2') DEFAULT NULL COMMENT '1= Potrait (Tegak),\r\n2= Landscape (Tidur)',
  `dami` varchar(2) DEFAULT NULL COMMENT 'Isian tergantung pada posisi_layout',
  `format_buku` varchar(255) DEFAULT NULL COMMENT 'Isian tergantung pada data dami',
  `jumlah_halaman` varchar(255) DEFAULT NULL,
  `kertas_isi` varchar(255) DEFAULT NULL,
  `warna_isi` varchar(255) DEFAULT NULL,
  `kertas_cover` varchar(255) DEFAULT NULL,
  `warna_cover` varchar(255) DEFAULT NULL,
  `efek_cover` varchar(255) DEFAULT NULL,
  `jenis_cover` varchar(255) DEFAULT NULL,
  `jilid` enum('1','2','3','4') DEFAULT NULL COMMENT '1= Bending ,\r\n2= Jahit Kawat ,\r\n3= Jahit Benang,\r\n4= Hardcover',
  `ukuran_jilid_bending` varchar(10) DEFAULT NULL COMMENT 'Terisi jika jilid nya adalah ''Bending'' (lihat kolom jilid)',
  `tahun_terbit` year(4) DEFAULT NULL,
  `buku_jadi` varchar(255) DEFAULT NULL,
  `jumlah_cetak` int(10) DEFAULT NULL,
  `buku_contoh` text DEFAULT NULL,
  `spp` varchar(30) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `perlengkapan` text DEFAULT NULL,
  `tgl_permintaan_jadi` date DEFAULT NULL,
  `status_penyetujuan` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1 = Pending ~~\r\n2 = Disetujui ~~\r\n3 = Ditolak ~~',
  `ket_penolakan` mediumtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `created_by` varchar(36) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `produksi_order_cetak`
--

INSERT INTO `produksi_order_cetak` (`id`, `kode_order`, `tipe_order`, `status_cetak`, `pilihan_terbit`, `urgent`, `judul_buku`, `sub_judul`, `penulis`, `isbn`, `eisbn`, `penerbit`, `imprint`, `platform_digital`, `status_buku`, `kelompok_buku`, `edisi_cetakan`, `posisi_layout`, `dami`, `format_buku`, `jumlah_halaman`, `kertas_isi`, `warna_isi`, `kertas_cover`, `warna_cover`, `efek_cover`, `jenis_cover`, `jilid`, `ukuran_jilid_bending`, `tahun_terbit`, `buku_jadi`, `jumlah_cetak`, `buku_contoh`, `spp`, `keterangan`, `perlengkapan`, `tgl_permintaan_jadi`, `status_penyetujuan`, `ket_penolakan`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
('0b22a2ba6b4b40ec8ee833fe40cb6468', '22-1000', '1', '1', '3', '1', 'Di Balik Mata Kaca', 'Sebuah Pengalaman', 'Ferdyawan Listanto', '12124sdsadse564444', 'dfsdfsdfs6687687', 'Andi', 'Pustaka Referensi', '[\"Moco\",\"Gramedia\",\"Esentral\",\"Indopustaka\"]', '1', 'Ensiklopedi', 'i/1', '1', '24', '19 x 23 cm', 'vi + 325', 'hvs 70', 'b/w', 'Ivory, 230', 'Full Color', 'UV', 'Biasa', '1', '2,5 cm', 2022, 'Wrapping', 500, NULL, NULL, NULL, NULL, '2022-08-30', '1', NULL, '2022-07-31 08:19:43', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('9d283eb9130746fb86680409c76bdb53', '22-3000', '2', '1', '1', '1', 'Rohani Dalam Jiwa', 'Termenung', 'Joko Jiwa', '2342323423', '213413fff445rGs', 'Andi', 'NAIN', 'null', '2', 'Studi Alkitab', 'vii/2', '1', '24', '19 x 23 cm', 'viii + 302', 'hvs 70', 'b/w', 'Ivory, 230', 'Full Color', 'UV', 'Biasa', '3', ' cm', 2022, 'wrapping', 350, NULL, NULL, NULL, NULL, '2022-08-31', '1', NULL, '2022-07-31 11:12:47', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `produksi_penyetujuan_order_cetak`
--

CREATE TABLE `produksi_penyetujuan_order_cetak` (
  `id` char(36) NOT NULL,
  `users_id` varchar(36) NOT NULL,
  `produksi_order_cetak_id` varchar(8) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `timeline`
--

CREATE TABLE `timeline` (
  `id` varchar(36) NOT NULL,
  `naskah_id` varchar(36) NOT NULL,
  `tgl_naskah_masuk` datetime NOT NULL,
  `tgl_mulai_penerbitan` datetime NOT NULL,
  `tgl_selesai_penerbitan` datetime DEFAULT NULL,
  `ttl_hari_penerbitan` int(10) UNSIGNED NOT NULL,
  `tgl_mulai_produksi` datetime NOT NULL,
  `tgl_selesai_produksi` datetime DEFAULT NULL,
  `ttl_hari_produksi` int(10) UNSIGNED NOT NULL,
  `tgl_buku_jadi` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `timeline_sub`
--

CREATE TABLE `timeline_sub` (
  `id` varchar(36) NOT NULL,
  `timeline_id` varchar(36) NOT NULL,
  `pic` varchar(36) NOT NULL,
  `proses` varchar(255) NOT NULL,
  `target` int(10) UNSIGNED NOT NULL COMMENT 'Total Hari',
  `tgl_mulai` datetime DEFAULT NULL COMMENT 'Input PIC',
  `tg_selesai` datetime DEFAULT NULL COMMENT 'Input PIC',
  `catatan` text DEFAULT NULL COMMENT 'Input PIC'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(36) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `avatar` varchar(100) NOT NULL DEFAULT 'default.jpg',
  `nama` varchar(100) NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `cabang_id` varchar(36) DEFAULT NULL,
  `divisi_id` varchar(36) DEFAULT NULL,
  `jabatan_id` varchar(36) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1' COMMENT '1:Aktif|2:NonAktif',
  `super_admin` enum('1','0') NOT NULL DEFAULT '0' COMMENT 'Default Admin',
  `created_by` varchar(36) DEFAULT NULL,
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `avatar`, `nama`, `tanggal_lahir`, `tempat_lahir`, `telepon`, `alamat`, `cabang_id`, `divisi_id`, `jabatan_id`, `status`, `super_admin`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
('37aee684a9e447a6bef36cbf08222d5d', 'direksi@gmail.com', '$2y$10$fPZp4mpZP5VufCqUKVUFB.ykQ02vDbzA/fHbymBAE8Emkyhj8DpmK', 'Ii0LnFZo5WfCrLvcEO49sC4h8GLHARCBNWfByYWX.jpg', 'Direksi', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '646a4663aea14eb9915b718cbcc5e33b', 'a39f467d051f49db9508778f643fdd96', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-05-19 01:51:10', NULL),
('3d8ae658b9c049f6b9b7633a6c0ef4f2', 'lorem@gmail.com', '$2y$10$piaLD4atKsjQtc9HRIEd/uVfCzShDpgrF911hY9svit0A9WOcyJJ.', 'default.jpg', 'lorem', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'd1946a0d285944488032d2dcd1a7882b', '02c4dfedf83a43cd89ba0a83de8445ed', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('4c45b120510e43a08961ec4a712c4ccb', 'userdua@gmail.com', '$2y$10$Tj1e8PsLZKBGEFYjdSNuNOlnUWMXViK9gSAYlUtxAaddKLkOOQCEO', 'default.jpg', 'Admin Dua', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'd1946a0d285944488032d2dcd1a7882b', '02c4dfedf83a43cd89ba0a83de8445ed', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-05-19 01:18:17', NULL),
('4fc80f443bfb4969b9a0272d9be08ef2', 'penerbitan@gmail.com', '$2y$10$otYXOmZAOToGukQ.t4na/u/czk5YiiX1S55/yUUUSoC/f30wkg5wi', 'default.jpg', 'Manager Penerbitan', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'd1946a0d285944488032d2dcd1a7882b', '765e0ac17c8e4cabae01582b06da410e', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('5090c6d9e50449449b2edf23db64cdf5', 'mpemasaran@gmail.com', '$2y$10$AYprSWSQaMLIDDE5e/GAoumsZgIp8uvyRxL/aUw10xSXYyNRIEOyW', 'default.jpg', 'Manager Pemasran', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'df719b3e9de442b3ba21b1b414887ec7', '5cacae63f0f94a91931ba4779879eab1', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('5960036aa4fe43c582015d8d812e901c', 'usersatu@gmail.com', '$2y$10$IaVZTOnvB6cj3atB7rDZYOzOPCTSGfJIx8KI0BPReePQEb15YvwO.', 'default.jpg', 'User Satu', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '821ac200b1de45fdad7d533ce0190492', '02c4dfedf83a43cd89ba0a83de8445ed', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('5c6b749331b847e38ee5f8b5b9e2f159', 'm.stock@gmail.com', '$2y$10$2/Ilzsvw8mbST51WjAMHnOeZFql1KW5U2kt1RscGoj/HEvHNMrHby', 'default.jpg', 'Manajer Stok', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'd1946a0d285944488032d2dcd1a7882b', '765e0ac17c8e4cabae01582b06da410e', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('8ce9a0324f524e8fba4f67e052f7c4c4', 'prodevdua@gmail.com', '$2y$10$o0huqa2lV9s8sdp0362JWu8cdCOp8ZQ4It58GLCxfpQPcIoB3pV/K', 'default.jpg', 'Prodev Dua', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '821ac200b1de45fdad7d533ce0190492', '02c4dfedf83a43cd89ba0a83de8445ed', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('909c0ff670d94102a8cc33d8fcae8993', 'editor@gmail.com', '$2y$10$HzjCB.XrexFHRzgj1NK77OpS50Gb9BIsIyzcVMzt1grt/ZMT6JDTW', 'default.jpg', 'Editor', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '821ac200b1de45fdad7d533ce0190492', '02c4dfedf83a43cd89ba0a83de8445ed', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('ba7f70e69bf74fc29fe3154980f5f53e', 'hendra@gmail.com', '$2y$10$ZcWsK4B9Z5XmHzDnsXRCG.zXbP3TnNcV233iObcIQWqO8M2eT8YSW', 'Tn5fjMQOA1BrHKzudsbtj9U9HxC01tvKNTFDCGR8.jpg', 'hendra', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '646a4663aea14eb9915b718cbcc5e33b', 'a39f467d051f49db9508778f643fdd96', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', 'ba7f70e69bf74fc29fe3154980f5f53e', NULL, NULL, '2022-05-19 04:52:09', NULL),
('be8d42fa88a14406ac201974963d9c1b', 'admin@gmail.com', '$2y$10$JfdI4rvmfegdd97t9xvgB.YWs5PQhfN4IlkOzgLoWrDeZaYDvL6Lu', 'default.jpg', 'Super Admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('cf9361d1274c44cbbb42c8d6383d0dad', 'mstock@gmail.com', '$2y$10$kTG9kuumxpdrAkMTG9SS1.bmt0Aflp88S7BqKNv3vmShiyvE.Nbry', 'default.jpg', 'Manajer Stok', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'd1946a0d285944488032d2dcd1a7882b', '765e0ac17c8e4cabae01582b06da410e', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-07-29 06:43:41'),
('e4ddf4d7c2b84cb69647f4dd63f9dbc2', 'prodevsatu@gmail.com', '$2y$10$fz6rEqhT/ZsYfZfpv2As0elZCxq7IUlDTaGtLus3wVdm0fqg/f5Au', 'default.jpg', 'Prodev Satu', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '821ac200b1de45fdad7d533ce0190492', '02c4dfedf83a43cd89ba0a83de8445ed', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('ec563464e9214fcbae2a0cc805ec2577', 'setter@gmail.com', '$2y$10$kPH1u6iWvU/HKYt020x2H.dFGy7x6u5/U6qjcIS6qi6kMfj8UTkau', 'default.jpg', 'Setter', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '821ac200b1de45fdad7d533ce0190492', '02c4dfedf83a43cd89ba0a83de8445ed', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('ef171a1a7bba4b81abdfe10ef8c6c0f8', 'dpemasaran@gmail.com', '$2y$10$qsv/NY6w9Y4w4.JATrFRFOS.UFpPINzoUgHwPf5RZugIy20hXH342', 'default.jpg', 'Direktur Pemasaran', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'df719b3e9de442b3ba21b1b414887ec7', 'a39f467d051f49db9508778f643fdd96', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_permission`
--

CREATE TABLE `user_permission` (
  `user_id` varchar(36) NOT NULL,
  `permission_id` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_permission`
--

INSERT INTO `user_permission` (`user_id`, `permission_id`) VALUES
('8ce9a0324f524e8fba4f67e052f7c4c4', '6903e82e7e94478f87df3cf80de6b587'),
('8ce9a0324f524e8fba4f67e052f7c4c4', 'ebca07da8aad42c4aee304e3a6b81001'),
('5960036aa4fe43c582015d8d812e901c', '5d793b19c75046b9a4d75d067e8e33b2'),
('5960036aa4fe43c582015d8d812e901c', '6903e82e7e94478f87df3cf80de6b587'),
('4fc80f443bfb4969b9a0272d9be08ef2', '12b852d92d284ab5a654c26e8856fffd'),
('4fc80f443bfb4969b9a0272d9be08ef2', '6903e82e7e94478f87df3cf80de6b587'),
('37aee684a9e447a6bef36cbf08222d5d', '6903e82e7e94478f87df3cf80de6b587'),
('37aee684a9e447a6bef36cbf08222d5d', '8791f143a90e42e2a4d1d0d6b1254bad'),
('37aee684a9e447a6bef36cbf08222d5d', '8d9b1da4234f46eb858e1ea490da6348'),
('909c0ff670d94102a8cc33d8fcae8993', '5d793b19c75046b9a4d75d067e8e33b2'),
('909c0ff670d94102a8cc33d8fcae8993', '6903e82e7e94478f87df3cf80de6b587'),
('ec563464e9214fcbae2a0cc805ec2577', '33c3711d787d416082c0519356547b0c'),
('ec563464e9214fcbae2a0cc805ec2577', '6903e82e7e94478f87df3cf80de6b587'),
('5090c6d9e50449449b2edf23db64cdf5', '6903e82e7e94478f87df3cf80de6b587'),
('5090c6d9e50449449b2edf23db64cdf5', 'a213b689b8274f4dbe19b3fb24d66840'),
('5090c6d9e50449449b2edf23db64cdf5', '8d9b1da4234f46eb858e1ea490da6348'),
('e4ddf4d7c2b84cb69647f4dd63f9dbc2', '6903e82e7e94478f87df3cf80de6b587'),
('e4ddf4d7c2b84cb69647f4dd63f9dbc2', 'ebca07da8aad42c4aee304e3a6b81001'),
('e4ddf4d7c2b84cb69647f4dd63f9dbc2', '8d9b1da4234f46eb858e1ea490da6348'),
('e4ddf4d7c2b84cb69647f4dd63f9dbc2', '1b89744217b04f79a8c1d7a967a46912'),
('4c45b120510e43a08961ec4a712c4ccb', '33c3711d787d416082c0519356547b0c'),
('4c45b120510e43a08961ec4a712c4ccb', '6903e82e7e94478f87df3cf80de6b587'),
('4c45b120510e43a08961ec4a712c4ccb', 'a213b689b8274f4dbe19b3fb24d66840'),
('4c45b120510e43a08961ec4a712c4ccb', '38645f82ae7c468abad1ab191e7a8ad9'),
('ef171a1a7bba4b81abdfe10ef8c6c0f8', '6903e82e7e94478f87df3cf80de6b587'),
('ef171a1a7bba4b81abdfe10ef8c6c0f8', '9beba245308543ce821efe8a3ba965e3'),
('ba7f70e69bf74fc29fe3154980f5f53e', '6903e82e7e94478f87df3cf80de6b587'),
('ba7f70e69bf74fc29fe3154980f5f53e', 'a213b689b8274f4dbe19b3fb24d66840'),
('ba7f70e69bf74fc29fe3154980f5f53e', 'ebca07da8aad42c4aee304e3a6b81001'),
('ba7f70e69bf74fc29fe3154980f5f53e', '1b89744217b04f79a8c1d7a967a46912'),
('3d8ae658b9c049f6b9b7633a6c0ef4f2', '6903e82e7e94478f87df3cf80de6b587'),
('3d8ae658b9c049f6b9b7633a6c0ef4f2', 'a213b689b8274f4dbe19b3fb24d66840'),
('be8d42fa88a14406ac201974963d9c1b', '1098a56970114e18898367d334658b47'),
('be8d42fa88a14406ac201974963d9c1b', '358a13267bcb4608a14c851c3010f79b'),
('be8d42fa88a14406ac201974963d9c1b', '6903e82e7e94478f87df3cf80de6b587'),
('be8d42fa88a14406ac201974963d9c1b', '8d9b1da4234f46eb858e1ea490da6348'),
('be8d42fa88a14406ac201974963d9c1b', 'a6034d814d7e4671b4cc8a98433f8fb2'),
('be8d42fa88a14406ac201974963d9c1b', '1b89744217b04f79a8c1d7a967a46912'),
('be8d42fa88a14406ac201974963d9c1b', '1f4e5b3752b8475cb5261940ef62532d'),
('be8d42fa88a14406ac201974963d9c1b', '4d64a842e08344b9aeec88ed9eb2eb72'),
('be8d42fa88a14406ac201974963d9c1b', '6b95b4e041e04d61a91422fe3d06fd8d'),
('be8d42fa88a14406ac201974963d9c1b', 'c64802952e504f4ab25a6b1241232f85'),
('be8d42fa88a14406ac201974963d9c1b', 'e0860766d564483e870b5974a601649c'),
('be8d42fa88a14406ac201974963d9c1b', '1c1940da68fa4f8ba2325e83c303c47c'),
('be8d42fa88a14406ac201974963d9c1b', '38645f82ae7c468abad1ab191e7a8ad9'),
('be8d42fa88a14406ac201974963d9c1b', '4bb845580b464d7db3d7c3b3e4fd213b');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access`
--
ALTER TABLE `access`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `access_bagian`
--
ALTER TABLE `access_bagian`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cabang`
--
ALTER TABLE `cabang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `divisi`
--
ALTER TABLE `divisi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama` (`nama`);

--
-- Indexes for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama` (`nama`);

--
-- Indexes for table `mm_select`
--
ALTER TABLE `mm_select`
  ADD UNIQUE KEY `keyword` (`keyword`,`options`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `notif`
--
ALTER TABLE `notif`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penerbitan_m_kelompok_buku`
--
ALTER TABLE `penerbitan_m_kelompok_buku`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `penerbitan_naskah`
--
ALTER TABLE `penerbitan_naskah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `penerbitan_naskah_files`
--
ALTER TABLE `penerbitan_naskah_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penerbitan_penulis`
--
ALTER TABLE `penerbitan_penulis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ktp` (`ktp`);

--
-- Indexes for table `penerbitan_pn_direksi`
--
ALTER TABLE `penerbitan_pn_direksi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penerbitan_pn_editor_setter`
--
ALTER TABLE `penerbitan_pn_editor_setter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penerbitan_pn_mm`
--
ALTER TABLE `penerbitan_pn_mm`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `penerbitan_pn_pemasaran`
--
ALTER TABLE `penerbitan_pn_pemasaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penerbitan_pn_penerbitan`
--
ALTER TABLE `penerbitan_pn_penerbitan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penerbitan_pn_prodev`
--
ALTER TABLE `penerbitan_pn_prodev`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penerbitan_pn_stts`
--
ALTER TABLE `penerbitan_pn_stts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `raw` (`raw`);

--
-- Indexes for table `produksi_order_cetak`
--
ALTER TABLE `produksi_order_cetak`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_order` (`kode_order`);

--
-- Indexes for table `produksi_penyetujuan_order_cetak`
--
ALTER TABLE `produksi_penyetujuan_order_cetak`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timeline`
--
ALTER TABLE `timeline`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timeline_sub`
--
ALTER TABLE `timeline_sub`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
