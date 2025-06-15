-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2025 at 07:50 PM
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
-- Database: `tugas`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_pembelian`
--

CREATE TABLE `detail_pembelian` (
  `DETAIL_PEMBELIAN_ID` varchar(5) NOT NULL,
  `PEMBELIAN_ID` varchar(5) DEFAULT NULL,
  `PRODUK_ID` varchar(5) DEFAULT NULL,
  `JUMLAH` int(11) DEFAULT NULL,
  `HARGA_SATUAN` decimal(12,2) DEFAULT NULL,
  `SUBTOTAL` decimal(12,2) DEFAULT NULL,
  `NAMA_PRODUK_BACKUP` varchar(100) DEFAULT NULL,
  `HARGA_BACKUP` decimal(12,2) DEFAULT NULL,
  `HARGA_BELI` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_pembelian`
--

INSERT INTO `detail_pembelian` (`DETAIL_PEMBELIAN_ID`, `PEMBELIAN_ID`, `PRODUK_ID`, `JUMLAH`, `HARGA_SATUAN`, `SUBTOTAL`, `NAMA_PRODUK_BACKUP`, `HARGA_BACKUP`, `HARGA_BELI`) VALUES
('DB001', 'BU001', 'PD001', 5, 3000.00, 10000.00, 'Pulpen Hitam', 3000.00, 2000.00),
('DB002', 'BU002', 'PD002', 3, 5000.00, 12000.00, 'Buku Tulis 38 Lbr', 5000.00, 4000.00),
('DB003', 'BU003', 'PD003', 8, 12000.00, 88000.00, 'Spidol Warna', 12000.00, 11000.00),
('DB004', 'BU004', 'PD004', 2, 8000.00, 14000.00, 'Pensil Mekanik', 8000.00, 7000.00),
('DB005', 'BU005', 'PD005', 9, 48000.00, 423000.00, 'Kertas A4 500 Lbr', 48000.00, 47000.00),
('DB006', 'BU006', 'PD006', 1, 7000.00, 6000.00, 'Penggaris Besi 30cm', 7000.00, 6000.00),
('DB007', 'BU007', 'PD007', 6, 2000.00, 6000.00, 'Penghapus Putih', 2000.00, 1000.00),
('DB008', 'BU008', 'PD008', 10, 25000.00, 240000.00, 'Cat Air 12 Warna', 25000.00, 24000.00),
('DB009', 'BU009', 'PD009', 4, 65000.00, 256000.00, 'Kalkulator Saku', 65000.00, 64000.00),
('DB010', 'BU010', 'PD010', 7, 3500.00, 17500.00, 'Map Kertas', 3500.00, 2500.00),
('DB011', 'BU011', 'PD011', 5, 9000.00, 40000.00, 'Stapler Mini', 9000.00, 8000.00),
('DB012', 'BU012', 'PD012', 3, 52000.00, 153000.00, 'Kertas HVS Folio', 52000.00, 51000.00),
('DB013', 'BU013', 'PD013', 8, 1500.00, 4000.00, 'Amplop Coklat', 1500.00, 500.00),
('DB014', 'BU014', 'PD014', 2, 3000.00, 4000.00, 'Pulpen Merah', 3000.00, 2000.00),
('DB015', 'BU015', 'PD015', 9, 2500.00, 13500.00, 'Pensil 2B', 2500.00, 1500.00),
('DB016', 'BU016', 'PD016', 1, 1500.00, 500.00, 'Penghapus Kecil', 1500.00, 500.00),
('DB017', 'BU017', 'PD017', 6, 30000.00, 174000.00, 'Cat Poster', 30000.00, 29000.00),
('DB018', 'BU018', 'PD018', 10, 10000.00, 90000.00, 'Spidol Permanent', 10000.00, 9000.00),
('DB019', 'BU019', 'PD019', 4, 4000.00, 12000.00, 'Rautan Pensil', 4000.00, 3000.00),
('DB020', 'BU020', 'PD020', 7, 15000.00, 98000.00, 'Binder A5', 15000.00, 14000.00);

