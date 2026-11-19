-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 19, 2026 at 05:33 AM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 8.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `secureadmin_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `aktivitas` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `aktivitas`, `deskripsi`, `created_at`, `updated_at`) VALUES
(4, 33, 'Login', 'Administrator berhasil login ke SecureAdmin.', '2026-11-12 12:00:31', '2026-11-12 12:00:31'),
(5, 34, 'Login', 'Administrator berhasil login ke SecureAdmin.', '2026-11-12 12:01:38', '2026-11-12 12:01:38'),
(6, 33, 'Logout', 'Administrator keluar dari SecureAdmin.', '2026-11-12 12:41:00', '2026-11-12 12:41:00'),
(7, 34, 'Logout', 'Administrator keluar dari SecureAdmin.', '2026-11-12 12:41:54', '2026-11-12 12:41:54'),
(8, 33, 'Login', 'Administrator berhasil login ke SecureAdmin.', '2026-11-12 12:42:51', '2026-11-12 12:42:51'),
(9, 34, 'Login', 'Administrator berhasil login ke SecureAdmin.', '2026-11-12 12:43:11', '2026-11-12 12:43:11'),
(10, 33, 'Tambah Data', 'Menambahkan dan mengenkripsi data: data rahasia', '2026-11-12 12:43:54', '2026-11-12 12:43:54'),
(11, 34, 'Logout', 'Administrator keluar dari SecureAdmin.', '2026-11-12 12:59:35', '2026-11-12 12:59:35'),
(12, 33, 'Tambah Data', 'Menambahkan dan mengenkripsi data: data rahasia', '2026-11-12 13:03:30', '2026-11-12 13:03:30'),
(13, 33, 'Dekripsi Data', 'Membuka dan mendekripsi data: data rahasia', '2026-11-12 13:03:37', '2026-11-12 13:03:37'),
(14, 33, 'Ubah Data', 'Mengubah data: data rahasia', '2026-11-12 13:03:44', '2026-11-12 13:03:44'),
(15, 33, 'Hapus Data', 'Menghapus data: data rahasia', '2026-11-12 13:03:47', '2026-11-12 13:03:47'),
(16, 33, 'Tambah Data', 'Menambahkan dan mengenkripsi data: daftar mantan', '2026-11-12 13:05:31', '2026-11-12 13:05:31'),
(17, 33, 'Login', 'Administrator berhasil login ke SecureAdmin.', '2026-11-14 09:27:12', '2026-11-14 09:27:12'),
(18, 33, 'Tambah Data', 'Menambahkan dan mengenkripsi data: ini data rahasia', '2026-11-14 09:28:25', '2026-11-14 09:28:25'),
(19, 33, 'Login', 'Administrator berhasil login ke SecureAdmin.', '2026-11-16 01:47:38', '2026-11-16 01:47:38'),
(20, 33, 'Dekripsi Data', 'Membuka dan mendekripsi data: ini data rahasia', '2026-11-16 01:49:02', '2026-11-16 01:49:02'),
(21, 34, 'Login', 'Administrator berhasil login ke SecureAdmin.', '2026-11-16 01:52:30', '2026-11-16 01:52:30'),
(22, 33, 'Login', 'Administrator berhasil login ke SecureAdmin.', '2026-11-18 17:28:03', '2026-11-18 17:28:03'),
(23, 34, 'Login', 'Administrator berhasil login ke SecureAdmin.', '2026-11-18 17:30:29', '2026-11-18 17:30:29'),
(24, NULL, 'Ubah Data', 'ID data: 10', '2026-11-18 20:56:36', '2026-11-18 20:56:36'),
(25, NULL, 'Ubah Data', 'ID data: 10', '2026-11-18 20:57:14', '2026-11-18 20:57:14');

-- --------------------------------------------------------

--
-- Table structure for table `admin_data`
--

CREATE TABLE `admin_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `encrypted_data` longtext NOT NULL,
  `encrypted_key` longtext NOT NULL,
  `iv` varchar(255) NOT NULL,
  `auth_tag` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_data`
--

INSERT INTO `admin_data` (`id`, `judul`, `encrypted_data`, `encrypted_key`, `iv`, `auth_tag`, `user_id`, `created_at`, `updated_at`) VALUES
(7, 'Data terenkripsi', '3tkCVxbBrq6ww9ac8RdjPDv+iBpkjdmNAtnUeWZuGRtGdbDhRTtic/m+jk9aTdlwGIPuo0NEjbzzZ3roekpvlSdXSX/EQe8+', 'uOjacqRe5yd50BgzY2rgAOy6m6LtcQ1Ae09wXBflZS9xuRpbbbAwcVNEXptM82mMYaSlUOMLLvPH4OHmsgfBcR6FVyCNlg30H51CEZ5sNH+7DPEu6DCqMfZN98DqJ2YsekPMw5kcX0giyLscV0PNJvAtWVeWPcLqKYRN0+Q1u19m/cBVugZ1MMqlFGg0LlJo/djoMmstnKHe2Sot2vMuE2hNZxKsfff1Fzmi1pnJ1wvtAbxDYmu1Mbqhkg5THSec+EELBIc/zQ5yQZsU+UsRZ6Zw2pNUEd+ysrSmlArYfnBxVo5SkizKQ0GBusbxzWVNpXT0i04n3kXd63dXQt3DTg==', 'Z7zswuNCBleXZQNi', 'H8FJx5nj1UUSDtj6EUO6EQ==', 33, '2026-11-12 12:43:54', '2026-11-18 19:20:14'),
(9, 'Data terenkripsi', 'IJf1wOAX6Y/+tYzZm3hf7F4BnUlRpD5tuE2nK2TLYj9uitfraVNfO9c5y6aNfoLm4KERiI/58AeqX3VCMabgp8hst4sGoa0wNJDh6aiD01hyKspOhr/5WrXVeeMXC5qJTef/oMkmX2eaVSijPfymGJok7Bwe3ki5eoQ=', 'pTbf2lhGcoa5Iyqr3meZniFsYOb0491EQHoja6/xUgSXuZsgtZ9VopSSpoB22rLjDr+gcLjNMwrFXI9oGt3n2lNDUVUHHJiGz7oC/5EqJFJiiVOPg/niPSX2F5KTqtydSkwg1RnFqws5DgtjUQCn90eqyb+Vsdl065pmxUwNO2uOBR/IgAK/updvax8rXw0NyrF+tIq36/d3QQSjmXL1IJ8hSYkmLxjTBRpqIN58lmdoBrdpBxkNz5QQ/lescCwRBDwbHnZWMLWtrI3y5cBIJV9ZFfLED2yFmwhatPltJF7cZSYQzel97drS8DmvLgGw+DJPpuyOIYDuMRRkgYkZvg==', '/YrctKkTEgkn2vd9', 'hzDHo01Vcygzgn3j1cWlXw==', 33, '2026-11-12 13:05:31', '2026-11-18 19:20:14'),
(10, 'Data terenkripsi', 'ip/4PPFIuK87RFPga9Gy+PYRmS8eE444S+NQCdaPMtTnZs36HMnjwYrYpuoVlMScy/xQo8FO0JHpnK4VIOuKb8rGme8dv9LZoR2cjpa5gClfMn63FPv9Qzhd25Fh', 'ZLFBxf7EsQ7K37TY63B1HiaXJ0BQznBtYnrCBcHB5Paq+zHPjzTmQk2eU8fvENuidlhp7nlXtxK/OJ+AlcuX5ZhS4HPC1xgpYsIlR+72+W1oqKOvhOyB41wB7uGWkCSW7F0t+NpNILXoMGbHAZSNg2ekkGKjZpfDS/0xJSPR+Kcn/JUG32o53jc8nzhCwcY6ZigQ4vocwl9W4uCYFxyF6Ty47IcJKa8X+1Sqqkkq6Mc2LmJOYgFl2kCe+a6FgjzEX1YrG3AzERmelgCHAvfOSPrLIis87gQVhUhwIv1vh2Qc6h3eWyXUnWXb2y/2pCS5Kc5fR5ayeHWKv3Ng9WXbjQ==', 'Mg8wxYMoQgsUcvqY', 'oIT5qMsBwLmBITJga731Cw==', NULL, '2026-11-14 09:28:25', '2026-11-18 20:57:14');

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
-- Table structure for table `landing_pages`
--

CREATE TABLE `landing_pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `section` varchar(255) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `tombol_teks` varchar(255) DEFAULT NULL,
  `tombol_url` varchar(255) DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `landing_pages`
