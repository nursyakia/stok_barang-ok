-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 09 Apr 2025 pada 06.44
-- Versi server: 8.0.17
-- Versi PHP: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbstokbarang`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbdetailpenjualan`
--

CREATE TABLE `tbdetailpenjualan` (
  `DetailID` int(11) NOT NULL,
  `PenjualanID` int(11) NOT NULL,
  `ProdukID` int(11) NOT NULL,
  `JumlahProduk` int(11) NOT NULL,
  `Subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tblaporanpembelian`
--

CREATE TABLE `tblaporanpembelian` (
  `id_pembelian` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `kode_barang` varchar(20) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `supplier` varchar(100) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `satuan` varchar(20) NOT NULL,
  `harga_beli` decimal(15,2) NOT NULL,
  `total` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `tblaporanpembelian`
--

INSERT INTO `tblaporanpembelian` (`id_pembelian`, `tanggal`, `kode_barang`, `nama_barang`, `supplier`, `jumlah`, `satuan`, `harga_beli`, `total`) VALUES
(1, '2025-04-05', 'BRG-001', 'Laptop Lenovo', 'syakia', 1, 'box', '15.00', '15.00'),
(2, '2025-04-05', 'BRG-002', 'Laptop Acer', 'aqella', 2, 'box', '30.00', '60.00'),
(3, '2025-04-05', 'BRG-003', 'Laptop HP', 'aidil', 1, 'box', '10.00', '10.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tblaporanstock`
--

CREATE TABLE `tblaporanstock` (
  `id` int(11) NOT NULL,
  `kode_barang` varchar(20) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `satuan` varchar(20) NOT NULL,
  `jumlah_stock` int(11) NOT NULL,
  `stock_minimal` int(11) NOT NULL,
  `status` enum('(''Stok Kurang'', ''Stok Cukup'')','','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `tblaporanstock`
--

INSERT INTO `tblaporanstock` (`id`, `kode_barang`, `nama_barang`, `satuan`, `jumlah_stock`, `stock_minimal`, `status`) VALUES
(6, 'BRG-001', 'Laptop Lenovo', 'box', 5, 15, ''),
(7, 'BRG-002', 'Laptop Acer', 'box', 4, 5, '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbpelanggan`
--

CREATE TABLE `tbpelanggan` (
  `PelangganID` int(11) NOT NULL,
  `NamaPelanggan` varchar(255) NOT NULL,
  `Alamat` text NOT NULL,
  `Nomor` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `tbpelanggan`
--

INSERT INTO `tbpelanggan` (`PelangganID`, `NamaPelanggan`, `Alamat`, `Nomor`) VALUES
(5, 'nursyakia', 'jl. lingkar setu cikaret', '08966666'),
(11, 'aqella', 'Jl. Depok margonda', '0896443764'),
(12, 'syakia', 'Jl. Raya Jakarta Bogor, No. 36', '08956295445445'),
(13, 'syakia', 'JL.Lingkar setu cikaret', '0896443764');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbpembelian`
--

CREATE TABLE `tbpembelian` (
  `id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `tanggal_pembelian` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `tbpembelian`
--

INSERT INTO `tbpembelian` (`id`, `produk_id`, `jumlah`, `gambar`, `tanggal_pembelian`) VALUES
(1, 4, 1, 'acer.jpg', '2025-04-08 17:36:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbpenjualan`
--

CREATE TABLE `tbpenjualan` (
  `PenjualanID` int(11) NOT NULL,
  `TanggalPenjualan` date NOT NULL,
  `TotalHarga` decimal(10,2) NOT NULL,
  `PelangganID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbproduk`
--

CREATE TABLE `tbproduk` (
  `ProdukID` int(11) NOT NULL,
  `NamaProduk` varchar(255) NOT NULL,
  `Harga` decimal(10,2) NOT NULL,
  `Stok` int(11) NOT NULL,
  `gambar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `tbproduk`
--

INSERT INTO `tbproduk` (`ProdukID`, `NamaProduk`, `Harga`, `Stok`, `gambar`) VALUES
(4, 'Laptop', '10000000.00', 2, 'acer.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbsupplier`
--

CREATE TABLE `tbsupplier` (
  `id` int(11) NOT NULL,
  `nama_supplier` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `kontak` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `tbsupplier`
--

INSERT INTO `tbsupplier` (`id`, `nama_supplier`, `alamat`, `kontak`) VALUES
(1, 'PT. Sentosa jaya', 'Jl. Raya jakarta No.36, Cibinong', '0889605036500'),
(2, 'PT. Good day', 'Jl. Depok margonda No.27, Depok', '08956295445'),
(3, 'PT. Jaya abadi', 'Jl. lingkar setu cikaret', '0889605036500');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbusers`
--

CREATE TABLE `tbusers` (
  `userID` int(11) NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tbdetailpenjualan`
--
ALTER TABLE `tbdetailpenjualan`
  ADD PRIMARY KEY (`DetailID`),
  ADD KEY `PenjualanID` (`PenjualanID`),
  ADD KEY `ProdukID` (`ProdukID`);

--
-- Indeks untuk tabel `tblaporanpembelian`
--
ALTER TABLE `tblaporanpembelian`
  ADD PRIMARY KEY (`id_pembelian`);

--
-- Indeks untuk tabel `tblaporanstock`
--
ALTER TABLE `tblaporanstock`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbpelanggan`
--
ALTER TABLE `tbpelanggan`
  ADD PRIMARY KEY (`PelangganID`);

--
-- Indeks untuk tabel `tbpembelian`
--
ALTER TABLE `tbpembelian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `produk_id` (`produk_id`);

--
-- Indeks untuk tabel `tbpenjualan`
--
ALTER TABLE `tbpenjualan`
  ADD PRIMARY KEY (`PenjualanID`),
  ADD KEY `PelangganID` (`PelangganID`);

--
-- Indeks untuk tabel `tbproduk`
--
ALTER TABLE `tbproduk`
  ADD PRIMARY KEY (`ProdukID`);

--
-- Indeks untuk tabel `tbsupplier`
--
ALTER TABLE `tbsupplier`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbusers`
--
ALTER TABLE `tbusers`
  ADD PRIMARY KEY (`userID`),
  ADD KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tbdetailpenjualan`
--
ALTER TABLE `tbdetailpenjualan`
  MODIFY `DetailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tblaporanpembelian`
--
ALTER TABLE `tblaporanpembelian`
  MODIFY `id_pembelian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tblaporanstock`
--
ALTER TABLE `tblaporanstock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `tbpelanggan`
--
ALTER TABLE `tbpelanggan`
  MODIFY `PelangganID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `tbpembelian`
--
ALTER TABLE `tbpembelian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tbpenjualan`
--
ALTER TABLE `tbpenjualan`
  MODIFY `PenjualanID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tbproduk`
--
ALTER TABLE `tbproduk`
  MODIFY `ProdukID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tbsupplier`
--
ALTER TABLE `tbsupplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `tbusers`
--
ALTER TABLE `tbusers`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tbdetailpenjualan`
--
ALTER TABLE `tbdetailpenjualan`
  ADD CONSTRAINT `tbdetailpenjualan_ibfk_1` FOREIGN KEY (`PenjualanID`) REFERENCES `tbpenjualan` (`PenjualanID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `tbdetailpenjualan_ibfk_2` FOREIGN KEY (`ProdukID`) REFERENCES `tbproduk` (`ProdukID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `tbpembelian`
--
ALTER TABLE `tbpembelian`
  ADD CONSTRAINT `tbpembelian_ibfk_1` FOREIGN KEY (`produk_id`) REFERENCES `tbproduk` (`ProdukID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `tbpenjualan`
--
ALTER TABLE `tbpenjualan`
  ADD CONSTRAINT `tbpenjualan_ibfk_1` FOREIGN KEY (`PelangganID`) REFERENCES `tbpelanggan` (`PelangganID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