--
-- Triggers `detail_pembelian`
--
DELIMITER $$
CREATE TRIGGER `backup_data_produk_detail_pembelian` BEFORE INSERT ON `detail_pembelian` FOR EACH ROW BEGIN
    DECLARE nama_produk_temp VARCHAR(100);
    DECLARE harga_temp DECIMAL(12,2);
    
    SELECT nama_produk, harga_satuan INTO nama_produk_temp, harga_temp
    FROM produk
    WHERE produk_id = NEW.produk_id;

    SET NEW.nama_produk_backup = nama_produk_temp;
    SET NEW.harga_backup = harga_temp;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_detail_pembelian` BEFORE INSERT ON `detail_pembelian` FOR EACH ROW BEGIN
    DECLARE v_harga DECIMAL(12,2);
    DECLARE v_nama_produk VARCHAR(100);

    SELECT harga_satuan, nama_produk INTO v_harga, v_nama_produk
    FROM produk
    WHERE produk_id = NEW.produk_id;

    SET NEW.harga_satuan = v_harga;
    SET NEW.subtotal = NEW.jumlah * v_harga;

    SET NEW.harga_backup = v_harga;
    SET NEW.nama_produk_backup = v_nama_produk;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_detail_pembelian` BEFORE UPDATE ON `detail_pembelian` FOR EACH ROW BEGIN
    DECLARE v_harga DECIMAL(12,2);
    DECLARE v_nama_produk VARCHAR(100);

   
    SELECT harga_satuan, nama_produk INTO v_harga, v_nama_produk
    FROM produk
    WHERE produk_id = NEW.produk_id;

  
    SET NEW.harga_satuan = v_harga;
    SET NEW.subtotal = NEW.jumlah * v_harga;

   
    SET NEW.harga_backup = v_harga;
    SET NEW.nama_produk_backup = v_nama_produk;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_harga_produk_detail_pembelian` BEFORE UPDATE ON `detail_pembelian` FOR EACH ROW BEGIN
  DECLARE harga_jual INT;

 
  SELECT HARGA_SATUAN INTO harga_jual
  FROM PRODUK
  WHERE PRODUK_ID = NEW.PRODUK_ID;

  
  IF NEW.PRODUK_ID != OLD.PRODUK_ID OR NEW.HARGA_BELI != GREATEST(0, harga_jual - 1000) THEN
    SET NEW.HARGA_BELI = GREATEST(0, harga_jual - 1000);
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `hitung_subtotal_detail_pembelian` BEFORE INSERT ON `detail_pembelian` FOR EACH ROW BEGIN
    SET NEW.subtotal = NEW.jumlah * NEW.harga_beli;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `kurangi_stok_setelah_hapus_detail` AFTER DELETE ON `detail_pembelian` FOR EACH ROW BEGIN
    UPDATE produk
    SET stok = stok - OLD.jumlah
    WHERE produk_id = OLD.produk_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `set_harga_beli_before_insert` BEFORE INSERT ON `detail_pembelian` FOR EACH ROW BEGIN
  DECLARE harga_jual INT;

  SELECT HARGA_SATUAN INTO harga_jual
  FROM PRODUK
  WHERE PRODUK_ID = NEW.PRODUK_ID;

  SET NEW.HARGA_BELI = GREATEST(0, harga_jual - 1000);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tambah_stok_setelah_pembelian` AFTER INSERT ON `detail_pembelian` FOR EACH ROW BEGIN
    UPDATE produk
    SET stok = stok + NEW.jumlah
    WHERE produk_id = NEW.produk_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_stok_setelah_ubah_detail` AFTER UPDATE ON `detail_pembelian` FOR EACH ROW BEGIN
    UPDATE produk
    SET stok = stok - OLD.jumlah + NEW.jumlah
    WHERE produk_id = NEW.produk_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_subtotal_detail_pembelian` BEFORE UPDATE ON `detail_pembelian` FOR EACH ROW BEGIN
    SET NEW.subtotal = NEW.jumlah * NEW.harga_beli;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_total_pembelian_after_delete` AFTER DELETE ON `detail_pembelian` FOR EACH ROW BEGIN
    UPDATE pembelian
    SET total = (
        SELECT COALESCE(SUM(subtotal), 0)
        FROM detail_pembelian
        WHERE pembelian_id = OLD.pembelian_id
    )
    WHERE pembelian_id = OLD.pembelian_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_total_pembelian_after_insert` AFTER INSERT ON `detail_pembelian` FOR EACH ROW BEGIN
    UPDATE pembelian
    SET total = (
        SELECT SUM(subtotal)
        FROM detail_pembelian
        WHERE pembelian_id = NEW.pembelian_id
    )
    WHERE pembelian_id = NEW.pembelian_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_total_pembelian_after_update` AFTER UPDATE ON `detail_pembelian` FOR EACH ROW BEGIN
    UPDATE pembelian
    SET total = (
        SELECT SUM(subtotal)
        FROM detail_pembelian
        WHERE pembelian_id = NEW.pembelian_id
    )
    WHERE pembelian_id = NEW.pembelian_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `detail_penjualan`
--

CREATE TABLE `detail_penjualan` (
  `DETAIL_ID` varchar(5) NOT NULL,
  `PENJUALAN_ID` varchar(5) DEFAULT NULL,
  `PRODUK_ID` varchar(5) DEFAULT NULL,
  `JUMLAH` int(11) NOT NULL,
  `NAMA_PRODUK` varchar(100) DEFAULT NULL,
  `HARGA_SATUAN` decimal(12,2) DEFAULT NULL,
  `SUBTOTAL` decimal(12,2) GENERATED ALWAYS AS (`JUMLAH` * `HARGA_SATUAN`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_penjualan`
