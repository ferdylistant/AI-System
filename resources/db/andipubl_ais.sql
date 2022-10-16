-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2022 at 06:14 PM
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
('3dbad039493241aa8ed0c698d07ee94d', NULL, '3f9dfd9391394a5fa10d835e0ebb341c', 1, 4, 'master/format-buku', 'fas fa-ruler-combined', 'Format Buku'),
('4e1627c1489844f985cbe2c485b2e162', NULL, 'f7e795b9ece54c6d82b0ed19f025a65e', 1, 1, 'manajemen-web/struktur-ao', 'fas fa-project-diagram', 'Struktur Organisasi'),
('5646908e-1e06-11ed-87ce-1078d2a38ee5', NULL, '8a3ca046fb54492a86aaead53f36bec7', 1, 1, 'produksi/proses/cetak', 'fas fa-chalkboard-teacher', 'Proses Produksi Cetak'),
('583a723cf036449d80d3742dcf695e38', NULL, '063203a5c5124b399ab76f8a03b93c0d', 1, 9, 'penerbitan/naskah/timeline', 'fas fa-question-circle', 'Timeline'),
('5ce34256ce1f4a8989ac8f3510576600', NULL, '063203a5c5124b399ab76f8a03b93c0d', 1, 3, '#', 'fas fa-clipboard-check', 'Deskripsi'),
('63a1825ffe574c00929e532fd6241629', NULL, '063203a5c5124b399ab76f8a03b93c0d', 1, 5, 'penerbitan/pracetak', 'fas fa-file-powerpoint', 'Pracetak'),
('70410774a1e0433bb213a9625aceb0bb', NULL, '063203a5c5124b399ab76f8a03b93c0d', 1, 6, 'penerbitan/order-cetak', 'fas fa-print', 'Order Cetak'),
('71d6b5671ebb4e128215fccc458fbf09', '5ce34256ce1f4a8989ac8f3510576600', '063203a5c5124b399ab76f8a03b93c0d', 2, 3, 'penerbitan/deskripsi/cover', 'fas fa-question-circle', 'Cover'),
('8bc1be5db97545e2ab1c79e0d68d4896', NULL, '3f9dfd9391394a5fa10d835e0ebb341c', 1, 1, 'master/platform-digital', 'fas fa-globe', 'Platform Digital'),
('92463f9e96394c19a979a3290fde5745', NULL, '063203a5c5124b399ab76f8a03b93c0d', 1, 4, 'penerbitan/editing', 'fas fa-user-edit', 'Editing'),
('b6cbf112-1e06-11ed-87ce-1078d2a38ee5', NULL, '8a3ca046fb54492a86aaead53f36bec7', 1, 2, 'produksi/proses/ebook-multimedia', 'fas fa-desktop', 'E-book Multimedia'),
('bc5eb3aa02394dcca7692764e1328cee', NULL, '3f9dfd9391394a5fa10d835e0ebb341c', 1, 2, 'master/imprint', 'fas fa-stamp', 'Imprint'),
('bd09e803c41245a49ef23987c27b20ac', '5ce34256ce1f4a8989ac8f3510576600', '063203a5c5124b399ab76f8a03b93c0d', 2, 1, 'penerbitan/deskripsi/produk', 'fas fa-question-circle', 'Produk'),
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
  `order_ab` tinyint(4) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `access_bagian`
--

INSERT INTO `access_bagian` (`id`, `name`, `order_ab`) VALUES
('04431b2b0e864cd4af41c87256cb92ef', 'Dashboard', 1),
('063203a5c5124b399ab76f8a03b93c0d', 'Penerbitan', 3),
('3f9dfd9391394a5fa10d835e0ebb341c', 'Master Data', 2),
('8a3ca046fb54492a86aaead53f36bec7', 'Produksi', 4),
('f7e795b9ece54c6d82b0ed19f025a65e', 'Manajemen Web', 5);

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
-- Table structure for table `deskripsi_cover`
--