--

INSERT INTO `landing_pages` (`id`, `section`, `judul`, `deskripsi`, `gambar`, `tombol_teks`, `tombol_url`, `aktif`, `created_at`, `updated_at`) VALUES
(1, 'hero', 'Keamanan Data dengan SecureAdmin', 'Sistem pengelolaan dan perlindungan data menggunakan teknologi kriptografi AES-256-GCM dan RSA-2048.', NULL, 'Pelajari Lebih Lanjut', '#tentang', 1, '2026-11-18 18:45:54', '2026-11-18 18:45:54'),
(2, 'about', 'Tentang SecureAdmin', 'SecureAdmin merupakan sistem pengelolaan data administrator yang dirancang untuk menjaga kerahasiaan, integritas, dan keamanan informasi menggunakan teknologi kriptografi.', NULL, NULL, NULL, 1, '2026-11-18 18:45:54', '2026-11-18 18:45:54'),
(3, 'features', 'Teknologi Keamanan', 'Perlindungan informasi menggunakan kombinasi algoritma kriptografi modern dan autentikasi berbasis kunci RSA.', NULL, NULL, NULL, 1, '2026-11-18 18:45:54', '2026-11-18 18:45:54');

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_11_12_114441_create_admin_data_table', 1),
(6, '2026_11_12_153738_create_activity_logs_table', 1),
(7, '2026_11_12_192046_add_role_to_users_table', 2),
(8, '2026_11_19_013253_create_landing_pages_table', 3),
(9, '2026_11_19_031224_make_user_id_nullable_on_admin_data_table', 4);

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
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`) VALUES
(33, 'Asep Hikmat Miptahudin', 'hikmatmiptahudin@gmail.com', NULL, '$2y$12$W7eGEAUvfjPPGAskOZ4RruJlzjhMZcX18lpZzWlUf2IFUGFoPzV0q', NULL, '2026-11-12 11:59:03', '2026-11-12 12:23:57', 'admin'),
(34, 'Camat', 'tongeretanggora17@gmail.com', NULL, '$2y$12$AeP3IOA/kdEK8nNAkr7tAOfsoE/3xhbXaT4AMRe11NgLd9qyjWXlm', NULL, '2026-11-12 11:59:17', '2026-11-12 11:59:17', 'user'),
(35, 'akmal', 'admin@biolink.com', NULL, '$2y$12$ux7TmeFzt/hQMclFu0oWW.Jgrogd4w3pNIl0751vCiA59YFlhVQIO', NULL, '2026-11-12 13:00:19', '2026-11-12 13:00:19', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `admin_data`
--
ALTER TABLE `admin_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_data_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `landing_pages`
--
ALTER TABLE `landing_pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `landing_pages_section_unique` (`section`);

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
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `admin_data`
--
ALTER TABLE `admin_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `landing_pages`
--
ALTER TABLE `landing_pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `admin_data`
--
ALTER TABLE `admin_data`
  ADD CONSTRAINT `admin_data_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
