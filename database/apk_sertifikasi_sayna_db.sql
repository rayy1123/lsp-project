-- phpMyAdmin SQL Dump
-- Database: apk_sertifikasi_sayna_db
-- Recreated & Optimized for Laragon (MySQL / phpMyAdmin)
-- Host: 127.0.0.1:3306

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = 0;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Buat database jika belum ada
--
CREATE DATABASE IF NOT EXISTS `apk_sertifikasi_sayna_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `apk_sertifikasi_sayna_db`;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'peserta',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
-- Password default: admin123
--
INSERT INTO `users` (`id`, `name`, `email`, `no_hp`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@gmail.com', '081234567890', NULL, '$2y$12$PVqwVYGSfOYNjkzwpclLGeo/5DJXATTU1tkJbmqG9mncqZfcm3w3u', 'admin', NULL, NOW(), NOW()),
(2, 'Peserta Contoh', 'peserta@gmail.com', '081298765432', NULL, '$2y$12$PVqwVYGSfOYNjkzwpclLGeo/5DJXATTU1tkJbmqG9mncqZfcm3w3u', 'peserta', NULL, NOW(), NOW());

-- --------------------------------------------------------

--
-- Struktur dari tabel `skemas`
--
DROP TABLE IF EXISTS `skemas`;
CREATE TABLE `skemas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_skema` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_skema` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `skemas_kode_skema_unique` (`kode_skema`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `skemas`
--
INSERT INTO `skemas` (`id`, `kode_skema`, `nama_skema`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'JWD-001', 'Junior Web Developer', 'Skema sertifikasi kompetensi untuk pembuatan dan pemeliharaan website interaktif menggunakan PHP dan MySQL.', NOW(), NOW()),
(2, 'JNA-002', 'Junior Network Administrator', 'Skema sertifikasi kompetensi untuk instalasi, konfigurasi, dan pemeliharaan jaringan komputer skala kecil-menengah.', NOW(), NOW()),
(3, 'DGM-003', 'Desainer Grafis Muda', 'Skema sertifikasi kompetensi perancangan visual, branding, dan aset multimedia digital.', NOW(), NOW());

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesertas`
--
DROP TABLE IF EXISTS `pesertas`;
CREATE TABLE `pesertas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `nama` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Laki-laki',
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `skema_id` bigint UNSIGNED NOT NULL,
  `tanggal_daftar` date NOT NULL,
  `status_kompetensi` enum('Belum Ditugaskan','Menunggu Pengumpulan','Menunggu Penilaian','Kompeten','Belum Kompeten') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Belum Ditugaskan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pesertas_nik_unique` (`nik`),
  UNIQUE KEY `pesertas_user_id_unique` (`user_id`),
  KEY `pesertas_skema_id_foreign` (`skema_id`),
  CONSTRAINT `pesertas_skema_id_foreign` FOREIGN KEY (`skema_id`) REFERENCES `skemas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pesertas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pesertas`
--
INSERT INTO `pesertas` (`id`, `user_id`, `nama`, `jenis_kelamin`, `nik`, `tempat_lahir`, `tanggal_lahir`, `email`, `no_hp`, `alamat`, `skema_id`, `tanggal_daftar`, `status_kompetensi`, `created_at`, `updated_at`) VALUES
(1, 2, 'Peserta Contoh', 'Laki-laki', '3201012345670001', 'Jakarta', '2000-01-15', 'peserta@gmail.com', '081298765432', 'Jl. Merdeka No. 10, Jakarta Pusat', 1, CURDATE(), 'Menunggu Pengumpulan', NOW(), NOW());

-- --------------------------------------------------------

--
-- Struktur dari tabel `tugas`
--
DROP TABLE IF EXISTS `tugas`;
CREATE TABLE `tugas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `skema_id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tugas_skema_id_foreign` (`skema_id`),
  CONSTRAINT `tugas_skema_id_foreign` FOREIGN KEY (`skema_id`) REFERENCES `skemas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tugas`
--
INSERT INTO `tugas` (`id`, `skema_id`, `judul`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 1, 'Uji Praktik JWD: Pembuatan Aplikasi CRUD Data Peserta', 'Buatlah aplikasi web sederhana dengan fitur CRUD data peserta serta otentikasi login. Kumpulkan tautan repository GitHub dan lampirkan screenshot tampilan aplikasi.', NOW(), NOW()),
(2, 2, 'Uji Praktik JNA: Konfigurasi Routing & Subnetting Jaringan', 'Lakukan simulasi konfigurasi routing statis dan dinamis pada Cisco Packet Tracer. Lampirkan tangkapan layar topologi dan link file simulasi.', NOW(), NOW()),
(3, 3, 'Uji Praktik DGM: Perancangan Logo & Brand Guidelines', 'Rancang identitas visual berupa logo dan panduan warna. Unggah file gambar karya desain Anda dan tautan Google Drive portofolio.', NOW(), NOW());

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengumpulan_tugas`
--
DROP TABLE IF EXISTS `pengumpulan_tugas`;
CREATE TABLE `pengumpulan_tugas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tugas_id` bigint UNSIGNED NOT NULL,
  `peserta_id` bigint UNSIGNED NOT NULL,
  `link_jawaban` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_jawaban` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Menunggu Penilaian','Kompeten','Belum Kompeten') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu Penilaian',
  `catatan_admin` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pengumpulan_tugas_tugas_peserta_unique` (`tugas_id`,`peserta_id`),
  KEY `pengumpulan_tugas_peserta_id_foreign` (`peserta_id`),
  CONSTRAINT `pengumpulan_tugas_tugas_id_foreign` FOREIGN KEY (`tugas_id`) REFERENCES `tugas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pengumpulan_tugas_peserta_id_foreign` FOREIGN KEY (`peserta_id`) REFERENCES `pesertas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_09_29_101753_create_skemas_table', 1),
(5, '2026_09_29_101904_create_pesertas_table', 1),
(6, '2026_10_13_040927_add_no_hp_to_users_table', 1),
(7, '2026_10_13_042849_add_role_to_users_table', 1),
(8, '2026_10_13_043536_add_user_id_to_pesertas_table', 1),
(9, '2026_10_14_050000_add_tempat_tanggal_lahir_to_pesertas_table', 1),
(10, '2026_10_14_060000_create_tugas_table', 1),
(11, '2026_10_14_070000_ensure_skema_id_on_tugas_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