--

INSERT INTO `detail_penjualan` (`DETAIL_ID`, `PENJUALAN_ID`, `PRODUK_ID`, `JUMLAH`, `NAMA_PRODUK`, `HARGA_SATUAN`) VALUES
('DS001', 'SL001', 'PD001', 3, 'Pulpen Hitam', 3000.00),
('DS002', 'SL002', 'PD002', 2, 'Buku Tulis 38 Lbr', 5000.00),
('DS003', 'SL003', 'PD003', 5, 'Spidol Warna', 12000.00),
('DS004', 'SL004', 'PD004', 1, 'Pensil Mekanik', 8000.00),
('DS005', 'SL005', 'PD005', 4, 'Kertas A4 500 Lbr', 48000.00),
('DS006', 'SL006', 'PD006', 3, 'Penggaris Besi 30cm', 7000.00),
('DS007', 'SL007', 'PD007', 2, 'Penghapus Putih', 2000.00),
('DS008', 'SL008', 'PD008', 5, 'Cat Air 12 Warna', 25000.00),
('DS009', 'SL009', 'PD009', 1, 'Kalkulator Saku', 65000.00),
('DS010', 'SL010', 'PD010', 4, 'Map Kertas', 3500.00),
('DS011', 'SL011', 'PD011', 1, 'Stapler Mini', 9000.00),
('DS012', 'SL012', 'PD012', 2, 'Kertas HVS Folio', 52000.00),
('DS013', 'SL013', 'PD013', 3, 'Amplop Coklat', 1500.00),
('DS014', 'SL014', 'PD014', 4, 'Pulpen Merah', 3000.00),
('DS015', 'SL015', 'PD015', 5, 'Pensil 2B', 2500.00),
('DS016', 'SL016', 'PD016', 6, 'Penghapus Kecil', 1500.00),
('DS017', 'SL017', 'PD017', 7, 'Cat Poster', 30000.00),
('DS018', 'SL018', 'PD018', 8, 'Spidol Permanent', 10000.00),
('DS019', 'SL019', 'PD019', 9, 'Rautan Pensil', 4000.00),
('DS020', 'SL020', 'PD020', 10, 'Binder A5', 15000.00);

