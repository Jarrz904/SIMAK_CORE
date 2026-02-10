-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2026 at 03:37 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simak`
--

-- --------------------------------------------------------

--
-- Table structure for table `aktivasis`
--

CREATE TABLE `aktivasis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `nik_aktivasi` varchar(255) NOT NULL,
  `jenis_layanan` varchar(255) DEFAULT NULL,
  `alasan` text DEFAULT NULL,
  `foto_ktp` varchar(255) DEFAULT NULL,
  `tanggapan_admin` text DEFAULT NULL,
  `processed_by` varchar(255) DEFAULT NULL,
  `is_rejected` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `aktivasis`
--

INSERT INTO `aktivasis` (`id`, `user_id`, `nama_lengkap`, `nik_aktivasi`, `jenis_layanan`, `alasan`, `foto_ktp`, `tanggapan_admin`, `processed_by`, `is_rejected`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'Operator Jarrz', '3049329584958498', 'AKTIVASI', 'tidak menggunakan foto', NULL, NULL, NULL, 0, 'Pending', '2026-02-10 01:50:33', '2026-02-10 01:50:33');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','warning','urgent') NOT NULL DEFAULT 'info',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `message`, `type`, `created_at`, `updated_at`) VALUES
(1, 'pengumuman', 'coba tahap 1', 'info', '2026-02-09 18:59:54', '2026-02-09 18:59:54');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kecamatans`
--

CREATE TABLE `kecamatans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_kecamatan` varchar(100) NOT NULL,
  `kode_wilayah` varchar(10) DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kecamatans`
--

INSERT INTO `kecamatans` (`id`, `nama_kecamatan`, `kode_wilayah`, `status`, `created_at`) VALUES
(1, 'ADIWERNA', '33.28.11', 'aktif', '2026-02-10 00:46:03'),
(2, 'BALAPULANG', '33.28.03', 'aktif', '2026-02-10 00:46:03'),
(3, 'BOJONG', '33.28.02', 'aktif', '2026-02-10 00:46:03'),
(4, 'BUMIJAWA', '33.28.01', 'aktif', '2026-02-10 00:46:03'),
(5, 'DUKUHTURI', '33.28.13', 'aktif', '2026-02-10 00:46:03'),
(6, 'DUKUHWARU', '33.28.12', 'aktif', '2026-02-10 00:46:03'),
(7, 'JATINEGARA', '33.28.06', 'aktif', '2026-02-10 00:46:03'),
(8, 'KEDUNGBANTENG', '33.28.07', 'aktif', '2026-02-10 00:46:03'),
(9, 'MPP', '33.28', 'aktif', '2026-02-10 00:46:03'),
(10, 'KRAMAT', '33.28.15', 'aktif', '2026-02-10 00:46:03'),
(11, 'LEBAKSIU', '33.28.05', 'aktif', '2026-02-10 00:46:03'),
(12, 'MARGASARI', '33.28.04', 'aktif', '2026-02-10 00:46:03'),
(13, 'PAGUYANGAN', '33.29.02', 'aktif', '2026-02-10 00:46:03'),
(14, 'PANGKAH', '33.28.08', 'aktif', '2026-02-10 00:46:03'),
(15, 'SLAWI', '33.28.09', 'aktif', '2026-02-10 00:46:03'),
(16, 'SURADADI', '33.28.17', 'aktif', '2026-02-10 00:46:03'),
(17, 'TALANG', '33.28.14', 'aktif', '2026-02-10 00:46:03'),
(18, 'TARUB', '33.28.16', 'aktif', '2026-02-10 00:46:03'),
(19, 'WARUREJA', '33.28.18', 'aktif', '2026-02-10 00:46:03');

-- --------------------------------------------------------

--
-- Table structure for table `luar_daerahs`
--

