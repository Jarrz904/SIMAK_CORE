-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2026 at 03:50 AM
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
-- Database: `siak`
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
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kecamatans`
--

INSERT INTO `kecamatans` (`id`, `nama_kecamatan`, `kode_wilayah`, `status`, `created_at`) VALUES
(1, 'ADIWERNA', NULL, 'aktif', '2026-01-20 13:16:35'),
(2, 'BALAPULANG', NULL, 'aktif', '2026-01-20 13:16:35'),
(3, 'BOJONG', NULL, 'aktif', '2026-01-20 13:16:35'),
(4, 'BUMIJAWA', NULL, 'aktif', '2026-01-20 13:16:35'),
(5, 'DUKUHTURI', NULL, 'aktif', '2026-01-20 13:16:35'),
(6, 'DUKUHWARU', NULL, 'aktif', '2026-01-20 13:16:35'),
(7, 'JATINEGARA', NULL, 'aktif', '2026-01-20 13:16:35'),
(8, 'KEDUNGBANTENG', NULL, 'aktif', '2026-01-20 13:16:35'),
(9, 'MPP', '33.28', 'aktif', '2026-01-21 01:26:24'),
(10, 'KRAMAT', NULL, 'aktif', '2026-01-20 13:16:35'),
(11, 'LEBAKSIU', NULL, 'aktif', '2026-01-20 13:16:35'),
(12, 'MARGASARI', NULL, 'aktif', '2026-01-20 13:16:35'),
(13, 'PAGUYANGAN', NULL, 'aktif', '2026-01-20 13:16:35'),
(14, 'PANGKAH', NULL, 'aktif', '2026-01-20 13:16:35'),
(15, 'SLAWI', NULL, 'aktif', '2026-01-20 13:16:35'),
(16, 'SURADADI', NULL, 'aktif', '2026-01-20 13:16:35'),
(17, 'TALANG', NULL, 'aktif', '2026-01-20 13:16:35'),
(18, 'TARUB', NULL, 'aktif', '2026-01-20 13:16:35'),
(19, 'WARUREJA', NULL, 'aktif', '2026-01-20 13:16:35');

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
(4, '2026_01_12_062618_create_penduduks_table', 1),
(5, '2026_01_12_062627_create_surats_table', 1),
(24, '2026_01_29_010952_add_is_rejected_to_update_datas_table', 10),
(40, '0001_01_01_000000_create_users_table', 11),
(41, '0001_01_01_000001_create_cache_table', 11),
(42, '0001_01_01_000002_create_jobs_table', 11),
(43, '2026_01_12_074922_add_identity_columns_to_users_table', 11),
(44, '2026_01_12_134050_add_is_active_to_users_table', 11),
(45, '2026_01_12_142352_create_troubles_table', 11),
(46, '2026_01_12_152449_add_tanggapan_admin_to_troubles_table', 11),
(47, '2026_01_12_162359_create_aktivasis_table', 11),
(48, '2026_01_13_005549_create_pengajuans_table', 11),
(49, '2026_01_13_005558_create_proxies_table', 11),
(50, '2026_01_13_024043_add_tanggapan_admin_to_multiple_tables', 11),
(51, '2026_01_13_075806_update_role_column_in_users_table', 11),
(52, '2026_01_19_024152_change_ip_detail_to_nullable_in_proxies_table', 11),
(53, '2026_01_20_044554_create_pembubuhans_table', 11),
(54, '2026_01_21_021232_add_processed_by_to_laporan_tables', 11),
(55, '2026_01_22_041127_add_processed_by_to_aktivasis_table', 11),
(56, '2026_01_23_023451_create_announcements_table', 11),
(57, '2026_01_25_123851_create_luar_daerahs_table', 11),
(58, '2026_01_26_005400_modify_pembubuhans_table', 11),
(59, '2026_01_26_005732_make_pembubuhans_columns_nullable', 11),
(60, '2026_01_28_035951_create_update_data_table', 11),
(61, '2026_01_29_064349_add_layanan_and_foto_to_aktivasis_table', 12);

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
  `jenis_dokumen` varchar(255) NOT NULL,
  `tanggapan_admin` text DEFAULT NULL,
  `processed_by` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `is_rejected` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('ZrDP3MXEa2sU2s8N3OWYlNisPjm6SGobzKUybJD9', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRm93QnZBNkxkNmxVUVc3MW5ER2xZaUxTcGprVnBsa1lQbkpGYkI3cCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC91c2VyL2Rhc2hib2FyZCI7czo1OiJyb3V0ZSI7czoxNDoidXNlci5kYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1769741391);

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

-- --------------------------------------------------------

--
-- Table structure for table `update_datas`
--

CREATE TABLE `update_datas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nik_pemohon` varchar(16) NOT NULL,
  `jenis_layanan` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `lampiran` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`lampiran`)),
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `is_rejected` tinyint(1) NOT NULL DEFAULT 0,
  `tanggapan_admin` text DEFAULT NULL,
  `processed_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `update_datas`