--
-- Triggers `detail_penjualan`
--
DELIMITER $$
CREATE TRIGGER `CEK_STOK_CUKUP` BEFORE INSERT ON `detail_penjualan` FOR EACH ROW BEGIN
    DECLARE STOK_SAAT_INI INT;

    SELECT STOK INTO STOK_SAAT_INI
    FROM PRODUK
    WHERE PRODUK_ID = NEW.PRODUK_ID;

    IF STOK_SAAT_INI < NEW.JUMLAH THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Stok tidak mencukupi untuk penjualan ini';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `HITUNG_SUBTOTAL_DAN_KURANGI_STOK` BEFORE INSERT ON `detail_penjualan` FOR EACH ROW BEGIN
    DECLARE HARGA DECIMAL(12,2);

    SELECT HARGA_SATUAN INTO HARGA
    FROM PRODUK
    WHERE PRODUK_ID = NEW.PRODUK_ID;

    SET NEW.SUBTOTAL = HARGA * NEW.JUMLAH;

    UPDATE PRODUK
    SET STOK = STOK - NEW.JUMLAH
    WHERE PRODUK_ID = NEW.PRODUK_ID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `UPDATE_TOTAL_HARGA_AFTER_DELETE` AFTER DELETE ON `detail_penjualan` FOR EACH ROW BEGIN
    UPDATE PENJUALAN
    SET TOTAL_HARGA = (
        SELECT IFNULL(SUM(SUBTOTAL), 0)
        FROM DETAIL_PENJUALAN
        WHERE PENJUALAN_ID = OLD.PENJUALAN_ID
    )
    WHERE PENJUALAN_ID = OLD.PENJUALAN_ID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `UPDATE_TOTAL_HARGA_AFTER_INSERT` AFTER INSERT ON `detail_penjualan` FOR EACH ROW BEGIN
    UPDATE PENJUALAN
    SET TOTAL_HARGA = (
        SELECT SUM(SUBTOTAL)
        FROM DETAIL_PENJUALAN
        WHERE PENJUALAN_ID = NEW.PENJUALAN_ID
    )
    WHERE PENJUALAN_ID = NEW.PENJUALAN_ID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `UPDATE_TOTAL_HARGA_AFTER_UPDATE` AFTER UPDATE ON `detail_penjualan` FOR EACH ROW BEGIN
    UPDATE PENJUALAN
    SET TOTAL_HARGA = (
        SELECT SUM(SUBTOTAL)
        FROM DETAIL_PENJUALAN
        WHERE PENJUALAN_ID = NEW.PENJUALAN_ID
    )
    WHERE PENJUALAN_ID = NEW.PENJUALAN_ID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_detail_penjualan` BEFORE INSERT ON `detail_penjualan` FOR EACH ROW BEGIN
  DECLARE v_nama_produk VARCHAR(100);
  DECLARE v_harga_satuan DECIMAL(12,2);

  SELECT nama_produk, harga_satuan
  INTO v_nama_produk, v_harga_satuan
  FROM produk
  WHERE produk_id = NEW.produk_id;

  SET NEW.nama_produk = v_nama_produk;
  SET NEW.harga_satuan = v_harga_satuan;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

CREATE TABLE `pembelian` (
  `PEMBELIAN_ID` varchar(5) NOT NULL,
  `TANGGAL` date NOT NULL,
  `SUPPLIER_ID` varchar(5) DEFAULT NULL,
  `NAMA_SUPPLIER_BACKUP` varchar(100) DEFAULT NULL,
  `TOTAL` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembelian`
--

INSERT INTO `pembelian` (`PEMBELIAN_ID`, `TANGGAL`, `SUPPLIER_ID`, `NAMA_SUPPLIER_BACKUP`, `TOTAL`) VALUES
('BU001', '2025-05-01', 'SP001', 'AJM', 10000.00),
('BU002', '2025-05-02', 'SP002', 'Gramedia', 12000.00),
('BU003', '2025-05-03', 'SP003', 'PT Kenko Sinar Indonesia', 88000.00),
('BU004', '2025-05-04', 'SP004', 'PT Tassindo Tasa Industries', 14000.00),
('BU005', '2025-05-05', 'SP005', 'Toko Merah', 423000.00),
('BU006', '2025-05-06', 'SP006', 'CV Pena Cipta', 6000.00),
('BU007', '2025-05-07', 'SP007', 'PT Pelita Stationery', 6000.00),
('BU008', '2025-05-08', 'SP008', 'PT Pena Dunia', 240000.00),
('BU009', '2025-05-09', 'SP009', 'ATK Surya Jaya', 256000.00),
('BU010', '2025-05-10', 'SP010', 'Toko Tulis Nusantara', 17500.00),
('BU011', '2025-06-01', 'SP001', 'AJM', 40000.00),
('BU012', '2025-06-02', 'SP002', 'Gramedia', 153000.00),
('BU013', '2025-06-03', 'SP003', 'PT Kenko Sinar Indonesia', 4000.00),
('BU014', '2025-06-04', 'SP004', 'PT Tassindo Tasa Industries', 4000.00),
('BU015', '2025-06-05', 'SP005', 'Toko Merah', 13500.00),
('BU016', '2025-06-06', 'SP006', 'CV Pena Cipta', 500.00),
('BU017', '2025-06-07', 'SP007', 'PT Pelita Stationery', 174000.00),
('BU018', '2025-06-08', 'SP008', 'PT Pena Dunia', 90000.00),
('BU019', '2025-06-09', 'SP009', 'ATK Surya Jaya', 12000.00),
('BU020', '2025-06-10', 'SP010', 'Toko Tulis Nusantara', 98000.00);

