-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 28, 2026 at 01:43 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventaris_ayupatra`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id` int NOT NULL,
  `kode_barang` varchar(50) DEFAULT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `id_kategori` int NOT NULL,
  `satuan` varchar(20) NOT NULL DEFAULT 'buah',
  `stok` int DEFAULT '0',
  `stok_minimal` int NOT NULL DEFAULT '0',
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `kondisi` enum('baik','rusak','hilang') NOT NULL DEFAULT 'baik',
  `harga` decimal(12,2) DEFAULT '0.00',
  `lokasi` varchar(100) DEFAULT NULL,
  `keterangan` text,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `kode_barang`, `nama_barang`, `id_kategori`, `satuan`, `stok`, `stok_minimal`, `status`, `kondisi`, `harga`, `lokasi`, `keterangan`, `foto`, `created_at`, `updated_at`) VALUES
(3, 'ELP-003', 'Laptop Lenovo ThinkPad', 3, 'unit', 12, 5, 'aktif', 'baik', '300000.00', NULL, NULL, NULL, '2026-02-25 03:10:14', '2026-02-25 03:10:14'),
(4, 'ELP-004', 'Keyboard Mechanical', 1, 'unit', 7, 3, 'aktif', 'baik', '450000.00', NULL, NULL, NULL, '2026-02-25 03:10:14', '2026-02-25 03:10:14'),
(5, 'ELP-005', 'Mouse Wireless', 3, 'unit', 96, 3, 'aktif', 'baik', '145000.00', NULL, NULL, NULL, '2026-02-25 03:10:14', '2026-02-28 01:22:49'),
(13, 'AKS-013', 'gantungan tas', 4, 'pcs', 7, 20, 'aktif', 'baik', '20000.00', NULL, NULL, NULL, '2026-02-25 03:10:14', '2026-02-28 01:22:29'),
(14, 'KON-014', 'air kelimutu', 5, 'box', 7, 15, 'aktif', 'baik', '22000.00', NULL, NULL, NULL, '2026-02-25 03:10:14', '2026-02-25 03:10:14'),
(15, 'KON-015', 'Mie Goreng', 5, 'box', 15, 20, 'aktif', 'baik', '123000.00', NULL, NULL, NULL, '2026-02-25 03:10:14', '2026-02-25 03:10:14'),
(16, 'BRG-20260228-69a24407e8654', 'mie geprek', 5, 'box', 4, 5, 'aktif', 'baik', '5000.00', NULL, 'pembeliann', NULL, '2026-02-28 01:25:28', '2026-02-28 01:41:24'),
(17, 'BRG-20260228-69a2442f5cd09', 'pulpen', 3, 'pcs', 10, 5, 'aktif', 'baik', '6000.00', NULL, 'pembelian', NULL, '2026-02-28 01:26:07', '2026-02-28 01:41:24');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int NOT NULL,
  `nama_kategori` varchar(50) NOT NULL,
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 'Elektronik', 'Perangkat elektronik dan aksesoris', '2026-02-25 03:08:40', '2026-02-25 03:08:40'),
(2, 'ATK', 'Alat Tulis Kantor', '2026-02-25 03:08:40', '2026-02-25 03:08:40'),
(3, 'Peralatan Kantor', 'Peralatan dan perlengkapan kantor', '2026-02-25 03:08:40', '2026-02-25 03:08:40'),
(4, 'Aksesoris', 'Aksesoris dan perangkat tambahan', '2026-02-25 03:08:40', '2026-02-25 03:08:40'),
(5, 'Konsumsi', 'Barang konsumsi dan makanan', '2026-02-25 03:08:40', '2026-02-25 03:08:40');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int NOT NULL,
  `id_barang` int NOT NULL,
  `jenis_transaksi` enum('masuk','keluar') NOT NULL,
  `jumlah` int NOT NULL,
  `tanggal_transaksi` datetime DEFAULT CURRENT_TIMESTAMP,
  `keterangan` text,
  `petugas_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `id_barang`, `jenis_transaksi`, `jumlah`, `tanggal_transaksi`, `keterangan`, `petugas_id`, `created_at`, `updated_at`) VALUES
(2, 4, 'masuk', 2, '2026-01-20 11:44:21', 'Pengadaan lab komputer', NULL, '2026-02-25 03:10:43', '2026-02-25 03:10:43'),
(4, 13, 'masuk', 5, '2026-01-24 09:36:24', 'pembaharuan', NULL, '2026-02-25 03:10:43', '2026-02-25 03:10:43'),
(5, 14, 'keluar', 3, '2026-01-24 09:46:14', 'hbi', NULL, '2026-02-25 03:10:43', '2026-02-25 03:10:43'),
(6, 13, 'keluar', 4, '2026-02-02 08:03:43', 'pengiriman ke customer', NULL, '2026-02-25 03:10:43', '2026-02-25 03:10:43'),
(7, 3, 'masuk', 4, '2026-02-03 14:12:44', 'Di Pinjam', NULL, '2026-02-25 03:10:43', '2026-02-25 03:10:43'),
(8, 15, 'masuk', 3, '2026-02-04 13:00:12', 'baru', NULL, '2026-02-25 03:10:43', '2026-02-25 03:10:43'),
(9, 13, 'masuk', 4, '2026-02-25 10:06:01', 'barang baru', NULL, '2026-02-25 03:10:43', '2026-02-25 03:10:43'),
(10, 3, 'keluar', 3, '2026-02-25 10:06:24', 'di pakai', NULL, '2026-02-25 03:10:43', '2026-02-25 03:10:43'),
(11, 13, 'keluar', 10, '2026-02-28 08:22:29', 'pengiriman', NULL, '2026-02-28 01:22:29', '2026-02-28 01:22:29'),
(12, 5, 'masuk', 90, '2026-02-28 08:22:49', 'penerimaan', NULL, '2026-02-28 01:22:49', '2026-02-28 01:22:49');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `role` enum('admin','petugas','user') NOT NULL DEFAULT 'user',
  `email` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `role`, `email`, `no_hp`, `is_active`, `created_at`, `updated_at`) VALUES
(3, 'ayu', '$2y$10$Sfng7sDZc84v8VDDcoibD.SwV5zfwvsOHUaJVdsrfev9/JXz97n/K', NULL, 'admin', NULL, NULL, 1, '2026-02-28 00:14:14', '2026-02-28 00:14:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_barang` (`id_barang`),
  ADD KEY `petugas_id` (`petugas_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `fk_barang_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`petugas_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
