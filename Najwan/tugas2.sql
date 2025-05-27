-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 04:57 PM
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
-- Database: `tugas2`
--

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `PRODUK_ID` varchar(5) NOT NULL,
  `NAMA_PRODUK` varchar(100) NOT NULL,
  `KATEGORI` varchar(50) DEFAULT NULL,
  `STOK` int(11) DEFAULT 0,
  `HARGA_SATUAN` decimal(12,2) DEFAULT NULL,
  `SUPPLIER_ID` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`PRODUK_ID`, `NAMA_PRODUK`, `KATEGORI`, `STOK`, `HARGA_SATUAN`, `SUPPLIER_ID`) VALUES
('PD002', 'Buku Tulis 38 Lbr', 'Alat Tulis', 70, 5000.00, 'SP001'),
('PD003', 'Spidol Warna', 'Alat Gambar', 25, 12000.00, 'SP003'),
('PD004', 'Pensil Mekanik', 'Alat Tulis', 30, 8000.00, 'SP004'),
('PD005', 'Kertas A4 500 Lbr', 'Kertas', 90, 48000.00, 'SP005'),
('PD006', 'Penggaris Besi 30cm', 'Alat Ukur', 40, 7000.00, 'SP006'),
('PD007', 'Penghapus Putih', 'Alat Tulis', 100, 2000.00, 'SP007'),
('PD008', 'Cat Air 12 Warna', 'Alat Gambar', 20, 25000.00, 'SP008'),
('PD009', 'Kalkulator Saku', 'Elektronik', 15, 65000.00, 'SP009'),
('PD010', 'Map Kertas', 'Aksesoris Kantor', 60, 3500.00, 'SP010'),
('PD011', 'Stapler Mini', 'Aksesoris Kantor', 45, 9000.00, 'SP011'),
('PD012', 'Kertas HVS Folio', 'Kertas', 80, 52000.00, 'SP012'),
('PD013', 'Amplop Coklat', 'Kertas', 100, 1500.00, 'SP013'),
('PD014', 'Pulpen Merah', 'Alat Tulis', 55, 3000.00, 'SP014'),
('PD015', 'Pensil 2B', 'Alat Tulis', 120, 2500.00, 'SP015'),
('PD016', 'Penghapus Kecil', 'Alat Tulis', 110, 1500.00, 'SP016'),
('PD017', 'Cat Poster', 'Alat Gambar', 18, 30000.00, 'SP017'),
('PD018', 'Spidol Permanent', 'Alat Tulis', 35, 10000.00, 'SP018'),
('PD019', 'Rautan Pensil', 'Aksesoris Kantor', 60, 4000.00, 'SP019'),
('PD020', 'Binder A5', 'Alat Tulis', 25, 15000.00, 'SP020');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `SUPPLIER_ID` varchar(5) NOT NULL,
  `NAMA_SUPPLIER` varchar(100) NOT NULL,
  `ALAMAT` text DEFAULT NULL,
  `KONTAK` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`SUPPLIER_ID`, `NAMA_SUPPLIER`, `ALAMAT`, `KONTAK`) VALUES
('SP001', 'AJM', 'Kota Semarang, Jawa Tengah', '081234567890'),
('SP003', 'PT Kenko Sinar Indonesia', 'Jakarta Selatan, DKI Jakarta', '081456789012'),
('SP004', 'PT Tassindo Tasa Industries', 'Tangerang, Banten', '081567890123'),
('SP005', 'Toko Merah', 'Sleman, DI Yogyakarta', '081678901234'),
('SP006', 'CV Pena Cipta', 'Surabaya, Jawa Timur', '081789012345'),
('SP007', 'PT Pelita Stationery', 'Bandung, Jawa Barat', '081890123456'),
('SP008', 'PT Pena Dunia', 'Kota Solo, Jawa Tengah', '081901234567'),
('SP009', 'ATK Surya Jaya', 'Medan, Sumatera Utara', '081012345678'),
('SP010', 'Toko Tulis Nusantara', 'Makassar, Sulawesi Selatan', '081123456789'),
('SP011', 'CV Sinar Pena', 'Denpasar, Bali', '082100000001'),
('SP012', 'PT Kertas Abadi', 'Pontianak, Kalimantan Barat', '082100000002'),
('SP013', 'Toko Pena Mas', 'Malang, Jawa Timur', '082100000003'),
('SP014', 'PT Tulis Maju', 'Palembang, Sumatera Selatan', '082100000004'),
('SP015', 'CV Pena Rakyat', 'Pekanbaru, Riau', '082100000005'),
('SP016', 'ATK Jaya Abadi', 'Balikpapan, Kalimantan Timur', '082100000006'),
('SP017', 'Toko Kenari', 'Padang, Sumatera Barat', '082100000007'),
('SP018', 'PT Pena Kencana', 'Cirebon, Jawa Barat', '082100000008'),
('SP019', 'CV Pena Nusantara', 'Mataram, NTB', '082100000009'),
('SP020', 'Toko Alat Kantor Sejahtera', 'Jayapura, Papua', '082100000010');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`PRODUK_ID`),
  ADD KEY `SUPPLIER_ID` (`SUPPLIER_ID`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`SUPPLIER_ID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`SUPPLIER_ID`) REFERENCES `supplier` (`SUPPLIER_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