--
-- Triggers `pembelian`
--
DELIMITER $$
CREATE TRIGGER `before_insert_pembelian` BEFORE INSERT ON `pembelian` FOR EACH ROW BEGIN
    DECLARE nama_supplier_temp VARCHAR(100);

   
    SELECT nama_supplier 
    INTO nama_supplier_temp
    FROM supplier
    WHERE supplier_id = NEW.supplier_id;

    
    SET NEW.nama_supplier_backup = nama_supplier_temp;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_pembelian` BEFORE UPDATE ON `pembelian` FOR EACH ROW BEGIN
    DECLARE nama_supplier_temp VARCHAR(100);

    
    SELECT nama_supplier 
    INTO nama_supplier_temp
    FROM supplier
    WHERE supplier_id = NEW.supplier_id;

    
    SET NEW.nama_supplier_backup = nama_supplier_temp;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `PENJUALAN_ID` varchar(5) NOT NULL,
  `TANGGAL` date NOT NULL,
  `TOTAL_HARGA` decimal(12,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penjualan`
--

INSERT INTO `penjualan` (`PENJUALAN_ID`, `TANGGAL`, `TOTAL_HARGA`) VALUES
('SL001', '2025-05-01', 9000.00),
('SL002', '2025-05-02', 10000.00),
('SL003', '2025-05-03', 60000.00),
('SL004', '2025-05-04', 8000.00),
('SL005', '2025-05-05', 192000.00),
('SL006', '2025-05-06', 21000.00),
('SL007', '2025-05-07', 4000.00),
('SL008', '2025-05-08', 125000.00),
('SL009', '2025-05-09', 65000.00),
('SL010', '2025-05-10', 14000.00),
('SL011', '2025-06-01', 9000.00),
('SL012', '2025-06-02', 104000.00),
('SL013', '2025-06-03', 4500.00),
('SL014', '2025-06-04', 12000.00),
('SL015', '2025-06-05', 12500.00),
('SL016', '2025-06-06', 9000.00),
('SL017', '2025-06-07', 210000.00),
('SL018', '2025-06-08', 80000.00),
('SL019', '2025-06-09', 36000.00),
('SL020', '2025-06-10', 150000.00);

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
('PD001', 'Pulpen Hitam', 'Alat Tulis', 52, 3000.00, 'SP001'),
('PD002', 'Buku Tulis 38 Lbr', 'Alat Tulis', 71, 5000.00, 'SP002'),
('PD003', 'Spidol Warna', 'Alat Gambar', 28, 12000.00, 'SP003'),
('PD004', 'Pensil Mekanik', 'Alat Tulis', 31, 8000.00, 'SP004'),
('PD005', 'Kertas A4 500 Lbr', 'Kertas', 95, 48000.00, 'SP005'),
('PD006', 'Penggaris Besi 30cm', 'Alat Ukur', 38, 7000.00, 'SP006'),
('PD007', 'Penghapus Putih', 'Alat Tulis', 104, 2000.00, 'SP007'),
('PD008', 'Cat Air 12 Warna', 'Alat Gambar', 25, 25000.00, 'SP008'),
('PD009', 'Kalkulator Saku', 'Elektronik', 18, 65000.00, 'SP009'),
('PD010', 'Map Kertas', 'Aksesoris Kantor', 63, 3500.00, 'SP010'),
('PD011', 'Stapler Mini', 'Aksesoris Kantor', 49, 9000.00, 'SP011'),
('PD012', 'Kertas HVS Folio', 'Kertas', 81, 52000.00, 'SP012'),
('PD013', 'Amplop Coklat', 'Kertas', 105, 1500.00, 'SP013'),
('PD014', 'Pulpen Merah', 'Alat Tulis', 53, 3000.00, 'SP014'),
('PD015', 'Pensil 2B', 'Alat Tulis', 124, 2500.00, 'SP015'),
('PD016', 'Penghapus Kecil', 'Alat Tulis', 105, 1500.00, 'SP016'),
('PD017', 'Cat Poster', 'Alat Gambar', 17, 30000.00, 'SP017'),
('PD018', 'Spidol Permanent', 'Alat Tulis', 37, 10000.00, 'SP018'),
('PD019', 'Rautan Pensil', 'Aksesoris Kantor', 55, 4000.00, 'SP019'),
('PD020', 'Binder A5', 'Alat Tulis', 22, 15000.00, 'SP020');