CREATE TABLE `luar_daerahs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nik` varchar(16) NOT NULL,
  `nik_luar_daerah` varchar(16) NOT NULL,
  `jenis_dokumen` varchar(255) NOT NULL,
  `tanggapan_admin` text DEFAULT NULL,
  `processed_by` varchar(255) DEFAULT NULL,
  `is_rejected` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('pending','selesai','ditolak') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `luar_daerahs`
--

INSERT INTO `luar_daerahs` (`id`, `user_id`, `nik`, `nik_luar_daerah`, `jenis_dokumen`, `tanggapan_admin`, `processed_by`, `is_rejected`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, '3201012301010001', '4969058368548095', 'PINDAH DATANG', NULL, NULL, 0, 'pending', '2026-02-10 01:46:05', '2026-02-10 01:46:05');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_12_074922_add_identity_columns_to_users_table', 1),
(5, '2026_01_12_134050_add_is_active_to_users_table', 1),
(6, '2026_01_12_142352_create_troubles_table', 1),
(7, '2026_01_12_152449_add_tanggapan_admin_to_troubles_table', 1),
(8, '2026_01_12_162359_create_aktivasis_table', 1),
(9, '2026_01_13_005549_create_pengajuans_table', 1),
(10, '2026_01_13_005558_create_proxies_table', 1),
(11, '2026_01_13_024043_add_tanggapan_admin_to_multiple_tables', 1),
(12, '2026_01_13_075806_update_role_column_in_users_table', 1),
(13, '2026_01_19_024152_change_ip_detail_to_nullable_in_proxies_table', 1),
(14, '2026_01_20_044554_create_pembubuhans_table', 1),
(15, '2026_01_21_021232_add_processed_by_to_laporan_tables', 1),
(16, '2026_01_22_041127_add_processed_by_to_aktivasis_table', 1),
(17, '2026_01_23_023451_create_announcements_table', 1),
(18, '2026_01_25_123851_create_luar_daerahs_table', 1),
(19, '2026_01_26_005732_make_pembubuhans_columns_nullable', 1),
(20, '2026_01_28_035951_create_update_data_table', 1),
(21, '2026_01_29_064349_add_layanan_and_foto_to_aktivasis_table', 1),
(22, '2026_01_30_084722_create_kecamatans_table', 1),
(23, '2026_02_10_012103_add_nik_pemohon_to_pembubuhans_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembubuhans`
--

CREATE TABLE `pembubuhans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nik` varchar(16) NOT NULL,
  `nik_pemohon` varchar(16) DEFAULT NULL,
  `no_akte` varchar(255) DEFAULT NULL,
  `jenis_dokumen` varchar(255) NOT NULL,
  `foto_dokumen` varchar(255) DEFAULT NULL,
  `tanggapan_admin` text DEFAULT NULL,
  `processed_by` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `is_rejected` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembubuhans`
--

INSERT INTO `pembubuhans` (`id`, `user_id`, `nik`, `nik_pemohon`, `no_akte`, `jenis_dokumen`, `foto_dokumen`, `tanggapan_admin`, `processed_by`, `status`, `is_rejected`, `created_at`, `updated_at`) VALUES
(3, 2, '3201012301010001', '4594545847584785', NULL, 'AKTA KELAHIRAN', NULL, NULL, NULL, 'Pending', 0, '2026-02-10 01:45:00', '2026-02-10 01:45:00');

-- --------------------------------------------------------

--
-- Table structure for table `pengajuans`
--

CREATE TABLE `pengajuans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(255) DEFAULT NULL,
  `nik_aktivasi` varchar(16) DEFAULT NULL,
  `jenis_registrasi` varchar(255) DEFAULT NULL,
  `kategori` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `foto_dokumen` text DEFAULT NULL,
  `tanggapan_admin` text DEFAULT NULL,
  `processed_by` varchar(255) DEFAULT NULL,
  `is_rejected` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengajuans`
--

INSERT INTO `pengajuans` (`id`, `user_id`, `nama_lengkap`, `nik_aktivasi`, `jenis_registrasi`, `kategori`, `deskripsi`, `foto_dokumen`, `tanggapan_admin`, `processed_by`, `is_rejected`, `status`, `created_at`, `updated_at`) VALUES
(2, 2, 'Operator Jarrz', '3201012301010001', NULL, 'REGISTRASI SIAK', 'registrasi SIAK', '[\"pengajuan\\/KbfdEdsMzHLqAr46DqKomnCnYfNMsWR7WrZxCO7V.png\",\"pengajuan\\/iDUNlTVxF2DZOf9hiavW7XqNeEPxusOev1D0fuOY.png\"]', NULL, NULL, 0, 'Pending', '2026-02-10 01:44:50', '2026-02-10 01:44:50');

-- --------------------------------------------------------

--
-- Table structure for table `proxies`
--

CREATE TABLE `proxies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `foto_proxy` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `tanggapan_admin` text DEFAULT NULL,
  `processed_by` varchar(255) DEFAULT NULL,
  `is_rejected` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ip_detail` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `proxies`
--

INSERT INTO `proxies` (`id`, `user_id`, `deskripsi`, `foto_proxy`, `status`, `tanggapan_admin`, `processed_by`, `is_rejected`, `created_at`, `updated_at`, `ip_detail`) VALUES
(1, 2, NULL, '[\"proxy\\/n9NVF9YBDGabVVF1NpECXHLQJ3LaMJAwKaRuxtqH.png\"]', 'Pending', NULL, NULL, 0, '2026-02-10 01:55:51', '2026-02-10 01:55:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('nCyaYIhPNlw5grxpY4QmEsC7EMZt3MoNGZ73Zei5', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiN1JDYlZjdkI5Q3dwQmU2c1phek55U2ZLSjRUV0ZvN3M2SEx0MldmayI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQ/dGFiPWRhdGFfdXNlciI7czo1OiJyb3V0ZSI7czoxMToiYWRtaW4uaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1770690986);

-- --------------------------------------------------------

--
-- Table structure for table `troubles`
--

CREATE TABLE `troubles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `kategori` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `foto_trouble` varchar(255) NOT NULL,
  `status` enum('pending','proses','selesai') NOT NULL DEFAULT 'pending',
  `tanggapan_admin` text DEFAULT NULL,
  `processed_by` varchar(255) DEFAULT NULL,
  `is_rejected` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `troubles`
--

INSERT INTO `troubles` (`id`, `user_id`, `kategori`, `deskripsi`, `foto_trouble`, `status`, `tanggapan_admin`, `processed_by`, `is_rejected`, `created_at`, `updated_at`) VALUES
(1, 2, 'PERANGKAT PENDUKUNG', 'perangkat pendukung', '[\"troubles\\/64Tmxn7oWilGUeCXmrWbJQ5ditzhBxCUAKHz7hvJ.png\",\"troubles\\/3AgJM6KXfeiuA4QN3jAQZzEKxe2jUsnZR0mmMGN6.png\"]', 'pending', NULL, NULL, 0, '2026-02-10 01:57:21', '2026-02-10 01:57:21');

-- --------------------------------------------------------

--
-- Table structure for table `update_datas`
--

CREATE TABLE `update_datas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nik_pemohon` varchar(255) NOT NULL,
  `jenis_layanan` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `lampiran` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `tanggapan_admin` text DEFAULT NULL,
  `processed_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `update_datas`
--

INSERT INTO `update_datas` (`id`, `user_id`, `nik_pemohon`, `jenis_layanan`, `deskripsi`, `lampiran`, `status`, `tanggapan_admin`, `processed_by`, `created_at`, `updated_at`) VALUES
(1, 2, '4594545847584785', 'PERUBAHAN GOLONGAN DARAH', 'coba cek', '\"[\\\"update_data\\\\\\/5QuZ28DH5Ta5OHmOaBH1y8TtwqKkKWwPwEboH9EW.png\\\",\\\"update_data\\\\\\/XG42V9LvSv2UHIb27rvnNVyI8XWpETw9WFkbPzvw.png\\\",\\\"update_data\\\\\\/o7APDT58xjRe7PLJtKdH0heKSWs5PnNcgHf6LV4C.png\\\"]\"', 'pending', NULL, NULL, '2026-02-10 01:55:38', '2026-02-10 01:55:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `pin` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nik`, `pin`, `name`, `location`, `email`, `email_verified_at`, `password`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, '3201010000000000', '332800', 'Admin PIAK', NULL, 'admin@gmail.com', NULL, '$2y$12$c4erL/DuLwLctRaOonG6aur/N20ZHrqb7m2ZVlGTpQZhN8U3Yk/DG', 'admin', 1, NULL, '2026-02-09 18:18:17', '2026-02-09 18:18:17'),
(2, '3201012301010001', '332811', 'Operator Jarrz', 'KEDUNGBANTENG', 'user@gmail.com', NULL, '$2y$12$q8O.xhKerdSIqQqTLD2U3.93YKcwsgGRkXyOQzGZjHCaRqEB32upG', 'user', 1, NULL, '2026-02-09 18:18:18', '2026-02-10 01:19:41'),
(7, '9483598493859487', '123123', 'faruk', 'MARGASARI', 'faruk65@sistem.com', NULL, '$2y$12$yBzDlqoF2RJYCBBUvMmErephJ3kjGMETj3A5RorxAmJRltohB4DM6', 'admin', 1, NULL, '2026-02-09 19:31:46', '2026-02-09 19:33:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aktivasis`
--
ALTER TABLE `aktivasis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aktivasis_user_id_foreign` (`user_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kecamatans`
--
ALTER TABLE `kecamatans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `luar_daerahs`
--
ALTER TABLE `luar_daerahs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `luar_daerahs_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pembubuhans`
--
ALTER TABLE `pembubuhans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pembubuhans_user_id_foreign` (`user_id`);

--
-- Indexes for table `pengajuans`
--
ALTER TABLE `pengajuans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengajuans_user_id_foreign` (`user_id`);

--
-- Indexes for table `proxies`
--
ALTER TABLE `proxies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proxies_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `troubles`
--
ALTER TABLE `troubles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `troubles_user_id_foreign` (`user_id`);

--
-- Indexes for table `update_datas`
--
ALTER TABLE `update_datas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `update_datas_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_pin_unique` (`pin`),
  ADD UNIQUE KEY `users_nik_unique` (`nik`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aktivasis`
--
ALTER TABLE `aktivasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kecamatans`
--
ALTER TABLE `kecamatans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `luar_daerahs`
--
ALTER TABLE `luar_daerahs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `pembubuhans`
--
ALTER TABLE `pembubuhans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pengajuans`
--
ALTER TABLE `pengajuans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `proxies`
--
ALTER TABLE `proxies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `troubles`
--
ALTER TABLE `troubles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `update_datas`
--
ALTER TABLE `update_datas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aktivasis`
--
ALTER TABLE `aktivasis`
  ADD CONSTRAINT `aktivasis_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `luar_daerahs`
--
ALTER TABLE `luar_daerahs`
  ADD CONSTRAINT `luar_daerahs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pembubuhans`
--
ALTER TABLE `pembubuhans`
  ADD CONSTRAINT `pembubuhans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pengajuans`
--
ALTER TABLE `pengajuans`
  ADD CONSTRAINT `pengajuans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `proxies`
--
ALTER TABLE `proxies`
  ADD CONSTRAINT `proxies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `troubles`
--
ALTER TABLE `troubles`
  ADD CONSTRAINT `troubles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `update_datas`
--
ALTER TABLE `update_datas`
  ADD CONSTRAINT `update_datas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
