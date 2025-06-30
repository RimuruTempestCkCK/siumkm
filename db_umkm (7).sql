-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2025 at 07:09 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_umkm`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_umkm`
--

CREATE TABLE `data_umkm` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_usaha` varchar(100) NOT NULL,
  `bidang_usaha` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal_input` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_umkm`
--

INSERT INTO `data_umkm` (`id`, `id_user`, `nama_usaha`, `bidang_usaha`, `alamat`, `deskripsi`, `tanggal_input`) VALUES
(1, 13, 'Toko Kelontong Tira', 'Kuliner', 'Padang', 'Kelontong rumahan', '2025-06-22'),
(2, 11, 'Kelontong Jaya', 'Kue Manis', 'Padang Panjang', 'Aneka kue', '2025-06-22'),
(3, 14, 'Sembako Murah', 'Retail', 'Bukittinggi', 'Usaha sembako dan kebutuhan pokok', '2025-06-22'),
(4, 15, 'Bacot UMKM', 'Tuak', 'Padang', 'Kedai Tuak', '2025-06-22'),
(5, 16, 'UMKM OYO', 'Kerajinan', 'Padang Pendek', 'Usaha Saya Yang Baru', '2025-06-22');

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail` int(11) NOT NULL,
  `id_transaksi` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail`, `id_transaksi`, `id_produk`, `jumlah`, `harga`, `subtotal`) VALUES
(1, 1, 1, 10, 6500.00, 65000.00),
(2, 1, 2, 5, 6000.00, 30000.00),
(3, 2, 3, 10, 15000.00, 150000.00),
(4, 3, 4, 10, 17000.00, 170000.00),
(5, 4, 2, 10, 6000.00, 60000.00),
(6, 5, 5, 15, 12000.00, 180000.00);

-- --------------------------------------------------------

--
-- Table structure for table `modal`
--

CREATE TABLE `modal` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `jumlah_modal` decimal(15,2) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modal`
--

INSERT INTO `modal` (`id`, `id_user`, `jumlah_modal`, `tanggal`, `keterangan`) VALUES
(1, 13, 50000000.00, '2025-06-22', 'Modal awal'),
(2, 11, 30000000.00, '2025-06-22', 'Modal pinjaman'),
(3, 14, 40000000.00, '2025-06-22', 'Tabungan pribadi'),
(4, 1, 100000000.00, '2025-06-22', 'Modal Awal Usaha Kedai Tuak');

-- --------------------------------------------------------

--
-- Table structure for table `pemasukan`
--

CREATE TABLE `pemasukan` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemasukan`
--

INSERT INTO `pemasukan` (`id`, `id_user`, `id_produk`, `jumlah`, `tanggal`, `keterangan`) VALUES
(1, 13, 1, 130000.00, '2025-06-22', 'Penjualan gelas'),
(2, 13, 2, 240000.00, '2025-06-22', 'Penjualan teh botol'),
(3, 11, 3, 300000.00, '2025-06-22', 'Penjualan brownies'),
(4, 14, 4, 255000.00, '2025-06-22', 'Penjualan minyak'),
(5, 14, 5, 480000.00, '2025-06-22', 'Penjualan gula'),
(6, 13, 2, 180000.00, '2025-06-22', 'Repeat order teh botol'),
(7, 14, 5, 360000.00, '2025-06-22', 'Penjualan gula lagi'),
(8, 1, 6, 40000.00, '2025-06-22', 'Pelanggan Pertama Cair Nih Bos\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengeluaran`
--

INSERT INTO `pengeluaran` (`id`, `id_user`, `jumlah`, `tanggal`, `keterangan`, `created_at`) VALUES
(1, 13, 20000.00, '2025-06-22', 'Beli kantong plastik', '2025-06-21 19:58:47'),
(2, 11, 50000.00, '2025-06-22', 'Beli bahan brownies', '2025-06-21 19:58:47'),
(3, 14, 75000.00, '2025-06-22', 'Beli karung beras', '2025-06-21 19:58:47'),
(4, 13, 30000.00, '2025-06-22', 'Ongkir dari distributor', '2025-06-21 19:58:47'),
(5, 1, 1500000.00, '2025-06-22', 'Gaji Karyawan', '2025-06-21 20:15:30');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `harga_beli` decimal(10,2) NOT NULL,
  `harga_jual` decimal(10,2) NOT NULL,
  `stok` int(11) DEFAULT 0,
  `satuan` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `id_user`, `nama_produk`, `harga_beli`, `harga_jual`, `stok`, `satuan`) VALUES
(1, 13, 'Gelas Plastik', 5000.00, 6500.00, 100, 'pcs'),
(2, 13, 'Teh Botol', 4000.00, 6000.00, 80, 'botol'),
(3, 11, 'Brownies Cokelat', 10000.00, 15000.00, 50, 'pcs'),
(4, 14, 'Minyak Goreng', 13000.00, 17000.00, 75, 'liter'),
(5, 14, 'Gula Pasir', 9000.00, 12000.00, 100, 'kg'),
(6, 1, 'Tuak Biasa', 15000.00, 20000.00, 198, 'liter');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `kode_transaksi` varchar(20) NOT NULL,
  `tanggal` datetime DEFAULT current_timestamp(),
  `total_harga` decimal(12,2) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `kode_transaksi`, `tanggal`, `total_harga`, `id_user`) VALUES
(1, 'TRX001', '2025-06-22 02:58:53', 120000.00, 13),
(2, 'TRX002', '2025-06-22 02:58:53', 150000.00, 11),
(3, 'TRX003', '2025-06-22 02:58:53', 170000.00, 14),
(4, 'TRX004', '2025-06-22 02:58:53', 90000.00, 13),
(5, 'TRX005', '2025-06-22 02:58:53', 180000.00, 14);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama`, `role`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Admin', 'admin'),
(2, 'owner', '72122ce96bfec66e2396d2e25225d70a', 'Owner UMKM ', 'admin'),
(11, 'user2', '7e58d63b60197ceb55a1c487989a3720', 'User Ganteng 2', 'user'),
(13, 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 'Tira Resma Yanti', 'user'),
(14, 'user1', '24c9e15e52afc47c225b757e7bee1f9d', 'User Paling Ganteng', 'user'),
(15, 'user3', '92877af70a45fd6a2ed7fe81e1236b78', 'user3', 'user'),
(16, 'user4', '3f02ebe3d7929b091e3d8ccfde2f3bc6', 'Aku User Baru Bang', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_umkm`
--
ALTER TABLE `data_umkm`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `modal`
--
ALTER TABLE `modal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pemasukan`
--
ALTER TABLE `pemasukan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pengeluaran_user` (`id_user`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD UNIQUE KEY `kode_transaksi` (`kode_transaksi`),
  ADD KEY `id_user` (`id_user`);

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
-- AUTO_INCREMENT for table `data_umkm`
--
ALTER TABLE `data_umkm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `modal`
--
ALTER TABLE `modal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pemasukan`
--
ALTER TABLE `pemasukan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `data_umkm`
--
ALTER TABLE `data_umkm`
  ADD CONSTRAINT `data_umkm_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Constraints for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`),
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`);

--
-- Constraints for table `pemasukan`
--
ALTER TABLE `pemasukan`
  ADD CONSTRAINT `pemasukan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Constraints for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD CONSTRAINT `fk_pengeluaran_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