--
-- Triggers `produk`
--
DELIMITER $$
CREATE TRIGGER `after_update_harga_produk` AFTER UPDATE ON `produk` FOR EACH ROW BEGIN
  IF NEW.HARGA_SATUAN != OLD.HARGA_SATUAN THEN
    UPDATE detail_pembelian
    SET HARGA_BELI = GREATEST(0, NEW.HARGA_SATUAN - 1000)
    WHERE PRODUK_ID = NEW.PRODUK_ID;
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_delete_produk` BEFORE DELETE ON `produk` FOR EACH ROW BEGIN
  UPDATE detail_penjualan
  SET
    nama_produk = OLD.nama_produk,
    harga_satuan = OLD.harga_satuan
  WHERE produk_id = OLD.produk_id;
END
$$
DELIMITER ;

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
('SP002', 'Gramedia', 'Kota Yogyakarta, DI Yogyakarta', '081345678901'),
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

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `ID` int(11) NOT NULL,
  `USERNAME` varchar(50) NOT NULL,
  `PASSWORD` varchar(500) NOT NULL,
  `DIBUAT` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`ID`, `USERNAME`, `PASSWORD`, `DIBUAT`) VALUES
(1, 'Sounda', '$2y$10$SAU8nD4IPdrJSu5NmViCZ.Cq3v9MAhfwaOYKxZseNt2XmkS1jSOsu', '2025-05-19 18:29:17'),
(2, 'Omedetou', '$2y$10$yxruejM5pEbXuOF7Rqebs.XBScPwR4bpRrXkXNtfOg5tljcO/uyTO', '2025-06-07 09:29:45'),
(3, 'Kucing', '$2y$10$RDLXfCrOj3a0PSdpzWqX8u.OdHr5SGUJo5B.fHcLGb2.YBJ2Qq3I2', '2025-06-07 17:08:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  ADD PRIMARY KEY (`DETAIL_PEMBELIAN_ID`),
  ADD KEY `PEMBELIAN_ID` (`PEMBELIAN_ID`),
  ADD KEY `PRODUK_ID` (`PRODUK_ID`);

--
-- Indexes for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD PRIMARY KEY (`DETAIL_ID`),
  ADD KEY `PENJUALAN_ID` (`PENJUALAN_ID`),
  ADD KEY `PRODUK_ID` (`PRODUK_ID`);

--
-- Indexes for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`PEMBELIAN_ID`),
  ADD KEY `SUPPLIER_ID` (`SUPPLIER_ID`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`PENJUALAN_ID`);

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
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `USERNAME` (`USERNAME`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  ADD CONSTRAINT `detail_pembelian_ibfk_1` FOREIGN KEY (`PEMBELIAN_ID`) REFERENCES `pembelian` (`PEMBELIAN_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_pembelian_ibfk_2` FOREIGN KEY (`PRODUK_ID`) REFERENCES `produk` (`PRODUK_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD CONSTRAINT `detail_penjualan_ibfk_1` FOREIGN KEY (`PENJUALAN_ID`) REFERENCES `penjualan` (`PENJUALAN_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_penjualan_ibfk_2` FOREIGN KEY (`PRODUK_ID`) REFERENCES `produk` (`PRODUK_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD CONSTRAINT `pembelian_ibfk_1` FOREIGN KEY (`SUPPLIER_ID`) REFERENCES `supplier` (`SUPPLIER_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`SUPPLIER_ID`) REFERENCES `supplier` (`SUPPLIER_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