--

INSERT INTO `update_datas` (`id`, `user_id`, `nik_pemohon`, `jenis_layanan`, `deskripsi`, `lampiran`, `status`, `is_rejected`, `tanggapan_admin`, `processed_by`, `created_at`, `updated_at`) VALUES
(17, 3, '5848578476873938', 'UPDATE TANGGAL LAHIR', 'COBAAA', '\"[\\\"update_data\\\\\\/tDYPOjXun4imkkpmjG8cISWLvu1pBIxRVBoe7c9G.png\\\"]\"', 'pending', 0, NULL, NULL, '2026-01-30 02:40:32', '2026-01-30 02:40:32'),
(18, 3, '5848578476873938', 'UPDATE NAMA', 'coba cekk', '\"[\\\"update_data\\\\\\/DGt45ZE0Ec4rMk2piSRkKl3gcF36YRxTUsKeWQO8.png\\\",\\\"update_data\\\\\\/pYi733qoVKZHdUvhE5HomsZzZyWWcZcrSbUp9MAs.png\\\"]\"', 'pending', 0, NULL, NULL, '2026-01-30 02:49:22', '2026-01-30 02:49:22');

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
(3, '3201234567890001', '654321', 'Muhammad Fajar Sidik', 'KEDUNGBANTENG', 'warga@siak.go.id', NULL, '$2y$12$m9mk8vPmfi/b9KQx1i/hie5HgP7Cg.LUJCNA36oY3XG0EaVFR7P3S', 'user', 1, NULL, '2026-01-28 20:10:20', '2026-01-28 20:11:57'),
(5, '1234567890123456', '112233', 'Admin1', 'ADIWERNA', 'admin@siak.go.id', NULL, '$2y$12$ZlYqx5yifiUxgEWPEhWhQ.bp0aXr23.K193JlRw5WAF9hzvY7GrIK', 'admin', 1, NULL, '2026-01-28 20:15:13', '2026-01-28 20:16:03'),
(11, '9483598493859489', '123456', 'Alveroes', 'BOJONG', '9483598493859489@sistem.com', NULL, '$2y$12$WFBzUFQq.eAnKrxsvjYXQeF4uOwhwDv8pBNKBYAz2QcAhF3wQTj2G', 'user', 1, NULL, '2026-01-28 20:35:06', '2026-01-28 20:35:06'),
(12, '9483598493859488', '332211', 'Admin2', 'MPP', '9483598493859488@sistem.com', NULL, '$2y$12$P/kuiPLbb4BTDglxsF2Gae6RNjn7KUJuGHUbIKfe.Rl091iyoMCaC', 'admin', 1, NULL, '2026-01-28 20:38:13', '2026-01-28 20:38:21');

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
  ADD KEY `update_datas_user_id_foreign` (`user_id`),
  ADD KEY `update_datas_status_index` (`status`),
  ADD KEY `update_datas_is_rejected_index` (`is_rejected`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `luar_daerahs`
--
ALTER TABLE `luar_daerahs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `pembubuhans`
--
ALTER TABLE `pembubuhans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `pengajuans`
--
ALTER TABLE `pengajuans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `proxies`
--
ALTER TABLE `proxies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `troubles`
--
ALTER TABLE `troubles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `update_datas`
--
ALTER TABLE `update_datas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