CREATE TABLE `deskripsi_cover` (
  `id` char(36) DEFAULT NULL,
  `deskripsi_produk_id` char(36) DEFAULT NULL,
  `des_front_cover` text DEFAULT NULL,
  `des_back_cover` text DEFAULT NULL,
  `finishing_cover` text DEFAULT NULL COMMENT 'Array',
  `jilid` enum('Binding','Jahit Kawat','Jahit Benang') DEFAULT NULL,
  `tipografi` varchar(255) DEFAULT NULL,
  `warna` varchar(255) DEFAULT NULL,
  `desainer` varchar(36) DEFAULT NULL,
  `bulan` date DEFAULT NULL,
  `tgl_deskripsi` datetime DEFAULT NULL,
  `contoh_cover` blob DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `deskripsi_cover`
--

INSERT INTO `deskripsi_cover` (`id`, `deskripsi_produk_id`, `des_front_cover`, `des_back_cover`, `finishing_cover`, `jilid`, `tipografi`, `warna`, `desainer`, `bulan`, `tgl_deskripsi`, `contoh_cover`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
('44dfe332-5755-4191-8158-e79d496e1473', '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-10-04 16:34:45', NULL, NULL, NULL, NULL, NULL);

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
  `bullet` text DEFAULT NULL COMMENT 'array',
  `sinopsis` longtext DEFAULT NULL,
  `setter` varchar(36) DEFAULT NULL,
  `korektor` varchar(36) DEFAULT NULL,
  `bulan` date DEFAULT NULL,
  `tgl_deskripsi` datetime DEFAULT NULL,
  `status` enum('Pending','Proses','Selesai','Antrian') NOT NULL DEFAULT 'Antrian',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `deskripsi_final`
--

INSERT INTO `deskripsi_final` (`id`, `deskripsi_produk_id`, `sub_judul_final`, `kertas_isi`, `jml_hal_asli`, `ukuran_asli`, `isi_warna`, `isi_huruf`, `bullet`, `sinopsis`, `setter`, `korektor`, `bulan`, `tgl_deskripsi`, `status`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
('44dfe332-5755-4191-8158-e79d496e1473', '2f7bf184-23cf-42d7-9efc-19e47aaa3f14', NULL, 'Art Paper', 900, NULL, 'Full Color', 'Menyesuaikan', '[\"Cara Mendapati Masa Depan Dengan Bahagia\",\"Menjauhi Prasangka Yang Membinasakan\",\"Menyikapi Problematika\"]', 'Buku ini merupakan sebuah pandangan hidup sesorang tentang betapa unik dan penuh kompleksitas segala entitas.', '355c4e0e850c43f382cf1052f7053f40', NULL, '2022-10-13', '2022-10-04 16:34:45', 'Proses', '2022-10-13 07:58:05', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `deskripsi_final_history`
--

CREATE TABLE `deskripsi_final_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `deskripsi_final_id` char(36) DEFAULT NULL,
  `type_history` enum('Status','Update') DEFAULT NULL,
  `format_buku_his` varchar(10) DEFAULT NULL,
  `format_buku_new` varchar(10) DEFAULT NULL,
  `judul_final_his` varchar(255) DEFAULT NULL,
  `judul_final_new` varchar(255) DEFAULT NULL,
  `sub_judul_final_his` varchar(255) DEFAULT NULL,
  `sub_judul_final_new` varchar(255) DEFAULT NULL,
  `kertas_isi_his` varchar(15) DEFAULT NULL,
  `kertas_isi_new` varchar(15) DEFAULT NULL,
  `jml_hal_his` int(11) DEFAULT NULL,
  `jml_hal_new` int(11) DEFAULT NULL,
  `jml_hal_asli_his` int(11) DEFAULT NULL,
  `jml_hal_asli_new` int(11) DEFAULT NULL,
  `ukuran_asli_his` varchar(10) DEFAULT NULL,
  `ukuran_asli_new` varchar(10) DEFAULT NULL,
  `isi_warna_his` varchar(15) DEFAULT NULL,
  `isi_warna_new` varchar(15) DEFAULT NULL,
  `isi_huruf_his` varchar(50) DEFAULT NULL,
  `isi_huruf_new` varchar(50) DEFAULT NULL,
  `bullet_his` text DEFAULT NULL,
  `bullet_new` text DEFAULT NULL,
  `setter_his` varchar(36) DEFAULT NULL,
  `setter_new` varchar(36) DEFAULT NULL,
  `korektor_his` varchar(36) DEFAULT NULL,
  `korektor_new` varchar(36) DEFAULT NULL,
  `sinopsis_his` longtext DEFAULT NULL,
  `sinopsis_new` longtext DEFAULT NULL,
  `bulan_his` date DEFAULT NULL,
  `bulan_new` date DEFAULT NULL,
  `kelengkapan_his` varchar(10) DEFAULT NULL,
  `kelengkapan_new` varchar(10) DEFAULT NULL,
  `catatan_his` text DEFAULT NULL,
  `catatan_new` text DEFAULT NULL,
  `status_his` varchar(8) DEFAULT NULL,
  `status_new` varchar(8) DEFAULT NULL,
  `author_id` varchar(36) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `deskripsi_final_history`
--

INSERT INTO `deskripsi_final_history` (`id`, `deskripsi_final_id`, `type_history`, `format_buku_his`, `format_buku_new`, `judul_final_his`, `judul_final_new`, `sub_judul_final_his`, `sub_judul_final_new`, `kertas_isi_his`, `kertas_isi_new`, `jml_hal_his`, `jml_hal_new`, `jml_hal_asli_his`, `jml_hal_asli_new`, `ukuran_asli_his`, `ukuran_asli_new`, `isi_warna_his`, `isi_warna_new`, `isi_huruf_his`, `isi_huruf_new`, `bullet_his`, `bullet_new`, `setter_his`, `setter_new`, `korektor_his`, `korektor_new`, `sinopsis_his`, `sinopsis_new`, `bulan_his`, `bulan_new`, `kelengkapan_his`, `kelengkapan_new`, `catatan_his`, `catatan_new`, `status_his`, `status_new`, `author_id`, `modified_at`) VALUES
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
(19, '44dfe332-5755-4191-8158-e79d496e1473', 'Update', NULL, NULL, 'Aku & Di Balik Mata Kaca', 'Aku & Di Balik Mata Kaca', NULL, NULL, 'Art Paper', 'Art Paper', NULL, NULL, 900, 900, NULL, NULL, 'Full Color', 'Full Color', 'Menyesuaikan', 'Menyesuaikan', '[\"Cara Mendapati Masa Depan Dengan Bahagia\",\"Menjauhi Prasangka Yang Membinasakan\",\"Menyikapi Problematika\"]', '[\"Cara Mendapati Masa Depan Dengan Bahagia\",\"Menjauhi Prasangka Yang Membinasakan\",\"Menyikapi Problematika\"]', '355c4e0e850c43f382cf1052f7053f40', '355c4e0e850c43f382cf1052f7053f40', NULL, NULL, NULL, 'Buku ini merupakan sebuah pandangan hidup sesorang tentang betapa unik dan penuh kompleksitas segala entitas.', '2022-10-13', '2022-10-13', NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-13 14:58:05');

-- --------------------------------------------------------

--
-- Table structure for table `deskripsi_produk`
--

CREATE TABLE `deskripsi_produk` (
  `id` char(36) NOT NULL,
  `naskah_id` varchar(36) DEFAULT NULL,
  `judul_final` varchar(255) DEFAULT NULL,
  `alt_judul` longtext DEFAULT NULL COMMENT 'array',
  `format_buku` varchar(10) DEFAULT NULL,
  `jml_hal_perkiraan` int(11) DEFAULT NULL,
  `imprint` varchar(50) DEFAULT NULL,
  `editor` varchar(36) DEFAULT NULL,
  `kelengkapan` enum('CD','Disket','DVD') DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `bulan` date DEFAULT NULL,
  `tgl_deskripsi` timestamp NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Proses','Selesai','Antrian','Revisi','Acc') DEFAULT NULL,
  `action_gm` datetime DEFAULT NULL,
  `alasan_revisi` tinytext DEFAULT NULL,
  `deadline_revisi` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `deskripsi_produk`
--

INSERT INTO `deskripsi_produk` (`id`, `naskah_id`, `judul_final`, `alt_judul`, `format_buku`, `jml_hal_perkiraan`, `imprint`, `editor`, `kelengkapan`, `catatan`, `bulan`, `tgl_deskripsi`, `status`, `action_gm`, `alasan_revisi`, `deadline_revisi`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
('2f7bf184-23cf-42d7-9efc-19e47aaa3f14', '16fdb6f329c544c6824cf85ec38501b7', 'Aku & Di Balik Mata Kaca', '[\"Aku & Di Balik Mata Kaca\",\"Di Balik Mata Kaca Terabaikan\",\"Ada Di Balik Mata Kaca\"]', '15,5 x 23', 1000, 'G-Media', NULL, NULL, NULL, '2022-09-01', '2022-09-14 05:00:42', 'Acc', '2022-10-04 16:34:44', NULL, NULL, '2022-10-10 03:43:03', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL);

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
  `alt_judul_his` longtext DEFAULT NULL,
  `alt_judul_new` longtext DEFAULT NULL,
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
  `catatan_his` text DEFAULT NULL,
  `catatan_new` text DEFAULT NULL,
  `bulan_his` date DEFAULT NULL,
  `bulan_new` date DEFAULT NULL,
  `status_his` varchar(8) DEFAULT NULL,
  `status_new` varchar(8) DEFAULT NULL,
  `alasan_revisi_his` tinytext DEFAULT NULL,
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
(51, '44dfe332-5755-4191-8158-e79d496e1473', 'Status', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Antrian', 'Proses', NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-10-07 15:11:39');

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
('06b17377feea4c9abad79795c52d5a02', 'AMD', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:43:39', NULL, NULL),
('1f7a55f145f84a9e9fbfa218482a2668', 'AGS', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-05-30 19:22:24', NULL, NULL),
('210f90ae56bf4428a061f0402f3a9ef6', 'PINKA', '57a9534bd79d4382bb0f43c89910702c', '57a9534bd79d4382bb0f43c89910702c', NULL, '2022-05-30 20:44:17', '2022-05-30 21:48:17', NULL),
('3561e1b9e15a4b9588f1c53366a5eaa8', 'AO - Penerbitan', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:44:04', NULL, NULL),
('55e6f7e600b94280895979e608a1905e', 'AO - Produksi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:44:24', NULL, NULL),
('646a4663aea14eb9915b718cbcc5e33b', 'Direksi', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-03-31 08:33:54', NULL, NULL),
('821ac200b1de45fdad7d533ce0190492', 'Produksi', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-03-31 08:33:36', NULL, NULL),
('b2a21896e2004b46b4a489118376a855', 'AO - PUP', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:44:29', NULL, NULL),
('cba340ae34984106a28fb87dbd3c4f84', 'AO - Pemasaran', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:43:55', NULL, NULL),
('cc0c20fa203249e6aae0bbf3d9f38ffe', 'AO - Non Departemen', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 19:59:17', NULL, NULL),
('d1946a0d285944488032d2dcd1a7882b', 'Operasional', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-03-31 08:33:48', NULL, NULL),
('d252d685b7cc469cbc7063a03a70a26c', 'AO - Operasional', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:45:32', NULL, NULL),
('df719b3e9de442b3ba21b1b414887ec7', 'Marketing', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-03-31 08:33:18', NULL, NULL),
('e20b4a5ba69a452bb8a704f5e8823b43', 'GME', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:43:44', NULL, NULL),
('fda3c745c5d6410cb8eb25fb41ae4d36', 'AO - Keuangan', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 19:59:01', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `format_buku`
--

CREATE TABLE `format_buku` (
  `id` char(36) NOT NULL,
  `jenis_format` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `created_by` varchar(36) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
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
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `created_by` varchar(36) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
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
  `created_at` timestamp NULL DEFAULT current_timestamp(),
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
('06a9d8c148eb4ccdb1cbe115d7474b69', 'Operator Mesin Potong Tiga Sisi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:29:06', NULL, NULL),
('07e058a9224645df94a5e5c7894c9ed1', 'Management Trainee (MT)', '57a9534bd79d4382bb0f43c89910702c', '57a9534bd79d4382bb0f43c89910702c', NULL, '2022-05-30 20:00:30', '2022-05-30 20:00:42', NULL),
('0e8709c7e3e4492cbc95087af5349f60', '2 Manager QC & Kalkulasi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:00:19', NULL, NULL),
('115a55d9446a4625b8f81ef6a0620cae', 'Arsip Plate', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:14:42', NULL, NULL),
('12272919184845e5b34b26045033709f', 'Operator Mesin Potong Itotech', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:28:40', NULL, NULL),
('1228e971102549dfabd69def30fa09e8', 'Marketplace Support', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:25:22', NULL, NULL),
('1362615d867c4109874147e8b59eefc6', 'Direct Selling Sekolah', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:15:52', NULL, NULL),
('1716fd2679b04f53bc3008fbcb265bb7', 'Administrasi Retur Fisik', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:13:55', NULL, NULL),
('189da055b78e4f86911970cab8d16323', 'IT Support', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:20:52', NULL, NULL),
('191e859f0223457f941153a04ee131d9', 'Operator Mesin Speed Master 4 Warna (Pak Yusuf)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:30:06', NULL, NULL),
('19f1242197284ffe854ea80e9ea141da', '5 Ekspedisi & Packing (Koordinator)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:10:28', NULL, NULL),
('1aa8e622fa4b472a98b6c5f782538078', 'IT dan Programmer', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:20:46', NULL, NULL),
('1bdb449355f94921bd160fed0695121d', 'Finishing Non Buku', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:19:40', NULL, NULL),
('1cd910eee9054a7d8d820ab23944a4cd', '2 Manager Area 1', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:48:04', NULL, NULL),
('1ddca5f391d44e24be22c5e8d755aa34', '2 Manager Area 3', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:48:10', NULL, NULL),
('2005df513d5b4a02a8305ee3032ccefc', 'P&RD MoU Rohani', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:30:38', NULL, NULL),
('21cebb63b70d4e05ad33dbc878465d33', 'Multimedia', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:25:27', NULL, NULL),
('2208ed773793471fabdb73033c4e494e', 'DS Umum dan Rohani', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:18:33', NULL, NULL),
('2236e590d3f04506b404e44ee0477add', 'Operator UV Besar', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:30:28', NULL, NULL),
('22c4de4765c24371aa8d859336a8bb8b', '2 Manager Accounting', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:47:53', NULL, NULL),
('2431ebed977546aa858c66e468fa972f', 'Direct Selling Perguruan Tinggi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:15:30', NULL, NULL),
('252a3eaac6884323b438e0e8c1f9ecf6', '2 Manager Produksi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:59:48', NULL, NULL),
('2537c25521aa47abaa892157971af4d8', 'Administrasi Keuangan', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:12:32', NULL, NULL),
('2796e655e96540a7ab049141b189c74f', 'Operator Mesin Potong Polar 1993', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:28:59', NULL, NULL),
('279ad59cff324604b0f6894cc7332e9a', 'Packing', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:30:45', NULL, NULL),
('284c727748fa46f1bda555ecd6f3fa5a', 'Pra Cetak Non Penerbitan Intern', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:37:45', NULL, NULL),
('2a8eed1cbdaf468895b8d18247a9be2e', 'DS Tangerang', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:18:18', NULL, NULL),
('2b310af11c2f4f78af23f0760ffefa48', 'Operator Mesin Wrapping Barat', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:29:15', NULL, NULL),
('2cf2869e4fa1485e9f3bfa75ad2781b7', 'Gudang & Ekspedisi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:19:53', NULL, NULL),
('2eb85e97e53a486f8aca2b08d3c3b7d4', 'Input Data Online', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:20:38', NULL, NULL),
('300c4db6ea724f4c80e6a64497304b77', 'Manager Buku Perti', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:45:00', NULL, NULL),
('312d6a9154964f70a8295ea3717bc969', 'Design & Setter', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:14:55', NULL, NULL),
('3189d4fa029542e090632ae162f7cf3e', '4 SMK (Kabag)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:07:16', NULL, NULL),
('356e453597c54e8baf51bc2bc1bdd463', 'Marketing Toko Buku', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:25:00', NULL, NULL),
('36cd1d6e1d6a4b9089e302fb86b8b7b2', 'Operator Hand Press', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:27:46', NULL, NULL),
('36d56e740951461dab5f482bcd55141f', '5 Gudang (Koordinator)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:10:54', NULL, NULL),
('3733f072fba04a63af25ae07de5e58ff', 'Administrasi Piutang', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:13:41', NULL, NULL),
('387ef8138f174e4f9fb2f2d147f03a71', '1 Direktur Operasional', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:46:07', NULL, NULL),
('38d8e64a060f4d7980c2ee01fc69a8ef', '2 Manager Toko Buku dan Jasa Cetak', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:03:56', NULL, NULL),
('39a97228e61647dea9a926fda5ef2976', 'Operator Binding PBM Utara 1', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:25:42', NULL, NULL),
('3c8b59472d4b4d26bad0de7427a5b225', '4 Editor (Kabag)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:05:53', NULL, NULL),
('3caafec399d341489b6192370051d9cc', 'DS Timor Tengah Utara', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:18:25', NULL, NULL),
('3cada79f539f46b9a76334b49d12aebf', 'Marketing AGS', '57a9534bd79d4382bb0f43c89910702c', '57a9534bd79d4382bb0f43c89910702c', NULL, '2022-05-30 21:22:19', '2022-05-30 21:23:40', NULL),
('3dc346ce207145bb9d314d88bd41d8e6', 'Operator Binding Yoshino 1', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:26:15', NULL, NULL),
('3e1e9a528cef4076a07c6bcc9b30b158', '5 Direct Selling (Supervisor)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:10:12', NULL, NULL),
('3e99fc9ae10b4530bd41a62122cb1d27', 'Marketing Percetakan & FO', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:24:27', NULL, NULL),
('3ee9942c26d04ed98985806e884ccac8', '4 Administrasi (Kabag)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:05:22', NULL, NULL),
('40f405c75c904233876aa6b07b6140cb', 'Marketing Indo Pustaka', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:23:54', NULL, NULL),
('413e3c0d577242d3898a011527fdd4b9', '4 Finishing, Bending, Potong, UV + dll, Wrapping (Kabag)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:06:14', NULL, NULL),
('416daa7cd55e42da9b99493ed0c6d18c', 'PIC Cabang', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 19:59:38', NULL, NULL),
('41ed58c1ff634eec81875479ad3eb5e8', 'Operator Mesin Speed Master 52', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:30:12', NULL, NULL),
('426036aaba6d4ebcb7fac38c49e3e626', 'Editor SMK', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:19:01', NULL, NULL),
('43e6121f78dc4ddbbc98011d9eb662a7', '4 MoU (Kabag)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:06:34', NULL, NULL),
('443bf19a191940d095912336c424ddbf', '5 Toko Buku (Supervisor)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:11:43', NULL, NULL),
('45dc34ca1fd044bea7cfba10ec57412f', '4 Personalia & Umum (Kabag)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:06:43', NULL, NULL),
('47e5085e00574169996bf9670627c5e9', 'Marketing PINKA (ANDI - Group)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:24:42', NULL, NULL),
('49df29ed0f5b4f5084536837d086aca2', 'Administrasi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:12:18', NULL, NULL),
('4a3f27b6b37049fd838938428521bb44', 'Administrasi Penjualan & Keuangan', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:13:34', NULL, NULL),
('4cebe6a298b74c11965343d3adffc40d', 'Sekretaris Direktur Operasional', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:49:44', NULL, NULL),
('4d6769b7770940e9b09871285af2de47', 'Andi Media Digital', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:14:25', NULL, NULL),
('4ef7f43c6cd042e89c359486145e4e60', '2 Manager Penjualan & Packing', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:49:46', NULL, NULL),
('4f4c2d915a0f412a98e84be4b9abc3b2', '3 Wakil Manager Stock', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:05:13', NULL, NULL),
('516acf73601146d9bf0d19307be28b70', 'Setter SMK', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:39:19', NULL, NULL),
('516b29cc941f4675af4f25e703a60bfb', 'Operator Doff, UV Kecil', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:26:41', NULL, NULL),
('542c06e42b784cafbae8a1666bae508a', 'Manager Buku Rohani', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:45:11', NULL, NULL),
('56540314fdff4480b481e58e86b97b83', 'DS Manggarai - Flores', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:17:42', NULL, NULL),
('5807a46c6d694c87ad2d0eed18777ce3', 'Operator Spot, UV', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:30:20', NULL, NULL),
('58fc78b637424cb894a238014974a6d8', 'Staff', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-05-30 19:22:45', NULL, NULL),
('5c8a5582335842f59cfadffd32c9058a', 'Direct Selling', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:15:20', NULL, NULL),
('5cde3d235c7c446e9f1f08a4c05eec1e', 'Design', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:14:49', NULL, NULL),
('5d0185e09379474483867aa233e63075', 'Direct Selling SMK & Perguruan Tinggi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:16:06', NULL, NULL),
('5e33e6ce57624fa99fa4458ab4a63e0b', '1 Staff Ahli Direktur Utama', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:46:27', NULL, NULL),
('60f35622aa77414b91ea82869688e5c7', 'Marketing Percetakan', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:24:17', NULL, NULL),
('61dbe8c3b3f145779d24ce736e52a306', '4 Design (Kabag)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:05:32', NULL, NULL),
('620feb2d0dea419f83964e844c7b2003', 'Manager Penerbitan Buku Umum', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:44:31', NULL, NULL),
('629cb23bb1d3405a8132e84f584d82fd', 'Operator Mesin Wrapping Timur', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:29:32', NULL, NULL),
('62a591ac2fef4bb5b081074f78a5669e', 'Non Jabatan', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:39:26', NULL, NULL),
('6330dbf209214e3dbe6b668cb0a68fe3', 'Ekspedisi & Packing', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:19:11', NULL, NULL),
('64551d6ddec04fbd8afb6cfd88e0b0da', 'DS Klaten dan Gunung Kidul', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:17:22', NULL, NULL),
('658d62cb439a4ba6ad2ea4efd7ce4753', 'Ekspedisi Order Cetak', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:19:21', NULL, NULL),
('67d21c0bfa4641f3978ee9534c6bd559', 'Operator Mesin Potong Perfecta/DQ', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:28:52', NULL, NULL),
('6abdb2fe57e9454b8a01b86068ca0d1a', 'Operator Binding PBM Utara 3', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:25:52', NULL, NULL),
('6ad60313ea184223a6c5a63d73be4b34', 'Direct Selling Rohani & Perguruan Tinggi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:15:44', NULL, NULL),
('6ba3a426f8c749239a7d0f9779f85c67', 'Operator Binding PBM Utara 2', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:25:45', NULL, NULL),
('6c0a53168f6943f183c55631c54cdbb2', 'Administrasi Eks Konsinyasi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:12:26', NULL, NULL),
('6c244da58f0c47a083fe5c0444d6b463', 'Direct Selling Tegal', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:16:20', NULL, NULL),
('6f70771b3afa49b790b5b61802a73440', 'Gudang Retur', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:20:19', NULL, NULL),
('7261c5386e554397859da16578262701', '3 Wakil Manager Online', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:05:05', NULL, NULL),
('72b983f419294a6180bab256f90e36f4', 'Administrasi Stock & RO', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:14:08', NULL, NULL),
('73abf833a89344d18df22604b2f1be44', 'Operator Binding Yoshino', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:26:13', NULL, NULL),
('7407ecd4181d4f429d173fbefd7ac938', 'Admin Penjualan', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:12:03', NULL, NULL),
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
('87e16ff18dce411c9c54122ac1275e56', 'Ilustrator', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:20:29', NULL, NULL),
('8837206b558a41bd898310ecb082ab94', 'Operator GTO 1 Warna', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:26:52', NULL, NULL),
('886b050b5cdd4d00bb7f990efbaf6d57', '1 Dewan Direksi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:45:26', NULL, NULL),
('8941d6db9f334699896a802578ac391c', 'DS Minahasa Utara', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:17:50', NULL, NULL),
('8c169ffc6aa6417d85dd8599a122409f', 'Marketing Support (Design)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:24:52', NULL, NULL),
('8d1cece86f1c42d9994ebca7bada307e', 'Operator Binding PBM Utara', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:25:39', NULL, NULL),
('8f11d5f27224472b8632c14136cf02f5', 'Design Graphic', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:15:06', NULL, NULL),
('901a26392eab4dc29cbfe1d14f004555', 'Driver', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:16:54', NULL, NULL),
('93f0483915f2417faf381af531ab21c6', 'Royalti', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:38:21', NULL, NULL),
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
('a8d16dcfad8e4c4cbfb34e7ffc1170b3', 'Accounting', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:11:50', NULL, NULL),
('a9b2128974ac4e8d9a0e67f7a9627fcc', '3 Wakil Manager Accounting', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:04:57', NULL, NULL),
('abbdc0df305a480bb6a517daf00f2f8d', 'Operator Binding Yoshino 2', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:26:19', NULL, NULL),
('ac8bc6e3b1bf44fcbc82644df8ee0019', '1 Wakil Direktur Operasional', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:46:36', NULL, NULL),
('b10bba4235964fa98af7052f7d4b4c36', 'Programmer', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:38:02', NULL, NULL),
('b14c6581b9524832a107ae12a0518ba3', '2 Staf Ahli Penerbitan Umum, Khusus Buku Teks & MoU', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:04:31', NULL, NULL),
('b153d77dac2b41608a8d1f1e17f4b2ff', 'Manager Penerbitan Buku Rohani', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:44:37', NULL, NULL),
('b19fd51ea3544d05b712e1391ba78a38', 'Direct Selling Umum', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:16:26', NULL, NULL),
('b2639536cbb440059ec7a1362d4de099', 'Direct Selling SMK', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:15:57', NULL, NULL),
('b317ce682c1845839a108fbb0eb4aec4', 'Redaktur Renungan', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:38:15', NULL, NULL),
('b5a00780ec4241b0823f05aec55aeb14', 'Personalia', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:30:54', NULL, NULL),
('b779d1a867384744802b88862bd5038c', 'Marketing Koordinator', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:23:59', NULL, NULL),
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
('c2b7e802f16c44da8e84321de0a04230', 'Redaktur Bahana', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:38:10', NULL, NULL),
('c4155b0b5cd64e9a99f8f03f0c9c5420', '4 Digital Media Support (Kabag)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:05:44', NULL, NULL),
('c49a6385fec2465083e17538013356f6', 'Tukang', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:40:03', NULL, NULL),
('c502a4d7ef92459399ef2b4a44dc44ba', 'E-Bahana', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:18:37', NULL, NULL),
('c53fd5b4a80042d7a04a1d3e24c620e7', 'Finishing Buku', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:19:34', NULL, NULL),
('c59878bff90d4b1094bacdef733f5cd3', 'DS Lampung Tengah', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:17:33', NULL, NULL),
('c6ca472a865c4fa18101722b5eced14e', 'Koordinator Teknisi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:21:46', NULL, NULL),
('c8c73c2514154252ba8227eca9b17cc3', '2 Wakil Direktur Penerbitan', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:04:44', NULL, NULL),
('c95e85716f8b4bf8834875d21933e15c', 'Administrasi PBU (ISBN, Duplikasi CD & Ekspedisi)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:12:55', NULL, NULL),
('c9f0106ff972412393227db316d03cb9', 'Prodev', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:37:57', NULL, NULL),
('cb12c05907784373b8fee1128f6a340f', 'Korektor MoU', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:21:57', NULL, NULL),
('cc752eb16ae54eb683f339cb7f55dc93', 'Marketing GME', '57a9534bd79d4382bb0f43c89910702c', '57a9534bd79d4382bb0f43c89910702c', NULL, '2022-05-30 21:22:24', '2022-05-30 21:23:24', NULL),
('ccee3a3ada804ccaa0e9019eb2d010ef', '2 Pjs. Man. HRD', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:04:10', NULL, NULL),
('ce6d7e7b7c004ebaad0da6cc60190ae5', 'Operator CTCP', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:26:32', NULL, NULL),
('cf882df375624a4bbe809e82ef196f57', 'DS Sumba Timur', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:18:13', NULL, NULL),
('d0baf26c251547bb9ae7ee6c8f07071d', '4 Kepala Cabang', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:06:23', NULL, NULL),
('d11c1f0d64034db09972dbf57aa694d5', '2 Manager Area 4', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:48:14', NULL, NULL),
('d275524d48be45e4aae3530770b66587', '2 Manager Stock & Retur', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:00:33', NULL, NULL),
('d3c46b59f3f0474785467019f899a960', 'Keuangan & Sekretaris Pemilik', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:21:33', NULL, NULL),
('d6ef995511b443ad8a9f14943cdbff95', 'Operator Mesin Kecil', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:28:18', NULL, NULL),
('d6fa8bb106b646aa82f39351dcdae39e', 'DS Rantau Prapat', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:17:56', NULL, NULL),
('d7910e457bbd4478b0a67a3d4bc5bfe3', 'Administrasi Siplah', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:14:01', NULL, NULL),
('d82ca3c239504b8e8529679a267736e8', 'Editor MOU', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:18:48', NULL, NULL),
('dbabe9ec1ef6476c842add38f838c56f', 'Operator Hand Press, Voil, Ponds & Emboss', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:28:10', NULL, NULL),
('dc6f2db38b244df7813cd4bc79f3e9dc', '5 Operator Hand Press, Voil, Ponds & Emboss (Koordinator)', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:11:15', NULL, NULL),
('dc8f27ac87ef4feb89bbb6eb13887c4e', 'Korektor SMK', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:22:01', NULL, NULL),
('de6a811cebee4145b0a7bdb223c29c0e', 'Setter', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:39:09', NULL, NULL),
('dedaa2fb2ff14c7a8aac534213902d3b', 'Operator Mesin Speed Master 2 Warna', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:29:45', NULL, NULL),
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
('f340d51d5710489aac530447ec1c1402', 'Direct Selling Umum & Perguruan Tinggi', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:16:36', NULL, NULL),
('f77a260046ae44c9ac5f10912b9b67c7', 'Editor Senior', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:18:56', NULL, NULL),
('f816d48287f0437181a00d1c76e4db99', 'Operator Hand Press & Potong Polar 1998', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:27:56', NULL, NULL),
('f8d18ec158d24f86afdf355bb56a0d67', '2 Manager Online', '57a9534bd79d4382bb0f43c89910702c', '57a9534bd79d4382bb0f43c89910702c', NULL, '2022-05-30 20:48:21', '2022-05-30 20:48:41', NULL),
('fc1f198e5dc9416fa68cb991d422804c', 'Manager Buku Pendidikan', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:22:15', NULL, NULL),
('fc9c94021e754295baef3c8f43e731a9', 'Direct Selling SMK Pati', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:16:11', NULL, NULL),
('fd088b6b60aa4dac9163eb542d43468f', 'Operator GTO 4 Warna', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:27:39', NULL, NULL),
('fe067d18155c4f298f07a409f6aca5a4', '1 Direktur Utama', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 20:46:14', NULL, NULL),
('fee49ef49c5141589a951c0be4a18690', 'Sales Toko Buku Modern', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, '2022-05-30 21:38:44', NULL, NULL);

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
('037ac9e60a4d42dbba06923aa5e810ec', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'a213b689b8274f4dbe19b3fb24d66840', '6318ede7909649eca9394fd844dfdd51', '2022-06-08 06:40:29', NULL),
('04fda3992788442890dcd7a3efc0922e', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'a213b689b8274f4dbe19b3fb24d66840', 'aeb27fc7ab75452a8bfdc5d014b63d83', '2022-06-08 05:00:26', NULL),
('104f3ce789fb43a09afbef30459f8956', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'ebca07da8aad42c4aee304e3a6b81001', '36504ee1023f4bf0be272593c5431669', '2022-06-13 02:58:39', NULL),
('18100e76db8e47398fd7d77c0ae52ab4', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'a213b689b8274f4dbe19b3fb24d66840', '2aea9efddc344fb8b8c03164d4fba843', '2022-06-08 06:43:02', NULL),
('1cc76114dfce4faeb64f2ab675892d0b', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'ebca07da8aad42c4aee304e3a6b81001', '4430fe9a3ae5499c8759d3225f0aa7b5', '2022-06-13 02:59:39', NULL),
('1ea13ec85e31440182c22206ea7944f0', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'a213b689b8274f4dbe19b3fb24d66840', 'fd9c40ad3c62499e9e9b10af7bfae40c', '2022-06-09 03:03:18', NULL),
('1ed1e9041e5448bfb5c5b5e4492852da', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'a213b689b8274f4dbe19b3fb24d66840', '1c95c52a37984a51a2b80874f7e482d5', '2022-06-09 03:40:07', NULL),
('203d3e2563b94cd5aa13291c125021d2', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'a213b689b8274f4dbe19b3fb24d66840', '9c8132c2ebc34a3195c3aaca81095f3c', '2022-06-13 03:01:16', NULL),
('20ffff8bd60f4abf9353223314511dc3', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '12b852d92d284ab5a654c26e8856fffd', '1a58f15bdf304d28a4063808a512cb8b', '2022-06-09 03:42:59', NULL),
('249c8142e2a94aa6acd85c1285da2da1', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'ebca07da8aad42c4aee304e3a6b81001', '1a58f15bdf304d28a4063808a512cb8b', '2022-06-09 03:42:59', NULL),
('25b1f54969dc46c2bf3fef893ba7a8ce', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '12b852d92d284ab5a654c26e8856fffd', '1c95c52a37984a51a2b80874f7e482d5', '2022-06-09 03:40:07', NULL),
('26ed01756da5476cadbf1ab5226c556d', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'ebca07da8aad42c4aee304e3a6b81001', 'aeb27fc7ab75452a8bfdc5d014b63d83', '2022-06-08 05:00:26', NULL),
('28fd42fa67904ef8a1276890d1df69fb', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '12b852d92d284ab5a654c26e8856fffd', '2aea9efddc344fb8b8c03164d4fba843', '2022-06-08 06:43:02', NULL),
('2913115217f44994b0552f67dc9aff48', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '12b852d92d284ab5a654c26e8856fffd', '95d9ebcd691e477eaeaf7c1aba4dd04d', '2022-06-09 01:56:53', NULL),
('2979f39e44a645b4a15ae74f0abd3cbe', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '9beba245308543ce821efe8a3ba965e3', '266da2c74b774d45a6717421208134e1', '2022-09-27 03:49:48', NULL),
('2f967492e4ca4682a897d90aa830fd25', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '12b852d92d284ab5a654c26e8856fffd', '5707a19dd4804b25899e57b37b335ab6', '2022-06-09 01:55:46', NULL),
('3b178a124eec4c29a131575495dc3220', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'ebca07da8aad42c4aee304e3a6b81001', 'fd9c40ad3c62499e9e9b10af7bfae40c', '2022-06-09 03:03:18', '2022-06-30 21:39:22'),
('3c7ee17454374ffa8230fffd71e78c12', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '12b852d92d284ab5a654c26e8856fffd', '4430fe9a3ae5499c8759d3225f0aa7b5', '2022-06-13 02:59:39', NULL),
('473c4d1f4a7c46c595c05b8bd9a69955', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'a213b689b8274f4dbe19b3fb24d66840', '266da2c74b774d45a6717421208134e1', '2022-09-27 03:49:48', NULL),
('476b8f3754314361ae127e5870567de2', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '12b852d92d284ab5a654c26e8856fffd', '36504ee1023f4bf0be272593c5431669', '2022-06-13 02:58:39', NULL),
('4fca757d4735422288cec8233cf2985a', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'ebca07da8aad42c4aee304e3a6b81001', '1c95c52a37984a51a2b80874f7e482d5', '2022-06-09 03:40:07', NULL),
('51c169355b7e425c95cb67ff66f44a46', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'a213b689b8274f4dbe19b3fb24d66840', 'dc29daf30b16470baebd899424abf819', '2022-06-09 01:54:44', NULL),
('525925e75fb14ccda8e561ea19290bb5', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'ebca07da8aad42c4aee304e3a6b81001', '3b6f233968394094976bcfcd116817df', '2022-06-09 03:41:41', NULL),
('59de219e6004410d84123a0823722f69', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'a213b689b8274f4dbe19b3fb24d66840', 'be20459513994ceea0a19f191d947ed0', '2022-06-13 03:00:22', NULL),
('5bd260650a9646a9bc03de3549347810', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'ebca07da8aad42c4aee304e3a6b81001', '2aea9efddc344fb8b8c03164d4fba843', '2022-06-08 06:43:02', NULL),
('6b6fc1dbcf72498c89683fd12a1ac472', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'a213b689b8274f4dbe19b3fb24d66840', '1a58f15bdf304d28a4063808a512cb8b', '2022-06-09 03:42:59', NULL),
('737539a252f640828c8ca1888f434b14', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '12b852d92d284ab5a654c26e8856fffd', '6318ede7909649eca9394fd844dfdd51', '2022-06-08 06:40:29', NULL),
('7e5b44dbb2ce4c888ece623a0bfdc5dc', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'a213b689b8274f4dbe19b3fb24d66840', '4430fe9a3ae5499c8759d3225f0aa7b5', '2022-06-13 02:59:39', NULL),
('819fc953fe8e4c42926dbb0272fbef1c', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'ebca07da8aad42c4aee304e3a6b81001', '266da2c74b774d45a6717421208134e1', '2022-09-27 03:49:48', NULL),
('880c968752e74d63b6c6b250b8973d14', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'a213b689b8274f4dbe19b3fb24d66840', '5707a19dd4804b25899e57b37b335ab6', '2022-06-09 01:55:46', NULL),
('8ae9d60c987a4712b89aa376d72b2f55', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '12b852d92d284ab5a654c26e8856fffd', 'f56c0df2ccd24207b5a18e660bcf6f1a', '2022-06-09 01:57:38', NULL),
('8b8791f8fcb84eb98d94a1115c9057fa', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'a213b689b8274f4dbe19b3fb24d66840', 'e75dd02c02154c44b142bfa7bb92049c', '2022-06-13 02:57:14', NULL),
('8bb7e7e9a936411d8354e6f0365d7b01', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '12b852d92d284ab5a654c26e8856fffd', 'e75dd02c02154c44b142bfa7bb92049c', '2022-06-13 02:57:14', NULL),
('a23cc918c4e54378a339cf7768973e20', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '12b852d92d284ab5a654c26e8856fffd', 'fd9c40ad3c62499e9e9b10af7bfae40c', '2022-06-09 03:03:18', NULL),
('a74f4dc370a64f249fe00246c5a3226f', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '12b852d92d284ab5a654c26e8856fffd', '3b6f233968394094976bcfcd116817df', '2022-06-09 03:41:41', NULL),
('a9095612217d4bb8b6ef247c767a2081', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'ebca07da8aad42c4aee304e3a6b81001', '6318ede7909649eca9394fd844dfdd51', '2022-06-08 06:40:29', NULL),
('b0763c5d31fc43de972494379cc358f5', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'a213b689b8274f4dbe19b3fb24d66840', '36504ee1023f4bf0be272593c5431669', '2022-06-13 02:58:39', NULL),
('b506f3ce50dd44069fa4939a4c2c23e0', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '12b852d92d284ab5a654c26e8856fffd', '266da2c74b774d45a6717421208134e1', '2022-09-27 03:49:48', NULL),
('bdc316e371044c13bf7e5c5a50764dbc', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'ebca07da8aad42c4aee304e3a6b81001', '5707a19dd4804b25899e57b37b335ab6', '2022-06-09 01:55:46', '2022-07-29 01:34:17'),
('c10b1e8d72244a6cad6d2dabb73a7036', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'a213b689b8274f4dbe19b3fb24d66840', 'f56c0df2ccd24207b5a18e660bcf6f1a', '2022-06-09 01:57:38', NULL),
('c68a01a629c445bfbff47027d92bc3e1', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'a213b689b8274f4dbe19b3fb24d66840', '3b6f233968394094976bcfcd116817df', '2022-06-09 03:41:41', NULL),
('c6cd444ea3a34e97b94b8c0a31d755ae', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'ebca07da8aad42c4aee304e3a6b81001', 'dc29daf30b16470baebd899424abf819', '2022-06-09 01:54:44', '2022-06-09 01:39:32'),
('cde4cf49bcef4d4798c37477d60a7ae4', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'ebca07da8aad42c4aee304e3a6b81001', '9c8132c2ebc34a3195c3aaca81095f3c', '2022-06-13 03:01:16', NULL),
('d1d2d5e7aef54ad0bd612891c348e018', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'ebca07da8aad42c4aee304e3a6b81001', 'f56c0df2ccd24207b5a18e660bcf6f1a', '2022-06-09 01:57:38', '2022-07-29 01:55:04'),
('dbf0796782134157b4d2be53f37a850d', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'ebca07da8aad42c4aee304e3a6b81001', 'e75dd02c02154c44b142bfa7bb92049c', '2022-06-13 02:57:14', NULL),
('e854092ad8fc40bc862578351ff1e173', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '12b852d92d284ab5a654c26e8856fffd', 'dc29daf30b16470baebd899424abf819', '2022-06-09 01:54:44', NULL),
('ea033967817949ca960dba5c8d926219', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'ebca07da8aad42c4aee304e3a6b81001', '95d9ebcd691e477eaeaf7c1aba4dd04d', '2022-06-09 01:56:53', '2022-07-29 02:21:06'),
('ef670d07ad5b4be0b39b6663dadc6766', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '12b852d92d284ab5a654c26e8856fffd', '9c8132c2ebc34a3195c3aaca81095f3c', '2022-06-13 03:01:16', NULL),
('f2de3c83d838438099cd85a9754669f9', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'a213b689b8274f4dbe19b3fb24d66840', '95d9ebcd691e477eaeaf7c1aba4dd04d', '2022-06-09 01:56:53', NULL),
('f4b7926b3c0e4d5bba36e1f42461aa8c', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '12b852d92d284ab5a654c26e8856fffd', 'aeb27fc7ab75452a8bfdc5d014b63d83', '2022-06-08 05:00:26', NULL),
('fc7153b286a84d7097e9efc3ff07745a', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, '12b852d92d284ab5a654c26e8856fffd', 'be20459513994ceea0a19f191d947ed0', '2022-06-13 03:00:22', NULL),
('fe41c05713734a3aa6e7819d078cd93b', 'Penerbitan', 'Penilaian Naskah', NULL, NULL, 'ebca07da8aad42c4aee304e3a6b81001', 'be20459513994ceea0a19f191d947ed0', '2022-06-13 03:00:22', NULL);

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
('26ed01756da5476cadbf1ab5226c556d', 'a400e4bcf70d40d78224043cc95e6241', '1', NULL, '2022-06-08 05:00:26', '2022-06-30 23:20:44'),
('a9095612217d4bb8b6ef247c767a2081', 'a400e4bcf70d40d78224043cc95e6241', '0', NULL, '2022-06-08 06:40:29', NULL),
('5bd260650a9646a9bc03de3549347810', 'a400e4bcf70d40d78224043cc95e6241', '0', NULL, '2022-06-08 06:43:02', NULL),
('c6cd444ea3a34e97b94b8c0a31d755ae', '072df3b932394d6caacb5c9c0960d42b', '1', NULL, '2022-06-09 01:54:44', '2022-06-09 01:39:32'),
('bdc316e371044c13bf7e5c5a50764dbc', '072df3b932394d6caacb5c9c0960d42b', '1', NULL, '2022-06-09 01:55:46', '2022-07-29 01:34:17'),
('ea033967817949ca960dba5c8d926219', '072df3b932394d6caacb5c9c0960d42b', '1', NULL, '2022-06-09 01:56:53', '2022-07-29 02:21:06'),
('d1d2d5e7aef54ad0bd612891c348e018', '072df3b932394d6caacb5c9c0960d42b', '1', NULL, '2022-06-09 01:57:38', '2022-07-29 01:55:04'),
('3b178a124eec4c29a131575495dc3220', 'a400e4bcf70d40d78224043cc95e6241', '1', NULL, '2022-06-09 03:03:18', '2022-06-30 21:39:22'),
('4fca757d4735422288cec8233cf2985a', '072df3b932394d6caacb5c9c0960d42b', '0', NULL, '2022-06-09 03:40:07', NULL),
('525925e75fb14ccda8e561ea19290bb5', '072df3b932394d6caacb5c9c0960d42b', '0', NULL, '2022-06-09 03:41:41', NULL),
('249c8142e2a94aa6acd85c1285da2da1', '072df3b932394d6caacb5c9c0960d42b', '0', NULL, '2022-06-09 03:42:59', NULL),
('dbf0796782134157b4d2be53f37a850d', '072df3b932394d6caacb5c9c0960d42b', '0', NULL, '2022-06-13 02:57:14', NULL),
('104f3ce789fb43a09afbef30459f8956', '072df3b932394d6caacb5c9c0960d42b', '0', NULL, '2022-06-13 02:58:39', NULL),
('1cc76114dfce4faeb64f2ab675892d0b', '072df3b932394d6caacb5c9c0960d42b', '0', NULL, '2022-06-13 02:59:39', NULL),
('fe41c05713734a3aa6e7819d078cd93b', '072df3b932394d6caacb5c9c0960d42b', '0', NULL, '2022-06-13 03:00:22', NULL),
('cde4cf49bcef4d4798c37477d60a7ae4', '072df3b932394d6caacb5c9c0960d42b', '0', NULL, '2022-06-13 03:01:16', NULL),
('26ed01756da5476cadbf1ab5226c556d', 'f88116e1873c4403a7fb76a08f5266cb', '1', NULL, '2022-06-23 04:12:52', '2022-06-30 23:20:44'),
('26ed01756da5476cadbf1ab5226c556d', 'a400e4bcf70d40d78224043cc95e6241', '1', NULL, '2022-07-01 05:59:53', '2022-06-30 23:20:44'),
('26ed01756da5476cadbf1ab5226c556d', 'f88116e1873c4403a7fb76a08f5266cb', '0', NULL, '2022-07-01 06:20:44', NULL),
('819fc953fe8e4c42926dbb0272fbef1c', 'e83ca4537495486c8d3b5d7e6ae2407a', '0', NULL, '2022-09-27 03:49:48', NULL);

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
  `judul_asli` varchar(255) NOT NULL,
  `tanggal_masuk_naskah` date DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `kelompok_buku_id` varchar(36) DEFAULT NULL,
  `jalur_buku` enum('Reguler','MoU','MoU-Reguler','SMK/NonSMK','Pro Literasi') DEFAULT NULL,
  `tentang_penulis` enum('1','0') NOT NULL DEFAULT '0' COMMENT '1:Ya|0:Tidak',
  `url_file` text DEFAULT NULL,
  `hard_copy` enum('1','0') NOT NULL DEFAULT '0' COMMENT '1:Ya|0:Tidak',
  `soft_copy` enum('1','0') NOT NULL DEFAULT '0' COMMENT '1:Ya|0:Tidak',
  `cdqr_code` enum('1','0') NOT NULL DEFAULT '0' COMMENT '1:Ya|0:Tidak',
  `keterangan` text DEFAULT NULL,
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
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penerbitan_naskah`
--

INSERT INTO `penerbitan_naskah` (`id`, `kode`, `judul_asli`, `tanggal_masuk_naskah`, `email`, `kelompok_buku_id`, `jalur_buku`, `tentang_penulis`, `url_file`, `hard_copy`, `soft_copy`, `cdqr_code`, `keterangan`, `pic_prodev`, `penilaian_naskah`, `date_pic_prodev`, `penilaian_prodev`, `penilaian_editset`, `penilaian_pemasaran`, `penilaian_penerbitan`, `penilaian_direksi`, `selesai_penilaian`, `selesai_penilaian_tgl`, `bukti_email_penulis`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
('16fdb6f329c544c6824cf85ec38501b7', 'NA20220914017', 'Di balik mata kaca', '2022-09-14', 'ferdylucker@gmail.com', '19232a62008d40e89b45572ab768634b', 'SMK/NonSMK', '1', 'https://www.google.com', '1', '1', '1', NULL, '77e9d0dacb154a37b5730efb8d36ad29', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-14 12:00:42', 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-09-14 02:58:18', '2022-10-14 07:56:10', NULL),
('1a58f15bdf304d28a4063808a512cb8b', 'NA20220609011', 'Seri Profesi - Arsitek', '2022-06-09', ' w04.memory@gmail.com', '488697c7556a413eb433806b35bb635f', 'Reguler', '1', NULL, '0', '1', '0', NULL, '072df3b932394d6caacb5c9c0960d42b', '1', '2022-06-08 20:42:59', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-09 03:42:59', NULL, NULL),
('1c95c52a37984a51a2b80874f7e482d5', 'NA20220609009', 'Seri Profesi - Desainer Baju', '2022-06-09', ' w04.memory@gmail.com', '488697c7556a413eb433806b35bb635f', 'Reguler', '1', NULL, '0', '1', '0', NULL, '072df3b932394d6caacb5c9c0960d42b', '1', '2022-06-08 20:40:07', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-09 03:40:07', NULL, NULL),
('266da2c74b774d45a6717421208134e1', 'NA20220927018', 'Ada Dia Dan Aku', '2022-09-22', 'fdasdasda@gmail.com', '24b12e3f73f84975b33ed2f8202b40ca', 'Reguler', '0', 'https://www.w3schools.com/tags/att_input_type_url.asp', '1', '1', '1', NULL, 'e83ca4537495486c8d3b5d7e6ae2407a', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-09-27 03:49:48', '2022-10-16 13:25:48', NULL),
('2aea9efddc344fb8b8c03164d4fba843', 'NA20220608003', 'Bahan Ajar Etika Profesi', '2022-06-08', 'sendi.novianto@dsn.dinus.ac.id', '1598193526f34779babe8c6746fc73d9', 'MoU-Reguler', '0', NULL, '0', '1', '0', NULL, 'a400e4bcf70d40d78224043cc95e6241', '1', '2022-06-07 23:43:02', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-08 06:43:02', NULL, NULL),
('36504ee1023f4bf0be272593c5431669', 'NA20220613013', 'Seri Rawat dan Sayangi Aku - Kweni Berbuah', '2022-06-09', 'fajriatun_nur@yahoo.co.id', '488697c7556a413eb433806b35bb635f', 'Reguler', '1', NULL, '0', '1', '0', NULL, '072df3b932394d6caacb5c9c0960d42b', '1', '2022-06-12 19:58:39', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-13 02:58:39', NULL, NULL),
('3b6f233968394094976bcfcd116817df', 'NA20220609010', 'Seri Profesi - Juru Masak', '2022-06-09', ' w04.memory@gmail.com', '488697c7556a413eb433806b35bb635f', 'Reguler', '1', NULL, '0', '1', '0', NULL, '072df3b932394d6caacb5c9c0960d42b', '1', '2022-06-08 20:41:41', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-09 03:41:41', NULL, NULL),
('4430fe9a3ae5499c8759d3225f0aa7b5', 'NA20220613014', 'Seri Rawat dan Sayangi Aku - Linci Ingin Disayang', '2022-06-09', 'fajriatun_nur@yahoo.co.id', '488697c7556a413eb433806b35bb635f', 'Reguler', '1', NULL, '0', '1', '0', NULL, '072df3b932394d6caacb5c9c0960d42b', '1', '2022-06-12 19:59:39', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-13 02:59:39', NULL, NULL),
('5707a19dd4804b25899e57b37b335ab6', 'NA20220609005', 'Seri Keselamatan di Tempat Umum - Saat di Kebun Binatang', '2022-06-08', 'fajriatun_nur@yahoo.co.id', '488697c7556a413eb433806b35bb635f', 'Reguler', '1', NULL, '0', '1', '0', NULL, '072df3b932394d6caacb5c9c0960d42b', '1', '2022-06-08 18:55:46', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-09 01:55:46', NULL, NULL),
('6318ede7909649eca9394fd844dfdd51', 'NA20220608002', 'Bahan Ajar Ketrampilan Interpersonal', '2022-06-08', 'sendi.novianto@dsn.dinus.ac.id', '1598193526f34779babe8c6746fc73d9', 'MoU-Reguler', '0', NULL, '0', '1', '0', NULL, 'a400e4bcf70d40d78224043cc95e6241', '1', '2022-06-07 23:40:29', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-08 06:40:29', NULL, NULL),
('95d9ebcd691e477eaeaf7c1aba4dd04d', 'NA20220609006', 'Seri Keselamatan di Tempat Umum - Saat di Angkutan Umum', '2022-06-08', 'fajriatun_nur@yahoo.co.id', '488697c7556a413eb433806b35bb635f', 'Reguler', '1', NULL, '0', '1', '0', NULL, '072df3b932394d6caacb5c9c0960d42b', '1', '2022-06-08 18:56:53', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-09 01:56:53', NULL, NULL),
('9c8132c2ebc34a3195c3aaca81095f3c', 'NA20220613016', 'Seri Rawat dan Sayangi Aku - Rumah Baru Moci', '2022-06-09', 'fajriatun_nur@yahoo.co.id', '488697c7556a413eb433806b35bb635f', 'Reguler', '1', NULL, '0', '1', '0', NULL, '072df3b932394d6caacb5c9c0960d42b', '1', '2022-06-12 20:01:16', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-13 03:01:16', NULL, NULL),
('aeb27fc7ab75452a8bfdc5d014b63d83', 'NA20220608001', 'Rahasia raja uang', '2022-06-30', 'jonar.situmorang@gmail.com', 'bccd38a17ea04ad695d7054e18306bdc', 'MoU-Reguler', '1', NULL, '0', '1', '0', NULL, 'f88116e1873c4403a7fb76a08f5266cb', '1', '2022-06-30 23:20:44', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', '205cbcb8d74646f7bfafd9b4972f04ac', NULL, '2022-06-08 05:00:26', '2022-06-30 23:20:44', NULL),
('be20459513994ceea0a19f191d947ed0', 'NA20220613015', 'Seri Rawat dan Sayangi Aku - Yamyam dan Keluarga Barunya', '2022-06-09', 'fajriatun_nur@yahoo.co.id', '488697c7556a413eb433806b35bb635f', 'Reguler', '1', NULL, '0', '1', '0', NULL, '072df3b932394d6caacb5c9c0960d42b', '1', '2022-06-12 20:00:22', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-13 03:00:22', NULL, NULL),
('dc29daf30b16470baebd899424abf819', 'NA20220609004', 'Seri Keselamatan di Tempat Umum - Saat di Mal', '2022-06-08', 'fajriatun_nur@yahoo.co.id', '488697c7556a413eb433806b35bb635f', 'Reguler', '1', NULL, '0', '1', '0', NULL, '072df3b932394d6caacb5c9c0960d42b', '1', '2022-06-08 18:54:44', '1', NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-09 01:54:44', NULL, NULL),
('e75dd02c02154c44b142bfa7bb92049c', 'NA20220613012', 'Seri Rawat dan Sayangi Aku - Jangan Lukai Aku', '2022-06-09', 'fajriatun_nur@yahoo.co.id', '488697c7556a413eb433806b35bb635f', 'Reguler', '1', NULL, '0', '1', '0', NULL, '072df3b932394d6caacb5c9c0960d42b', '1', '2022-06-12 19:57:14', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-13 02:57:14', NULL, NULL),
('f56c0df2ccd24207b5a18e660bcf6f1a', 'NA20220609007', 'Seri Keselamatan di Tempat Umum - Saat di Kolam Renang', '2022-06-08', 'fajriatun_nur@yahoo.co.id', '488697c7556a413eb433806b35bb635f', 'Reguler', '1', NULL, '0', '1', '0', NULL, '072df3b932394d6caacb5c9c0960d42b', '1', '2022-06-08 18:57:38', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-09 01:57:38', NULL, NULL),
('fd9c40ad3c62499e9e9b10af7bfae40c', 'NA20220609008', 'Manajemen Keuangan', '2022-06-09', 'fandy.tjiptono@vuw.ac.nz', '1598193526f34779babe8c6746fc73d9', 'Reguler', '0', NULL, '0', '1', '0', NULL, 'a400e4bcf70d40d78224043cc95e6241', '1', '2022-06-08 20:03:18', '1', NULL, NULL, NULL, NULL, '0', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-09 03:03:18', NULL, NULL);

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
  `email_his` varchar(100) DEFAULT NULL,
  `email_new` varchar(100) DEFAULT NULL,
  `kelompok_buku_his` varchar(36) DEFAULT NULL,
  `kelompok_buku_new` varchar(36) DEFAULT NULL,
  `tgl_masuk_nas_his` date DEFAULT NULL,
  `tgl_masuk_nas_new` date DEFAULT NULL,
  `tentang_penulis_his` tinyint(4) DEFAULT NULL COMMENT '0/1',
  `tentang_penulis_new` tinyint(4) DEFAULT NULL COMMENT '0/1',
  `hard_copy_his` tinyint(4) DEFAULT NULL COMMENT '0/1',
  `hard_copy_new` tinyint(4) DEFAULT NULL COMMENT '0/1',
  `soft_copy_his` tinyint(4) DEFAULT NULL COMMENT '0/1',
  `soft_copy_new` tinyint(4) DEFAULT NULL COMMENT '0/1',
  `cdqr_code_his` tinyint(4) DEFAULT NULL COMMENT '0/1',
  `cdqr_code_new` tinyint(4) DEFAULT NULL COMMENT '0/1',
  `pic_prodev_his` varchar(36) DEFAULT NULL,
  `pic_prodev_new` varchar(36) DEFAULT NULL,
  `penulis_his` longtext DEFAULT NULL,
  `penulis_new` longtext DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `author_id` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `penerbitan_naskah_history`
--

INSERT INTO `penerbitan_naskah_history` (`id`, `naskah_id`, `type_history`, `judul_asli_his`, `judul_asli_new`, `email_his`, `email_new`, `kelompok_buku_his`, `kelompok_buku_new`, `tgl_masuk_nas_his`, `tgl_masuk_nas_new`, `tentang_penulis_his`, `tentang_penulis_new`, `hard_copy_his`, `hard_copy_new`, `soft_copy_his`, `soft_copy_new`, `cdqr_code_his`, `cdqr_code_new`, `pic_prodev_his`, `pic_prodev_new`, `penulis_his`, `penulis_new`, `modified_at`, `author_id`) VALUES
(1, '266da2c74b774d45a6717421208134e1', 'Update', 'Ada Dia', 'Ada Dia Dan Aku', 'fdasdasda@gmail.com', 'fdasdasda@gmail.com', '24b12e3f73f84975b33ed2f8202b40ca', '24b12e3f73f84975b33ed2f8202b40ca', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[{\"naskah_id\":\"266da2c74b774d45a6717421208134e1\",\"penulis_id\":\"353a88a472d5438dacf5e7fb7d6271e3\"},{\"naskah_id\":\"266da2c74b774d45a6717421208134e1\",\"penulis_id\":\"90d2a75954c0442bb9f6b2578e83fc8b\"}]', '2022-10-16 19:44:33', 'be8d42fa88a14406ac201974963d9c1b'),
(2, '266da2c74b774d45a6717421208134e1', 'Update', NULL, NULL, NULL, NULL, '24b12e3f73f84975b33ed2f8202b40ca', '24b12e3f73f84975b33ed2f8202b40ca', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'e83ca4537495486c8d3b5d7e6ae2407a', 'e83ca4537495486c8d3b5d7e6ae2407a', NULL, '[{\"naskah_id\":\"266da2c74b774d45a6717421208134e1\",\"penulis_id\":\"353a88a472d5438dacf5e7fb7d6271e3\"}]', '2022-10-16 20:11:17', 'be8d42fa88a14406ac201974963d9c1b'),
(3, '266da2c74b774d45a6717421208134e1', 'Update', NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-23', '2022-09-22', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '[{\"naskah_id\":\"266da2c74b774d45a6717421208134e1\",\"penulis_id\":\"353a88a472d5438dacf5e7fb7d6271e3\"},{\"naskah_id\":\"266da2c74b774d45a6717421208134e1\",\"penulis_id\":\"90d2a75954c0442bb9f6b2578e83fc8b\"}]', '2022-10-16 20:25:48', 'be8d42fa88a14406ac201974963d9c1b');

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
('74deca8d129042a9aa23c82aceee89f5', '16fdb6f329c544c6824cf85ec38501b7'),
('353a88a472d5438dacf5e7fb7d6271e3', '266da2c74b774d45a6717421208134e1'),
('90d2a75954c0442bb9f6b2578e83fc8b', '266da2c74b774d45a6717421208134e1');

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
('2fb3089865b6476399a3e0c611f17427', 'DR. Pulung Nurtantio Andono S.T, M.Kom ; Sri Winarno, Ph.D ; Indra Gamaynto, ST., MITM ; Dr.Sendi Novianto S.Kom, M.T', '2022-06-30', 'yogyakarta', 'WNI', NULL, NULL, NULL, 'sendi.novianto@dsn.dinus.ac.id', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-08 06:11:36', NULL, NULL),
('353a88a472d5438dacf5e7fb7d6271e3', 'Yohanes Hendra', '1994-03-01', 'Yogyakarta', 'WNI', NULL, NULL, NULL, 'hendra@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default.jpg', NULL, NULL, NULL, NULL, NULL, 'c1E1DP4PJ9US7FHzrwApeDJIac7EDK5aWWfvh4zN.png', NULL, 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-05-30 03:50:38', '2022-05-30 04:18:29', NULL),
('3e7189290fc144998971d02e8ca43641', 'Dr.Azrul, M.Pd.', '1985-12-01', 'Batu Basa', 'WNI', 'Perumahan Lubuk Sejahtera Lestari, Blok.Garniti No.8, Lubuk Buaya, Kota Padang', '081363342109', NULL, 'azruk@uinin.ac.id', 'Fakultas Tarbiyah dan Kegirian - UIN Imam Bonjol Padang', 'Dosen', 'Jl.Prof.Dr.M.Yunus, Kota Padang, Prov.Sumatera Barat', '0751-35711', NULL, NULL, NULL, NULL, NULL, 'default.jpg', 'BNI', 'Azrul,', '126901001410536', '41.237.242.7-201.000', '1305090108850001', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-10 02:26:33', NULL, NULL),
('4f09b39879d4420ea0d40bdf31b3f13a', 'Fajriatun Nurhidayati', '2022-06-08', 'Yogyakarta', 'WNI', 'RT.06/1, Karangsalam, Kec.Susukan, Kab.Banjarnegara, Jawa Tengah 53475', '0822 1386 0541', NULL, 'fajriatun_nur@yahoo.co.id', NULL, NULL, NULL, NULL, 'fajriatun Nur', '@d_fajria', '@fajriatunNur1', NULL, '15kLHw3C7kkkePHJdTo44ItKlwl664td1riPm801.pdf', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-09 01:52:08', NULL, NULL),
('74deca8d129042a9aa23c82aceee89f5', 'Nidhom Khoeron', '1992-12-31', 'Cirebon', 'WNI', 'Desa.Waringin, Kec.Ciwaringin, Kab.Cirebon 45167', '0838 2311 9266', NULL, ' w04.memory@gmail.com', NULL, 'Pengajar Private, Penulis Buku', NULL, NULL, 'Nidhom Khoeron', '@nidhom.kho', 'Nidh Notes', NULL, 'QsMeKhRy8T5YqS3GFCsFR04JTsLmpA9RFLTwb5kZ.pdf', 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-06-09 03:35:14', '2022-10-14 07:52:42', NULL),
('785df60d4d834d1f8adfeb375e859360', 'Dr. Ulfia Rahmi, M.Pd.', '1987-05-24', 'Batuhampa', 'WNI', 'Perumahan Lubuk Sejahtera Lestari, Blok.Garniti No.8, Lubuk Buaya, Kota.Padang', '081363392202', NULL, 'ulfia@fip.unp.ac.id', 'Fakultas Ilmu Pendidikan - Universitas Negeri Padang', 'Dosen', 'Fakultas Ilmu Pendidikan - Universitas Negeri Padang : Jl.Prof.Dr.Hamka, Kota Padang, Prov.Sumatera Barat.', '0751-7058698', NULL, NULL, NULL, NULL, NULL, 'default.jpg', 'BNI', 'Ulfia Rahmi', '0573291905', '70.926.002.0-201.000', '1307136405870003', NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-10 02:21:51', NULL, NULL),
('90d2a75954c0442bb9f6b2578e83fc8b', 'Lorem Ipsum', '1985-03-15', 'Surabaya', 'WNI', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry', '085743172451', '0274551155', 'lorem@gamil.com', 'Andi Offset', 'Staff', 'Jl Beo, Condong catur. Sleman', '0274558787', 'Lorem Ipsum', '@loremipsum', '@loremipsum', '<div style=\"text-align: justify;\">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</div>', 'JAQ4X0wxDEegR06P3c7tJPPWZeB4Wmgs0mGD3Ggu.pdf', 'r6OlOVcNRmaPSGSOtHyEhU3VhVEoanXtxfKWANdJ.jpg', 'CDB', 'Lorem Ipsum', '88123321', '4777123890', '3471031704900005', 'knov8tsqXdnirPjRblMapTU8FQqOtzNao89v5sEa.png', 'ISxxQm249EIWbeOdGBdFLG6KnpyF9GD3t0lA4VL4.png', 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-03-18 06:26:59', '2022-05-30 04:22:48', NULL),
('986a4833c1c2406193299452bf7c3e91', 'Test Image', '2022-06-02', 'Yogyakarta', 'WNI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'p6TErMkr13ZbR3Q0gexBtDmUDg25nZgHpYukXAwJ.jpg', NULL, NULL, NULL, NULL, NULL, 'OepSJwJAbAUCrXbyTShowYtgAEfaLLNXWEs5D0EZ.jpg', 'ntf0eB938LLZvnPnp50wqtuyuLTKxpDSSCxtEtsN.jpg', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-06-03 06:42:21', NULL, NULL),
('b66053c746b04329836d4f121ab7c06f', 'Mandriwati, G.A.', '1992-12-31', 'Yogyakarta', 'WNI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-09 04:14:35', NULL, NULL),
('d0789ba314354de4926b549233008ef6', 'JK Rowling Door', '1994-03-02', 'Inggris', 'WNA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<p><strong style=\"margin: 0px; padding: 0px; font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">Lorem Ipsum</strong><span style=\"font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span><br></p>', NULL, 'PXzUEK5uHNaUSQxu842S09ZL0Neub4XlLRTuh8v6.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-05-30 02:09:52', NULL, NULL),
('db307ea965334f6f8284e46d820dd5a1', 'Diana ; Fandy Tjiptono', '2022-06-30', 'Yogyakarta', 'WNI', NULL, NULL, NULL, 'fandy.tjiptono@vuw.ac.nz', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-09 02:47:32', NULL, NULL),
('e42ff33b548b43538f610935d9dde775', 'Ridi Ferdiana', '1983-10-20', 'Cirebon', 'WNI', 'Jl.Kaliurang Km.7, Gg.Melati 2 No.48, Babadan Baru, Sleman, Yogyakarta', '0856 280 3939', NULL, 'ridi@ugm.ac.id', 'Fakultas Teknik Universitas Gadjah Mada', NULL, 'Jl.Grafika No.2, DTETI UGM', NULL, NULL, NULL, NULL, NULL, '4Yn7UdGZe4ixaJ4cEQRtaAqzOo4xEa3So8A10ldJ.pdf', 'default.jpg', 'BCA', 'Ridi Ferdiana', '0372408365', '67 476 484 0 542 000', '3404072010830016', '5kSoFAjckAHUMILnyRZrDb0m7rFk3DgJo7XCWMec.jpg', 'kKQeN4ilB7MR52G3PfLEiFTQ4wlMZhf7IqnhVaK6.jpg', '5537f8f560a549e88d7a443b801bb1af', NULL, NULL, '2022-06-08 04:53:33', NULL, NULL);

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
('pilar', NULL, 'BUKU PENGGERAK'),
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
('22aa6275370245a0a3972119bd2da125', '5707a19dd4804b25899e57b37b335ab6', 'Baik', 'Baik', 'c16a55c4b1c54e12b3c79d292a340f84', 'Naskah seri Keselamatan di tempat umum ini terdiri dari 4 cerita, yaitu saat di Mal, di kebun binatang, di angkutan umum dan di kolam renang. Tema cukup menarik dan yang jelas dibutuhkan untuk anak-anak maupun orang tua. Yang perlu diperhatikan dalam pembahasannya (oleh penulis) adalah untuk mengecek penatalaksanaan penanganan jika ada darurat di tempat-tempat umum tersebut. Misalnya dengan melakukan survei atau riset kecil-kecilan di tempat-tempat yang menjadi background dari masing-masing cerita ini. Yaitu di mall, kebun binatang, angkutan umum maupun di kolam renang. Kali ini topik khusus keselamatan umum yang diangkat adalah SAAT DI KEBUN BINATANG, bercerita tentang Vino dan teman-teman sekelasnya yang bersama-sama sedang melakukan fieldtrip ke Kebun Binatang. Mereka diajak untuk menjaga kebersihan, tidak memberi makan sembarangan, tidak terlalu dekat dengan kandang hewan, dan menaati peraturan di kebun binatang tersebut, Hal yang masih belum ada dalam pembahasan disini adalah, untuk tidak menggoda satwa yang sedang tidur dan tidak bersikap apatis. Hal-hal tersebut mungkin perlu ditambahkan baik dalam alur cerita ataupun dalam Tips Aman Di Kebun Binatang, yang diberikan di akhir cerita.  Perlu dicek juga untuk ilustrasi dari masing-masing judul ini. Topik khusus di Kebun Binatang ini dalam seri ini cukup menarik. dibutuhkan dan cukup menjual, karena destinasi wisata untuk anak-anak yan paling sering dikunjungi salah satunya adalah Kebun Binatang.', 'anak usia 7+, para pendamping dan pendidik anak-anak, orang tua yang mempunyai anak balita dan masyarakat pada umumnya', 'toko buku, proyek dan sekolah', NULL, 'Baik', 'Revisi', 'Sangat baik', NULL, '072df3b932394d6caacb5c9c0960d42b', NULL, '2022-07-29 08:34:17', NULL),
('28cdc37c9cec4db08204f46bf39ce2b8', '1a71b26e23094c39a62f6c8d62ec6665', 'Baik', 'Cukup', '0d6b22630e41467a85f2764630b81033', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing', 'Baik', 'Diterima', 'enim ipsam voluptatem quia voluptas sit', NULL, 'e4ddf4d7c2b84cb69647f4dd63f9dbc2', NULL, '2022-07-22 03:13:39', NULL),
('6749ecec8cd4482ea15f2ce4a6c84451', 'fd9c40ad3c62499e9e9b10af7bfae40c', 'Baik', 'Baik', '3df79091d3814110917e84fb7df08227', 'Buku “Manajemen Keuangan” ini mengulas teori, konsep, dan aplikasi pokok pengelolaan keuangan  dalam konteks perusahaan. Pembahasan disampaikan dalam bahasa yang mudah dipahami dan disertai dengan contoh praktis. Struktur bab disusun secara sistematis ke dalam 5 bagian: (1) Konsep Dasar Manajemen Keuangan (apa dan bagaimana manajemen keuangan, pasar keuangan dan lembaga jasa keuangan, memahami laporan keuangan, menganalisis laporan keuangan, serta konsep nilai waktu uang); (2) Penilaian Sekuritas (risiko dan return, penilaian obligasi, dan penilaian saham); (3) Penilaian Investasi Jangka Panjang (biaya modal, capital budgeting, dan estimasi arus dan analisis risiko); (4) Keputusan Keuangan Strategik (struktur modal dan kebijakan dividen dan pembelian kembali saham); dan (5) Perencanaan Keuangan dan Modal Kerja (perencanaan keuangan dan manajemen modal kerja). Selain cocok diadopsi sebagai buku pegangan kuliah Manajemen Keuangan, buku ini juga dapat dijadikan panduan praktis bagi para praktisi maupun pemerhati keuangan untuk mempelajari prinsip dasar manajemen keuangan.', 'mata kuliah manajemen keuangan', 'mahasiswa ekonomi manajemen', 'mahasiswa ekonomi manajemen', 'Baik', 'Diterima', 'mahasiswa manajemen dan konsultan keuangan', '[{\"judul\":\"Manajemen Keuangan\"}]', 'a400e4bcf70d40d78224043cc95e6241', 'a400e4bcf70d40d78224043cc95e6241', '2022-06-30 21:39:22', '2022-06-30 21:40:00'),
('6dc6cbeb8a584b3f9f5e5ce698384783', 'f56c0df2ccd24207b5a18e660bcf6f1a', 'Baik', 'Baik', 'c16a55c4b1c54e12b3c79d292a340f84', 'Naskah seri Keselamatan di tempat umum ini terdiri dari 4 cerita, yaitu saat di Mal, di kebun binatang, di angkutan umum dan di kolam renang. Tema cukup menarik dan yang jelas dibutuhkan untuk anak-anak maupun orang tua. Yang perlu diperhatikan dalam pembahasannya (oleh penulis) adalah untuk mengecek penatalaksanaan penanganan jika ada darurat di tempat-tempat umum tersebut. Misalnya dengan melakukan survei atau riset kecil-kecilan di tempat-tempat yang menjadi background dari masing-masing cerita ini. Yaitu di mall, kebun binatang, angkutan umum maupun di kolam renang. Kali ini topik khusus keselamatan umum yang diangkat adalah SAAT DI KOLAM RENANG, bercerita tentang Andin dan Kayla yang diajak oleh Kak Tami untuk menemani Kak Tami berlatih renang. Mereka berdua sambil menunggu kakaknya bermain di kolam renang bermain. Mereka berhati-hati dengan tidak berlari di kolam, menjaga kebersihan dengan tidak buang air di kolam renang serta tidak berenang di kolam dewasa. Ada beberapa hal yang perlu ditekankan lagi baik dalam cerita maupun dalam tips aman di akhir cerita, yaitu untuk tidak menggunakan perhiasan, melakukan pemanasan sebentar sebelum berenang serta menghindari tepi kolam.  Hal-hal tersebut mungkin perlu ditambahkan baik dalam alur cerita ataupun dalam Tips Aman  di akhir cerita.  Perlu dicek juga untuk ilustrasi dari masing-masing judul ini. Topik khusus di Kolam Renang ini dalam seri ini cukup menarik. dibutuhkan dan cukup menjual, karena banyak anak-anak yang suka bermain air, salah satunya adalah di kolam renang, selain di laut.', 'anak usia 6+, praktisi pendidikan dan pendamping anak, orang tua, dan masyarakat pada umumnya', 'toko buku, sekolah dan proyek', NULL, 'Baik', 'Diterima', 'Sangat baik', NULL, '072df3b932394d6caacb5c9c0960d42b', NULL, '2022-07-29 08:55:04', NULL),
('a8852a7a6ae24ceb9e0e426c58955310', '95d9ebcd691e477eaeaf7c1aba4dd04d', 'Baik', 'Baik', 'c16a55c4b1c54e12b3c79d292a340f84', 'Naskah seri Keselamatan di tempat umum ini terdiri dari 4 cerita, yaitu saat di Mal, di kebun binatang, di angkutan umum dan di kolam renang. Tema cukup menarik dan yang jelas dibutuhkan untuk anak-anak maupun orang tua. Yang perlu diperhatikan dalam pembahasannya (oleh penulis) adalah untuk mengecek penatalaksanaan penanganan jika ada darurat di tempat-tempat umum tersebut. Misalnya dengan melakukan survei atau riset kecil-kecilan di tempat-tempat yang menjadi background dari masing-masing cerita ini. Yaitu di mall, kebun binatang, angkutan umum maupun di kolam renang. Kali ini topik khusus keselamatan umum yang diangkat adalah SAAT DI ANGKUTAN UMUM, bercerita tentang Meta dan Tia yang akan pulang bersama menggunakan angkutan umum. Meta memperingatkan Tia untuk tidak bermain HP di angkot, tidak tidur di angkot dan menjaga untuk tidak sampai ketiduran di angkutan umum, untuk menghindari hal-hal yang tidak diinginkan serta memeriksa barang-barang yang dibawa jangan sampai tertinggal. Perlu dicek juga untuk ilustrasi dari masing-masing judul ini. Topik di angkutan umum ini  dalam seri ini cukup menarik meski mungkin sudah tidak terlalu populer karena sekarang lebih banyak menggunakan ojol. Tetapi topik ini masih cukup dibutuhkan dan cukup menjual, karena tetap bisa diterapkan menggunakan alat transportasi umum lainnya, seperti kereta api listrik, dll.', 'anak usia 6+, praktisi pendidikan dan pendamping anak, orang tua, dan masyarakat pada umumnya', 'toko buku, sekolah, proyek', NULL, 'Baik', 'Diterima', 'Sangat baik', NULL, '072df3b932394d6caacb5c9c0960d42b', NULL, '2022-07-29 09:21:06', NULL),
('b1a7ec6148df4d5898923fbf33f869be', 'dc29daf30b16470baebd899424abf819', 'Baik', 'Baik', 'c16a55c4b1c54e12b3c79d292a340f84', 'Naskah seri Keselamatan di tempat umum ini terdiri dari 4 cerita, yaitu saat di Mal, di kebun binatang, di angkutan umum dan di kolam renang. Tema cukup menarik dan yang jelas dibutuhkan untuk anak-anak maupun orang tua. Yang perlu diperhatikan dalam pembahasannya (oleh penulis) adalah untuk mengecek penatalaksanaan penanganan jika ada darurat di tempat-tempat umum tersebut. Misalnya dengan melakukan survei atau riset kecil-kecilan di tempat-tempat yang menjadi background dari masing-masing cerita ini. Yaitu di mall, kebun binatang, angkutan umum maupun di kolam renang. Untuk naskah dengan topik khusus keselamatan umum SAAT DI MALL, ini bercerita tentang dua orang anak yang saking asyiknya bermain di mall, hingga mereka akhirnya terpisah dari Pamannya yang mengajak mereka untuk bermain di Mall. Anak tersebut kemudian mencari petugas keamanan Mall (satpam) yang kemudian membantu mereka, dan akhirnya mereka dapat bertemu lagi dengan pamannya. Perlu dicek juga untuk ilustrasi dari masing-masing judul ini. Tetapi secara keseluruhan tema seri ini cukup menarik. dibutuhkan dan cukup menjual.', 'anak usia 7+, praktisi pendidikan, orang tua, dan masyarakat pada umumnya', 'proyek sekolah, perpustakaan, toko buku', 'Penerbitan', 'Baik', 'Diterima', 'Sangat baik', '[{\"judul\":\"Seri Keselamatan di Tempat Umum - Saat di Mal\"}]', '072df3b932394d6caacb5c9c0960d42b', '072df3b932394d6caacb5c9c0960d42b', '2022-06-09 01:39:32', '2022-07-29 01:10:45');

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
('456ef138f6a54356ae534f1e26db5e12', '5707a19dd4804b25899e57b37b335ab6', '2022-06-09 01:55:46', '2022-06-07 17:00:00', '2022-07-29 01:34:17', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('afa9189cb2924186af57e140b5d07d2a', '6318ede7909649eca9394fd844dfdd51', '2022-06-08 06:40:29', '2022-06-07 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('807b5b5d333e4795ac52965297014564', '95d9ebcd691e477eaeaf7c1aba4dd04d', '2022-06-09 01:56:53', '2022-06-07 17:00:00', '2022-07-29 02:21:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('ec6b88e193234473b7a2d8754acd4a51', '9c8132c2ebc34a3195c3aaca81095f3c', '2022-06-13 03:01:16', '2022-06-08 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('dbf488fdb55b4e639a2aaac4ff70cad6', 'aeb27fc7ab75452a8bfdc5d014b63d83', '2022-06-08 05:00:26', '2022-06-29 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('8d8f906c0ae64cc98e52c50dd0ef4464', 'be20459513994ceea0a19f191d947ed0', '2022-06-13 03:00:22', '2022-06-08 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('66427a29b87345168afb822db133256f', 'dc29daf30b16470baebd899424abf819', '2022-06-09 01:54:44', '2022-06-07 17:00:00', '2022-06-09 01:39:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('857b57c405d547eea4dd7d4e041ae91c', 'e75dd02c02154c44b142bfa7bb92049c', '2022-06-13 02:57:14', '2022-06-08 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('7c2cc21bcd9c42deb5e0c5b33485e5c6', 'f56c0df2ccd24207b5a18e660bcf6f1a', '2022-06-09 01:57:38', '2022-06-07 17:00:00', '2022-07-29 01:55:04', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('f7610c986e5547da8aedbf42c4a2b4e1', 'fd9c40ad3c62499e9e9b10af7bfae40c', '2022-06-09 03:03:18', '2022-06-08 17:00:00', '2022-06-30 21:39:22', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('7720778b3fbe4985a6caa389eb9542cf', '7efc9508128a4bec9ba4bd1140085ca3', '2022-09-09 10:21:20', '2022-09-09 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-09 10:21:20'),
('930b73d33a8445f7b933643ad967aa5f', 'b9614d8eb16a40cb871a589f23507e19', '2022-07-27 15:23:22', '2022-07-25 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('9e81321a28cb4d3b91e9c39541f62547', '1a71b26e23094c39a62f6c8d62ec6665', '2022-07-22 09:32:45', '2022-07-06 00:00:00', '2022-07-22 10:13:39', NULL, NULL, '2022-07-22 09:46:53', '2022-07-22 10:15:14', '2022-07-22 10:11:56', '2022-07-22 10:15:41', '2022-07-22 10:15:41'),
('8cd52b19503448a9965bf25519115419', '53ab006dae6b4fbab6b5077ded2751f7', NULL, '2022-09-12 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-12 02:59:02'),
('689621c52584453087a8c367e28cb757', '16fdb6f329c544c6824cf85ec38501b7', NULL, '2022-09-13 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-14 02:58:18'),
('3c365640aa224ca18ad9d3c9c2cd7e7c', '266da2c74b774d45a6717421208134e1', NULL, '2022-09-22 17:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
('068adb0171304c628b267874004d7e8c', '8bc1be5db97545e2ab1c79e0d68d4896', '', 'Read', 'lihat-platform-digital', 'Lihat Platform Digital'),
('09179170e6e643eca66b282e2ffae1f8', '70410774a1e0433bb213a9625aceb0bb', '', 'Approval', 'persetujuan-order-cetak', 'Persetujuan Cetak'),
('0d9c8667ccb34e9da275e7dce09d9cd9', '3dbad039493241aa8ed0c698d07ee94d', '', 'Update', 'ubah-format-buku', 'Ubah Format Buku'),
('1098a56970114e18898367d334658b47', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/mengubah-naskah', 'Update', 'ubah-data-naskah', 'Ubah Data Naskah'),
('12b852d92d284ab5a654c26e8856fffd', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/penilaian', 'Update', 'naskah-pn-mpenerbitan', 'Penilaian M.Penerbitan'),
('171e6210418440a8bf4d689841d0f32c', 'bfb8b970f85c4a42bac1dc56181dc96b', '', 'Approval', 'persetujuan-order-ebook', 'Persetujuan E-Book'),
('1b89744217b04f79a8c1d7a967a46912', 'fb6c8f0dcc9e43199642f08a0fe1fd56', 'penerbitan/penulis', 'Read', 'lihat-penulis', 'Lihat Data Penulis'),
('1c1940da68fa4f8ba2325e83c303c47c', '131899f9a9204e0baa1b23cd2eedff6a', 'manajemen-web/user', 'Update', 'ubah-data-user', 'Ubah Data User'),
('1f4e5b3752b8475cb5261940ef62532d', 'fb6c8f0dcc9e43199642f08a0fe1fd56', 'penerbitan/penulis/membuat-penulis', 'Create', 'tambah-data-penulis', 'Buat Data Penulis'),
('26a74e3097b94bd882bd1a9f6feace68', 'e32aa5bb41144ac58f2e6eeca81604ac', '', 'Create', 'ubah-atau-buat-des-final', 'Buat/Ubah Deskripsi Final'),
('28c3460bb5cf4c618ba8ec6f3c12ddbd', '31a0187d88d94ddc83db4b71524b5b2d', '', 'Delete', 'hapus-kelompok-buku', 'Hapus Kelompok Buku'),
('2b6032ef8a73463ba2c761c86be5ed5d', '8bc1be5db97545e2ab1c79e0d68d4896', '', 'Create', 'buat-platform-digital', 'Buat Platform Digital'),
('33c3711d787d416082c0519356547b0c', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/penilaian', 'Update', 'naskah-pn-setter', 'Penilaian Setter'),
('358a13267bcb4608a14c851c3010f79b', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/membuat-naskah', 'Create', 'tambah-data-naskah', 'Tambah Data Naskah'),
('38645f82ae7c468abad1ab191e7a8ad9', '131899f9a9204e0baa1b23cd2eedff6a', 'manajemen-web/users', 'Read', NULL, 'Lihat Data Users'),
('38f34660ef404dc9b7a0ee0f697ae781', '63a1825ffe574c00929e532fd6241629', '', 'Read', 'lihat-pracetak', 'Lihat Pracetak'),
('3a70433b-16f5-11ed-ae5c-1078d2a38ee5', 'bc5eb3aa02394dcca7692764e1328cee', '', 'Create', 'tambah-data-imprint', 'Buat Data Imprint'),
('4943c707-1e08-11ed-87ce-1078d2a38ee5', '5646908e-1e06-11ed-87ce-1078d2a38ee5', '', 'Read', 'lihat-proses-produksi', 'Lihat Data Proses Produksi'),
('4bb845580b464d7db3d7c3b3e4fd213b', '4e1627c1489844f985cbe2c485b2e162', 'manajemen-web/struktur-ao', 'Read', NULL, 'Lihat Struktur AO'),
('4cea10b3a4434bc3b342407a78a9ab2a', 'bfb8b970f85c4a42bac1dc56181dc96b', '', 'Delete', 'hapus-produksi-ebook', 'Hapus Order E-book'),
('4d64a842e08344b9aeec88ed9eb2eb72', '70410774a1e0433bb213a9625aceb0bb', '', 'Update', 'update-produksi', 'Mengubah Data Produksi Order Cetak'),
('569c1d340cea4b21a54910177eeaf51f', 'bd09e803c41245a49ef23987c27b20ac', '', 'Read', 'lihat-deskripsi-produk', 'Lihat Deskripsi Produk'),
('5a1bd42cca6f412cb1795a1aeddac2fe', 'bd09e803c41245a49ef23987c27b20ac', '', 'Approval', 'action-progress-des-produk', 'Action Progress Des.Produk'),
('5d793b19c75046b9a4d75d067e8e33b2', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/penilaian', 'Update', 'naskah-pn-editor', 'Penilaian Editor'),
('60b2f2ca-16f5-11ed-ae5c-1078d2a38ee5', 'bc5eb3aa02394dcca7692764e1328cee', '', 'Update', 'ubah-data-imprint', 'Ubah Data Imprint'),
('6903e82e7e94478f87df3cf80de6b587', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah', 'Read', NULL, 'Lihat Data Naskah'),
('6b4e3b36783d4a488101da7639c40de0', '71d6b5671ebb4e128215fccc458fbf09', '', 'Read', 'lihat-deskripsi-cover', 'Lihat Deskripsi Cover'),
('7527e84e47f94304b39525fa770dd904', 'bd09e803c41245a49ef23987c27b20ac', '', 'Create', 'ubah-atau-buat-des-produk', 'Buat/Ubah Deskripsi Produk'),
('78712deb909d4d88af7f098c0fcf6857', 'bfb8b970f85c4a42bac1dc56181dc96b', '', 'Decline', 'persetujuan-pending', 'Persetujuan Pending'),
('808ab7987c9b4f0ab025b1b9e3ed1d43', '92463f9e96394c19a979a3290fde5745', '', 'Read', 'lihat-editing', 'Buat Editing'),
('8791f143a90e42e2a4d1d0d6b1254bad', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/penilaian', 'Update', 'naskah-pn-direksi', 'Penilaian Direksi'),
('89bc4b0ef1dd4306a3217cbf24551071', 'fb6c8f0dcc9e43199642f08a0fe1fd56', 'penerbitan/penulis/hapus-penulis', 'Delete', 'hapus-data-penulis', 'Hapus Data Penulis'),
('8a6141a082554335a2137c90f9fa0a5e', '31a0187d88d94ddc83db4b71524b5b2d', '', 'Create', 'buat-kelompok-buku', 'Buat Kelompok Buku'),
('8baa9163-16f5-11ed-ae5c-1078d2a38ee5', 'bc5eb3aa02394dcca7692764e1328cee', '', 'Delete', 'hapus-data-imprint', 'Hapus Data Imprint'),
('8d9b1da4234f46eb858e1ea490da6348', '583a723cf036449d80d3742dcf695e38', 'penerbitan/naskah/timeline', 'Update', 'timeline-naskah-update-date', 'Ubah Tanggal Timeline Naskah'),
('8de7d59a74f345a5bcab20ec43376299', '30d0f70435904ad5b4e7cbfeb98fc021', '', 'Update', 'notifikasi-email-penulis', 'Notifikasi kirim Email Ke Penulis'),
('8f53727c763849aab80c1513505decf8', 'bfb8b970f85c4a42bac1dc56181dc96b', '', 'Update', 'update-produksi-ebook', 'Ubah Order E-book'),
('99a7a50e866749879f55b92df2b5449c', 'bd09e803c41245a49ef23987c27b20ac', '', 'Approval', 'approval-deskripsi-produk', 'Approval Deskripsi Produk'),
('9b4e52c30f974844ac7a050000a0ee6a', '70410774a1e0433bb213a9625aceb0bb', '', 'Decline', 'persetujuan-pending-cetak', 'Persetujuan Pending'),
('9beba245308543ce821efe8a3ba965e3', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/penilaian', 'Update', 'naskah-pn-dpemasaran', 'Penilaian D.Pemasaran'),
('9d69d18ff5184804990bc21cb1005ab7', 'bfb8b970f85c4a42bac1dc56181dc96b', '', 'Read', 'lihat-order-ebook', 'Lihat Order E-book'),
('a213b689b8274f4dbe19b3fb24d66840', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/penilaian', 'Update', 'naskah-pn-mpemasaran', 'Penilaian M.Pemasaran'),
('a6034d814d7e4671b4cc8a98433f8fb2', '583a723cf036449d80d3742dcf695e38', 'penerbitan/naskah/timeline', 'Create', 'timeline-naskah-add', 'Buat Timeline Naskah'),
('a91ee437-1e08-11ed-87ce-1078d2a38ee5', '5646908e-1e06-11ed-87ce-1078d2a38ee5', '', 'Update', 'ubah-lanjutan-data-produksi', 'Ubah Data Proses Produksi'),
('bc5a7cb945e14432bfdf312e2059e868', '31a0187d88d94ddc83db4b71524b5b2d', '', 'Read', 'lihat-kelompok-buku', 'Lihat Kelompok Buku'),
('bc6b9c821e3f42ccb57532930c8d92be', '8bc1be5db97545e2ab1c79e0d68d4896', '', 'Update', 'ubah-platform-digital', 'Ubah Platform Digital'),
('be40fd210eb44ee68475bbe80eb8b1ea', '31a0187d88d94ddc83db4b71524b5b2d', '', 'Update', 'ubah-kelompok-buku', 'Ubah Kelompok Buku'),
('c21495eca0d44776aeacf431dc9fb0e1', 'bfb8b970f85c4a42bac1dc56181dc96b', '', 'Create', 'tambah-produksi-ebook', 'Buat Order E-Book'),
('c64802952e504f4ab25a6b1241232f85', '70410774a1e0433bb213a9625aceb0bb', 'produksi/order-cetak', 'Read', 'lihat-order-cetak', 'Lihat Data Order Cetak'),
('cc93223a47764195ac15aacf266673d9', 'fb6c8f0dcc9e43199642f08a0fe1fd56', 'penerbitan/penulis/mengubah-penulis', 'Update', 'ubah-data-penulis', 'Ubah Data Penulis'),
('d821a505-1e08-11ed-87ce-1078d2a38ee5', 'b6cbf112-1e06-11ed-87ce-1078d2a38ee5', '', 'Update', 'ubah-data-multimedia', 'Ubah Data E-book Multimedia'),
('db87d2605a68440fbf8e148744e243e8', 'e32aa5bb41144ac58f2e6eeca81604ac', '', 'Read', 'lihat-deskripsi-final', 'Lihat Deskripsi Final'),
('dc43f263313f4788bccbcc9adf642a1f', '3dbad039493241aa8ed0c698d07ee94d', '', 'Delete', 'hapus-format-buku', 'Hapus Format Buku'),
('e0860766d564483e870b5974a601649c', '70410774a1e0433bb213a9625aceb0bb', '', 'Create', 'tambah-produksi-cetak', 'Membuat Data Order Cetak'),
('e9f5bad7fdd94494a125e451de456a92', '8bc1be5db97545e2ab1c79e0d68d4896', '', 'Delete', 'hapus-platform-digital', 'Hapus Platform Digital'),
('ebca07da8aad42c4aee304e3a6b81001', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/penilaian', 'Update', 'naskah-pn-prodev', 'Penilaian Prodev'),
('eecbccb6-1e08-11ed-87ce-1078d2a38ee5', 'b6cbf112-1e06-11ed-87ce-1078d2a38ee5', '', 'Read', 'lihat-ebook-multimedia', 'Lihat Data E-book Multimedia'),
('f76c69fb-16f4-11ed-ae5c-1078d2a38ee5', 'bc5eb3aa02394dcca7692764e1328cee', '', 'Read', 'lihat-imprint', 'Lihat Data Imprint'),
('faa7c4808c714ca49762f6aaade7da3b', '3dbad039493241aa8ed0c698d07ee94d', '', 'Read', 'lihat-format-buku', 'Lihat Format Buku'),
('fd061c3363db4b298eea0bb0b4cbcbf0', '3dbad039493241aa8ed0c698d07ee94d', '', 'Create', 'buat-format-buku', 'Buat Format Buku');

-- --------------------------------------------------------

--
-- Table structure for table `platform_digital_ebook`
--

CREATE TABLE `platform_digital_ebook` (
  `id` char(36) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `created_by` varchar(36) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
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
-- Table structure for table `produksi_order_cetak`
--

CREATE TABLE `produksi_order_cetak` (
  `id` varchar(36) NOT NULL,
  `kode_order` varchar(8) NOT NULL,
  `tipe_order` enum('1','2') NOT NULL COMMENT '1:Umum | 2:Rohani',
  `jenis_mesin` enum('1','2') DEFAULT NULL COMMENT '1= POD, 2= Mesin Besar',
  `status_cetak` enum('1','2','3') NOT NULL COMMENT '1:Buku Baru | 2:Cetak Ulang Revisi | 3:Cetak Ulang',
  `pilihan_terbit` enum('1','2') DEFAULT NULL COMMENT '1 = Cetak Fisik,\r\n2 = Cetak Fisik & E-Book',
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
  `buku_jadi` enum('Wrapping','Tidak Wrapping') DEFAULT NULL,
  `jumlah_cetak` int(10) DEFAULT NULL,
  `buku_contoh` text DEFAULT NULL,
  `spp` varchar(30) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `perlengkapan` text DEFAULT NULL,
  `tgl_permintaan_jadi` date DEFAULT NULL,
  `status_penyetujuan` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1 = Pending ~~\r\n2 = Disetujui ~~\r\n3 = Ditolak ~~',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `created_by` varchar(36) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `produksi_order_ebook`
--

CREATE TABLE `produksi_order_ebook` (
  `id` char(36) NOT NULL,
  `kode_order` varchar(9) NOT NULL,
  `tipe_order` enum('1','2') DEFAULT NULL COMMENT '1= Umum, 2= Rohani',
  `judul_buku` varchar(50) DEFAULT NULL,
  `sub_judul` varchar(100) DEFAULT NULL,
  `platform_digital` text DEFAULT NULL COMMENT 'array',
  `penulis` varchar(70) DEFAULT NULL,
  `eisbn` varchar(100) DEFAULT NULL,
  `penerbit` varchar(100) DEFAULT NULL,
  `imprint` varchar(100) DEFAULT NULL,
  `edisi_cetakan` varchar(20) DEFAULT NULL,
  `jumlah_halaman` varchar(100) DEFAULT NULL,
  `kelompok_buku` varchar(100) DEFAULT NULL,
  `tahun_terbit` year(4) DEFAULT NULL,
  `status_buku` enum('1','2') DEFAULT NULL COMMENT '1= Reguler, 2= MOU',
  `spp` varchar(25) DEFAULT NULL,
  `perlengkapan` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `tgl_upload` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `created_by` varchar(36) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `produksi_order_ebook`
--

INSERT INTO `produksi_order_ebook` (`id`, `kode_order`, `tipe_order`, `judul_buku`, `sub_judul`, `platform_digital`, `penulis`, `eisbn`, `penerbit`, `imprint`, `edisi_cetakan`, `jumlah_halaman`, `kelompok_buku`, `tahun_terbit`, `status_buku`, `spp`, `perlengkapan`, `keterangan`, `tgl_upload`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
('62b39571-5192-496b-97df-d7663f2681dd', 'E22-1000', '1', 'Di Balik Mata Kaca', 'Sebuah Pengalaman', '[\"Moco\",\"Indopustaka\",\"Bahanaflix\",\"Esentral\",\"Google Book\"]', 'Yohanes Hendra', '2453442341234', 'Andi', 'PBMR Andi', 'vii/1', 'viii + 325', 'ArchitectPhotop', 2022, '2', NULL, NULL, NULL, '2022-09-14 13:49:49', '2022-09-06 06:49:49', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `produksi_penyetujuan_order_cetak`
--

CREATE TABLE `produksi_penyetujuan_order_cetak` (
  `id` char(36) NOT NULL,
  `produksi_order_cetak_id` varchar(36) NOT NULL,
  `m_penerbitan` varchar(36) DEFAULT NULL,
  `m_stok` varchar(36) DEFAULT NULL,
  `d_operasional` varchar(36) DEFAULT NULL,
  `d_keuangan` varchar(36) DEFAULT NULL,
  `d_utama` varchar(36) DEFAULT NULL,
  `m_penerbitan_act` enum('1','3') NOT NULL DEFAULT '1' COMMENT '1= Belum, 3= Setuju',
  `m_stok_act` enum('1','3') NOT NULL DEFAULT '1' COMMENT '1= Belum, 3= Setuju',
  `d_operasional_act` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1= Belum, 2= Pending, 3= Setuju',
  `d_keuangan_act` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1= Belum, 2= Pending, 3= Setuju',
  `d_utama_act` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1= Belum, 2= Pending, 3= Setuju',
  `tgl_permintaan_jadi_history` date DEFAULT NULL,
  `jumlah_cetak_history` int(10) DEFAULT NULL,
  `diubah_oleh` varchar(36) DEFAULT NULL,
  `ket_pending` text DEFAULT NULL,
  `pending_sampai` date DEFAULT NULL,
  `status_general` enum('Proses','Pending','Selesai') NOT NULL DEFAULT 'Proses',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `produksi_penyetujuan_order_ebook`
--

CREATE TABLE `produksi_penyetujuan_order_ebook` (
  `id` char(36) NOT NULL,
  `produksi_order_ebook_id` char(36) NOT NULL,
  `m_penerbitan` varchar(36) DEFAULT NULL,
  `d_operasional` varchar(36) DEFAULT NULL,
  `d_keuangan` varchar(36) DEFAULT NULL,
  `d_utama` varchar(36) DEFAULT NULL,
  `m_penerbitan_act` enum('1','3') NOT NULL DEFAULT '1' COMMENT '1= belum, 3= Setuju',
  `d_operasional_act` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1= belum, 2= pending, 3= setuju',
  `d_keuangan_act` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1= belum, 2= pending, 3= setuju',
  `d_utama_act` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1= belum, 2= pending, 3= setuju',
  `tgl_upload_history` datetime DEFAULT NULL,
  `ket_pending` text DEFAULT NULL,
  `pending_sampai` date DEFAULT NULL,
  `status_general` enum('Proses','Pending','Selesai') DEFAULT 'Proses',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `produksi_penyetujuan_order_ebook`
--

INSERT INTO `produksi_penyetujuan_order_ebook` (`id`, `produksi_order_ebook_id`, `m_penerbitan`, `d_operasional`, `d_keuangan`, `d_utama`, `m_penerbitan_act`, `d_operasional_act`, `d_keuangan_act`, `d_utama_act`, `tgl_upload_history`, `ket_pending`, `pending_sampai`, `status_general`, `created_at`, `updated_at`) VALUES
('370ec5ac-8280-4bc9-9969-03191d57db92', '62b39571-5192-496b-97df-d7663f2681dd', '4fc80f443bfb4969b9a0272d9be08ef2', 'fd035809e2c045098770a7e9dfccddf9', 'c62658af71bf4c5692b041c9384d068b', '37aee684a9e447a6bef36cbf08222d5d', '1', '1', '1', '1', NULL, NULL, NULL, 'Proses', '2022-09-06 06:49:49', '2022-09-06 06:49:52'),
('7c559112-bce4-489f-9972-427cdf81639c', '329fb5c2-531c-4f61-a6cd-4aaf812f76a9', '4fc80f443bfb4969b9a0272d9be08ef2', 'fd035809e2c045098770a7e9dfccddf9', 'c62658af71bf4c5692b041c9384d068b', '37aee684a9e447a6bef36cbf08222d5d', '1', '1', '1', '1', NULL, NULL, NULL, 'Proses', '2022-09-06 06:24:12', '2022-09-06 06:25:21'),
('d1bbaf8f-ad71-4b01-8fef-11c9b7308d59', 'd9079338-1709-4ac5-b845-15314c079e9d', '4fc80f443bfb4969b9a0272d9be08ef2', 'fd035809e2c045098770a7e9dfccddf9', 'c62658af71bf4c5692b041c9384d068b', '37aee684a9e447a6bef36cbf08222d5d', '1', '1', '1', '1', NULL, NULL, NULL, 'Proses', '2022-09-06 04:58:55', '2022-09-06 04:59:08');

-- --------------------------------------------------------

--
-- Table structure for table `proses_ebook_multimedia`
--

CREATE TABLE `proses_ebook_multimedia` (
  `id` char(36) NOT NULL,
  `order_ebook_id` char(36) NOT NULL,
  `bukti_upload` longtext DEFAULT NULL COMMENT 'Link Bukti Upload',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(36) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
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
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `created_by` varchar(36) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
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
('03e6d8cb79ab4b1cb8f907c39ea4b11b', 'orderperti@gmail.com', '$2y$10$4vbwZ58dqW5mAPntJFhY5.uMGLsD4YIP9Le/IKh.K7OIHoUIaaHbq', 'default.jpg', 'Palar Putra Patria', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cba340ae34984106a28fb87dbd3c4f84', '1ddca5f391d44e24be22c5e8d755aa34', '1', '0', '735d609249d649f3b4e4296d69ab0fd6', '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, '2022-05-30 21:35:50', NULL),
('072df3b932394d6caacb5c9c0960d42b', '008.penerbitan@gmail.com', '$2y$10$mvyZjgC26vZ47fGvHarmMuRCe6XJF5ofVMZNMyOgzBagJOnrh9ub6', 'default.jpg', 'Florentina Meiana Utami', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', 'c9f0106ff972412393227db316d03cb9', '1', '0', '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('0ecea60f2691405585fa1aa535368bee', '197.penerbitan@gmail.com', '$2y$10$9pakJxx7NrDY0ynBqC0qXunO0H5EdHQ9Nz3it.mwlqvb00CnIsjba', 'default.jpg', 'Dwi Prabantini', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', 'c9f0106ff972412393227db316d03cb9', '1', '0', '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('1beceaecfa3f45c8be6d2f761aea389f', 'naskahmou@gmail.com', '$2y$10$P8H6syiCYKuZz4sNr034DuyPoWabcwZ5u/48R6r4BhLe60AWiGWuG', 'default.jpg', 'Lily Erlinawati', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', '43e6121f78dc4ddbbc98011d9eb662a7', '1', '0', '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('205cbcb8d74646f7bfafd9b4972f04ac', 'apriyo@gmail.com', '$2y$10$54Gu05ZqCieS3dwFC2adAu4M749mCjEc77VrMEVUNwd9i9Dtk4Ryy', 'default.jpg', 'Andreas Priyo Saddono', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'fda3c745c5d6410cb8eb25fb41ae4d36', '58fc78b637424cb894a238014974a6d8', '1', '0', '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('214af5d98cc74484be4ef05aa56a9e45', 'pemasaran@gmail.com', '$2y$10$GfutH1idi08pXVloCI.Q2.4yPE0.UcCXNndCRB248QE.jBHTUdbYW', 'default.jpg', 'Pemasaran Contoh', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '1f7a55f145f84a9e9fbfa218482a2668', '58fc78b637424cb894a238014974a6d8', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('25fbb5a3233e4cf6a889b412691149d1', 'editor@gmail.com', '$2y$10$4lvU0hyZnmon1i2vnnZSC.IAFUkDRecHbzyHK.ePXUjn3guoxR8UC', 'default.jpg', 'Editor Contoh', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '1f7a55f145f84a9e9fbfa218482a2668', '58fc78b637424cb894a238014974a6d8', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('2d4e15b2176e49a1bb7d54d06c8f935f', '139.penerbitan@gmail.com', '$2y$10$TLzzdC/9XVdXG2Npmliq7eQpV713UUif/pDV05z/wOMBg.Polx9JC', 'default.jpg', 'Antonius Teguh Budi Suprapto', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', '3189d4fa029542e090632ae162f7cf3e', '1', '0', '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('355c4e0e850c43f382cf1052f7053f40', 'setter@gmail.com', '$2y$10$13QUAJQqtqtcjEaVEM7k2e3OmdaiuVHyQIh7TTXgH1ToAa/oqHWsO', 'default.jpg', 'Setter Contoh', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '1f7a55f145f84a9e9fbfa218482a2668', '58fc78b637424cb894a238014974a6d8', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('417efb26a79e4c15966aea36536478ff', 'prodev@gmail.com', '$2y$10$clxJYFdvPNnuSFeGoQ/fYeSbbhC5NKNvCGsTSrcGFpifMIos7YLh6', 'default.jpg', 'Prodev Contoh', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '1f7a55f145f84a9e9fbfa218482a2668', '58fc78b637424cb894a238014974a6d8', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('46cc8c6792634b3eb0c0da7c38763d01', 'ao.marketingsup02@gmail.com', '$2y$10$2ezjwa3ApeVZMvUEIZG90e.ChzdJKA7ywVSBnUH5maSTFSTy8803O', 'default.jpg', 'Maria Veronica Frida Astuti', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cba340ae34984106a28fb87dbd3c4f84', '4cebe6a298b74c11965343d3adffc40d', '1', '0', '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('54281e5fceba4bf092c79ce994537ed3', 'agustinussaputra52@gmail.com', '$2y$10$T6PZrji0dQsGPUQodvK9JeFa1g81TIlY5DWaf2SJ2n2T0oHntQJIO', 'default.jpg', 'Agustinus Subardana', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cba340ae34984106a28fb87dbd3c4f84', '1cd910eee9054a7d8d820ab23944a4cd', '1', '0', '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('5537f8f560a549e88d7a443b801bb1af', '135.penerbitan@gmail.com', '$2y$10$C3csXjpjI/d/66022Nc0qOwBMBdoxqe30oNs3NpnRsUXy6L3M8fWC', 'default.jpg', 'Cecilia Anita Herawati', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', '58fc78b637424cb894a238014974a6d8', '1', '0', '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('57a9534bd79d4382bb0f43c89910702c', 'verina@gmail.com', '$2y$10$FZc1p4ilqo0EQGI27.bCzufMs8NLlrkIclDl/aTn.2peysiYlsD/2', '6k7QIkCEM9lggCUUvgoTey49AIUfUZcm4joGg1Qy.jpg', 'Verina', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '1f7a55f145f84a9e9fbfa218482a2668', '58fc78b637424cb894a238014974a6d8', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-05-30 19:42:23', NULL),
('59ed337897f347659c6c78270df47a80', 'managerrohanieva@gmail.com', '$2y$10$6b5r4t/PaT4IdifwDIanXut.crtyp1kZ6tETvOka8SgbWsRe/QPSa', 'default.jpg', 'Eva Renta Agustina Sibarani', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cba340ae34984106a28fb87dbd3c4f84', '542c06e42b784cafbae8a1666bae508a', '1', '0', '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('5e26c2b906a543989ed3a9abba178fe1', 'samudromadderswae@gmail.com', '$2y$10$BQdk9lT3AbvTQGEpOn8KheF8hY94a1lgsHAn6ihP5RfZyLrcOa3Ou', 'default.jpg', 'Samudro Cahyono', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', '58fc78b637424cb894a238014974a6d8', '1', '0', '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('5f64a6100114456a8905b8dfc1dc1460', 'penerbitan@gmail.com', '$2y$10$H69ntwUUmmBNP.NcLjFiaezEdEWaV5pix4E7qJHBC.ENQVpCbgxJ2', 'default.jpg', 'Penerbitan Contoh', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '1f7a55f145f84a9e9fbfa218482a2668', '58fc78b637424cb894a238014974a6d8', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('735d609249d649f3b4e4296d69ab0fd6', 'vaniamarditaa@gmail.com', '$2y$10$vQ2zyYhaf2NB4D9.GBmK6eHdNNAu5sybqWSinP5H3L94nma10MO46', 'default.jpg', 'Elisabeth Vania Mardita', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', '07e058a9224645df94a5e5c7894c9ed1', '1', '0', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, NULL, NULL, NULL),
('77e9d0dacb154a37b5730efb8d36ad29', '046.penerbitan@gmail.com', '$2y$10$4BqR.DkoxTz.KZ0dOn3TmO.nGWOqjjfTWLuW4JFz8jMqbgymsrJFG', 'default.jpg', 'Dyna Novitasari', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', 'c9f0106ff972412393227db316d03cb9', '1', '0', '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('7c4f86aba34f430884fb87fee84bc870', 'ferdylucker@gmail.com', '$2y$10$4EsVFMMPCP2l0rjRuLiIWel50QldDXz5j/9stPL.CnC7oDMN1FM96', 'default.jpg', 'Ferdyawan Listanto', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '1f7a55f145f84a9e9fbfa218482a2668', '7a9181a87fc34b4083e6e9e44594a1ac', '1', '0', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, NULL, NULL, NULL),
('7e7bb093dc0e4fb98f3d8a37b92d4457', 'edis.mulyanta@gmail.com', '$2y$10$QFXL6w1nRsTqVa.MsrXlxOeeNgpymjV3Ou5dxB2tBWLLykLznOAFC', 'default.jpg', 'Edi Srimulyanto', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', 'b14c6581b9524832a107ae12a0518ba3', '1', '0', '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('9f72c952b9ea4474a912d619cba5d0a8', 'yehezkiel.gondowijoyo@gmail.com', '$2y$10$icRrW.cPQ7RU5Kcu4xaHi.oGNjD6SkZspc0qJwmvhBONEVt7nUwsu', 'default.jpg', 'Yehezkiel Arman Gondowijoyo', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cc0c20fa203249e6aae0bbf3d9f38ffe', 'fe067d18155c4f298f07a409f6aca5a4', '1', '0', '735d609249d649f3b4e4296d69ab0fd6', '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, '2022-06-01 18:46:38', NULL),
('a400e4bcf70d40d78224043cc95e6241', '064.penerbitan@gmail.com', '$2y$10$stfG2FHtqQcpPsb31qlLueNBYDYFs5KgYpuvzMQp5S2O0ug0zKauO', 'default.jpg', 'Ignatius Eko Agus Wibowo', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', 'c9f0106ff972412393227db316d03cb9', '1', '0', '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('a5496cc344a24908835563d47e108a52', 'project.andigroup@gmail.com', '$2y$10$78zuslsWqmGovTs92egBW.YofVDaPewD.KAxtw.SlN22LQvfeDRxC', 'default.jpg', 'Hendrix Dani Sanjaya', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cba340ae34984106a28fb87dbd3c4f84', '86bdfb7e323148b89c0316caea05b113', '1', '0', '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('b18e4ff295b24fd2bab92717b85504b6', '442.penerbitan@gmail.com', '$2y$10$H7gkvA6v1zcscJj.moRBHOsK9v75N83EbQiDKB2GAC/26JZgYZqKC', 'default.jpg', 'Dwinita Andriastuti', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', 'c9f0106ff972412393227db316d03cb9', '1', '0', '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('b7b8f96b9f534c3b96e1122dfe18317e', 'panuwun.budi@gmail.com', '$2y$10$14AcogjWJMtIASyOc035ZuRRlaMVg0ngtxI.EMeZaOSarOhpb4sSq', 'default.jpg', 'Panuwun Budi Raharjo', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', 'c0d1b2987056470bb3ffc8f499c40b85', '1', '0', '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('bc95dda2d2a741728a97de102d1c2155', 'manajerbukurohani@gmail.com', '$2y$10$wl.9In/bLwqp65nwfh7wrOJLm71cYfvgVU3eBI3tBiZm/wDygfm/C', 'default.jpg', 'R.S Ronny Kurniawan Darsono', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cba340ae34984106a28fb87dbd3c4f84', 'd11c1f0d64034db09972dbf57aa694d5', '1', '0', '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('be8d42fa88a14406ac201974963d9c1b', 'admin@gmail.com', '$2y$10$JfdI4rvmfegdd97t9xvgB.YWs5PQhfN4IlkOzgLoWrDeZaYDvL6Lu', 'default.jpg', 'Super Admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('c47a1a96c06642db86350d8b442911d1', 'yudantodwi81@gmail.com', '$2y$10$wjqwFz2CWe4TfObIvCsw4O.Nvk5Co9HDrJgLsXn2ByNcdxwEEskcS', 'default.jpg', 'Yudanto Dwilaksono', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'cba340ae34984106a28fb87dbd3c4f84', '300c4db6ea724f4c80e6a64497304b77', '1', '0', '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('c94ad7236255430b82c0546dd82b917e', 'direkturmarketing.ops@gmail.com', '$2y$10$bl8y7u8tBANan1vUxmpp4O2i2mkMv8KOvKJBwWbzY/hDRyD6eYH7e', 'default.jpg', 'Adi Kristianto', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', '387ef8138f174e4f9fb2f2d147f03a71', '1', '0', '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('dc306f83529445139c16dd55b4f0e6f3', '314.penerbitan@gmail.com', '$2y$10$nmWPVq600NkZO0X4XvFTq.9KiKOx8Ctf7uN8jwQGyOn/UVIIGBpKi', 'default.jpg', 'Tjahjono Tri Wibowo', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', '620feb2d0dea419f83964e844c7b2003', '1', '0', '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('e83ca4537495486c8d3b5d7e6ae2407a', '227.penerbitan@gmail.com', '$2y$10$TpnTqwmNeUB1PUMrHZrAquxf0trwsICTBcMoA3mYwORwF5DD9L0LK', 'default.jpg', 'Brigitta Pasca Puspita Sari', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', 'c9f0106ff972412393227db316d03cb9', '1', '0', '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('ee2c544aa4dc4c1eb12472cd84406358', 'direksi@gmail.com', '$2y$10$jBLTb06gat36o2jtzb1eQ.led9dh6KjLWAOQq8fQyT4I3RQC64cL2', 'default.jpg', 'Direksi Contoh', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '1f7a55f145f84a9e9fbfa218482a2668', '58fc78b637424cb894a238014974a6d8', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('f38be109ef1a42eb9a9b9d4de30cd6a0', 'titopenerbitandi@gmail.com', '$2y$10$6spe9FUsY04.N61y59nvBeYCjvz7CA6taFwXpuTbMnh0uYeiYK/Km', 'default.jpg', 'Astito Puput Wijanarko', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'fda3c745c5d6410cb8eb25fb41ae4d36', '7d4184188a694c55bd2e6c945182c249', '1', '0', '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('f88116e1873c4403a7fb76a08f5266cb', '423.penerbitan@gmail.com', '$2y$10$p/h3lk9TZ/t9lDj96kzxVehLNnZWX60R9Au6NAjs1vFI8ASQVbu.6', 'default.jpg', 'Jesicca Deviyanti', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '3561e1b9e15a4b9588f1c53366a5eaa8', 'c9f0106ff972412393227db316d03cb9', '1', '0', '735d609249d649f3b4e4296d69ab0fd6', NULL, NULL, NULL, NULL, NULL),
('fbba0a06756b43c69a01c961616ff978', 'ivan.fernadin@gmail.com', '$2y$10$60RfEDnsTjU4BMlbdlvXJusUsBpurYg4hdjNW.eBH3P/3n7.BfVU2', 'default.jpg', 'Ivan Fernadin', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '1f7a55f145f84a9e9fbfa218482a2668', '987d4c4e12a74897b3478f23e757d732', '1', '0', '57a9534bd79d4382bb0f43c89910702c', NULL, NULL, NULL, NULL, NULL);

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
('dc306f83529445139c16dd55b4f0e6f3', '1098a56970114e18898367d334658b47'),
('dc306f83529445139c16dd55b4f0e6f3', '12b852d92d284ab5a654c26e8856fffd'),
('dc306f83529445139c16dd55b4f0e6f3', '358a13267bcb4608a14c851c3010f79b'),
('dc306f83529445139c16dd55b4f0e6f3', '6903e82e7e94478f87df3cf80de6b587'),
('dc306f83529445139c16dd55b4f0e6f3', '1b89744217b04f79a8c1d7a967a46912'),
('dc306f83529445139c16dd55b4f0e6f3', '1f4e5b3752b8475cb5261940ef62532d'),
('dc306f83529445139c16dd55b4f0e6f3', '89bc4b0ef1dd4306a3217cbf24551071'),
('dc306f83529445139c16dd55b4f0e6f3', 'cc93223a47764195ac15aacf266673d9'),
('c94ad7236255430b82c0546dd82b917e', '6903e82e7e94478f87df3cf80de6b587'),
('c94ad7236255430b82c0546dd82b917e', '8791f143a90e42e2a4d1d0d6b1254bad'),
('c94ad7236255430b82c0546dd82b917e', '1b89744217b04f79a8c1d7a967a46912'),
('7e7bb093dc0e4fb98f3d8a37b92d4457', '12b852d92d284ab5a654c26e8856fffd'),
('7e7bb093dc0e4fb98f3d8a37b92d4457', '358a13267bcb4608a14c851c3010f79b'),
('7e7bb093dc0e4fb98f3d8a37b92d4457', '6903e82e7e94478f87df3cf80de6b587'),
('7e7bb093dc0e4fb98f3d8a37b92d4457', '1b89744217b04f79a8c1d7a967a46912'),
('9f72c952b9ea4474a912d619cba5d0a8', '6903e82e7e94478f87df3cf80de6b587'),
('9f72c952b9ea4474a912d619cba5d0a8', '8791f143a90e42e2a4d1d0d6b1254bad'),
('9f72c952b9ea4474a912d619cba5d0a8', '8d9b1da4234f46eb858e1ea490da6348'),
('9f72c952b9ea4474a912d619cba5d0a8', 'a6034d814d7e4671b4cc8a98433f8fb2'),
('9f72c952b9ea4474a912d619cba5d0a8', '1b89744217b04f79a8c1d7a967a46912'),
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
('e83ca4537495486c8d3b5d7e6ae2407a', '6903e82e7e94478f87df3cf80de6b587'),
('e83ca4537495486c8d3b5d7e6ae2407a', 'ebca07da8aad42c4aee304e3a6b81001'),
('e83ca4537495486c8d3b5d7e6ae2407a', '1b89744217b04f79a8c1d7a967a46912'),
('e83ca4537495486c8d3b5d7e6ae2407a', 'cc93223a47764195ac15aacf266673d9'),
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
('ee2c544aa4dc4c1eb12472cd84406358', 'f76c69fb-16f4-11ed-ae5c-1078d2a38ee5'),
('ee2c544aa4dc4c1eb12472cd84406358', '068adb0171304c628b267874004d7e8c'),
('ee2c544aa4dc4c1eb12472cd84406358', 'faa7c4808c714ca49762f6aaade7da3b'),
('ee2c544aa4dc4c1eb12472cd84406358', 'bc5a7cb945e14432bfdf312e2059e868'),
('ee2c544aa4dc4c1eb12472cd84406358', '171e6210418440a8bf4d689841d0f32c'),
('ee2c544aa4dc4c1eb12472cd84406358', '78712deb909d4d88af7f098c0fcf6857'),
('ee2c544aa4dc4c1eb12472cd84406358', '9d69d18ff5184804990bc21cb1005ab7'),
('ee2c544aa4dc4c1eb12472cd84406358', '09179170e6e643eca66b282e2ffae1f8'),
('ee2c544aa4dc4c1eb12472cd84406358', '9b4e52c30f974844ac7a050000a0ee6a'),
('ee2c544aa4dc4c1eb12472cd84406358', 'c64802952e504f4ab25a6b1241232f85'),
('ee2c544aa4dc4c1eb12472cd84406358', 'db87d2605a68440fbf8e148744e243e8'),
('ee2c544aa4dc4c1eb12472cd84406358', '569c1d340cea4b21a54910177eeaf51f'),
('ee2c544aa4dc4c1eb12472cd84406358', '99a7a50e866749879f55b92df2b5449c'),
('ee2c544aa4dc4c1eb12472cd84406358', '6b4e3b36783d4a488101da7639c40de0'),
('ee2c544aa4dc4c1eb12472cd84406358', '6903e82e7e94478f87df3cf80de6b587'),
('ee2c544aa4dc4c1eb12472cd84406358', '8791f143a90e42e2a4d1d0d6b1254bad'),
('ee2c544aa4dc4c1eb12472cd84406358', '1b89744217b04f79a8c1d7a967a46912'),
('ee2c544aa4dc4c1eb12472cd84406358', 'eecbccb6-1e08-11ed-87ce-1078d2a38ee5'),
('ee2c544aa4dc4c1eb12472cd84406358', '4943c707-1e08-11ed-87ce-1078d2a38ee5'),
('be8d42fa88a14406ac201974963d9c1b', '3a70433b-16f5-11ed-ae5c-1078d2a38ee5'),
('be8d42fa88a14406ac201974963d9c1b', '60b2f2ca-16f5-11ed-ae5c-1078d2a38ee5'),
('be8d42fa88a14406ac201974963d9c1b', '8baa9163-16f5-11ed-ae5c-1078d2a38ee5'),
('be8d42fa88a14406ac201974963d9c1b', 'f76c69fb-16f4-11ed-ae5c-1078d2a38ee5'),
('be8d42fa88a14406ac201974963d9c1b', '068adb0171304c628b267874004d7e8c'),
('be8d42fa88a14406ac201974963d9c1b', '2b6032ef8a73463ba2c761c86be5ed5d'),
('be8d42fa88a14406ac201974963d9c1b', 'bc6b9c821e3f42ccb57532930c8d92be'),
('be8d42fa88a14406ac201974963d9c1b', 'e9f5bad7fdd94494a125e451de456a92'),
('be8d42fa88a14406ac201974963d9c1b', '0d9c8667ccb34e9da275e7dce09d9cd9'),
('be8d42fa88a14406ac201974963d9c1b', 'dc43f263313f4788bccbcc9adf642a1f'),
('be8d42fa88a14406ac201974963d9c1b', 'faa7c4808c714ca49762f6aaade7da3b'),
('be8d42fa88a14406ac201974963d9c1b', 'fd061c3363db4b298eea0bb0b4cbcbf0'),
('be8d42fa88a14406ac201974963d9c1b', '28c3460bb5cf4c618ba8ec6f3c12ddbd'),
('be8d42fa88a14406ac201974963d9c1b', '8a6141a082554335a2137c90f9fa0a5e'),
('be8d42fa88a14406ac201974963d9c1b', 'bc5a7cb945e14432bfdf312e2059e868'),
('be8d42fa88a14406ac201974963d9c1b', 'be40fd210eb44ee68475bbe80eb8b1ea'),
('be8d42fa88a14406ac201974963d9c1b', '171e6210418440a8bf4d689841d0f32c'),
('be8d42fa88a14406ac201974963d9c1b', '4cea10b3a4434bc3b342407a78a9ab2a'),
('be8d42fa88a14406ac201974963d9c1b', '78712deb909d4d88af7f098c0fcf6857'),
('be8d42fa88a14406ac201974963d9c1b', '8f53727c763849aab80c1513505decf8'),
('be8d42fa88a14406ac201974963d9c1b', '9d69d18ff5184804990bc21cb1005ab7'),
('be8d42fa88a14406ac201974963d9c1b', 'c21495eca0d44776aeacf431dc9fb0e1'),
('be8d42fa88a14406ac201974963d9c1b', '808ab7987c9b4f0ab025b1b9e3ed1d43'),
('be8d42fa88a14406ac201974963d9c1b', '09179170e6e643eca66b282e2ffae1f8'),
('be8d42fa88a14406ac201974963d9c1b', '4d64a842e08344b9aeec88ed9eb2eb72'),
('be8d42fa88a14406ac201974963d9c1b', '9b4e52c30f974844ac7a050000a0ee6a'),
('be8d42fa88a14406ac201974963d9c1b', 'c64802952e504f4ab25a6b1241232f85'),
('be8d42fa88a14406ac201974963d9c1b', 'e0860766d564483e870b5974a601649c'),
('be8d42fa88a14406ac201974963d9c1b', '38f34660ef404dc9b7a0ee0f697ae781'),
('be8d42fa88a14406ac201974963d9c1b', '26a74e3097b94bd882bd1a9f6feace68'),
('be8d42fa88a14406ac201974963d9c1b', 'db87d2605a68440fbf8e148744e243e8'),
('be8d42fa88a14406ac201974963d9c1b', '569c1d340cea4b21a54910177eeaf51f'),
('be8d42fa88a14406ac201974963d9c1b', '5a1bd42cca6f412cb1795a1aeddac2fe'),
('be8d42fa88a14406ac201974963d9c1b', '7527e84e47f94304b39525fa770dd904'),
('be8d42fa88a14406ac201974963d9c1b', '6b4e3b36783d4a488101da7639c40de0'),
('be8d42fa88a14406ac201974963d9c1b', '8d9b1da4234f46eb858e1ea490da6348'),
('be8d42fa88a14406ac201974963d9c1b', 'a6034d814d7e4671b4cc8a98433f8fb2'),
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
('be8d42fa88a14406ac201974963d9c1b', '1b89744217b04f79a8c1d7a967a46912'),
('be8d42fa88a14406ac201974963d9c1b', '1f4e5b3752b8475cb5261940ef62532d'),
('be8d42fa88a14406ac201974963d9c1b', '89bc4b0ef1dd4306a3217cbf24551071'),
('be8d42fa88a14406ac201974963d9c1b', 'cc93223a47764195ac15aacf266673d9'),
('be8d42fa88a14406ac201974963d9c1b', 'd821a505-1e08-11ed-87ce-1078d2a38ee5'),
('be8d42fa88a14406ac201974963d9c1b', 'eecbccb6-1e08-11ed-87ce-1078d2a38ee5'),
('be8d42fa88a14406ac201974963d9c1b', '4943c707-1e08-11ed-87ce-1078d2a38ee5'),
('be8d42fa88a14406ac201974963d9c1b', 'a91ee437-1e08-11ed-87ce-1078d2a38ee5'),
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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_ab` (`order_ab`);

--
-- Indexes for table `cabang`
--
ALTER TABLE `cabang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

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
-- Indexes for table `divisi`
--
ALTER TABLE `divisi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama` (`nama`);

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
-- Indexes for table `produksi_order_cetak`
--
ALTER TABLE `produksi_order_cetak`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_order` (`kode_order`);

--
-- Indexes for table `produksi_order_ebook`
--
ALTER TABLE `produksi_order_ebook`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_order` (`kode_order`);

--
-- Indexes for table `produksi_penyetujuan_order_cetak`
--
ALTER TABLE `produksi_penyetujuan_order_cetak`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `produksi_penyetujuan_order_ebook`
--
ALTER TABLE `produksi_penyetujuan_order_ebook`
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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `deskripsi_final_history`
--
ALTER TABLE `deskripsi_final_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `deskripsi_produk_history`
--
ALTER TABLE `deskripsi_produk_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

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
-- AUTO_INCREMENT for table `penerbitan_m_kelompok_buku_history`
--
ALTER TABLE `penerbitan_m_kelompok_buku_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `penerbitan_naskah_history`
--
ALTER TABLE `penerbitan_naskah_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `platform_digital_ebook_history`
--
ALTER TABLE `platform_digital_ebook_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
