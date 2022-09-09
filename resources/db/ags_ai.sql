-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Sep 2022 pada 11.55
-- Versi server: 10.1.38-MariaDB
-- Versi PHP: 7.4.11

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
-- Struktur dari tabel `access`
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
-- Dumping data untuk tabel `access`
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
-- Struktur dari tabel `access_bagian`
--

CREATE TABLE `access_bagian` (
  `id` varchar(36) NOT NULL,
  `name` varchar(150) NOT NULL,
  `order_ab` tinyint(4) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `access_bagian`
--

INSERT INTO `access_bagian` (`id`, `name`, `order_ab`) VALUES
('04431b2b0e864cd4af41c87256cb92ef', 'Dashboard', 1),
('063203a5c5124b399ab76f8a03b93c0d', 'Penerbitan', 3),
('3f9dfd9391394a5fa10d835e0ebb341c', 'Master Data', 2),
('8a3ca046fb54492a86aaead53f36bec7', 'Produksi', 4),
('f7e795b9ece54c6d82b0ed19f025a65e', 'Manajemen Web', 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cabang`
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
-- Dumping data untuk tabel `cabang`
--

INSERT INTO `cabang` (`id`, `kode`, `nama`, `telp`, `alamat`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
('ada2962f70ce45fd8b930f1babafeba8', '0000', 'Head Office', '0274123456', 'JL Beo ,Mrican Caturtunggal Depok Sleman', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-03-31 08:32:58', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `divisi`
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
-- Dumping data untuk tabel `divisi`
--

INSERT INTO `divisi` (`id`, `nama`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
('646a4663aea14eb9915b718cbcc5e33b', 'Direksi', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-03-31 08:33:54', NULL, NULL),
('821ac200b1de45fdad7d533ce0190492', 'Produksi', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-03-31 08:33:36', NULL, NULL),
('d1946a0d285944488032d2dcd1a7882b', 'Operasional', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-03-31 08:33:48', NULL, NULL),
('df719b3e9de442b3ba21b1b414887ec7', 'Marketing', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-03-31 08:33:18', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `format_buku`
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
-- Dumping data untuk tabel `format_buku`
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
('d5a2701a-f5d4-41f8-bacf-b40e778d5936', '10 x 15', '2022-09-08 08:17:50', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
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
-- Struktur dari tabel `imprint`
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
-- Dumping data untuk tabel `imprint`
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
('8b102fb5-20d1-4a5c-89db-3a39dd01fd58', 'Andi', '2022-08-08 09:42:39', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
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
-- Struktur dari tabel `jabatan`
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
-- Dumping data untuk tabel `jabatan`
--

INSERT INTO `jabatan` (`id`, `nama`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
('02c4dfedf83a43cd89ba0a83de8445ed', 'Staff', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-03-31 08:34:02', NULL, NULL),
('58682ac96ef74d2187ae6bb6b87e3686', 'Direktur Keuangan', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-08-02 03:32:28', NULL, NULL),
('5cacae63f0f94a91931ba4779879eab1', 'Asisten Manager', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-03-31 08:34:30', NULL, NULL),
('765e0ac17c8e4cabae01582b06da410e', 'Manager', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-03-31 08:34:11', NULL, NULL),
('803d36a2d66442499e5411e536fd4201', 'Manajer Stok', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-08-02 03:33:55', NULL, NULL),
('8badd0f20e2f434bb7d7a067e93d0e2e', 'Direktur Operasional', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-08-02 03:32:45', NULL, NULL),
('9a51e94ea42c4f26ab1143468286013f', 'Manajer Penerbitan', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-08-02 03:35:28', NULL, NULL),
('a39f467d051f49db9508778f643fdd96', 'Direktur Utama', 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-03-31 08:34:45', '2022-08-02 03:29:01', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mm_select`
--

CREATE TABLE `mm_select` (
  `id` varchar(36) DEFAULT NULL,
  `keyword` varchar(50) NOT NULL,
  `options` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `mm_select`
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
-- Struktur dari tabel `notif`
--

CREATE TABLE `notif` (
  `id` varchar(36) NOT NULL,
  `section` enum('Penerbitan','Produksi') NOT NULL,
  `type` enum('Penilaian Naskah','Timeline Naskah','Persetujuan Order Buku Baru','Persetujuan Order Cetak Ulang Revisi','Persetujuan Order Cetak Ulang','Proses Produksi Order Cetak','Persetujuan Order E-Book','Proses Produksi Order E-Book') NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `raw_data` text,
  `permission_id` varchar(36) NOT NULL,
  `form_id` varchar(36) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expired` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `notif_detail`
--

CREATE TABLE `notif_detail` (
  `notif_id` varchar(36) NOT NULL,
  `user_id` varchar(36) NOT NULL,
  `seen` enum('1','0') NOT NULL DEFAULT '0' COMMENT 'if seen(1) update(null)::: updated by naskah',
  `raw_data` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `penerbitan_m_kelompok_buku`
--

CREATE TABLE `penerbitan_m_kelompok_buku` (
  `id` varchar(36) NOT NULL,
  `kode` varchar(6) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `penerbitan_m_kelompok_buku`
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
('87cf57e453044cb890784aacc59461f6', 'KB001', 'Aplikasi Office', 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-02-23 03:27:48', '2022-09-07 07:24:01', NULL),
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
('b0ea25ec-b366-4de3-b02b-c0a6488557eb', 'KB120', 'coba2', 'be8d42fa88a14406ac201974963d9c1b', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-09-07 04:42:01', '2022-09-07 07:12:15', '2022-09-07 07:12:15'),
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
('d96347a9bc704888a2a7a72b3d4b97a6', 'KB010', 'Open Source', 'be8d42fa88a14406ac201974963d9c1b', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-02-23 03:27:48', '2022-09-07 07:05:27', '2022-09-07 07:05:27'),
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
('f4e827d5-4a01-4c62-ac16-64545c9d3b4f', 'KB119', 'cobacoba', 'be8d42fa88a14406ac201974963d9c1b', NULL, 'be8d42fa88a14406ac201974963d9c1b', '2022-09-07 04:41:31', '2022-09-07 07:17:15', '2022-09-07 07:17:15'),
('f4f977eb93654039a958e10b29e99bbf', 'KB038', 'Fotografi', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('f9b757202fa444d8b711521412f09139', 'KB036', 'Law', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('fa853236a45c467a9d0c932c18322de3', 'KB094', 'Referensi', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('fbda0b3fa71049f0aebd50101c5a21cc', 'KB058', 'Social Sciences', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('fc8ffd28677d4688bccb687606da3b16', 'KB090', 'Khpn Kristen/Pria', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('fee5ad06baaf469cacbfb51a093ab2b8', 'KB024', 'Business & Econ.', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL),
('ffcca04ba0894989b011c72ecf70591e', 'KB099', 'Khdp Krist / Insp', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-02-23 03:27:48', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penerbitan_naskah`
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
  `keterangan` text,
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
  `bukti_email_penulis` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `penerbitan_naskah`
--

INSERT INTO `penerbitan_naskah` (`id`, `kode`, `judul_asli`, `tanggal_masuk_naskah`, `email`, `kelompok_buku_id`, `jalur_buku`, `tentang_penulis`, `hard_copy`, `soft_copy`, `cdqr_code`, `keterangan`, `pic_prodev`, `penilaian_naskah`, `date_pic_prodev`, `penilaian_prodev`, `penilaian_editset`, `penilaian_pemasaran`, `penilaian_penerbitan`, `penilaian_direksi`, `selesai_penilaian`, `selesai_penilaian_tgl`, `status_penilaian`, `bukti_email_penulis`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
('1a71b26e23094c39a62f6c8d62ec6665', 'NA20220722001', 'Lorem Ipsum Reguler', '2022-07-06', NULL, '0242257c619e4f0f85f0a2d872359e95', 'Reguler', '1', '0', '1', '0', NULL, 'e4ddf4d7c2b84cb69647f4dd63f9dbc2', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Reguler', NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-07-22 02:32:45', NULL, NULL),
('7efc9508128a4bec9ba4bd1140085ca3', 'NA20220909003', 'cobacoba', '2022-09-09', 'ferdy@gmail.com', '0d6b22630e41467a85f2764630b81033', 'SMK/NonSMK', '1', '1', '1', '1', NULL, '4fc80f443bfb4969b9a0272d9be08ef2', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-09-09 03:21:20', NULL, NULL),
('b9614d8eb16a40cb871a589f23507e19', 'NA20220727002', 'coba', '2022-07-25', 'fdasdasda@gmail.com', '0242257c619e4f0f85f0a2d872359e95', 'MoU-Reguler', '0', '1', '0', '1', NULL, '4fc80f443bfb4969b9a0272d9be08ef2', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-07-27 08:23:22', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penerbitan_naskah_files`
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
-- Dumping data untuk tabel `penerbitan_naskah_files`
--

INSERT INTO `penerbitan_naskah_files` (`id`, `naskah_id`, `kategori`, `file`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
('064d3933431f496f96f94298e53fc14c', '1a71b26e23094c39a62f6c8d62ec6665', 'File Tambahan Naskah', 'g2fL1xUNZVmaZ3xxmMFXHwUrcNb32I6Ipi0Ofu50.rar', NULL, NULL, NULL, '2022-07-22 02:32:45', NULL, NULL),
('5892d56a88f64a528e0843ee47d3f2b7', 'b9614d8eb16a40cb871a589f23507e19', 'File Naskah Asli', 'fdfdYE9LTqAJ8kOlTwhEBPvXvmV12ln6FCZmmcuh.pdf', NULL, NULL, NULL, '2022-07-27 08:23:22', NULL, NULL),
('7276bbb0a725401c8c7725f154d733b8', '1a71b26e23094c39a62f6c8d62ec6665', 'File Naskah Asli', 'FgEkTY5YjgOz4loGbbDfKemiaG9FH29rZUGnpwUj.pdf', NULL, NULL, NULL, '2022-07-22 02:32:45', NULL, NULL),
('832df51ad41c486e8e1bf7c35c827da5', '7efc9508128a4bec9ba4bd1140085ca3', 'File Naskah Asli', 'CQXRRAZmUiYxMHu5BVDur0htW1LsEYLTuKsgeEPe.pdf', NULL, NULL, NULL, '2022-09-09 03:21:20', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penerbitan_naskah_penulis`
--

CREATE TABLE `penerbitan_naskah_penulis` (
  `penulis_id` varchar(36) NOT NULL,
  `naskah_id` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `penerbitan_naskah_penulis`
--

INSERT INTO `penerbitan_naskah_penulis` (`penulis_id`, `naskah_id`) VALUES
('90d2a75954c0442bb9f6b2578e83fc8b', '1a71b26e23094c39a62f6c8d62ec6665'),
('353a88a472d5438dacf5e7fb7d6271e3', 'b9614d8eb16a40cb871a589f23507e19'),
('90d2a75954c0442bb9f6b2578e83fc8b', '7efc9508128a4bec9ba4bd1140085ca3'),
('353a88a472d5438dacf5e7fb7d6271e3', '7efc9508128a4bec9ba4bd1140085ca3'),
('0297e7cb197647a885098e96716bab0d', '7efc9508128a4bec9ba4bd1140085ca3');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penerbitan_penulis`
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
  `tentang_penulis` text,
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
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `penerbitan_penulis`
--

INSERT INTO `penerbitan_penulis` (`id`, `nama`, `tanggal_lahir`, `tempat_lahir`, `kewarganegaraan`, `alamat_domisili`, `ponsel_domisili`, `telepon_domisili`, `email`, `nama_kantor`, `jabatan_dikantor`, `alamat_kantor`, `telepon_kantor`, `sosmed_fb`, `sosmed_ig`, `sosmed_tw`, `tentang_penulis`, `file_tentang_penulis`, `foto_penulis`, `bank`, `bank_atasnama`, `no_rekening`, `npwp`, `ktp`, `scan_npwp`, `scan_ktp`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
('0297e7cb197647a885098e96716bab0d', 'Asus', '2022-04-27', 'Jakarta', 'WNI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '9jJjMRWN8LNES0suQ6dBmBw4YilCEoP9Uv1eBxMG.pdf', 'P6AF18Ogv4xFDg39MdfoSTtSdU3PGw8qEdT2T9vs.png', NULL, NULL, NULL, NULL, NULL, 'oZimqdtfM3zdpXgb13LnyHU4OAEP5qZHIj1ibUnJ.png', 'hfDa3nNmQK7VPkUWIQip2vvp4g0VZlQkcmgSbW1F.png', 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-05-30 04:32:16', '2022-05-30 04:34:17', NULL),
('353a88a472d5438dacf5e7fb7d6271e3', 'Yohanes Hendra', '1994-03-01', 'Yogyakarta', 'WNI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'default.jpg', NULL, NULL, NULL, NULL, NULL, 'c1E1DP4PJ9US7FHzrwApeDJIac7EDK5aWWfvh4zN.png', NULL, 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-05-30 03:50:38', '2022-05-30 04:18:29', NULL),
('90d2a75954c0442bb9f6b2578e83fc8b', 'Lorem Ipsum', '1985-03-15', 'Surabaya', 'WNI', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry', '085743172451', '0274551155', 'lorem@gamil.com', 'Andi Offset', 'Staff', 'Jl Beo, Condong catur. Sleman', '0274558787', 'Lorem Ipsum', '@loremipsum', '@loremipsum', '<div style=\"text-align: justify;\">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</div>', 'JAQ4X0wxDEegR06P3c7tJPPWZeB4Wmgs0mGD3Ggu.pdf', 'r6OlOVcNRmaPSGSOtHyEhU3VhVEoanXtxfKWANdJ.jpg', 'CDB', 'Lorem Ipsum', '88123321', '4777123890', '3471031704900005', 'knov8tsqXdnirPjRblMapTU8FQqOtzNao89v5sEa.png', 'ISxxQm249EIWbeOdGBdFLG6KnpyF9GD3t0lA4VL4.png', 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, '2022-03-18 06:26:59', '2022-05-30 04:22:48', NULL),
('986a4833c1c2406193299452bf7c3e91', 'Test Image', '2022-06-02', 'Yogyakarta', 'WNI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'p6TErMkr13ZbR3Q0gexBtDmUDg25nZgHpYukXAwJ.jpg', NULL, NULL, NULL, NULL, NULL, 'OepSJwJAbAUCrXbyTShowYtgAEfaLLNXWEs5D0EZ.jpg', 'ntf0eB938LLZvnPnp50wqtuyuLTKxpDSSCxtEtsN.jpg', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-06-03 06:42:21', NULL, NULL),
('d0789ba314354de4926b549233008ef6', 'JK Rowling Door', '1994-03-02', 'Inggris', 'WNA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<p><strong style=\"margin: 0px; padding: 0px; font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">Lorem Ipsum</strong><span style=\"font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span><br></p>', NULL, 'PXzUEK5uHNaUSQxu842S09ZL0Neub4XlLRTuh8v6.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-05-30 02:09:52', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penerbitan_pn_direksi`
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
-- Dumping data untuk tabel `penerbitan_pn_direksi`
--

INSERT INTO `penerbitan_pn_direksi` (`id`, `naskah_id`, `judul_final`, `sub_judul_final`, `keputusan_final`, `catatan`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
('11cf826afbf74a56ba83f07a030643b6', '1a71b26e23094c39a62f6c8d62ec6665', '', '', 'Reguler', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', '37aee684a9e447a6bef36cbf08222d5d', NULL, '2022-07-22 03:15:41', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penerbitan_pn_editor_setter`
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
-- Struktur dari tabel `penerbitan_pn_mm`
--

CREATE TABLE `penerbitan_pn_mm` (
  `keyword` varchar(255) NOT NULL COMMENT 'lowercase',
  `id` varchar(36) DEFAULT NULL,
  `options` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `penerbitan_pn_mm`
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
-- Struktur dari tabel `penerbitan_pn_pemasaran`
--

CREATE TABLE `penerbitan_pn_pemasaran` (
  `id` varchar(36) NOT NULL,
  `naskah_id` varchar(36) NOT NULL,
  `pic` enum('M','D') NOT NULL COMMENT 'M:Manager|D:Direksi',
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
-- Dumping data untuk tabel `penerbitan_pn_pemasaran`
--

INSERT INTO `penerbitan_pn_pemasaran` (`id`, `naskah_id`, `pic`, `prospek_pasar`, `potensi_dana`, `ds_tb`, `pilar`, `potensi`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
('1de7a872e6174a4b8a8ab2753fbe36bd', '1a71b26e23094c39a62f6c8d62ec6665', 'M', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '[\"TB\"]', '[\"MOU KAMPUS\",\"BUKU SMK\",\"BUKU PAK\",\"BUKU HET SD\"]', NULL, '5090c6d9e50449449b2edf23db64cdf5', NULL, '2022-07-22 02:33:54', NULL),
('4149aab55ed7466e88feb5645d03b06c', '1a71b26e23094c39a62f6c8d62ec6665', 'M', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '[\"DS\"]', '[\"MOU KAMPUS\",\"MOU PERORANGAN BUKU UMUM\",\"MOU PERORANGAN BUKU ROHANI\"]', NULL, 'ba7f70e69bf74fc29fe3154980f5f53e', NULL, '2022-07-22 02:46:53', NULL),
('a614541de4cf40b78e2d127ec4e06767', '1a71b26e23094c39a62f6c8d62ec6665', 'D', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', NULL, 'ef171a1a7bba4b81abdfe10ef8c6c0f8', NULL, '2022-07-22 03:11:56', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penerbitan_pn_penerbitan`
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
-- Dumping data untuk tabel `penerbitan_pn_penerbitan`
--

INSERT INTO `penerbitan_pn_penerbitan` (`id`, `naskah_id`, `penilaian_umum`, `saran`, `catatan`, `potensi`, `tanggapan_usulan_judul`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
('ffaed342512f465c8dc9da01a1bd5da0', '1a71b26e23094c39a62f6c8d62ec6665', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Diterima', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum', NULL, '4fc80f443bfb4969b9a0272d9be08ef2', NULL, '2022-07-22 03:15:14', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penerbitan_pn_prodev`
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
-- Dumping data untuk tabel `penerbitan_pn_prodev`
--

INSERT INTO `penerbitan_pn_prodev` (`id`, `naskah_id`, `sistematika`, `nilai_keilmuan`, `kelompok_buku_id`, `isi_materi`, `sasaran_keilmuan`, `sasaran_pasar`, `sumber_dana_pasar`, `skala_penilaian`, `saran`, `potensi`, `usulan_judul`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
('28cdc37c9cec4db08204f46bf39ce2b8', '1a71b26e23094c39a62f6c8d62ec6665', 'Baik', 'Cukup', '0d6b22630e41467a85f2764630b81033', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'Lorem Ipsum is simply dummy text of the printing', 'Baik', 'Diterima', 'enim ipsam voluptatem quia voluptas sit', NULL, 'e4ddf4d7c2b84cb69647f4dd63f9dbc2', NULL, '2022-07-22 03:13:39', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penerbitan_pn_stts`
--

CREATE TABLE `penerbitan_pn_stts` (
  `id` varchar(36) NOT NULL,
  `naskah_id` varchar(36) NOT NULL,
  `tgl_input_admin` datetime DEFAULT CURRENT_TIMESTAMP,
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
-- Dumping data untuk tabel `penerbitan_pn_stts`
--

INSERT INTO `penerbitan_pn_stts` (`id`, `naskah_id`, `tgl_input_admin`, `tgl_naskah_masuk`, `tgl_pn_prodev`, `tgl_pn_editor`, `tgl_pn_setter`, `tgl_pn_m_pemasaran`, `tgl_pn_m_penerbitan`, `tgl_pn_d_pemasaran`, `tgl_pn_direksi`, `tgl_pn_selesai`) VALUES
('7720778b3fbe4985a6caa389eb9542cf', '7efc9508128a4bec9ba4bd1140085ca3', '2022-09-09 10:21:20', '2022-09-09 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-09 10:21:20'),
('930b73d33a8445f7b933643ad967aa5f', 'b9614d8eb16a40cb871a589f23507e19', '2022-07-27 15:23:22', '2022-07-25 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('9e81321a28cb4d3b91e9c39541f62547', '1a71b26e23094c39a62f6c8d62ec6665', '2022-07-22 09:32:45', '2022-07-06 00:00:00', '2022-07-22 10:13:39', NULL, NULL, '2022-07-22 09:46:53', '2022-07-22 10:15:14', '2022-07-22 10:11:56', '2022-07-22 10:15:41', '2022-07-22 10:15:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `permissions`
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
-- Dumping data untuk tabel `permissions`
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
('5d793b19c75046b9a4d75d067e8e33b2', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/penilaian', 'Update', 'naskah-pn-editor', 'Penilaian Editor'),
('60b2f2ca-16f5-11ed-ae5c-1078d2a38ee5', 'bc5eb3aa02394dcca7692764e1328cee', '', 'Update', 'ubah-data-imprint', 'Ubah Data Imprint'),
('6903e82e7e94478f87df3cf80de6b587', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah', 'Read', 'lihat-naskah', 'Lihat Data Naskah'),
('6b4e3b36783d4a488101da7639c40de0', '71d6b5671ebb4e128215fccc458fbf09', '', 'Read', 'lihat-deskripsi-cover', 'Lihat Deskripsi Cover'),
('78712deb909d4d88af7f098c0fcf6857', 'bfb8b970f85c4a42bac1dc56181dc96b', '', 'Decline', 'persetujuan-pending', 'Persetujuan Pending'),
('808ab7987c9b4f0ab025b1b9e3ed1d43', '92463f9e96394c19a979a3290fde5745', '', 'Read', 'lihat-editing', 'Buat Editing'),
('8791f143a90e42e2a4d1d0d6b1254bad', '30d0f70435904ad5b4e7cbfeb98fc021', 'penerbitan/naskah/penilaian', 'Update', 'naskah-pn-direksi', 'Penilaian Direksi'),
('89bc4b0ef1dd4306a3217cbf24551071', 'fb6c8f0dcc9e43199642f08a0fe1fd56', 'penerbitan/penulis/hapus-penulis', 'Delete', 'hapus-data-penulis', 'Hapus Data Penulis'),
('8a6141a082554335a2137c90f9fa0a5e', '31a0187d88d94ddc83db4b71524b5b2d', '', 'Create', 'buat-kelompok-buku', 'Buat Kelompok Buku'),
('8baa9163-16f5-11ed-ae5c-1078d2a38ee5', 'bc5eb3aa02394dcca7692764e1328cee', '', 'Delete', 'hapus-data-imprint', 'Hapus Data Imprint'),
('8d9b1da4234f46eb858e1ea490da6348', '583a723cf036449d80d3742dcf695e38', 'penerbitan/naskah/timeline', 'Update', 'timeline-naskah-update-date', 'Ubah Tanggal Timeline Naskah'),
('8de7d59a74f345a5bcab20ec43376299', '30d0f70435904ad5b4e7cbfeb98fc021', '', 'Read', 'notifikasi-email-penulis', 'Notifikasi kirim Email Ke Penulis'),
('8f53727c763849aab80c1513505decf8', 'bfb8b970f85c4a42bac1dc56181dc96b', '', 'Update', 'update-produksi-ebook', 'Ubah Order E-book'),
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
-- Struktur dari tabel `platform_digital_ebook`
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
-- Dumping data untuk tabel `platform_digital_ebook`
--

INSERT INTO `platform_digital_ebook` (`id`, `nama`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
('0258637f-3511-4a9f-8b74-cd3fc5f3d587', 'Moco', '2022-09-06 04:12:46', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('04f57a2a-2d2b-4826-b49c-019a91e35620', 'Indopustaka', '2022-09-06 04:15:29', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('0bba7f98-5efd-4044-9e25-49193bbbd556', 'Gramedia', '2022-09-06 04:13:31', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('35a05fdc-50cf-4778-ad40-5d1872e830bd', 'Bahanaflix', '2022-09-06 04:15:17', 'be8d42fa88a14406ac201974963d9c1b', '2022-09-06 04:32:17', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL),
('e15a923a-c839-43fb-9e28-14e577ec7527', 'Esentral', '2022-09-06 04:13:42', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL),
('f658566d-2bb3-4b6d-9a3f-bbcfa78d5d77', 'Google Book', '2022-09-06 04:13:21', 'be8d42fa88a14406ac201974963d9c1b', '2022-09-06 08:14:00', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `produksi_order_cetak`
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
  `platform_digital` text COMMENT 'array',
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
  `buku_contoh` text,
  `spp` varchar(30) DEFAULT NULL,
  `keterangan` text,
  `perlengkapan` text,
  `tgl_permintaan_jadi` date DEFAULT NULL,
  `status_penyetujuan` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1 = Pending ~~\r\n2 = Disetujui ~~\r\n3 = Ditolak ~~',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(36) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `produksi_order_ebook`
--

CREATE TABLE `produksi_order_ebook` (
  `id` char(36) NOT NULL,
  `kode_order` varchar(9) NOT NULL,
  `tipe_order` enum('1','2') DEFAULT NULL COMMENT '1= Umum, 2= Rohani',
  `judul_buku` varchar(50) DEFAULT NULL,
  `sub_judul` varchar(100) DEFAULT NULL,
  `platform_digital` text COMMENT 'array',
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
  `perlengkapan` text,
  `keterangan` text,
  `tgl_upload` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(36) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `produksi_order_ebook`
--

INSERT INTO `produksi_order_ebook` (`id`, `kode_order`, `tipe_order`, `judul_buku`, `sub_judul`, `platform_digital`, `penulis`, `eisbn`, `penerbit`, `imprint`, `edisi_cetakan`, `jumlah_halaman`, `kelompok_buku`, `tahun_terbit`, `status_buku`, `spp`, `perlengkapan`, `keterangan`, `tgl_upload`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
('62b39571-5192-496b-97df-d7663f2681dd', 'E22-1000', '1', 'Di Balik Mata Kaca', 'Sebuah Pengalaman', '[\"Moco\",\"Indopustaka\",\"Bahanaflix\",\"Esentral\",\"Google Book\"]', 'Yohanes Hendra', '2453442341234', 'Andi', 'PBMR Andi', 'vii/1', 'viii + 325', 'ArchitectPhotop', 2022, '2', NULL, NULL, NULL, '2022-09-14 13:49:49', '2022-09-06 06:49:49', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `produksi_penyetujuan_order_cetak`
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
  `ket_pending` text,
  `pending_sampai` date DEFAULT NULL,
  `status_general` enum('Proses','Pending','Selesai') NOT NULL DEFAULT 'Proses',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `produksi_penyetujuan_order_ebook`
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
  `ket_pending` text,
  `pending_sampai` date DEFAULT NULL,
  `status_general` enum('Proses','Pending','Selesai') DEFAULT 'Proses',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `produksi_penyetujuan_order_ebook`
--

INSERT INTO `produksi_penyetujuan_order_ebook` (`id`, `produksi_order_ebook_id`, `m_penerbitan`, `d_operasional`, `d_keuangan`, `d_utama`, `m_penerbitan_act`, `d_operasional_act`, `d_keuangan_act`, `d_utama_act`, `tgl_upload_history`, `ket_pending`, `pending_sampai`, `status_general`, `created_at`, `updated_at`) VALUES
('370ec5ac-8280-4bc9-9969-03191d57db92', '62b39571-5192-496b-97df-d7663f2681dd', '4fc80f443bfb4969b9a0272d9be08ef2', 'fd035809e2c045098770a7e9dfccddf9', 'c62658af71bf4c5692b041c9384d068b', '37aee684a9e447a6bef36cbf08222d5d', '1', '1', '1', '1', NULL, NULL, NULL, 'Proses', '2022-09-06 06:49:49', '2022-09-06 06:49:52'),
('7c559112-bce4-489f-9972-427cdf81639c', '329fb5c2-531c-4f61-a6cd-4aaf812f76a9', '4fc80f443bfb4969b9a0272d9be08ef2', 'fd035809e2c045098770a7e9dfccddf9', 'c62658af71bf4c5692b041c9384d068b', '37aee684a9e447a6bef36cbf08222d5d', '1', '1', '1', '1', NULL, NULL, NULL, 'Proses', '2022-09-06 06:24:12', '2022-09-06 06:25:21'),
('d1bbaf8f-ad71-4b01-8fef-11c9b7308d59', 'd9079338-1709-4ac5-b845-15314c079e9d', '4fc80f443bfb4969b9a0272d9be08ef2', 'fd035809e2c045098770a7e9dfccddf9', 'c62658af71bf4c5692b041c9384d068b', '37aee684a9e447a6bef36cbf08222d5d', '1', '1', '1', '1', NULL, NULL, NULL, 'Proses', '2022-09-06 04:58:55', '2022-09-06 04:59:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `proses_ebook_multimedia`
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
-- Struktur dari tabel `proses_produksi_cetak`
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
-- Struktur dari tabel `timeline`
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
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `timeline_sub`
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
-- Struktur dari tabel `users`
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
  `created_by` varchar(36) DEFAULT NULL,
  `updated_by` varchar(36) DEFAULT NULL,
  `deleted_by` varchar(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `avatar`, `nama`, `tanggal_lahir`, `tempat_lahir`, `telepon`, `alamat`, `cabang_id`, `divisi_id`, `jabatan_id`, `status`, `super_admin`, `created_by`, `updated_by`, `deleted_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
('37aee684a9e447a6bef36cbf08222d5d', 'dirut@gmail.com', '$2y$10$fPZp4mpZP5VufCqUKVUFB.ykQ02vDbzA/fHbymBAE8Emkyhj8DpmK', 'Ii0LnFZo5WfCrLvcEO49sC4h8GLHARCBNWfByYWX.jpg', 'Yehezkiel A', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '646a4663aea14eb9915b718cbcc5e33b', 'a39f467d051f49db9508778f643fdd96', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-08-02 03:38:40', NULL),
('3d8ae658b9c049f6b9b7633a6c0ef4f2', 'lorem@gmail.com', '$2y$10$piaLD4atKsjQtc9HRIEd/uVfCzShDpgrF911hY9svit0A9WOcyJJ.', 'default.jpg', 'lorem', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'd1946a0d285944488032d2dcd1a7882b', '02c4dfedf83a43cd89ba0a83de8445ed', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('4c45b120510e43a08961ec4a712c4ccb', 'userdua@gmail.com', '$2y$10$Tj1e8PsLZKBGEFYjdSNuNOlnUWMXViK9gSAYlUtxAaddKLkOOQCEO', 'default.jpg', 'Admin Dua', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'd1946a0d285944488032d2dcd1a7882b', '02c4dfedf83a43cd89ba0a83de8445ed', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-05-19 01:18:17', NULL),
('4fc80f443bfb4969b9a0272d9be08ef2', 'penerbitan@gmail.com', '$2y$10$otYXOmZAOToGukQ.t4na/u/czk5YiiX1S55/yUUUSoC/f30wkg5wi', 'default.jpg', 'Tjahjono Tri W', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '646a4663aea14eb9915b718cbcc5e33b', '9a51e94ea42c4f26ab1143468286013f', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-08-02 03:49:00', NULL),
('5090c6d9e50449449b2edf23db64cdf5', 'mpemasaran@gmail.com', '$2y$10$AYprSWSQaMLIDDE5e/GAoumsZgIp8uvyRxL/aUw10xSXYyNRIEOyW', 'default.jpg', 'Manager Pemasran', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'df719b3e9de442b3ba21b1b414887ec7', '5cacae63f0f94a91931ba4779879eab1', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('5960036aa4fe43c582015d8d812e901c', 'usersatu@gmail.com', '$2y$10$IaVZTOnvB6cj3atB7rDZYOzOPCTSGfJIx8KI0BPReePQEb15YvwO.', 'default.jpg', 'User Satu', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '821ac200b1de45fdad7d533ce0190492', '02c4dfedf83a43cd89ba0a83de8445ed', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('59cb3d41a58141c3a3518a2e78c84221', 'manajerstok@gmail.com', '$2y$10$/Ayl2zUmD0PImMjeSt5Nle63Bj0ZH3HdRKdTHWpEcxKZ0Ak449USC', 'default.jpg', 'Astito Nining P', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '821ac200b1de45fdad7d533ce0190492', '803d36a2d66442499e5411e536fd4201', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('5c6b749331b847e38ee5f8b5b9e2f159', 'm.stock@gmail.com', '$2y$10$2/Ilzsvw8mbST51WjAMHnOeZFql1KW5U2kt1RscGoj/HEvHNMrHby', 'default.jpg', 'Manajer Stok', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'd1946a0d285944488032d2dcd1a7882b', '765e0ac17c8e4cabae01582b06da410e', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-08-02 03:46:30'),
('8ce9a0324f524e8fba4f67e052f7c4c4', 'prodevdua@gmail.com', '$2y$10$o0huqa2lV9s8sdp0362JWu8cdCOp8ZQ4It58GLCxfpQPcIoB3pV/K', 'default.jpg', 'Prodev Dua', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '821ac200b1de45fdad7d533ce0190492', '02c4dfedf83a43cd89ba0a83de8445ed', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('909c0ff670d94102a8cc33d8fcae8993', 'editor@gmail.com', '$2y$10$HzjCB.XrexFHRzgj1NK77OpS50Gb9BIsIyzcVMzt1grt/ZMT6JDTW', 'default.jpg', 'Editor', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '821ac200b1de45fdad7d533ce0190492', '02c4dfedf83a43cd89ba0a83de8445ed', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('ba7f70e69bf74fc29fe3154980f5f53e', 'hendra@gmail.com', '$2y$10$ZcWsK4B9Z5XmHzDnsXRCG.zXbP3TnNcV233iObcIQWqO8M2eT8YSW', 'Tn5fjMQOA1BrHKzudsbtj9U9HxC01tvKNTFDCGR8.jpg', 'hendra', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '646a4663aea14eb9915b718cbcc5e33b', 'a39f467d051f49db9508778f643fdd96', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', 'ba7f70e69bf74fc29fe3154980f5f53e', NULL, NULL, '2022-05-19 04:52:09', NULL),
('be8d42fa88a14406ac201974963d9c1b', 'admin@gmail.com', '$2y$10$JfdI4rvmfegdd97t9xvgB.YWs5PQhfN4IlkOzgLoWrDeZaYDvL6Lu', 'default.jpg', 'Super Admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '1', NULL, NULL, NULL, NULL, NULL, NULL),
('c62658af71bf4c5692b041c9384d068b', 'dirke@gmail.com', '$2y$10$xRUfhTDRlq6o3e.TTHYBIeO9.j/HW/V3kzdiq/Tnwdm8zfqaz1imi', 'default.jpg', 'Dra. Lanita H', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '646a4663aea14eb9915b718cbcc5e33b', '58682ac96ef74d2187ae6bb6b87e3686', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('cf9361d1274c44cbbb42c8d6383d0dad', 'mstock@gmail.com', '$2y$10$kTG9kuumxpdrAkMTG9SS1.bmt0Aflp88S7BqKNv3vmShiyvE.Nbry', 'default.jpg', 'Manajer Stok', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'd1946a0d285944488032d2dcd1a7882b', '765e0ac17c8e4cabae01582b06da410e', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, '2022-07-29 06:43:41'),
('e4ddf4d7c2b84cb69647f4dd63f9dbc2', 'prodevsatu@gmail.com', '$2y$10$fz6rEqhT/ZsYfZfpv2As0elZCxq7IUlDTaGtLus3wVdm0fqg/f5Au', 'default.jpg', 'Prodev Satu', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '821ac200b1de45fdad7d533ce0190492', '02c4dfedf83a43cd89ba0a83de8445ed', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('ec563464e9214fcbae2a0cc805ec2577', 'setter@gmail.com', '$2y$10$kPH1u6iWvU/HKYt020x2H.dFGy7x6u5/U6qjcIS6qi6kMfj8UTkau', 'default.jpg', 'Setter', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '821ac200b1de45fdad7d533ce0190492', '02c4dfedf83a43cd89ba0a83de8445ed', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('ef171a1a7bba4b81abdfe10ef8c6c0f8', 'dpemasaran@gmail.com', '$2y$10$qsv/NY6w9Y4w4.JATrFRFOS.UFpPINzoUgHwPf5RZugIy20hXH342', 'default.jpg', 'Direktur Pemasaran', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', 'df719b3e9de442b3ba21b1b414887ec7', 'a39f467d051f49db9508778f643fdd96', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL),
('fd035809e2c045098770a7e9dfccddf9', 'dirop@gmail.com', '$2y$10$FfHSdH./FQoCIHKkeKkpQeMDeuC/jidSYseE1c45StbINnwT6ezB6', 'default.jpg', 'Adi Kristianto', NULL, NULL, NULL, NULL, 'ada2962f70ce45fd8b930f1babafeba8', '646a4663aea14eb9915b718cbcc5e33b', '8badd0f20e2f434bb7d7a067e93d0e2e', '1', '0', 'be8d42fa88a14406ac201974963d9c1b', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_permission`
--

CREATE TABLE `user_permission` (
  `user_id` varchar(36) NOT NULL,
  `permission_id` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user_permission`
--

INSERT INTO `user_permission` (`user_id`, `permission_id`) VALUES
('8ce9a0324f524e8fba4f67e052f7c4c4', '6903e82e7e94478f87df3cf80de6b587'),
('8ce9a0324f524e8fba4f67e052f7c4c4', 'ebca07da8aad42c4aee304e3a6b81001'),
('5960036aa4fe43c582015d8d812e901c', '5d793b19c75046b9a4d75d067e8e33b2'),
('5960036aa4fe43c582015d8d812e901c', '6903e82e7e94478f87df3cf80de6b587'),
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
('3d8ae658b9c049f6b9b7633a6c0ef4f2', '6b95b4e041e04d61a91422fe3d06fd8d'),
('3d8ae658b9c049f6b9b7633a6c0ef4f2', 'c64802952e504f4ab25a6b1241232f85'),
('37aee684a9e447a6bef36cbf08222d5d', '6903e82e7e94478f87df3cf80de6b587'),
('37aee684a9e447a6bef36cbf08222d5d', '8791f143a90e42e2a4d1d0d6b1254bad'),
('37aee684a9e447a6bef36cbf08222d5d', '8d9b1da4234f46eb858e1ea490da6348'),
('37aee684a9e447a6bef36cbf08222d5d', '09179170e6e643eca66b282e2ffae1f8'),
('37aee684a9e447a6bef36cbf08222d5d', '9b4e52c30f974844ac7a050000a0ee6a'),
('37aee684a9e447a6bef36cbf08222d5d', 'c64802952e504f4ab25a6b1241232f85'),
('37aee684a9e447a6bef36cbf08222d5d', '171e6210418440a8bf4d689841d0f32c'),
('37aee684a9e447a6bef36cbf08222d5d', '78712deb909d4d88af7f098c0fcf6857'),
('37aee684a9e447a6bef36cbf08222d5d', '9d69d18ff5184804990bc21cb1005ab7'),
('4fc80f443bfb4969b9a0272d9be08ef2', '12b852d92d284ab5a654c26e8856fffd'),
('4fc80f443bfb4969b9a0272d9be08ef2', '6903e82e7e94478f87df3cf80de6b587'),
('4fc80f443bfb4969b9a0272d9be08ef2', '09179170e6e643eca66b282e2ffae1f8'),
('4fc80f443bfb4969b9a0272d9be08ef2', 'c64802952e504f4ab25a6b1241232f85'),
('4fc80f443bfb4969b9a0272d9be08ef2', '171e6210418440a8bf4d689841d0f32c'),
('4fc80f443bfb4969b9a0272d9be08ef2', '9d69d18ff5184804990bc21cb1005ab7'),
('59cb3d41a58141c3a3518a2e78c84221', '09179170e6e643eca66b282e2ffae1f8'),
('59cb3d41a58141c3a3518a2e78c84221', 'c64802952e504f4ab25a6b1241232f85'),
('c62658af71bf4c5692b041c9384d068b', '09179170e6e643eca66b282e2ffae1f8'),
('c62658af71bf4c5692b041c9384d068b', '9b4e52c30f974844ac7a050000a0ee6a'),
('c62658af71bf4c5692b041c9384d068b', 'c64802952e504f4ab25a6b1241232f85'),
('c62658af71bf4c5692b041c9384d068b', '171e6210418440a8bf4d689841d0f32c'),
('c62658af71bf4c5692b041c9384d068b', '78712deb909d4d88af7f098c0fcf6857'),
('c62658af71bf4c5692b041c9384d068b', '9d69d18ff5184804990bc21cb1005ab7'),
('fd035809e2c045098770a7e9dfccddf9', '09179170e6e643eca66b282e2ffae1f8'),
('fd035809e2c045098770a7e9dfccddf9', '9b4e52c30f974844ac7a050000a0ee6a'),
('fd035809e2c045098770a7e9dfccddf9', 'c64802952e504f4ab25a6b1241232f85'),
('fd035809e2c045098770a7e9dfccddf9', '171e6210418440a8bf4d689841d0f32c'),
('fd035809e2c045098770a7e9dfccddf9', '78712deb909d4d88af7f098c0fcf6857'),
('fd035809e2c045098770a7e9dfccddf9', '9d69d18ff5184804990bc21cb1005ab7'),
('be8d42fa88a14406ac201974963d9c1b', '3a70433b-16f5-11ed-ae5c-1078d2a38ee5'),
('be8d42fa88a14406ac201974963d9c1b', '60b2f2ca-16f5-11ed-ae5c-1078d2a38ee5'),
('be8d42fa88a14406ac201974963d9c1b', '8baa9163-16f5-11ed-ae5c-1078d2a38ee5'),
('be8d42fa88a14406ac201974963d9c1b', 'f76c69fb-16f4-11ed-ae5c-1078d2a38ee5'),
('be8d42fa88a14406ac201974963d9c1b', '068adb0171304c628b267874004d7e8c'),
('be8d42fa88a14406ac201974963d9c1b', '2b6032ef8a73463ba2c761c86be5ed5d'),
('be8d42fa88a14406ac201974963d9c1b', 'bc6b9c821e3f42ccb57532930c8d92be'),
('be8d42fa88a14406ac201974963d9c1b', '0d9c8667ccb34e9da275e7dce09d9cd9'),
('be8d42fa88a14406ac201974963d9c1b', 'dc43f263313f4788bccbcc9adf642a1f'),
('be8d42fa88a14406ac201974963d9c1b', 'faa7c4808c714ca49762f6aaade7da3b'),
('be8d42fa88a14406ac201974963d9c1b', 'fd061c3363db4b298eea0bb0b4cbcbf0'),
('be8d42fa88a14406ac201974963d9c1b', '28c3460bb5cf4c618ba8ec6f3c12ddbd'),
('be8d42fa88a14406ac201974963d9c1b', '8a6141a082554335a2137c90f9fa0a5e'),
('be8d42fa88a14406ac201974963d9c1b', 'bc5a7cb945e14432bfdf312e2059e868'),
('be8d42fa88a14406ac201974963d9c1b', 'be40fd210eb44ee68475bbe80eb8b1ea'),
('be8d42fa88a14406ac201974963d9c1b', '4cea10b3a4434bc3b342407a78a9ab2a'),
('be8d42fa88a14406ac201974963d9c1b', '8f53727c763849aab80c1513505decf8'),
('be8d42fa88a14406ac201974963d9c1b', '9d69d18ff5184804990bc21cb1005ab7'),
('be8d42fa88a14406ac201974963d9c1b', 'c21495eca0d44776aeacf431dc9fb0e1'),
('be8d42fa88a14406ac201974963d9c1b', '808ab7987c9b4f0ab025b1b9e3ed1d43'),
('be8d42fa88a14406ac201974963d9c1b', '4d64a842e08344b9aeec88ed9eb2eb72'),
('be8d42fa88a14406ac201974963d9c1b', 'c64802952e504f4ab25a6b1241232f85'),
('be8d42fa88a14406ac201974963d9c1b', 'e0860766d564483e870b5974a601649c'),
('be8d42fa88a14406ac201974963d9c1b', '38f34660ef404dc9b7a0ee0f697ae781'),
('be8d42fa88a14406ac201974963d9c1b', 'db87d2605a68440fbf8e148744e243e8'),
('be8d42fa88a14406ac201974963d9c1b', '569c1d340cea4b21a54910177eeaf51f'),
('be8d42fa88a14406ac201974963d9c1b', '6b4e3b36783d4a488101da7639c40de0'),
('be8d42fa88a14406ac201974963d9c1b', '8d9b1da4234f46eb858e1ea490da6348'),
('be8d42fa88a14406ac201974963d9c1b', 'a6034d814d7e4671b4cc8a98433f8fb2'),
('be8d42fa88a14406ac201974963d9c1b', '1098a56970114e18898367d334658b47'),
('be8d42fa88a14406ac201974963d9c1b', '358a13267bcb4608a14c851c3010f79b'),
('be8d42fa88a14406ac201974963d9c1b', '6903e82e7e94478f87df3cf80de6b587'),
('be8d42fa88a14406ac201974963d9c1b', '8de7d59a74f345a5bcab20ec43376299'),
('be8d42fa88a14406ac201974963d9c1b', '1b89744217b04f79a8c1d7a967a46912'),
('be8d42fa88a14406ac201974963d9c1b', '1f4e5b3752b8475cb5261940ef62532d'),
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
-- Indeks untuk tabel `access`
--
ALTER TABLE `access`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `access_bagian`
--
ALTER TABLE `access_bagian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_ab` (`order_ab`);

--
-- Indeks untuk tabel `cabang`
--
ALTER TABLE `cabang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indeks untuk tabel `divisi`
--
ALTER TABLE `divisi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama` (`nama`);

--
-- Indeks untuk tabel `format_buku`
--
ALTER TABLE `format_buku`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `imprint`
--
ALTER TABLE `imprint`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama` (`nama`);

--
-- Indeks untuk tabel `mm_select`
--
ALTER TABLE `mm_select`
  ADD UNIQUE KEY `keyword` (`keyword`,`options`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indeks untuk tabel `notif`
--
ALTER TABLE `notif`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `penerbitan_m_kelompok_buku`
--
ALTER TABLE `penerbitan_m_kelompok_buku`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indeks untuk tabel `penerbitan_naskah`
--
ALTER TABLE `penerbitan_naskah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indeks untuk tabel `penerbitan_naskah_files`
--
ALTER TABLE `penerbitan_naskah_files`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `penerbitan_penulis`
--
ALTER TABLE `penerbitan_penulis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ktp` (`ktp`);

--
-- Indeks untuk tabel `penerbitan_pn_direksi`
--
ALTER TABLE `penerbitan_pn_direksi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `penerbitan_pn_editor_setter`
--
ALTER TABLE `penerbitan_pn_editor_setter`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `penerbitan_pn_mm`
--
ALTER TABLE `penerbitan_pn_mm`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indeks untuk tabel `penerbitan_pn_pemasaran`
--
ALTER TABLE `penerbitan_pn_pemasaran`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `penerbitan_pn_penerbitan`
--
ALTER TABLE `penerbitan_pn_penerbitan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `penerbitan_pn_prodev`
--
ALTER TABLE `penerbitan_pn_prodev`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `penerbitan_pn_stts`
--
ALTER TABLE `penerbitan_pn_stts`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `raw` (`raw`);

--
-- Indeks untuk tabel `platform_digital_ebook`
--
ALTER TABLE `platform_digital_ebook`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `produksi_order_cetak`
--
ALTER TABLE `produksi_order_cetak`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_order` (`kode_order`);

--
-- Indeks untuk tabel `produksi_order_ebook`
--
ALTER TABLE `produksi_order_ebook`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_order` (`kode_order`);

--
-- Indeks untuk tabel `produksi_penyetujuan_order_cetak`
--
ALTER TABLE `produksi_penyetujuan_order_cetak`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `produksi_penyetujuan_order_ebook`
--
ALTER TABLE `produksi_penyetujuan_order_ebook`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `proses_ebook_multimedia`
--
ALTER TABLE `proses_ebook_multimedia`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `proses_produksi_cetak`
--
ALTER TABLE `proses_produksi_cetak`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `timeline`
--
ALTER TABLE `timeline`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `timeline_sub`
--
ALTER TABLE `timeline_sub`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
