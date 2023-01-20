-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 20, 2023 at 10:17 AM
-- Server version: 5.7.41
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aisystem_db`
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
  `order_menu` smallint(6) DEFAULT NULL,
  `url` varchar(200) NOT NULL DEFAULT '#',
  `icon` varchar(150) NOT NULL DEFAULT 'fas fa-question-circle',
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `access`
--

INSERT INTO `access` (`id`, `parent_id`, `bagian_id`, `level`, `order_menu`, `url`, `icon`, `name`) VALUES
('131899f9a9204e0baa1b23cd2eedff6a', NULL, 'f7e795b9ece54c6d82b0ed19f025a65e', 1, 2, 'manajemen-web/users', 'fas fa-users-cog', 'Users'),
('30d0f70435904ad5b4e7cbfeb98fc021', NULL, '063203a5c5124b399ab76f8a03b93c0d', 1, 2, 'penerbitan/naskah', 'fas fa-file-alt', 'Naskah'),
('31a0187d88d94ddc83db4b71524b5b2d', NULL, '3f9dfd9391394a5fa10d835e0ebb341c', 1, 3, 'master/kelompok-buku', 'fas fa-layer-group', 'Kelompok Buku'),
('365190039fb44c8ab629806a5490addf', '63a1825ffe574c00929e532fd6241629', '063203a5c5124b399ab76f8a03b93c0d', 2, 1, 'penerbitan/pracetak/setter', 'fas fa-question-circle', 'Setter'),
('3dbad039493241aa8ed0c698d07ee94d', NULL, '3f9dfd9391394a5fa10d835e0ebb341c', 1, 4, 'master/format-buku', 'fas fa-ruler-combined', 'Format Buku'),
('4e1627c1489844f985cbe2c485b2e162', NULL, 'f7e795b9ece54c6d82b0ed19f025a65e', 1, 1, 'manajemen-web/struktur-ao', 'fas fa-project-diagram', 'Struktur Organisasi'),
('5646908e-1e06-11ed-87ce-1078d2a38ee5', NULL, '8a3ca046fb54492a86aaead53f36bec7', 1, 1, 'produksi/proses/cetak', 'fas fa-chalkboard-teacher', 'Proses Produksi Cetak'),
('583a723cf036449d80d3742dcf695e38', NULL, '063203a5c5124b399ab76f8a03b93c0d', 1, 9, 'penerbitan/naskah/timeline', 'fas fa-question-circle', 'Timeline'),
('5ce34256ce1f4a8989ac8f3510576600', NULL, '063203a5c5124b399ab76f8a03b93c0d', 1, 3, '#', 'fas fa-clipboard-check', 'Deskripsi'),
('63a1825ffe574c00929e532fd6241629', NULL, '063203a5c5124b399ab76f8a03b93c0d', 1, 5, '#', 'fas fa-file-powerpoint', 'Pracetak'),
('6b6e6377467d4b67911ef1b915244ed2', '5ce34256ce1f4a8989ac8f3510576600', '063203a5c5124b399ab76f8a03b93c0d', 2, 4, 'penerbitan/deskripsi/turun-cetak', 'fas fa-question-circle', 'Turun Cetak'),
('70410774a1e0433bb213a9625aceb0bb', NULL, '063203a5c5124b399ab76f8a03b93c0d', 1, 6, 'penerbitan/order-cetak', 'fas fa-print', 'Order Cetak'),
('71d6b5671ebb4e128215fccc458fbf09', '5ce34256ce1f4a8989ac8f3510576600', '063203a5c5124b399ab76f8a03b93c0d', 2, 3, 'penerbitan/deskripsi/cover', 'fas fa-question-circle', 'Cover'),
('8bc1be5db97545e2ab1c79e0d68d4896', NULL, '3f9dfd9391394a5fa10d835e0ebb341c', 1, 1, 'master/platform-digital', 'fas fa-globe', 'Platform Digital'),
('92463f9e96394c19a979a3290fde5745', NULL, '063203a5c5124b399ab76f8a03b93c0d', 1, 4, 'penerbitan/editing', 'fas fa-user-edit', 'Editing'),
('b6cbf112-1e06-11ed-87ce-1078d2a38ee5', NULL, '8a3ca046fb54492a86aaead53f36bec7', 1, 2, 'produksi/proses/ebook-multimedia', 'fas fa-desktop', 'E-book Multimedia'),
('bc5eb3aa02394dcca7692764e1328cee', NULL, '3f9dfd9391394a5fa10d835e0ebb341c', 1, 2, 'master/imprint', 'fas fa-stamp', 'Imprint'),
('bd09e803c41245a49ef23987c27b20ac', '5ce34256ce1f4a8989ac8f3510576600', '063203a5c5124b399ab76f8a03b93c0d', 2, 1, 'penerbitan/deskripsi/produk', 'fas fa-question-circle', 'Produk'),
('be061671a86c4437803f7c225e117799', '63a1825ffe574c00929e532fd6241629', '063203a5c5124b399ab76f8a03b93c0d', 2, 2, 'penerbitan/pracetak/designer', 'fas fa-question-circle', 'Designer'),
('bfb8b970f85c4a42bac1dc56181dc96b', NULL, '063203a5c5124b399ab76f8a03b93c0d', 1, 7, 'penerbitan/order-ebook', 'fas fa-atlas', 'Order E-Book'),
('e32aa5bb41144ac58f2e6eeca81604ac', '5ce34256ce1f4a8989ac8f3510576600', '063203a5c5124b399ab76f8a03b93c0d', 2, 2, 'penerbitan/deskripsi/final', 'fas fa-clipboard-check', 'Final'),
('fb6c8f0dcc9e43199642f08a0fe1fd56', NULL, '063203a5c5124b399ab76f8a03b93c0d', 1, 1, 'penerbitan/penulis', 'fas fa-pen', 'Penulis');

-- --------------------------------------------------------

--
-- Table structure for table `access_bagian`
--

CREATE TABLE `access_bagian` (
  `id` varchar(36) NOT NULL,
  `name` varchar(150) NOT NULL,
  `order_ab` tinyint(4) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `access_bagian`
--

INSERT INTO `access_bagian` (`id`, `name`, `order_ab`) VALUES
('04431b2b0e864cd4af41c87256cb92ef', 'Dashboard', 1),
('063203a5c5124b399ab76f8a03b93c0d', 'Penerbitan', 3),
('3f9dfd9391394a5fa10d835e0ebb341c', 'Master Data', 2),
('8a3ca046fb54492a86aaead53f36bec7', 'Produksi', 4),
('b80a98c1ba2848b39d524cc8d30a742c', 'Penjualan', 5),
('f7e795b9ece54c6d82b0ed19f025a65e', 'Manajemen Web', 6);

-- --------------------------------------------------------

--
-- Table structure for table `cabang`
--

CREATE TABLE `cabang` (
  `id` varchar(36) NOT NULL,
  `kode` varchar(5) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `telp` varchar(20) DEFAULT NULL,
  `alamat` text,
  `created_by` varchar(36) DEFAULT NULL,
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cabang`
--

INSERT INTO `cabang` (`id`, `kode`, `nama`, `telp`, `alamat`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
('ada2962f70ce45fd8b930f1babafeba8', '0000', 'Head Office', '0274561881', 'JL Beo 38-40 Yogyakarta', 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-03-31 08:32:58', '2022-10-20 02:22:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `deskripsi_cover`
--

CREATE TABLE `deskripsi_cover` (
  `id` char(36) NOT NULL,
  `deskripsi_produk_id` char(36) DEFAULT NULL,
  `des_front_cover` varchar(255) DEFAULT NULL,
  `des_back_cover` varchar(255) DEFAULT NULL,
  `finishing_cover` text COMMENT 'Array',
  `jilid` enum('Binding','Jahit Kawat','Jahit Benang') DEFAULT NULL,
  `tipografi` varchar(255) DEFAULT NULL,
  `warna` varchar(255) DEFAULT NULL,
  `desainer` varchar(36) DEFAULT NULL,
  `bulan` date DEFAULT NULL,
  `tgl_deskripsi` datetime DEFAULT NULL,
  `contoh_cover` longtext COMMENT 'Url link',
  `kelengkapan` varchar(9) DEFAULT NULL,
  `catatan` text,
  `status` enum('Pending','Proses','Selesai','Antrian','Terkunci') NOT NULL DEFAULT 'Terkunci',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `deskripsi_cover`
--

INSERT INTO `deskripsi_cover` (`id`, `deskripsi_produk_id`, `des_front_cover`, `des_back_cover`, `finishing_cover`, `jilid`, `tipografi`, `warna`, `desainer`, `bulan`, `tgl_deskripsi`, `contoh_cover`, `kelengkapan`, `catatan`, `status`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
('2c05015a-d003-42d9-8a90-3bb62fc1103a', '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Warna kuning..', 'Warna biru..', '[\"Glossy\",\"Laminasi Dof\",\"UV Spot\"]', 'Binding', NULL, NULL, '3d43ab399ec24c30b39c9b052686416d', '2022-10-22', '2022-10-04 16:34:45', 'https://www.google.com', NULL, NULL, 'Selesai', '2022-11-02 07:42:25', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL),
('5132c563-ec87-4fc6-8c53-dba06f239315', '9dd93fdc-e481-4d31-89f1-21a2617f16de', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-17 15:15:09', NULL, NULL, NULL, 'Terkunci', NULL, NULL, NULL, NULL),
('765d9de0-7af1-4d03-8376-01bc87a93e6e', 'a5c58d55-5359-44c0-bdbb-55e26de48908', 'Bdbdbs', 'Nehsnsn', '[\"Embosh\",\"Glossy\"]', 'Binding', 'Nsnsbbs', 'Merah', '3d43ab399ec24c30b39c9b052686416d', '2023-01-01', '2023-01-18 14:43:12', 'https://web.aisystem.id/penerbitan/deskripsi/cover/edit?desc=765d9de0-7af1-4d03-8376-01bc87a93e6e&kode=NA20230118030', 'Download', 'Nsnsbsbbe', 'Selesai', '2023-01-18 08:15:14', 'ceadd9fb648445eab1e350357e51d1ce', NULL, NULL),
('970dc95b-f768-4713-acb1-b4b116f33b73', '77e7ef01-7c5f-48de-a401-11885576edf3', 'Hdnduebdbdejbdhdbfjfbdmsbdb', 'Nsbdhebdnsbensbsbsnebrrmdbebenejsh', '[\"Embosh\",\"Glossy\"]', 'Binding', 'Ndgendbdh', 'Merah', '48ca3074299d46d7b557343ab9106671', '2023-01-01', '2023-01-17 15:17:18', 'https://web.aisystem.id/penerbitan/deskripsi/cover/edit?desc=970dc95b-f768-4713-acb1-b4b116f33b73&kode=NA20230117029', 'Barcode', 'Gek dang', 'Selesai', '2023-01-17 08:38:22', 'ceadd9fb648445eab1e350357e51d1ce', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `deskripsi_cover_history`
--

CREATE TABLE `deskripsi_cover_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `deskripsi_cover_id` char(36) DEFAULT NULL,
  `type_history` enum('Status','Update') DEFAULT NULL,
  `sub_judul_final_his` varchar(255) DEFAULT NULL,
  `sub_judul_final_new` varchar(255) DEFAULT NULL,
  `bullet_his` text,
  `bullet_new` text,
  `des_front_cover_his` varchar(255) DEFAULT NULL,
  `des_front_cover_new` varchar(255) DEFAULT NULL,
  `des_back_cover_his` varchar(255) DEFAULT NULL,
  `des_back_cover_new` varchar(255) DEFAULT NULL,
  `finishing_cover_his` text,
  `finishing_cover_new` text,
  `format_buku_his` varchar(15) DEFAULT NULL,
  `format_buku_new` varchar(15) DEFAULT NULL,
  `jilid_his` varchar(15) DEFAULT NULL,
  `jilid_new` varchar(15) DEFAULT NULL,
  `kelengkapan_his` varchar(10) DEFAULT NULL,
  `kelengkapan_new` varchar(10) DEFAULT NULL,
  `tipografi_his` varchar(255) DEFAULT NULL,
  `tipografi_new` varchar(255) DEFAULT NULL,
  `warna_his` varchar(255) DEFAULT NULL,
  `warna_new` varchar(255) DEFAULT NULL,
  `desainer_his` varchar(36) DEFAULT NULL,
  `desainer_new` varchar(36) DEFAULT NULL,
  `catatan_his` text,
  `catatan_new` text,
  `bulan_his` date DEFAULT NULL,
  `bulan_new` date DEFAULT NULL,
  `contoh_cover_his` longtext COMMENT 'Url link',
  `contoh_cover_new` longtext COMMENT 'Url link',
  `status_his` varchar(8) DEFAULT NULL,
  `status_new` varchar(8) DEFAULT NULL,
  `author_id` varchar(36) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `deskripsi_cover_history`
--

INSERT INTO `deskripsi_cover_history` (`id`, `deskripsi_cover_id`, `type_history`, `sub_judul_final_his`, `sub_judul_final_new`, `bullet_his`, `bullet_new`, `des_front_cover_his`, `des_front_cover_new`, `des_back_cover_his`, `des_back_cover_new`, `finishing_cover_his`, `finishing_cover_new`, `format_buku_his`, `format_buku_new`, `jilid_his`, `jilid_new`, `kelengkapan_his`, `kelengkapan_new`, `tipografi_his`, `tipografi_new`, `warna_his`, `warna_new`, `desainer_his`, `desainer_new`, `catatan_his`, `catatan_new`, `bulan_his`, `bulan_new`, `contoh_cover_his`, `contoh_cover_new`, `status_his`, `status_new`, `author_id`, `modified_at`) VALUES
(1, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', 'be8d42fa88a14406ac201974963d9c1b', '2022-10-18 15:27:19'),
(2, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"Embosh\",\"Foil\",\"Glossy\",\"Laminasi Dof\",\"UV\",\"UV Spot\"]', '[\"Embosh\",\"Glossy\"]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-01', '2022-10-22', NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-22 16:11:00'),
(3, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"Embosh\",\"Glossy\"]', '[\"Embosh\",\"Foil\",\"Glossy\"]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-22', '2022-10-22', NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-22 17:50:27'),
(4, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"Embosh\",\"Foil\",\"Glossy\"]', '[\"Laminasi Dof\"]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-22', '2022-10-22', NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-22 19:00:40'),
(5, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[\"Laminasi Dof\"]', '[\"Glossy\",\"Laminasi Dof\",\"UV Spot\"]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-22', '2022-10-22', NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-22 19:01:02'),
(6, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', 'be8d42fa88a14406ac201974963d9c1b', '2022-10-24 22:58:02'),
(7, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', 'be8d42fa88a14406ac201974963d9c1b', '2022-10-25 10:02:49'),
(8, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', 'be8d42fa88a14406ac201974963d9c1b', '2022-11-02 14:42:25'),
(9, '970dc95b-f768-4713-acb1-b4b116f33b73', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-17 15:27:18'),
(10, '970dc95b-f768-4713-acb1-b4b116f33b73', 'Update', NULL, NULL, NULL, NULL, NULL, 'Hdnduebdbdejbdhdbfjfbdmsbdb', NULL, 'Nsbdhebdnsbensbsbsnebrrmdbebenejsh', NULL, '[\"Embosh\",\"Glossy\"]', NULL, NULL, NULL, 'Binding', NULL, 'Barcode', NULL, 'Ndgendbdh', NULL, 'Merah', NULL, '48ca3074299d46d7b557343ab9106671', NULL, 'Gek dang', NULL, '2023-01-01', NULL, 'https://web.aisystem.id/penerbitan/deskripsi/cover/edit?desc=970dc95b-f768-4713-acb1-b4b116f33b73&kode=NA20230117029', NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-17 15:38:07'),
(11, '970dc95b-f768-4713-acb1-b4b116f33b73', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-17 15:38:22'),
(12, '765d9de0-7af1-4d03-8376-01bc87a93e6e', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-18 14:58:37'),
(13, '765d9de0-7af1-4d03-8376-01bc87a93e6e', 'Update', NULL, NULL, NULL, NULL, NULL, 'Bdbdbs', NULL, 'Nehsnsn', NULL, '[\"Embosh\",\"Glossy\"]', NULL, NULL, NULL, 'Binding', NULL, 'Download', NULL, 'Nsnsbbs', NULL, 'Merah', NULL, '3d43ab399ec24c30b39c9b052686416d', NULL, 'Nsnsbsbbe', NULL, '2023-01-01', NULL, NULL, NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-18 14:59:48'),
(15, '765d9de0-7af1-4d03-8376-01bc87a93e6e', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://web.aisystem.id/penerbitan/deskripsi/cover/edit?desc=765d9de0-7af1-4d03-8376-01bc87a93e6e&kode=NA20230118030', NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-18 15:00:43'),
(17, '765d9de0-7af1-4d03-8376-01bc87a93e6e', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-18 15:03:22'),
(23, '765d9de0-7af1-4d03-8376-01bc87a93e6e', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-18 15:15:14');

-- --------------------------------------------------------

--
-- Table structure for table `deskripsi_final`
--

CREATE TABLE `deskripsi_final` (
  `id` char(36) NOT NULL,
  `deskripsi_produk_id` char(36) DEFAULT NULL,
  `sub_judul_final` varchar(255) DEFAULT NULL,
  `kertas_isi` enum('60 Gr','70 Gr','80 Gr','Art Paper','Book Paper','CD/Buram','Imperial','Matte Paper') DEFAULT NULL,
  `jml_hal_asli` int(11) DEFAULT NULL,
  `ukuran_asli` varchar(10) DEFAULT NULL,
  `isi_warna` enum('Black & White','Dua Warna','Full Color','Sisipan Warna') DEFAULT NULL,
  `isi_huruf` varchar(50) DEFAULT NULL,
  `bullet` text COMMENT 'array',
  `sinopsis` longtext,
  `setter` varchar(36) DEFAULT NULL,
  `korektor` varchar(36) DEFAULT NULL,
  `bulan` date DEFAULT NULL,
  `tgl_deskripsi` datetime DEFAULT NULL,
  `kelengkapan` enum('Barcode','CD','Download') DEFAULT NULL,
  `catatan` text,
  `status` enum('Pending','Proses','Selesai','Antrian') NOT NULL DEFAULT 'Antrian',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `deskripsi_final`
--

INSERT INTO `deskripsi_final` (`id`, `deskripsi_produk_id`, `sub_judul_final`, `kertas_isi`, `jml_hal_asli`, `ukuran_asli`, `isi_warna`, `isi_huruf`, `bullet`, `sinopsis`, `setter`, `korektor`, `bulan`, `tgl_deskripsi`, `kelengkapan`, `catatan`, `status`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
('330bb9ae-7378-4bf7-bf48-6eb066f2d566', '77e7ef01-7c5f-48de-a401-11885576edf3', 'Langkah cepat move on', '70 Gr', 125, 'A4', 'Black & White', 'Arial', '[]', 'Akankah aku bisa melupakanmu seperti engkau meluoakanku', '6fc8b6f16e394f6f802ece3753ba36dc', '0c5a151afe204df2bf6c38485055da16', '2023-01-01', '2023-01-17 15:17:18', NULL, 'Cepat', 'Selesai', '2023-01-17 08:26:55', 'ceadd9fb648445eab1e350357e51d1ce', NULL, NULL),
('44dfe332-5755-4191-8158-e79d496e1473', '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', NULL, 'Art Paper', 900, NULL, 'Full Color', 'Menyesuaikan', '[\"Cara Mendapati Masa Depan Dengan Bahagia\",\"Menjauhi Prasangka Yang Membinasakan\",\"Menyikapi Problematika\"]', 'Buku ini merupakan sebuah pandangan hidup sesorang tentang betapa unik dan penuh kompleksitas segala entitas.', 'a4f8d1d67d2e4b9aa2a8e8680a953194', '12c8a8639d814102b01c7ffc0cd52e71', '2022-10-01', '2022-10-04 16:34:45', 'Download', NULL, 'Selesai', '2022-10-22 09:08:44', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL),
('ae96ee7e-5581-4459-9a4a-004d10f35a6c', '9dd93fdc-e481-4d31-89f1-21a2617f16de', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-17 15:15:09', NULL, NULL, 'Proses', '2023-01-17 08:39:57', 'ceadd9fb648445eab1e350357e51d1ce', NULL, NULL),
('b8536fe9-d27a-457a-be6d-e5af83d7836b', 'a5c58d55-5359-44c0-bdbb-55e26de48908', NULL, '70 Gr', 143, 'A4', 'Dua Warna', 'Arial', '[]', NULL, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '0007828bc2a5496bbdd8fbaefe2e1565', '2023-01-01', '2023-01-18 14:43:12', NULL, NULL, 'Selesai', '2023-01-18 07:57:00', 'ceadd9fb648445eab1e350357e51d1ce', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `deskripsi_final_history`
--

CREATE TABLE `deskripsi_final_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `deskripsi_final_id` char(36) DEFAULT NULL,
  `type_history` enum('Status','Update') DEFAULT NULL,
  `format_buku_his` varchar(15) DEFAULT NULL,
  `format_buku_new` varchar(15) DEFAULT NULL,
  `judul_final_his` varchar(255) DEFAULT NULL,
  `judul_final_new` varchar(255) DEFAULT NULL,
  `sub_judul_final_his` varchar(255) DEFAULT NULL,
  `sub_judul_final_new` varchar(255) DEFAULT NULL,
  `kertas_isi_his` varchar(15) DEFAULT NULL,
  `kertas_isi_new` varchar(15) DEFAULT NULL,
  `jml_hal_perkiraan_his` int(11) DEFAULT NULL,
  `jml_hal_perkiraan_new` int(11) DEFAULT NULL,
  `jml_hal_asli_his` int(11) DEFAULT NULL,
  `jml_hal_asli_new` int(11) DEFAULT NULL,
  `ukuran_asli_his` varchar(10) DEFAULT NULL,
  `ukuran_asli_new` varchar(10) DEFAULT NULL,
  `isi_warna_his` varchar(15) DEFAULT NULL,
  `isi_warna_new` varchar(15) DEFAULT NULL,
  `isi_huruf_his` varchar(50) DEFAULT NULL,
  `isi_huruf_new` varchar(50) DEFAULT NULL,
  `bullet_his` text,
  `bullet_new` text,
  `setter_his` varchar(36) DEFAULT NULL,
  `setter_new` varchar(36) DEFAULT NULL,
  `korektor_his` varchar(36) DEFAULT NULL,
  `korektor_new` varchar(36) DEFAULT NULL,
  `sinopsis_his` longtext,
  `sinopsis_new` longtext,
  `bulan_his` date DEFAULT NULL,
  `bulan_new` date DEFAULT NULL,
  `kelengkapan_his` varchar(10) DEFAULT NULL,
  `kelengkapan_new` varchar(10) DEFAULT NULL,
  `catatan_his` text,
  `catatan_new` text,
  `status_his` varchar(8) DEFAULT NULL,
  `status_new` varchar(8) DEFAULT NULL,
  `author_id` varchar(36) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `deskripsi_final_history`
--

INSERT INTO `deskripsi_final_history` (`id`, `deskripsi_final_id`, `type_history`, `format_buku_his`, `format_buku_new`, `judul_final_his`, `judul_final_new`, `sub_judul_final_his`, `sub_judul_final_new`, `kertas_isi_his`, `kertas_isi_new`, `jml_hal_perkiraan_his`, `jml_hal_perkiraan_new`, `jml_hal_asli_his`, `jml_hal_asli_new`, `ukuran_asli_his`, `ukuran_asli_new`, `isi_warna_his`, `isi_warna_new`, `isi_huruf_his`, `isi_huruf_new`, `bullet_his`, `bullet_new`, `setter_his`, `setter_new`, `korektor_his`, `korektor_new`, `sinopsis_his`, `sinopsis_new`, `bulan_his`, `bulan_new`, `kelengkapan_his`, `kelengkapan_new`, `catatan_his`, `catatan_new`, `status_his`, `status_new`, `author_id`, `modified_at`) VALUES
(1, '44dfe332-5755-4191-8158-e79d496e1473', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', 'be8d42fa88a14406ac201974963d9c1b', '2022-10-07 15:13:56'),
(2, '44dfe332-5755-4191-8158-e79d496e1473', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Antrian', 'be8d42fa88a14406ac201974963d9c1b', '2022-10-11 08:55:03'),
(3, '44dfe332-5755-4191-8158-e79d496e1473', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', 'be8d42fa88a14406ac201974963d9c1b', '2022-10-11 11:01:50'),
(4, '44dfe332-5755-4191-8158-e79d496e1473', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Antrian', 'be8d42fa88a14406ac201974963d9c1b', '2022-10-11 15:35:40'),
(5, '44dfe332-5755-4191-8158-e79d496e1473', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', 'be8d42fa88a14406ac201974963d9c1b', '2022-10-11 15:38:53'),
(6, '44dfe332-5755-4191-8158-e79d496e1473', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Pending', 'be8d42fa88a14406ac201974963d9c1b', '2022-10-11 15:53:51'),
(7, '44dfe332-5755-4191-8158-e79d496e1473', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Proses', 'be8d42fa88a14406ac201974963d9c1b', '2022-10-11 15:54:48'),
(8, '44dfe332-5755-4191-8158-e79d496e1473', 'Update', NULL, NULL, 'Aku & Di Balik Mata Kaca', 'Aku & Di Balik Mata Kaca', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 127, NULL, NULL, NULL, 'Full Color', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-01', NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-11 16:18:58'),
(9, '44dfe332-5755-4191-8158-e79d496e1473', 'Update', NULL, NULL, 'Aku & Di Balik Mata Kaca', 'Aku & Di Balik Mata Kaca', NULL, NULL, NULL, 'Art Paper', NULL, NULL, 127, 127, NULL, NULL, 'Full Color', 'Full Color', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-01', '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-11 16:24:48'),
(10, '44dfe332-5755-4191-8158-e79d496e1473', 'Update', NULL, NULL, 'Aku & Di Balik Mata Kaca', 'Aku & Di Balik Mata Kaca', NULL, NULL, 'Art Paper', 'Art Paper', NULL, NULL, 127, 127, NULL, NULL, 'Full Color', 'Full Color', NULL, NULL, NULL, '[null]', NULL, NULL, NULL, NULL, NULL, NULL, '1970-01-01', '2022-10-01', NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-11 16:27:29'),
(11, '44dfe332-5755-4191-8158-e79d496e1473', 'Update', NULL, NULL, 'Aku & Di Balik Mata Kaca', 'Aku & Di Balik Mata Kaca', NULL, NULL, 'Art Paper', 'Art Paper', NULL, NULL, 127, 127, NULL, NULL, 'Full Color', 'Full Color', NULL, NULL, NULL, '[null]', NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-01', '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-11 16:28:38'),
(12, '44dfe332-5755-4191-8158-e79d496e1473', 'Update', NULL, NULL, 'Aku & Di Balik Mata Kaca', 'Aku & Di Balik Mata Kaca', NULL, NULL, 'Art Paper', 'Art Paper', NULL, NULL, 127, 127, NULL, NULL, 'Full Color', 'Full Color', NULL, NULL, NULL, '[null]', NULL, '355c4e0e850c43f382cf1052f7053f40', NULL, NULL, NULL, NULL, '1970-01-01', '2022-10-01', NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-12 09:12:14'),
(13, '44dfe332-5755-4191-8158-e79d496e1473', 'Update', NULL, NULL, 'Aku & Di Balik Mata Kaca', 'Aku & Di Balik Mata Kaca', NULL, NULL, 'Art Paper', 'Art Paper', NULL, NULL, 127, 127, NULL, NULL, 'Full Color', 'Black & White', NULL, NULL, NULL, '[]', '355c4e0e850c43f382cf1052f7053f40', '355c4e0e850c43f382cf1052f7053f40', NULL, NULL, NULL, NULL, '2022-10-01', '2022-10-01', NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-12 09:18:45'),
(14, '44dfe332-5755-4191-8158-e79d496e1473', 'Update', NULL, NULL, 'Aku & Di Balik Mata Kaca', 'Aku & Di Balik Mata Kaca', NULL, NULL, 'Art Paper', 'Art Paper', NULL, NULL, 127, 127, NULL, NULL, 'Black & White', 'Full Color', NULL, NULL, NULL, '[]', '355c4e0e850c43f382cf1052f7053f40', '355c4e0e850c43f382cf1052f7053f40', NULL, NULL, NULL, NULL, '2022-10-01', '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-12 09:23:33'),
(15, '44dfe332-5755-4191-8158-e79d496e1473', 'Update', NULL, NULL, 'Aku & Di Balik Mata Kaca', 'Aku & Di Balik Mata Kaca', NULL, NULL, 'Art Paper', 'Art Paper', NULL, NULL, 127, 127, NULL, NULL, 'Full Color', 'Full Color', NULL, NULL, NULL, '[]', '355c4e0e850c43f382cf1052f7053f40', '355c4e0e850c43f382cf1052f7053f40', NULL, NULL, NULL, NULL, '1970-01-01', '2022-10-01', NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-12 09:26:25'),
(16, '44dfe332-5755-4191-8158-e79d496e1473', 'Update', NULL, NULL, 'Aku & Di Balik Mata Kaca', 'Aku & Di Balik Mata Kaca', NULL, NULL, 'Art Paper', 'Art Paper', NULL, NULL, 900, 900, NULL, NULL, 'Full Color', 'Full Color', NULL, NULL, '[]', '[\"Cara Mendapati Masa Depan Dengan Bahagia\",\"Menjauhi Prasangka Yang Membinasakan\",\"Menyikapi Problematika\"]', '355c4e0e850c43f382cf1052f7053f40', '355c4e0e850c43f382cf1052f7053f40', NULL, NULL, NULL, NULL, '2022-10-01', '0000-00-00', NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-13 13:55:26'),
(17, '44dfe332-5755-4191-8158-e79d496e1473', 'Update', NULL, NULL, 'Aku & Di Balik Mata Kaca', 'Aku & Di Balik Mata Kaca', NULL, NULL, 'Art Paper', 'Art Paper', NULL, NULL, 900, 900, NULL, NULL, 'Full Color', 'Full Color', NULL, NULL, '[\"Cara Mendapati Masa Depan Dengan Bahagia\",\"Menjauhi Prasangka Yang Membinasakan\",\"Menyikapi Problematika\"]', '[\"Cara Mendapati Masa Depan Dengan Bahagia\",\"Menjauhi Prasangka Yang Membinasakan\",\"Menyikapi Problematika\"]', '355c4e0e850c43f382cf1052f7053f40', '355c4e0e850c43f382cf1052f7053f40', NULL, NULL, NULL, NULL, '0000-00-00', '2022-10-01', NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-13 14:21:56'),
(18, '44dfe332-5755-4191-8158-e79d496e1473', 'Update', NULL, NULL, 'Aku & Di Balik Mata Kaca', 'Aku & Di Balik Mata Kaca', NULL, NULL, 'Art Paper', 'Art Paper', NULL, NULL, 900, 900, NULL, NULL, 'Full Color', 'Full Color', NULL, 'Menyesuaikan', '[\"Cara Mendapati Masa Depan Dengan Bahagia\",\"Menjauhi Prasangka Yang Membinasakan\",\"Menyikapi Problematika\"]', '[\"Cara Mendapati Masa Depan Dengan Bahagia\",\"Menjauhi Prasangka Yang Membinasakan\",\"Menyikapi Problematika\"]', '355c4e0e850c43f382cf1052f7053f40', '355c4e0e850c43f382cf1052f7053f40', NULL, NULL, NULL, NULL, '2022-10-01', '2022-10-13', NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-13 14:22:19'),
(19, '44dfe332-5755-4191-8158-e79d496e1473', 'Update', NULL, NULL, 'Aku & Di Balik Mata Kaca', 'Aku & Di Balik Mata Kaca', NULL, NULL, 'Art Paper', 'Art Paper', NULL, NULL, 900, 900, NULL, NULL, 'Full Color', 'Full Color', 'Menyesuaikan', 'Menyesuaikan', '[\"Cara Mendapati Masa Depan Dengan Bahagia\",\"Menjauhi Prasangka Yang Membinasakan\",\"Menyikapi Problematika\"]', '[\"Cara Mendapati Masa Depan Dengan Bahagia\",\"Menjauhi Prasangka Yang Membinasakan\",\"Menyikapi Problematika\"]', '355c4e0e850c43f382cf1052f7053f40', '355c4e0e850c43f382cf1052f7053f40', NULL, NULL, NULL, 'Buku ini merupakan sebuah pandangan hidup sesorang tentang betapa unik dan penuh kompleksitas segala entitas.', '2022-10-13', '2022-10-13', NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-13 14:58:05'),
(20, '44dfe332-5755-4191-8158-e79d496e1473', 'Update', '15,5 x 23', '19,5 x 27,', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-13', '2022-10-17', NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-17 13:09:56'),
(21, '44dfe332-5755-4191-8158-e79d496e1473', 'Update', '19,5 x 27,', '25 x 17,6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-17', '2022-10-17', NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-17 13:13:58'),
(22, '44dfe332-5755-4191-8158-e79d496e1473', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Pending', 'be8d42fa88a14406ac201974963d9c1b', '2022-10-18 09:01:51'),
(23, '44dfe332-5755-4191-8158-e79d496e1473', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Selesai', 'be8d42fa88a14406ac201974963d9c1b', '2022-10-18 09:30:07'),
(24, '44dfe332-5755-4191-8158-e79d496e1473', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', 'be8d42fa88a14406ac201974963d9c1b', '2022-10-18 09:38:25'),
(25, '44dfe332-5755-4191-8158-e79d496e1473', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', 'be8d42fa88a14406ac201974963d9c1b', '2022-10-18 09:52:57'),
(26, '44dfe332-5755-4191-8158-e79d496e1473', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', 'be8d42fa88a14406ac201974963d9c1b', '2022-10-18 10:27:31'),
(27, '44dfe332-5755-4191-8158-e79d496e1473', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Selesai', 'Proses', 'be8d42fa88a14406ac201974963d9c1b', '2022-10-18 10:34:49'),
(28, '44dfe332-5755-4191-8158-e79d496e1473', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', 'be8d42fa88a14406ac201974963d9c1b', '2022-10-18 10:41:04'),
(29, '44dfe332-5755-4191-8158-e79d496e1473', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Selesai', 'Proses', 'be8d42fa88a14406ac201974963d9c1b', '2022-10-18 13:50:19'),
(30, '44dfe332-5755-4191-8158-e79d496e1473', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', 'be8d42fa88a14406ac201974963d9c1b', '2022-10-18 13:51:30'),
(31, '44dfe332-5755-4191-8158-e79d496e1473', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-17', '2022-10-22', NULL, 'Download', NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-22 13:25:02'),
(32, '44dfe332-5755-4191-8158-e79d496e1473', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a4f8d1d67d2e4b9aa2a8e8680a953194', NULL, '12c8a8639d814102b01c7ffc0cd52e71', NULL, NULL, '2022-10-22', '2022-10-22', 'Download', NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-22 13:38:11'),
(33, '44dfe332-5755-4191-8158-e79d496e1473', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-22', '2022-10-22', NULL, 'Download', NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-22 13:50:05'),
(34, '44dfe332-5755-4191-8158-e79d496e1473', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-22', '2022-10-01', NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-22 15:50:14'),
(35, '44dfe332-5755-4191-8158-e79d496e1473', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-01', '2022-11-01', NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-22 16:08:30'),
(36, '44dfe332-5755-4191-8158-e79d496e1473', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-01', '2022-10-01', NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-22 16:08:44'),
(37, '330bb9ae-7378-4bf7-bf48-6eb066f2d566', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-17 15:17:38'),
(38, '330bb9ae-7378-4bf7-bf48-6eb066f2d566', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-17 15:25:46'),
(39, '330bb9ae-7378-4bf7-bf48-6eb066f2d566', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-17 15:26:55'),
(40, 'ae96ee7e-5581-4459-9a4a-004d10f35a6c', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-17 15:39:57'),
(41, 'b8536fe9-d27a-457a-be6d-e5af83d7836b', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-18 14:45:56'),
(42, 'b8536fe9-d27a-457a-be6d-e5af83d7836b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '70 Gr', NULL, NULL, NULL, 143, NULL, 'A4', NULL, 'Dua Warna', NULL, 'Arial', NULL, '[]', NULL, 'a4f8d1d67d2e4b9aa2a8e8680a953194', NULL, '0007828bc2a5496bbdd8fbaefe2e1565', NULL, NULL, NULL, '2023-01-01', NULL, NULL, NULL, NULL, NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-18 14:47:02'),
(43, 'b8536fe9-d27a-457a-be6d-e5af83d7836b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-18 14:56:30'),
(44, 'b8536fe9-d27a-457a-be6d-e5af83d7836b', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-18 14:57:00');

-- --------------------------------------------------------

--
-- Table structure for table `deskripsi_produk`
--

CREATE TABLE `deskripsi_produk` (
  `id` char(36) NOT NULL,
  `naskah_id` varchar(36) DEFAULT NULL,
  `judul_final` varchar(255) DEFAULT NULL,
  `alt_judul` longtext COMMENT 'array',
  `format_buku` varchar(15) DEFAULT NULL,
  `jml_hal_perkiraan` int(11) DEFAULT NULL,
  `imprint` varchar(50) DEFAULT NULL,
  `editor` varchar(36) DEFAULT NULL,
  `kelengkapan` enum('CD','Disket','DVD') DEFAULT NULL,
  `catatan` text,
  `bulan` date DEFAULT NULL,
  `tgl_deskripsi` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Pending','Proses','Selesai','Antrian','Revisi','Acc') DEFAULT NULL,
  `action_gm` datetime DEFAULT NULL,
  `alasan_revisi` tinytext,
  `deadline_revisi` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `deskripsi_produk`
--

INSERT INTO `deskripsi_produk` (`id`, `naskah_id`, `judul_final`, `alt_judul`, `format_buku`, `jml_hal_perkiraan`, `imprint`, `editor`, `kelengkapan`, `catatan`, `bulan`, `tgl_deskripsi`, `status`, `action_gm`, `alasan_revisi`, `deadline_revisi`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
('2f7bf184-23cf-42d7-9efc-19e47aaa3f14', '16fdb6f329c544c6824cf85ec38501b7', 'Aku & Di Balik Mata Kaca', '[\"Aku & Di Balik Mata Kaca\",\"Di Balik Mata Kaca Terabaikan\",\"Ada Di Balik Mata Kaca\"]', '25 x 17,6', 1000, 'G-Media', 'fab4f858e0314d1dbf6b5b834007313e', 'DVD', NULL, '2022-10-21', '2022-09-14 05:00:42', 'Acc', '2022-10-04 16:34:44', NULL, NULL, '2022-10-21 08:09:50', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL),
('77e7ef01-7c5f-48de-a401-11885576edf3', '5ace04aefcf14f1d968d5435091880dd', 'Aku lebih mudah melupakan dirimu', '[\"Aku lebih cepat lagi dan lagi\",\"Aku lebih mudah melupakan dirimu\"]', '18 x 14,5', 156, 'Andi', '8095be9de5414f7697531da885119cc3', NULL, 'Gek digawwE', '2023-01-01', '2023-01-17 07:35:46', 'Acc', '2023-01-17 15:17:18', NULL, NULL, '2023-01-17 08:59:19', 'ceadd9fb648445eab1e350357e51d1ce', NULL, NULL),
('9dd93fdc-e481-4d31-89f1-21a2617f16de', '334ddadb6c144d3cac9449be04dc3887', 'aku dan kamu tetap satu', '[\"aku dan kamu menjadi satu\",\"aku dan kamu tetap satu\",\"aku dan kamu menjadi kita\"]', '9 x 12,5', 115, 'Andi', 'e829fe4fb03f45f482f77653158d461c', NULL, 'butuh cepat jadi', '2023-01-01', '2023-01-13 09:27:06', 'Acc', '2023-01-17 15:15:09', NULL, NULL, '2023-01-17 08:15:09', 'ceadd9fb648445eab1e350357e51d1ce', NULL, NULL),
('a5c58d55-5359-44c0-bdbb-55e26de48908', 'f9e5451153bb4f6ab910dfee4e2668b0', 'Senang belajar origami', '[\"Origami asyik\",\"Yuk belajar origa,i\",\"Senang belajar origami\"]', '18 x 14,5', 123, 'Andi', '303f2de11d4f472d869757d7ea5bb83f', 'CD', 'Bsbssbbs', '2023-01-01', '2023-01-18 07:22:30', 'Acc', '2023-01-18 14:43:12', NULL, NULL, '2023-01-18 07:43:12', 'ceadd9fb648445eab1e350357e51d1ce', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `deskripsi_produk_history`
--

CREATE TABLE `deskripsi_produk_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `deskripsi_produk_id` char(36) DEFAULT NULL,
  `type_history` enum('Update','Approval','Revisi','Status') DEFAULT NULL,
  `judul_final_his` varchar(255) DEFAULT NULL,
  `judul_final_new` varchar(255) DEFAULT NULL,
  `alt_judul_his` longtext,
  `alt_judul_new` longtext,
  `format_buku_his` varchar(10) DEFAULT NULL,
  `format_buku_new` varchar(10) DEFAULT NULL,
  `jml_hal_his` int(11) DEFAULT NULL,
  `jml_hal_new` int(11) DEFAULT NULL,
  `imprint_his` varchar(50) DEFAULT NULL,
  `imprint_new` varchar(50) DEFAULT NULL,
  `editor_his` varchar(36) DEFAULT NULL,
  `editor_new` varchar(36) DEFAULT NULL,
  `kelengkapan_his` varchar(6) DEFAULT NULL,
  `kelengkapan_new` varchar(6) DEFAULT NULL,
  `catatan_his` text,
  `catatan_new` text,
  `bulan_his` date DEFAULT NULL,
  `bulan_new` date DEFAULT NULL,
  `status_his` varchar(8) DEFAULT NULL,
  `status_new` varchar(8) DEFAULT NULL,
  `alasan_revisi_his` tinytext,
  `deadline_revisi_his` datetime DEFAULT NULL,
  `author_id` varchar(36) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `deskripsi_produk_history`
--

INSERT INTO `deskripsi_produk_history` (`id`, `deskripsi_produk_id`, `type_history`, `judul_final_his`, `judul_final_new`, `alt_judul_his`, `alt_judul_new`, `format_buku_his`, `format_buku_new`, `jml_hal_his`, `jml_hal_new`, `imprint_his`, `imprint_new`, `editor_his`, `editor_new`, `kelengkapan_his`, `kelengkapan_new`, `catatan_his`, `catatan_new`, `bulan_his`, `bulan_new`, `status_his`, `status_new`, `alasan_revisi_his`, `deadline_revisi_his`, `author_id`, `modified_at`) VALUES
(1, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Antrian', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-09-26 14:13:36'),
(2, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Pending', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-09-26 14:46:46'),
(3, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Selesai', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-09-26 14:48:41'),
(4, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Selesai', 'Antrian', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-09-26 14:48:54'),
(5, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-09-26 14:49:00'),
(6, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Pending', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-09-26 14:49:12'),
(7, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Antrian', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-09-26 15:33:33'),
(8, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-09-26 22:40:11'),
(9, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Update', NULL, NULL, '[\"Aku & Di Balik Mata Kaca\",\"Di Balik Mata Kaca Terabaikan\",\"Ada Di Balik Mata Kaca\"]', '[\"Aku & Di Balik Mata Kaca\",\"Di Balik Mata Kaca Terabaikan\",\"Ada Di Balik Mata Kaca\"]', '10 x 20', '15,5 x 23', 300, 1000, 'YesCom', 'G-Media', NULL, NULL, 'CD', 'VCD', NULL, NULL, '2022-09-01', '2022-09-01', NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-09-26 22:45:04'),
(10, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Antrian', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-09-26 23:37:47'),
(11, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Pending', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-09-28 09:57:24'),
(12, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Proses', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-09-28 11:41:43'),
(13, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-09-28 13:11:12'),
(14, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Revisi', 'Selesai', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-09-28 16:15:13'),
(18, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Revisi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Selesai', 'Revisi', 'Data belum sepenuhnya lengkap.', '2022-10-05 14:08:15', 'ee2c544aa4dc4c1eb12472cd84406358', '2022-09-30 14:08:15'),
(19, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Revisi', 'Selesai', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-09-30 15:30:12'),
(20, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Selesai', 'Proses', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-09-30 15:31:29'),
(21, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Update', NULL, 'Aku & Di Balik Mata Kaca', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Update', NULL, 'Di Balik Mata Kaca Terabaikan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Update', NULL, 'Di Balik Mata Kaca Terabaikan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Update', NULL, 'Aku & Di Balik Mata Kaca', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Update', NULL, 'Aku & Di Balik Mata Kaca', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ee2c544aa4dc4c1eb12472cd84406358', '2022-10-03 14:27:27'),
(26, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Update', NULL, 'Di Balik Mata Kaca Terabaikan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ee2c544aa4dc4c1eb12472cd84406358', '2022-10-03 15:09:14'),
(27, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Update', NULL, 'Ada Di Balik Mata Kaca', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ee2c544aa4dc4c1eb12472cd84406358', '2022-10-03 15:09:39'),
(29, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Update', NULL, 'Ada Di Balik Mata Kaca', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ee2c544aa4dc4c1eb12472cd84406358', '2022-10-03 15:11:51'),
(30, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Update', NULL, 'Di Balik Mata Kaca Terabaikan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ee2c544aa4dc4c1eb12472cd84406358', '2022-10-03 15:16:16'),
(31, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Update', NULL, 'Aku & Di Balik Mata Kaca', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ee2c544aa4dc4c1eb12472cd84406358', '2022-10-03 15:16:47'),
(32, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Update', NULL, 'Ada Di Balik Mata Kaca', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ee2c544aa4dc4c1eb12472cd84406358', '2022-10-03 15:17:15'),
(33, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Update', NULL, 'Di Balik Mata Kaca Terabaikan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ee2c544aa4dc4c1eb12472cd84406358', '2022-10-03 15:18:51'),
(34, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Update', NULL, 'Aku & Di Balik Mata Kaca', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ee2c544aa4dc4c1eb12472cd84406358', '2022-10-03 15:19:40'),
(35, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Update', NULL, 'Ada Di Balik Mata Kaca', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ee2c544aa4dc4c1eb12472cd84406358', '2022-10-03 15:19:44'),
(36, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Update', NULL, 'Aku & Di Balik Mata Kaca', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ee2c544aa4dc4c1eb12472cd84406358', '2022-10-03 15:39:01'),
(37, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Update', NULL, 'Di Balik Mata Kaca Terabaikan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ee2c544aa4dc4c1eb12472cd84406358', '2022-10-03 15:58:25'),
(38, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-03 16:06:01'),
(39, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Update', NULL, 'Ada Di Balik Mata Kaca', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ee2c544aa4dc4c1eb12472cd84406358', '2022-10-03 16:20:50'),
(40, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Update', NULL, 'Aku & Di Balik Mata Kaca', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ee2c544aa4dc4c1eb12472cd84406358', '2022-10-04 08:41:34'),
(41, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Revisi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Selesai', 'Revisi', 'Belum lengkap, usulan editor harus diisi', '2022-10-11 08:42:05', 'ee2c544aa4dc4c1eb12472cd84406358', '2022-10-04 08:42:05'),
(42, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Revisi', 'Selesai', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-04 09:03:35'),
(43, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Revisi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Selesai', 'Revisi', 'Benerin lagi', '2022-10-09 09:04:01', 'ee2c544aa4dc4c1eb12472cd84406358', '2022-10-04 09:04:01'),
(44, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Revisi', 'Selesai', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-04 09:06:30'),
(45, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Selesai', 'Pending', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-04 10:29:32'),
(46, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Proses', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-04 10:29:57'),
(47, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Antrian', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-04 10:31:42'),
(48, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Selesai', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-04 10:32:47'),
(49, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Approval', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Selesai', 'Acc', NULL, NULL, 'ee2c544aa4dc4c1eb12472cd84406358', '2022-10-04 16:31:32'),
(50, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Approval', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Selesai', 'Acc', NULL, NULL, 'ee2c544aa4dc4c1eb12472cd84406358', '2022-10-04 16:34:44'),
(51, '44dfe332-5755-4191-8158-e79d496e1473', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-07 15:11:39'),
(52, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Update', NULL, NULL, '[\"Aku & Di Balik Mata Kaca\",\"Di Balik Mata Kaca Terabaikan\",\"Ada Di Balik Mata Kaca\"]', '[\"Aku & Di Balik Mata Kaca\",\"Di Balik Mata Kaca Terabaikan\",\"Ada Di Balik Mata Kaca\"]', '25 x 17,6', '25 x 17,6', 1000, 1000, 'G-Media', 'G-Media', NULL, NULL, NULL, 'Disket', NULL, NULL, '2022-09-01', '2022-09-01', NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-21 13:45:13'),
(53, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Disket', 'CD', NULL, NULL, '2022-09-01', '2022-09-01', NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-21 14:05:18'),
(54, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CD', 'VCD', NULL, NULL, '2022-09-01', '2022-10-21', NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-21 14:10:14'),
(55, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', 'CD', NULL, NULL, '2022-10-21', '2022-10-21', NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-21 14:15:13'),
(56, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CD', 'DVD', NULL, NULL, '2022-10-21', '2022-10-21', NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-21 14:24:45'),
(57, '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'fab4f858e0314d1dbf6b5b834007313e', NULL, NULL, NULL, NULL, '2022-10-21', '2022-10-21', NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-21 15:09:50'),
(58, '9dd93fdc-e481-4d31-89f1-21a2617f16de', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-13 16:39:33'),
(59, '9dd93fdc-e481-4d31-89f1-21a2617f16de', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-13 16:45:56'),
(60, '9dd93fdc-e481-4d31-89f1-21a2617f16de', 'Revisi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Selesai', 'Revisi', 'g menarik', '2023-01-17 16:46:50', 'dc306f83529445139c16dd55b4f0e6f3', '2023-01-13 16:46:50'),
(61, '9dd93fdc-e481-4d31-89f1-21a2617f16de', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Revisi', 'Proses', NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-14 14:41:58'),
(62, '9dd93fdc-e481-4d31-89f1-21a2617f16de', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Pending', NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-14 14:59:48'),
(63, '9dd93fdc-e481-4d31-89f1-21a2617f16de', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Proses', NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-14 15:12:34'),
(64, '9dd93fdc-e481-4d31-89f1-21a2617f16de', 'Update', NULL, NULL, NULL, '[\"aku dan kamu menjadi satu\",\"aku dan kamu tetap satu\",\"aku dan kamu menjadi kita\"]', NULL, '9 x 12,5', NULL, 115, NULL, 'Andi', NULL, 'e829fe4fb03f45f482f77653158d461c', NULL, NULL, NULL, 'butuh cepat jadi', NULL, '2023-01-01', NULL, NULL, NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-16 09:27:21'),
(65, '9dd93fdc-e481-4d31-89f1-21a2617f16de', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-16 09:27:52'),
(66, '77e7ef01-7c5f-48de-a401-11885576edf3', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-17 14:36:52'),
(67, '77e7ef01-7c5f-48de-a401-11885576edf3', 'Update', NULL, NULL, NULL, '[\"Aku lebih cepat lagi dan lagi\",\"Aku lebih mudah melupakan dirimu\"]', NULL, '18 x 14,5', NULL, 125, NULL, 'Andi', NULL, '8095be9de5414f7697531da885119cc3', NULL, NULL, NULL, 'Gek digawwE', NULL, '2023-01-01', NULL, NULL, NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-17 14:38:24'),
(68, '77e7ef01-7c5f-48de-a401-11885576edf3', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-17 14:38:44'),
(69, '77e7ef01-7c5f-48de-a401-11885576edf3', 'Update', NULL, 'Aku lebih mudah melupakan dirimu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'dc306f83529445139c16dd55b4f0e6f3', '2023-01-17 14:39:22'),
(70, '77e7ef01-7c5f-48de-a401-11885576edf3', 'Revisi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Selesai', 'Revisi', 'coba nama penulis ditambah gelar', '2023-01-20 14:40:13', 'dc306f83529445139c16dd55b4f0e6f3', '2023-01-17 14:40:13'),
(71, '77e7ef01-7c5f-48de-a401-11885576edf3', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Revisi', 'Selesai', NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-17 14:41:59'),
(72, '77e7ef01-7c5f-48de-a401-11885576edf3', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Acc', 'Selesai', NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-17 14:47:35'),
(73, '9dd93fdc-e481-4d31-89f1-21a2617f16de', 'Update', NULL, 'aku dan kamu menjadi kita', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-17 15:00:57'),
(74, '9dd93fdc-e481-4d31-89f1-21a2617f16de', 'Update', NULL, 'aku dan kamu menjadi satu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-17 15:01:01'),
(75, '77e7ef01-7c5f-48de-a401-11885576edf3', 'Update', NULL, 'Aku lebih cepat lagi dan lagi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'dc306f83529445139c16dd55b4f0e6f3', '2023-01-17 15:03:18'),
(76, '77e7ef01-7c5f-48de-a401-11885576edf3', 'Update', NULL, 'Aku lebih mudah melupakan dirimu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'dc306f83529445139c16dd55b4f0e6f3', '2023-01-17 15:04:12'),
(77, '9dd93fdc-e481-4d31-89f1-21a2617f16de', 'Update', NULL, 'aku dan kamu tetap satu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'dc306f83529445139c16dd55b4f0e6f3', '2023-01-17 15:05:49'),
(78, '9dd93fdc-e481-4d31-89f1-21a2617f16de', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Acc', 'Proses', NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-17 15:06:46'),
(79, '9dd93fdc-e481-4d31-89f1-21a2617f16de', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-17 15:07:14'),
(80, '9dd93fdc-e481-4d31-89f1-21a2617f16de', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-17 15:07:36'),
(81, '9dd93fdc-e481-4d31-89f1-21a2617f16de', 'Approval', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Selesai', 'Acc', NULL, NULL, 'dc306f83529445139c16dd55b4f0e6f3', '2023-01-17 15:15:09'),
(82, '77e7ef01-7c5f-48de-a401-11885576edf3', 'Approval', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Selesai', 'Acc', NULL, NULL, 'dc306f83529445139c16dd55b4f0e6f3', '2023-01-17 15:17:18'),
(83, 'a5c58d55-5359-44c0-bdbb-55e26de48908', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-18 14:23:12'),
(84, 'a5c58d55-5359-44c0-bdbb-55e26de48908', 'Update', NULL, NULL, NULL, '[\"Origami asyik\",\"Yuk belajar origa,i\",\"Senang belajar origami\"]', NULL, '18 x 14,5', NULL, 123, NULL, 'Andi', NULL, '303f2de11d4f472d869757d7ea5bb83f', NULL, 'CD', NULL, 'Bsbssbbs', NULL, '2023-01-01', NULL, NULL, NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-18 14:24:39'),
(85, 'a5c58d55-5359-44c0-bdbb-55e26de48908', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-18 14:42:21'),
(86, 'a5c58d55-5359-44c0-bdbb-55e26de48908', 'Update', NULL, 'Yuk belajar origa,i', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'dc306f83529445139c16dd55b4f0e6f3', '2023-01-18 14:42:55'),
(87, 'a5c58d55-5359-44c0-bdbb-55e26de48908', 'Update', NULL, 'Senang belajar origami', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'dc306f83529445139c16dd55b4f0e6f3', '2023-01-18 14:43:02'),
(88, 'a5c58d55-5359-44c0-bdbb-55e26de48908', 'Approval', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Selesai', 'Acc', NULL, NULL, 'dc306f83529445139c16dd55b4f0e6f3', '2023-01-18 14:43:12');

-- --------------------------------------------------------

--
-- Table structure for table `deskripsi_turun_cetak`
--

CREATE TABLE `deskripsi_turun_cetak` (
  `id` char(36) NOT NULL,
  `pracetak_cover_id` char(36) DEFAULT NULL,
  `pracetak_setter_id` char(36) DEFAULT NULL,
  `tipe_order` set('1','2') DEFAULT NULL COMMENT '1= Umum,\r\n2= Rohani',
  `bulan` date DEFAULT NULL,
  `tgl_masuk` datetime DEFAULT NULL,
  `tgl_status_selesai` datetime DEFAULT NULL,
  `tgl_pil_terbit_selesai` datetime DEFAULT NULL,
  `status` enum('Pending','Proses','Selesai','Antrian') DEFAULT 'Antrian'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `deskripsi_turun_cetak`
--

INSERT INTO `deskripsi_turun_cetak` (`id`, `pracetak_cover_id`, `pracetak_setter_id`, `tipe_order`, `bulan`, `tgl_masuk`, `tgl_status_selesai`, `tgl_pil_terbit_selesai`, `status`) VALUES
('b629bea1-e1c7-4d61-b00a-7489341abc1b', '2c05015a-d003-42d9-8a90-3bb62fc1103a', '737388c6-015a-4e1b-b0a2-3c71a083a045', '1', '2023-01-01', '2023-01-18 11:32:30', '2023-01-18 11:44:20', '2023-01-18 11:44:48', 'Selesai'),
('ee5fcc10-4bb2-4f06-b9e1-c23fd377af33', 'f850c6dc-f70e-4994-990d-e3e75c598002', 'b7c5387e-d145-402a-8116-47c5ab8c414b', '1', '2023-01-01', '2023-01-06 13:41:03', '2023-01-09 14:37:51', '2023-01-09 19:56:54', 'Selesai');

-- --------------------------------------------------------

--
-- Table structure for table `deskripsi_turun_cetak_history`
--

CREATE TABLE `deskripsi_turun_cetak_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `deskripsi_turun_cetak_id` char(36) DEFAULT NULL,
  `type_history` enum('Status','Update','Pilihan Terbit') DEFAULT NULL,
  `edisi_cetak_his` varchar(100) DEFAULT NULL,
  `edisi_cetak_new` varchar(100) DEFAULT NULL,
  `format_buku_his` varchar(15) DEFAULT NULL,
  `format_buku_new` varchar(15) DEFAULT NULL,
  `tipe_order_his` set('1','2') DEFAULT NULL,
  `tipe_order_new` set('1','2') DEFAULT NULL,
  `bulan_his` date DEFAULT NULL,
  `bulan_new` date DEFAULT NULL,
  `status_his` varchar(8) DEFAULT NULL,
  `status_new` varchar(8) DEFAULT NULL,
  `tgl_pil_terbit_selesai` datetime DEFAULT NULL,
  `author_id` varchar(36) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `deskripsi_turun_cetak_history`
--

INSERT INTO `deskripsi_turun_cetak_history` (`id`, `deskripsi_turun_cetak_id`, `type_history`, `edisi_cetak_his`, `edisi_cetak_new`, `format_buku_his`, `format_buku_new`, `tipe_order_his`, `tipe_order_new`, `bulan_his`, `bulan_new`, `status_his`, `status_new`, `tgl_pil_terbit_selesai`, `author_id`, `modified_at`) VALUES
(1, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Update', '1 Edit', '1 Edit aJa', '15 x 15', '20 x 28', NULL, NULL, '2023-01-01', '2023-02-01', NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-03 15:35:46'),
(2, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Update', '1 Edit aJa', '2 Edit', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-03 15:36:41'),
(3, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Update', NULL, NULL, '20 x 28', '20 x 20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-03 15:36:59'),
(4, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, '2023-02-01', '2023-01-01', NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-03 15:37:07'),
(5, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Update', '2 Edit', '2 Series', '20 x 20', '18 x 18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-03 16:09:11'),
(6, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Update', '2 Series', '2 Series Penerbit', '18 x 18', '20 x 28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 14:21:41'),
(7, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Pending', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 15:16:04'),
(8, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Antrian', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 15:20:20'),
(9, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Selesai', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 15:20:29'),
(10, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Selesai', 'Pending', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 15:22:15'),
(11, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Proses', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 15:22:46'),
(12, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Antrian', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 15:26:13'),
(13, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Selesai', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 15:26:57'),
(14, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Update', '2 Series Penerbit', 'XXI/2 Series Penerbit', '20 x 28', '25 x 17,6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 15:41:10'),
(15, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-05 09:07:56'),
(16, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Update', 'XXI/2 Series Penerbit', 'XXI / Penerbit', '25 x 17,6', '10 x 15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-05 09:17:40'),
(17, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Selesai', 'Antrian', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-05 09:38:47'),
(18, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Pending', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-05 09:39:08'),
(19, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Proses', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 08:20:48'),
(20, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 08:48:16'),
(21, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Selesai', 'Pending', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 13:39:26'),
(22, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Proses', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 13:43:18'),
(23, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 13:44:01'),
(24, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Selesai', 'Proses', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 16:13:36'),
(25, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 16:14:54'),
(26, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Selesai', 'Proses', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-09 09:44:37'),
(27, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-09 09:47:20'),
(28, 'ee5fcc10-4bb2-4f06-b9e1-c23fd377af33', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-09 13:31:57'),
(29, 'ee5fcc10-4bb2-4f06-b9e1-c23fd377af33', 'Update', NULL, NULL, NULL, NULL, NULL, '1', NULL, '2023-01-01', NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-09 13:41:11'),
(30, 'ee5fcc10-4bb2-4f06-b9e1-c23fd377af33', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-09 14:37:51'),
(31, 'b629bea1-e1c7-4d61-b00a-7489341abc1b', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-18 11:39:37'),
(32, 'b629bea1-e1c7-4d61-b00a-7489341abc1b', 'Update', NULL, NULL, NULL, NULL, NULL, '1', NULL, '2023-01-01', NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-18 11:40:14'),
(33, 'b629bea1-e1c7-4d61-b00a-7489341abc1b', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-18 11:44:20');

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
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `divisi`
--

INSERT INTO `divisi` (`id`, `nama`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
('06b17377feea4c9abad79795c52d5a02', 'Non Dept - Non Dept', '57a9534bd79d4382bb0f43c89910702c', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-05-30 20:43:39', '2022-10-20 02:35:01', NULL),
('1f7a55f145f84a9e9fbfa218482a2668', 'AGS - AGS', 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-05-30 19:22:24', '2022-10-20 03:49:07', NULL),
('210f90ae56bf4428a061f0402f3a9ef6', 'Non Dept - GME', '57a9534bd79d4382bb0f43c89910702c', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-05-30 20:44:17', '2022-10-20 02:35:30', NULL),
('3561e1b9e15a4b9588f1c53366a5eaa8', 'Operasional - AMD', '57a9534bd79d4382bb0f43c89910702c', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-05-30 20:44:04', '2022-10-20 02:45:17', NULL),
('55e6f7e600b94280895979e608a1905e', 'GME - GME', '57a9534bd79d4382bb0f43c89910702c', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-05-30 20:44:24', '2022-10-20 02:50:34', NULL),
('646a4663aea14eb9915b718cbcc5e33b', 'Direksi', 'be8d42fa88a14406ac201974963d9c1b', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-03-31 08:33:54', NULL, '2022-10-20 03:21:44'),
('821ac200b1de45fdad7d533ce0190492', 'Keuangan - Produksi', 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-03-31 08:33:36', '2022-10-20 02:35:54', NULL),
('b2a21896e2004b46b4a489118376a855', 'AO - PUP', '57a9534bd79d4382bb0f43c89910702c', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-05-30 20:44:29', NULL, '2022-10-20 03:21:51'),
('cba340ae34984106a28fb87dbd3c4f84', 'Keuangan - PUP', '57a9534bd79d4382bb0f43c89910702c', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-05-30 20:43:55', '2022-10-20 02:41:22', NULL),
('cc0c20fa203249e6aae0bbf3d9f38ffe', 'Operasional - Penerbitan', '57a9534bd79d4382bb0f43c89910702c', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-05-30 19:59:17', '2022-10-20 02:40:14', NULL),
('d1946a0d285944488032d2dcd1a7882b', 'Operasional', 'be8d42fa88a14406ac201974963d9c1b', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-03-31 08:33:48', NULL, '2022-10-20 03:21:57'),
('d252d685b7cc469cbc7063a03a70a26c', 'Operasional - Pemasaran', '57a9534bd79d4382bb0f43c89910702c', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-05-30 21:45:32', '2022-10-20 02:40:36', NULL),
('df719b3e9de442b3ba21b1b414887ec7', 'Marketing', 'be8d42fa88a14406ac201974963d9c1b', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-03-31 08:33:18', NULL, '2022-10-20 03:22:08'),
('e20b4a5ba69a452bb8a704f5e8823b43', 'GME', '57a9534bd79d4382bb0f43c89910702c', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-05-30 20:43:44', NULL, '2022-10-20 03:21:37'),
('fda3c745c5d6410cb8eb25fb41ae4d36', 'Keuangan - Keuangan', '57a9534bd79d4382bb0f43c89910702c', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-05-30 19:59:01', '2022-10-20 02:38:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `editing_proses`
--

CREATE TABLE `editing_proses` (
  `id` char(36) NOT NULL,
  `deskripsi_final_id` char(36) DEFAULT NULL,
  `editor` longtext COMMENT 'Array',
  `tgl_masuk_editing` datetime DEFAULT NULL,
  `tgl_selesai_proses` datetime DEFAULT NULL,
  `bulan` date DEFAULT NULL,
  `tgl_mulai_edit` datetime DEFAULT NULL,
  `tgl_selesai_edit` datetime DEFAULT NULL,
  `copy_editor` longtext COMMENT 'Array',
  `tgl_mulai_copyeditor` datetime DEFAULT NULL,
  `tgl_selesai_copyeditor` datetime DEFAULT NULL,
  `turun_pracetak` datetime DEFAULT NULL,
  `catatan` text,
  `proses` set('0','1') DEFAULT '0',
  `status` enum('Antrian','Pending','Proses','Selesai') DEFAULT 'Antrian',
  `ket_pending` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `editing_proses`
--

INSERT INTO `editing_proses` (`id`, `deskripsi_final_id`, `editor`, `tgl_masuk_editing`, `tgl_selesai_proses`, `bulan`, `tgl_mulai_edit`, `tgl_selesai_edit`, `copy_editor`, `tgl_mulai_copyeditor`, `tgl_selesai_copyeditor`, `turun_pracetak`, `catatan`, `proses`, `status`, `ket_pending`) VALUES
('32f88312-2a9c-4c5c-8e67-db6305a59bb2', '330bb9ae-7378-4bf7-bf48-6eb066f2d566', '[\"303f2de11d4f472d869757d7ea5bb83f\"]', '2023-01-17 15:38:22', '2023-01-17 16:12:47', '2023-01-01', '2023-01-17 15:59:26', '2023-01-17 16:02:31', '[\"86ce9a31dae44411a568cc4b97dad7fd\"]', '2023-01-17 16:05:55', '2023-01-17 16:10:28', NULL, NULL, '0', 'Selesai', NULL),
('4b56e1f6-d7d0-4d8a-aa2a-1eabe7d12763', 'b8536fe9-d27a-457a-be6d-e5af83d7836b', '[\"303f2de11d4f472d869757d7ea5bb83f\"]', '2023-01-18 15:15:14', '2023-01-18 15:31:39', '2023-01-01', '2023-01-18 15:24:44', '2023-01-18 15:25:59', '[\"86ce9a31dae44411a568cc4b97dad7fd\"]', '2023-01-18 15:28:28', '2023-01-18 15:30:38', NULL, NULL, '0', 'Selesai', NULL),
('d85a1255-1b27-4135-8346-54c593419f2f', '44dfe332-5755-4191-8158-e79d496e1473', '[\"e829fe4fb03f45f482f77653158d461c\"]', '2022-11-02 14:42:25', '2022-11-23 10:29:33', '2022-11-01', '2022-11-21 08:56:48', '2022-11-21 08:58:54', '[\"0c5a151afe204df2bf6c38485055da16\"]', NULL, '2022-11-23 10:29:12', NULL, NULL, '0', 'Selesai', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `editing_proses_history`
--

CREATE TABLE `editing_proses_history` (
  `id` bigint(20) NOT NULL,
  `editing_proses_id` char(36) DEFAULT NULL,
  `type_history` enum('Status','Update','Progress') DEFAULT NULL,
  `editor_his` longtext,
  `editor_new` longtext,
  `copy_editor_his` longtext,
  `copy_editor_new` longtext,
  `bullet_his` text,
  `bullet_new` text,
  `jml_hal_perkiraan_his` int(11) DEFAULT NULL,
  `jml_hal_perkiraan_new` int(11) DEFAULT NULL,
  `bulan_his` date DEFAULT NULL,
  `bulan_new` date DEFAULT NULL,
  `status_his` varchar(8) DEFAULT NULL,
  `status_new` varchar(8) DEFAULT NULL,
  `catatan_his` text,
  `catatan_new` text,
  `progress` tinyint(4) DEFAULT NULL,
  `author_id` varchar(36) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `editing_proses_history`
--

INSERT INTO `editing_proses_history` (`id`, `editing_proses_id`, `type_history`, `editor_his`, `editor_new`, `copy_editor_his`, `copy_editor_new`, `bullet_his`, `bullet_new`, `jml_hal_perkiraan_his`, `jml_hal_perkiraan_new`, `bulan_his`, `bulan_new`, `status_his`, `status_new`, `catatan_his`, `catatan_new`, `progress`, `author_id`, `modified_at`) VALUES
(1, 'd85a1255-1b27-4135-8346-54c593419f2f', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-18 10:21:00'),
(2, 'd85a1255-1b27-4135-8346-54c593419f2f', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-18 10:22:31'),
(3, 'd85a1255-1b27-4135-8346-54c593419f2f', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Antrian', NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-18 10:28:06'),
(4, 'd85a1255-1b27-4135-8346-54c593419f2f', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Pending', NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-18 10:30:05'),
(5, 'd85a1255-1b27-4135-8346-54c593419f2f', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Proses', NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-18 10:30:22'),
(6, 'd85a1255-1b27-4135-8346-54c593419f2f', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Pending', NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-18 10:31:15'),
(7, 'd85a1255-1b27-4135-8346-54c593419f2f', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Antrian', NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-18 10:45:22'),
(8, 'd85a1255-1b27-4135-8346-54c593419f2f', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-21 08:47:00'),
(9, 'd85a1255-1b27-4135-8346-54c593419f2f', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-21 08:56:48'),
(10, 'd85a1255-1b27-4135-8346-54c593419f2f', 'Update', NULL, NULL, NULL, '[\"0c5a151afe204df2bf6c38485055da16\"]', NULL, NULL, NULL, NULL, '2022-11-17', '2022-11-21', NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-21 09:00:27'),
(11, 'd85a1255-1b27-4135-8346-54c593419f2f', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-21 09:00:32'),
(12, 'd85a1255-1b27-4135-8346-54c593419f2f', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-21 14:52:50'),
(13, 'd85a1255-1b27-4135-8346-54c593419f2f', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-22 16:22:00'),
(14, 'd85a1255-1b27-4135-8346-54c593419f2f', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-23 08:47:23'),
(15, 'd85a1255-1b27-4135-8346-54c593419f2f', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-23 10:27:51'),
(16, 'd85a1255-1b27-4135-8346-54c593419f2f', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-21', '2022-11-23', NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-23 10:28:47'),
(17, 'd85a1255-1b27-4135-8346-54c593419f2f', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-23 10:29:33'),
(18, 'd85a1255-1b27-4135-8346-54c593419f2f', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-23', '2022-10-01', NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-24 09:05:21'),
(19, 'd85a1255-1b27-4135-8346-54c593419f2f', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-01', '2022-11-01', NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-24 09:06:10'),
(20, 'd85a1255-1b27-4135-8346-54c593419f2f', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-08 14:07:20'),
(21, 'd85a1255-1b27-4135-8346-54c593419f2f', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-08 14:07:43'),
(22, 'd85a1255-1b27-4135-8346-54c593419f2f', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-08 14:07:47'),
(23, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-13 14:57:29'),
(24, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-13 15:48:24'),
(25, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-13 16:04:29'),
(26, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-15 10:19:49'),
(27, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-15 10:20:00'),
(28, '32f88312-2a9c-4c5c-8e67-db6305a59bb2', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', NULL, NULL, NULL, '52706514ef294f11807d246bc701fb7a', '2023-01-17 15:49:42'),
(29, '32f88312-2a9c-4c5c-8e67-db6305a59bb2', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '52706514ef294f11807d246bc701fb7a', '2023-01-17 15:58:31'),
(30, '32f88312-2a9c-4c5c-8e67-db6305a59bb2', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '52706514ef294f11807d246bc701fb7a', '2023-01-17 15:58:41'),
(31, '32f88312-2a9c-4c5c-8e67-db6305a59bb2', 'Update', '[\"8095be9de5414f7697531da885119cc3\"]', '[\"303f2de11d4f472d869757d7ea5bb83f\"]', NULL, NULL, NULL, NULL, NULL, 156, NULL, '2023-01-01', NULL, NULL, NULL, NULL, NULL, '52706514ef294f11807d246bc701fb7a', '2023-01-17 15:59:19'),
(32, '32f88312-2a9c-4c5c-8e67-db6305a59bb2', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '52706514ef294f11807d246bc701fb7a', '2023-01-17 15:59:26'),
(33, '32f88312-2a9c-4c5c-8e67-db6305a59bb2', 'Update', NULL, NULL, NULL, '[\"86ce9a31dae44411a568cc4b97dad7fd\"]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '52706514ef294f11807d246bc701fb7a', '2023-01-17 16:05:42'),
(34, '32f88312-2a9c-4c5c-8e67-db6305a59bb2', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '52706514ef294f11807d246bc701fb7a', '2023-01-17 16:05:55'),
(35, '32f88312-2a9c-4c5c-8e67-db6305a59bb2', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', NULL, NULL, NULL, '52706514ef294f11807d246bc701fb7a', '2023-01-17 16:12:47'),
(36, '4b56e1f6-d7d0-4d8a-aa2a-1eabe7d12763', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', NULL, NULL, NULL, '52706514ef294f11807d246bc701fb7a', '2023-01-18 15:19:23'),
(37, '4b56e1f6-d7d0-4d8a-aa2a-1eabe7d12763', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '52706514ef294f11807d246bc701fb7a', '2023-01-18 15:24:44'),
(38, '4b56e1f6-d7d0-4d8a-aa2a-1eabe7d12763', 'Update', NULL, NULL, NULL, '[\"86ce9a31dae44411a568cc4b97dad7fd\"]', NULL, NULL, NULL, NULL, NULL, '2023-01-01', NULL, NULL, NULL, NULL, NULL, '52706514ef294f11807d246bc701fb7a', '2023-01-18 15:28:22'),
(39, '4b56e1f6-d7d0-4d8a-aa2a-1eabe7d12763', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '52706514ef294f11807d246bc701fb7a', '2023-01-18 15:28:28'),
(40, '4b56e1f6-d7d0-4d8a-aa2a-1eabe7d12763', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', NULL, NULL, NULL, '52706514ef294f11807d246bc701fb7a', '2023-01-18 15:31:39');

-- --------------------------------------------------------

--
-- Table structure for table `editing_proses_selesai`
--

CREATE TABLE `editing_proses_selesai` (
  `id` bigint(20) NOT NULL,
  `type` enum('Editor','Copy Editor') DEFAULT NULL,
  `editing_proses_id` char(36) DEFAULT NULL,
  `users_id` varchar(36) DEFAULT NULL,
  `tgl_proses_selesai` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `editing_proses_selesai`
--

INSERT INTO `editing_proses_selesai` (`id`, `type`, `editing_proses_id`, `users_id`, `tgl_proses_selesai`) VALUES
(1, 'Editor', 'd85a1255-1b27-4135-8346-54c593419f2f', 'e829fe4fb03f45f482f77653158d461c', '2022-11-21 08:58:54'),
(4, 'Copy Editor', 'd85a1255-1b27-4135-8346-54c593419f2f', '0c5a151afe204df2bf6c38485055da16', '2022-11-23 10:29:12'),
(5, 'Editor', '32f88312-2a9c-4c5c-8e67-db6305a59bb2', '303f2de11d4f472d869757d7ea5bb83f', '2023-01-17 16:02:31'),
(6, 'Copy Editor', '32f88312-2a9c-4c5c-8e67-db6305a59bb2', '86ce9a31dae44411a568cc4b97dad7fd', '2023-01-17 16:10:28'),
(7, 'Editor', '4b56e1f6-d7d0-4d8a-aa2a-1eabe7d12763', '303f2de11d4f472d869757d7ea5bb83f', '2023-01-18 15:25:59'),
(8, 'Copy Editor', '4b56e1f6-d7d0-4d8a-aa2a-1eabe7d12763', '86ce9a31dae44411a568cc4b97dad7fd', '2023-01-18 15:30:38');

-- --------------------------------------------------------

--
-- Table structure for table `format_buku`
--

CREATE TABLE `format_buku` (
  `id` char(36) NOT NULL,
  `jenis_format` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(36) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `format_buku`
--

INSERT INTO `format_buku` (`id`, `jenis_format`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
('00b3d327-4d4e-4d3e-9354-db5937834822', '20 x 24', '2022-09-08 07:46:16', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('03b147a8-f116-493a-b89a-afa49789af6f', '18 x 14,5', '2022-09-08 08:22:21', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('043030ec-937a-4994-a961-31e44cc95455', '25 x 20', '2022-09-08 07:42:59', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('05b58d4c-2d60-4a3f-9e8a-646b6cf69fc2', '10 x 20', '2022-09-08 08:33:37', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('16741c44-9d76-49ef-ab1f-3601d1f17d26', '12 x 23', '2022-09-08 08:33:18', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('1a3c0d7b-27b4-4cf3-a1f2-6819bff36d2e', '12 x 19', '2022-09-08 08:33:28', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('1ed81d05-527a-4240-bd97-772ed1e468fd', '19 x 21', '2022-09-08 08:10:34', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('1ffd28a8-ec9c-4fa6-9b1b-b0487f4612ce', '15 x 15', '2022-09-08 08:32:23', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('20332d5a-0312-4ccd-b3c3-18ce1c6ab50e', '28 x 21', '2022-09-08 07:45:50', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('263d8e03-cdb5-4b4c-9f9a-071435856fc7', '21,5 x 29', '2022-09-08 07:43:57', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('27abbf45-c7e1-4b97-aa4c-0bce86f7d407', '21 x 29', '2022-09-08 08:21:32', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('2de34e90-e46b-4bc2-8e8d-4503724f29c9', '28 x 40', '2022-09-08 07:35:24', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('30d8b993-02f4-4fbb-b422-9db67c21fc8c', '14 x 19', '2022-09-08 08:17:41', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('33bdf031-da9d-4e55-a907-7acb89163683', '16 x 24', '2022-09-08 07:34:15', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('33f9868c-9e45-4023-b251-4f88edef9a78', '17,6 x 25', '2022-09-08 08:10:48', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('3655c222-c0a8-4205-9ae9-c7fcaa2d50b6', '19 x 25', '2022-09-08 07:22:50', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('3a96ec78-04af-4bc0-b08d-e7012cde77ef', '23 x 21', '2022-09-08 08:21:13', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('3bad1839-ea3c-45f0-b227-bec0e0fabea6', '17,5 x 24,', '2022-09-08 08:23:33', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('3bf95c4e-37eb-4d8c-89ac-36e2b5212259', '18 x 23', '2022-09-08 07:43:25', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('3c462584-e043-4de6-aaa4-646d1c8ab54f', '18 x 25', '2022-09-08 07:35:57', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('3ce0c7f3-6b88-42cf-83a8-e319db1013b6', '15,5 x 23', '2022-09-08 07:36:10', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('3d6e4b20-f8ba-498c-b2e3-2ee8d094588f', '19,5 x 27,', '2022-09-08 07:33:37', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('41b9e190-a012-4e00-aee3-3c9d79b7cfd6', '12 x 16', '2022-09-08 07:43:45', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('48167b08-5100-40b1-be4c-6e706275935e', '16,5 x 21', '2022-09-08 08:10:24', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('49d5004c-ac92-4b23-a1b8-b5a2350ee3e7', '15 x 10', '2022-09-08 08:33:01', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('4bf4cd46-0392-4349-96d2-c7780a42b224', '25 x 17,6', '2022-09-08 07:36:26', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('4cf1286e-d95a-47f9-b129-9dd66e51fd96', '21 x 14,5', '2022-09-08 07:42:21', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('4f4b732d-9e8c-41af-b3f6-5d1766dcdd41', '20 x 28', '2022-09-08 08:22:54', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('52a686fa-df29-4589-8236-40d0c07d6227', '29,7 x 21', '2022-09-08 06:59:48', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('5a3c55d7-71c2-416f-93a6-aff65bf8b29f', '15 x 21', '2022-09-08 07:33:19', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('6129cc12-9950-4f04-b7fe-24643b276ce4', '27 x 19', '2022-09-08 07:42:44', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('64741b33-a174-4683-8768-a4ef6ab3b929', '23 x 19', '2022-09-08 08:15:45', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('6c76c38e-1463-491c-9c97-9be3ac89cdfc', '19 x 23', '2022-09-08 08:23:03', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('6f16d0e3-bec1-40fc-941a-348f852129a5', '14,5 x 18', '2022-09-08 08:16:54', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('725bbe24-9d6d-4a8d-89c4-0abbdf1da669', '28 x 20', '2022-09-08 07:41:58', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('78d1eb46-4adb-4638-b7e8-458d5b1c572f', '28,5 x 21', '2022-09-08 07:33:04', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('7d694465-167b-414f-bcfd-b132402eb40f', '11 x 18', '2022-09-08 07:46:31', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('7ef6ad3e-74f2-4c17-9139-a44c1b2729b3', '13 x 20', '2022-09-08 08:17:31', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('8c2a3deb-4dcb-4ccd-87cb-07cc4f53c53a', '19 x 14', '2022-09-08 08:21:51', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('9040fc78-4b0e-413a-a114-dd76234fad9e', '21 x 29,7', '2022-09-08 08:16:22', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('92215e7a-a0c5-4219-9b98-466fa0da6895', '17 x 24', '2022-09-08 07:28:17', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('9791545a-e38f-45a6-801c-76e3a3442b5a', '26,5 x 25', '2022-09-08 07:35:12', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('9975d557-27f9-4b32-887e-a099a3d9ad6f', '40 x 40', '2022-09-08 07:44:29', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('9af8b57a-5b1f-459d-b4e0-142888b3052d', '21 x 28', '2022-09-08 08:17:05', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('9fd8c2d9-9707-43a8-a727-1d84a60433bd', '17,5 x 24', '2022-09-08 07:34:42', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('a03da286-03d1-4a48-be6e-2339f70caa3a', '18 x 18', '2022-09-08 07:42:32', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('a0ecec9e-01c6-495c-9779-adcafada9ee3', '15 x 19', '2022-09-08 07:35:45', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('a11ed3a8-8b88-4047-a543-3aa5e42e3eb3', '21 x 23', '2022-09-08 07:34:52', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('a9b7634e-4e57-471d-abb5-c0438386ec8c', '13 x 19', '2022-09-08 08:07:19', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('b1755a36-07a3-4cc7-b51b-32c7ad3da576', '21 x 27,5', '2022-09-08 08:10:09', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('b7ea7b64-0f12-4bfa-824a-c922f8b1ee64', '20,5 x 24', '2022-09-08 08:22:41', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('bc0e9dcd-0a44-4c1a-b9fd-fd763e21045f', '19 x 27,5', '2022-09-08 07:23:07', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('bf15ef4a-b35f-47a1-9338-d472046c9423', '20 x 20', '2022-09-08 07:43:11', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('c094433a-cdee-48e8-80da-4d716889d06f', '24 x 20,5', '2022-09-08 08:20:55', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('c285eb8c-ea5b-4e07-b6cb-05e845aa6c36', '9 x 12,5', '2022-09-08 07:36:40', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('c3eecf0a-0b73-4d34-820f-62eaa6156c96', '19 x 19', '2022-09-08 08:23:19', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('c88a7fbd-ef6c-458f-8140-d4ab6c2f932a', '20 x 25', '2022-09-08 07:41:24', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('c8b5aeec-a9e0-4bce-add8-365d4e3ce9ab', '29,5 x 20,', '2022-09-08 08:22:07', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('cae456da-4789-42b2-b053-445ad443ac26', '19 x 24', '2022-09-08 07:34:30', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('cea05ec2-f58e-480b-a722-c6e784d76cea', '14,8 x 21', '2022-09-08 07:46:02', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('d5a2701a-f5d4-41f8-bacf-b40e778d5936', '10 x 15', '2022-09-08 08:17:50', 'be8d42fa88a14406ac201974963d9c1b', '2022-09-23 04:21:27', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL),
('d9653aa4-e317-4605-8ad1-9c5c6e36eafc', '16 x 23', '2022-09-08 08:31:50', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('dc0f3bda-b5a6-4b7e-8a88-f2f29c210eac', '21,59 x 27', '2022-09-08 08:17:22', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('e6696c11-bf84-4c1d-8fe4-c4f6466f5ef2', '17 x 22', '2022-09-08 08:16:00', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('e6aa3405-464f-4073-8e2d-59782974fe27', '22 x 15,5', '2022-09-08 07:42:10', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('eae78443-94f8-4d9e-a78a-1db7f8daab11', '21 x 21', '2022-09-08 08:22:31', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('eb721a05-3b7e-4397-b2b4-36688ac79213', '22 x 30', '2022-09-08 07:44:46', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('f09625b6-da85-4938-bc70-110e20681c4c', '14 x 21', '2022-09-08 08:32:38', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('f252fab5-ab32-47df-851b-c8e42264aa2a', '37,5 x 28', '2022-09-08 07:44:11', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('f5c2afa4-838b-4636-b625-4440351f65b2', '15 x 23', '2022-09-08 07:45:38', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('f9c54039-828f-4398-a1ba-f592075d4035', '17,5 x 25', '2022-09-08 07:33:58', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `format_buku_history`
--

CREATE TABLE `format_buku_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `format_buku_id` char(36) DEFAULT NULL,
  `jenis_format_history` varchar(10) DEFAULT NULL,
  `jenis_format_new` varchar(10) DEFAULT NULL,
  `author_id` varchar(36) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `format_buku_history`
--

INSERT INTO `format_buku_history` (`id`, `format_buku_id`, `jenis_format_history`, `jenis_format_new`, `author_id`, `modified_at`) VALUES
(1, 'd5a2701a-f5d4-41f8-bacf-b40e778d5936', '10 x 152', '10 x 15', 'be8d42fa88a14406ac201974963d9c1b', '2022-09-23 11:21:27');

-- --------------------------------------------------------

--
-- Table structure for table `imprint`
--

CREATE TABLE `imprint` (
  `id` char(36) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(36) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `imprint`
--

INSERT INTO `imprint` (`id`, `nama`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
('02b68ae4-b27f-4dea-9339-f47061e392a0', 'Sigma', '2022-08-08 10:30:13', 'be8d42fa88a14406ac201974963d9c1b', '2022-08-08 11:13:15', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL),
('1892d47b-0634-4347-a7db-70d9f890801c', 'NAIN', '2022-08-08 10:28:06', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('24b6e227-ef21-49f2-82b8-c2b06c026f67', 'PBMR Andi', '2022-08-08 10:29:47', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Rapha', '2022-08-08 10:28:39', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('3e8fa55d-966e-429e-8ebc-06e06de1fad4', 'Rumah Baca', '2022-08-08 10:29:22', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('740e1b92-f1c9-42f7-91d5-4bf5c995c0ca', 'NyoNyo', '2022-08-08 10:29:29', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('75c137a9-aee7-4a3c-b95b-0e6582378a72', 'Mou Perorangan', '2022-08-08 10:29:38', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('75c520ce-b74b-41bc-9919-f88c63f68e7d', 'Rainbow', '2022-08-08 10:28:46', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('8b102fb5-20d1-4a5c-89db-3a39dd01fd58', 'Andi', '2022-08-08 09:42:39', 'be8d42fa88a14406ac201974963d9c1b', '2022-09-22 15:51:29', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL),
('9080c9f3-53f4-4421-9f95-3907df3aec73', 'Sheila', '2022-08-08 10:29:04', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('a342fe02-3efb-45b7-bd7a-e4b9ca18be16', 'Pustaka Referensi', '2022-08-08 10:30:22', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('b0133193-38ae-41a3-8ae1-6c5c33dfd453', 'Lily Publisher', '2022-08-08 10:30:05', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('b3439dc0-dd5f-4902-888d-0bc831b67120', 'Nigtoon Cookery', '2022-08-08 10:28:21', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('b795d9b0-fbc9-4610-a886-b41590eb98c2', 'Cahaya Harapan', '2022-08-08 10:30:31', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('c4cf90ee-75d4-4bee-b83c-4e511a5297ad', 'YesCom', '2022-08-08 10:28:29', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('d78bebcd-5570-4dcf-9bd7-e9c99c72e072', 'MOU Pro Literasi', '2022-08-08 10:29:12', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('e0d379e7-2a95-4de8-8050-1e0e5bf728bd', 'G-Media', '2022-08-08 10:27:49', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('e82f2e7a-4492-4cc9-b6c9-b34ee1ce88ca', 'Lautan Pustaka', '2022-08-08 10:28:56', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('eb784613-9a0c-4fc3-b739-0abc12346917', 'Garam Media', '2022-08-08 10:29:54', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `imprint_history`
--

CREATE TABLE `imprint_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `imprint_id` char(36) DEFAULT NULL,
  `imprint_history` varchar(100) DEFAULT NULL,
  `imprint_new` varchar(100) DEFAULT NULL,
  `author_id` varchar(36) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `imprint_history`
--

INSERT INTO `imprint_history` (`id`, `imprint_id`, `imprint_history`, `imprint_new`, `author_id`, `modified_at`) VALUES
(1, '8b102fb5-20d1-4a5c-89db-3a39dd01fd58', 'Andi', 'Andiiiiiiiiiiiiiiiiii', 'be8d42fa88a14406ac201974963d9c1b', '2022-09-22 20:44:43'),
(2, '8b102fb5-20d1-4a5c-89db-3a39dd01fd58', 'Andiiiiiiiiiiiiiiiiii', 'Andi', 'be8d42fa88a14406ac201974963d9c1b', '2022-09-22 22:01:01'),
(3, '8b102fb5-20d1-4a5c-89db-3a39dd01fd58', 'Andi', 'Andi Offset', 'be8d42fa88a14406ac201974963d9c1b', '2022-09-22 22:50:56'),
(4, '8b102fb5-20d1-4a5c-89db-3a39dd01fd58', 'Andi Offset', 'Andi', 'be8d42fa88a14406ac201974963d9c1b', '2022-09-22 22:51:29');

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
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jabatan`
--

INSERT INTO `jabatan` (`id`, `nama`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
('01724d318cd14a278b84d82341072792', '5 Finishing Non Buku (Koordinator)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:10:45', NULL, NULL),
('01f2dc83fd8845d0ba6d95c585a4d64a', 'Gudang Material & Pembelian', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:20:13', NULL, NULL),
('039402fbcd0f471799cdf286b731cd36', 'DS Batam (Tanjungpinang)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:17:09', NULL, NULL),
('04ed4cfa8aef4957bcc68682e300b74c', 'Support System', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:39:52', NULL, NULL),
('0507db5a99ec449593f3d91031973ab6', 'Administrasi PBU (SPP, Surat Menyurat, Umum)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:13:10', NULL, NULL),
('0541d9b957ec401fac47fac5e8c4f8d8', 'Teknisi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:39:58', NULL, NULL),
('05d19a5575e84cd0a2d5ea7c549675cb', 'Tehnisi', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 06:23:17', NULL, NULL),
('065917299b214f849faa94cf7e01e490', 'PIC Web Andipublisher dan reseller', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 06:27:10', NULL, NULL),
('06a9d8c148eb4ccdb1cbe115d7474b69', 'Operator Mesin Potong Tiga Sisi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:29:06', NULL, NULL),
('07e058a9224645df94a5e5c7894c9ed1', 'Management Trainee (MT)', '57a9534bd79d4382bb0f43c89910702c', '57a9534bd79d4382bb0f43c89910702c', NULL, '2022-05-30 20:00:30', '2022-05-30 20:00:42', NULL),
('0a860fc1417f4b0b89d020ac17cfd68c', 'Prodev E-Book', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:17:00', NULL, NULL),
('0ab4de9a915447a28ee514318fd8e288', 'Marketing Support', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 06:29:17', NULL, NULL),
('0e8709c7e3e4492cbc95087af5349f60', '2 Manager QC & Kalkulasi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:00:19', NULL, NULL),
('115a55d9446a4625b8f81ef6a0620cae', 'Arsip Plate', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:14:42', NULL, NULL),
('12272919184845e5b34b26045033709f', 'Operator Mesin Potong Itotech', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:28:40', NULL, NULL),
('1228e971102549dfabd69def30fa09e8', 'Marketplace Support', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:25:22', NULL, NULL),
('1362615d867c4109874147e8b59eefc6', 'Direct Selling Sekolah', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:15:52', NULL, NULL),
('1625a7ddee6b4328b95351448bb7e123', 'Pjs Kepala Cabang', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:11:17', NULL, NULL),
('1716fd2679b04f53bc3008fbcb265bb7', 'Administrasi Retur Fisik', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:13:55', NULL, NULL),
('189da055b78e4f86911970cab8d16323', 'IT Support', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:20:52', NULL, NULL),
('191e859f0223457f941153a04ee131d9', 'Operator Mesin Speed Master 4 Warna (Pak Yusuf)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:30:06', NULL, NULL),
('19f1242197284ffe854ea80e9ea141da', '5 Ekspedisi & Packing (Koordinator)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:10:28', NULL, NULL),
('1aa8e622fa4b472a98b6c5f782538078', 'IT dan Programmer', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:20:46', NULL, NULL),
('1bdb449355f94921bd160fed0695121d', 'Finishing Non Buku', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:19:40', NULL, NULL),
('1c2de5723b7246f0873280b8e6a84cab', 'Wakabag Buku SMK', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:23:48', NULL, NULL),
('1cd910eee9054a7d8d820ab23944a4cd', '2 Manager Area 1', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:48:04', NULL, NULL),
('1ddca5f391d44e24be22c5e8d755aa34', '2 Manager Area 3', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:48:10', NULL, NULL),
('2005df513d5b4a02a8305ee3032ccefc', 'P&RD MoU Rohani', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:30:38', NULL, NULL),
('21a8760a6b0b4526924b68df47c1faf6', '1 Direktur Marketing Proyek', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 03:26:29', NULL, NULL),
('21a9e94e7c4b49698bd3344b17b21106', 'Customer Service Online', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:18:27', NULL, NULL),
('21cebb63b70d4e05ad33dbc878465d33', 'Multimedia', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:25:27', NULL, NULL),
('2208ed773793471fabdb73033c4e494e', 'DS Umum dan Rohani', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:18:33', NULL, NULL),
('2236e590d3f04506b404e44ee0477add', 'Operator UV Besar', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:30:28', NULL, NULL),
('22c4de4765c24371aa8d859336a8bb8b', '2 Manager Accounting', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:47:53', NULL, NULL),
('2380019d08d3497689fef9d1b921043c', 'Administrasi Stock', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 06:36:03', NULL, NULL),
('2393523579bd419780a264904fd94bbb', 'Setter Produksi', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 03:55:28', NULL, NULL),
('2431ebed977546aa858c66e468fa972f', 'Direct Selling Perguruan Tinggi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:15:30', NULL, NULL),
('252a3eaac6884323b438e0e8c1f9ecf6', '2 Manager Produksi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:59:48', NULL, NULL),
('2537c25521aa47abaa892157971af4d8', 'Administrasi Keuangan', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:12:32', NULL, NULL),
('2796e655e96540a7ab049141b189c74f', 'Operator Mesin Potong Polar 1993', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:28:59', NULL, NULL),
('279ad59cff324604b0f6894cc7332e9a', 'Packing', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:30:45', NULL, NULL),
('284c727748fa46f1bda555ecd6f3fa5a', 'Pra Cetak Non Penerbitan Intern', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:37:45', NULL, NULL),
('29ea73245e344580aab2504d3299f619', 'Freelance Gudang', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:20:25', NULL, NULL),
('2a8eed1cbdaf468895b8d18247a9be2e', 'DS Tangerang', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:18:18', NULL, NULL),
('2b310af11c2f4f78af23f0760ffefa48', 'Operator Mesin Wrapping Barat', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:29:15', NULL, NULL),
('2cf2869e4fa1485e9f3bfa75ad2781b7', 'Gudang & Ekspedisi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:19:53', NULL, NULL),
('2eb85e97e53a486f8aca2b08d3c3b7d4', 'Input Data Online', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:20:38', NULL, NULL),
('300c4db6ea724f4c80e6a64497304b77', 'Manager Buku Perti', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:45:00', NULL, NULL),
('312d6a9154964f70a8295ea3717bc969', 'Design & Setter', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:14:55', NULL, NULL),
('3189d4fa029542e090632ae162f7cf3e', '4 SMK (Kabag)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:07:16', NULL, NULL),
('32709ca06e344792a5d6193064b09c70', 'Finishing Jahit', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:15:33', NULL, NULL),
('356e453597c54e8baf51bc2bc1bdd463', 'Marketing Toko Buku', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:25:00', NULL, NULL),
('36cd1d6e1d6a4b9089e302fb86b8b7b2', 'Operator Hand Press', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:27:46', NULL, NULL),
('36d56e740951461dab5f482bcd55141f', '5 Gudang (Koordinator)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:10:54', NULL, NULL),
('37090718ead447b5bf16299f511b9cc5', 'GM Penerbitan Buku Rohani', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:21:59', NULL, NULL),
('3733f072fba04a63af25ae07de5e58ff', 'Administrasi Piutang', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:13:41', NULL, NULL),
('387ef8138f174e4f9fb2f2d147f03a71', '1 Direktur Operasional', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:46:07', NULL, NULL),
('38d8e64a060f4d7980c2ee01fc69a8ef', '2 Manager Toko Buku dan Jasa Cetak', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:03:56', NULL, NULL),
('39a97228e61647dea9a926fda5ef2976', 'Operator Binding PBM Utara 1', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:25:42', NULL, NULL),
('3c8b59472d4b4d26bad0de7427a5b225', '4 Editor (Kabag)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:05:53', NULL, NULL),
('3c9eabebaa7440f2846e3a1a7ab6e7e0', 'Pemasaran Web Andipublisher', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 06:33:54', NULL, NULL),
('3caafec399d341489b6192370051d9cc', 'DS Timor Tengah Utara', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:18:25', NULL, NULL),
('3cada79f539f46b9a76334b49d12aebf', 'Marketing AGS', '57a9534bd79d4382bb0f43c89910702c', '57a9534bd79d4382bb0f43c89910702c', NULL, '2022-05-30 21:22:19', '2022-05-30 21:23:40', NULL),
('3daddad55a76400f8cc7f88201c97dd7', '5 Redaktur Pelaksana Bahana Media', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 04:09:06', NULL, NULL),
('3dc346ce207145bb9d314d88bd41d8e6', 'Operator Binding Yoshino 1', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:26:15', NULL, NULL),
('3e1e9a528cef4076a07c6bcc9b30b158', '5 Direct Selling (Supervisor)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:10:12', NULL, NULL),
('3e99fc9ae10b4530bd41a62122cb1d27', 'Marketing Percetakan & FO', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:24:27', NULL, NULL),
('3ee9942c26d04ed98985806e884ccac8', '4 Administrasi (Kabag)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:05:22', NULL, NULL),
('3f5b787583654ccdbec74d848d25098c', 'Marketing PINKA (ANDI-Group)', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:13:03', NULL, NULL),
('3ff5efeaca3e4e919b73d47eb9716fbc', 'Operator POD', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 06:35:12', NULL, NULL),
('40200f9b3ea74177ae7715fe9eb2c2f7', '3 Wakil Manager Digital Marketing & Purchasing', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 03:52:13', NULL, NULL),
('40f405c75c904233876aa6b07b6140cb', 'Marketing Indo Pustaka', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:23:54', NULL, NULL),
('413e3c0d577242d3898a011527fdd4b9', '4 Finishing, Bending, Potong, UV + dll, Wrapping (Kabag)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:06:14', NULL, NULL),
('416daa7cd55e42da9b99493ed0c6d18c', 'PIC Cabang', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 19:59:38', NULL, NULL),
('41ed58c1ff634eec81875479ad3eb5e8', 'Operator Mesin Speed Master 52', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:30:12', NULL, NULL),
('426036aaba6d4ebcb7fac38c49e3e626', 'Editor SMK', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:19:01', NULL, NULL),
('43e6121f78dc4ddbbc98011d9eb662a7', '4 MoU (Kabag)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:06:34', NULL, NULL),
('443bf19a191940d095912336c424ddbf', '5 Toko Buku (Supervisor)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:11:43', NULL, NULL),
('45dc34ca1fd044bea7cfba10ec57412f', '4 Personalia & Umum (Kabag)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:06:43', NULL, NULL),
('47e5085e00574169996bf9670627c5e9', 'Marketing PINKA (ANDI - Group)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:24:42', NULL, NULL),
('48456a0c703549eb9549196fc3d58dde', 'Operator Speed Master 52', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 06:17:58', NULL, NULL),
('49df29ed0f5b4f5084536837d086aca2', 'Administrasi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:12:18', NULL, NULL),
('4a3f27b6b37049fd838938428521bb44', 'Administrasi Penjualan & Keuangan', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:13:34', NULL, NULL),
('4cebe6a298b74c11965343d3adffc40d', 'Sekretaris Direktur Operasional', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:49:44', NULL, NULL),
('4d6769b7770940e9b09871285af2de47', 'Andi Media Digital', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:14:25', NULL, NULL),
('4d8836c10ca0448eaca91978fc8f6f1a', '5 Toko Buku Nasional (Supervisor)', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:10:15', NULL, NULL),
('4e288ea7db8e4db3879a897293297b08', 'Freelance Admin Keuangan', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:20:32', NULL, NULL),
('4ef7f43c6cd042e89c359486145e4e60', '2 Manager Penjualan & Packing', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:49:46', NULL, NULL),
('4f4c2d915a0f412a98e84be4b9abc3b2', '3 Wakil Manager Stock', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:05:13', NULL, NULL),
('4f959e5509e84f4cb27fef7cb4fea9a0', 'Sekretaris Direktur Utama & GM Penerbitan Buku Rohani', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 06:34:47', NULL, NULL),
('516acf73601146d9bf0d19307be28b70', 'Setter SMK', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:39:19', NULL, NULL),
('516b29cc941f4675af4f25e703a60bfb', 'Operator Doff, UV Kecil', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:26:41', NULL, NULL),
('536a5178a41240cf9f545cb865e0ac2c', '2 Manager Area 2 & Manager Marketing Proyek', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 06:21:55', NULL, NULL),
('542c06e42b784cafbae8a1666bae508a', 'Manager Buku Rohani', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:45:11', NULL, NULL),
('55729c2c9a6145daa07654ec82736616', 'Hubungan LN', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:23:58', NULL, NULL),
('56540314fdff4480b481e58e86b97b83', 'DS Manggarai - Flores', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:17:42', NULL, NULL),
('5807a46c6d694c87ad2d0eed18777ce3', 'Operator Spot, UV', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:30:20', NULL, NULL),
('582442d607f649b9b83e188a875deb25', 'E-Book Creator', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:16:48', NULL, NULL),
('58fc78b637424cb894a238014974a6d8', 'Staff', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-05-30 19:22:45', NULL, NULL),
('59285f1bc8fe431186c97efab5699cc8', 'Operator Speed Master 4 Warna', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 04:12:28', NULL, NULL),
('5c8a5582335842f59cfadffd32c9058a', 'Direct Selling', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:15:20', NULL, NULL),
('5cde3d235c7c446e9f1f08a4c05eec1e', 'Design', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:14:49', NULL, NULL),
('5d0185e09379474483867aa233e63075', 'Direct Selling SMK & Perguruan Tinggi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:16:06', NULL, NULL),
('5e33e6ce57624fa99fa4458ab4a63e0b', '1 Staff Ahli Direktur Utama', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:46:27', NULL, NULL),
('60f35622aa77414b91ea82869688e5c7', 'Marketing Percetakan', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:24:17', NULL, NULL),
('61708bd46d2c43ee8893236f73ef8068', '2 Manager Digital Marketing & Audit Cabang', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 04:58:35', NULL, NULL),
('61dbe8c3b3f145779d24ce736e52a306', '4 Design (Kabag)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:05:32', NULL, NULL),
('620feb2d0dea419f83964e844c7b2003', 'Manager Penerbitan Buku Umum', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:44:31', NULL, NULL),
('629cb23bb1d3405a8132e84f584d82fd', 'Operator Mesin Wrapping Timur', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:29:32', NULL, NULL),
('62a591ac2fef4bb5b081074f78a5669e', 'Non Jabatan', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:39:26', NULL, NULL),
('6330dbf209214e3dbe6b668cb0a68fe3', 'Ekspedisi & Packing', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:19:11', NULL, NULL),
('64551d6ddec04fbd8afb6cfd88e0b0da', 'DS Klaten dan Gunung Kidul', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:17:22', NULL, NULL),
('64e2cf0711af4c378da7ae291d233a41', 'Ruang Aksara', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:17:55', NULL, NULL),
('658d62cb439a4ba6ad2ea4efd7ce4753', 'Ekspedisi Order Cetak', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:19:21', NULL, NULL),
('67d21c0bfa4641f3978ee9534c6bd559', 'Operator Mesin Potong Perfecta/DQ', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:28:52', NULL, NULL),
('6896c969a25a4d56aa92f9a1ba1ead59', 'Operator Speed Master 4 Warna (pak Yusuf)', 'be8d42fa88a14406ac201974963d9c1b', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-20 04:12:39', NULL, '2022-10-20 04:19:01'),
('68ede942ee1a48aa877ea0095334f078', 'Manajer Buku Teks', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:22:13', NULL, NULL),
('6abdb2fe57e9454b8a01b86068ca0d1a', 'Operator Binding PBM Utara 3', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:25:52', NULL, NULL),
('6ad60313ea184223a6c5a63d73be4b34', 'Direct Selling Rohani & Perguruan Tinggi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:15:44', NULL, NULL),
('6ba3a426f8c749239a7d0f9779f85c67', 'Operator Binding PBM Utara 2', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:25:45', NULL, NULL),
('6c0a53168f6943f183c55631c54cdbb2', 'Administrasi Eks Konsinyasi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:12:26', NULL, NULL),
('6c244da58f0c47a083fe5c0444d6b463', 'Direct Selling Tegal', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:16:20', NULL, NULL),
('6f70771b3afa49b790b5b61802a73440', 'Gudang Retur', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:20:19', NULL, NULL),
('717e42750a4248e08a296588d53dc311', 'Administrasi Stock & Penjualan', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 06:31:57', NULL, NULL),
('71b2e233ed99473e82bac2a1470e973b', '1 General Manager Non Sekolah', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 03:50:18', NULL, NULL),
('7261c5386e554397859da16578262701', '3 Wakil Manager Online', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:05:05', NULL, NULL),
('72b983f419294a6180bab256f90e36f4', 'Administrasi Stock & RO', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:14:08', NULL, NULL),
('73abf833a89344d18df22604b2f1be44', 'Operator Binding Yoshino', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:26:13', NULL, NULL),
('7407ecd4181d4f429d173fbefd7ac938', 'Admin Penjualan', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:12:03', NULL, NULL),
('7425f685d6694dd1a99ccaa373912dba', '2 Pjs. Manager Marketing Gereja, STT, & PnRD', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 06:16:57', NULL, NULL),
('743bc752495d4140b91e527073fc5053', 'Kalkulasi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:20:58', NULL, NULL),
('78b1006996a14776927f0f98c29f4f24', 'Sales Toko Buku & Direct Selling Rohani', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:38:37', NULL, NULL),
('7a9181a87fc34b4083e6e9e44594a1ac', 'Developer Web', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:15:13', NULL, NULL),
('7b5abb88239149dc9007aafe4283ef1d', 'Direct Selling Rohani', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:15:35', NULL, NULL),
('7d4184188a694c55bd2e6c945182c249', 'Manager Penjualan', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 19:59:52', NULL, NULL),
('7e5b7c634df34aeda9b2c4ad04c6539b', '1 Dewan Direksi & Pjs. WaDir. Keuangan', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:45:39', NULL, NULL),
('7e5ff2339b304e369f74463fe510556c', 'Operator Mesin POD', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:29:36', NULL, NULL),
('808b2471e7a844fbb090cc7d1b2af38a', 'Operator Mesin Lipat', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:28:22', NULL, NULL),
('82e2acd72a174c93a24894bf38f36f99', '2 Manager PPIC', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:49:51', NULL, NULL),
('84ad4ac3fbad42d39f040d8603d9c0be', 'Sales Toko Buku', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:38:29', NULL, NULL),
('85691d994eae40cba06da14ee5cccaa9', 'SPV Direct Selling', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:39:34', NULL, NULL),
('8632dd0e617b465196d6e1a710eba469', 'Gudang Material', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:20:05', NULL, NULL),
('86bdfb7e323148b89c0316caea05b113', 'Manager Area 2', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:44:52', NULL, NULL),
('870d9a1d75f84f25b4be36fa48148c67', 'Gudang Marketing Online', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:19:17', NULL, NULL),
('87e16ff18dce411c9c54122ac1275e56', 'Ilustrator', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:20:29', NULL, NULL),
('87fcdb42bca942b49d0564640b325db7', 'IT dan Programer', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 06:18:46', NULL, NULL),
('8837206b558a41bd898310ecb082ab94', 'Operator GTO 1 Warna', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:26:52', NULL, NULL),
('886b050b5cdd4d00bb7f990efbaf6d57', '1 Dewan Direksi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:45:26', NULL, NULL),
('88fd1779b18045ab8f1742f6eb6e30f6', 'Design Produksi', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 03:55:52', NULL, NULL),
('8941d6db9f334699896a802578ac391c', 'DS Minahasa Utara', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:17:50', NULL, NULL),
('8c169ffc6aa6417d85dd8599a122409f', 'Marketing Support (Design)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:24:52', NULL, NULL),
('8d1cece86f1c42d9994ebca7bada307e', 'Operator Binding PBM Utara', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:25:39', NULL, NULL),
('8f11d5f27224472b8632c14136cf02f5', 'Design Graphic', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:15:06', NULL, NULL),
('901a26392eab4dc29cbfe1d14f004555', 'Driver', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:16:54', NULL, NULL),
('9080d370743b4877b5c15448a04f827c', 'PIC Web Andipublisher dan Sosmed Seller (IG,GB)', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 06:31:11', NULL, NULL),
('914c0eb16d4745098d1658b22642110c', 'Pjs. Koordinator Marketplace', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:15:22', NULL, NULL),
('93f0483915f2417faf381af531ab21c6', 'Royalti', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:38:21', NULL, NULL),
('954d10a2ca384d25bcdcc93e43c135b9', 'Koordinator Bahana Digital', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 06:28:08', NULL, NULL),
('987d4c4e12a74897b3478f23e757d732', '2 Manager AGS', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:47:57', NULL, NULL),
('98816b7f224f47b189571533676434fd', 'Petugas Kebersihan & Umum', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:31:08', NULL, NULL),
('99ff5b9cc25b497fa1bc8314efa4ba20', 'Administrasi Penerbitan', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:13:18', NULL, NULL),
('9a0bb34735424842ba98c5e0f1c11b95', '4 Pracetak (Kabag)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:06:52', NULL, NULL),
('9aa871b4bb144846b3f09aef2c7f9152', 'Administrasi Toko Buku Modern', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:14:17', NULL, NULL),
('9b1cb0f370a244f59ffc0f7cff2014ea', 'Marketing Online', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:24:09', NULL, NULL),
('9d10b04dcb9d40f0a911f7cd07619f15', 'Staff Keuangan', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:39:43', NULL, NULL),
('9df386e256fb40e8a0fdc06a2d6950df', 'Admin Retur', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:12:08', NULL, NULL),
('a3ec7c93f78e45408e0d2b3bed224846', 'Kalkulasi, Administrasi Pengiriman Order Cetak', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:21:13', NULL, NULL),
('a57da1e8017b446da73d04d5e0d4dfb3', 'Setter MoU', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:39:14', NULL, NULL),
('a7576a201a6f408080c984ebd4c6965c', 'Finishing', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:19:27', NULL, NULL),
('a7d5039ddcbc4a42877af85d53231c47', 'Pesanan Marketplace', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 06:33:41', NULL, NULL),
('a8d16dcfad8e4c4cbfb34e7ffc1170b3', 'Accounting', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:11:50', NULL, NULL),
('a9b2128974ac4e8d9a0e67f7a9627fcc', '3 Wakil Manager Accounting', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:04:57', NULL, NULL),
('ab629134176544a792b67c36a20a094a', 'Sekretaris Direktur Keuangan', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 04:55:37', NULL, NULL),
('abbdc0df305a480bb6a517daf00f2f8d', 'Operator Binding Yoshino 2', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:26:19', NULL, NULL),
('ac8bc6e3b1bf44fcbc82644df8ee0019', '1 Wakil Direktur Operasional', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:46:36', NULL, NULL),
('adda951f7d1e4fc998cf4f968b93e223', 'Manajer Digital Media', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:22:39', NULL, NULL),
('ae6cd3605dc84c47b8b0bf1052a8401f', 'GM Penerbitan Buku Umum', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:21:46', NULL, NULL),
('b10bba4235964fa98af7052f7d4b4c36', 'Programmer', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:38:02', NULL, NULL),
('b14c6581b9524832a107ae12a0518ba3', '2 Staf Ahli Penerbitan Umum, Khusus Buku Teks & MoU', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:04:31', NULL, NULL),
('b153d77dac2b41608a8d1f1e17f4b2ff', 'Manager Penerbitan Buku Rohani', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:44:37', NULL, NULL),
('b19fd51ea3544d05b712e1391ba78a38', 'Direct Selling Umum', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:16:26', NULL, NULL),
('b23798a5c7ea4ae79305e7744b1c4810', 'Junior Product Executive', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:15:46', NULL, NULL),
('b2639536cbb440059ec7a1362d4de099', 'Direct Selling SMK', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:15:57', NULL, NULL),
('b317ce682c1845839a108fbb0eb4aec4', 'Redaktur Renungan', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:38:15', NULL, NULL),
('b5a00780ec4241b0823f05aec55aeb14', 'Personalia', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:30:54', NULL, NULL),
('b7551574bd6f485b9432dc1ec9f6df6b', 'Bahana Digital', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:17:11', NULL, NULL),
('b779d1a867384744802b88862bd5038c', 'Marketing Koordinator', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:23:59', NULL, NULL),
('b89115b2224448988f51de865d16f97f', 'Royalty', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 06:25:13', NULL, NULL),
('ba69cc34c8b64922a5dbd22ad4266736', 'Operator Mesin Speed Master 2 Warna (Pak Hadi)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:29:55', NULL, NULL),
('bb3306272bbe4b1a96f468a67d66ce93', 'Keuangan - Pembelian', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:21:25', NULL, NULL),
('bb56cd9fab484243a615a544310592c1', 'APPS Developer', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:14:33', NULL, NULL),
('bb909b2d2d1941b1b0dc8564e66eea7e', '1 Direktur Keuangan', '57a9534bd79d4382bb0f43c89910702c', '57a9534bd79d4382bb0f43c89910702c', NULL, '2022-05-30 20:45:46', '2022-05-30 20:46:57', NULL),
('bbeb1474deeb43559b4f2e542bb3a197', 'Administrasi Penjualan', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:13:25', NULL, NULL),
('bca9772f3ea94c638a91e866f726a638', 'Operator', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:25:32', NULL, NULL),
('bdcd69c1643b4cf8b7f399ce6ca7d258', 'Administrasi Retur', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:13:47', NULL, NULL),
('be7c01ac0b3a486e98a2cb4de8c3a2bc', 'Operator Mesin Speed Master 4 Warna (Pak Hadi)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:30:01', NULL, NULL),
('c049dc9aba9444e9bef14a5c05f04a59', 'Korektor', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:21:53', NULL, NULL),
('c0d1b2987056470bb3ffc8f499c40b85', 'Sekretaris Direktur Utama', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:39:04', NULL, NULL),
('c20172916f5645a7ab38ca45523ef2c9', '2 Manajer Digital Media', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 04:08:45', NULL, NULL),
('c2b7e802f16c44da8e84321de0a04230', 'Redaktur Bahana', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:38:10', NULL, NULL),
('c4155b0b5cd64e9a99f8f03f0c9c5420', '4 Digital Media Support (Kabag)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:05:44', NULL, NULL),
('c45439db1cbb416ba08f5b8e89cbaf1d', 'Sekretaris Departemen Operasional', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 06:40:15', NULL, NULL),
('c49a6385fec2465083e17538013356f6', 'Tukang', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:40:03', NULL, NULL),
('c502a4d7ef92459399ef2b4a44dc44ba', 'E-Bahana', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:18:37', NULL, NULL),
('c53fd5b4a80042d7a04a1d3e24c620e7', 'Finishing Buku', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:19:34', NULL, NULL),
('c59878bff90d4b1094bacdef733f5cd3', 'DS Lampung Tengah', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:17:33', NULL, NULL),
('c65e04908f3b4c1984575bf98361c7ae', 'Manajer Buku Rohani', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:22:24', NULL, NULL),
('c6ca472a865c4fa18101722b5eced14e', 'Koordinator Teknisi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:21:46', NULL, NULL),
('c79fcedd8827429e9c1add40b2dbe71c', '2 Pjs. Manager Marketing Perguruan Tinggi', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 06:34:03', NULL, NULL),
('c8c73c2514154252ba8227eca9b17cc3', '2 Wakil Direktur Penerbitan', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:04:44', NULL, NULL),
('c95e85716f8b4bf8834875d21933e15c', 'Administrasi PBU (ISBN, Duplikasi CD & Ekspedisi)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:12:55', NULL, NULL),
('c99bbf7a76774fccab8436ae39ae8a09', 'Marketplace', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:17:37', NULL, NULL),
('c9f0106ff972412393227db316d03cb9', 'Prodev', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:37:57', NULL, NULL),
('cb12c05907784373b8fee1128f6a340f', 'Korektor MoU', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:21:57', NULL, NULL),
('cc752eb16ae54eb683f339cb7f55dc93', 'Marketing GME', '57a9534bd79d4382bb0f43c89910702c', '57a9534bd79d4382bb0f43c89910702c', NULL, '2022-05-30 21:22:24', '2022-05-30 21:23:24', NULL),
('ccee3a3ada804ccaa0e9019eb2d010ef', '2 Pjs. Man. HRD', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:04:10', NULL, NULL),
('ce6d7e7b7c004ebaad0da6cc60190ae5', 'Operator CTCP', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:26:32', NULL, NULL),
('cf00069665b04b2a88fed7b335df46c7', 'Purchasing', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:17:27', NULL, NULL),
('cf882df375624a4bbe809e82ef196f57', 'DS Sumba Timur', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:18:13', NULL, NULL),
('d01e5595fec0490f8dad8fec158fb3f3', 'Pjs. Supervisor Marketing Yogyakarta', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 04:04:28', NULL, NULL),
('d0baf26c251547bb9ae7ee6c8f07071d', '4 Kepala Cabang', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:06:23', NULL, NULL),
('d11c1f0d64034db09972dbf57aa694d5', '2 Manager Area 4', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:48:14', NULL, NULL),
('d275524d48be45e4aae3530770b66587', '2 Manager Stock & Retur', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:00:33', NULL, NULL),
('d3c46b59f3f0474785467019f899a960', 'Keuangan & Sekretaris Pemilik', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:21:33', NULL, NULL),
('d4106a9b51a947a3ae9416218ff7ac55', '2 General Manajer Penerbitan Buku Umum', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 06:32:54', NULL, NULL),
('d50fae8ec7a0469e8d185df3b277aa6b', '4 Kepala Bagian Personalia', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 06:56:46', NULL, NULL),
('d62813561e394099b57104c980c208b4', 'Pjs. Koordinator Customer Service', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 03:52:49', NULL, NULL),
('d6ef995511b443ad8a9f14943cdbff95', 'Operator Mesin Kecil', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:28:18', NULL, NULL),
('d6fa8bb106b646aa82f39351dcdae39e', 'DS Rantau Prapat', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:17:56', NULL, NULL),
('d7910e457bbd4478b0a67a3d4bc5bfe3', 'Administrasi Siplah', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:14:01', NULL, NULL),
('d82ca3c239504b8e8529679a267736e8', 'Editor MOU', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:18:48', NULL, NULL),
('d857d784948e43718f44429293067856', '2 Manajer Penerbitan Khusus Buku Teks & MoU', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 04:08:02', NULL, NULL),
('dbabe9ec1ef6476c842add38f838c56f', 'Operator Hand Press, Voil, Ponds & Emboss', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:28:10', NULL, NULL),
('dc6f2db38b244df7813cd4bc79f3e9dc', '5 Operator Hand Press, Voil, Ponds & Emboss (Koordinator)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:11:15', NULL, NULL),
('dc8f27ac87ef4feb89bbb6eb13887c4e', 'Korektor SMK', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:22:01', NULL, NULL),
('ddec4d6eaaef44709574c75667e67e27', 'Digital Marketing E-Book', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:19:25', NULL, NULL),
('de6a811cebee4145b0a7bdb223c29c0e', 'Setter', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:39:09', NULL, NULL),
('dedaa2fb2ff14c7a8aac534213902d3b', 'Operator Mesin Speed Master 2 Warna', '57a9534bd79d4382bb0f43c89910702c', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-05-30 21:29:45', NULL, '2022-10-20 04:17:58'),
('e27bf0342e684a709462be878eac71ba', 'Manajemen Trainee', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:14:43', NULL, NULL),
('e4d0149f28ba4b528368ba54cb1bffe6', 'Gudang', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:19:46', NULL, NULL),
('e8b9d8b996084cbbb25af6979afbf4ff', 'Tukang Masak', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:40:08', NULL, NULL),
('e9a70c1e4c85490585611078529c72ef', 'Direct Selling Umum & Rohani', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:16:49', NULL, NULL),
('ea2bf1ca8d64454aa37d43cb8c46c242', 'DS Sampit (Kotawaringin Timur)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:18:07', NULL, NULL),
('eadfd81c0d2547e68adf4b310e6fb45c', 'Satpam', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:38:52', NULL, NULL),
('eafe82581c2c4a12974d376891e359c4', 'Operator Binding Yoshino 3', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:26:23', NULL, NULL),
('eb2e03a5fe5e49d2a52134c558ec137c', 'Operator Mesin Wrapping Barat & Pengiriman Internal', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:29:23', NULL, NULL),
('ed208bf0aa754529998e02335f803161', 'Editor', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:18:44', NULL, NULL),
('ed66360eec504a23b104db5685c68eb4', 'Pajak', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:30:49', NULL, NULL),
('ee9eacfdd4934110bb900a7d15ba9786', '5 Redaktur Pelaksana Bahana', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:11:28', NULL, NULL),
('ef28b75c6c93455e8cbb8809506a318f', 'Keuangan - Kasir', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:21:19', NULL, NULL),
('efcc65bf27ab41719b35512924acc547', 'Gudang Cabang', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:20:00', NULL, NULL),
('f149f37b6461413fbfca9cf92c2ef5f7', '1 Staf Ahli Direktur Utama', 'be8d42fa88a14406ac201974963d9c1b', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-20 03:48:27', NULL, '2022-10-20 03:48:46'),
('f340d51d5710489aac530447ec1c1402', 'Direct Selling Umum & Perguruan Tinggi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:16:36', NULL, NULL),
('f452db7bf45e4fb59cc1af2b27d18901', 'Super Agen', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 03:53:01', NULL, NULL),
('f54b0d94d2874bcf879adebf1043001d', 'Operator Speed Master 2 Warna', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 04:08:36', NULL, NULL),
('f77a260046ae44c9ac5f10912b9b67c7', 'Editor Senior', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:18:56', NULL, NULL),
('f816d48287f0437181a00d1c76e4db99', 'Operator Hand Press & Potong Polar 1998', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:27:56', NULL, NULL),
('f8d18ec158d24f86afdf355bb56a0d67', '2 Manager Online', '57a9534bd79d4382bb0f43c89910702c', '57a9534bd79d4382bb0f43c89910702c', NULL, '2022-05-30 20:48:21', '2022-05-30 20:48:41', NULL),
('fc1f198e5dc9416fa68cb991d422804c', 'Manager Buku Pendidikan', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:22:15', NULL, NULL),
('fc9c94021e754295baef3c8f43e731a9', 'Direct Selling SMK Pati', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:16:11', NULL, NULL),
('fd088b6b60aa4dac9163eb542d43468f', 'Operator GTO 4 Warna', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:27:39', NULL, NULL),
('fe067d18155c4f298f07a409f6aca5a4', '1 Direktur Utama', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:46:14', NULL, NULL),
('fee49ef49c5141589a951c0be4a18690', 'Sales Toko Buku Modern', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:38:44', NULL, NULL),
('ff85ff36f9474471ba976dd189e8efad', 'Content Creator Sosmed & Marketplace', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:16:04', NULL, NULL);

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
  `type` enum('Penilaian Naskah','Timeline Naskah','Tolak Order E-Book','Terima Order E-Book') NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `raw_data` text,
  `permission_id` varchar(36) NOT NULL,
  `form_id` varchar(36) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expired` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notif`
--

INSERT INTO `notif` (`id`, `section`, `type`, `url`, `raw_data`, `permission_id`, `form_id`, `created_at`, `expired`) VALUES
('015a74b847ba42ef80a02305cbc4f35a', 'Penerbitan', 'Terima Order E-Book', NULL, NULL, '4cea10b3a4434bc3b342407a78a9ab2a', '67d6d21b-de7c-4bfb-a5c1-1f760ad77645', '2023-01-18 05:57:53', NULL),
('041c1f3237c041f89175f92e4006ab6f', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '8791f143a90e42e2a4d1d0d6b1254bad', 'f9e5451153bb4f6ab910dfee4e2668b0', '2023-01-18 07:13:30', '2023-01-18 07:14:29'),
('12b8a2c729474c0093c22ece80a00fd3', 'Penerbitan', 'Tolak Order E-Book', NULL, NULL, '4cea10b3a4434bc3b342407a78a9ab2a', '7c706efc-cf87-4b59-9d00-8fa036895647', '2023-01-18 06:10:37', NULL),
('153870a3b19a494d95132def31c5dc6f', 'Penerbitan', 'Terima Order E-Book', NULL, NULL, '78712deb909d4d88af7f098c0fcf6857', '7c706efc-cf87-4b59-9d00-8fa036895647', '2023-01-18 06:13:36', NULL),
('2bf4c0e8d86548c48df5f3fd3df41aa3', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '12b852d92d284ab5a654c26e8856fffd', '5ace04aefcf14f1d968d5435091880dd', '2023-01-17 07:20:32', '2023-01-17 07:30:06'),
('2ede7e01253d4942a4c89abf3aba910a', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'a213b689b8274f4dbe19b3fb24d66840', '5ace04aefcf14f1d968d5435091880dd', '2023-01-17 07:20:32', '2023-01-17 07:23:22'),
('313e10568c8842eb955c4d25565474df', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'ebca07da8aad42c4aee304e3a6b81001', '334ddadb6c144d3cac9449be04dc3887', '2023-01-13 09:02:55', '2023-01-13 09:05:22'),
('33743a2e953b40919126a043573e53d5', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'ebca07da8aad42c4aee304e3a6b81001', 'f9e5451153bb4f6ab910dfee4e2668b0', '2023-01-18 07:05:26', '2023-01-18 07:08:09'),
('3a2f5ce186264fa885ef77646d779a51', 'Penerbitan', 'Tolak Order E-Book', NULL, NULL, '78712deb909d4d88af7f098c0fcf6857', '67d6d21b-de7c-4bfb-a5c1-1f760ad77645', '2023-01-18 05:58:31', NULL),
('3b7d06ec9ee042539ddb043e7cdddf21', 'Penerbitan', 'Terima Order E-Book', NULL, NULL, '8f53727c763849aab80c1513505decf8', '67d6d21b-de7c-4bfb-a5c1-1f760ad77645', '2023-01-18 06:00:13', NULL),
('3df041bb9b5e4184b5d2545eb5061cea', 'Penerbitan', 'Terima Order E-Book', NULL, NULL, '8f53727c763849aab80c1513505decf8', '7c706efc-cf87-4b59-9d00-8fa036895647', '2023-01-18 06:22:47', NULL),
('4d2597b705ab4f6bba7b1447516b3b03', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'a213b689b8274f4dbe19b3fb24d66840', '334ddadb6c144d3cac9449be04dc3887', '2023-01-13 09:02:55', '2023-01-13 09:09:17'),
('6268cb20a5cd47e4880f24b770e156cc', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '9beba245308543ce821efe8a3ba965e3', '334ddadb6c144d3cac9449be04dc3887', '2023-01-13 09:02:55', NULL),
('64b71aeb3ef3492091b4dc3474532a0e', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '8791f143a90e42e2a4d1d0d6b1254bad', 'f9e5451153bb4f6ab910dfee4e2668b0', '2023-01-18 07:11:03', '2023-01-18 07:14:29'),
('6af7634b1cf34fe0a89e67992014ee24', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '12b852d92d284ab5a654c26e8856fffd', 'f9e5451153bb4f6ab910dfee4e2668b0', '2023-01-18 07:05:26', '2023-01-18 07:11:03'),
('6c1d619ac45b48b380817f3a35203874', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '9beba245308543ce821efe8a3ba965e3', 'f9e5451153bb4f6ab910dfee4e2668b0', '2023-01-18 07:05:26', NULL),
('778203fc2b5b4a38a180cf413e0c5648', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '8791f143a90e42e2a4d1d0d6b1254bad', '5ace04aefcf14f1d968d5435091880dd', '2023-01-17 07:30:06', '2023-01-17 07:31:56'),
('7ad65a2826fe43f181f004a6ea643b87', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'a213b689b8274f4dbe19b3fb24d66840', 'f9e5451153bb4f6ab910dfee4e2668b0', '2023-01-18 07:05:26', '2023-01-18 07:09:20'),
('7b129cadc6584b5c8e4c37cd241d1119', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '12b852d92d284ab5a654c26e8856fffd', '334ddadb6c144d3cac9449be04dc3887', '2023-01-13 09:02:55', '2023-01-13 09:06:40'),
('96d7f3ef12c34a72b3953e545e84e3d1', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '9beba245308543ce821efe8a3ba965e3', '5ace04aefcf14f1d968d5435091880dd', '2023-01-17 07:20:32', NULL),
('a34e74c45d924e6d90b9f4ac5e21b587', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '8791f143a90e42e2a4d1d0d6b1254bad', '334ddadb6c144d3cac9449be04dc3887', '2023-01-13 09:12:40', '2023-01-13 09:17:34'),
('ae51f1db35894272895859db6551e1c8', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'ebca07da8aad42c4aee304e3a6b81001', '5ace04aefcf14f1d968d5435091880dd', '2023-01-17 07:20:32', '2023-01-17 07:25:19'),
('df66265d07bb4ba7a1f08f21d492f1b2', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '8791f143a90e42e2a4d1d0d6b1254bad', '5ace04aefcf14f1d968d5435091880dd', '2023-01-17 07:31:08', '2023-01-17 07:31:56'),
('e86076f4d7004343a1d8c7fcec39e866', 'Penerbitan', 'Terima Order E-Book', NULL, NULL, '171e6210418440a8bf4d689841d0f32c', '7c706efc-cf87-4b59-9d00-8fa036895647', '2023-01-18 06:09:30', NULL),
('fab6f9d47eb94ed6aa1c0a508135128e', 'Penerbitan', 'Terima Order E-Book', NULL, NULL, '171e6210418440a8bf4d689841d0f32c', '67d6d21b-de7c-4bfb-a5c1-1f760ad77645', '2023-01-18 05:56:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notif_detail`
--

CREATE TABLE `notif_detail` (
  `notif_id` varchar(36) NOT NULL,
  `user_id` varchar(36) NOT NULL,
  `seen` enum('1','0') NOT NULL DEFAULT '0' COMMENT 'if seen(1) update(null)::: updated by naskah',
  `raw_data` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notif_detail`
--

INSERT INTO `notif_detail` (`notif_id`, `user_id`, `seen`, `raw_data`, `created_at`, `updated_at`) VALUES
('313e10568c8842eb955c4d25565474df', 'ceadd9fb648445eab1e350357e51d1ce', '1', NULL, '2023-01-13 09:02:55', '2023-01-13 09:05:22'),
('ae51f1db35894272895859db6551e1c8', 'ceadd9fb648445eab1e350357e51d1ce', '1', NULL, '2023-01-17 07:20:32', '2023-01-17 07:25:19'),
('fab6f9d47eb94ed6aa1c0a508135128e', '7c4f86aba34f430884fb87fee84bc870', '0', NULL, '2023-01-18 05:56:31', NULL),
('015a74b847ba42ef80a02305cbc4f35a', '7c4f86aba34f430884fb87fee84bc870', '0', NULL, '2023-01-18 05:57:53', NULL),
('3a2f5ce186264fa885ef77646d779a51', '7c4f86aba34f430884fb87fee84bc870', '0', NULL, '2023-01-18 05:58:31', NULL),
('3b7d06ec9ee042539ddb043e7cdddf21', '7c4f86aba34f430884fb87fee84bc870', '0', NULL, '2023-01-18 06:00:13', NULL),
('3b7d06ec9ee042539ddb043e7cdddf21', '7c4f86aba34f430884fb87fee84bc870', '0', NULL, '2023-01-18 06:00:13', NULL),
('3b7d06ec9ee042539ddb043e7cdddf21', '7c4f86aba34f430884fb87fee84bc870', '0', NULL, '2023-01-18 06:00:13', NULL),
('e86076f4d7004343a1d8c7fcec39e866', '7c4f86aba34f430884fb87fee84bc870', '0', NULL, '2023-01-18 06:09:30', NULL),
('12b8a2c729474c0093c22ece80a00fd3', '7c4f86aba34f430884fb87fee84bc870', '0', NULL, '2023-01-18 06:10:37', NULL),
('153870a3b19a494d95132def31c5dc6f', '7c4f86aba34f430884fb87fee84bc870', '0', NULL, '2023-01-18 06:13:36', NULL),
('3df041bb9b5e4184b5d2545eb5061cea', '7c4f86aba34f430884fb87fee84bc870', '0', NULL, '2023-01-18 06:22:47', NULL),
('3df041bb9b5e4184b5d2545eb5061cea', '7c4f86aba34f430884fb87fee84bc870', '0', NULL, '2023-01-18 06:22:47', NULL),
('3df041bb9b5e4184b5d2545eb5061cea', '7c4f86aba34f430884fb87fee84bc870', '0', NULL, '2023-01-18 06:22:47', NULL),
('33743a2e953b40919126a043573e53d5', 'ceadd9fb648445eab1e350357e51d1ce', '1', NULL, '2023-01-18 07:05:26', '2023-01-18 07:08:09');

-- --------------------------------------------------------

--
-- Table structure for table `order_cetak`
--

CREATE TABLE `order_cetak` (
  `id` char(36) NOT NULL,
  `deskripsi_turun_cetak_id` char(36) DEFAULT NULL,
  `kode_order` varchar(8) NOT NULL,
  `status_cetak` enum('1','2','3') DEFAULT NULL COMMENT '1:Buku Baru | 2:Cetak Ulang Revisi | 3:Cetak Ulang ',
  `posisi_layout` enum('1','2') DEFAULT NULL COMMENT '1= Potrait (Tegak),\r\n2= Landscape (Tidur)',
  `dami` varchar(2) DEFAULT NULL COMMENT 'Isian tergantung pada posisi_layout',
  `jenis_cover` varchar(255) DEFAULT NULL,
  `kertas_cover` varchar(255) DEFAULT NULL,
  `ukuran_jilid_binding` varchar(10) DEFAULT NULL COMMENT 'Terisi jika jilid nya adalah ''Bending'' (lihat kolom jilid)',
  `tahun_terbit` year(4) DEFAULT NULL,
  `buku_jadi` enum('Wrapping','Tidak Wrapping') DEFAULT NULL,
  `jumlah_cetak` int(10) DEFAULT NULL,
  `buku_contoh` text,
  `spp` varchar(30) DEFAULT NULL,
  `keterangan` text,
  `perlengkapan` text,
  `tgl_permintaan_jadi` date DEFAULT NULL,
  `status` enum('Antrian','Pending','Proses','Selesai') DEFAULT 'Antrian',
  `tgl_masuk` datetime DEFAULT NULL,
  `tgl_selesai_order` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_cetak`
--

INSERT INTO `order_cetak` (`id`, `deskripsi_turun_cetak_id`, `kode_order`, `status_cetak`, `posisi_layout`, `dami`, `jenis_cover`, `kertas_cover`, `ukuran_jilid_binding`, `tahun_terbit`, `buku_jadi`, `jumlah_cetak`, `buku_contoh`, `spp`, `keterangan`, `perlengkapan`, `tgl_permintaan_jadi`, `status`, `tgl_masuk`, `tgl_selesai_order`) VALUES
('7c706efc-cf87-4b59-9d00-8fa036895647', 'b629bea1-e1c7-4d61-b00a-7489341abc1b', '23-1001', '1', '1', '24', 'Soft', 'Ivory 60', '2.5', 2023, 'Wrapping', 2000, NULL, '098/SPP/01/2023', NULL, NULL, '2023-01-31', 'Proses', '2023-01-18 11:44:48', NULL),
('dd190773-e637-4459-9d52-d9aa9a5d09fa', 'ee5fcc10-4bb2-4f06-b9e1-c23fd377af33', '23-1000', '1', '2', '16', 'Softcover', 'Ivory', '4', 2023, 'Tidak Wrapping', 200, NULL, NULL, NULL, NULL, '2023-01-25', 'Proses', '2023-01-09 19:56:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_cetak_action`
--

CREATE TABLE `order_cetak_action` (
  `id` bigint(20) NOT NULL,
  `order_cetak_id` char(36) DEFAULT NULL,
  `type_departemen` enum('Penerbitan','Marketing & Ops','Keuangan','Direktur Utama') DEFAULT NULL,
  `type_action` enum('Approval','Decline') DEFAULT NULL,
  `users_id` varchar(36) DEFAULT NULL,
  `catatan_action` text,
  `tgl_action` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `order_cetak_history`
--

CREATE TABLE `order_cetak_history` (
  `id` bigint(20) NOT NULL,
  `order_cetak_id` char(36) DEFAULT NULL,
  `type_history` enum('Update','Approval','Decline','Status') DEFAULT NULL,
  `status_cetak_his` tinyint(4) DEFAULT NULL COMMENT '1:Buku Baru | 2:Cetak Ulang Revisi | 3:Cetak Ulang ',
  `status_cetak_new` tinyint(4) DEFAULT NULL COMMENT '1:Buku Baru | 2:Cetak Ulang Revisi | 3:Cetak Ulang ',
  `edisi_cetak_his` varchar(100) DEFAULT NULL,
  `edisi_cetak_new` varchar(100) DEFAULT NULL,
  `jml_hal_perkiraan_his` mediumint(9) DEFAULT NULL,
  `jml_hal_perkiraan_new` mediumint(9) DEFAULT NULL,
  `kelompok_buku_id_his` varchar(36) DEFAULT NULL,
  `kelompok_buku_id_new` varchar(36) DEFAULT NULL,
  `tipe_order_his` set('1','2') DEFAULT NULL COMMENT '1= Umum, 2= Rohani',
  `tipe_order_new` set('1','2') DEFAULT NULL COMMENT '1= Umum, 2= Rohani',
  `posisi_layout_his` tinyint(4) DEFAULT NULL COMMENT '1= Potrait (Tegak), 2= Landscape (Tidur)',
  `posisi_layout_new` tinyint(4) DEFAULT NULL COMMENT '1= Potrait (Tegak), 2= Landscape (Tidur)',
  `dami_his` tinyint(4) DEFAULT NULL,
  `dami_new` tinyint(4) DEFAULT NULL,
  `format_buku_his` varchar(15) DEFAULT NULL,
  `format_buku_new` varchar(15) DEFAULT NULL,
  `jilid_his` varchar(15) DEFAULT NULL,
  `jilid_new` varchar(15) DEFAULT NULL,
  `ukuran_binding_his` tinyint(4) DEFAULT NULL,
  `ukuran_binding_new` tinyint(4) DEFAULT NULL,
  `kertas_isi_his` varchar(15) DEFAULT NULL,
  `kertas_isi_new` varchar(15) DEFAULT NULL,
  `isi_warna_his` varchar(20) DEFAULT NULL,
  `isi_warna_new` varchar(20) DEFAULT NULL,
  `jenis_cover_his` varchar(100) DEFAULT NULL,
  `jenis_cover_new` varchar(100) DEFAULT NULL,
  `kertas_cover_his` varchar(100) DEFAULT NULL,
  `kertas_cover_new` varchar(100) DEFAULT NULL,
  `warna_cover_his` varchar(30) DEFAULT NULL,
  `warna_cover_new` varchar(30) DEFAULT NULL,
  `finishing_cover_his` text,
  `finishing_cover_new` text,
  `buku_jadi_his` varchar(15) DEFAULT NULL,
  `buku_jadi_new` varchar(15) DEFAULT NULL,
  `jumlah_cetak_his` tinyint(4) DEFAULT NULL,
  `jumlah_cetak_new` tinyint(4) DEFAULT NULL,
  `tahun_terbit_his` year(4) DEFAULT NULL,
  `tahun_terbit_new` year(4) DEFAULT NULL,
  `tgl_permintaan_jadi_his` date DEFAULT NULL,
  `tgl_permintaan_jadi_new` date DEFAULT NULL,
  `spp_his` varchar(255) DEFAULT NULL,
  `spp_new` varchar(255) DEFAULT NULL,
  `buku_contoh_his` text,
  `buku_contoh_new` text,
  `perlengkapan_his` text,
  `perlengkapan_new` text,
  `keterangan_his` text,
  `keterangan_new` text,
  `status_his` varchar(8) DEFAULT NULL,
  `status_new` varchar(8) DEFAULT NULL,
  `type_departemen` varchar(20) DEFAULT NULL,
  `catatan_action` text,
  `author_id` varchar(36) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_cetak_history`
--

INSERT INTO `order_cetak_history` (`id`, `order_cetak_id`, `type_history`, `status_cetak_his`, `status_cetak_new`, `edisi_cetak_his`, `edisi_cetak_new`, `jml_hal_perkiraan_his`, `jml_hal_perkiraan_new`, `kelompok_buku_id_his`, `kelompok_buku_id_new`, `tipe_order_his`, `tipe_order_new`, `posisi_layout_his`, `posisi_layout_new`, `dami_his`, `dami_new`, `format_buku_his`, `format_buku_new`, `jilid_his`, `jilid_new`, `ukuran_binding_his`, `ukuran_binding_new`, `kertas_isi_his`, `kertas_isi_new`, `isi_warna_his`, `isi_warna_new`, `jenis_cover_his`, `jenis_cover_new`, `kertas_cover_his`, `kertas_cover_new`, `warna_cover_his`, `warna_cover_new`, `finishing_cover_his`, `finishing_cover_new`, `buku_jadi_his`, `buku_jadi_new`, `jumlah_cetak_his`, `jumlah_cetak_new`, `tahun_terbit_his`, `tahun_terbit_new`, `tgl_permintaan_jadi_his`, `tgl_permintaan_jadi_new`, `spp_his`, `spp_new`, `buku_contoh_his`, `buku_contoh_new`, `perlengkapan_his`, `perlengkapan_new`, `keterangan_his`, `keterangan_new`, `status_his`, `status_new`, `type_departemen`, `catatan_action`, `author_id`, `modified_at`) VALUES
(1, 'dd190773-e637-4459-9d52-d9aa9a5d09fa', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Antrian', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-13 14:35:51'),
(2, 'dd190773-e637-4459-9d52-d9aa9a5d09fa', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-13 14:37:24'),
(3, 'dd190773-e637-4459-9d52-d9aa9a5d09fa', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Pending', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-13 14:55:38'),
(4, 'dd190773-e637-4459-9d52-d9aa9a5d09fa', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Antrian', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-13 14:56:22'),
(5, 'dd190773-e637-4459-9d52-d9aa9a5d09fa', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-14 15:18:28'),
(7, 'dd190773-e637-4459-9d52-d9aa9a5d09fa', 'Update', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, 16, NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, 'Softcover', NULL, 'Ivory', NULL, 'Merah', '[\"Glossy\",\"Laminasi Dof\",\"UV Spot\"]', '[\"Glossy\",\"Laminasi Dof\"]', NULL, NULL, NULL, 127, NULL, 2023, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-16 17:13:20');

-- --------------------------------------------------------

--
-- Table structure for table `order_ebook`
--

CREATE TABLE `order_ebook` (
  `id` char(36) NOT NULL,
  `deskripsi_turun_cetak_id` char(36) DEFAULT NULL,
  `kode_order` varchar(9) NOT NULL,
  `tahun_terbit` year(4) DEFAULT NULL,
  `spp` varchar(25) DEFAULT NULL,
  `eisbn` char(13) DEFAULT NULL,
  `perlengkapan` text,
  `keterangan` text,
  `tgl_upload` datetime DEFAULT NULL,
  `status` enum('Antrian','Pending','Proses','Selesai') DEFAULT 'Antrian',
  `tgl_masuk` datetime DEFAULT NULL,
  `tgl_selesai_order` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_ebook`
--

INSERT INTO `order_ebook` (`id`, `deskripsi_turun_cetak_id`, `kode_order`, `tahun_terbit`, `spp`, `eisbn`, `perlengkapan`, `keterangan`, `tgl_upload`, `status`, `tgl_masuk`, `tgl_selesai_order`) VALUES
('67d6d21b-de7c-4bfb-a5c1-1f760ad77645', 'b629bea1-e1c7-4d61-b00a-7489341abc1b', 'E23-1001', 2023, '098/SPP/01/2023', '5666677778888', NULL, NULL, '2023-01-19 13:03:33', 'Selesai', '2023-01-18 11:44:48', '2023-01-18 13:03:53'),
('7916a422-105b-4996-ad12-8410720eda28', 'ee5fcc10-4bb2-4f06-b9e1-c23fd377af33', 'E23-1000', 2023, NULL, '2343423423423', NULL, NULL, '2023-01-14 08:56:08', 'Proses', '2023-01-09 19:56:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_ebook_action`
--

CREATE TABLE `order_ebook_action` (
  `id` bigint(20) NOT NULL,
  `order_ebook_id` char(36) DEFAULT NULL,
  `type_departemen` enum('Penerbitan','Marketing & Ops','Keuangan','Direktur Utama') DEFAULT NULL,
  `type_action` enum('Approval','Decline') DEFAULT NULL,
  `users_id` varchar(36) DEFAULT NULL,
  `catatan_action` text,
  `tgl_action` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `order_ebook_history`
--

CREATE TABLE `order_ebook_history` (
  `id` bigint(20) NOT NULL,
  `type_history` enum('Status','Update','Approval','Decline') DEFAULT NULL,
  `order_ebook_id` char(36) DEFAULT NULL,
  `tipe_order_his` tinyint(4) DEFAULT NULL,
  `tipe_order_new` tinyint(4) DEFAULT NULL,
  `edisi_cetak_his` varchar(100) DEFAULT NULL,
  `edisi_cetak_new` varchar(100) DEFAULT NULL,
  `jml_hal_perkiraan_his` int(11) DEFAULT NULL,
  `jml_hal_perkiraan_new` int(11) DEFAULT NULL,
  `kelompok_buku_id_his` varchar(36) DEFAULT NULL,
  `kelompok_buku_id_new` varchar(36) DEFAULT NULL,
  `tahun_terbit_his` year(4) DEFAULT NULL,
  `tahun_terbit_new` year(4) DEFAULT NULL,
  `tgl_upload_his` datetime DEFAULT NULL,
  `tgl_upload_new` datetime DEFAULT NULL,
  `spp_his` varchar(25) DEFAULT NULL,
  `spp_new` varchar(25) DEFAULT NULL,
  `keterangan_his` text,
  `keterangan_new` text,
  `perlengkapan_his` text,
  `perlengkapan_new` text,
  `eisbn_his` char(13) DEFAULT NULL,
  `eisbn_new` char(13) DEFAULT NULL,
  `status_his` varchar(8) DEFAULT NULL,
  `status_new` varchar(8) DEFAULT NULL,
  `type_departemen` varchar(20) DEFAULT NULL,
  `catatan_action` text,
  `author_id` varchar(36) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_ebook_history`
--

INSERT INTO `order_ebook_history` (`id`, `type_history`, `order_ebook_id`, `tipe_order_his`, `tipe_order_new`, `edisi_cetak_his`, `edisi_cetak_new`, `jml_hal_perkiraan_his`, `jml_hal_perkiraan_new`, `kelompok_buku_id_his`, `kelompok_buku_id_new`, `tahun_terbit_his`, `tahun_terbit_new`, `tgl_upload_his`, `tgl_upload_new`, `spp_his`, `spp_new`, `keterangan_his`, `keterangan_new`, `perlengkapan_his`, `perlengkapan_new`, `eisbn_his`, `eisbn_new`, `status_his`, `status_new`, `type_departemen`, `catatan_action`, `author_id`, `modified_at`) VALUES
(1, 'Status', '7916a422-105b-4996-ad12-8410720eda28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Pending', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-10 15:17:46'),
(2, 'Status', '7916a422-105b-4996-ad12-8410720eda28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Proses', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-10 15:38:04'),
(5, 'Update', '7916a422-105b-4996-ad12-8410720eda28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-12 08:53:32', '2023-01-14 08:56:08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-11 08:56:08'),
(8, 'Decline', '7916a422-105b-4996-ad12-8410720eda28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Dir. Operasional', 'Kurangin jumlahnya', 'c94ad7236255430b82c0546dd82b917e', '2023-01-13 13:35:59'),
(11, 'Status', '67d6d21b-de7c-4bfb-a5c1-1f760ad77645', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', NULL, NULL, 'dc4547d5a820478188ae9c1a1ae876a6', '2023-01-18 12:53:24'),
(12, 'Update', '67d6d21b-de7c-4bfb-a5c1-1f760ad77645', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2023, '1970-01-01 07:00:00', '2023-01-19 12:54:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5666677778888', NULL, NULL, NULL, NULL, 'dc4547d5a820478188ae9c1a1ae876a6', '2023-01-18 12:54:32'),
(15, 'Decline', '67d6d21b-de7c-4bfb-a5c1-1f760ad77645', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Dir. Keuangan', 'jangan', 'ab76995143c94f07b6cadc832f252752', '2023-01-18 12:58:31'),
(17, 'Update', '67d6d21b-de7c-4bfb-a5c1-1f760ad77645', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-19 12:54:32', '2023-01-19 13:03:33', NULL, '098/SPP/01/2023', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'dc4547d5a820478188ae9c1a1ae876a6', '2023-01-18 13:03:33'),
(18, 'Status', '67d6d21b-de7c-4bfb-a5c1-1f760ad77645', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', NULL, NULL, 'dc4547d5a820478188ae9c1a1ae876a6', '2023-01-18 13:03:53');

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
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
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
('87cf57e453044cb890784aacc59461f6', 'KB001', 'Aplikasi Office', 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-02-23 03:27:48', NULL, NULL),
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
-- Table structure for table `penerbitan_m_kelompok_buku_history`
--

CREATE TABLE `penerbitan_m_kelompok_buku_history` (
  `id` bigint(20) NOT NULL,
  `kelompok_buku_id` varchar(36) DEFAULT NULL,
  `kelompok_buku_history` varchar(100) DEFAULT NULL,
  `kelompok_buku_new` varchar(100) DEFAULT NULL,
  `author_id` varchar(36) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penerbitan_m_kelompok_buku_history`
--

INSERT INTO `penerbitan_m_kelompok_buku_history` (`id`, `kelompok_buku_id`, `kelompok_buku_history`, `kelompok_buku_new`, `author_id`, `modified_at`) VALUES
(1, '87cf57e453044cb890784aacc59461f6', 'Aplikasi Office Com', 'Aplikasi Office Com', 'be8d42fa88a14406ac201974963d9c1b', '2022-09-23 10:15:17'),
(2, '87cf57e453044cb890784aacc59461f6', 'Aplikasi Office Com', 'Aplikasi Office', 'be8d42fa88a14406ac201974963d9c1b', '2022-09-23 10:15:44');

-- --------------------------------------------------------

--
-- Table structure for table `penerbitan_naskah`
--

CREATE TABLE `penerbitan_naskah` (
  `id` varchar(36) NOT NULL,
  `kode` varchar(13) NOT NULL,
  `judul_asli` text,
  `tanggal_masuk_naskah` date DEFAULT NULL,
  `kelompok_buku_id` varchar(36) DEFAULT NULL,
  `jalur_buku` enum('Reguler','MoU','MoU-Reguler','SMK/NonSMK','Pro Literasi') DEFAULT NULL,
  `sumber_naskah` text COMMENT 'Array',
  `url_file` text,
  `cdqr_code` enum('1','0') NOT NULL DEFAULT '0' COMMENT '1:Ya|0:Tidak',
  `keterangan` text,
  `pic_prodev` varchar(36) DEFAULT NULL,
  `penilaian_naskah` enum('1','0') NOT NULL,
  `date_pic_prodev` timestamp NULL DEFAULT NULL,
  `penilaian_prodev` enum('1','0') DEFAULT NULL COMMENT '#deprecated',
  `penilaian_editset` enum('1','0') DEFAULT NULL COMMENT '#deprecated',
  `penilaian_pemasaran` enum('1','0') DEFAULT NULL COMMENT '#deprecated',
  `penilaian_penerbitan` enum('1','0') DEFAULT NULL COMMENT '#deprecated',
  `penilaian_direksi` enum('1','0') DEFAULT NULL COMMENT '#deprecated',
  `selesai_penilaian` enum('0','1','2') DEFAULT NULL COMMENT 'N:Default | 0:Belum Selesai | 1:Selesai Dinilai | 2:Tidak Dinilai',
  `selesai_penilaian_tgl` datetime DEFAULT NULL,
  `bukti_email_penulis` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penerbitan_naskah`
--

INSERT INTO `penerbitan_naskah` (`id`, `kode`, `judul_asli`, `tanggal_masuk_naskah`, `kelompok_buku_id`, `jalur_buku`, `sumber_naskah`, `url_file`, `cdqr_code`, `keterangan`, `pic_prodev`, `penilaian_naskah`, `date_pic_prodev`, `penilaian_prodev`, `penilaian_editset`, `penilaian_pemasaran`, `penilaian_penerbitan`, `penilaian_direksi`, `selesai_penilaian`, `selesai_penilaian_tgl`, `bukti_email_penulis`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
('16fdb6f329c544c6824cf85ec38501b7', 'NA20220914017', 'Di balik mata kaca', '2022-09-14', '19232a62008d40e89b45572ab768634b', 'SMK/NonSMK', NULL, 'https://www.google.com', '1', NULL, 'e83ca4537495486c8d3b5d7e6ae2407a', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-14 12:00:42', 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-09-14 02:58:18', '2022-10-17 06:14:52', NULL),
('1a58f15bdf304d28a4063808a512cb8b', 'NA20220609011', 'Seri Profesi - Arsitek', '2022-06-09', '488697c7556a413eb433806b35bb635f', 'Reguler', NULL, NULL, '0', NULL, '072df3b932394d6caacb5c9c0960d42b', '1', '2022-06-08 20:42:59', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-09 03:42:59', NULL, NULL),
('1a97e49ef92945d58bbc26b388771d9c', 'NA20221029023', 'Mencari Uang\r\n-Begadang bukan solusi!', '2022-10-30', '61de98a888f949138397db5fe09798c4', 'Reguler', '[\"HC\",\"SC\"]', 'https://www.w3schools.com/tags/att_input_type_url.asp', '0', 'cetak', 'ceadd9fb648445eab1e350357e51d1ce', '1', NULL, NULL, NULL, NULL, NULL, '1', '1', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-28 20:17:52', NULL, NULL),
('1c95c52a37984a51a2b80874f7e482d5', 'NA20220609009', 'Seri Profesi - Desainer Baju', '2022-06-09', '488697c7556a413eb433806b35bb635f', 'Reguler', NULL, NULL, '0', NULL, '072df3b932394d6caacb5c9c0960d42b', '1', '2022-06-08 20:40:07', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-09 03:40:07', NULL, NULL),
('266da2c74b774d45a6717421208134e1', 'NA20220927018', 'Ada Dia Dan Aku', '2022-09-22', '24b12e3f73f84975b33ed2f8202b40ca', 'Reguler', NULL, 'https://www.w3schools.com/tags/att_input_type_url.asp', '1', NULL, 'e83ca4537495486c8d3b5d7e6ae2407a', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-09-27 03:49:48', '2022-10-16 13:25:48', NULL),
('286e171699af495abeca62d8f2a84160', 'NA20221028022', 'Komrfosed\r\n-Komedi Komedo', '2022-10-28', '1ec8fca6167844c8a1e2ebd2b94cc600', 'Reguler', NULL, 'https://www.w3schools.com/tags/att_input_type_url.asp', '0', 'uytfuy', '072df3b932394d6caacb5c9c0960d42b', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-28 09:06:06', NULL, NULL),
('2aea9efddc344fb8b8c03164d4fba843', 'NA20220608003', 'Bahan Ajar Etika Profesi', '2022-06-08', '1598193526f34779babe8c6746fc73d9', 'MoU-Reguler', NULL, NULL, '0', NULL, 'a400e4bcf70d40d78224043cc95e6241', '1', '2022-06-07 23:43:02', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-08 06:43:02', NULL, NULL),
('334ddadb6c144d3cac9449be04dc3887', 'NA20230113028', 'AKU DAN KAMU', '2023-01-07', '2d370bf6aa894cb2a0d1e657d98bf152', 'Reguler', '[\"HC\"]', NULL, '1', 'testing', 'ceadd9fb648445eab1e350357e51d1ce', '1', NULL, NULL, NULL, NULL, NULL, '1', '1', NULL, '2023-01-13 16:27:06', 'dc4547d5a820478188ae9c1a1ae876a6', NULL, NULL, '2023-01-13 09:02:55', NULL, NULL),
('36504ee1023f4bf0be272593c5431669', 'NA20220613013', 'Seri Rawat dan Sayangi Aku - Kweni Berbuah', '2022-06-09', '488697c7556a413eb433806b35bb635f', 'Reguler', NULL, NULL, '0', NULL, '072df3b932394d6caacb5c9c0960d42b', '1', '2022-06-12 19:58:39', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-13 02:58:39', NULL, NULL),
('3b6f233968394094976bcfcd116817df', 'NA20220609010', 'Seri Profesi - Juru Masak', '2022-06-09', '488697c7556a413eb433806b35bb635f', 'Reguler', NULL, NULL, '0', NULL, '072df3b932394d6caacb5c9c0960d42b', '1', '2022-06-08 20:41:41', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-09 03:41:41', NULL, NULL),
('4430fe9a3ae5499c8759d3225f0aa7b5', 'NA20220613014', 'Seri Rawat dan Sayangi Aku - Linci Ingin Disayang', '2022-06-09', '488697c7556a413eb433806b35bb635f', 'Reguler', NULL, NULL, '0', NULL, '072df3b932394d6caacb5c9c0960d42b', '1', '2022-06-12 19:59:39', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-13 02:59:39', NULL, NULL),
('5707a19dd4804b25899e57b37b335ab6', 'NA20220609005', 'Seri Keselamatan di Tempat Umum - Saat di Kebun Binatang', '2022-06-08', '488697c7556a413eb433806b35bb635f', 'Reguler', NULL, NULL, '0', NULL, '072df3b932394d6caacb5c9c0960d42b', '1', '2022-06-08 18:55:46', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-09 01:55:46', NULL, NULL),
('5ace04aefcf14f1d968d5435091880dd', 'NA20230117029', 'Aku Lebih Cepat Melupakanmu', '2023-01-01', '4469f465d7cf474492c3ba285f9bd94c', 'Reguler', '[\"HC\"]', NULL, '1', 'cetak', 'ceadd9fb648445eab1e350357e51d1ce', '1', NULL, NULL, NULL, NULL, NULL, '1', '1', NULL, '2023-01-17 14:35:46', 'dc4547d5a820478188ae9c1a1ae876a6', NULL, NULL, '2023-01-17 07:20:32', NULL, NULL),
('6318ede7909649eca9394fd844dfdd51', 'NA20220608002', 'Bahan Ajar Ketrampilan Interpersonal', '2022-06-08', '1598193526f34779babe8c6746fc73d9', 'MoU-Reguler', NULL, NULL, '0', NULL, 'a400e4bcf70d40d78224043cc95e6241', '1', '2022-06-07 23:40:29', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-08 06:40:29', NULL, NULL),
('6e200c9fa08443e7b9338efbcee16d44', 'NA20221103025', 'Hari Ini Adalah Pengalaman', '2022-11-03', '19232a62008d40e89b45572ab768634b', 'Reguler', '[\"HC\"]', NULL, '1', 'cetak', '0ecea60f2691405585fa1aa535368bee', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-11-03 03:52:47', NULL, NULL),
('7067273f1d904108981ed70925b3728e', 'NA20221026020', 'Menjadi Kaya\r\nCara cepat untuk kaya raya', '2022-10-26', '119337b2f6db478faaced3693ba35e6b', 'Reguler', NULL, 'https://www.w3schools.com/tags/att_input_type_url.asp', '0', 'cetak', '072df3b932394d6caacb5c9c0960d42b', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-10-26 14:54:10', '2022-10-26 15:00:36', NULL),
('84df36f9b0074c9e93028e3e2257a7d1', 'NA20221026019', 'Kelapa Jatuh', '2022-10-26', '0e3c62efe1c34fc595505860ddee1376', 'SMK/NonSMK', NULL, 'https://www.w3schools.com/tags/att_input_type_url.asp', '1', NULL, 'a400e4bcf70d40d78224043cc95e6241', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-26 03:28:58', NULL, NULL),
('886da09d771444279a9075ebc85a1e66', 'NA20221103024', 'Kambbing Hitam Memutih', '2022-11-03', '0242257c619e4f0f85f0a2d872359e95', 'Reguler', '[\"HC\",\"SC\"]', 'https://www.w3schools.com/tags/att_input_type_url.asp', '0', 'cetak', '0ecea60f2691405585fa1aa535368bee', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-11-03 03:23:05', NULL, NULL),
('95d9ebcd691e477eaeaf7c1aba4dd04d', 'NA20220609006', 'Seri Keselamatan di Tempat Umum - Saat di Angkutan Umum', '2022-06-08', '488697c7556a413eb433806b35bb635f', 'Reguler', NULL, NULL, '0', NULL, '072df3b932394d6caacb5c9c0960d42b', '1', '2022-06-08 18:56:53', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-09 01:56:53', NULL, NULL),
('9c8132c2ebc34a3195c3aaca81095f3c', 'NA20220613016', 'Seri Rawat dan Sayangi Aku - Rumah Baru Moci', '2022-06-09', '488697c7556a413eb433806b35bb635f', 'Reguler', NULL, NULL, '0', NULL, '072df3b932394d6caacb5c9c0960d42b', '1', '2022-06-12 20:01:16', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-13 03:01:16', NULL, NULL),
('aeb27fc7ab75452a8bfdc5d014b63d83', 'NA20220608001', 'Rahasia raja uang', '2022-06-30', 'bccd38a17ea04ad695d7054e18306bdc', 'MoU-Reguler', NULL, NULL, '0', NULL, 'f88116e1873c4403a7fb76a08f5266cb', '1', '2022-06-30 23:20:44', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', '205cbcb8d74646f7bfafd9b4972f04ac', NULL, '2022-06-08 05:00:26', '2022-06-30 23:20:44', NULL),
('b6a2fd8a6e8a43d589dad51cdfcd71c5', 'NA20221202027', 'Akuuuuuuuuu', '2022-12-08', '0e3c62efe1c34fc595505860ddee1376', 'SMK/NonSMK', '[\"HC\"]', NULL, '0', 'cetak', 'ceadd9fb648445eab1e350357e51d1ce', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-12-02 08:44:57', NULL, NULL),
('be20459513994ceea0a19f191d947ed0', 'NA20220613015', 'Seri Rawat dan Sayangi Aku - Yamyam dan Keluarga Barunya', '2022-06-09', '488697c7556a413eb433806b35bb635f', 'Reguler', NULL, NULL, '0', NULL, '072df3b932394d6caacb5c9c0960d42b', '1', '2022-06-12 20:00:22', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-13 03:00:22', NULL, NULL),
('be2c0f93a0db462a81f69081b4991a5a', 'NA20221103026', 'Coba-coba-coba', '2022-11-03', '0242257c619e4f0f85f0a2d872359e95', 'Reguler', '[\"HC\"]', NULL, '0', 'cetak', 'a400e4bcf70d40d78224043cc95e6241', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-11-03 03:53:53', '2022-11-03 18:02:30', NULL),
('dc29daf30b16470baebd899424abf819', 'NA20220609004', 'Seri Keselamatan di Tempat Umum - Saat di Mal', '2022-06-08', '488697c7556a413eb433806b35bb635f', 'Reguler', NULL, NULL, '0', NULL, '072df3b932394d6caacb5c9c0960d42b', '1', '2022-06-08 18:54:44', '1', NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-09 01:54:44', NULL, NULL),
('e75dd02c02154c44b142bfa7bb92049c', 'NA20220613012', 'Seri Rawat dan Sayangi Aku - Jangan Lukai Aku', '2022-06-09', '488697c7556a413eb433806b35bb635f', 'Reguler', NULL, NULL, '0', NULL, '072df3b932394d6caacb5c9c0960d42b', '1', '2022-06-12 19:57:14', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-13 02:57:14', NULL, NULL),
('e97bb54ed5b64fc5aab1f6d435e2aaa1', 'NA20221027021', 'Buah Semangka Jatuh\r\n-Sebuah komedi komedo', '2022-10-26', 'd12fdd4026f1443baa17be28c98a659d', 'Reguler', NULL, 'https://www.w3schools.com/tags/att_input_type_url.asp', '0', 'Cetak', 'f88116e1873c4403a7fb76a08f5266cb', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-27 09:08:32', NULL, NULL),
('f56c0df2ccd24207b5a18e660bcf6f1a', 'NA20220609007', 'Seri Keselamatan di Tempat Umum - Saat di Kolam Renang', '2022-06-08', '488697c7556a413eb433806b35bb635f', 'Reguler', NULL, NULL, '0', NULL, '072df3b932394d6caacb5c9c0960d42b', '1', '2022-06-08 18:57:38', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-09 01:57:38', NULL, NULL),
('f9e5451153bb4f6ab910dfee4e2668b0', 'NA20230118030', 'Origami Boneka Jari', '2023-01-18', '488697c7556a413eb433806b35bb635f', 'Reguler', '[\"HC\"]', NULL, '1', 'ok', 'ceadd9fb648445eab1e350357e51d1ce', '1', NULL, NULL, NULL, NULL, NULL, '1', '1', NULL, '2023-01-18 14:22:30', 'dc4547d5a820478188ae9c1a1ae876a6', NULL, NULL, '2023-01-18 07:05:26', NULL, NULL),
('fd9c40ad3c62499e9e9b10af7bfae40c', 'NA20220609008', 'Manajemen Keuangan', '2022-06-09', '1598193526f34779babe8c6746fc73d9', 'Reguler', NULL, NULL, '0', NULL, 'a400e4bcf70d40d78224043cc95e6241', '1', '2022-06-08 20:03:18', '1', NULL, NULL, NULL, '1', '1', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-09 03:03:18', NULL, NULL);

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
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penerbitan_naskah_files`
--

INSERT INTO `penerbitan_naskah_files` (`id`, `naskah_id`, `kategori`, `file`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
('064d3933431f496f96f94298e53fc14c', '1a71b26e23094c39a62f6c8d62ec6665', 'File Tambahan Naskah', 'g2fL1xUNZVmaZ3xxmMFXHwUrcNb32I6Ipi0Ofu50.rar', NULL, NULL, NULL, '2022-07-22 02:32:45', NULL, NULL),
('0b7c5514ae754cf79ae586e12a15c7d9', 'fd9c40ad3c62499e9e9b10af7bfae40c', 'File Naskah Asli', 'VJZqueuUrLRQC26vGSRixSnX5luWr2y1a8oQPRYb.pdf', NULL, NULL, NULL, '2022-06-09 03:03:18', NULL, NULL),
('219bb70917484c9b9b01fd5389dec948', '5707a19dd4804b25899e57b37b335ab6', 'File Naskah Asli', '6iQnREuTGheBhaxJypsmMTJrZjX73u7XJhdsOSuP.pdf', NULL, NULL, NULL, '2022-06-09 01:55:46', NULL, NULL),
('22cd0c750cf7427eb0bb57c595e0458c', 'dc29daf30b16470baebd899424abf819', 'File Naskah Asli', 'bvLmi94UYMTr1w6nBl0CHKtUaB0MBNEYEpW5lw6x.pdf', NULL, NULL, NULL, '2022-06-09 01:54:44', NULL, NULL),
('2bbe146ce5c241dd9d572f25f821fda2', '1c95c52a37984a51a2b80874f7e482d5', 'File Naskah Asli', 'x3XgZVaG8uS1PgSTSspeQg457uOQ6mudbyQHJQui.pdf', NULL, NULL, NULL, '2022-06-09 03:40:07', NULL, NULL),
('31536fc725174a2eb23252ef4320c998', '9c8132c2ebc34a3195c3aaca81095f3c', 'File Naskah Asli', 'OGOUokpo3hLafH2NtSp2HUcuElWgRm5NXgmsXVgq.pdf', NULL, NULL, NULL, '2022-06-13 03:01:16', NULL, NULL),
('3d94ab251a5a49de81e9d5f3379e160d', 'be20459513994ceea0a19f191d947ed0', 'File Naskah Asli', 'PfErEotGIBD00QvIA8x2njRunw9v6tWhzB5o5dfp.pdf', NULL, NULL, NULL, '2022-06-13 03:00:22', NULL, NULL),
('460eaaebf7d04f1a91d674eabf7cb530', 'aeb27fc7ab75452a8bfdc5d014b63d83', 'File Naskah Asli', 'bAElat1bt56ucI1eAzJ9qQpzrSn8DsODYJNHG4D8.pdf', NULL, NULL, NULL, '2022-06-08 05:00:26', NULL, NULL),
('5892d56a88f64a528e0843ee47d3f2b7', 'b9614d8eb16a40cb871a589f23507e19', 'File Naskah Asli', 'fdfdYE9LTqAJ8kOlTwhEBPvXvmV12ln6FCZmmcuh.pdf', NULL, NULL, NULL, '2022-07-27 08:23:22', NULL, NULL),
('606ec12cd3fb46e59c8cad92d1e5594c', '1a58f15bdf304d28a4063808a512cb8b', 'File Naskah Asli', 'k3xXmVblsIwBuK8yw076i4o6VJoAkP93W9aHU8IB.pdf', NULL, NULL, NULL, '2022-06-09 03:42:59', NULL, NULL),
('615edf2140f94c7795ced3c4869c1b02', '36504ee1023f4bf0be272593c5431669', 'File Naskah Asli', 'IswZIXTYJRXXrFbSn8h1xBZ3gqfXdCdypndvhTin.pdf', NULL, NULL, NULL, '2022-06-13 02:58:39', NULL, NULL),
('7276bbb0a725401c8c7725f154d733b8', '1a71b26e23094c39a62f6c8d62ec6665', 'File Naskah Asli', 'FgEkTY5YjgOz4loGbbDfKemiaG9FH29rZUGnpwUj.pdf', NULL, NULL, NULL, '2022-07-22 02:32:45', NULL, NULL),
('8050e10ef2694e9899d5ad2927b0080b', 'f56c0df2ccd24207b5a18e660bcf6f1a', 'File Naskah Asli', 'RTmUQDLwSnlDRXREwCCdCm8qLW9keZleSTn9hhcv.pdf', NULL, NULL, NULL, '2022-06-09 01:57:38', NULL, NULL),
('832df51ad41c486e8e1bf7c35c827da5', '7efc9508128a4bec9ba4bd1140085ca3', 'File Naskah Asli', 'CQXRRAZmUiYxMHu5BVDur0htW1LsEYLTuKsgeEPe.pdf', NULL, NULL, NULL, '2022-09-09 03:21:20', NULL, NULL),
('887598b2414c40f38e8171a928c00eb4', '4430fe9a3ae5499c8759d3225f0aa7b5', 'File Naskah Asli', 'V0NqW7DlADkek0XZ9fNLqe7zVTI8Qw5Odoxco4V6.pdf', NULL, NULL, NULL, '2022-06-13 02:59:39', NULL, NULL),
('b9d2bf759fe74fb182bbc7472fbb092b', '3b6f233968394094976bcfcd116817df', 'File Naskah Asli', '7X6wLqsisuwEKNmO4XfmGhCLmwS6FQziWS58PiuZ.pdf', NULL, NULL, NULL, '2022-06-09 03:41:41', NULL, NULL),
('c0855eeb8ecd44359ed43c37ef89fcf6', '6318ede7909649eca9394fd844dfdd51', 'File Naskah Asli', 'UrHyiMRk2FkUYpoJtPbTc90RgA76anADxXZ5iTNi.pdf', NULL, NULL, NULL, '2022-06-08 06:40:29', NULL, NULL),
('c733ee2132ae44859a5da7b4d0eb3eb0', '53ab006dae6b4fbab6b5077ded2751f7', 'File Naskah Asli', 'fyEkXkrABurBTc1iCeX0RFw0x7rGl3mP97DXbXii.pdf', NULL, NULL, NULL, '2022-09-12 02:59:02', NULL, NULL),
('c9db5a59787c4d36ad5d7b1a22422c7d', '2aea9efddc344fb8b8c03164d4fba843', 'File Naskah Asli', 'BM8zOQDCHFRXV3NAK09EMHaohfQSWor9XisAuHj8.pdf', NULL, NULL, NULL, '2022-06-08 06:43:02', NULL, NULL),
('ce69648db100406898704bf2439098cc', '95d9ebcd691e477eaeaf7c1aba4dd04d', 'File Naskah Asli', '36G9zFcoba2CPPyKUTrMxy6cTs1ZceV3qXRcpwuo.pdf', NULL, NULL, NULL, '2022-06-09 01:56:53', NULL, NULL),
('d467d608bc1e4299b5644d69c9068b0c', '16fdb6f329c544c6824cf85ec38501b7', 'File Naskah Asli', 'NiADodCwVTVkLpD7zsWmEw2x2klbvZxPSICgQMab.pdf', NULL, NULL, NULL, '2022-09-14 02:58:18', NULL, NULL),
('f8776ff915ac4e9ea31749bb2622fa02', 'e75dd02c02154c44b142bfa7bb92049c', 'File Naskah Asli', '5NzQu1sAjHFHleUuVHJRkiZ3TrJiTc9m06SunFoR.pdf', NULL, NULL, NULL, '2022-06-13 02:57:14', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `penerbitan_naskah_history`
--

CREATE TABLE `penerbitan_naskah_history` (
  `id` bigint(20) NOT NULL,
  `naskah_id` varchar(36) DEFAULT NULL,
  `type_history` enum('Update','Sent Email') DEFAULT NULL,
  `judul_asli_his` varchar(255) DEFAULT NULL,
  `judul_asli_new` varchar(255) DEFAULT NULL,
  `kelompok_buku_his` varchar(36) DEFAULT NULL,
  `kelompok_buku_new` varchar(36) DEFAULT NULL,
  `tgl_masuk_nas_his` date DEFAULT NULL,
  `tgl_masuk_nas_new` date DEFAULT NULL,
  `sumber_naskah_his` text,
  `sumber_naskah_new` text,
  `cdqr_code_his` tinyint(4) DEFAULT NULL COMMENT '0/1',
  `cdqr_code_new` tinyint(4) DEFAULT NULL COMMENT '0/1',
  `pic_prodev_his` varchar(36) DEFAULT NULL,
  `pic_prodev_new` varchar(36) DEFAULT NULL,
  `penulis_his` longtext,
  `penulis_new` longtext,
  `bukti_email_penulis` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `author_id` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penerbitan_naskah_history`
--

INSERT INTO `penerbitan_naskah_history` (`id`, `naskah_id`, `type_history`, `judul_asli_his`, `judul_asli_new`, `kelompok_buku_his`, `kelompok_buku_new`, `tgl_masuk_nas_his`, `tgl_masuk_nas_new`, `sumber_naskah_his`, `sumber_naskah_new`, `cdqr_code_his`, `cdqr_code_new`, `pic_prodev_his`, `pic_prodev_new`, `penulis_his`, `penulis_new`, `bukti_email_penulis`, `modified_at`, `author_id`) VALUES
(1, '266da2c74b774d45a6717421208134e1', 'Update', 'Ada Dia', 'Ada Dia Dan Aku', '24b12e3f73f84975b33ed2f8202b40ca', '24b12e3f73f84975b33ed2f8202b40ca', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[{\"naskah_id\":\"266da2c74b774d45a6717421208134e1\",\"penulis_id\":\"353a88a472d5438dacf5e7fb7d6271e3\"},{\"naskah_id\":\"266da2c74b774d45a6717421208134e1\",\"penulis_id\":\"90d2a75954c0442bb9f6b2578e83fc8b\"}]', NULL, '2022-10-16 19:44:33', 'be8d42fa88a14406ac201974963d9c1b'),
(2, '266da2c74b774d45a6717421208134e1', 'Update', NULL, NULL, '24b12e3f73f84975b33ed2f8202b40ca', '24b12e3f73f84975b33ed2f8202b40ca', NULL, NULL, NULL, NULL, NULL, NULL, 'e83ca4537495486c8d3b5d7e6ae2407a', 'e83ca4537495486c8d3b5d7e6ae2407a', NULL, '[{\"naskah_id\":\"266da2c74b774d45a6717421208134e1\",\"penulis_id\":\"353a88a472d5438dacf5e7fb7d6271e3\"}]', NULL, '2022-10-16 20:11:17', 'be8d42fa88a14406ac201974963d9c1b'),
(3, '266da2c74b774d45a6717421208134e1', 'Update', NULL, NULL, NULL, NULL, '2022-09-23', '2022-09-22', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[{\"naskah_id\":\"266da2c74b774d45a6717421208134e1\",\"penulis_id\":\"353a88a472d5438dacf5e7fb7d6271e3\"},{\"naskah_id\":\"266da2c74b774d45a6717421208134e1\",\"penulis_id\":\"90d2a75954c0442bb9f6b2578e83fc8b\"}]', NULL, '2022-10-16 20:25:48', 'be8d42fa88a14406ac201974963d9c1b'),
(4, '16fdb6f329c544c6824cf85ec38501b7', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '77e9d0dacb154a37b5730efb8d36ad29', 'e83ca4537495486c8d3b5d7e6ae2407a', NULL, '[{\"naskah_id\":\"16fdb6f329c544c6824cf85ec38501b7\",\"penulis_id\":\"74deca8d129042a9aa23c82aceee89f5\"}]', NULL, '2022-10-17 13:14:52', 'be8d42fa88a14406ac201974963d9c1b'),
(5, '7067273f1d904108981ed70925b3728e', 'Update', 'Menjadi Kaya\r\nCara cepat untuk kaya', 'Menjadi Kaya\r\nCara cepat untuk kaya raya', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[{\"naskah_id\":\"7067273f1d904108981ed70925b3728e\",\"penulis_id\":\"90d2a75954c0442bb9f6b2578e83fc8b\"}]', NULL, '2022-10-26 22:00:36', 'be8d42fa88a14406ac201974963d9c1b'),
(6, 'be2c0f93a0db462a81f69081b4991a5a', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, '[\"HC\",\"SC\"]', '[\"HC\"]', NULL, NULL, NULL, NULL, NULL, '[{\"naskah_id\":\"be2c0f93a0db462a81f69081b4991a5a\",\"penulis_id\":\"353a88a472d5438dacf5e7fb7d6271e3\"}]', NULL, '2022-11-04 01:02:30', 'be8d42fa88a14406ac201974963d9c1b'),
(7, '334ddadb6c144d3cac9449be04dc3887', 'Sent Email', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-13 16:27:06', '2023-01-13 16:27:06', 'ceadd9fb648445eab1e350357e51d1ce'),
(8, '5ace04aefcf14f1d968d5435091880dd', 'Sent Email', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-17 14:35:46', '2023-01-17 14:35:46', 'ceadd9fb648445eab1e350357e51d1ce'),
(9, 'f9e5451153bb4f6ab910dfee4e2668b0', 'Sent Email', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-18 14:22:30', '2023-01-18 14:22:30', 'ceadd9fb648445eab1e350357e51d1ce');

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
('2fb3089865b6476399a3e0c611f17427', '6318ede7909649eca9394fd844dfdd51'),
('2fb3089865b6476399a3e0c611f17427', '2aea9efddc344fb8b8c03164d4fba843'),
('4f09b39879d4420ea0d40bdf31b3f13a', 'dc29daf30b16470baebd899424abf819'),
('4f09b39879d4420ea0d40bdf31b3f13a', '5707a19dd4804b25899e57b37b335ab6'),
('4f09b39879d4420ea0d40bdf31b3f13a', '95d9ebcd691e477eaeaf7c1aba4dd04d'),
('4f09b39879d4420ea0d40bdf31b3f13a', 'f56c0df2ccd24207b5a18e660bcf6f1a'),
('db307ea965334f6f8284e46d820dd5a1', 'fd9c40ad3c62499e9e9b10af7bfae40c'),
('74deca8d129042a9aa23c82aceee89f5', '1c95c52a37984a51a2b80874f7e482d5'),
('74deca8d129042a9aa23c82aceee89f5', '3b6f233968394094976bcfcd116817df'),
('74deca8d129042a9aa23c82aceee89f5', '1a58f15bdf304d28a4063808a512cb8b'),
('4f09b39879d4420ea0d40bdf31b3f13a', 'e75dd02c02154c44b142bfa7bb92049c'),
('4f09b39879d4420ea0d40bdf31b3f13a', '36504ee1023f4bf0be272593c5431669'),
('4f09b39879d4420ea0d40bdf31b3f13a', '4430fe9a3ae5499c8759d3225f0aa7b5'),
('4f09b39879d4420ea0d40bdf31b3f13a', 'be20459513994ceea0a19f191d947ed0'),
('4f09b39879d4420ea0d40bdf31b3f13a', '9c8132c2ebc34a3195c3aaca81095f3c'),
('e42ff33b548b43538f610935d9dde775', 'aeb27fc7ab75452a8bfdc5d014b63d83'),
('90d2a75954c0442bb9f6b2578e83fc8b', '1a71b26e23094c39a62f6c8d62ec6665'),
('353a88a472d5438dacf5e7fb7d6271e3', 'b9614d8eb16a40cb871a589f23507e19'),
('90d2a75954c0442bb9f6b2578e83fc8b', '7efc9508128a4bec9ba4bd1140085ca3'),
('353a88a472d5438dacf5e7fb7d6271e3', '7efc9508128a4bec9ba4bd1140085ca3'),
('0297e7cb197647a885098e96716bab0d', '7efc9508128a4bec9ba4bd1140085ca3'),
('e42ff33b548b43538f610935d9dde775', '53ab006dae6b4fbab6b5077ded2751f7'),
('353a88a472d5438dacf5e7fb7d6271e3', '266da2c74b774d45a6717421208134e1'),
('90d2a75954c0442bb9f6b2578e83fc8b', '266da2c74b774d45a6717421208134e1'),
('74deca8d129042a9aa23c82aceee89f5', '16fdb6f329c544c6824cf85ec38501b7'),
('353a88a472d5438dacf5e7fb7d6271e3', '84df36f9b0074c9e93028e3e2257a7d1'),
('90d2a75954c0442bb9f6b2578e83fc8b', '84df36f9b0074c9e93028e3e2257a7d1'),
('90d2a75954c0442bb9f6b2578e83fc8b', 'a8d7134ffedb4bf69b551074195ecce6'),
('90d2a75954c0442bb9f6b2578e83fc8b', '7067273f1d904108981ed70925b3728e'),
('3e7189290fc144998971d02e8ca43641', 'e97bb54ed5b64fc5aab1f6d435e2aaa1'),
('353a88a472d5438dacf5e7fb7d6271e3', '286e171699af495abeca62d8f2a84160'),
('0297e7cb197647a885098e96716bab0d', '1a97e49ef92945d58bbc26b388771d9c'),
('353a88a472d5438dacf5e7fb7d6271e3', '886da09d771444279a9075ebc85a1e66'),
('0297e7cb197647a885098e96716bab0d', '6e200c9fa08443e7b9338efbcee16d44'),
('353a88a472d5438dacf5e7fb7d6271e3', 'be2c0f93a0db462a81f69081b4991a5a'),
('353a88a472d5438dacf5e7fb7d6271e3', 'b6a2fd8a6e8a43d589dad51cdfcd71c5'),
('0297e7cb197647a885098e96716bab0d', '334ddadb6c144d3cac9449be04dc3887'),
('68d3825762e94da3aabbb6de96bdb4f9', '5ace04aefcf14f1d968d5435091880dd'),
('60e6ad24480a4ebf91bf00245dafd0b2', 'f9e5451153bb4f6ab910dfee4e2668b0');

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
  `alamat_domisili` text,
  `ponsel_domisili` varchar(20) DEFAULT NULL,
  `telepon_domisili` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nama_kantor` varchar(150) DEFAULT NULL,
  `jabatan_dikantor` varchar(150) DEFAULT NULL,
  `alamat_kantor` text,
  `telepon_kantor` varchar(20) DEFAULT NULL,
  `sosmed_fb` varchar(150) DEFAULT NULL COMMENT 'facebook',
  `sosmed_ig` varchar(150) DEFAULT NULL COMMENT 'instagram',
  `sosmed_tw` varchar(150) DEFAULT NULL COMMENT 'twitter',
  `file_hibah_royalti` varchar(255) DEFAULT NULL,
  `foto_penulis` varchar(255) DEFAULT NULL,
  `url_tentang_penulis` text,
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
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penerbitan_penulis`
--

INSERT INTO `penerbitan_penulis` (`id`, `nama`, `tanggal_lahir`, `tempat_lahir`, `kewarganegaraan`, `alamat_domisili`, `ponsel_domisili`, `telepon_domisili`, `email`, `nama_kantor`, `jabatan_dikantor`, `alamat_kantor`, `telepon_kantor`, `sosmed_fb`, `sosmed_ig`, `sosmed_tw`, `file_hibah_royalti`, `foto_penulis`, `url_tentang_penulis`, `bank`, `bank_atasnama`, `no_rekening`, `npwp`, `ktp`, `scan_npwp`, `scan_ktp`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
('0297e7cb197647a885098e96716bab0d', 'Asus', '2022-04-27', 'Indonesia', 'WNI', 'Jalan Beo', '08560', '08535', 'asus@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'GXA4BY8Qoqvq9EHDkurTR2cH7Q7RH2NhESuadFVR.png', 'https://web.aisystem.id/penerbitan/penulis/mengubah-penulis/0297e7cb197647a885098e96716bab0d', 'bca', 'asus', '917397183981', '34.534.534.5-345.345', '3453453453453453', 'oZimqdtfM3zdpXgb13LnyHU4OAEP5qZHIj1ibUnJ.png', 'hfDa3nNmQK7VPkUWIQip2vvp4g0VZlQkcmgSbW1F.png', 'be8d42fa88a14406ac201974963d9c1b', 'dc4547d5a820478188ae9c1a1ae876a6', NULL, '2022-05-30 04:32:16', '2023-01-13 09:26:44', NULL),
('2fb3089865b6476399a3e0c611f17427', 'DR. Pulung Nurtantio Andono S.T, M.Kom ; Sri Winarno, Ph.D ; Indra Gamaynto, ST., MITM ; Dr.Sendi Novianto S.Kom, M.T', '2022-06-30', 'yogyakarta', 'WNI', NULL, NULL, NULL, 'sendi.novianto@dsn.dinus.ac.id', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-08 06:11:36', NULL, NULL),
('353a88a472d5438dacf5e7fb7d6271e3', 'Yohanes Hendra', '1994-03-01', 'Yogyakarta', 'WNI', NULL, '0', '0', 'hendra@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ROI1vlsHzPv1LoSYcz5CCpyixXML2THirzAu8XZh.pdf', 'EIJlxHuVcFUvpPCZC4oZCeX8SzsD1h388jA9u7zn.jpg', 'https://web.aisystem.id/penerbitan/naskah', NULL, NULL, NULL, NULL, NULL, 'NAq83LeGsdxKCfXM2MAXRT1xO3SliVPrWcTdnsAy.jpg', 'VLwTzDqqBSCQ4qSqmn5Dv4o98gmw1CQaECSi0ZEz.jpg', 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-05-30 03:50:38', '2022-11-04 03:34:05', NULL),
('3e7189290fc144998971d02e8ca43641', 'Dr.Azrul, M.Pd.', '1985-12-01', 'Batu Basa', 'WNI', 'Perumahan Lubuk Sejahtera Lestari, Blok.Garniti No.8, Lubuk Buaya, Kota Padang', '081363342109', NULL, 'azruk@uinin.ac.id', 'Fakultas Tarbiyah dan Kegirian - UIN Imam Bonjol Padang', 'Dosen', 'Jl.Prof.Dr.M.Yunus, Kota Padang, Prov.Sumatera Barat', '0751-35711', NULL, NULL, NULL, NULL, 'default.jpg', NULL, 'BNI', 'Azrul,', '126901001410536', '41.237.242.7-201.000', '1305090108850001', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-10 02:26:33', NULL, NULL),
('4f09b39879d4420ea0d40bdf31b3f13a', 'Fajriatun Nurhidayati', '2022-06-08', 'Yogyakarta', 'WNI', 'RT.06/1, Karangsalam, Kec.Susukan, Kab.Banjarnegara, Jawa Tengah 53475', '0822 1386 0541', NULL, 'fajriatun_nur@yahoo.co.id', NULL, NULL, NULL, NULL, 'fajriatun Nur', '@d_fajria', '@fajriatunNur1', NULL, 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-09 01:52:08', NULL, NULL),
('60e6ad24480a4ebf91bf00245dafd0b2', 'Haziah Ans', '2023-01-18', 'Indonesia', 'WNI', 'Jalan Beo', '0678', '075867', 'asus@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'x8hLxenO43RADuLPSsZEebcNrWyPBItXBkNiKWli.jpg', 'https://web.aisystem.id/penerbitan/penulis/mengubah-penulis/60e6ad24480a4ebf91bf00245dafd0b2', 'bca', 'haziah', '917397183981', '35.187.846.7-242.482', '6896696898798978', 'yuvk7WfLt81L9cWQIf6ua1sXKOMVKTOoi4NWSKvI.jpg', '1lZ0qrzlv35a5cy4tK0pESONkFN8h4lgaegWdJyx.jpg', 'be8d42fa88a14406ac201974963d9c1b', 'dc4547d5a820478188ae9c1a1ae876a6', NULL, '2023-01-18 07:02:31', '2023-01-18 07:22:17', NULL),
('68d3825762e94da3aabbb6de96bdb4f9', 'Ferdy Hasan Mubarok, S.Pd., M.Pd.', '2010-06-16', 'Lampung', 'WNI', 'lampung', '0', '0', 'ferdyhasan@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default.jpg', 'https://web.aisystem.id/penerbitan/penulis/mengubah-penulis/68d3825762e94da3aabbb6de96bdb4f9', 'bank toyib', 'ferdy mubarok', '12346', '02.456.413.2-254.546', '5661223125645646', 'uqyPJF8rqRRDp3u5l37ACD4PmGy1LI8tu1zSgvyU.jpg', 'S9mlrixoe71QuLj9jPleE7nGhA7CrwTDwA2lDIIC.jpg', 'dc4547d5a820478188ae9c1a1ae876a6', 'dc4547d5a820478188ae9c1a1ae876a6', NULL, '2023-01-17 07:16:41', '2023-01-17 07:41:23', NULL),
('74deca8d129042a9aa23c82aceee89f5', 'Nidhom Khoeron', '1992-12-31', 'Cirebon', 'WNI', 'Desa.Waringin, Kec.Ciwaringin, Kab.Cirebon 45167', '0838 2311 9266', NULL, ' w04.memory@gmail.com', NULL, 'Pengajar Private, Penulis Buku', NULL, NULL, 'Nidhom Khoeron', '@nidhom.kho', 'Nidh Notes', NULL, 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-06-09 03:35:14', '2022-10-14 07:52:42', NULL),
('785df60d4d834d1f8adfeb375e859360', 'Dr. Ulfia Rahmi, M.Pd.', '1987-05-24', 'Batuhampa', 'WNI', 'Perumahan Lubuk Sejahtera Lestari, Blok.Garniti No.8, Lubuk Buaya, Kota.Padang', '081363392202', NULL, 'ulfia@fip.unp.ac.id', 'Fakultas Ilmu Pendidikan - Universitas Negeri Padang', 'Dosen', 'Fakultas Ilmu Pendidikan - Universitas Negeri Padang : Jl.Prof.Dr.Hamka, Kota Padang, Prov.Sumatera Barat.', '0751-7058698', NULL, NULL, NULL, NULL, 'default.jpg', NULL, 'BNI', 'Ulfia Rahmi', '0573291905', '70.926.002.0-201.000', '1307136405870003', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-10 02:21:51', NULL, NULL),
('90d2a75954c0442bb9f6b2578e83fc8b', 'Lorem Ipsum', '1985-03-15', 'Surabaya', 'WNI', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry', '085743172451', '0274551155', 'lorem@gamil.com', 'Andi Offset', 'Staff', 'Jl Beo, Condong catur. Sleman', '0274558787', 'Lorem Ipsum', '@loremipsum', '@loremipsum', NULL, 'r6OlOVcNRmaPSGSOtHyEhU3VhVEoanXtxfKWANdJ.jpg', NULL, 'CDB', 'Lorem Ipsum', '88123321', '4777123890', '3471031704900005', 'knov8tsqXdnirPjRblMapTU8FQqOtzNao89v5sEa.png', 'ISxxQm249EIWbeOdGBdFLG6KnpyF9GD3t0lA4VL4.png', 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-03-18 06:26:59', '2022-05-30 04:22:48', NULL),
('986a4833c1c2406193299452bf7c3e91', 'Test Image', '2022-06-02', 'Yogyakarta', 'WNI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'p6TErMkr13ZbR3Q0gexBtDmUDg25nZgHpYukXAwJ.jpg', NULL, NULL, NULL, NULL, NULL, NULL, 'OepSJwJAbAUCrXbyTShowYtgAEfaLLNXWEs5D0EZ.jpg', 'ntf0eB938LLZvnPnp50wqtuyuLTKxpDSSCxtEtsN.jpg', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-06-03 06:42:21', NULL, NULL),
('b66053c746b04329836d4f121ab7c06f', 'Mandriwati, G.A.', '1992-12-31', 'Yogyakarta', 'WNI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-09 04:14:35', NULL, NULL),
('d0789ba314354de4926b549233008ef6', 'JK Rowling Door', '1994-03-02', 'Inggris', 'WNA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PXzUEK5uHNaUSQxu842S09ZL0Neub4XlLRTuh8v6.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-05-30 02:09:52', NULL, NULL),
('db307ea965334f6f8284e46d820dd5a1', 'Diana ; Fandy Tjiptono', '2022-06-30', 'Yogyakarta', 'WNI', NULL, NULL, NULL, 'fandy.tjiptono@vuw.ac.nz', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-09 02:47:32', NULL, NULL),
('e42ff33b548b43538f610935d9dde775', 'Ridi Ferdiana', '1983-10-20', 'Cirebon', 'WNI', 'Jl.Kaliurang Km.7, Gg.Melati 2 No.48, Babadan Baru, Sleman, Yogyakarta', '0856 280 3939', NULL, 'ridi@ugm.ac.id', 'Fakultas Teknik Universitas Gadjah Mada', NULL, 'Jl.Grafika No.2, DTETI UGM', NULL, NULL, NULL, NULL, NULL, 'default.jpg', NULL, 'BCA', 'Ridi Ferdiana', '0372408365', '67 476 484 0 542 000', '3404072010830016', '5kSoFAjckAHUMILnyRZrDb0m7rFk3DgJo7XCWMec.jpg', 'kKQeN4ilB7MR52G3PfLEiFTQ4wlMZhf7IqnhVaK6.jpg', '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-08 04:53:33', NULL, NULL);

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
  `catatan` text,
  `created_by` varchar(36) DEFAULT NULL,
  `updated_by` varchar(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penerbitan_pn_direksi`
--

INSERT INTO `penerbitan_pn_direksi` (`id`, `naskah_id`, `judul_final`, `sub_judul_final`, `keputusan_final`, `catatan`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
('11cf826afbf74a56ba83f07a030643b6', '1a71b26e23094c39a62f6c8d62ec6665', '', '', 'Reguler', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', '37aee684a9e447a6bef36cbf08222d5d', NULL, '2022-07-22 03:15:41', NULL),
('4467c288a9e54bd1a8bf39d3908fe475', '334ddadb6c144d3cac9449be04dc3887', 'AKU dan KAMU', 'jadi satu', 'Reguler', 'test', '9f72c952b9ea4474a912d619cba5d0a8', NULL, '2023-01-13 09:17:34', NULL),
('64d42318a9124393860cb7f24d521b46', 'f9e5451153bb4f6ab910dfee4e2668b0', 'Origami Boneka Jari', 'dilengkapi dengan sains yang menyenangkan', 'Reguler', 'ok', '9f72c952b9ea4474a912d619cba5d0a8', NULL, '2023-01-18 07:14:29', NULL),
('67add8101bd5403bbbfb7082bb8ad890', '1a97e49ef92945d58bbc26b388771d9c', 'Mentjari Oeang', 'Begadang bukan solusi', 'Reguler', 'cetak', 'ee2c544aa4dc4c1eb12472cd84406358', NULL, '2022-10-28 20:33:14', NULL),
('7628596d00fa4adeabfa830aa0159503', 'fd9c40ad3c62499e9e9b10af7bfae40c', 'Manajemen Keuangan & Bisnis', 'Manajerial trainner', 'Reguler', 'Silahkan konfirmasi laagi', 'ee2c544aa4dc4c1eb12472cd84406358', NULL, '2022-10-26 10:30:56', NULL),
('e39ac6abd1ee49fab2283113e03d49fa', '5ace04aefcf14f1d968d5435091880dd', 'Aku lebih', 'aku', 'Reguler', 'masuk', '9f72c952b9ea4474a912d619cba5d0a8', NULL, '2023-01-17 07:31:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `penerbitan_pn_editor_setter`
--

CREATE TABLE `penerbitan_pn_editor_setter` (
  `id` varchar(36) NOT NULL,
  `naskah_id` varchar(36) NOT NULL,
  `penilaian_editor_umum` text,
  `penilaian_bahasa` enum('Baik','Cukup','Kurang') DEFAULT NULL,
  `catatan_bahasa` text,
  `penilaian_sistematika` enum('Baik','Cukup','Kurang') DEFAULT NULL,
  `catatan_sistematika` text,
  `penilaian_konsistensi` enum('Baik','Cukup','Kurang') DEFAULT NULL,
  `catatan_konsistensi` text,
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
  `pic` enum('M','D') NOT NULL,
  `prospek_pasar` text,
  `potensi_dana` text,
  `ds_tb` text,
  `pilar` text,
  `potensi` text,
  `created_by` varchar(36) DEFAULT NULL,
  `updated_by` varchar(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penerbitan_pn_pemasaran`
--

INSERT INTO `penerbitan_pn_pemasaran` (`id`, `naskah_id`, `pic`, `prospek_pasar`, `potensi_dana`, `ds_tb`, `pilar`, `potensi`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
('06027e13dec942bab9791c7de662a164', '95d9ebcd691e477eaeaf7c1aba4dd04d', 'M', 'baik', 'bisa', '[\"DS\"]', '[\"TOKO BUKU\",\"MOU PERORANGAN BUKU UMUM\",\"MOU PERORANGAN BUKU ROHANI\"]', NULL, 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', '2022-10-26 03:22:23', '2022-10-26 03:22:36'),
('1456fd469b7441ccbb836cb14aa777ba', 'dc29daf30b16470baebd899424abf819', 'M', 'Sangat Bisa', 'Tinggi', '[\"TB\"]', '[\"PERPUSTAKAAN\",\"BUKU PENDAMPING SMA\",\"PAUD DAN TK\"]', NULL, '7ff48adfec9b44b4bfce7173c012a8c7', NULL, '2022-10-27 09:00:17', NULL),
('14b74e7622524891aaa7ced06d43efb2', 'f56c0df2ccd24207b5a18e660bcf6f1a', 'M', 'Sangat Menjual', 'Tinggi', '[\"DS\"]', '[\"MOU KAMPUS\",\"PROYEK\",\"ONLINE\"]', NULL, '7ff48adfec9b44b4bfce7173c012a8c7', NULL, '2022-10-27 09:03:07', NULL),
('1501c8a2d6de4d819707f5ee222d8428', 'fd9c40ad3c62499e9e9b10af7bfae40c', 'D', 'Cukup menarik', 'bisa dikurangi', 'bisa', 'boleh', NULL, 'c94ad7236255430b82c0546dd82b917e', NULL, '2022-10-26 09:33:09', NULL),
('1de7a872e6174a4b8a8ab2753fbe36bd', '1a71b26e23094c39a62f6c8d62ec6665', 'M', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '[\"TB\"]', '[\"MOU KAMPUS\",\"BUKU SMK\",\"BUKU PAK\",\"BUKU HET SD\"]', NULL, '5090c6d9e50449449b2edf23db64cdf5', NULL, '2022-07-22 02:33:54', NULL),
('4149aab55ed7466e88feb5645d03b06c', '1a71b26e23094c39a62f6c8d62ec6665', 'M', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '[\"DS\"]', '[\"MOU KAMPUS\",\"MOU PERORANGAN BUKU UMUM\",\"MOU PERORANGAN BUKU ROHANI\"]', NULL, 'ba7f70e69bf74fc29fe3154980f5f53e', NULL, '2022-07-22 02:46:53', NULL),
('43ed8d45174c45a88f936e2c06b9afa8', 'f56c0df2ccd24207b5a18e660bcf6f1a', 'M', 'Bisa Menguntungkan', 'Luas', '[\"TB\"]', '[\"BUKU PAK\",\"BUKU PENDAMPING SMA\",\"BUKU PENDAMPING SD\",\"INDOPUSTAKA\"]', NULL, '214af5d98cc74484be4ef05aa56a9e45', NULL, '2022-10-27 09:05:11', NULL),
('468267f4b6224d1f8300766013b5621b', '1a97e49ef92945d58bbc26b388771d9c', 'D', 'Bisa dilakukan', 'Bisa diusahakan', 'Bisa', 'Ikut', NULL, 'c94ad7236255430b82c0546dd82b917e', NULL, '2022-10-28 20:27:09', NULL),
('4c252d93b9214ff4b39e0a91dcf1c5ce', 'f9e5451153bb4f6ab910dfee4e2668b0', 'M', 'Ok', '500 juta', '[\"DS\"]', '[\"TOKO BUKU\",\"BUKU PENGGERAK\"]', NULL, '214af5d98cc74484be4ef05aa56a9e45', NULL, '2023-01-18 07:08:50', NULL),
('59a95f85ac2b4aee8715325c4aebb85c', '334ddadb6c144d3cac9449be04dc3887', 'M', 'Bagus', 'Mantap', '[\"TB\"]', '[\"TOKO BUKU\",\"MOU PERORANGAN BUKU UMUM\"]', NULL, '7ff48adfec9b44b4bfce7173c012a8c7', NULL, '2023-01-13 09:08:37', NULL),
('5ebcd37b7f324efd97dd09473738f9da', '334ddadb6c144d3cac9449be04dc3887', 'M', 'Ini prospeknya Apik', '50.000 - 120000', '[\"TB\"]', '[\"TOKO BUKU\",\"PROYEK\"]', NULL, '214af5d98cc74484be4ef05aa56a9e45', NULL, '2023-01-13 09:09:17', NULL),
('62bfcd13972c40c29f27203b0805ca16', '334ddadb6c144d3cac9449be04dc3887', 'D', 'Bagus', 'Mantap 100 juta', 'Sip', 'Ini pilar', NULL, 'c94ad7236255430b82c0546dd82b917e', NULL, '2023-01-13 09:12:40', NULL),
('6de387c0b5a045a4be2fdc9e684f5546', 'dc29daf30b16470baebd899424abf819', 'M', 'Menjual', 'Lebih', '[\"DS\"]', '[\"LAIN-LAIN\",\"ONLINE\",\"PAMERAN\",\"BUKU HET SMP\"]', NULL, '214af5d98cc74484be4ef05aa56a9e45', NULL, '2022-10-27 08:51:22', NULL),
('7831bb6300e047e990faf909a5c9da8d', '1a97e49ef92945d58bbc26b388771d9c', 'M', 'Bagus Sekali', 'Rendah', '[\"DS\"]', '[\"TOKO BUKU\",\"PERPUSTAKAAN\",\"BUKU PENGGERAK\"]', NULL, '7ff48adfec9b44b4bfce7173c012a8c7', NULL, '2022-10-28 20:24:45', NULL),
('809445250a6e468ea1e88f947a7618c4', '5ace04aefcf14f1d968d5435091880dd', 'D', 'naskah ini bagus', 'ini akan menjadi luar biasa jika diterbitkan', 'OK', 'OK', NULL, 'c94ad7236255430b82c0546dd82b917e', NULL, '2023-01-17 07:31:08', NULL),
('91ce0af7822f453890a4b1a93466208f', 'dc29daf30b16470baebd899424abf819', 'M', 'Cukup baik', 'lumayan baik', 'TB', 'TB', NULL, 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', '2022-10-25 14:06:15', '2022-10-25 14:07:39'),
('9d37dafa6b704fcdb81751bd95e6c4ff', '1a97e49ef92945d58bbc26b388771d9c', 'M', 'Sangat Mendukung', 'Tinggi', '[\"TB\"]', '[\"PERPUSTAKAAN\",\"ONLINE\",\"BUKU PENGGERAK\"]', NULL, '214af5d98cc74484be4ef05aa56a9e45', NULL, '2022-10-28 20:22:42', NULL),
('a5e1da0252314e5db36eea0b6d1cecd1', '5707a19dd4804b25899e57b37b335ab6', 'M', 'Sangat baik', 'bisa saja', 'DS Tidak bisa', 'Boleh saja', NULL, 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', '2022-10-26 03:50:31', '2022-10-26 03:52:36'),
('a614541de4cf40b78e2d127ec4e06767', '1a71b26e23094c39a62f6c8d62ec6665', 'D', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', NULL, 'ef171a1a7bba4b81abdfe10ef8c6c0f8', NULL, '2022-07-22 03:11:56', NULL),
('bf866d6ff2564dc1837302820b1c7e0f', 'f9e5451153bb4f6ab910dfee4e2668b0', 'M', 'OK', 'ok', '[\"DS\"]', '[\"TOKO BUKU\"]', NULL, '7ff48adfec9b44b4bfce7173c012a8c7', NULL, '2023-01-18 07:09:20', NULL),
('da9c350c749641549415b72c1d91743c', 'fd9c40ad3c62499e9e9b10af7bfae40c', 'M', 'Sangat Good', 'ascsasdasdasd', '[\"TB\"]', '[\"TOKO BUKU\",\"MOU KAMPUS\"]', NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-10-26 06:57:27', NULL),
('dad7aeed1bc0424b819986e41e2f42f8', 'f9e5451153bb4f6ab910dfee4e2668b0', 'D', 'Jsgsns', 'Hsgsbsg', 'Bshsgs', 'Hshsgsgs', NULL, 'c94ad7236255430b82c0546dd82b917e', NULL, '2023-01-18 07:13:30', NULL),
('f6bf51b0be3740c3a330a0a71bd178f7', 'fd9c40ad3c62499e9e9b10af7bfae40c', 'M', 'Sangatlah prospek', 'asdasfa', '[\"TB\"]', '[\"TOKO BUKU\",\"MOU KAMPUS\"]', NULL, '214af5d98cc74484be4ef05aa56a9e45', NULL, '2022-10-26 06:59:33', NULL),
('fcf44d1865484c3b98bb5986469e9966', '5ace04aefcf14f1d968d5435091880dd', 'M', 'Sangat menarik', 'Besar', '[\"DS\",\"TB\"]', '[\"TOKO BUKU\",\"MOU KAMPUS\",\"MOU PERORANGAN BUKU UMUM\",\"PERPUSTAKAAN\"]', NULL, '7ff48adfec9b44b4bfce7173c012a8c7', NULL, '2023-01-17 07:23:05', NULL),
('fda7c00efe2b40a7a257a80478373b3c', '5ace04aefcf14f1d968d5435091880dd', 'M', 'Kedepanya akan baik mengingat PPKM sudah usai', '> 500 Juta', '[\"TB\"]', '[\"TOKO BUKU\",\"MOU KAMPUS\",\"PERPUSTAKAAN\",\"BUKU PENDAMPING SMA\"]', NULL, '214af5d98cc74484be4ef05aa56a9e45', NULL, '2023-01-17 07:23:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `penerbitan_pn_penerbitan`
--

CREATE TABLE `penerbitan_pn_penerbitan` (
  `id` varchar(36) NOT NULL,
  `naskah_id` varchar(36) NOT NULL,
  `penilaian_umum` text,
  `saran` enum('Diterima','Ditolak','Revisi','eBook') DEFAULT NULL,
  `catatan` text,
  `potensi` text,
  `tanggapan_usulan_judul` text,
  `created_by` varchar(36) DEFAULT NULL,
  `updated_by` varchar(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penerbitan_pn_penerbitan`
--

INSERT INTO `penerbitan_pn_penerbitan` (`id`, `naskah_id`, `penilaian_umum`, `saran`, `catatan`, `potensi`, `tanggapan_usulan_judul`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
('0e6316fd5c9f44b186afb8e399c95a4a', '334ddadb6c144d3cac9449be04dc3887', 'ok', 'Diterima', 'ok', NULL, NULL, 'dc306f83529445139c16dd55b4f0e6f3', NULL, '2023-01-13 09:06:40', NULL),
('10348b546608403494b42ff8f1280c86', '5707a19dd4804b25899e57b37b335ab6', 'lumayan', 'Diterima', NULL, 'masuk', NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-10-26 03:49:58', NULL),
('2083a1fb752a445cba69f17ccf34f6e2', 'f9e5451153bb4f6ab910dfee4e2668b0', 'ok', 'Diterima', 'ok', 'ok', NULL, 'dc306f83529445139c16dd55b4f0e6f3', NULL, '2023-01-18 07:11:03', NULL),
('71a5a4ef1ea949dda8fc5ec33fcf573a', '95d9ebcd691e477eaeaf7c1aba4dd04d', 'jos', 'Diterima', NULL, 'Bagus', NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-10-26 03:21:32', NULL),
('75875777ed914233b5e79f988e91e508', '5ace04aefcf14f1d968d5435091880dd', 'yo wis lah', 'Diterima', 'sipp', 'oks', NULL, 'dc306f83529445139c16dd55b4f0e6f3', NULL, '2023-01-17 07:30:06', NULL),
('8c8903b7605942dc9507556adbd9b51a', '1a97e49ef92945d58bbc26b388771d9c', NULL, 'Diterima', NULL, 'Lumayan Bagus Sekali', NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-10-28 20:21:06', NULL),
('960c2f1101914fad9ec4f1454f67edfb', 'f56c0df2ccd24207b5a18e660bcf6f1a', 'Bisa terbit', 'Diterima', NULL, 'Sangat Jos', NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-10-27 09:01:42', NULL),
('9d29d542d02842808f6fd15e38e5af7e', 'dc29daf30b16470baebd899424abf819', NULL, 'Diterima', NULL, 'Baik', NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-10-25 14:05:42', NULL),
('ffaed342512f465c8dc9da01a1bd5da0', '1a71b26e23094c39a62f6c8d62ec6665', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Diterima', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum', NULL, '4fc80f443bfb4969b9a0272d9be08ef2', NULL, '2022-07-22 03:15:14', NULL),
('fff48faa7dc64eedbea4f4280abbfc4a', 'fd9c40ad3c62499e9e9b10af7bfae40c', NULL, 'Diterima', NULL, 'Lumayan Bagus Sekali', NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-10-26 06:52:20', NULL);

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
  `isi_materi` text,
  `sasaran_keilmuan` text,
  `sasaran_pasar` text,
  `sumber_dana_pasar` text,
  `skala_penilaian` enum('Baik','Cukup','Kurang') NOT NULL,
  `saran` enum('Diterima','Ditolak','Revisi','eBook') NOT NULL,
  `potensi` text,
  `usulan_judul` text,
  `created_by` varchar(36) DEFAULT NULL,
  `updated_by` varchar(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penerbitan_pn_prodev`
--

INSERT INTO `penerbitan_pn_prodev` (`id`, `naskah_id`, `sistematika`, `nilai_keilmuan`, `kelompok_buku_id`, `isi_materi`, `sasaran_keilmuan`, `sasaran_pasar`, `sumber_dana_pasar`, `skala_penilaian`, `saran`, `potensi`, `usulan_judul`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
('16373f1a1f89436390ef88aafc8c5140', 'f9e5451153bb4f6ab910dfee4e2668b0', 'Baik', 'Baik', '488697c7556a413eb433806b35bb635f', 'Nsnsns', 'Hshshsh', 'Hshshsh', 'Semua bisa', 'Baik', 'Diterima', 'Jual di toko buku dan DS', NULL, 'ceadd9fb648445eab1e350357e51d1ce', NULL, '2023-01-18 07:08:09', NULL),
('22aa6275370245a0a3972119bd2da125', '5707a19dd4804b25899e57b37b335ab6', 'Baik', 'Baik', 'c16a55c4b1c54e12b3c79d292a340f84', 'Naskah seri Keselamatan di tempat umum ini terdiri dari 4 cerita, yaitu saat di Mal, di kebun binatang, di angkutan umum dan di kolam renang. Tema cukup menarik dan yang jelas dibutuhkan untuk anak-anak maupun orang tua. Yang perlu diperhatikan dalam pembahasannya (oleh penulis) adalah untuk mengecek penatalaksanaan penanganan jika ada darurat di tempat-tempat umum tersebut. Misalnya dengan melakukan survei atau riset kecil-kecilan di tempat-tempat yang menjadi background dari masing-masing cerita ini. Yaitu di mall, kebun binatang, angkutan umum maupun di kolam renang. Kali ini topik khusus keselamatan umum yang diangkat adalah SAAT DI KEBUN BINATANG, bercerita tentang Vino dan teman-teman sekelasnya yang bersama-sama sedang melakukan fieldtrip ke Kebun Binatang. Mereka diajak untuk menjaga kebersihan, tidak memberi makan sembarangan, tidak terlalu dekat dengan kandang hewan, dan menaati peraturan di kebun binatang tersebut, Hal yang masih belum ada dalam pembahasan disini adalah, untuk tidak menggoda satwa yang sedang tidur dan tidak bersikap apatis. Hal-hal tersebut mungkin perlu ditambahkan baik dalam alur cerita ataupun dalam Tips Aman Di Kebun Binatang, yang diberikan di akhir cerita.  Perlu dicek juga untuk ilustrasi dari masing-masing judul ini. Topik khusus di Kebun Binatang ini dalam seri ini cukup menarik. dibutuhkan dan cukup menjual, karena destinasi wisata untuk anak-anak yan paling sering dikunjungi salah satunya adalah Kebun Binatang.', 'anak usia 7+, para pendamping dan pendidik anak-anak, orang tua yang mempunyai anak balita dan masyarakat pada umumnya', 'toko buku, proyek dan sekolah', NULL, 'Baik', 'Revisi', 'Sangat baik', NULL, '072df3b932394d6caacb5c9c0960d42b', NULL, '2022-07-29 08:34:17', NULL),
('28cdc37c9cec4db08204f46bf39ce2b8', '1a71b26e23094c39a62f6c8d62ec6665', 'Baik', 'Cukup', '0d6b22630e41467a85f2764630b81033', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing', 'Baik', 'Diterima', 'enim ipsam voluptatem quia voluptas sit', NULL, 'e4ddf4d7c2b84cb69647f4dd63f9dbc2', NULL, '2022-07-22 03:13:39', NULL),
('6749ecec8cd4482ea15f2ce4a6c84451', 'fd9c40ad3c62499e9e9b10af7bfae40c', 'Baik', 'Baik', '3df79091d3814110917e84fb7df08227', 'Buku “Manajemen Keuangan” ini mengulas teori, konsep, dan aplikasi pokok pengelolaan keuangan  dalam konteks perusahaan. Pembahasan disampaikan dalam bahasa yang mudah dipahami dan disertai dengan contoh praktis. Struktur bab disusun secara sistematis ke dalam 5 bagian: (1) Konsep Dasar Manajemen Keuangan (apa dan bagaimana manajemen keuangan, pasar keuangan dan lembaga jasa keuangan, memahami laporan keuangan, menganalisis laporan keuangan, serta konsep nilai waktu uang); (2) Penilaian Sekuritas (risiko dan return, penilaian obligasi, dan penilaian saham); (3) Penilaian Investasi Jangka Panjang (biaya modal, capital budgeting, dan estimasi arus dan analisis risiko); (4) Keputusan Keuangan Strategik (struktur modal dan kebijakan dividen dan pembelian kembali saham); dan (5) Perencanaan Keuangan dan Modal Kerja (perencanaan keuangan dan manajemen modal kerja). Selain cocok diadopsi sebagai buku pegangan kuliah Manajemen Keuangan, buku ini juga dapat dijadikan panduan praktis bagi para praktisi maupun pemerhati keuangan untuk mempelajari prinsip dasar manajemen keuangan.', 'mata kuliah manajemen keuangan', 'mahasiswa ekonomi manajemen', 'mahasiswa ekonomi manajemen', 'Baik', 'Diterima', 'mahasiswa manajemen dan konsultan keuangan', '[{\"judul\":\"Manajemen Keuangan\"}]', 'a400e4bcf70d40d78224043cc95e6241', 'a400e4bcf70d40d78224043cc95e6241', '2022-06-30 21:39:22', '2022-06-30 21:40:00'),
('6d70658e0bab4bec90580b62673bfdc3', '5ace04aefcf14f1d968d5435091880dd', 'Cukup', 'Baik', '2d370bf6aa894cb2a0d1e657d98bf152', 'Menarik dan kekinian', 'Siapa saja', NULL, 'Semua bisa', 'Baik', 'Diterima', 'Jual di toko buku dan DS', NULL, 'ceadd9fb648445eab1e350357e51d1ce', NULL, '2023-01-17 07:25:19', NULL),
('6dc6cbeb8a584b3f9f5e5ce698384783', 'f56c0df2ccd24207b5a18e660bcf6f1a', 'Baik', 'Baik', 'c16a55c4b1c54e12b3c79d292a340f84', 'Naskah seri Keselamatan di tempat umum ini terdiri dari 4 cerita, yaitu saat di Mal, di kebun binatang, di angkutan umum dan di kolam renang. Tema cukup menarik dan yang jelas dibutuhkan untuk anak-anak maupun orang tua. Yang perlu diperhatikan dalam pembahasannya (oleh penulis) adalah untuk mengecek penatalaksanaan penanganan jika ada darurat di tempat-tempat umum tersebut. Misalnya dengan melakukan survei atau riset kecil-kecilan di tempat-tempat yang menjadi background dari masing-masing cerita ini. Yaitu di mall, kebun binatang, angkutan umum maupun di kolam renang. Kali ini topik khusus keselamatan umum yang diangkat adalah SAAT DI KOLAM RENANG, bercerita tentang Andin dan Kayla yang diajak oleh Kak Tami untuk menemani Kak Tami berlatih renang. Mereka berdua sambil menunggu kakaknya bermain di kolam renang bermain. Mereka berhati-hati dengan tidak berlari di kolam, menjaga kebersihan dengan tidak buang air di kolam renang serta tidak berenang di kolam dewasa. Ada beberapa hal yang perlu ditekankan lagi baik dalam cerita maupun dalam tips aman di akhir cerita, yaitu untuk tidak menggunakan perhiasan, melakukan pemanasan sebentar sebelum berenang serta menghindari tepi kolam.  Hal-hal tersebut mungkin perlu ditambahkan baik dalam alur cerita ataupun dalam Tips Aman  di akhir cerita.  Perlu dicek juga untuk ilustrasi dari masing-masing judul ini. Topik khusus di Kolam Renang ini dalam seri ini cukup menarik. dibutuhkan dan cukup menjual, karena banyak anak-anak yang suka bermain air, salah satunya adalah di kolam renang, selain di laut.', 'anak usia 6+, praktisi pendidikan dan pendamping anak, orang tua, dan masyarakat pada umumnya', 'toko buku, sekolah dan proyek', NULL, 'Baik', 'Diterima', 'Sangat baik', NULL, '072df3b932394d6caacb5c9c0960d42b', NULL, '2022-07-29 08:55:04', NULL),
('9d427d5341af4a5cbc6a470a58bd359d', '1a97e49ef92945d58bbc26b388771d9c', 'Baik', 'Cukup', '338668ba6f424786881b6e3db917be71', NULL, NULL, NULL, NULL, 'Baik', 'Diterima', NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', NULL, '2022-10-28 20:19:43', NULL),
('a8852a7a6ae24ceb9e0e426c58955310', '95d9ebcd691e477eaeaf7c1aba4dd04d', 'Baik', 'Baik', 'c16a55c4b1c54e12b3c79d292a340f84', 'Naskah seri Keselamatan di tempat umum ini terdiri dari 4 cerita, yaitu saat di Mal, di kebun binatang, di angkutan umum dan di kolam renang. Tema cukup menarik dan yang jelas dibutuhkan untuk anak-anak maupun orang tua. Yang perlu diperhatikan dalam pembahasannya (oleh penulis) adalah untuk mengecek penatalaksanaan penanganan jika ada darurat di tempat-tempat umum tersebut. Misalnya dengan melakukan survei atau riset kecil-kecilan di tempat-tempat yang menjadi background dari masing-masing cerita ini. Yaitu di mall, kebun binatang, angkutan umum maupun di kolam renang. Kali ini topik khusus keselamatan umum yang diangkat adalah SAAT DI ANGKUTAN UMUM, bercerita tentang Meta dan Tia yang akan pulang bersama menggunakan angkutan umum. Meta memperingatkan Tia untuk tidak bermain HP di angkot, tidak tidur di angkot dan menjaga untuk tidak sampai ketiduran di angkutan umum, untuk menghindari hal-hal yang tidak diinginkan serta memeriksa barang-barang yang dibawa jangan sampai tertinggal. Perlu dicek juga untuk ilustrasi dari masing-masing judul ini. Topik di angkutan umum ini  dalam seri ini cukup menarik meski mungkin sudah tidak terlalu populer karena sekarang lebih banyak menggunakan ojol. Tetapi topik ini masih cukup dibutuhkan dan cukup menjual, karena tetap bisa diterapkan menggunakan alat transportasi umum lainnya, seperti kereta api listrik, dll.', 'anak usia 6+, praktisi pendidikan dan pendamping anak, orang tua, dan masyarakat pada umumnya', 'toko buku, sekolah, proyek', NULL, 'Baik', 'Diterima', 'Sangat baik', NULL, '072df3b932394d6caacb5c9c0960d42b', NULL, '2022-07-29 09:21:06', NULL),
('b1a7ec6148df4d5898923fbf33f869be', 'dc29daf30b16470baebd899424abf819', 'Baik', 'Baik', 'c16a55c4b1c54e12b3c79d292a340f84', 'Naskah seri Keselamatan di tempat umum ini terdiri dari 4 cerita, yaitu saat di Mal, di kebun binatang, di angkutan umum dan di kolam renang. Tema cukup menarik dan yang jelas dibutuhkan untuk anak-anak maupun orang tua. Yang perlu diperhatikan dalam pembahasannya (oleh penulis) adalah untuk mengecek penatalaksanaan penanganan jika ada darurat di tempat-tempat umum tersebut. Misalnya dengan melakukan survei atau riset kecil-kecilan di tempat-tempat yang menjadi background dari masing-masing cerita ini. Yaitu di mall, kebun binatang, angkutan umum maupun di kolam renang. Untuk naskah dengan topik khusus keselamatan umum SAAT DI MALL, ini bercerita tentang dua orang anak yang saking asyiknya bermain di mall, hingga mereka akhirnya terpisah dari Pamannya yang mengajak mereka untuk bermain di Mall. Anak tersebut kemudian mencari petugas keamanan Mall (satpam) yang kemudian membantu mereka, dan akhirnya mereka dapat bertemu lagi dengan pamannya. Perlu dicek juga untuk ilustrasi dari masing-masing judul ini. Tetapi secara keseluruhan tema seri ini cukup menarik. dibutuhkan dan cukup menjual.', 'anak usia 7+, praktisi pendidikan, orang tua, dan masyarakat pada umumnya', 'proyek sekolah, perpustakaan, toko buku', 'Penerbitan', 'Baik', 'Diterima', 'Sangat baik', '[{\"judul\":\"Seri Keselamatan di Tempat Umum - Saat di Mal\"}]', '072df3b932394d6caacb5c9c0960d42b', '072df3b932394d6caacb5c9c0960d42b', '2022-06-09 01:39:32', '2022-07-29 01:10:45'),
('c0560b0cbc0d4700b6151f07a50dff2a', '266da2c74b774d45a6717421208134e1', 'Baik', 'Kurang', '0e3c62efe1c34fc595505860ddee1376', NULL, NULL, NULL, NULL, 'Cukup', 'Diterima', NULL, NULL, 'e83ca4537495486c8d3b5d7e6ae2407a', NULL, '2022-12-09 02:56:16', NULL),
('d1544f5baba34b2081f17e89a3617860', '334ddadb6c144d3cac9449be04dc3887', 'Baik', 'Baik', '2d370bf6aa894cb2a0d1e657d98bf152', 'Mantaaaappp bro', 'Bagus', 'Semua bisa', 'Semua bisa', 'Baik', 'Diterima', 'Jual di toko buku dan DS', NULL, 'ceadd9fb648445eab1e350357e51d1ce', NULL, '2023-01-13 09:05:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `penerbitan_pn_stts`
--

CREATE TABLE `penerbitan_pn_stts` (
  `id` varchar(36) NOT NULL,
  `naskah_id` varchar(36) NOT NULL,
  `tgl_input_admin` timestamp NULL DEFAULT NULL,
  `tgl_naskah_masuk` timestamp NULL DEFAULT NULL,
  `tgl_pn_prodev` timestamp NULL DEFAULT NULL,
  `tgl_pn_editor` timestamp NULL DEFAULT NULL,
  `tgl_pn_setter` timestamp NULL DEFAULT NULL,
  `tgl_pn_m_pemasaran` timestamp NULL DEFAULT NULL,
  `tgl_pn_m_penerbitan` timestamp NULL DEFAULT NULL,
  `tgl_pn_d_pemasaran` timestamp NULL DEFAULT NULL,
  `tgl_pn_direksi` timestamp NULL DEFAULT NULL,
  `tgl_pn_selesai` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penerbitan_pn_stts`
--

INSERT INTO `penerbitan_pn_stts` (`id`, `naskah_id`, `tgl_input_admin`, `tgl_naskah_masuk`, `tgl_pn_prodev`, `tgl_pn_editor`, `tgl_pn_setter`, `tgl_pn_m_pemasaran`, `tgl_pn_m_penerbitan`, `tgl_pn_d_pemasaran`, `tgl_pn_direksi`, `tgl_pn_selesai`) VALUES
('32a56027965441dba8efda35d0b0ae9f', '1a58f15bdf304d28a4063808a512cb8b', '2022-06-09 03:42:59', '2022-06-08 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('134862de60dd4ac0985dedbfa9e84413', '1c95c52a37984a51a2b80874f7e482d5', '2022-06-09 03:40:07', '2022-06-08 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('f1903d92e2124737bb48f771bf481a62', '2aea9efddc344fb8b8c03164d4fba843', '2022-06-08 06:43:02', '2022-06-07 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('6cfa48cc2cc44a459a0933ac19a92517', '36504ee1023f4bf0be272593c5431669', '2022-06-13 02:58:39', '2022-06-08 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('3a64480c8b2d4f3ab68a986e432a2935', '3b6f233968394094976bcfcd116817df', '2022-06-09 03:41:41', '2022-06-08 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('ad390c2261644c04ab0d827bd7831302', '4430fe9a3ae5499c8759d3225f0aa7b5', '2022-06-13 02:59:39', '2022-06-08 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('456ef138f6a54356ae534f1e26db5e12', '5707a19dd4804b25899e57b37b335ab6', '2022-06-09 01:55:46', '2022-06-07 17:00:00', '2022-07-29 01:34:17', NULL, NULL, NULL, '2022-10-26 03:49:58', NULL, NULL, NULL),
('afa9189cb2924186af57e140b5d07d2a', '6318ede7909649eca9394fd844dfdd51', '2022-06-08 06:40:29', '2022-06-07 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('807b5b5d333e4795ac52965297014564', '95d9ebcd691e477eaeaf7c1aba4dd04d', '2022-06-09 01:56:53', '2022-06-07 17:00:00', '2022-07-29 02:21:06', NULL, NULL, NULL, '2022-10-26 03:21:32', NULL, NULL, NULL),
('ec6b88e193234473b7a2d8754acd4a51', '9c8132c2ebc34a3195c3aaca81095f3c', '2022-06-13 03:01:16', '2022-06-08 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('dbf488fdb55b4e639a2aaac4ff70cad6', 'aeb27fc7ab75452a8bfdc5d014b63d83', '2022-06-08 05:00:26', '2022-06-29 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('8d8f906c0ae64cc98e52c50dd0ef4464', 'be20459513994ceea0a19f191d947ed0', '2022-06-13 03:00:22', '2022-06-08 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('66427a29b87345168afb822db133256f', 'dc29daf30b16470baebd899424abf819', '2022-06-09 01:54:44', '2022-06-07 17:00:00', '2022-06-09 01:39:32', NULL, NULL, '2022-10-27 09:00:17', '2022-10-25 14:05:42', NULL, NULL, NULL),
('857b57c405d547eea4dd7d4e041ae91c', 'e75dd02c02154c44b142bfa7bb92049c', '2022-06-13 02:57:14', '2022-06-08 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('7c2cc21bcd9c42deb5e0c5b33485e5c6', 'f56c0df2ccd24207b5a18e660bcf6f1a', '2022-06-09 01:57:38', '2022-06-07 17:00:00', '2022-07-29 01:55:04', NULL, NULL, '2022-10-27 09:05:11', '2022-10-27 09:01:42', NULL, NULL, NULL),
('f7610c986e5547da8aedbf42c4a2b4e1', 'fd9c40ad3c62499e9e9b10af7bfae40c', '2022-06-09 03:03:18', '2022-06-08 17:00:00', '2022-06-30 21:39:22', NULL, NULL, '2022-10-26 06:59:33', '2022-10-26 06:52:20', '2022-10-26 09:33:09', '2022-10-26 10:30:56', '2022-10-26 10:30:56'),
('7720778b3fbe4985a6caa389eb9542cf', '7efc9508128a4bec9ba4bd1140085ca3', '2022-09-09 10:21:20', '2022-09-09 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-09 10:21:20'),
('930b73d33a8445f7b933643ad967aa5f', 'b9614d8eb16a40cb871a589f23507e19', '2022-07-27 15:23:22', '2022-07-25 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('9e81321a28cb4d3b91e9c39541f62547', '1a71b26e23094c39a62f6c8d62ec6665', '2022-07-22 09:32:45', '2022-07-06 00:00:00', '2022-07-22 10:13:39', NULL, NULL, '2022-07-22 09:46:53', '2022-07-22 10:15:14', '2022-07-22 10:11:56', '2022-07-22 10:15:41', '2022-07-22 10:15:41'),
('8cd52b19503448a9965bf25519115419', '53ab006dae6b4fbab6b5077ded2751f7', NULL, '2022-09-12 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-12 02:59:02'),
('689621c52584453087a8c367e28cb757', '16fdb6f329c544c6824cf85ec38501b7', NULL, '2022-09-13 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-14 02:58:18'),
('3c365640aa224ca18ad9d3c9c2cd7e7c', '266da2c74b774d45a6717421208134e1', NULL, '2022-09-22 17:00:00', '2022-12-09 02:56:16', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('f93964b1198543c490ab26a33e0a51e8', '84df36f9b0074c9e93028e3e2257a7d1', NULL, '2022-10-25 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-26 03:28:58'),
('4f21e2cd28ca4a71b86978b05fef8787', 'a8d7134ffedb4bf69b551074195ecce6', NULL, '2022-10-26 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('d957c25e80da4c619483efb4e5c0ed9b', '7067273f1d904108981ed70925b3728e', NULL, '2022-10-25 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('c66b71843495493b943bfde4f0469054', 'e97bb54ed5b64fc5aab1f6d435e2aaa1', NULL, '2022-10-25 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('e2d87f6e0f7b409ba5ded961d8fd5733', '286e171699af495abeca62d8f2a84160', NULL, '2022-10-27 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('7f59a6ab7c6c46f28a5caee7cfe5887d', '1a97e49ef92945d58bbc26b388771d9c', NULL, '2022-10-29 17:00:00', '2022-10-28 20:19:43', NULL, NULL, '2022-10-28 20:24:45', '2022-10-28 20:21:06', '2022-10-28 20:27:09', '2022-10-28 20:33:15', '2022-10-28 20:33:15'),
('6bdd5ba9c7bd4f64989bbd0c91dc9317', '886da09d771444279a9075ebc85a1e66', NULL, '2022-11-02 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('19d52c70f44049dda8dcea3db159f5e9', '6e200c9fa08443e7b9338efbcee16d44', NULL, '2022-11-02 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('382332f057ba48efb255fa660a7c5206', 'be2c0f93a0db462a81f69081b4991a5a', NULL, '2022-11-02 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2bbdcd69ffe44ab5925b798c91af28bb', 'b6a2fd8a6e8a43d589dad51cdfcd71c5', NULL, '2022-12-07 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-02 08:44:57'),
('786be306d6c04451a756a7472250ce52', '334ddadb6c144d3cac9449be04dc3887', NULL, '2023-01-06 17:00:00', '2023-01-13 09:05:22', NULL, NULL, '2023-01-13 09:09:17', '2023-01-13 09:06:40', '2023-01-13 09:12:40', '2023-01-13 09:17:34', '2023-01-13 09:17:34'),
('f277beefdbb64371b6887f58ea75ee38', '5ace04aefcf14f1d968d5435091880dd', NULL, '2022-12-31 17:00:00', '2023-01-17 07:25:19', NULL, NULL, '2023-01-17 07:23:22', '2023-01-17 07:30:06', '2023-01-17 07:31:08', '2023-01-17 07:31:56', '2023-01-17 07:31:56'),
('7b1c549c79734897b730b8c89e5ec6c7', 'f9e5451153bb4f6ab910dfee4e2668b0', NULL, '2023-01-17 17:00:00', '2023-01-18 07:08:09', NULL, NULL, '2023-01-18 07:09:20', '2023-01-18 07:11:03', '2023-01-18 07:13:30', '2023-01-18 07:14:29', '2023-01-18 07:14:29');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` varchar(36) NOT NULL,
  `access_id` varchar(36) NOT NULL,
  `url` varchar(200) NOT NULL,
  `type` enum('Create','Read','Update','Delete','Approval','Decline') NOT NULL,
  `raw` varchar(255) DEFAULT NULL COMMENT '!: Hardcode di script',
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `access_id`, `url`, `type`, `raw`, `name`) VALUES
('053eae77-6c72-11ed-9e64-4cedfb61fb39', '365190039fb44c8ab629806a5490addf', '', 'Create', 'otorisasi-setter-praset-mou', 'Otorisasi Setter MOU'),
('068adb0171304c628b267874004d7e8c', '8bc1be5db97545e2ab1c79e0d68d4896', '', 'Read', 'lihat-platform-digital', 'Lihat Platform Digital'),
('09179170e6e643eca66b282e2ffae1f8', '70410774a1e0433bb213a9625aceb0bb', '', 'Approval', 'Persetujuan Penerbitan', 'Persetujuan Cetak Dep Penerbitan'),
('0ce44192fb05400fb51f33c3c7a3d601', 'be061671a86c4437803f7c225e117799', '', 'Read', 'lihat-pracetak-designer', 'Lihat Pracetak Designer'),
('0d9c8667ccb34e9da275e7dce09d9cd9', '3dbad039493241aa8ed0c698d07ee94d', '', 'Update', 'ubah-format-buku', 'Ubah Format Buku'),
('1098a56970114e18898367d334658b47', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/mengubah-naskah', 'Update', 'ubah-data-naskah', 'Ubah Data Naskah'),
('12b852d92d284ab5a654c26e8856fffd', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/penilaian', 'Update', 'naskah-pn-mpenerbitan', 'Penilaian M.Penerbitan'),
('171e6210418440a8bf4d689841d0f32c', 'bfb8b970f85c4a42bac1dc56181dc96b', '', 'Approval', 'Penerbitan', 'Persetujuan E-book Dep Penerbitan'),
('1b89744217b04f79a8c1d7a967a46912', 'fb6c8f0dcc9e43199642f08a0fe1fd56', 'penerbitan/penulis', 'Read', 'lihat-penulis', 'Lihat Data Penulis'),
('1c1940da68fa4f8ba2325e83c303c47c', '131899f9a9204e0baa1b23cd2eedff6a', 'manajemen-web/user', 'Update', 'ubah-data-user', 'Ubah Data User'),
('1f4e5b3752b8475cb5261940ef62532d', 'fb6c8f0dcc9e43199642f08a0fe1fd56', 'penerbitan/penulis/membuat-penulis', 'Create', 'tambah-data-penulis', 'Buat Data Penulis'),
('25b1853c-6952-11ed-9234-4cedfb61fb39', '365190039fb44c8ab629806a5490addf', '', 'Create', 'ubah-atau-buat-setter-mou', 'Kabag Pracetak Setter MOU'),
('26a74e3097b94bd882bd1a9f6feace68', 'e32aa5bb41144ac58f2e6eeca81604ac', '', 'Create', 'ubah-atau-buat-des-final', 'Buat/Ubah Deskripsi Final'),
('276682372c5c45eca2139b32e4e5cc7a', 'be061671a86c4437803f7c225e117799', '', 'Create', 'otorisasi-korektor-prades-smk', 'Otorisasi Korektor SMK'),
('28c3460bb5cf4c618ba8ec6f3c12ddbd', '31a0187d88d94ddc83db4b71524b5b2d', '', 'Delete', 'hapus-kelompok-buku', 'Hapus Kelompok Buku'),
('29feb750adfa496fa4822fadd4ac1367', '92463f9e96394c19a979a3290fde5745', '', 'Create', 'otorisasi-editor-editing-reguler', 'Otorisasi Editor Reguler'),
('2a6ddda5-6c74-11ed-9e64-4cedfb61fb39', '365190039fb44c8ab629806a5490addf', '', 'Create', 'otorisasi-korektor-praset-reguler', 'Otorisasi Korektor Setting Reguler'),
('2ad0efee-6c72-11ed-9e64-4cedfb61fb39', '365190039fb44c8ab629806a5490addf', '', 'Create', 'otorisasi-setter-praset-smk', 'Otorisasi Setter SMK'),
('2b6032ef8a73463ba2c761c86be5ed5d', '8bc1be5db97545e2ab1c79e0d68d4896', '', 'Create', 'buat-platform-digital', 'Buat Platform Digital'),
('2c2753d3-6951-11ed-9234-4cedfb61fb39', '365190039fb44c8ab629806a5490addf', '', 'Create', 'ubah-atau-buat-setter-reguler', 'Kabag Pracetak Setter Reguler'),
('2e5924c8cc0e444dae36bafd2c89d727', 'be061671a86c4437803f7c225e117799', '', 'Create', 'otorisasi-kabag-prades-mou', 'Otorisasi Kabag MOU'),
('2ea1d4e7a4ae4677a0fc85b859cc5738', '365190039fb44c8ab629806a5490addf', '', 'Read', 'lihat-pracetak-setter', 'Lihat Pracetak Setter'),
('33c3711d787d416082c0519356547b0c', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/penilaian', 'Update', 'naskah-pn-setter', 'Penilaian Setter'),
('358a13267bcb4608a14c851c3010f79b', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/membuat-naskah', 'Create', 'tambah-data-naskah', 'Tambah Data Naskah'),
('38645f82ae7c468abad1ab191e7a8ad9', '131899f9a9204e0baa1b23cd2eedff6a', 'manajemen-web/users', 'Read', NULL, 'Lihat Data Users'),
('3a70433b-16f5-11ed-ae5c-1078d2a38ee5', 'bc5eb3aa02394dcca7692764e1328cee', '', 'Create', 'tambah-data-imprint', 'Buat Data Imprint'),
('3afa314e14904a1da386b2d8ede3582b', 'e32aa5bb41144ac58f2e6eeca81604ac', '', 'Approval', 'action-progress-des-final', 'Action Progress Des.Final'),
('405c0fcdbef14d49abd9ffcc53984c6e', '6b6e6377467d4b67911ef1b915244ed2', '', 'Read', 'lihat-deskripsi-turuncetak', 'Lihat Deskripsi Turun Cetak'),
('457aca55-6952-11ed-9234-4cedfb61fb39', '365190039fb44c8ab629806a5490addf', '', 'Create', 'ubah-atau-buat-setter-smk', 'Kabag Pracetak Setter SMK'),
('4943c707-1e08-11ed-87ce-1078d2a38ee5', '5646908e-1e06-11ed-87ce-1078d2a38ee5', '', 'Read', 'lihat-proses-produksi', 'Lihat Data Proses Produksi'),
('4b3413e795944f48af97085069fb6855', 'be061671a86c4437803f7c225e117799', '', 'Create', 'otorisasi-korektor-prades-reguler', 'Otorisasi Korektor Reguler'),
('4bb845580b464d7db3d7c3b3e4fd213b', '4e1627c1489844f985cbe2c485b2e162', 'manajemen-web/struktur-ao', 'Read', NULL, 'Lihat Struktur AO'),
('4cea10b3a4434bc3b342407a78a9ab2a', 'bfb8b970f85c4a42bac1dc56181dc96b', '', 'Approval', 'Marketing & Ops', 'Persetujuan E-book Dep Marketing Ops'),
('4d64a842e08344b9aeec88ed9eb2eb72', '70410774a1e0433bb213a9625aceb0bb', '', 'Approval', 'Persetujuan Marketing & Ops', 'Persetujuan Cetak Dep Marketing Ops'),
('539f5991-6c74-11ed-9e64-4cedfb61fb39', '365190039fb44c8ab629806a5490addf', '', 'Create', 'otorisasi-korektor-praset-mou', 'Otorisasi Korektor Setting MOU'),
('569c1d340cea4b21a54910177eeaf51f', 'bd09e803c41245a49ef23987c27b20ac', '', 'Read', 'lihat-deskripsi-produk', 'Lihat Deskripsi Produk'),
('5a009edf56ba4c64a7df1820a5fea34f', '70410774a1e0433bb213a9625aceb0bb', '', 'Approval', 'Persetujuan Keuangan', 'Persetujuan Cetak Dep Keuangan'),
('5a1bd42cca6f412cb1795a1aeddac2fe', 'bd09e803c41245a49ef23987c27b20ac', '', 'Approval', 'action-progress-des-produk', 'Action Progress Des.Produk'),
('5d793b19c75046b9a4d75d067e8e33b2', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/penilaian', 'Update', 'naskah-pn-editor', 'Penilaian Editor'),
('60b2f2ca-16f5-11ed-ae5c-1078d2a38ee5', 'bc5eb3aa02394dcca7692764e1328cee', '', 'Update', 'ubah-data-imprint', 'Ubah Data Imprint'),
('63759b4b-663c-11ed-94ad-4cedfb61fb39', '92463f9e96394c19a979a3290fde5745', '', 'Create', 'otorisasi-copyeditor-editing-reguler', 'Otorisasi Copy Editor Reguler'),
('683c46bd6f8d48e286f0da3767098c2e', 'be061671a86c4437803f7c225e117799', '', 'Create', 'otorisasi-kabag-prades-reguler', 'Otorisasi Kabag Reguler'),
('6903e82e7e94478f87df3cf80de6b587', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah', 'Read', NULL, 'Lihat Data Naskah'),
('6b4e3b36783d4a488101da7639c40de0', '71d6b5671ebb4e128215fccc458fbf09', '', 'Read', 'lihat-deskripsi-cover', 'Lihat Deskripsi Cover'),
('6effe04d-8fd9-11ed-a315-4cedfb61fb39', '6b6e6377467d4b67911ef1b915244ed2', '', 'Approval', 'pilih-penerbitan-turcet', 'Pilih Penerbitan Cetak/Ebook'),
('7527e84e47f94304b39525fa770dd904', 'bd09e803c41245a49ef23987c27b20ac', '', 'Create', 'ubah-atau-buat-des-produk', 'Buat/Ubah Deskripsi Produk'),
('78712deb909d4d88af7f098c0fcf6857', 'bfb8b970f85c4a42bac1dc56181dc96b', '', 'Approval', 'Keuangan', 'Persetujuan E-book Dep Keuangan'),
('7d574866-6c74-11ed-9e64-4cedfb61fb39', '365190039fb44c8ab629806a5490addf', '', 'Create', 'otorisasi-korektor-praset-smk', 'Otorisasi Korektor Setting SMK'),
('808ab7987c9b4f0ab025b1b9e3ed1d43', '92463f9e96394c19a979a3290fde5745', '', 'Read', 'lihat-editing', 'Lihat Editing'),
('8791f143a90e42e2a4d1d0d6b1254bad', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/penilaian', 'Update', 'naskah-pn-direksi', 'Penilaian Direksi'),
('87b2c263-663c-11ed-94ad-4cedfb61fb39', '92463f9e96394c19a979a3290fde5745', '', 'Create', 'otorisasi-copyeditor-editing-smk', 'Otorisasi Copy Editor SMK'),
('88f281e83aff47d08f555a2961420bf5', '92463f9e96394c19a979a3290fde5745', '', 'Create', 'ubah-atau-buat-editing-reguler', 'Kabag Editor Reguler'),
('89bc4b0ef1dd4306a3217cbf24551071', 'fb6c8f0dcc9e43199642f08a0fe1fd56', 'penerbitan/penulis/hapus-penulis', 'Delete', 'hapus-data-penulis', 'Hapus Data Penulis'),
('8a6141a082554335a2137c90f9fa0a5e', '31a0187d88d94ddc83db4b71524b5b2d', '', 'Create', 'buat-kelompok-buku', 'Buat Kelompok Buku'),
('8b3d0b17c9a045fbb76600e5044b0121', '71d6b5671ebb4e128215fccc458fbf09', '', 'Create', 'ubah-atau-buat-des-cover', 'Buat/Ubah Deskripsi Cover'),
('8baa9163-16f5-11ed-ae5c-1078d2a38ee5', 'bc5eb3aa02394dcca7692764e1328cee', '', 'Delete', 'hapus-data-imprint', 'Hapus Data Imprint'),
('8d9b1da4234f46eb858e1ea490da6348', '583a723cf036449d80d3742dcf695e38', 'penerbitan/naskah/timeline', 'Update', 'timeline-naskah-update-date', 'Ubah Tanggal Timeline Naskah'),
('8de7d59a74f345a5bcab20ec43376299', '30d0f70435904ad5b4e7cbfeb98fc021', '', 'Update', 'notifikasi-email-penulis', 'Notifikasi kirim Email Ke Penulis'),
('8f53727c763849aab80c1513505decf8', 'bfb8b970f85c4a42bac1dc56181dc96b', '', 'Approval', 'Direktur Utama', 'Persetujuan E-book Dir. Utama'),
('8f6fb226ee414c5ea7afd565cf099d7d', 'be061671a86c4437803f7c225e117799', '', 'Create', 'otorisasi-kabag-prades-smk', 'Otorisasi Kabag SMK'),
('99a7a50e866749879f55b92df2b5449c', 'bd09e803c41245a49ef23987c27b20ac', '', 'Approval', 'approval-deskripsi-produk', 'Approval Deskripsi Produk'),
('9b4e52c30f974844ac7a050000a0ee6a', '70410774a1e0433bb213a9625aceb0bb', '', 'Approval', 'Persetujuan Direktur Utama', 'Persetujuan Cetak Dir Utama'),
('9beba245308543ce821efe8a3ba965e3', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/penilaian', 'Update', 'naskah-pn-dpemasaran', 'Penilaian D.Pemasaran'),
('9d69d18ff5184804990bc21cb1005ab7', 'bfb8b970f85c4a42bac1dc56181dc96b', '', 'Read', 'lihat-order-ebook', 'Lihat Order E-book'),
('a213b689b8274f4dbe19b3fb24d66840', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/penilaian', 'Update', 'naskah-pn-mpemasaran', 'Penilaian M.Pemasaran'),
('a6034d814d7e4671b4cc8a98433f8fb2', '583a723cf036449d80d3742dcf695e38', 'penerbitan/naskah/timeline', 'Create', 'timeline-naskah-add', 'Buat Timeline Naskah'),
('a91ee437-1e08-11ed-87ce-1078d2a38ee5', '5646908e-1e06-11ed-87ce-1078d2a38ee5', '', 'Update', 'ubah-lanjutan-data-produksi', 'Ubah Data Proses Produksi'),
('a9354dd060524bce8278e2cd75ce349a', '92463f9e96394c19a979a3290fde5745', '', 'Create', 'ubah-atau-buat-editing-smk', 'Kabag Editor SMK'),
('ac61ff38a6854298919a06a5b4f34242', '71d6b5671ebb4e128215fccc458fbf09', '', 'Approval', 'action-progress-des-cover', 'Action Progress Des.Cover'),
('ad4c0acc-6c71-11ed-9e64-4cedfb61fb39', '365190039fb44c8ab629806a5490addf', '', 'Create', 'otorisasi-setter-praset-reguler', 'Otorisasi Setter Reguler'),
('b10091920a6348859870b2340a75b746', '92463f9e96394c19a979a3290fde5745', '', 'Create', 'otorisasi-editor-editing-mou', 'Otorisasi Editor MOU'),
('bc5a7cb945e14432bfdf312e2059e868', '31a0187d88d94ddc83db4b71524b5b2d', '', 'Read', 'lihat-kelompok-buku', 'Lihat Kelompok Buku'),
('bc6b9c821e3f42ccb57532930c8d92be', '8bc1be5db97545e2ab1c79e0d68d4896', '', 'Update', 'ubah-platform-digital', 'Ubah Platform Digital'),
('be40fd210eb44ee68475bbe80eb8b1ea', '31a0187d88d94ddc83db4b71524b5b2d', '', 'Update', 'ubah-kelompok-buku', 'Ubah Kelompok Buku'),
('c21495eca0d44776aeacf431dc9fb0e1', 'bfb8b970f85c4a42bac1dc56181dc96b', '', 'Update', 'update-order-ebook', 'Mengubah Order E-book'),
('c64802952e504f4ab25a6b1241232f85', '70410774a1e0433bb213a9625aceb0bb', 'produksi/order-cetak', 'Read', 'lihat-order-cetak', 'Lihat Data Order Cetak'),
('c7c43d092d4f411492a4b4db17f9809f', 'be061671a86c4437803f7c225e117799', '', 'Create', 'otorisasi-desainer-prades-mou', 'Otorisasi Desainer MOU'),
('c85dbeca3e87406f97d9e10f6d5970d4', '92463f9e96394c19a979a3290fde5745', '', 'Create', 'otorisasi-editor-editing-smk', 'Otorisasi Editor SMK'),
('caa36115b94a4507823ba27f93d07304', 'be061671a86c4437803f7c225e117799', '', 'Create', 'otorisasi-korektor-prades-mou', 'Otorisasi Korektor MOU'),
('cc93223a47764195ac15aacf266673d9', 'fb6c8f0dcc9e43199642f08a0fe1fd56', 'penerbitan/penulis/mengubah-penulis', 'Update', 'ubah-data-penulis', 'Ubah Data Penulis'),
('ce3589b822a14011ba581c803ef50f5b', '92463f9e96394c19a979a3290fde5745', '', 'Create', 'ubah-atau-buat-editing-mou', 'Kabag Editor MOU'),
('d821a505-1e08-11ed-87ce-1078d2a38ee5', 'b6cbf112-1e06-11ed-87ce-1078d2a38ee5', '', 'Update', 'ubah-data-multimedia', 'Ubah Data E-book Multimedia'),
('db87d2605a68440fbf8e148744e243e8', 'e32aa5bb41144ac58f2e6eeca81604ac', '', 'Read', 'lihat-deskripsi-final', 'Lihat Deskripsi Final'),
('dc43f263313f4788bccbcc9adf642a1f', '3dbad039493241aa8ed0c698d07ee94d', '', 'Delete', 'hapus-format-buku', 'Hapus Format Buku'),
('de329f1d-663c-11ed-94ad-4cedfb61fb39', '92463f9e96394c19a979a3290fde5745', '', 'Create', 'otorisasi-copyeditor-editing-mou', 'Otorisasi Copy Editor MOU'),
('e0860766d564483e870b5974a601649c', '70410774a1e0433bb213a9625aceb0bb', '', 'Update', 'update-order-cetak', 'Mengubah Data Order Cetak'),
('e0923722a9fc44abaa84e59d545afe69', 'be061671a86c4437803f7c225e117799', '', 'Create', 'otorisasi-desainer-prades-reguler', 'Otorisasi Desainer Reguler'),
('e9f5bad7fdd94494a125e451de456a92', '8bc1be5db97545e2ab1c79e0d68d4896', '', 'Delete', 'hapus-platform-digital', 'Hapus Platform Digital'),
('ebca07da8aad42c4aee304e3a6b81001', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/penilaian', 'Update', 'naskah-pn-prodev', 'Penilaian Prodev'),
('eecbccb6-1e08-11ed-87ce-1078d2a38ee5', 'b6cbf112-1e06-11ed-87ce-1078d2a38ee5', '', 'Read', 'lihat-ebook-multimedia', 'Lihat Data E-book Multimedia'),
('ef9cb16ff775416f931a6a08f51070c3', 'be061671a86c4437803f7c225e117799', '', 'Create', 'otorisasi-desainer-prades-smk', 'Otorisasi Desainer SMK'),
('f76c69fb-16f4-11ed-ae5c-1078d2a38ee5', 'bc5eb3aa02394dcca7692764e1328cee', '', 'Read', 'lihat-imprint', 'Lihat Data Imprint'),
('faa7c4808c714ca49762f6aaade7da3b', '3dbad039493241aa8ed0c698d07ee94d', '', 'Read', 'lihat-format-buku', 'Lihat Format Buku'),
('fd061c3363db4b298eea0bb0b4cbcbf0', '3dbad039493241aa8ed0c698d07ee94d', '', 'Create', 'buat-format-buku', 'Buat Format Buku');

-- --------------------------------------------------------

--
-- Table structure for table `pilihan_penerbitan`
--

CREATE TABLE `pilihan_penerbitan` (
  `id` char(36) NOT NULL,
  `deskripsi_turun_cetak_id` char(36) DEFAULT NULL,
  `pilihan_terbit` varchar(25) DEFAULT NULL,
  `platform_digital_ebook_id` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pilihan_penerbitan`
--

INSERT INTO `pilihan_penerbitan` (`id`, `deskripsi_turun_cetak_id`, `pilihan_terbit`, `platform_digital_ebook_id`) VALUES
('1fa88554-b0e6-48e5-a067-be30e7de1156', 'ee5fcc10-4bb2-4f06-b9e1-c23fd377af33', '[\"cetak\",\"ebook\"]', '[\"Moco\",\"Indopustaka\",\"Bahanaflix\",\"Esentral\"]'),
('4d2c27fb-c772-4629-84f0-774ce27ba1ac', 'b629bea1-e1c7-4d61-b00a-7489341abc1b', '[\"cetak\",\"ebook\"]', '[\"Moco\",\"Indopustaka\",\"Gramedia\",\"Bahanaflix\",\"Esentral\",\"Google Book\"]');

-- --------------------------------------------------------

--
-- Table structure for table `platform_digital_ebook`
--

CREATE TABLE `platform_digital_ebook` (
  `id` char(36) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(36) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `platform_digital_ebook`
--

INSERT INTO `platform_digital_ebook` (`id`, `nama`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
('0258637f-3511-4a9f-8b74-cd3fc5f3d587', 'Moco', '2022-09-06 04:12:46', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('04f57a2a-2d2b-4826-b49c-019a91e35620', 'Indopustaka', '2022-09-06 04:15:29', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('0bba7f98-5efd-4044-9e25-49193bbbd556', 'Gramedia', '2022-09-06 04:13:31', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('35a05fdc-50cf-4778-ad40-5d1872e830bd', 'Bahanaflix', '2022-09-06 04:15:17', 'be8d42fa88a14406ac201974963d9c1b', '2022-09-23 02:16:10', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL),
('e15a923a-c839-43fb-9e28-14e577ec7527', 'Esentral', '2022-09-06 04:13:42', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('f658566d-2bb3-4b6d-9a3f-bbcfa78d5d77', 'Google Book', '2022-09-06 04:13:21', 'be8d42fa88a14406ac201974963d9c1b', '2022-09-06 08:14:00', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `platform_digital_ebook_history`
--

CREATE TABLE `platform_digital_ebook_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `platform_id` char(36) DEFAULT NULL,
  `platform_history` varchar(100) DEFAULT NULL,
  `platform_new` varchar(100) DEFAULT NULL,
  `author_id` varchar(36) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `platform_digital_ebook_history`
--

INSERT INTO `platform_digital_ebook_history` (`id`, `platform_id`, `platform_history`, `platform_new`, `author_id`, `modified_at`) VALUES
(1, '35a05fdc-50cf-4778-ad40-5d1872e830bd', 'Bahanaflix', 'Bahanaflik', 'be8d42fa88a14406ac201974963d9c1b', '2022-09-23 09:15:38'),
(2, '35a05fdc-50cf-4778-ad40-5d1872e830bd', 'Bahanaflik', 'Bahanaflix', 'be8d42fa88a14406ac201974963d9c1b', '2022-09-23 09:16:10');

-- --------------------------------------------------------

--
-- Table structure for table `pracetak_cover`
--

CREATE TABLE `pracetak_cover` (
  `id` char(36) NOT NULL,
  `deskripsi_cover_id` char(36) DEFAULT NULL,
  `desainer` longtext COMMENT 'Array',
  `tgl_masuk_cover` datetime DEFAULT NULL,
  `mulai_pengajuan_cover` datetime DEFAULT NULL,
  `selesai_pengajuan_cover` datetime DEFAULT NULL,
  `mulai_proof` datetime DEFAULT NULL,
  `selesai_proof` datetime DEFAULT NULL,
  `mulai_cover` datetime DEFAULT NULL,
  `selesai_cover` datetime DEFAULT NULL,
  `korektor` varchar(36) DEFAULT NULL,
  `mulai_koreksi` datetime DEFAULT NULL,
  `selesai_koreksi` datetime DEFAULT NULL,
  `turun_cetak` datetime DEFAULT NULL,
  `catatan` text,
  `proses` set('0','1') DEFAULT '0',
  `proses_saat_ini` enum('Antrian Pengajuan Desain','Pengajuan Desain','Approval Prodev','Antrian Desain Back Cover','Desain Back Cover','Antrian Koreksi','Koreksi','Siap Turcet','Turun Cetak','Desain Revisi') DEFAULT NULL,
  `bulan` date DEFAULT NULL,
  `status` enum('Antrian','Pending','Proses','Selesai','Revisi') DEFAULT 'Antrian'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pracetak_cover`
--

INSERT INTO `pracetak_cover` (`id`, `deskripsi_cover_id`, `desainer`, `tgl_masuk_cover`, `mulai_pengajuan_cover`, `selesai_pengajuan_cover`, `mulai_proof`, `selesai_proof`, `mulai_cover`, `selesai_cover`, `korektor`, `mulai_koreksi`, `selesai_koreksi`, `turun_cetak`, `catatan`, `proses`, `proses_saat_ini`, `bulan`, `status`) VALUES
('2c05015a-d003-42d9-8a90-3bb62fc1103a', '970dc95b-f768-4713-acb1-b4b116f33b73', '[\"3d43ab399ec24c30b39c9b052686416d\"]', '2023-01-17 15:38:22', '2023-01-18 11:23:17', '2023-01-18 11:27:04', '2023-01-18 11:27:38', '2023-01-18 11:29:06', '2023-01-18 11:29:46', '2023-01-18 11:29:59', '[\"0007828bc2a5496bbdd8fbaefe2e1565\"]', '2023-01-18 11:30:39', '2023-01-18 11:31:31', '2023-01-18 11:32:30', NULL, '0', 'Turun Cetak', '2023-02-01', 'Selesai'),
('f59540f4-23fe-4450-a9ce-86d019437761', '765d9de0-7af1-4d03-8376-01bc87a93e6e', '[\"3d43ab399ec24c30b39c9b052686416d\"]', '2023-01-18 15:15:14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, 'Antrian'),
('f850c6dc-f70e-4994-990d-e3e75c598002', '2c05015a-d003-42d9-8a90-3bb62fc1103a', '[\"3d43ab399ec24c30b39c9b052686416d\"]', '2022-11-02 14:42:25', '2023-01-04 16:40:56', '2023-01-04 16:41:22', '2023-01-04 16:44:05', '2023-01-06 08:59:09', '2023-01-06 13:29:48', '2023-01-06 13:29:59', '[\"0007828bc2a5496bbdd8fbaefe2e1565\"]', '2023-01-06 10:03:01', '2023-01-06 13:37:02', '2023-01-06 13:41:03', NULL, '0', 'Turun Cetak', '2022-12-01', 'Selesai');

-- --------------------------------------------------------

--
-- Table structure for table `pracetak_cover_history`
--

CREATE TABLE `pracetak_cover_history` (
  `id` bigint(20) NOT NULL,
  `pracetak_cover_id` char(36) DEFAULT NULL,
  `type_history` enum('Update','Progress','Status') DEFAULT NULL,
  `desainer_his` longtext,
  `desainer_new` longtext,
  `korektor_his` longtext,
  `korektor_new` longtext,
  `bulan_his` date DEFAULT NULL,
  `bulan_new` date DEFAULT NULL,
  `status_his` varchar(8) DEFAULT NULL,
  `status_new` varchar(8) DEFAULT NULL,
  `progress` tinyint(4) DEFAULT NULL,
  `catatan_his` text,
  `catatan_new` text,
  `proses_ini_his` varchar(40) DEFAULT NULL,
  `proses_ini_new` varchar(40) DEFAULT NULL,
  `mulai_pengajuan_cover` datetime DEFAULT NULL,
  `selesai_pengajuan_cover` datetime DEFAULT NULL,
  `mulai_proof` datetime DEFAULT NULL,
  `selesai_proof` datetime DEFAULT NULL,
  `ket_revisi` text,
  `mulai_cover` datetime DEFAULT NULL,
  `selesai_cover` datetime DEFAULT NULL,
  `mulai_koreksi` datetime DEFAULT NULL,
  `selesai_koreksi` datetime DEFAULT NULL,
  `author_id` varchar(36) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pracetak_cover_history`
--

INSERT INTO `pracetak_cover_history` (`id`, `pracetak_cover_id`, `type_history`, `desainer_his`, `desainer_new`, `korektor_his`, `korektor_new`, `bulan_his`, `bulan_new`, `status_his`, `status_new`, `progress`, `catatan_his`, `catatan_new`, `proses_ini_his`, `proses_ini_new`, `mulai_pengajuan_cover`, `selesai_pengajuan_cover`, `mulai_proof`, `selesai_proof`, `ket_revisi`, `mulai_cover`, `selesai_cover`, `mulai_koreksi`, `selesai_koreksi`, `author_id`, `modified_at`) VALUES
(1, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-29 16:48:39'),
(2, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Antrian', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-29 16:51:11'),
(3, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-29 16:51:37'),
(4, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-29 16:57:32'),
(5, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Antrian', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-29 16:58:31'),
(6, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-30 09:13:27'),
(8, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-03 13:09:50'),
(9, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-03 14:53:34'),
(10, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-03 14:54:17'),
(11, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Approval Prodev', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-03 15:01:29'),
(12, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Approval Prodev', 'Antrian Pengajuan Desain', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-03 15:01:45'),
(13, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-03 15:01:50'),
(14, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-03 15:15:05'),
(15, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian Pengajuan Desain', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-03 15:18:28'),
(16, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-03 15:18:44'),
(17, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-03 15:19:15'),
(18, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-03 15:23:48'),
(19, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-03 15:31:08'),
(20, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', 'Proses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-03 15:31:39'),
(21, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian Pengajuan Desain', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 13:32:53'),
(22, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 13:32:56'),
(23, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-04 14:22:10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3d43ab399ec24c30b39c9b052686416d', '2023-01-04 14:22:10'),
(24, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian Pengajuan Desain', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 14:27:40'),
(25, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian Pengajuan Desain', 'Approval Prodev', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 14:28:11'),
(26, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 14:32:40'),
(27, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Revisi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Warnanya diganti hijau ya', NULL, NULL, NULL, NULL, 'e83ca4537495486c8d3b5d7e6ae2407a', '2023-01-04 14:59:55'),
(28, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, 'Revisi', 'Proses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 15:28:53'),
(29, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Proses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-04 15:32:34', NULL, NULL, NULL, NULL, NULL, 'e83ca4537495486c8d3b5d7e6ae2407a', '2023-01-04 15:32:34'),
(30, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, '[\"12c8a8639d814102b01c7ffc0cd52e71\"]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian Koreksi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 15:40:49'),
(31, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian Koreksi', 'Antrian Desain Back Cover', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 15:49:21'),
(32, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 15:55:35'),
(33, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 15:57:58'),
(34, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian Pengajuan Desain', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 16:40:22'),
(35, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 16:40:29'),
(36, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 16:40:34'),
(37, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian Pengajuan Desain', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 16:40:50'),
(38, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 16:40:56'),
(39, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-04 16:41:22', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3d43ab399ec24c30b39c9b052686416d', '2023-01-04 16:41:22'),
(40, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Approval Prodev', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 16:41:41'),
(41, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 16:44:05'),
(42, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Revisi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Salah su datane', NULL, NULL, NULL, NULL, 'e83ca4537495486c8d3b5d7e6ae2407a', '2023-01-04 16:44:24'),
(43, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, 'Revisi', 'Proses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-04 16:46:25'),
(44, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Revisi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ulang lagi cetak', NULL, NULL, NULL, NULL, 'e83ca4537495486c8d3b5d7e6ae2407a', '2023-01-06 08:38:32'),
(45, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, 'Revisi', 'Proses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 08:58:50'),
(46, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Proses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-06 08:59:09', NULL, NULL, NULL, NULL, NULL, 'e83ca4537495486c8d3b5d7e6ae2407a', '2023-01-06 08:59:09'),
(47, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian Pengajuan Desain', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 09:00:37'),
(48, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian Pengajuan Desain', 'Antrian Desain Back Cover', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 09:02:04'),
(49, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 09:02:07'),
(50, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-06 09:14:40', NULL, NULL, '3d43ab399ec24c30b39c9b052686416d', '2023-01-06 09:14:40'),
(51, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, '[\"0007828bc2a5496bbdd8fbaefe2e1565\"]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 09:26:26'),
(52, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 09:43:56'),
(53, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-06 09:44:33', '0007828bc2a5496bbdd8fbaefe2e1565', '2023-01-06 09:44:33'),
(54, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', 'Antrian Koreksi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 09:46:39'),
(55, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 10:03:01'),
(56, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-06 10:09:48', '0007828bc2a5496bbdd8fbaefe2e1565', '2023-01-06 10:09:48'),
(57, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 10:13:28'),
(58, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-06 10:13:48', NULL, NULL, '3d43ab399ec24c30b39c9b052686416d', '2023-01-06 10:13:48'),
(59, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 10:20:50'),
(60, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-06 10:21:25', '0007828bc2a5496bbdd8fbaefe2e1565', '2023-01-06 10:21:25'),
(61, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 10:22:04'),
(62, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-06 10:27:14', NULL, NULL, '3d43ab399ec24c30b39c9b052686416d', '2023-01-06 10:27:14'),
(63, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 10:34:09'),
(64, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-06 12:53:25', '0007828bc2a5496bbdd8fbaefe2e1565', '2023-01-06 12:53:25'),
(65, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-06 12:55:00', '0007828bc2a5496bbdd8fbaefe2e1565', '2023-01-06 12:55:00'),
(66, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Desain Revisi', 'Turun Cetak', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 12:57:53'),
(67, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Desain Revisi', 'Turun Cetak', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 13:05:21'),
(68, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Desain Revisi', 'Turun Cetak', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 13:06:24'),
(69, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 13:10:14'),
(70, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 13:10:36'),
(71, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Desain Revisi', 'Turun Cetak', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 13:18:49'),
(72, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 13:25:24'),
(73, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-06 13:25:37', NULL, NULL, '3d43ab399ec24c30b39c9b052686416d', '2023-01-06 13:25:37'),
(74, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 13:27:55'),
(75, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-06 13:28:08', '0007828bc2a5496bbdd8fbaefe2e1565', '2023-01-06 13:28:08'),
(76, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 13:29:48'),
(77, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-06 13:29:59', NULL, NULL, '3d43ab399ec24c30b39c9b052686416d', '2023-01-06 13:29:59'),
(78, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 13:36:37'),
(79, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-06 13:37:02', '0007828bc2a5496bbdd8fbaefe2e1565', '2023-01-06 13:37:02'),
(80, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Desain Revisi', 'Siap Turcet', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 13:40:23'),
(81, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Siap Turcet', 'Turun Cetak', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 13:41:03'),
(83, 'f850c6dc-f70e-4994-990d-e3e75c598002', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-06 13:51:15'),
(84, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 11:19:40'),
(85, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Update', NULL, NULL, NULL, NULL, NULL, '2023-02-01', NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian Pengajuan Desain', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 11:20:46'),
(86, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 11:23:17'),
(87, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-18 11:27:04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3d43ab399ec24c30b39c9b052686416d', '2023-01-18 11:27:04'),
(88, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Approval Prodev', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 11:27:34'),
(89, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 11:27:38'),
(90, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Revisi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'kok gitu', NULL, NULL, NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-18 11:28:14'),
(91, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, 'Revisi', 'Proses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 11:28:49'),
(92, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Proses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-18 11:29:06', NULL, NULL, NULL, NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-18 11:29:06'),
(93, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian Desain Back Cover', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 11:29:43'),
(94, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 11:29:46'),
(95, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-18 11:29:59', NULL, NULL, '3d43ab399ec24c30b39c9b052686416d', '2023-01-18 11:29:59'),
(96, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Update', NULL, NULL, NULL, '[\"0007828bc2a5496bbdd8fbaefe2e1565\"]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 11:30:35'),
(97, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 11:30:39'),
(98, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-18 11:31:31', '0007828bc2a5496bbdd8fbaefe2e1565', '2023-01-18 11:31:31'),
(99, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Desain Revisi', 'Siap Turcet', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 11:32:18'),
(100, '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Siap Turcet', 'Turun Cetak', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 11:32:30');

-- --------------------------------------------------------

--
-- Table structure for table `pracetak_cover_proof`
--

CREATE TABLE `pracetak_cover_proof` (
  `id` bigint(20) NOT NULL,
  `type_user` enum('Desainer','Korektor') DEFAULT NULL,
  `type_action` enum('Proof','Revisi','Selesai Revisi') DEFAULT NULL,
  `pracetak_cover_id` char(36) DEFAULT NULL,
  `users_id` varchar(36) DEFAULT NULL,
  `ket_revisi` text,
  `tgl_action` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pracetak_cover_proof`
--

INSERT INTO `pracetak_cover_proof` (`id`, `type_user`, `type_action`, `pracetak_cover_id`, `users_id`, `ket_revisi`, `tgl_action`) VALUES
(1, 'Desainer', 'Revisi', 'f850c6dc-f70e-4994-990d-e3e75c598002', 'e83ca4537495486c8d3b5d7e6ae2407a', 'Salah su datane', '2023-01-04 16:44:24'),
(2, 'Desainer', 'Selesai Revisi', 'f850c6dc-f70e-4994-990d-e3e75c598002', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2023-01-04 16:46:25'),
(3, 'Desainer', 'Revisi', 'f850c6dc-f70e-4994-990d-e3e75c598002', 'e83ca4537495486c8d3b5d7e6ae2407a', 'Ulang lagi cetak', '2023-01-06 08:38:32'),
(4, 'Desainer', 'Selesai Revisi', 'f850c6dc-f70e-4994-990d-e3e75c598002', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2023-01-06 08:58:50'),
(5, 'Desainer', 'Proof', 'f850c6dc-f70e-4994-990d-e3e75c598002', 'e83ca4537495486c8d3b5d7e6ae2407a', NULL, '2023-01-06 08:59:09'),
(6, 'Desainer', 'Revisi', '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'ceadd9fb648445eab1e350357e51d1ce', 'kok gitu', '2023-01-18 11:28:14'),
(7, 'Desainer', 'Selesai Revisi', '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'a1b814111063435eb7a862c18d1de4cf', NULL, '2023-01-18 11:28:49'),
(8, 'Desainer', 'Proof', '2c05015a-d003-42d9-8a90-3bb62fc1103a', 'ceadd9fb648445eab1e350357e51d1ce', NULL, '2023-01-18 11:29:06');

-- --------------------------------------------------------

--
-- Table structure for table `pracetak_cover_selesai`
--

CREATE TABLE `pracetak_cover_selesai` (
  `id` bigint(20) NOT NULL,
  `type` enum('Desainer','Korektor') DEFAULT NULL,
  `section` enum('Pengajuan Cover','Back Cover Design','Back Cover Design Revision','Koreksi') DEFAULT NULL,
  `tahap` tinyint(4) DEFAULT NULL,
  `pracetak_cover_id` char(36) DEFAULT NULL,
  `users_id` varchar(36) DEFAULT NULL,
  `tgl_proses_selesai` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pracetak_cover_selesai`
--

INSERT INTO `pracetak_cover_selesai` (`id`, `type`, `section`, `tahap`, `pracetak_cover_id`, `users_id`, `tgl_proses_selesai`) VALUES
(1, 'Desainer', 'Pengajuan Cover', 1, 'f850c6dc-f70e-4994-990d-e3e75c598002', '3d43ab399ec24c30b39c9b052686416d', '2023-01-04 16:41:22'),
(2, 'Desainer', 'Back Cover Design', 1, 'f850c6dc-f70e-4994-990d-e3e75c598002', '3d43ab399ec24c30b39c9b052686416d', '2023-01-06 09:14:40'),
(10, 'Korektor', 'Koreksi', 1, 'f850c6dc-f70e-4994-990d-e3e75c598002', '0007828bc2a5496bbdd8fbaefe2e1565', '2023-01-06 12:55:00'),
(11, 'Desainer', 'Back Cover Design Revision', 1, 'f850c6dc-f70e-4994-990d-e3e75c598002', '3d43ab399ec24c30b39c9b052686416d', '2023-01-06 13:25:37'),
(12, 'Korektor', 'Koreksi', 2, 'f850c6dc-f70e-4994-990d-e3e75c598002', '0007828bc2a5496bbdd8fbaefe2e1565', '2023-01-06 13:28:08'),
(13, 'Desainer', 'Back Cover Design Revision', 2, 'f850c6dc-f70e-4994-990d-e3e75c598002', '3d43ab399ec24c30b39c9b052686416d', '2023-01-06 13:29:59'),
(14, 'Korektor', 'Koreksi', 3, 'f850c6dc-f70e-4994-990d-e3e75c598002', '0007828bc2a5496bbdd8fbaefe2e1565', '2023-01-06 13:37:02'),
(15, 'Desainer', 'Pengajuan Cover', 1, '2c05015a-d003-42d9-8a90-3bb62fc1103a', '3d43ab399ec24c30b39c9b052686416d', '2023-01-18 11:27:04'),
(16, 'Desainer', 'Back Cover Design', 1, '2c05015a-d003-42d9-8a90-3bb62fc1103a', '3d43ab399ec24c30b39c9b052686416d', '2023-01-18 11:29:59'),
(17, 'Korektor', 'Koreksi', 1, '2c05015a-d003-42d9-8a90-3bb62fc1103a', '0007828bc2a5496bbdd8fbaefe2e1565', '2023-01-18 11:31:31');

-- --------------------------------------------------------

--
-- Table structure for table `pracetak_setter`
--

CREATE TABLE `pracetak_setter` (
  `id` char(36) NOT NULL,
  `deskripsi_final_id` char(36) DEFAULT NULL,
  `jml_hal_final` int(11) DEFAULT NULL,
  `tgl_masuk_pracetak` datetime DEFAULT NULL,
  `mulai_setting` datetime DEFAULT NULL,
  `selesai_setting` datetime DEFAULT NULL,
  `setter` longtext COMMENT 'Array',
  `mulai_proof` datetime DEFAULT NULL,
  `selesai_proof` datetime DEFAULT NULL,
  `mulai_koreksi` datetime DEFAULT NULL,
  `selesai_koreksi` datetime DEFAULT NULL,
  `korektor` longtext COMMENT 'Array',
  `turun_cetak` datetime DEFAULT NULL,
  `edisi_cetak` varchar(100) DEFAULT NULL,
  `mulai_p_copyright` datetime DEFAULT NULL,
  `selesai_p_copyright` datetime DEFAULT NULL,
  `isbn` char(13) DEFAULT NULL,
  `pengajuan_harga` int(11) DEFAULT NULL,
  `proses_saat_ini` enum('Antrian Setting','Setting','Proof Prodev','Antrian Koreksi','Koreksi','Siap Turcet','Turun Cetak','Setting Revisi') DEFAULT NULL,
  `proses` set('0','1') DEFAULT '0',
  `bulan` date DEFAULT NULL,
  `catatan` text,
  `status` enum('Antrian','Pending','Proses','Selesai','Revisi') DEFAULT 'Antrian'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pracetak_setter`
--

INSERT INTO `pracetak_setter` (`id`, `deskripsi_final_id`, `jml_hal_final`, `tgl_masuk_pracetak`, `mulai_setting`, `selesai_setting`, `setter`, `mulai_proof`, `selesai_proof`, `mulai_koreksi`, `selesai_koreksi`, `korektor`, `turun_cetak`, `edisi_cetak`, `mulai_p_copyright`, `selesai_p_copyright`, `isbn`, `pengajuan_harga`, `proses_saat_ini`, `proses`, `bulan`, `catatan`, `status`) VALUES
('18f171f1-fc0c-48dc-a37e-391f95fa69fd', 'b8536fe9-d27a-457a-be6d-e5af83d7836b', 123, '2023-01-18 15:15:14', NULL, NULL, '[\"a4f8d1d67d2e4b9aa2a8e8680a953194\"]', NULL, NULL, NULL, NULL, '[\"0007828bc2a5496bbdd8fbaefe2e1565\"]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, 'Proses'),
('737388c6-015a-4e1b-b0a2-3c71a083a045', '330bb9ae-7378-4bf7-bf48-6eb066f2d566', 156, '2023-01-17 15:38:22', '2023-01-18 11:13:53', '2023-01-18 11:14:17', '[\"a4f8d1d67d2e4b9aa2a8e8680a953194\"]', '2023-01-18 11:11:12', '2023-01-18 11:11:49', '2023-01-18 11:12:59', '2023-01-18 11:14:40', '[\"0007828bc2a5496bbdd8fbaefe2e1565\"]', '2023-01-18 11:15:30', 'I / 1', '2023-01-18 11:15:07', '2023-01-19 11:15:07', '12102010201', 1200000, 'Turun Cetak', '0', '2023-01-01', NULL, 'Selesai'),
('b7c5387e-d145-402a-8116-47c5ab8c414b', '44dfe332-5755-4191-8158-e79d496e1473', 1000, '2022-11-02 14:42:25', '2022-12-21 14:32:49', '2022-12-21 14:33:56', '[\"a4f8d1d67d2e4b9aa2a8e8680a953194\",\"ba360e2a572f45979cb83648fc5e2ec7\"]', '2022-12-09 09:14:40', '2022-12-13 14:55:47', '2022-12-09 09:14:40', '2022-12-21 14:35:28', '[\"12c8a8639d814102b01c7ffc0cd52e71\"]', '2022-12-21 15:38:13', '1', NULL, NULL, '2334524534653', 345234234, 'Turun Cetak', '0', '2022-12-01', NULL, 'Selesai');

-- --------------------------------------------------------

--
-- Table structure for table `pracetak_setter_history`
--

CREATE TABLE `pracetak_setter_history` (
  `id` bigint(20) NOT NULL,
  `pracetak_setter_id` char(36) DEFAULT NULL,
  `type_history` enum('Status','Update','Progress') DEFAULT NULL,
  `jml_hal_final_his` int(11) DEFAULT NULL,
  `jml_hal_final_new` int(11) DEFAULT NULL,
  `setter_his` longtext,
  `setter_new` longtext,
  `korektor_his` longtext,
  `korektor_new` longtext,
  `bulan_his` date DEFAULT NULL,
  `bulan_new` date DEFAULT NULL,
  `status_his` varchar(8) DEFAULT NULL,
  `status_new` varchar(8) DEFAULT NULL,
  `progress` tinyint(4) DEFAULT NULL,
  `catatan_his` text,
  `catatan_new` text,
  `edisi_cetak_his` varchar(100) DEFAULT NULL,
  `edisi_cetak_new` varchar(100) DEFAULT NULL,
  `mulai_setting` datetime DEFAULT NULL,
  `selesai_setting` datetime DEFAULT NULL,
  `mulai_proof` datetime DEFAULT NULL,
  `selesai_proof` datetime DEFAULT NULL,
  `ket_revisi` text,
  `mulai_koreksi` datetime DEFAULT NULL,
  `selesai_koreksi` datetime DEFAULT NULL,
  `mulai_p_copyright` datetime DEFAULT NULL,
  `selesai_p_copyright` datetime DEFAULT NULL,
  `isbn_his` char(13) DEFAULT NULL,
  `isbn_new` char(13) DEFAULT NULL,
  `pengajuan_harga_his` int(11) DEFAULT NULL,
  `pengajuan_harga_new` int(11) DEFAULT NULL,
  `proses_ini_his` varchar(20) DEFAULT NULL,
  `proses_ini_new` varchar(20) DEFAULT NULL,
  `author_id` varchar(36) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pracetak_setter_history`
--

INSERT INTO `pracetak_setter_history` (`id`, `pracetak_setter_id`, `type_history`, `jml_hal_final_his`, `jml_hal_final_new`, `setter_his`, `setter_new`, `korektor_his`, `korektor_new`, `bulan_his`, `bulan_new`, `status_his`, `status_new`, `progress`, `catatan_his`, `catatan_new`, `edisi_cetak_his`, `edisi_cetak_new`, `mulai_setting`, `selesai_setting`, `mulai_proof`, `selesai_proof`, `ket_revisi`, `mulai_koreksi`, `selesai_koreksi`, `mulai_p_copyright`, `selesai_p_copyright`, `isbn_his`, `isbn_new`, `pengajuan_harga_his`, `pengajuan_harga_new`, `proses_ini_his`, `proses_ini_new`, `author_id`, `modified_at`) VALUES
(1, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-22 11:22:34'),
(2, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Antrian', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-22 11:25:44'),
(3, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-22 11:30:08'),
(4, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-22 16:50:17'),
(5, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-22 16:56:44'),
(6, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-22 16:59:38'),
(7, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-22 17:00:04'),
(8, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-23 09:01:08'),
(9, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-23 09:03:27'),
(10, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-23 14:58:56'),
(11, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-23 15:24:49'),
(12, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-01', NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-24 09:03:09'),
(13, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-24 09:11:48'),
(14, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-01', '2022-11-24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-24 09:27:20'),
(15, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-24 09:44:53'),
(16, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-24 16:43:51'),
(17, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-24 16:43:54'),
(18, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-24 16:44:31'),
(19, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-24', '2022-11-24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-24 16:53:36'),
(20, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-24', '2022-11-24', NULL, NULL, NULL, NULL, NULL, '1', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-24 16:55:53'),
(21, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-24', '2022-11-24', NULL, NULL, NULL, NULL, NULL, '2', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-24 16:56:40'),
(22, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-24', '2022-11-24', NULL, NULL, NULL, NULL, NULL, '1', '3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-24 16:58:20'),
(23, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-24', '2022-11-24', NULL, NULL, NULL, NULL, NULL, '3', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-24 16:59:23'),
(24, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-24', '2022-11-24', NULL, NULL, NULL, NULL, NULL, '1', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-24 17:00:31'),
(25, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-24 17:11:56'),
(26, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-01', '2022-10-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-24 17:12:24'),
(27, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-01', '2022-10-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-24 17:15:50'),
(28, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-24 17:16:25'),
(29, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-24 17:17:08'),
(30, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-01', '2022-11-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-24 17:17:24'),
(31, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, '[\"a4f8d1d67d2e4b9aa2a8e8680a953194\"]', '[\"a4f8d1d67d2e4b9aa2a8e8680a953194\",\"8444ed9de99e4c429bf93b082a19e258\"]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-25 09:17:30'),
(32, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, '[\"a4f8d1d67d2e4b9aa2a8e8680a953194\",\"8444ed9de99e4c429bf93b082a19e258\"]', '[\"a4f8d1d67d2e4b9aa2a8e8680a953194\",\"ba360e2a572f45979cb83648fc5e2ec7\"]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-25 09:20:49'),
(33, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-11-25 12:57:16'),
(34, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-01 09:55:05'),
(35, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-01 09:55:09'),
(36, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-01 09:55:30'),
(37, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-05 11:11:55'),
(38, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-05 11:12:00'),
(39, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-05 11:26:13'),
(40, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '127', NULL, 500000, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-05 14:43:12'),
(41, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '127', '2147483647', NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-05 14:45:43'),
(42, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2147483647', '2342342354545', NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-05 14:48:53'),
(43, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-05 15:15:50'),
(44, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-05 15:15:53'),
(45, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 500000, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-05 15:16:12'),
(46, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2342342354545', '2132342435234', NULL, 345234234, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-06 11:28:23'),
(47, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-06 11:28:27'),
(48, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-06 11:28:37'),
(49, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-06 11:28:44'),
(50, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-06 11:31:40'),
(51, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-06 11:31:57'),
(52, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-06 13:00:25'),
(53, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-06 13:02:14'),
(54, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-06 13:02:30'),
(55, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-06 13:02:34'),
(56, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-08 14:10:11'),
(57, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-08 14:17:52'),
(58, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-08 14:18:12'),
(59, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-08 15:14:20'),
(60, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-08 15:15:04'),
(61, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-08 15:15:09'),
(62, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-08 15:22:33'),
(63, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-09 09:14:33'),
(64, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-09 09:14:40'),
(65, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Revisi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Revisi huruf italic', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'e83ca4537495486c8d3b5d7e6ae2407a', '2022-12-12 14:00:40'),
(66, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Revisi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Digawe italic yo tulisane ng cover', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'e83ca4537495486c8d3b5d7e6ae2407a', '2022-12-12 15:26:42'),
(68, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Revisi', 'Proses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-13 11:23:28'),
(69, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Proses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-13 14:55:47', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'e83ca4537495486c8d3b5d7e6ae2407a', '2022-12-13 14:55:47'),
(70, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-13 14:56:56'),
(71, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian Koreksi', 'be8d42fa88a14406ac201974963d9c1b', '2022-12-13 16:04:18'),
(73, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-15 10:13:46', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '12c8a8639d814102b01c7ffc0cd52e71', '2022-12-15 10:13:46'),
(74, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-15 13:47:59', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '12c8a8639d814102b01c7ffc0cd52e71', '2022-12-15 13:47:59'),
(75, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-15 16:20:35'),
(76, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-15 16:35:22', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '12c8a8639d814102b01c7ffc0cd52e71', '2022-12-15 16:35:22'),
(77, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-15 16:36:18'),
(78, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-16 15:28:59', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '2022-12-16 15:28:59'),
(79, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-20 11:17:30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ba360e2a572f45979cb83648fc5e2ec7', '2022-12-20 11:17:30'),
(80, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-20 14:45:30'),
(81, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-21 08:44:43', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '12c8a8639d814102b01c7ffc0cd52e71', '2022-12-21 08:44:43'),
(82, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-21 08:46:22'),
(83, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-21 11:21:34', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ba360e2a572f45979cb83648fc5e2ec7', '2022-12-21 11:21:34'),
(84, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-21 11:24:49', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '2022-12-21 11:24:49'),
(85, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-21 11:31:48'),
(86, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-21 13:10:13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '12c8a8639d814102b01c7ffc0cd52e71', '2022-12-21 13:10:13'),
(87, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-21 13:12:13'),
(88, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-21 14:06:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ba360e2a572f45979cb83648fc5e2ec7', '2022-12-21 14:06:32'),
(89, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-21 14:30:03'),
(90, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-21 14:30:51', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '12c8a8639d814102b01c7ffc0cd52e71', '2022-12-21 14:30:51'),
(91, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-21 14:32:49'),
(92, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-21 14:33:56', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ba360e2a572f45979cb83648fc5e2ec7', '2022-12-21 14:33:56'),
(93, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-21 14:35:12'),
(94, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-21 14:35:28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '12c8a8639d814102b01c7ffc0cd52e71', '2022-12-21 14:35:28'),
(95, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Setting Revisi', 'Siap Turcet', 'be8d42fa88a14406ac201974963d9c1b', '2022-12-21 15:08:29'),
(96, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2334524534653', NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-21 15:22:14'),
(97, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-21 15:22:20'),
(98, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Siap Turcet', 'Turun Cetak', 'be8d42fa88a14406ac201974963d9c1b', '2022-12-21 15:22:37'),
(99, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-21 15:36:57'),
(100, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-12-21 15:38:13'),
(101, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-12-13 15:36:57', '2022-12-22 15:36:57', NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-02 14:46:54'),
(102, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-03 09:55:32'),
(103, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, '2022-11-01', '2022-12-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-02 09:55:32', NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-03 09:55:43'),
(104, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-02 09:55:43', NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-03 09:57:33'),
(105, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2023-01-03 10:01:09'),
(107, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-17 16:27:21'),
(108, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Update', NULL, NULL, '[\"6fc8b6f16e394f6f802ece3753ba36dc\"]', '[\"a4f8d1d67d2e4b9aa2a8e8680a953194\"]', NULL, NULL, NULL, '2023-01-01', NULL, NULL, NULL, NULL, NULL, NULL, 'I / 1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian Setting', 'a1b814111063435eb7a862c18d1de4cf', '2023-01-17 16:35:37'),
(109, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-17 16:38:33'),
(110, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-18 10:10:04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '2023-01-18 10:10:04'),
(111, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proof Prodev', 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 10:13:11'),
(112, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 10:13:15'),
(113, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Revisi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'kok gini', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-18 10:13:40'),
(114, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Revisi', 'Proses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 10:14:09'),
(115, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Proses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-18 10:14:33', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-18 10:14:33'),
(116, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian Koreksi', 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 10:15:48'),
(117, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 10:15:52'),
(118, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 10:16:56'),
(119, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Update', NULL, NULL, NULL, NULL, '[\"0c5a151afe204df2bf6c38485055da16\"]', '[\"0007828bc2a5496bbdd8fbaefe2e1565\"]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian Koreksi', 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 10:17:08'),
(120, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 10:17:16'),
(121, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-18 10:21:39', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0007828bc2a5496bbdd8fbaefe2e1565', '2023-01-18 10:21:39'),
(122, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 10:21:53'),
(123, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-18 10:31:31', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '2023-01-18 10:31:31'),
(124, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 10:32:18'),
(125, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-18 10:58:04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0007828bc2a5496bbdd8fbaefe2e1565', '2023-01-18 10:58:04'),
(126, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 10:58:31'),
(127, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-18 10:58:52', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '2023-01-18 10:58:52'),
(128, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 10:59:18'),
(129, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-18 10:59:35', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0007828bc2a5496bbdd8fbaefe2e1565', '2023-01-18 10:59:35'),
(130, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 10:59:59'),
(131, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 11:00:02'),
(132, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Siap Turcet', 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 11:00:20'),
(133, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '12102010201', NULL, 1200000, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 11:00:57'),
(134, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian Setting', 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 11:06:06'),
(135, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 11:06:11'),
(136, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-18 11:06:41', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '2023-01-18 11:06:41'),
(137, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proof Prodev', 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 11:11:06'),
(138, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 11:11:12'),
(139, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Proses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-18 11:11:49', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ceadd9fb648445eab1e350357e51d1ce', '2023-01-18 11:11:49'),
(140, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian Koreksi', 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 11:12:54'),
(141, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 11:12:59'),
(142, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-18 11:13:18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0007828bc2a5496bbdd8fbaefe2e1565', '2023-01-18 11:13:18'),
(143, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 11:13:53'),
(144, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-18 11:14:17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '2023-01-18 11:14:17'),
(145, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Progress', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 11:14:29'),
(146, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-01-18 11:14:40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0007828bc2a5496bbdd8fbaefe2e1565', '2023-01-18 11:14:40'),
(147, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Setting Revisi', 'Siap Turcet', 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 11:15:07'),
(148, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Proses', 'Selesai', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 11:15:30'),
(149, '18f171f1-fc0c-48dc-a37e-391f95fa69fd', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a1b814111063435eb7a862c18d1de4cf', '2023-01-18 15:35:02');

-- --------------------------------------------------------

--
-- Table structure for table `pracetak_setter_proof`
--

CREATE TABLE `pracetak_setter_proof` (
  `id` bigint(20) NOT NULL,
  `type_user` enum('Setter','Editor') DEFAULT NULL,
  `type_action` enum('Proof','Revisi','Selesai Revisi') DEFAULT NULL,
  `pracetak_setter_id` char(36) DEFAULT NULL,
  `users_id` varchar(36) DEFAULT NULL,
  `ket_revisi` text,
  `tgl_action` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pracetak_setter_proof`
--

INSERT INTO `pracetak_setter_proof` (`id`, `type_user`, `type_action`, `pracetak_setter_id`, `users_id`, `ket_revisi`, `tgl_action`) VALUES
(1, 'Setter', 'Revisi', 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'e83ca4537495486c8d3b5d7e6ae2407a', 'Digawe italic yo tulisane ng cover', '2022-12-12 15:26:42'),
(3, 'Setter', 'Selesai Revisi', 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-12-13 11:23:28'),
(4, 'Setter', 'Proof', 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'e83ca4537495486c8d3b5d7e6ae2407a', NULL, '2022-12-13 14:55:47'),
(8, 'Setter', 'Proof', '737388c6-015a-4e1b-b0a2-3c71a083a045', 'ceadd9fb648445eab1e350357e51d1ce', NULL, '2023-01-18 11:11:49');

-- --------------------------------------------------------

--
-- Table structure for table `pracetak_setter_selesai`
--

CREATE TABLE `pracetak_setter_selesai` (
  `id` bigint(20) NOT NULL,
  `type` enum('Setter','Korektor') DEFAULT NULL,
  `section` enum('Proof Setting','Proof Setting Revision','Setting Revision','Koreksi') DEFAULT NULL,
  `tahap` tinyint(4) DEFAULT NULL,
  `pracetak_setter_id` char(36) DEFAULT NULL,
  `users_id` varchar(36) DEFAULT NULL,
  `tgl_proses_selesai` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pracetak_setter_selesai`
--

INSERT INTO `pracetak_setter_selesai` (`id`, `type`, `section`, `tahap`, `pracetak_setter_id`, `users_id`, `tgl_proses_selesai`) VALUES
(1, 'Setter', 'Proof Setting', 1, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'a4f8d1d67d2e4b9aa2a8e8680a953194', '2022-12-07 08:51:49'),
(5, 'Setter', 'Proof Setting', 1, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'ba360e2a572f45979cb83648fc5e2ec7', '2022-12-08 15:12:38'),
(15, 'Korektor', 'Koreksi', 1, 'b7c5387e-d145-402a-8116-47c5ab8c414b', '12c8a8639d814102b01c7ffc0cd52e71', '2022-12-15 16:35:22'),
(16, 'Setter', 'Setting Revision', 1, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'a4f8d1d67d2e4b9aa2a8e8680a953194', '2022-12-16 15:10:54'),
(19, 'Setter', 'Setting Revision', 1, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'ba360e2a572f45979cb83648fc5e2ec7', '2022-12-20 11:17:30'),
(23, 'Korektor', 'Koreksi', 2, 'b7c5387e-d145-402a-8116-47c5ab8c414b', '12c8a8639d814102b01c7ffc0cd52e71', '2022-12-21 08:44:43'),
(30, 'Setter', 'Setting Revision', 2, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'ba360e2a572f45979cb83648fc5e2ec7', '2022-12-21 11:23:21'),
(31, 'Setter', 'Setting Revision', 2, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'a4f8d1d67d2e4b9aa2a8e8680a953194', '2022-12-21 11:24:49'),
(33, 'Korektor', 'Koreksi', 3, 'b7c5387e-d145-402a-8116-47c5ab8c414b', '12c8a8639d814102b01c7ffc0cd52e71', '2022-12-21 13:10:13'),
(34, 'Setter', 'Setting Revision', 3, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'a4f8d1d67d2e4b9aa2a8e8680a953194', '2022-12-21 13:12:28'),
(36, 'Setter', 'Setting Revision', 3, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'ba360e2a572f45979cb83648fc5e2ec7', '2022-12-21 14:06:32'),
(37, 'Korektor', 'Koreksi', 4, 'b7c5387e-d145-402a-8116-47c5ab8c414b', '12c8a8639d814102b01c7ffc0cd52e71', '2022-12-21 14:30:51'),
(38, 'Setter', 'Setting Revision', 4, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'a4f8d1d67d2e4b9aa2a8e8680a953194', '2022-12-21 14:33:23'),
(39, 'Setter', 'Setting Revision', 4, 'b7c5387e-d145-402a-8116-47c5ab8c414b', 'ba360e2a572f45979cb83648fc5e2ec7', '2022-12-21 14:33:56'),
(40, 'Korektor', 'Koreksi', 5, 'b7c5387e-d145-402a-8116-47c5ab8c414b', '12c8a8639d814102b01c7ffc0cd52e71', '2022-12-21 14:35:28'),
(47, 'Setter', 'Proof Setting', 1, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'a4f8d1d67d2e4b9aa2a8e8680a953194', '2023-01-18 11:06:41'),
(48, 'Korektor', 'Koreksi', 1, '737388c6-015a-4e1b-b0a2-3c71a083a045', '0007828bc2a5496bbdd8fbaefe2e1565', '2023-01-18 11:13:18'),
(49, 'Setter', 'Setting Revision', 1, '737388c6-015a-4e1b-b0a2-3c71a083a045', 'a4f8d1d67d2e4b9aa2a8e8680a953194', '2023-01-18 11:14:17'),
(50, 'Korektor', 'Koreksi', 2, '737388c6-015a-4e1b-b0a2-3c71a083a045', '0007828bc2a5496bbdd8fbaefe2e1565', '2023-01-18 11:14:40');

-- --------------------------------------------------------

--
-- Table structure for table `proses_ebook_multimedia`
--

CREATE TABLE `proses_ebook_multimedia` (
  `id` char(36) NOT NULL,
  `order_ebook_id` char(36) NOT NULL,
  `bukti_upload` longtext COMMENT 'Link Bukti Upload',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(36) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `proses_produksi_cetak`
--

CREATE TABLE `proses_produksi_cetak` (
  `id` char(36) NOT NULL,
  `order_cetak_id` varchar(36) NOT NULL,
  `katern` varchar(10) DEFAULT NULL,
  `mesin` smallint(6) DEFAULT NULL,
  `plat` date DEFAULT NULL,
  `cetak_isi` date DEFAULT NULL,
  `cover` date DEFAULT NULL,
  `lipat_isi` date DEFAULT NULL,
  `jilid` date DEFAULT NULL,
  `potong_3_sisi` date DEFAULT NULL,
  `wrapping` date DEFAULT NULL,
  `kirim_gudang` date DEFAULT NULL,
  `harga` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(36) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` varchar(36) DEFAULT NULL
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
  `ttl_hari_penerbitan` int(10) UNSIGNED NOT NULL,
  `tgl_mulai_produksi` datetime NOT NULL,
  `ttl_hari_produksi` int(10) UNSIGNED NOT NULL,
  `tgl_buku_jadi` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
  `catatan` text COMMENT 'Input PIC'
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
  `alamat` text,
  `cabang_id` varchar(36) DEFAULT NULL,
  `divisi_id` varchar(36) DEFAULT NULL,
  `jabatan_id` varchar(36) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1' COMMENT '1:Aktif|2:NonAktif',
  `super_admin` enum('1','0') NOT NULL DEFAULT '0' COMMENT 'Default Admin',
  `last_login` datetime DEFAULT NULL,
  `ip_address` binary(45) DEFAULT NULL,
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

INSERT INTO `users` (`id`, `email`, `password`, `avatar`, `nama`, `tanggal_lahir`, `tempat_lahir`, `telepon`, `alamat`, `cabang_id`, `divisi_id`, `jabatan_id`, `status`, `super_admin`, `last_login`, `ip_address`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
('0007828bc2a5496bbdd8fbaefe2e1565', 'swilasari@gmail.com', '$2y$10$nBno7rVVPAUThhQOwnDM8.XoLbfYmIALHBBM6NjVLUMiML/VuTgIq', 'default.jpg', 'Brigita Swilasari Puspitarini', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'c049dc9aba9444e9bef14a5c05f04a59', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('03e6d8cb79ab4b1cb8f907c39ea4b11b', 'orderperti@gmail.com', '$2y$10$4vbwZ58dqW5mAPntJFhY5.uMGLsD4YIP9Le/IKh.K7OIHoUIaaHbq', 'default.jpg', 'Palar Putra Patria', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cba340ae34984106a28fb87dbd3c4f84', '1ddca5f391d44e24be22c5e8d755aa34', '1', '0', NULL, NULL, '735d609249d649f3b4e4296d69ab0fd6', '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, '2022-05-30 21:35:50', NULL),
('072df3b932394d6caacb5c9c0960d42b', '008.penerbitan@gmail.com', '$2y$10$mvyZjgC26vZ47fGvHarmMuRCe6XJF5ofVMZNMyOgzBagJOnrh9ub6', 'default.jpg', 'Florentina Meiana Utami', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', 'c9f0106ff972412393227db316d03cb9', '1', '0', NULL, NULL, '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('08a09b6b1c554d8f8a9b847d9e6586e0', 'erang.risanto@gmail.com', '$2y$10$1u90c0B5C4rcgz0mOR5aAOgrIewJt9AXVoqg0B/w8aDdnjeHcEQxe', 'default.jpg', 'Erang Risanto', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'd82ca3c239504b8e8529679a267736e8', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('09305e5e563a459bbaf6a0d7b2493bd1', 'sumarti@gmail.com', '$2y$10$cfIh5ltf5DEPuw8pnbghwOs35Z.pCb7VE33.BhBNlXOXTaRspUape', 'default.jpg', 'Sumarti', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'de6a811cebee4145b0a7bdb223c29c0e', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('0c5a151afe204df2bf6c38485055da16', 'fungky2310@gmail.com', '$2y$10$BnKjc37Poi9y.zncMnrk8.1AyQYngCIj0ZB9BLAyfIFCqkDvxuds.', 'default.jpg', 'Fungki Repiana', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'dc8f27ac87ef4feb89bbb6eb13887c4e', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('0ecea60f2691405585fa1aa535368bee', '197.penerbitan@gmail.com', '$2y$10$9pakJxx7NrDY0ynBqC0qXunO0H5EdHQ9Nz3it.mwlqvb00CnIsjba', 'default.jpg', 'Dwi Prabantini', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', 'c9f0106ff972412393227db316d03cb9', '1', '0', NULL, NULL, '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('12c8a8639d814102b01c7ffc0cd52e71', 'lindaika@gmail.com', '$2y$10$6y.0DY0vSoRipDWzWO.aeugf/IJjFOCW2S7.dqKHXSDAIlNBQVU0y', 'default.jpg', 'Yosephin Linda Ika Pascalisa', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'dc8f27ac87ef4feb89bbb6eb13887c4e', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('1beceaecfa3f45c8be6d2f761aea389f', 'naskahmou@gmail.com', '$2y$10$P8H6syiCYKuZz4sNr034DuyPoWabcwZ5u/48R6r4BhLe60AWiGWuG', 'default.jpg', 'Lily Erlinawati', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', '43e6121f78dc4ddbbc98011d9eb662a7', '1', '0', NULL, NULL, '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('1e5077100ccb4fe48012e16ddf0200d9', 'dewani.harbunawati@gmail.com', '$2y$10$K.jxqHugo3PJda3hpYLT7O2Y6LBCCH7vmuyhnCreM6IIqsBowpuDW', 'default.jpg', 'Dewani Harbunawati', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'd82ca3c239504b8e8529679a267736e8', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('205cbcb8d74646f7bfafd9b4972f04ac', 'apriyo@gmail.com', '$2y$10$54Gu05ZqCieS3dwFC2adAu4M749mCjEc77VrMEVUNwd9i9Dtk4Ryy', 'default.jpg', 'Andreas Priyo Saddono', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'fda3c745c5d6410cb8eb25fb41ae4d36', '58fc78b637424cb894a238014974a6d8', '1', '0', NULL, NULL, '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('214af5d98cc74484be4ef05aa56a9e45', 'pemasaran@gmail.com', '$2y$10$GfutH1idi08pXVloCI.Q2.4yPE0.UcCXNndCRB248QE.jBHTUdbYW', 'x70wjisXzIpEiHQ94L0nV8DOIRle3Q4FXCHdy7EG.jpg', 'Pemasaran Contoh', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '1f7a55f145f84a9e9fbfa218482a2668', '58fc78b637424cb894a238014974a6d8', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '214af5d98cc74484be4ef05aa56a9e45', NULL, NULL, '2022-10-28 07:08:34', NULL),
('25fbb5a3233e4cf6a889b412691149d1', 'editor@gmail.com', '$2y$10$4lvU0hyZnmon1i2vnnZSC.IAFUkDRecHbzyHK.ePXUjn3guoxR8UC', 'default.jpg', 'Editor Contoh', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '1f7a55f145f84a9e9fbfa218482a2668', '58fc78b637424cb894a238014974a6d8', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('2d4e15b2176e49a1bb7d54d06c8f935f', '139.penerbitan@gmail.com', '$2y$10$TLzzdC/9XVdXG2Npmliq7eQpV713UUif/pDV05z/wOMBg.Polx9JC', 'default.jpg', 'Antonius Teguh Budi Suprapto', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', '3189d4fa029542e090632ae162f7cf3e', '1', '0', NULL, NULL, '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('303f2de11d4f472d869757d7ea5bb83f', 'susitjen@gmail.com', '$2y$10$aViInhT6NPTOgMMxOTk9meoY9WwrQSdcVBnopQL96Egdq0KWk3vI2', 'default.jpg', 'Susi Tjen', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'ed208bf0aa754529998e02335f803161', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('355c4e0e850c43f382cf1052f7053f40', 'setter@gmail.com', '$2y$10$13QUAJQqtqtcjEaVEM7k2e3OmdaiuVHyQIh7TTXgH1ToAa/oqHWsO', 'default.jpg', 'Setter Contoh', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '1f7a55f145f84a9e9fbfa218482a2668', '58fc78b637424cb894a238014974a6d8', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('3d43ab399ec24c30b39c9b052686416d', 'andangsuhana@gmail.com', '$2y$10$Y9f1Riuchs9b2rMviUMNk.Y9rfVYMt4h0NfWIBzCzYip6Urzsman6', 'default.jpg', 'Andang Suhana', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', '5cde3d235c7c446e9f1f08a4c05eec1e', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('417efb26a79e4c15966aea36536478ff', 'prodev@gmail.com', '$2y$10$clxJYFdvPNnuSFeGoQ/fYeSbbhC5NKNvCGsTSrcGFpifMIos7YLh6', 'default.jpg', 'Prodev Contoh', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '1f7a55f145f84a9e9fbfa218482a2668', '58fc78b637424cb894a238014974a6d8', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('46cc8c6792634b3eb0c0da7c38763d01', 'ao.marketingsup02@gmail.com', '$2y$10$2ezjwa3ApeVZMvUEIZG90e.ChzdJKA7ywVSBnUH5maSTFSTy8803O', 'default.jpg', 'Maria Veronica Frida Astuti', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cba340ae34984106a28fb87dbd3c4f84', '4cebe6a298b74c11965343d3adffc40d', '1', '0', NULL, NULL, '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('48ca3074299d46d7b557343ab9106671', 'ofriyunus@gmail.com', '$2y$10$EsE9v7ON01NheKRK0JStUuI9sOA0wR8r1plkLxcYGPXSuRh7.OlAy', 'default.jpg', 'Ofri Yunus Sapya', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', '5cde3d235c7c446e9f1f08a4c05eec1e', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('4a0d3c09979f4a6b8bedbfa822e492bf', 'robertus_arianto@gmail.com', '$2y$10$R4..Cx.m/7RA7LBxhT9xHucOpo/sUVQzEjGMT62NbywYb3CQQOA1C', 'default.jpg', 'Robertus Arianto', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'cb12c05907784373b8fee1128f6a340f', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('52706514ef294f11807d246bc701fb7a', 'fransiscawiwin@gmail.com', '$2y$10$fIDBEw1LDMqbh9oRuKAUKuiovWfCtiPnWACqjnkk5mTZSwxEG109C', 'default.jpg', 'Fransisca Wiwin Tety Utami', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', '3c8b59472d4b4d26bad0de7427a5b225', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('54281e5fceba4bf092c79ce994537ed3', 'agustinussaputra52@gmail.com', '$2y$10$T6PZrji0dQsGPUQodvK9JeFa1g81TIlY5DWaf2SJ2n2T0oHntQJIO', 'default.jpg', 'Agustinus Subardana', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cba340ae34984106a28fb87dbd3c4f84', '1cd910eee9054a7d8d820ab23944a4cd', '1', '0', NULL, NULL, '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('5537f8f560a549e88d7a443b801bb1af', '135.penerbitan@gmail.com', '$2y$10$C3csXjpjI/d/66022Nc0qOwBMBdoxqe30oNs3NpnRsUXy6L3M8fWC', 'default.jpg', 'Cecilia Anita Herawati', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', '58fc78b637424cb894a238014974a6d8', '1', '0', NULL, NULL, '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('57a9534bd79d4382bb0f43c89910702c', 'verina@gmail.com', '$2y$10$FZc1p4ilqo0EQGI27.bCzufMs8NLlrkIclDl/aTn.2peysiYlsD/2', '6k7QIkCEM9lggCUUvgoTey49AIUfUZcm4joGg1Qy.jpg', 'Verina', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '1f7a55f145f84a9e9fbfa218482a2668', '58fc78b637424cb894a238014974a6d8', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-05-30 19:42:23', NULL),
('59ed337897f347659c6c78270df47a80', 'managerrohanieva@gmail.com', '$2y$10$6b5r4t/PaT4IdifwDIanXut.crtyp1kZ6tETvOka8SgbWsRe/QPSa', 'default.jpg', 'Eva Renta Agustina Sibarani', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cba340ae34984106a28fb87dbd3c4f84', '542c06e42b784cafbae8a1666bae508a', '1', '0', NULL, NULL, '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('5aadc2a04f7b48aa8a62ea85a7c0f93c', 'hadi2y@yahoo.com', '$2y$10$aZkrgkdRS0HuDyOJ0WDhIuzp5K7ufFtaQDP7faS1m.LxQlG9yYBZS', 'default.jpg', 'Sucipto Hadi', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '210f90ae56bf4428a061f0402f3a9ef6', '21a8760a6b0b4526924b68df47c1faf6', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('5bd2dc1dd25945eb81b32547ea6ba0e8', 'pelangiputeri588@gmail.com', '$2y$10$DFzzrbV0kE2uNgDFMG3XZOCL7CAiySsrBcQQedR08R7DcbHyKmdce', 'default.jpg', 'Barbara Tarandita Pelangi Puteri', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'c049dc9aba9444e9bef14a5c05f04a59', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('5e26c2b906a543989ed3a9abba178fe1', 'samudromadderswae@gmail.com', '$2y$10$BQdk9lT3AbvTQGEpOn8KheF8hY94a1lgsHAn6ihP5RfZyLrcOa3Ou', 'default.jpg', 'Samudro Cahyono', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', '58fc78b637424cb894a238014974a6d8', '1', '0', NULL, NULL, '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('5f64a6100114456a8905b8dfc1dc1460', 'penerbitan@gmail.com', '$2y$10$H69ntwUUmmBNP.NcLjFiaezEdEWaV5pix4E7qJHBC.ENQVpCbgxJ2', 'default.jpg', 'Penerbitan Contoh', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '1f7a55f145f84a9e9fbfa218482a2668', '58fc78b637424cb894a238014974a6d8', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('69d1e267bf8944d7b36b8ba263285f30', 'virgo_anggiat@gmail.com', '$2y$10$txr5Iq5xlFUQ/rAACjRTcuCmnJFa0IQH0WZjowws0VFdP2nStJrAe', 'default.jpg', 'Virgo Anggiat Panahatan Napitupulu', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', '516acf73601146d9bf0d19307be28b70', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('6fc8b6f16e394f6f802ece3753ba36dc', 'andika.aji@gmail.com', '$2y$10$Gz6/KQaYuaAiD1CdAjO2U.5t5uYoRluEfe41tK5wUbUQxzdpp9wyW', 'default.jpg', 'Andika Sundoro Aji', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'a57da1e8017b446da73d04d5e0d4dfb3', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('701e66044f1a4bd4abc8ff4bc8a82997', 'agustinussaputra52@yahoo.com', '$2y$10$e1QpnZI/e/.Yzj9Dfnt8MeOhjXwsSgLVUThrbeUAJ0rwKio/GVXxq', 'default.jpg', 'Agustinus Subardana', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'd252d685b7cc469cbc7063a03a70a26c', '71b2e233ed99473e82bac2a1470e973b', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-27 08:54:43'),
('735d609249d649f3b4e4296d69ab0fd6', 'vaniamarditaa@gmail.com', '$2y$10$vQ2zyYhaf2NB4D9.GBmK6eHdNNAu5sybqWSinP5H3L94nma10MO46', 'default.jpg', 'Elisabeth Vania Mardita', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', '07e058a9224645df94a5e5c7894c9ed1', '1', '0', NULL, NULL, '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, NULL, NULL, NULL),
('77e9d0dacb154a37b5730efb8d36ad29', '046.penerbitan@gmail.com', '$2y$10$4BqR.DkoxTz.KZ0dOn3TmO.nGWOqjjfTWLuW4JFz8jMqbgymsrJFG', 'default.jpg', 'Dyna Novitasari', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', 'c9f0106ff972412393227db316d03cb9', '1', '0', NULL, NULL, '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('7b821837e1a2435cb01c397b20ee0202', 'danynofiyanto@gmail.com', '$2y$10$ykjusZvqaFFiDqzGNXoB0uQbs5Ni6WcrzmL2ncHcIdb9Pnv.56CsS', 'default.jpg', 'Dany Nofiyanto', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', '5cde3d235c7c446e9f1f08a4c05eec1e', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('7c4f86aba34f430884fb87fee84bc870', 'ferdylucker@gmail.com', '$2y$10$4EsVFMMPCP2l0rjRuLiIWel50QldDXz5j/9stPL.CnC7oDMN1FM96', 'default.jpg', 'Ferdyawan Listanto', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '1f7a55f145f84a9e9fbfa218482a2668', '7a9181a87fc34b4083e6e9e44594a1ac', '1', '0', NULL, NULL, '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, NULL, NULL, NULL),
('7cba06238a524d06b3960524f8e08662', 'vaniopraba@gmail.com', '$2y$10$ZgPzmeTZ9vunOqOTnBlV4.nAJIkf0AcVGvVum5uCMIrJSbs1cOs8y', 'default.jpg', 'Vanio Praba Pradipa', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'a57da1e8017b446da73d04d5e0d4dfb3', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('7e7bb093dc0e4fb98f3d8a37b92d4457', 'edis.mulyanta@gmail.com', '$2y$10$QFXL6w1nRsTqVa.MsrXlxOeeNgpymjV3Ou5dxB2tBWLLykLznOAFC', 'default.jpg', 'Edi Srimulyanto', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', 'b14c6581b9524832a107ae12a0518ba3', '1', '0', NULL, NULL, '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('7ff48adfec9b44b4bfce7173c012a8c7', 'pemasaran2@gmail.com', '$2y$10$FrS9ouEo/bQdJi8XprQaHusbmOvExtKgXnjyeT3AmfOQVGyh3d/Wu', 'Y9cGO9LQl7PiB3oGur4HxR7ks1fz1rySHqHJODPt.jpg', 'Pemasaran Contoh 2', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '1f7a55f145f84a9e9fbfa218482a2668', '04ed4cfa8aef4957bcc68682e300b74c', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '7ff48adfec9b44b4bfce7173c012a8c7', NULL, NULL, '2022-10-27 11:22:48', NULL),
('8023aebc96d3430baa641124e7da7c39', 'irwanfirdausmulia@gmail.com', '$2y$10$iwvKNEEiZq6p9yIe/68Enu35c.k0OrOr.4Y1lbXU02Wbb0dHPjMIm', 'default.jpg', 'Irwan Firdaus Mulia', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', '5cde3d235c7c446e9f1f08a4c05eec1e', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('8095be9de5414f7697531da885119cc3', 'dian.christine@gmail.com', '$2y$10$k8pW0MvnFGC99Wbkb/KVgOee0WVWZcfbskl9EjQfSyF397OidFaqi', 'default.jpg', 'Dian Christine Fitriasari', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'ed208bf0aa754529998e02335f803161', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('8444ed9de99e4c429bf93b082a19e258', 'daud_yosefsinu@gmail.com', '$2y$10$S4wD8P3/AfjzIePefuXIAuKoDthx09QIHPcm6ijaa7RZmNLgoB9rW', 'default.jpg', 'Daud Yosef Sinu', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'de6a811cebee4145b0a7bdb223c29c0e', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('86ce9a31dae44411a568cc4b97dad7fd', 'yatmitugiyanti@gmail.com', '$2y$10$KED9816G13PdeZBHtyNMve0c8J2SOjEC75WNPvn3k0bOKgs.FKWgu', 'default.jpg', 'Yatmi Tugiyanti', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'c049dc9aba9444e9bef14a5c05f04a59', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('92a3212dc42445bfbd9846a0f6cf02b3', 'marcellakikaayu@gmail.com', '$2y$10$MfglXED1KLHtK/HdqfCNmOwPcGNcsRM2OqCFoinoOMQa8doR9bTGK', 'default.jpg', 'Marcella Kika Ayu Swastanti P', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'ed208bf0aa754529998e02335f803161', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('9f72c952b9ea4474a912d619cba5d0a8', 'yehezkiel.gondowijoyo@gmail.com', '$2y$10$icRrW.cPQ7RU5Kcu4xaHi.oGNjD6SkZspc0qJwmvhBONEVt7nUwsu', 'default.jpg', 'Yehezkiel Arman Gondowijoyo', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '06b17377feea4c9abad79795c52d5a02', 'fe067d18155c4f298f07a409f6aca5a4', '1', '0', NULL, NULL, '735d609249d649f3b4e4296d69ab0fd6', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-20 07:31:01', NULL),
('a136abd42d7a40e4a4c58220184bf1ae', 'ferryannugroho@gmail.com', '$2y$10$DaDrJt/dEE7y4ef5C82N1O.Z/Wnrg3ERo6LSlBfr7rhxAZsBECrcG', 'default.jpg', 'Ferryan Nugroho Purnomo', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', '5cde3d235c7c446e9f1f08a4c05eec1e', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('a1b814111063435eb7a862c18d1de4cf', 'eri.pracetak@gmail.com', '$2y$10$k0er1LUC3/9aF0GUmay9geHHMapuMzE9C/ejW.ZI/UnpnESaCrrbW', 'default.jpg', 'Eri', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', '9a0bb34735424842ba98c5e0f1c11b95', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('a400e4bcf70d40d78224043cc95e6241', '064.penerbitan@gmail.com', '$2y$10$stfG2FHtqQcpPsb31qlLueNBYDYFs5KgYpuvzMQp5S2O0ug0zKauO', 'default.jpg', 'Ignatius Eko Agus Wibowo', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', 'c9f0106ff972412393227db316d03cb9', '1', '0', NULL, NULL, '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('a4f8d1d67d2e4b9aa2a8e8680a953194', 'binudisenoaji@gmail.com', '$2y$10$yNoqQFRMmjY.yOBWiuoGJOCggYOaF1C/xlXRAgfe4bM.ViqKFcy/G', 'default.jpg', 'Binudi Seno Aji', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'de6a811cebee4145b0a7bdb223c29c0e', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('a5496cc344a24908835563d47e108a52', 'project.andigroup@gmail.com', '$2y$10$78zuslsWqmGovTs92egBW.YofVDaPewD.KAxtw.SlN22LQvfeDRxC', 'default.jpg', 'Hendrix Dani Sanjaya', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cba340ae34984106a28fb87dbd3c4f84', '86bdfb7e323148b89c0316caea05b113', '1', '0', NULL, NULL, '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('a977a8769e2f4cfb950c9253a903486b', 'yuliusbasuki@gmail.com', '$2y$10$psoVtMm4zmOzWe8OKuqiGutOorHTL2IS/mmTYvvRMD1f1B4.lIJf.', 'default.jpg', 'Yulius Basuki Adi Wibowo', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'de6a811cebee4145b0a7bdb223c29c0e', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('ab76995143c94f07b6cadc832f252752', 'dirke@gmail.com', '$2y$10$0AhgSN1fqIgMjJcO1OBkCOObEdEKAR2NrGi99/lrI53ySC7bIIcdG', 'default.jpg', 'Direktur Keuangan', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cba340ae34984106a28fb87dbd3c4f84', 'bb909b2d2d1941b1b0dc8564e66eea7e', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('b18e4ff295b24fd2bab92717b85504b6', '442.penerbitan@gmail.com', '$2y$10$H7gkvA6v1zcscJj.moRBHOsK9v75N83EbQiDKB2GAC/26JZgYZqKC', 'default.jpg', 'Dwinita Andriastuti', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', 'c9f0106ff972412393227db316d03cb9', '1', '0', NULL, NULL, '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('b480397ca0e14eea90fb27acdfb7cd27', 'vindyarahayu@gmail.com', '$2y$10$wiD3ehEco.rkaVTM301rCO.ew1OLIXuGp.arJvaM/lOddIJ58ld1O', 'default.jpg', 'Vindya Puspasari Rahayu', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'de6a811cebee4145b0a7bdb223c29c0e', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('b7b8f96b9f534c3b96e1122dfe18317e', 'panuwun.budi@gmail.com', '$2y$10$14AcogjWJMtIASyOc035ZuRRlaMVg0ngtxI.EMeZaOSarOhpb4sSq', 'default.jpg', 'Panuwun Budi Raharjo', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', 'c0d1b2987056470bb3ffc8f499c40b85', '1', '0', NULL, NULL, '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('ba360e2a572f45979cb83648fc5e2ec7', 'ginanjar_raharjo@gmail.com', '$2y$10$P1tC7e5I38BY12YeJxG.zOtKm1CktaK7qnKWjsZKPzueXLuv3i9hK', 'default.jpg', 'Ginanjar Raharjo', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', '516acf73601146d9bf0d19307be28b70', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('ba7eba932acf4d379eb53af0da80ea9b', 'monda.nasarena@gmail.com', '$2y$10$0ozO10q4NbRRC8Zq8on1JuylrWoLRd7XUN/kTC36G4FTH5FqSVvyO', 'default.jpg', 'Elisabet Diamonda Nasarena Apriliani', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'c049dc9aba9444e9bef14a5c05f04a59', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('bc95dda2d2a741728a97de102d1c2155', 'manajerbukurohani@gmail.com', '$2y$10$wl.9In/bLwqp65nwfh7wrOJLm71cYfvgVU3eBI3tBiZm/wDygfm/C', 'default.jpg', 'R.S Ronny Kurniawan Darsono', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cba340ae34984106a28fb87dbd3c4f84', 'd11c1f0d64034db09972dbf57aa694d5', '1', '0', NULL, NULL, '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('be8d42fa88a14406ac201974963d9c1b', 'admin@gmail.com', '$2y$10$JfdI4rvmfegdd97t9xvgB.YWs5PQhfN4IlkOzgLoWrDeZaYDvL6Lu', 'default.jpg', 'Super Admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('c47a1a96c06642db86350d8b442911d1', 'yudantodwi81@gmail.com', '$2y$10$wjqwFz2CWe4TfObIvCsw4O.Nvk5Co9HDrJgLsXn2ByNcdxwEEskcS', 'default.jpg', 'Yudanto Dwilaksono', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cba340ae34984106a28fb87dbd3c4f84', '300c4db6ea724f4c80e6a64497304b77', '1', '0', NULL, NULL, '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('c94ad7236255430b82c0546dd82b917e', 'direkturmarketing.ops@gmail.com', '$2y$10$bl8y7u8tBANan1vUxmpp4O2i2mkMv8KOvKJBwWbzY/hDRyD6eYH7e', 'default.jpg', 'Adi Kristianto', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', '387ef8138f174e4f9fb2f2d147f03a71', '1', '0', NULL, NULL, '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('ccfbf9f0386e416fbeac1e9893b195f7', 'venantiusindar@gmail.com', '$2y$10$nVghZTVY41S9dcrKVR0Bcu6INGpcdXMeTd7ANY3ycP76wS1WYNOMW', 'default.jpg', 'Venantius Indar Anantya', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'dc8f27ac87ef4feb89bbb6eb13887c4e', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('ceadd9fb648445eab1e350357e51d1ce', 'prodev2@gmail.com', '$2y$10$3.6xzJWjIE04eYzQGgC2PeoABS58MoXMkHDLSH942fjQuEzBS8PA2', 'PUBeUEWEO2oBcHR23gZbHwQylLfkg3N3ftAjoc7f.png', 'Prodev 2', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'c9f0106ff972412393227db316d03cb9', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-10-28 19:43:58', NULL),
('d205099db6784d6bb6475da49aaf9458', 'tabithasa25@gmail.com', '$2y$10$6AbCvSlzTG54GSYW.DBlDOHGCG7Vz2UKPlQRvL18q2By4Wdf0dS8C', 'default.jpg', 'Tabitha Sonia Ayudea', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'a57da1e8017b446da73d04d5e0d4dfb3', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('d3884f7b830d47f8b5b8ab7f8abc7530', 'skolastika.c@gmail.com', '$2y$10$JmRLEc6K/ktNyqyzbsSGbuEqWpxX2gzNu8RwKYddOIXESw1cwKgaK', 'default.jpg', 'Skolastika Cynthia Maharani', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'cb12c05907784373b8fee1128f6a340f', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('dc306f83529445139c16dd55b4f0e6f3', '314.penerbitan@gmail.com', '$2y$10$nmWPVq600NkZO0X4XvFTq.9KiKOx8Ctf7uN8jwQGyOn/UVIIGBpKi', 'default.jpg', 'Tjahjono Tri Wibowo', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', '620feb2d0dea419f83964e844c7b2003', '1', '0', NULL, NULL, '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('dc4547d5a820478188ae9c1a1ae876a6', 'adminpenerbitan1@gmail.com', '$2y$10$HiLR8j0cEUyoq6tWWO676uII119MIf48.ycq1NDEoZ3AJKqy/lyhS', 'default.jpg', 'Admin Penerbitan', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', '99ff5b9cc25b497fa1bc8314efa4ba20', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('e4d01f4ae38a4aca8108ee3713ff0dcc', 'radhityaindra@gmail.com', '$2y$10$dAYqGUNRFDZVhjYvsVjTc.7/9MtHpIulzRg/IzkXJTROQ1guEs.6q', 'default.jpg', 'Radhitya Indra Arhadi', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'd82ca3c239504b8e8529679a267736e8', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('e829fe4fb03f45f482f77653158d461c', 'wanodyadwi@gmail.com', '$2y$10$WjaKVLKoHYGLzSe6XmhxO.HsJBBRTbS/vrFr3ZLpDa/aztNXrBenS', 'default.jpg', 'Brigitta Wanodya Dwi Putriningtyas', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', '426036aaba6d4ebcb7fac38c49e3e626', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('e83ca4537495486c8d3b5d7e6ae2407a', '227.penerbitan@gmail.com', '$2y$10$TpnTqwmNeUB1PUMrHZrAquxf0trwsICTBcMoA3mYwORwF5DD9L0LK', 'default.jpg', 'Brigitta Pasca Puspita Sari', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', 'c9f0106ff972412393227db316d03cb9', '1', '0', NULL, NULL, '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('ee2c544aa4dc4c1eb12472cd84406358', 'direksi@gmail.com', '$2y$10$jBLTb06gat36o2jtzb1eQ.led9dh6KjLWAOQq8fQyT4I3RQC64cL2', 'default.jpg', 'Direksi Contoh', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '1f7a55f145f84a9e9fbfa218482a2668', '58fc78b637424cb894a238014974a6d8', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('ef814604b5ed42b3b260ace20767c037', 'lidyamayasari@gmail.com', '$2y$10$dLUe.3b5wBX2QI.1dOYd3eKUTg69bfgiJj4/l7xgBWgARlfNzD/QC', 'default.jpg', 'Lidya Mayasari', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'ed208bf0aa754529998e02335f803161', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('f38be109ef1a42eb9a9b9d4de30cd6a0', 'titopenerbitandi@gmail.com', '$2y$10$6spe9FUsY04.N61y59nvBeYCjvz7CA6taFwXpuTbMnh0uYeiYK/Km', 'default.jpg', 'Astito Puput Wijanarko', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'fda3c745c5d6410cb8eb25fb41ae4d36', '7d4184188a694c55bd2e6c945182c249', '1', '0', NULL, NULL, '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('f88116e1873c4403a7fb76a08f5266cb', '423.penerbitan@gmail.com', '$2y$10$p/h3lk9TZ/t9lDj96kzxVehLNnZWX60R9Au6NAjs1vFI8ASQVbu.6', 'default.jpg', 'Jesicca Deviyanti', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', 'c9f0106ff972412393227db316d03cb9', '1', '0', NULL, NULL, '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('fab4f858e0314d1dbf6b5b834007313e', 'yuliafransisca@gmail.com', '$2y$10$DJz/vjwxw0bB4enSuwxsUOH/1S71OPQx9CfdYv4/eh8JYU38lN7ki', 'default.jpg', 'Yulia Fransisca Ayuningrum', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'ed208bf0aa754529998e02335f803161', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('fb0d2a39c503408bb67a5f613577cae9', 'julioprakoso@gmail.com', '$2y$10$S.wRryXYTatDj6QuXVdSVO7BA8/E6bKydqmBx7OrqUlvPaMRyE7XK', 'default.jpg', 'Dea Julio Prakoso', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'de6a811cebee4145b0a7bdb223c29c0e', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('fbba0a06756b43c69a01c961616ff978', 'ivan.fernadin@gmail.com', '$2y$10$60RfEDnsTjU4BMlbdlvXJusUsBpurYg4hdjNW.eBH3P/3n7.BfVU2', 'default.jpg', 'Ivan Fernadin', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '1f7a55f145f84a9e9fbfa218482a2668', '987d4c4e12a74897b3478f23e757d732', '1', '0', NULL, NULL, '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, NULL, NULL, NULL),
('fc2ba902bcfa410397bbd3331bda13ec', 'arieprabawati@gmail.com', '$2y$10$GGUKn8VW8mSzT0b2vlNbhu8mnMdEZGeENDO5GGwVDM7D8RwLdPNaa', 'default.jpg', 'Th. Arie Prabawati', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'ed208bf0aa754529998e02335f803161', '1', '0', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_log`
--

CREATE TABLE `user_log` (
  `id` bigint(20) NOT NULL,
  `users_id` varchar(36) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_log`
--

INSERT INTO `user_log` (`id`, `users_id`, `ip_address`, `last_login`, `user_agent`) VALUES
(1, 'be8d42fa88a14406ac201974963d9c1b', '192.168.6.42', '2022-11-29 11:18:44', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(2, 'be8d42fa88a14406ac201974963d9c1b', '192.168.6.42', '2022-11-30 10:24:05', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(3, 'be8d42fa88a14406ac201974963d9c1b', '192.168.6.42', '2022-11-30 10:27:43', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(4, 'be8d42fa88a14406ac201974963d9c1b', '192.168.6.42', '2022-11-30 10:31:29', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(5, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '192.168.6.46', '2022-11-30 10:59:13', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36'),
(6, 'be8d42fa88a14406ac201974963d9c1b', '192.168.6.42', '2022-11-30 14:06:34', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(7, 'be8d42fa88a14406ac201974963d9c1b', '192.168.6.42', '2022-12-01 11:03:30', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(8, 'be8d42fa88a14406ac201974963d9c1b', '192.168.6.42', '2022-12-01 11:26:21', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(9, 'be8d42fa88a14406ac201974963d9c1b', '192.168.6.42', '2022-12-01 11:27:07', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(10, 'be8d42fa88a14406ac201974963d9c1b', '192.168.6.42', '2022-12-01 11:27:51', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(11, 'ceadd9fb648445eab1e350357e51d1ce', '192.168.6.42', '2022-12-02 15:45:19', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(12, 'be8d42fa88a14406ac201974963d9c1b', '192.168.6.42', '2022-12-05 09:18:01', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(13, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '192.168.6.42', '2022-12-05 15:19:21', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(14, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '192.168.6.42', '2022-12-07 08:50:18', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(15, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '192.168.6.42', '2022-12-08 11:47:40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(16, 'ba360e2a572f45979cb83648fc5e2ec7', '192.168.6.42', '2022-12-08 13:24:40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(17, 'e83ca4537495486c8d3b5d7e6ae2407a', '192.168.6.42', '2022-12-09 09:55:30', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(18, 'e83ca4537495486c8d3b5d7e6ae2407a', '192.168.6.42', '2022-12-09 10:29:50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(19, 'ceadd9fb648445eab1e350357e51d1ce', '192.168.6.42', '2022-12-09 16:30:38', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(20, 'e83ca4537495486c8d3b5d7e6ae2407a', '192.168.6.42', '2022-12-12 09:14:56', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(21, 'e83ca4537495486c8d3b5d7e6ae2407a', '192.168.6.42', '2022-12-13 09:42:29', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(22, '12c8a8639d814102b01c7ffc0cd52e71', '192.168.6.42', '2022-12-14 08:13:12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(23, '12c8a8639d814102b01c7ffc0cd52e71', '192.168.6.42', '2022-12-15 08:31:21', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(24, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '192.168.6.42', '2022-12-15 16:19:52', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(25, '12c8a8639d814102b01c7ffc0cd52e71', '192.168.6.42', '2022-12-15 16:35:09', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(26, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '192.168.6.42', '2022-12-16 08:34:50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(27, '12c8a8639d814102b01c7ffc0cd52e71', '192.168.6.42', '2022-12-16 10:53:09', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(28, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '192.168.6.42', '2022-12-16 10:57:19', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(29, 'ba360e2a572f45979cb83648fc5e2ec7', '192.168.6.42', '2022-12-16 16:01:55', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(30, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '192.168.6.42', '2022-12-19 08:28:59', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(31, 'ba360e2a572f45979cb83648fc5e2ec7', '192.168.6.42', '2022-12-19 14:25:09', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(32, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '192.168.6.42', '2022-12-20 09:14:36', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(33, 'ba360e2a572f45979cb83648fc5e2ec7', '192.168.6.42', '2022-12-20 09:15:16', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(34, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '192.168.6.42', '2022-12-20 10:37:43', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(35, 'ba360e2a572f45979cb83648fc5e2ec7', '192.168.6.42', '2022-12-20 10:45:52', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(36, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '192.168.6.42', '2022-12-20 10:48:15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(37, 'ba360e2a572f45979cb83648fc5e2ec7', '192.168.6.42', '2022-12-20 11:16:33', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(38, '12c8a8639d814102b01c7ffc0cd52e71', '192.168.6.42', '2022-12-20 11:21:30', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(39, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '192.168.6.42', '2022-12-20 14:45:53', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(40, '12c8a8639d814102b01c7ffc0cd52e71', '192.168.6.42', '2022-12-20 14:46:42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(41, '12c8a8639d814102b01c7ffc0cd52e71', '192.168.6.42', '2022-12-21 08:41:04', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(42, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '192.168.6.42', '2022-12-21 08:46:58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(43, 'ba360e2a572f45979cb83648fc5e2ec7', '192.168.6.42', '2022-12-21 08:47:45', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(44, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '192.168.6.42', '2022-12-21 10:28:30', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(45, 'ba360e2a572f45979cb83648fc5e2ec7', '192.168.6.42', '2022-12-21 10:59:57', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(46, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '192.168.6.42', '2022-12-21 11:07:00', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(47, 'ba360e2a572f45979cb83648fc5e2ec7', '192.168.6.42', '2022-12-21 11:09:50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(48, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '192.168.6.42', '2022-12-21 11:24:14', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(49, '12c8a8639d814102b01c7ffc0cd52e71', '192.168.6.42', '2022-12-21 11:25:50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(50, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '192.168.6.42', '2022-12-21 13:11:11', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(51, 'ba360e2a572f45979cb83648fc5e2ec7', '192.168.6.42', '2022-12-21 13:13:24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(52, '12c8a8639d814102b01c7ffc0cd52e71', '192.168.6.42', '2022-12-21 14:30:28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(53, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '192.168.6.42', '2022-12-21 14:33:10', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(54, 'ba360e2a572f45979cb83648fc5e2ec7', '192.168.6.42', '2022-12-21 14:33:43', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(55, '12c8a8639d814102b01c7ffc0cd52e71', '192.168.6.42', '2022-12-21 14:34:44', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(56, 'be8d42fa88a14406ac201974963d9c1b', '192.168.6.42', '2022-12-27 08:35:32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(57, 'be8d42fa88a14406ac201974963d9c1b', '127.0.0.1', '2022-12-28 09:40:22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(58, 'be8d42fa88a14406ac201974963d9c1b', '127.0.0.1', '2022-12-28 09:43:35', 'Mozilla/5.0 (X11; Linux x86_64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(59, 'be8d42fa88a14406ac201974963d9c1b', '127.0.0.1', '2022-12-28 09:43:36', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(60, 'be8d42fa88a14406ac201974963d9c1b', '127.0.0.1', '2022-12-28 11:20:11', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(61, '3d43ab399ec24c30b39c9b052686416d', '127.0.0.1', '2023-01-04 13:33:51', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(62, '3d43ab399ec24c30b39c9b052686416d', '127.0.0.1', '2023-01-04 13:34:31', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(63, 'e83ca4537495486c8d3b5d7e6ae2407a', '127.0.0.1', '2023-01-04 14:24:29', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(64, '3d43ab399ec24c30b39c9b052686416d', '127.0.0.1', '2023-01-04 16:00:51', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(65, 'e83ca4537495486c8d3b5d7e6ae2407a', '127.0.0.1', '2023-01-04 16:43:28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(66, '3d43ab399ec24c30b39c9b052686416d', '127.0.0.1', '2023-01-04 16:45:02', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(67, 'e83ca4537495486c8d3b5d7e6ae2407a', '127.0.0.1', '2023-01-06 08:35:00', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(68, '3d43ab399ec24c30b39c9b052686416d', '127.0.0.1', '2023-01-06 08:39:34', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(69, 'e83ca4537495486c8d3b5d7e6ae2407a', '127.0.0.1', '2023-01-06 08:57:40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(70, '3d43ab399ec24c30b39c9b052686416d', '127.0.0.1', '2023-01-06 09:00:00', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(71, '0007828bc2a5496bbdd8fbaefe2e1565', '127.0.0.1', '2023-01-06 09:27:35', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(72, '3d43ab399ec24c30b39c9b052686416d', '127.0.0.1', '2023-01-06 09:42:37', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(73, '0007828bc2a5496bbdd8fbaefe2e1565', '127.0.0.1', '2023-01-06 09:43:20', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(74, '3d43ab399ec24c30b39c9b052686416d', '127.0.0.1', '2023-01-06 10:10:20', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(75, '0007828bc2a5496bbdd8fbaefe2e1565', '127.0.0.1', '2023-01-06 10:21:10', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(76, '3d43ab399ec24c30b39c9b052686416d', '127.0.0.1', '2023-01-06 10:22:24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(77, '0007828bc2a5496bbdd8fbaefe2e1565', '127.0.0.1', '2023-01-06 10:34:32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(78, '3d43ab399ec24c30b39c9b052686416d', '127.0.0.1', '2023-01-06 13:21:50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(79, '0007828bc2a5496bbdd8fbaefe2e1565', '127.0.0.1', '2023-01-06 13:26:55', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(80, '3d43ab399ec24c30b39c9b052686416d', '127.0.0.1', '2023-01-06 13:28:39', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(81, '0007828bc2a5496bbdd8fbaefe2e1565', '127.0.0.1', '2023-01-06 13:36:52', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(82, 'be8d42fa88a14406ac201974963d9c1b', '127.0.0.1', '2023-01-09 11:53:22', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(83, 'be8d42fa88a14406ac201974963d9c1b', '127.0.0.1', '2023-01-09 22:31:02', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(84, 'dc306f83529445139c16dd55b4f0e6f3', '127.0.0.1', '2023-01-11 13:03:38', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(85, 'be8d42fa88a14406ac201974963d9c1b', '127.0.0.1', '2023-01-11 13:09:39', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(86, 'be8d42fa88a14406ac201974963d9c1b', '127.0.0.1', '2023-01-11 13:39:49', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(87, 'c94ad7236255430b82c0546dd82b917e', '127.0.0.1', '2023-01-11 13:42:37', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(88, 'c94ad7236255430b82c0546dd82b917e', '127.0.0.1', '2023-01-11 13:43:01', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(89, '9f72c952b9ea4474a912d619cba5d0a8', '127.0.0.1', '2023-01-12 08:53:23', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(90, 'dc306f83529445139c16dd55b4f0e6f3', '127.0.0.1', '2023-01-12 09:07:21', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(91, 'c94ad7236255430b82c0546dd82b917e', '127.0.0.1', '2023-01-12 09:08:08', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(92, 'dc306f83529445139c16dd55b4f0e6f3', '127.0.0.1', '2023-01-12 09:14:14', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(93, 'c94ad7236255430b82c0546dd82b917e', '127.0.0.1', '2023-01-12 11:58:08', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(94, 'dc306f83529445139c16dd55b4f0e6f3', '127.0.0.1', '2023-01-12 12:02:06', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(95, 'c94ad7236255430b82c0546dd82b917e', '127.0.0.1', '2023-01-12 12:28:55', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(96, '9f72c952b9ea4474a912d619cba5d0a8', '127.0.0.1', '2023-01-12 12:53:44', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(97, 'ab76995143c94f07b6cadc832f252752', '127.0.0.1', '2023-01-12 13:51:14', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(98, 'c94ad7236255430b82c0546dd82b917e', '127.0.0.1', '2023-01-12 14:00:13', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(99, 'dc306f83529445139c16dd55b4f0e6f3', '127.0.0.1', '2023-01-12 14:00:51', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(100, '9f72c952b9ea4474a912d619cba5d0a8', '127.0.0.1', '2023-01-12 14:02:48', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(101, 'c94ad7236255430b82c0546dd82b917e', '127.0.0.1', '2023-01-12 14:09:07', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(102, 'ab76995143c94f07b6cadc832f252752', '127.0.0.1', '2023-01-12 16:39:47', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(103, 'c94ad7236255430b82c0546dd82b917e', '127.0.0.1', '2023-01-12 16:45:43', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(104, 'ab76995143c94f07b6cadc832f252752', '127.0.0.1', '2023-01-12 16:46:54', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(105, 'c94ad7236255430b82c0546dd82b917e', '127.0.0.1', '2023-01-12 16:51:53', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(106, 'ab76995143c94f07b6cadc832f252752', '127.0.0.1', '2023-01-12 16:55:42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(107, '9f72c952b9ea4474a912d619cba5d0a8', '127.0.0.1', '2023-01-13 08:43:58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(108, 'dc306f83529445139c16dd55b4f0e6f3', '127.0.0.1', '2023-01-13 11:05:05', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(109, 'c94ad7236255430b82c0546dd82b917e', '127.0.0.1', '2023-01-13 13:22:03', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(110, 'ab76995143c94f07b6cadc832f252752', '127.0.0.1', '2023-01-13 13:23:13', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(111, '9f72c952b9ea4474a912d619cba5d0a8', '127.0.0.1', '2023-01-13 13:34:34', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(112, 'c94ad7236255430b82c0546dd82b917e', '127.0.0.1', '2023-01-13 13:35:26', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(113, '9f72c952b9ea4474a912d619cba5d0a8', '127.0.0.1', '2023-01-13 13:36:49', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(114, 'ab76995143c94f07b6cadc832f252752', '127.0.0.1', '2023-01-13 13:37:28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(115, '9f72c952b9ea4474a912d619cba5d0a8', '127.0.0.1', '2023-01-13 13:41:08', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(116, 'be8d42fa88a14406ac201974963d9c1b', '103.137.254.46', '2023-01-13 14:45:27', 'Mozilla/5.0 (X11; Linux x86_64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(117, 'be8d42fa88a14406ac201974963d9c1b', '103.137.254.46', '2023-01-13 14:59:08', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(118, 'be8d42fa88a14406ac201974963d9c1b', '103.137.254.46', '2023-01-13 14:59:45', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(119, 'be8d42fa88a14406ac201974963d9c1b', '182.2.40.94', '2023-01-13 15:43:05', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(120, 'be8d42fa88a14406ac201974963d9c1b', '103.137.254.46', '2023-01-13 15:48:43', 'Mozilla/5.0 (Linux; Android 5.1.1; SM-T285) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.74 Safari/537.36'),
(121, 'dc4547d5a820478188ae9c1a1ae876a6', '103.137.254.46', '2023-01-13 15:49:55', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(122, '417efb26a79e4c15966aea36536478ff', '103.137.254.46', '2023-01-13 15:53:49', 'Mozilla/5.0 (Linux; Android 5.1.1; SM-T285) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.74 Safari/537.36'),
(123, 'ceadd9fb648445eab1e350357e51d1ce', '103.137.254.46', '2023-01-13 15:54:21', 'Mozilla/5.0 (Linux; Android 5.1.1; SM-T285) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.74 Safari/537.36'),
(124, 'dc306f83529445139c16dd55b4f0e6f3', '103.137.254.46', '2023-01-13 15:54:33', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(125, 'dc306f83529445139c16dd55b4f0e6f3', '182.2.69.209', '2023-01-13 15:54:57', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(126, '7ff48adfec9b44b4bfce7173c012a8c7', '103.137.254.46', '2023-01-13 15:59:51', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(127, '214af5d98cc74484be4ef05aa56a9e45', '103.137.254.46', '2023-01-13 16:00:06', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(128, 'be8d42fa88a14406ac201974963d9c1b', '103.137.254.46', '2023-01-13 16:10:41', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(129, 'dc4547d5a820478188ae9c1a1ae876a6', '103.137.254.46', '2023-01-13 16:11:18', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(130, 'c94ad7236255430b82c0546dd82b917e', '103.137.254.46', '2023-01-13 16:11:37', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(131, 'be8d42fa88a14406ac201974963d9c1b', '103.137.254.46', '2023-01-13 16:13:14', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:107.0) Gecko/20100101 Firefox/107.0'),
(132, '9f72c952b9ea4474a912d619cba5d0a8', '103.137.254.46', '2023-01-13 16:13:58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(133, 'dc4547d5a820478188ae9c1a1ae876a6', '103.137.254.46', '2023-01-13 16:20:51', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(134, 'dc4547d5a820478188ae9c1a1ae876a6', '103.137.254.46', '2023-01-13 16:21:25', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(135, 'dc4547d5a820478188ae9c1a1ae876a6', '103.137.254.46', '2023-01-13 16:22:48', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(136, 'be8d42fa88a14406ac201974963d9c1b', '103.137.254.46', '2023-01-13 16:33:37', 'Mozilla/5.0 (Linux; Android 5.1.1; SM-T285) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.74 Safari/537.36'),
(137, 'ceadd9fb648445eab1e350357e51d1ce', '103.137.254.46', '2023-01-13 16:38:53', 'Mozilla/5.0 (Linux; Android 5.1.1; SM-T285) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.74 Safari/537.36'),
(138, 'be8d42fa88a14406ac201974963d9c1b', '139.0.38.243', '2023-01-14 02:01:26', 'Mozilla/5.0 (Linux; Android 11; vivo 1904) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Mobile Safari/537.36'),
(139, 'ceadd9fb648445eab1e350357e51d1ce', '139.0.38.243', '2023-01-14 12:28:20', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(140, 'dc306f83529445139c16dd55b4f0e6f3', '139.0.38.243', '2023-01-14 14:31:45', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(141, 'ceadd9fb648445eab1e350357e51d1ce', '139.0.38.243', '2023-01-14 14:33:37', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(142, 'be8d42fa88a14406ac201974963d9c1b', '139.0.38.243', '2023-01-14 14:38:20', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(143, 'be8d42fa88a14406ac201974963d9c1b', '139.0.38.243', '2023-01-14 14:39:00', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(144, 'dc306f83529445139c16dd55b4f0e6f3', '139.0.38.243', '2023-01-14 14:40:57', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(145, 'ceadd9fb648445eab1e350357e51d1ce', '139.0.38.243', '2023-01-14 14:41:29', 'Mozilla/5.0 (Linux; Android 11; vivo 1904) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Mobile Safari/537.36'),
(146, 'ceadd9fb648445eab1e350357e51d1ce', '103.137.254.46', '2023-01-16 09:23:48', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(147, 'dc4547d5a820478188ae9c1a1ae876a6', '103.137.254.46', '2023-01-16 09:33:15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(148, 'be8d42fa88a14406ac201974963d9c1b', '139.0.38.243', '2023-01-17 01:22:46', 'Mozilla/5.0 (Linux; Android 11; vivo 1904) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Mobile Safari/537.36'),
(149, 'be8d42fa88a14406ac201974963d9c1b', '103.137.254.46', '2023-01-17 10:38:34', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(150, 'dc4547d5a820478188ae9c1a1ae876a6', '103.137.254.46', '2023-01-17 14:13:13', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(151, '7ff48adfec9b44b4bfce7173c012a8c7', '103.137.254.46', '2023-01-17 14:13:57', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(152, '214af5d98cc74484be4ef05aa56a9e45', '103.137.254.46', '2023-01-17 14:16:38', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36 OPR/94.0.0.0'),
(153, '214af5d98cc74484be4ef05aa56a9e45', '103.137.254.46', '2023-01-17 14:18:16', 'Mozilla/5.0 (X11; Linux x86_64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(154, 'c94ad7236255430b82c0546dd82b917e', '103.137.254.46', '2023-01-17 14:29:03', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36 Edg/109.0.1518.55'),
(155, '9f72c952b9ea4474a912d619cba5d0a8', '103.137.254.46', '2023-01-17 14:31:00', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(156, 'be8d42fa88a14406ac201974963d9c1b', '103.137.254.46', '2023-01-17 14:44:03', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(157, 'ceadd9fb648445eab1e350357e51d1ce', '182.2.36.22', '2023-01-17 15:34:26', 'Mozilla/5.0 (Linux; Android 5.1.1; SM-T285) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.74 Safari/537.36'),
(158, '52706514ef294f11807d246bc701fb7a', '182.2.36.22', '2023-01-17 15:45:52', 'Mozilla/5.0 (Linux; Android 5.1.1; SM-T285) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.74 Safari/537.36'),
(159, 'be8d42fa88a14406ac201974963d9c1b', '182.2.36.22', '2023-01-17 15:46:39', 'Mozilla/5.0 (Linux; Android 5.1.1; SM-T285) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.74 Safari/537.36'),
(160, '52706514ef294f11807d246bc701fb7a', '182.2.36.22', '2023-01-17 15:49:19', 'Mozilla/5.0 (Linux; Android 5.1.1; SM-T285) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.74 Safari/537.36'),
(161, '52706514ef294f11807d246bc701fb7a', '182.2.36.22', '2023-01-17 15:50:29', 'Mozilla/5.0 (Linux; Android 5.1.1; SM-T285) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.74 Safari/537.36'),
(162, '52706514ef294f11807d246bc701fb7a', '182.2.36.22', '2023-01-17 15:52:00', 'Mozilla/5.0 (Linux; Android 5.1.1; SM-T285) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.74 Safari/537.36'),
(163, '303f2de11d4f472d869757d7ea5bb83f', '103.137.254.46', '2023-01-17 15:59:52', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(164, 'be8d42fa88a14406ac201974963d9c1b', '182.2.36.22', '2023-01-17 16:00:19', 'Mozilla/5.0 (Linux; Android 5.1.1; SM-T285) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.74 Safari/537.36'),
(165, '303f2de11d4f472d869757d7ea5bb83f', '103.137.254.46', '2023-01-17 16:01:47', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(166, '52706514ef294f11807d246bc701fb7a', '182.2.36.22', '2023-01-17 16:02:53', 'Mozilla/5.0 (Linux; Android 5.1.1; SM-T285) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.74 Safari/537.36'),
(167, 'be8d42fa88a14406ac201974963d9c1b', '103.137.254.46', '2023-01-17 16:06:04', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36 Edg/109.0.1518.55'),
(168, '86ce9a31dae44411a568cc4b97dad7fd', '182.2.36.22', '2023-01-17 16:06:45', 'Mozilla/5.0 (Linux; Android 5.1.1; SM-T285) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.74 Safari/537.36'),
(169, 'be8d42fa88a14406ac201974963d9c1b', '182.2.36.22', '2023-01-17 16:07:12', 'Mozilla/5.0 (Linux; Android 5.1.1; SM-T285) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.74 Safari/537.36'),
(170, '86ce9a31dae44411a568cc4b97dad7fd', '103.137.254.46', '2023-01-17 16:09:54', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36 Edg/109.0.1518.55'),
(171, '86ce9a31dae44411a568cc4b97dad7fd', '182.2.36.22', '2023-01-17 16:10:47', 'Mozilla/5.0 (Linux; Android 5.1.1; SM-T285) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.74 Safari/537.36'),
(172, '52706514ef294f11807d246bc701fb7a', '182.2.36.22', '2023-01-17 16:11:22', 'Mozilla/5.0 (Linux; Android 5.1.1; SM-T285) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.74 Safari/537.36'),
(173, 'be8d42fa88a14406ac201974963d9c1b', '182.2.36.22', '2023-01-17 16:13:42', 'Mozilla/5.0 (Linux; Android 5.1.1; SM-T285) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.74 Safari/537.36'),
(174, 'a1b814111063435eb7a862c18d1de4cf', '182.2.36.22', '2023-01-17 16:17:57', 'Mozilla/5.0 (Linux; Android 5.1.1; SM-T285) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.74 Safari/537.36'),
(175, 'be8d42fa88a14406ac201974963d9c1b', '182.2.36.22', '2023-01-17 16:39:47', 'Mozilla/5.0 (Linux; Android 5.1.1; SM-T285) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.74 Safari/537.36'),
(176, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '103.137.254.46', '2023-01-18 10:07:38', 'Mozilla/5.0 (Linux; Android 8.1.0; 8001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36'),
(177, 'a1b814111063435eb7a862c18d1de4cf', '103.137.254.46', '2023-01-18 10:08:34', 'Mozilla/5.0 (X11; Linux x86_64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(178, 'be8d42fa88a14406ac201974963d9c1b', '103.137.254.46', '2023-01-18 10:11:32', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(179, 'ceadd9fb648445eab1e350357e51d1ce', '103.137.254.46', '2023-01-18 10:12:02', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(180, 'be8d42fa88a14406ac201974963d9c1b', '103.137.254.46', '2023-01-18 10:18:11', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36 Edg/109.0.1518.55'),
(181, 'be8d42fa88a14406ac201974963d9c1b', '103.137.254.46', '2023-01-18 10:18:59', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36 Edg/109.0.1518.55'),
(182, '0007828bc2a5496bbdd8fbaefe2e1565', '182.2.43.14', '2023-01-18 10:20:59', 'Mozilla/5.0 (Linux; Android 8.1.0; 8001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(183, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '103.137.254.46', '2023-01-18 10:26:44', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(184, 'be8d42fa88a14406ac201974963d9c1b', '182.2.43.14', '2023-01-18 10:39:45', 'Mozilla/5.0 (Linux; Android 11; Infinix X689) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Mobile Safari/537.36'),
(185, '0007828bc2a5496bbdd8fbaefe2e1565', '182.2.43.14', '2023-01-18 10:52:35', 'Mozilla/5.0 (Linux; Android 8.1.0; 8001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(186, 'ceadd9fb648445eab1e350357e51d1ce', '103.137.254.46', '2023-01-18 11:11:29', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(187, '3d43ab399ec24c30b39c9b052686416d', '182.2.43.14', '2023-01-18 11:21:14', 'Mozilla/5.0 (Linux; Android 8.1.0; 8001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(188, '3d43ab399ec24c30b39c9b052686416d', '182.2.43.14', '2023-01-18 11:22:47', 'Mozilla/5.0 (Linux; Android 8.1.0; 8001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(189, '0007828bc2a5496bbdd8fbaefe2e1565', '182.2.43.14', '2023-01-18 11:31:14', 'Mozilla/5.0 (Linux; Android 8.1.0; 8001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(190, 'be8d42fa88a14406ac201974963d9c1b', '182.2.43.14', '2023-01-18 11:33:11', 'Mozilla/5.0 (Linux; Android 8.1.0; 8001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(191, 'c94ad7236255430b82c0546dd82b917e', '182.2.43.14', '2023-01-18 11:34:40', 'Mozilla/5.0 (Linux; Android 8.1.0; 8001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36'),
(192, 'c94ad7236255430b82c0546dd82b917e', '182.2.43.14', '2023-01-18 11:37:30', 'Mozilla/5.0 (Linux; Android 8.1.0; 8001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36'),
(193, 'c94ad7236255430b82c0546dd82b917e', '182.2.43.14', '2023-01-18 11:39:01', 'Mozilla/5.0 (Linux; Android 8.1.0; 8001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36'),
(194, 'be8d42fa88a14406ac201974963d9c1b', '103.137.254.46', '2023-01-18 11:45:33', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36 OPR/94.0.0.0'),
(195, 'dc4547d5a820478188ae9c1a1ae876a6', '182.2.43.14', '2023-01-18 11:46:30', 'Mozilla/5.0 (Linux; Android 8.1.0; 8001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(196, 'dc4547d5a820478188ae9c1a1ae876a6', '182.2.43.14', '2023-01-18 11:48:58', 'Mozilla/5.0 (Linux; Android 8.1.0; 8001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(197, 'dc306f83529445139c16dd55b4f0e6f3', '103.137.254.46', '2023-01-18 12:55:49', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(198, 'c94ad7236255430b82c0546dd82b917e', '103.137.254.46', '2023-01-18 12:57:11', 'Mozilla/5.0 (Linux; Android 8.1.0; 8001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36'),
(199, 'ab76995143c94f07b6cadc832f252752', '103.137.254.46', '2023-01-18 12:57:49', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(200, '9f72c952b9ea4474a912d619cba5d0a8', '103.137.254.46', '2023-01-18 12:59:26', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(201, 'dc306f83529445139c16dd55b4f0e6f3', '103.137.254.46', '2023-01-18 13:08:43', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(202, 'c94ad7236255430b82c0546dd82b917e', '103.137.254.46', '2023-01-18 13:10:11', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(203, 'ab76995143c94f07b6cadc832f252752', '103.137.254.46', '2023-01-18 13:11:06', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(204, 'ab76995143c94f07b6cadc832f252752', '103.137.254.46', '2023-01-18 13:12:51', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(205, '9f72c952b9ea4474a912d619cba5d0a8', '103.137.254.46', '2023-01-18 13:14:07', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(206, 'be8d42fa88a14406ac201974963d9c1b', '103.137.254.46', '2023-01-18 13:18:11', 'Mozilla/5.0 (X11; Linux x86_64; rv:108.0) Gecko/20100101 Firefox/108.0'),
(207, '9f72c952b9ea4474a912d619cba5d0a8', '103.137.254.46', '2023-01-18 13:19:34', 'Mozilla/5.0 (Linux; Android 8.1.0; 8001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36'),
(208, 'be8d42fa88a14406ac201974963d9c1b', '103.137.254.46', '2023-01-18 13:51:42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36'),
(209, 'ceadd9fb648445eab1e350357e51d1ce', '182.2.43.2', '2023-01-18 14:06:12', 'Mozilla/5.0 (Linux; Android 5.1.1; SM-T285) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.74 Safari/537.36'),
(210, '214af5d98cc74484be4ef05aa56a9e45', '103.137.254.46', '2023-01-18 14:06:32', 'Mozilla/5.0 (Linux; Android 8.1.0; 8001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36'),
(211, '7ff48adfec9b44b4bfce7173c012a8c7', '103.137.254.46', '2023-01-18 14:06:41', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(212, 'dc306f83529445139c16dd55b4f0e6f3', '103.137.254.46', '2023-01-18 14:10:09', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(213, '9f72c952b9ea4474a912d619cba5d0a8', '103.137.254.46', '2023-01-18 14:11:57', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(214, 'c94ad7236255430b82c0546dd82b917e', '182.2.43.2', '2023-01-18 14:12:18', 'Mozilla/5.0 (Linux; Android 8.1.0; 8001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(215, 'ceadd9fb648445eab1e350357e51d1ce', '182.2.43.2', '2023-01-18 14:14:50', 'Mozilla/5.0 (Linux; Android 5.1.1; SM-T285) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.74 Safari/537.36'),
(216, 'dc4547d5a820478188ae9c1a1ae876a6', '103.137.254.46', '2023-01-18 14:15:30', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(217, 'ceadd9fb648445eab1e350357e51d1ce', '103.137.254.46', '2023-01-18 14:24:26', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(218, 'dc306f83529445139c16dd55b4f0e6f3', '103.137.254.46', '2023-01-18 14:25:28', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(219, 'dc306f83529445139c16dd55b4f0e6f3', '103.137.254.46', '2023-01-18 14:26:21', 'Mozilla/5.0 (Linux; Android 8.1.0; 8001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36'),
(220, '52706514ef294f11807d246bc701fb7a', '103.137.254.46', '2023-01-18 15:17:17', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(221, '52706514ef294f11807d246bc701fb7a', '182.2.43.2', '2023-01-18 15:24:02', 'Mozilla/5.0 (Linux; Android 5.1.1; SM-T285) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.74 Safari/537.36'),
(222, '303f2de11d4f472d869757d7ea5bb83f', '182.2.43.2', '2023-01-18 15:25:18', 'Mozilla/5.0 (Linux; Android 8.1.0; 8001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(223, '86ce9a31dae44411a568cc4b97dad7fd', '103.137.254.46', '2023-01-18 15:29:41', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(224, 'a1b814111063435eb7a862c18d1de4cf', '103.137.254.46', '2023-01-18 15:32:48', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(225, 'a4f8d1d67d2e4b9aa2a8e8680a953194', '103.137.254.46', '2023-01-18 15:32:51', 'Mozilla/5.0 (Linux; Android 8.1.0; 8001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36'),
(226, 'c94ad7236255430b82c0546dd82b917e', '182.2.43.2', '2023-01-18 15:54:40', 'Mozilla/5.0 (Linux; Android 8.1.0; 8001) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'),
(227, 'be8d42fa88a14406ac201974963d9c1b', '114.79.51.189', '2023-01-19 21:18:59', 'Mozilla/5.0 (Linux; Android 11; vivo 1904) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Mobile Safari/537.36'),
(228, 'be8d42fa88a14406ac201974963d9c1b', '103.137.254.46', '2023-01-20 10:08:51', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36');

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
('57a9534bd79d4382bb0f43c89910702c', '6903e82e7e94478f87df3cf80de6b587'),
('57a9534bd79d4382bb0f43c89910702c', '1b89744217b04f79a8c1d7a967a46912'),
('57a9534bd79d4382bb0f43c89910702c', '1c1940da68fa4f8ba2325e83c303c47c'),
('57a9534bd79d4382bb0f43c89910702c', '38645f82ae7c468abad1ab191e7a8ad9'),
('57a9534bd79d4382bb0f43c89910702c', '4bb845580b464d7db3d7c3b3e4fd213b'),
('205cbcb8d74646f7bfafd9b4972f04ac', '1098a56970114e18898367d334658b47'),
('205cbcb8d74646f7bfafd9b4972f04ac', '12b852d92d284ab5a654c26e8856fffd'),
('205cbcb8d74646f7bfafd9b4972f04ac', '33c3711d787d416082c0519356547b0c'),
('205cbcb8d74646f7bfafd9b4972f04ac', '358a13267bcb4608a14c851c3010f79b'),
('205cbcb8d74646f7bfafd9b4972f04ac', '5d793b19c75046b9a4d75d067e8e33b2'),
('205cbcb8d74646f7bfafd9b4972f04ac', '6903e82e7e94478f87df3cf80de6b587'),
('205cbcb8d74646f7bfafd9b4972f04ac', '8791f143a90e42e2a4d1d0d6b1254bad'),
('205cbcb8d74646f7bfafd9b4972f04ac', 'a213b689b8274f4dbe19b3fb24d66840'),
('205cbcb8d74646f7bfafd9b4972f04ac', 'ebca07da8aad42c4aee304e3a6b81001'),
('205cbcb8d74646f7bfafd9b4972f04ac', '8d9b1da4234f46eb858e1ea490da6348'),
('205cbcb8d74646f7bfafd9b4972f04ac', 'a6034d814d7e4671b4cc8a98433f8fb2'),
('205cbcb8d74646f7bfafd9b4972f04ac', '1b89744217b04f79a8c1d7a967a46912'),
('205cbcb8d74646f7bfafd9b4972f04ac', '1f4e5b3752b8475cb5261940ef62532d'),
('205cbcb8d74646f7bfafd9b4972f04ac', '89bc4b0ef1dd4306a3217cbf24551071'),
('205cbcb8d74646f7bfafd9b4972f04ac', 'cc93223a47764195ac15aacf266673d9'),
('205cbcb8d74646f7bfafd9b4972f04ac', '1c1940da68fa4f8ba2325e83c303c47c'),
('205cbcb8d74646f7bfafd9b4972f04ac', '38645f82ae7c468abad1ab191e7a8ad9'),
('205cbcb8d74646f7bfafd9b4972f04ac', '4bb845580b464d7db3d7c3b3e4fd213b'),
('735d609249d649f3b4e4296d69ab0fd6', '1098a56970114e18898367d334658b47'),
('735d609249d649f3b4e4296d69ab0fd6', '6903e82e7e94478f87df3cf80de6b587'),
('735d609249d649f3b4e4296d69ab0fd6', '8d9b1da4234f46eb858e1ea490da6348'),
('735d609249d649f3b4e4296d69ab0fd6', 'a6034d814d7e4671b4cc8a98433f8fb2'),
('735d609249d649f3b4e4296d69ab0fd6', '1b89744217b04f79a8c1d7a967a46912'),
('735d609249d649f3b4e4296d69ab0fd6', '1f4e5b3752b8475cb5261940ef62532d'),
('735d609249d649f3b4e4296d69ab0fd6', '89bc4b0ef1dd4306a3217cbf24551071'),
('735d609249d649f3b4e4296d69ab0fd6', 'cc93223a47764195ac15aacf266673d9'),
('735d609249d649f3b4e4296d69ab0fd6', '1c1940da68fa4f8ba2325e83c303c47c'),
('735d609249d649f3b4e4296d69ab0fd6', '38645f82ae7c468abad1ab191e7a8ad9'),
('735d609249d649f3b4e4296d69ab0fd6', '4bb845580b464d7db3d7c3b3e4fd213b'),
('b7b8f96b9f534c3b96e1122dfe18317e', '1098a56970114e18898367d334658b47'),
('b7b8f96b9f534c3b96e1122dfe18317e', '12b852d92d284ab5a654c26e8856fffd'),
('b7b8f96b9f534c3b96e1122dfe18317e', '33c3711d787d416082c0519356547b0c'),
('b7b8f96b9f534c3b96e1122dfe18317e', '358a13267bcb4608a14c851c3010f79b'),
('b7b8f96b9f534c3b96e1122dfe18317e', '5d793b19c75046b9a4d75d067e8e33b2'),
('b7b8f96b9f534c3b96e1122dfe18317e', '6903e82e7e94478f87df3cf80de6b587'),
('b7b8f96b9f534c3b96e1122dfe18317e', '8791f143a90e42e2a4d1d0d6b1254bad'),
('b7b8f96b9f534c3b96e1122dfe18317e', 'a213b689b8274f4dbe19b3fb24d66840'),
('b7b8f96b9f534c3b96e1122dfe18317e', 'ebca07da8aad42c4aee304e3a6b81001'),
('b7b8f96b9f534c3b96e1122dfe18317e', '8d9b1da4234f46eb858e1ea490da6348'),
('b7b8f96b9f534c3b96e1122dfe18317e', 'a6034d814d7e4671b4cc8a98433f8fb2'),
('b7b8f96b9f534c3b96e1122dfe18317e', '1b89744217b04f79a8c1d7a967a46912'),
('b7b8f96b9f534c3b96e1122dfe18317e', '1f4e5b3752b8475cb5261940ef62532d'),
('b7b8f96b9f534c3b96e1122dfe18317e', '89bc4b0ef1dd4306a3217cbf24551071'),
('b7b8f96b9f534c3b96e1122dfe18317e', 'cc93223a47764195ac15aacf266673d9'),
('b7b8f96b9f534c3b96e1122dfe18317e', '1c1940da68fa4f8ba2325e83c303c47c'),
('b7b8f96b9f534c3b96e1122dfe18317e', '38645f82ae7c468abad1ab191e7a8ad9'),
('b7b8f96b9f534c3b96e1122dfe18317e', '4bb845580b464d7db3d7c3b3e4fd213b'),
('417efb26a79e4c15966aea36536478ff', '6903e82e7e94478f87df3cf80de6b587'),
('417efb26a79e4c15966aea36536478ff', 'ebca07da8aad42c4aee304e3a6b81001'),
('417efb26a79e4c15966aea36536478ff', '1b89744217b04f79a8c1d7a967a46912'),
('214af5d98cc74484be4ef05aa56a9e45', '6903e82e7e94478f87df3cf80de6b587'),
('214af5d98cc74484be4ef05aa56a9e45', 'a213b689b8274f4dbe19b3fb24d66840'),
('214af5d98cc74484be4ef05aa56a9e45', '1b89744217b04f79a8c1d7a967a46912'),
('5f64a6100114456a8905b8dfc1dc1460', '12b852d92d284ab5a654c26e8856fffd'),
('5f64a6100114456a8905b8dfc1dc1460', '6903e82e7e94478f87df3cf80de6b587'),
('5f64a6100114456a8905b8dfc1dc1460', '1b89744217b04f79a8c1d7a967a46912'),
('25fbb5a3233e4cf6a889b412691149d1', '5d793b19c75046b9a4d75d067e8e33b2'),
('25fbb5a3233e4cf6a889b412691149d1', '6903e82e7e94478f87df3cf80de6b587'),
('355c4e0e850c43f382cf1052f7053f40', '33c3711d787d416082c0519356547b0c'),
('355c4e0e850c43f382cf1052f7053f40', '6903e82e7e94478f87df3cf80de6b587'),
('fbba0a06756b43c69a01c961616ff978', '1098a56970114e18898367d334658b47'),
('fbba0a06756b43c69a01c961616ff978', '12b852d92d284ab5a654c26e8856fffd'),
('fbba0a06756b43c69a01c961616ff978', '33c3711d787d416082c0519356547b0c'),
('fbba0a06756b43c69a01c961616ff978', '358a13267bcb4608a14c851c3010f79b'),
('fbba0a06756b43c69a01c961616ff978', '5d793b19c75046b9a4d75d067e8e33b2'),
('fbba0a06756b43c69a01c961616ff978', '6903e82e7e94478f87df3cf80de6b587'),
('fbba0a06756b43c69a01c961616ff978', '8791f143a90e42e2a4d1d0d6b1254bad'),
('fbba0a06756b43c69a01c961616ff978', 'a213b689b8274f4dbe19b3fb24d66840'),
('fbba0a06756b43c69a01c961616ff978', 'ebca07da8aad42c4aee304e3a6b81001'),
('fbba0a06756b43c69a01c961616ff978', '8d9b1da4234f46eb858e1ea490da6348'),
('fbba0a06756b43c69a01c961616ff978', 'a6034d814d7e4671b4cc8a98433f8fb2'),
('fbba0a06756b43c69a01c961616ff978', '1b89744217b04f79a8c1d7a967a46912'),
('fbba0a06756b43c69a01c961616ff978', '1f4e5b3752b8475cb5261940ef62532d'),
('fbba0a06756b43c69a01c961616ff978', '89bc4b0ef1dd4306a3217cbf24551071'),
('fbba0a06756b43c69a01c961616ff978', 'cc93223a47764195ac15aacf266673d9'),
('fbba0a06756b43c69a01c961616ff978', '1c1940da68fa4f8ba2325e83c303c47c'),
('fbba0a06756b43c69a01c961616ff978', '38645f82ae7c468abad1ab191e7a8ad9'),
('fbba0a06756b43c69a01c961616ff978', '4bb845580b464d7db3d7c3b3e4fd213b'),
('f38be109ef1a42eb9a9b9d4de30cd6a0', '1098a56970114e18898367d334658b47'),
('f38be109ef1a42eb9a9b9d4de30cd6a0', '12b852d92d284ab5a654c26e8856fffd'),
('f38be109ef1a42eb9a9b9d4de30cd6a0', '33c3711d787d416082c0519356547b0c'),
('f38be109ef1a42eb9a9b9d4de30cd6a0', '358a13267bcb4608a14c851c3010f79b'),
('f38be109ef1a42eb9a9b9d4de30cd6a0', '5d793b19c75046b9a4d75d067e8e33b2'),
('f38be109ef1a42eb9a9b9d4de30cd6a0', '6903e82e7e94478f87df3cf80de6b587'),
('f38be109ef1a42eb9a9b9d4de30cd6a0', '8791f143a90e42e2a4d1d0d6b1254bad'),
('f38be109ef1a42eb9a9b9d4de30cd6a0', 'a213b689b8274f4dbe19b3fb24d66840'),
('f38be109ef1a42eb9a9b9d4de30cd6a0', 'ebca07da8aad42c4aee304e3a6b81001'),
('f38be109ef1a42eb9a9b9d4de30cd6a0', '8d9b1da4234f46eb858e1ea490da6348'),
('f38be109ef1a42eb9a9b9d4de30cd6a0', 'a6034d814d7e4671b4cc8a98433f8fb2'),
('f38be109ef1a42eb9a9b9d4de30cd6a0', '1b89744217b04f79a8c1d7a967a46912'),
('f38be109ef1a42eb9a9b9d4de30cd6a0', '1f4e5b3752b8475cb5261940ef62532d'),
('f38be109ef1a42eb9a9b9d4de30cd6a0', '89bc4b0ef1dd4306a3217cbf24551071'),
('f38be109ef1a42eb9a9b9d4de30cd6a0', 'cc93223a47764195ac15aacf266673d9'),
('f38be109ef1a42eb9a9b9d4de30cd6a0', '1c1940da68fa4f8ba2325e83c303c47c'),
('f38be109ef1a42eb9a9b9d4de30cd6a0', '38645f82ae7c468abad1ab191e7a8ad9'),
('f38be109ef1a42eb9a9b9d4de30cd6a0', '4bb845580b464d7db3d7c3b3e4fd213b'),
('59ed337897f347659c6c78270df47a80', '12b852d92d284ab5a654c26e8856fffd'),
('59ed337897f347659c6c78270df47a80', '6903e82e7e94478f87df3cf80de6b587'),
('59ed337897f347659c6c78270df47a80', '8791f143a90e42e2a4d1d0d6b1254bad'),
('59ed337897f347659c6c78270df47a80', 'a213b689b8274f4dbe19b3fb24d66840'),
('59ed337897f347659c6c78270df47a80', 'ebca07da8aad42c4aee304e3a6b81001'),
('59ed337897f347659c6c78270df47a80', 'a6034d814d7e4671b4cc8a98433f8fb2'),
('59ed337897f347659c6c78270df47a80', '1b89744217b04f79a8c1d7a967a46912'),
('59ed337897f347659c6c78270df47a80', '38645f82ae7c468abad1ab191e7a8ad9'),
('59ed337897f347659c6c78270df47a80', '4bb845580b464d7db3d7c3b3e4fd213b'),
('5537f8f560a549e88d7a443b801bb1af', '1098a56970114e18898367d334658b47'),
('5537f8f560a549e88d7a443b801bb1af', '358a13267bcb4608a14c851c3010f79b'),
('5537f8f560a549e88d7a443b801bb1af', '6903e82e7e94478f87df3cf80de6b587'),
('5537f8f560a549e88d7a443b801bb1af', '1b89744217b04f79a8c1d7a967a46912'),
('5537f8f560a549e88d7a443b801bb1af', '1f4e5b3752b8475cb5261940ef62532d'),
('5537f8f560a549e88d7a443b801bb1af', '89bc4b0ef1dd4306a3217cbf24551071'),
('5537f8f560a549e88d7a443b801bb1af', 'cc93223a47764195ac15aacf266673d9'),
('5e26c2b906a543989ed3a9abba178fe1', '1098a56970114e18898367d334658b47'),
('5e26c2b906a543989ed3a9abba178fe1', '358a13267bcb4608a14c851c3010f79b'),
('5e26c2b906a543989ed3a9abba178fe1', '6903e82e7e94478f87df3cf80de6b587'),
('5e26c2b906a543989ed3a9abba178fe1', '1b89744217b04f79a8c1d7a967a46912'),
('5e26c2b906a543989ed3a9abba178fe1', '1f4e5b3752b8475cb5261940ef62532d'),
('5e26c2b906a543989ed3a9abba178fe1', '89bc4b0ef1dd4306a3217cbf24551071'),
('5e26c2b906a543989ed3a9abba178fe1', 'cc93223a47764195ac15aacf266673d9'),
('77e9d0dacb154a37b5730efb8d36ad29', '1098a56970114e18898367d334658b47'),
('77e9d0dacb154a37b5730efb8d36ad29', '6903e82e7e94478f87df3cf80de6b587'),
('77e9d0dacb154a37b5730efb8d36ad29', 'ebca07da8aad42c4aee304e3a6b81001'),
('77e9d0dacb154a37b5730efb8d36ad29', '1b89744217b04f79a8c1d7a967a46912'),
('77e9d0dacb154a37b5730efb8d36ad29', 'cc93223a47764195ac15aacf266673d9'),
('a400e4bcf70d40d78224043cc95e6241', '1098a56970114e18898367d334658b47'),
('a400e4bcf70d40d78224043cc95e6241', '6903e82e7e94478f87df3cf80de6b587'),
('a400e4bcf70d40d78224043cc95e6241', 'ebca07da8aad42c4aee304e3a6b81001'),
('a400e4bcf70d40d78224043cc95e6241', '1b89744217b04f79a8c1d7a967a46912'),
('a400e4bcf70d40d78224043cc95e6241', 'cc93223a47764195ac15aacf266673d9'),
('b18e4ff295b24fd2bab92717b85504b6', '1098a56970114e18898367d334658b47'),
('b18e4ff295b24fd2bab92717b85504b6', '6903e82e7e94478f87df3cf80de6b587'),
('b18e4ff295b24fd2bab92717b85504b6', 'ebca07da8aad42c4aee304e3a6b81001'),
('b18e4ff295b24fd2bab92717b85504b6', '1b89744217b04f79a8c1d7a967a46912'),
('b18e4ff295b24fd2bab92717b85504b6', 'cc93223a47764195ac15aacf266673d9'),
('1beceaecfa3f45c8be6d2f761aea389f', '1098a56970114e18898367d334658b47'),
('1beceaecfa3f45c8be6d2f761aea389f', '358a13267bcb4608a14c851c3010f79b'),
('1beceaecfa3f45c8be6d2f761aea389f', '6903e82e7e94478f87df3cf80de6b587'),
('1beceaecfa3f45c8be6d2f761aea389f', '1b89744217b04f79a8c1d7a967a46912'),
('1beceaecfa3f45c8be6d2f761aea389f', 'cc93223a47764195ac15aacf266673d9'),
('2d4e15b2176e49a1bb7d54d06c8f935f', '1098a56970114e18898367d334658b47'),
('2d4e15b2176e49a1bb7d54d06c8f935f', '358a13267bcb4608a14c851c3010f79b'),
('2d4e15b2176e49a1bb7d54d06c8f935f', '6903e82e7e94478f87df3cf80de6b587'),
('2d4e15b2176e49a1bb7d54d06c8f935f', '1b89744217b04f79a8c1d7a967a46912'),
('2d4e15b2176e49a1bb7d54d06c8f935f', 'cc93223a47764195ac15aacf266673d9'),
('7e7bb093dc0e4fb98f3d8a37b92d4457', '12b852d92d284ab5a654c26e8856fffd'),
('7e7bb093dc0e4fb98f3d8a37b92d4457', '358a13267bcb4608a14c851c3010f79b'),
('7e7bb093dc0e4fb98f3d8a37b92d4457', '6903e82e7e94478f87df3cf80de6b587'),
('7e7bb093dc0e4fb98f3d8a37b92d4457', '1b89744217b04f79a8c1d7a967a46912'),
('03e6d8cb79ab4b1cb8f907c39ea4b11b', '6903e82e7e94478f87df3cf80de6b587'),
('03e6d8cb79ab4b1cb8f907c39ea4b11b', 'a213b689b8274f4dbe19b3fb24d66840'),
('03e6d8cb79ab4b1cb8f907c39ea4b11b', '1b89744217b04f79a8c1d7a967a46912'),
('bc95dda2d2a741728a97de102d1c2155', '6903e82e7e94478f87df3cf80de6b587'),
('bc95dda2d2a741728a97de102d1c2155', 'a213b689b8274f4dbe19b3fb24d66840'),
('bc95dda2d2a741728a97de102d1c2155', '1b89744217b04f79a8c1d7a967a46912'),
('a5496cc344a24908835563d47e108a52', '6903e82e7e94478f87df3cf80de6b587'),
('a5496cc344a24908835563d47e108a52', 'a213b689b8274f4dbe19b3fb24d66840'),
('a5496cc344a24908835563d47e108a52', '1b89744217b04f79a8c1d7a967a46912'),
('54281e5fceba4bf092c79ce994537ed3', '6903e82e7e94478f87df3cf80de6b587'),
('54281e5fceba4bf092c79ce994537ed3', 'a213b689b8274f4dbe19b3fb24d66840'),
('54281e5fceba4bf092c79ce994537ed3', '1b89744217b04f79a8c1d7a967a46912'),
('46cc8c6792634b3eb0c0da7c38763d01', '6903e82e7e94478f87df3cf80de6b587'),
('46cc8c6792634b3eb0c0da7c38763d01', '1b89744217b04f79a8c1d7a967a46912'),
('f88116e1873c4403a7fb76a08f5266cb', '6903e82e7e94478f87df3cf80de6b587'),
('f88116e1873c4403a7fb76a08f5266cb', 'ebca07da8aad42c4aee304e3a6b81001'),
('f88116e1873c4403a7fb76a08f5266cb', '1b89744217b04f79a8c1d7a967a46912'),
('f88116e1873c4403a7fb76a08f5266cb', 'cc93223a47764195ac15aacf266673d9'),
('072df3b932394d6caacb5c9c0960d42b', '6903e82e7e94478f87df3cf80de6b587'),
('072df3b932394d6caacb5c9c0960d42b', 'ebca07da8aad42c4aee304e3a6b81001'),
('072df3b932394d6caacb5c9c0960d42b', '1b89744217b04f79a8c1d7a967a46912'),
('072df3b932394d6caacb5c9c0960d42b', 'cc93223a47764195ac15aacf266673d9'),
('0ecea60f2691405585fa1aa535368bee', '6903e82e7e94478f87df3cf80de6b587'),
('0ecea60f2691405585fa1aa535368bee', 'ebca07da8aad42c4aee304e3a6b81001'),
('0ecea60f2691405585fa1aa535368bee', '1b89744217b04f79a8c1d7a967a46912'),
('0ecea60f2691405585fa1aa535368bee', 'cc93223a47764195ac15aacf266673d9'),
('7c4f86aba34f430884fb87fee84bc870', '3a70433b-16f5-11ed-ae5c-1078d2a38ee5'),
('7c4f86aba34f430884fb87fee84bc870', '60b2f2ca-16f5-11ed-ae5c-1078d2a38ee5'),
('7c4f86aba34f430884fb87fee84bc870', '8baa9163-16f5-11ed-ae5c-1078d2a38ee5'),
('7c4f86aba34f430884fb87fee84bc870', 'f76c69fb-16f4-11ed-ae5c-1078d2a38ee5'),
('7c4f86aba34f430884fb87fee84bc870', '068adb0171304c628b267874004d7e8c'),
('7c4f86aba34f430884fb87fee84bc870', '2b6032ef8a73463ba2c761c86be5ed5d'),
('7c4f86aba34f430884fb87fee84bc870', 'bc6b9c821e3f42ccb57532930c8d92be'),
('7c4f86aba34f430884fb87fee84bc870', 'e9f5bad7fdd94494a125e451de456a92'),
('7c4f86aba34f430884fb87fee84bc870', '0d9c8667ccb34e9da275e7dce09d9cd9'),
('7c4f86aba34f430884fb87fee84bc870', 'dc43f263313f4788bccbcc9adf642a1f'),
('7c4f86aba34f430884fb87fee84bc870', 'faa7c4808c714ca49762f6aaade7da3b'),
('7c4f86aba34f430884fb87fee84bc870', 'fd061c3363db4b298eea0bb0b4cbcbf0'),
('7c4f86aba34f430884fb87fee84bc870', '28c3460bb5cf4c618ba8ec6f3c12ddbd'),
('7c4f86aba34f430884fb87fee84bc870', '8a6141a082554335a2137c90f9fa0a5e'),
('7c4f86aba34f430884fb87fee84bc870', 'bc5a7cb945e14432bfdf312e2059e868'),
('7c4f86aba34f430884fb87fee84bc870', 'be40fd210eb44ee68475bbe80eb8b1ea'),
('7c4f86aba34f430884fb87fee84bc870', '09179170e6e643eca66b282e2ffae1f8'),
('7c4f86aba34f430884fb87fee84bc870', '4d64a842e08344b9aeec88ed9eb2eb72'),
('7c4f86aba34f430884fb87fee84bc870', '9b4e52c30f974844ac7a050000a0ee6a'),
('7c4f86aba34f430884fb87fee84bc870', 'c64802952e504f4ab25a6b1241232f85'),
('7c4f86aba34f430884fb87fee84bc870', 'e0860766d564483e870b5974a601649c'),
('7c4f86aba34f430884fb87fee84bc870', '1b89744217b04f79a8c1d7a967a46912'),
('7c4f86aba34f430884fb87fee84bc870', '1f4e5b3752b8475cb5261940ef62532d'),
('7c4f86aba34f430884fb87fee84bc870', '89bc4b0ef1dd4306a3217cbf24551071'),
('7c4f86aba34f430884fb87fee84bc870', 'cc93223a47764195ac15aacf266673d9'),
('7c4f86aba34f430884fb87fee84bc870', '171e6210418440a8bf4d689841d0f32c'),
('7c4f86aba34f430884fb87fee84bc870', '4cea10b3a4434bc3b342407a78a9ab2a'),
('7c4f86aba34f430884fb87fee84bc870', '78712deb909d4d88af7f098c0fcf6857'),
('7c4f86aba34f430884fb87fee84bc870', '8f53727c763849aab80c1513505decf8'),
('7c4f86aba34f430884fb87fee84bc870', '9d69d18ff5184804990bc21cb1005ab7'),
('7c4f86aba34f430884fb87fee84bc870', 'c21495eca0d44776aeacf431dc9fb0e1'),
('7c4f86aba34f430884fb87fee84bc870', '808ab7987c9b4f0ab025b1b9e3ed1d43'),
('7c4f86aba34f430884fb87fee84bc870', '38f34660ef404dc9b7a0ee0f697ae781'),
('7c4f86aba34f430884fb87fee84bc870', 'db87d2605a68440fbf8e148744e243e8'),
('7c4f86aba34f430884fb87fee84bc870', '569c1d340cea4b21a54910177eeaf51f'),
('7c4f86aba34f430884fb87fee84bc870', '6b4e3b36783d4a488101da7639c40de0'),
('7c4f86aba34f430884fb87fee84bc870', '8d9b1da4234f46eb858e1ea490da6348'),
('7c4f86aba34f430884fb87fee84bc870', 'a6034d814d7e4671b4cc8a98433f8fb2'),
('7c4f86aba34f430884fb87fee84bc870', '1098a56970114e18898367d334658b47'),
('7c4f86aba34f430884fb87fee84bc870', '12b852d92d284ab5a654c26e8856fffd'),
('7c4f86aba34f430884fb87fee84bc870', '33c3711d787d416082c0519356547b0c'),
('7c4f86aba34f430884fb87fee84bc870', '358a13267bcb4608a14c851c3010f79b'),
('7c4f86aba34f430884fb87fee84bc870', '5d793b19c75046b9a4d75d067e8e33b2'),
('7c4f86aba34f430884fb87fee84bc870', '6903e82e7e94478f87df3cf80de6b587'),
('7c4f86aba34f430884fb87fee84bc870', '8791f143a90e42e2a4d1d0d6b1254bad'),
('7c4f86aba34f430884fb87fee84bc870', '8de7d59a74f345a5bcab20ec43376299'),
('7c4f86aba34f430884fb87fee84bc870', '9beba245308543ce821efe8a3ba965e3'),
('7c4f86aba34f430884fb87fee84bc870', 'a213b689b8274f4dbe19b3fb24d66840'),
('7c4f86aba34f430884fb87fee84bc870', 'ebca07da8aad42c4aee304e3a6b81001'),
('7c4f86aba34f430884fb87fee84bc870', 'd821a505-1e08-11ed-87ce-1078d2a38ee5'),
('7c4f86aba34f430884fb87fee84bc870', 'eecbccb6-1e08-11ed-87ce-1078d2a38ee5'),
('7c4f86aba34f430884fb87fee84bc870', '4943c707-1e08-11ed-87ce-1078d2a38ee5'),
('7c4f86aba34f430884fb87fee84bc870', 'a91ee437-1e08-11ed-87ce-1078d2a38ee5'),
('7c4f86aba34f430884fb87fee84bc870', '1c1940da68fa4f8ba2325e83c303c47c'),
('7c4f86aba34f430884fb87fee84bc870', '38645f82ae7c468abad1ab191e7a8ad9'),
('7c4f86aba34f430884fb87fee84bc870', '4bb845580b464d7db3d7c3b3e4fd213b'),
('ee2c544aa4dc4c1eb12472cd84406358', '068adb0171304c628b267874004d7e8c'),
('ee2c544aa4dc4c1eb12472cd84406358', 'f76c69fb-16f4-11ed-ae5c-1078d2a38ee5'),
('ee2c544aa4dc4c1eb12472cd84406358', 'bc5a7cb945e14432bfdf312e2059e868'),
('ee2c544aa4dc4c1eb12472cd84406358', 'faa7c4808c714ca49762f6aaade7da3b'),
('ee2c544aa4dc4c1eb12472cd84406358', '1b89744217b04f79a8c1d7a967a46912'),
('ee2c544aa4dc4c1eb12472cd84406358', '6903e82e7e94478f87df3cf80de6b587'),
('ee2c544aa4dc4c1eb12472cd84406358', '8791f143a90e42e2a4d1d0d6b1254bad'),
('ee2c544aa4dc4c1eb12472cd84406358', '569c1d340cea4b21a54910177eeaf51f'),
('ee2c544aa4dc4c1eb12472cd84406358', '99a7a50e866749879f55b92df2b5449c'),
('ee2c544aa4dc4c1eb12472cd84406358', 'db87d2605a68440fbf8e148744e243e8'),
('ee2c544aa4dc4c1eb12472cd84406358', '6b4e3b36783d4a488101da7639c40de0'),
('ee2c544aa4dc4c1eb12472cd84406358', '808ab7987c9b4f0ab025b1b9e3ed1d43'),
('ee2c544aa4dc4c1eb12472cd84406358', '88f281e83aff47d08f555a2961420bf5'),
('ee2c544aa4dc4c1eb12472cd84406358', '2ea1d4e7a4ae4677a0fc85b859cc5738'),
('ee2c544aa4dc4c1eb12472cd84406358', '0ce44192fb05400fb51f33c3c7a3d601'),
('ee2c544aa4dc4c1eb12472cd84406358', '09179170e6e643eca66b282e2ffae1f8'),
('ee2c544aa4dc4c1eb12472cd84406358', '9b4e52c30f974844ac7a050000a0ee6a'),
('ee2c544aa4dc4c1eb12472cd84406358', 'c64802952e504f4ab25a6b1241232f85'),
('ee2c544aa4dc4c1eb12472cd84406358', '171e6210418440a8bf4d689841d0f32c'),
('ee2c544aa4dc4c1eb12472cd84406358', '78712deb909d4d88af7f098c0fcf6857'),
('ee2c544aa4dc4c1eb12472cd84406358', '9d69d18ff5184804990bc21cb1005ab7'),
('ee2c544aa4dc4c1eb12472cd84406358', '4943c707-1e08-11ed-87ce-1078d2a38ee5'),
('ee2c544aa4dc4c1eb12472cd84406358', 'eecbccb6-1e08-11ed-87ce-1078d2a38ee5'),
('7ff48adfec9b44b4bfce7173c012a8c7', '6903e82e7e94478f87df3cf80de6b587'),
('7ff48adfec9b44b4bfce7173c012a8c7', 'a213b689b8274f4dbe19b3fb24d66840'),
('fab4f858e0314d1dbf6b5b834007313e', '29feb750adfa496fa4822fadd4ac1367'),
('fab4f858e0314d1dbf6b5b834007313e', '808ab7987c9b4f0ab025b1b9e3ed1d43'),
('fc2ba902bcfa410397bbd3331bda13ec', '29feb750adfa496fa4822fadd4ac1367'),
('fc2ba902bcfa410397bbd3331bda13ec', '808ab7987c9b4f0ab025b1b9e3ed1d43'),
('e829fe4fb03f45f482f77653158d461c', '808ab7987c9b4f0ab025b1b9e3ed1d43'),
('e829fe4fb03f45f482f77653158d461c', 'c85dbeca3e87406f97d9e10f6d5970d4'),
('0c5a151afe204df2bf6c38485055da16', '808ab7987c9b4f0ab025b1b9e3ed1d43'),
('0c5a151afe204df2bf6c38485055da16', '87b2c263-663c-11ed-94ad-4cedfb61fb39'),
('ba360e2a572f45979cb83648fc5e2ec7', '2ad0efee-6c72-11ed-9e64-4cedfb61fb39'),
('ba360e2a572f45979cb83648fc5e2ec7', '2ea1d4e7a4ae4677a0fc85b859cc5738'),
('e83ca4537495486c8d3b5d7e6ae2407a', '1b89744217b04f79a8c1d7a967a46912'),
('e83ca4537495486c8d3b5d7e6ae2407a', 'cc93223a47764195ac15aacf266673d9'),
('e83ca4537495486c8d3b5d7e6ae2407a', '6903e82e7e94478f87df3cf80de6b587'),
('e83ca4537495486c8d3b5d7e6ae2407a', 'ebca07da8aad42c4aee304e3a6b81001'),
('e83ca4537495486c8d3b5d7e6ae2407a', '569c1d340cea4b21a54910177eeaf51f'),
('e83ca4537495486c8d3b5d7e6ae2407a', '5a1bd42cca6f412cb1795a1aeddac2fe'),
('e83ca4537495486c8d3b5d7e6ae2407a', '7527e84e47f94304b39525fa770dd904'),
('e83ca4537495486c8d3b5d7e6ae2407a', '26a74e3097b94bd882bd1a9f6feace68'),
('e83ca4537495486c8d3b5d7e6ae2407a', '3afa314e14904a1da386b2d8ede3582b'),
('e83ca4537495486c8d3b5d7e6ae2407a', 'db87d2605a68440fbf8e148744e243e8'),
('e83ca4537495486c8d3b5d7e6ae2407a', '6b4e3b36783d4a488101da7639c40de0'),
('e83ca4537495486c8d3b5d7e6ae2407a', '8b3d0b17c9a045fbb76600e5044b0121'),
('e83ca4537495486c8d3b5d7e6ae2407a', 'ac61ff38a6854298919a06a5b4f34242'),
('e83ca4537495486c8d3b5d7e6ae2407a', '405c0fcdbef14d49abd9ffcc53984c6e'),
('e83ca4537495486c8d3b5d7e6ae2407a', '2ea1d4e7a4ae4677a0fc85b859cc5738'),
('e83ca4537495486c8d3b5d7e6ae2407a', '0ce44192fb05400fb51f33c3c7a3d601'),
('12c8a8639d814102b01c7ffc0cd52e71', '2ea1d4e7a4ae4677a0fc85b859cc5738'),
('12c8a8639d814102b01c7ffc0cd52e71', '7d574866-6c74-11ed-9e64-4cedfb61fb39'),
('12c8a8639d814102b01c7ffc0cd52e71', '0ce44192fb05400fb51f33c3c7a3d601'),
('12c8a8639d814102b01c7ffc0cd52e71', '276682372c5c45eca2139b32e4e5cc7a'),
('dc306f83529445139c16dd55b4f0e6f3', '1b89744217b04f79a8c1d7a967a46912'),
('dc306f83529445139c16dd55b4f0e6f3', '1f4e5b3752b8475cb5261940ef62532d'),
('dc306f83529445139c16dd55b4f0e6f3', '89bc4b0ef1dd4306a3217cbf24551071'),
('dc306f83529445139c16dd55b4f0e6f3', 'cc93223a47764195ac15aacf266673d9'),
('dc306f83529445139c16dd55b4f0e6f3', '1098a56970114e18898367d334658b47'),
('dc306f83529445139c16dd55b4f0e6f3', '12b852d92d284ab5a654c26e8856fffd'),
('dc306f83529445139c16dd55b4f0e6f3', '358a13267bcb4608a14c851c3010f79b'),
('dc306f83529445139c16dd55b4f0e6f3', '6903e82e7e94478f87df3cf80de6b587'),
('dc306f83529445139c16dd55b4f0e6f3', '569c1d340cea4b21a54910177eeaf51f'),
('dc306f83529445139c16dd55b4f0e6f3', '99a7a50e866749879f55b92df2b5449c'),
('dc306f83529445139c16dd55b4f0e6f3', 'db87d2605a68440fbf8e148744e243e8'),
('dc306f83529445139c16dd55b4f0e6f3', '6b4e3b36783d4a488101da7639c40de0'),
('dc306f83529445139c16dd55b4f0e6f3', '405c0fcdbef14d49abd9ffcc53984c6e'),
('dc306f83529445139c16dd55b4f0e6f3', '808ab7987c9b4f0ab025b1b9e3ed1d43'),
('dc306f83529445139c16dd55b4f0e6f3', '2ea1d4e7a4ae4677a0fc85b859cc5738'),
('dc306f83529445139c16dd55b4f0e6f3', '0ce44192fb05400fb51f33c3c7a3d601'),
('dc306f83529445139c16dd55b4f0e6f3', 'c64802952e504f4ab25a6b1241232f85'),
('dc306f83529445139c16dd55b4f0e6f3', '171e6210418440a8bf4d689841d0f32c'),
('dc306f83529445139c16dd55b4f0e6f3', '9d69d18ff5184804990bc21cb1005ab7'),
('be8d42fa88a14406ac201974963d9c1b', '068adb0171304c628b267874004d7e8c'),
('be8d42fa88a14406ac201974963d9c1b', '2b6032ef8a73463ba2c761c86be5ed5d'),
('be8d42fa88a14406ac201974963d9c1b', 'bc6b9c821e3f42ccb57532930c8d92be'),
('be8d42fa88a14406ac201974963d9c1b', 'e9f5bad7fdd94494a125e451de456a92'),
('be8d42fa88a14406ac201974963d9c1b', '3a70433b-16f5-11ed-ae5c-1078d2a38ee5'),
('be8d42fa88a14406ac201974963d9c1b', '60b2f2ca-16f5-11ed-ae5c-1078d2a38ee5'),
('be8d42fa88a14406ac201974963d9c1b', '8baa9163-16f5-11ed-ae5c-1078d2a38ee5'),
('be8d42fa88a14406ac201974963d9c1b', 'f76c69fb-16f4-11ed-ae5c-1078d2a38ee5'),
('be8d42fa88a14406ac201974963d9c1b', '28c3460bb5cf4c618ba8ec6f3c12ddbd'),
('be8d42fa88a14406ac201974963d9c1b', '8a6141a082554335a2137c90f9fa0a5e'),
('be8d42fa88a14406ac201974963d9c1b', 'bc5a7cb945e14432bfdf312e2059e868'),
('be8d42fa88a14406ac201974963d9c1b', 'be40fd210eb44ee68475bbe80eb8b1ea'),
('be8d42fa88a14406ac201974963d9c1b', '0d9c8667ccb34e9da275e7dce09d9cd9'),
('be8d42fa88a14406ac201974963d9c1b', 'dc43f263313f4788bccbcc9adf642a1f'),
('be8d42fa88a14406ac201974963d9c1b', 'faa7c4808c714ca49762f6aaade7da3b'),
('be8d42fa88a14406ac201974963d9c1b', 'fd061c3363db4b298eea0bb0b4cbcbf0'),
('be8d42fa88a14406ac201974963d9c1b', '1b89744217b04f79a8c1d7a967a46912'),
('be8d42fa88a14406ac201974963d9c1b', '1f4e5b3752b8475cb5261940ef62532d'),
('be8d42fa88a14406ac201974963d9c1b', '89bc4b0ef1dd4306a3217cbf24551071'),
('be8d42fa88a14406ac201974963d9c1b', 'cc93223a47764195ac15aacf266673d9'),
('be8d42fa88a14406ac201974963d9c1b', '1098a56970114e18898367d334658b47'),
('be8d42fa88a14406ac201974963d9c1b', '12b852d92d284ab5a654c26e8856fffd'),
('be8d42fa88a14406ac201974963d9c1b', '33c3711d787d416082c0519356547b0c'),
('be8d42fa88a14406ac201974963d9c1b', '358a13267bcb4608a14c851c3010f79b'),
('be8d42fa88a14406ac201974963d9c1b', '5d793b19c75046b9a4d75d067e8e33b2'),
('be8d42fa88a14406ac201974963d9c1b', '6903e82e7e94478f87df3cf80de6b587'),
('be8d42fa88a14406ac201974963d9c1b', '8791f143a90e42e2a4d1d0d6b1254bad'),
('be8d42fa88a14406ac201974963d9c1b', '8de7d59a74f345a5bcab20ec43376299'),
('be8d42fa88a14406ac201974963d9c1b', '9beba245308543ce821efe8a3ba965e3'),
('be8d42fa88a14406ac201974963d9c1b', 'a213b689b8274f4dbe19b3fb24d66840'),
('be8d42fa88a14406ac201974963d9c1b', 'ebca07da8aad42c4aee304e3a6b81001'),
('be8d42fa88a14406ac201974963d9c1b', '569c1d340cea4b21a54910177eeaf51f'),
('be8d42fa88a14406ac201974963d9c1b', '5a1bd42cca6f412cb1795a1aeddac2fe'),
('be8d42fa88a14406ac201974963d9c1b', '7527e84e47f94304b39525fa770dd904'),
('be8d42fa88a14406ac201974963d9c1b', '99a7a50e866749879f55b92df2b5449c'),
('be8d42fa88a14406ac201974963d9c1b', '26a74e3097b94bd882bd1a9f6feace68'),
('be8d42fa88a14406ac201974963d9c1b', '3afa314e14904a1da386b2d8ede3582b'),
('be8d42fa88a14406ac201974963d9c1b', 'db87d2605a68440fbf8e148744e243e8'),
('be8d42fa88a14406ac201974963d9c1b', '6b4e3b36783d4a488101da7639c40de0'),
('be8d42fa88a14406ac201974963d9c1b', '8b3d0b17c9a045fbb76600e5044b0121'),
('be8d42fa88a14406ac201974963d9c1b', 'ac61ff38a6854298919a06a5b4f34242'),
('be8d42fa88a14406ac201974963d9c1b', '405c0fcdbef14d49abd9ffcc53984c6e'),
('be8d42fa88a14406ac201974963d9c1b', '6effe04d-8fd9-11ed-a315-4cedfb61fb39'),
('be8d42fa88a14406ac201974963d9c1b', '808ab7987c9b4f0ab025b1b9e3ed1d43'),
('be8d42fa88a14406ac201974963d9c1b', '88f281e83aff47d08f555a2961420bf5'),
('be8d42fa88a14406ac201974963d9c1b', 'a9354dd060524bce8278e2cd75ce349a'),
('be8d42fa88a14406ac201974963d9c1b', 'ce3589b822a14011ba581c803ef50f5b'),
('be8d42fa88a14406ac201974963d9c1b', '25b1853c-6952-11ed-9234-4cedfb61fb39'),
('be8d42fa88a14406ac201974963d9c1b', '2c2753d3-6951-11ed-9234-4cedfb61fb39'),
('be8d42fa88a14406ac201974963d9c1b', '2ea1d4e7a4ae4677a0fc85b859cc5738'),
('be8d42fa88a14406ac201974963d9c1b', '457aca55-6952-11ed-9234-4cedfb61fb39'),
('be8d42fa88a14406ac201974963d9c1b', '0ce44192fb05400fb51f33c3c7a3d601'),
('be8d42fa88a14406ac201974963d9c1b', '2e5924c8cc0e444dae36bafd2c89d727'),
('be8d42fa88a14406ac201974963d9c1b', '683c46bd6f8d48e286f0da3767098c2e'),
('be8d42fa88a14406ac201974963d9c1b', '8f6fb226ee414c5ea7afd565cf099d7d'),
('be8d42fa88a14406ac201974963d9c1b', '09179170e6e643eca66b282e2ffae1f8'),
('be8d42fa88a14406ac201974963d9c1b', '4d64a842e08344b9aeec88ed9eb2eb72'),
('be8d42fa88a14406ac201974963d9c1b', '9b4e52c30f974844ac7a050000a0ee6a'),
('be8d42fa88a14406ac201974963d9c1b', 'c64802952e504f4ab25a6b1241232f85'),
('be8d42fa88a14406ac201974963d9c1b', 'e0860766d564483e870b5974a601649c'),
('be8d42fa88a14406ac201974963d9c1b', '9d69d18ff5184804990bc21cb1005ab7'),
('be8d42fa88a14406ac201974963d9c1b', 'c21495eca0d44776aeacf431dc9fb0e1'),
('be8d42fa88a14406ac201974963d9c1b', '8d9b1da4234f46eb858e1ea490da6348'),
('be8d42fa88a14406ac201974963d9c1b', 'a6034d814d7e4671b4cc8a98433f8fb2'),
('be8d42fa88a14406ac201974963d9c1b', '4943c707-1e08-11ed-87ce-1078d2a38ee5'),
('be8d42fa88a14406ac201974963d9c1b', 'a91ee437-1e08-11ed-87ce-1078d2a38ee5'),
('be8d42fa88a14406ac201974963d9c1b', 'd821a505-1e08-11ed-87ce-1078d2a38ee5'),
('be8d42fa88a14406ac201974963d9c1b', 'eecbccb6-1e08-11ed-87ce-1078d2a38ee5'),
('be8d42fa88a14406ac201974963d9c1b', '4bb845580b464d7db3d7c3b3e4fd213b'),
('be8d42fa88a14406ac201974963d9c1b', '1c1940da68fa4f8ba2325e83c303c47c'),
('be8d42fa88a14406ac201974963d9c1b', '38645f82ae7c468abad1ab191e7a8ad9'),
('9f72c952b9ea4474a912d619cba5d0a8', '1b89744217b04f79a8c1d7a967a46912'),
('9f72c952b9ea4474a912d619cba5d0a8', '6903e82e7e94478f87df3cf80de6b587'),
('9f72c952b9ea4474a912d619cba5d0a8', '8791f143a90e42e2a4d1d0d6b1254bad'),
('9f72c952b9ea4474a912d619cba5d0a8', '569c1d340cea4b21a54910177eeaf51f'),
('9f72c952b9ea4474a912d619cba5d0a8', '99a7a50e866749879f55b92df2b5449c'),
('9f72c952b9ea4474a912d619cba5d0a8', 'db87d2605a68440fbf8e148744e243e8'),
('9f72c952b9ea4474a912d619cba5d0a8', '6b4e3b36783d4a488101da7639c40de0'),
('9f72c952b9ea4474a912d619cba5d0a8', '405c0fcdbef14d49abd9ffcc53984c6e'),
('9f72c952b9ea4474a912d619cba5d0a8', '808ab7987c9b4f0ab025b1b9e3ed1d43'),
('9f72c952b9ea4474a912d619cba5d0a8', '2ea1d4e7a4ae4677a0fc85b859cc5738'),
('9f72c952b9ea4474a912d619cba5d0a8', '0ce44192fb05400fb51f33c3c7a3d601'),
('9f72c952b9ea4474a912d619cba5d0a8', '09179170e6e643eca66b282e2ffae1f8'),
('9f72c952b9ea4474a912d619cba5d0a8', '9b4e52c30f974844ac7a050000a0ee6a'),
('9f72c952b9ea4474a912d619cba5d0a8', 'c64802952e504f4ab25a6b1241232f85'),
('9f72c952b9ea4474a912d619cba5d0a8', 'e0860766d564483e870b5974a601649c'),
('9f72c952b9ea4474a912d619cba5d0a8', '8f53727c763849aab80c1513505decf8'),
('9f72c952b9ea4474a912d619cba5d0a8', '9d69d18ff5184804990bc21cb1005ab7'),
('9f72c952b9ea4474a912d619cba5d0a8', 'c21495eca0d44776aeacf431dc9fb0e1'),
('9f72c952b9ea4474a912d619cba5d0a8', '8d9b1da4234f46eb858e1ea490da6348'),
('9f72c952b9ea4474a912d619cba5d0a8', 'a6034d814d7e4671b4cc8a98433f8fb2'),
('9f72c952b9ea4474a912d619cba5d0a8', '4943c707-1e08-11ed-87ce-1078d2a38ee5'),
('9f72c952b9ea4474a912d619cba5d0a8', 'eecbccb6-1e08-11ed-87ce-1078d2a38ee5'),
('9f72c952b9ea4474a912d619cba5d0a8', '38645f82ae7c468abad1ab191e7a8ad9'),
('ceadd9fb648445eab1e350357e51d1ce', '1b89744217b04f79a8c1d7a967a46912'),
('ceadd9fb648445eab1e350357e51d1ce', '1098a56970114e18898367d334658b47'),
('ceadd9fb648445eab1e350357e51d1ce', '6903e82e7e94478f87df3cf80de6b587'),
('ceadd9fb648445eab1e350357e51d1ce', 'ebca07da8aad42c4aee304e3a6b81001'),
('ceadd9fb648445eab1e350357e51d1ce', '569c1d340cea4b21a54910177eeaf51f'),
('ceadd9fb648445eab1e350357e51d1ce', '5a1bd42cca6f412cb1795a1aeddac2fe'),
('ceadd9fb648445eab1e350357e51d1ce', '7527e84e47f94304b39525fa770dd904'),
('ceadd9fb648445eab1e350357e51d1ce', '26a74e3097b94bd882bd1a9f6feace68'),
('ceadd9fb648445eab1e350357e51d1ce', '3afa314e14904a1da386b2d8ede3582b'),
('ceadd9fb648445eab1e350357e51d1ce', 'db87d2605a68440fbf8e148744e243e8'),
('ceadd9fb648445eab1e350357e51d1ce', '6b4e3b36783d4a488101da7639c40de0'),
('ceadd9fb648445eab1e350357e51d1ce', '8b3d0b17c9a045fbb76600e5044b0121'),
('ceadd9fb648445eab1e350357e51d1ce', 'ac61ff38a6854298919a06a5b4f34242'),
('ceadd9fb648445eab1e350357e51d1ce', '405c0fcdbef14d49abd9ffcc53984c6e'),
('ceadd9fb648445eab1e350357e51d1ce', '808ab7987c9b4f0ab025b1b9e3ed1d43'),
('ceadd9fb648445eab1e350357e51d1ce', '2ea1d4e7a4ae4677a0fc85b859cc5738'),
('ceadd9fb648445eab1e350357e51d1ce', '0ce44192fb05400fb51f33c3c7a3d601'),
('52706514ef294f11807d246bc701fb7a', '1b89744217b04f79a8c1d7a967a46912'),
('52706514ef294f11807d246bc701fb7a', '6903e82e7e94478f87df3cf80de6b587'),
('52706514ef294f11807d246bc701fb7a', '569c1d340cea4b21a54910177eeaf51f'),
('52706514ef294f11807d246bc701fb7a', 'db87d2605a68440fbf8e148744e243e8'),
('52706514ef294f11807d246bc701fb7a', '6b4e3b36783d4a488101da7639c40de0'),
('52706514ef294f11807d246bc701fb7a', '405c0fcdbef14d49abd9ffcc53984c6e'),
('52706514ef294f11807d246bc701fb7a', '808ab7987c9b4f0ab025b1b9e3ed1d43'),
('52706514ef294f11807d246bc701fb7a', '88f281e83aff47d08f555a2961420bf5'),
('303f2de11d4f472d869757d7ea5bb83f', '29feb750adfa496fa4822fadd4ac1367'),
('303f2de11d4f472d869757d7ea5bb83f', '808ab7987c9b4f0ab025b1b9e3ed1d43'),
('86ce9a31dae44411a568cc4b97dad7fd', '29feb750adfa496fa4822fadd4ac1367'),
('86ce9a31dae44411a568cc4b97dad7fd', '63759b4b-663c-11ed-94ad-4cedfb61fb39'),
('86ce9a31dae44411a568cc4b97dad7fd', '808ab7987c9b4f0ab025b1b9e3ed1d43'),
('a1b814111063435eb7a862c18d1de4cf', '2c2753d3-6951-11ed-9234-4cedfb61fb39'),
('a1b814111063435eb7a862c18d1de4cf', '2ea1d4e7a4ae4677a0fc85b859cc5738'),
('a1b814111063435eb7a862c18d1de4cf', '0ce44192fb05400fb51f33c3c7a3d601'),
('a1b814111063435eb7a862c18d1de4cf', '683c46bd6f8d48e286f0da3767098c2e'),
('a4f8d1d67d2e4b9aa2a8e8680a953194', '2ea1d4e7a4ae4677a0fc85b859cc5738'),
('a4f8d1d67d2e4b9aa2a8e8680a953194', 'ad4c0acc-6c71-11ed-9e64-4cedfb61fb39'),
('0007828bc2a5496bbdd8fbaefe2e1565', '63759b4b-663c-11ed-94ad-4cedfb61fb39'),
('0007828bc2a5496bbdd8fbaefe2e1565', '808ab7987c9b4f0ab025b1b9e3ed1d43'),
('0007828bc2a5496bbdd8fbaefe2e1565', '2a6ddda5-6c74-11ed-9e64-4cedfb61fb39'),
('0007828bc2a5496bbdd8fbaefe2e1565', '2ea1d4e7a4ae4677a0fc85b859cc5738'),
('0007828bc2a5496bbdd8fbaefe2e1565', '0ce44192fb05400fb51f33c3c7a3d601'),
('0007828bc2a5496bbdd8fbaefe2e1565', '4b3413e795944f48af97085069fb6855'),
('3d43ab399ec24c30b39c9b052686416d', '0ce44192fb05400fb51f33c3c7a3d601'),
('3d43ab399ec24c30b39c9b052686416d', 'e0923722a9fc44abaa84e59d545afe69'),
('c94ad7236255430b82c0546dd82b917e', '1b89744217b04f79a8c1d7a967a46912'),
('c94ad7236255430b82c0546dd82b917e', '6903e82e7e94478f87df3cf80de6b587'),
('c94ad7236255430b82c0546dd82b917e', '9beba245308543ce821efe8a3ba965e3'),
('c94ad7236255430b82c0546dd82b917e', '405c0fcdbef14d49abd9ffcc53984c6e'),
('c94ad7236255430b82c0546dd82b917e', '6effe04d-8fd9-11ed-a315-4cedfb61fb39'),
('c94ad7236255430b82c0546dd82b917e', 'c64802952e504f4ab25a6b1241232f85'),
('c94ad7236255430b82c0546dd82b917e', '4cea10b3a4434bc3b342407a78a9ab2a'),
('c94ad7236255430b82c0546dd82b917e', '9d69d18ff5184804990bc21cb1005ab7'),
('dc4547d5a820478188ae9c1a1ae876a6', '1b89744217b04f79a8c1d7a967a46912'),
('dc4547d5a820478188ae9c1a1ae876a6', '1f4e5b3752b8475cb5261940ef62532d'),
('dc4547d5a820478188ae9c1a1ae876a6', '89bc4b0ef1dd4306a3217cbf24551071'),
('dc4547d5a820478188ae9c1a1ae876a6', 'cc93223a47764195ac15aacf266673d9'),
('dc4547d5a820478188ae9c1a1ae876a6', '1098a56970114e18898367d334658b47'),
('dc4547d5a820478188ae9c1a1ae876a6', '358a13267bcb4608a14c851c3010f79b'),
('dc4547d5a820478188ae9c1a1ae876a6', '6903e82e7e94478f87df3cf80de6b587'),
('dc4547d5a820478188ae9c1a1ae876a6', '569c1d340cea4b21a54910177eeaf51f'),
('dc4547d5a820478188ae9c1a1ae876a6', 'db87d2605a68440fbf8e148744e243e8'),
('dc4547d5a820478188ae9c1a1ae876a6', '6b4e3b36783d4a488101da7639c40de0'),
('dc4547d5a820478188ae9c1a1ae876a6', '405c0fcdbef14d49abd9ffcc53984c6e'),
('dc4547d5a820478188ae9c1a1ae876a6', '808ab7987c9b4f0ab025b1b9e3ed1d43'),
('dc4547d5a820478188ae9c1a1ae876a6', '2ea1d4e7a4ae4677a0fc85b859cc5738'),
('dc4547d5a820478188ae9c1a1ae876a6', '0ce44192fb05400fb51f33c3c7a3d601'),
('dc4547d5a820478188ae9c1a1ae876a6', '4d64a842e08344b9aeec88ed9eb2eb72'),
('dc4547d5a820478188ae9c1a1ae876a6', 'c64802952e504f4ab25a6b1241232f85'),
('dc4547d5a820478188ae9c1a1ae876a6', '9d69d18ff5184804990bc21cb1005ab7'),
('dc4547d5a820478188ae9c1a1ae876a6', 'c21495eca0d44776aeacf431dc9fb0e1'),
('ab76995143c94f07b6cadc832f252752', 'c64802952e504f4ab25a6b1241232f85'),
('ab76995143c94f07b6cadc832f252752', '78712deb909d4d88af7f098c0fcf6857'),
('ab76995143c94f07b6cadc832f252752', '9d69d18ff5184804990bc21cb1005ab7'),
('ab76995143c94f07b6cadc832f252752', 'c21495eca0d44776aeacf431dc9fb0e1');

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_ab` (`order_ab`);

--
-- Indexes for table `cabang`
--
ALTER TABLE `cabang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `deskripsi_cover`
--
ALTER TABLE `deskripsi_cover`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deskripsi_cover_history`
--
ALTER TABLE `deskripsi_cover_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deskripsi_final`
--
ALTER TABLE `deskripsi_final`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deskripsi_final_history`
--
ALTER TABLE `deskripsi_final_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deskripsi_produk`
--
ALTER TABLE `deskripsi_produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deskripsi_produk_history`
--
ALTER TABLE `deskripsi_produk_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deskripsi_turun_cetak`
--
ALTER TABLE `deskripsi_turun_cetak`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deskripsi_turun_cetak_history`
--
ALTER TABLE `deskripsi_turun_cetak_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `divisi`
--
ALTER TABLE `divisi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama` (`nama`);

--
-- Indexes for table `editing_proses`
--
ALTER TABLE `editing_proses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `editing_proses_history`
--
ALTER TABLE `editing_proses_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `editing_proses_selesai`
--
ALTER TABLE `editing_proses_selesai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `format_buku`
--
ALTER TABLE `format_buku`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `format_buku_history`
--
ALTER TABLE `format_buku_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `imprint`
--
ALTER TABLE `imprint`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `imprint_history`
--
ALTER TABLE `imprint_history`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `order_cetak`
--
ALTER TABLE `order_cetak`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_order` (`kode_order`);

--
-- Indexes for table `order_cetak_action`
--
ALTER TABLE `order_cetak_action`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_cetak_history`
--
ALTER TABLE `order_cetak_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_ebook`
--
ALTER TABLE `order_ebook`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_order` (`kode_order`);

--
-- Indexes for table `order_ebook_action`
--
ALTER TABLE `order_ebook_action`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_ebook_history`
--
ALTER TABLE `order_ebook_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penerbitan_m_kelompok_buku`
--
ALTER TABLE `penerbitan_m_kelompok_buku`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `penerbitan_m_kelompok_buku_history`
--
ALTER TABLE `penerbitan_m_kelompok_buku_history`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `penerbitan_naskah_history`
--
ALTER TABLE `penerbitan_naskah_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penerbitan_penulis`
--
ALTER TABLE `penerbitan_penulis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ktp` (`ktp`),
  ADD UNIQUE KEY `npwp` (`npwp`);

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
-- Indexes for table `pilihan_penerbitan`
--
ALTER TABLE `pilihan_penerbitan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `platform_digital_ebook`
--
ALTER TABLE `platform_digital_ebook`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `platform_digital_ebook_history`
--
ALTER TABLE `platform_digital_ebook_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pracetak_cover`
--
ALTER TABLE `pracetak_cover`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pracetak_cover_history`
--
ALTER TABLE `pracetak_cover_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pracetak_cover_proof`
--
ALTER TABLE `pracetak_cover_proof`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pracetak_cover_selesai`
--
ALTER TABLE `pracetak_cover_selesai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pracetak_setter`
--
ALTER TABLE `pracetak_setter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pracetak_setter_history`
--
ALTER TABLE `pracetak_setter_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pracetak_setter_proof`
--
ALTER TABLE `pracetak_setter_proof`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pracetak_setter_selesai`
--
ALTER TABLE `pracetak_setter_selesai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proses_ebook_multimedia`
--
ALTER TABLE `proses_ebook_multimedia`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proses_produksi_cetak`
--
ALTER TABLE `proses_produksi_cetak`
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

--
-- Indexes for table `user_log`
--
ALTER TABLE `user_log`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `deskripsi_cover_history`
--
ALTER TABLE `deskripsi_cover_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `deskripsi_final_history`
--
ALTER TABLE `deskripsi_final_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `deskripsi_produk_history`
--
ALTER TABLE `deskripsi_produk_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `deskripsi_turun_cetak_history`
--
ALTER TABLE `deskripsi_turun_cetak_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `editing_proses_history`
--
ALTER TABLE `editing_proses_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `editing_proses_selesai`
--
ALTER TABLE `editing_proses_selesai`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `format_buku_history`
--
ALTER TABLE `format_buku_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `imprint_history`
--
ALTER TABLE `imprint_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_cetak_action`
--
ALTER TABLE `order_cetak_action`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_cetak_history`
--
ALTER TABLE `order_cetak_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `order_ebook_action`
--
ALTER TABLE `order_ebook_action`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_ebook_history`
--
ALTER TABLE `order_ebook_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `penerbitan_m_kelompok_buku_history`
--
ALTER TABLE `penerbitan_m_kelompok_buku_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `penerbitan_naskah_history`
--
ALTER TABLE `penerbitan_naskah_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `platform_digital_ebook_history`
--
ALTER TABLE `platform_digital_ebook_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pracetak_cover_history`
--
ALTER TABLE `pracetak_cover_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `pracetak_cover_proof`
--
ALTER TABLE `pracetak_cover_proof`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pracetak_cover_selesai`
--
ALTER TABLE `pracetak_cover_selesai`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `pracetak_setter_history`
--
ALTER TABLE `pracetak_setter_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- AUTO_INCREMENT for table `pracetak_setter_proof`
--
ALTER TABLE `pracetak_setter_proof`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pracetak_setter_selesai`
--
ALTER TABLE `pracetak_setter_selesai`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `user_log`
--
ALTER TABLE `user_log`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=229;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
