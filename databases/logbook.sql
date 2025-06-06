-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 06, 2025 at 08:07 PM
-- Server version: 10.11.10-MariaDB-log
-- PHP Version: 8.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `logbook_magang`
--

-- --------------------------------------------------------

--
-- Table structure for table `Admin`
--

CREATE TABLE `Admin` (
  `admin_id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `Admin`
--

INSERT INTO `Admin` (`admin_id`, `nama`, `email`) VALUES
(1, 'adi', 'adi@gmail.com'),
(2, 'sandi', 'sandi@mail.com');

-- --------------------------------------------------------

--
-- Table structure for table `Karyawan`
--

CREATE TABLE `Karyawan` (
  `karyawan_id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `posisi` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_telp` varchar(15) DEFAULT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `status_kerja` enum('Aktif','Tidak Aktif') DEFAULT 'Aktif',
  `supervisor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `Karyawan`
--

INSERT INTO `Karyawan` (`karyawan_id`, `nama`, `posisi`, `email`, `no_telp`, `tanggal_masuk`, `status_kerja`, `supervisor_id`) VALUES
(2, 'tutut', 'Rs Ulin', 'tutut@mail.com', '088343453400', '2025-05-03', 'Aktif', 2),
(3, 'pero', 'Rs Suaka Insan', 'pero@mail.com', '089345234234', '2025-05-05', 'Aktif', 3),
(4, 'soraya', 'Rs Bayangkara', 'mail@mail.com', '0899345343', '2025-05-07', 'Aktif', 3);

-- --------------------------------------------------------

--
-- Table structure for table `LaporanKinerja`
--

CREATE TABLE `LaporanKinerja` (
  `laporan_id` int(11) NOT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `total_aktivitas` int(11) DEFAULT NULL,
  `total_hambatan` int(11) DEFAULT NULL,
  `rekomendasi` text DEFAULT NULL,
  `laporan_file` blob DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Logbook`
--

CREATE TABLE `Logbook` (
  `logbook_id` int(11) NOT NULL,
  `karyawan_id` int(11) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `aktivitas` varchar(255) NOT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_selesai` time NOT NULL,
  `hambatan` varchar(255) DEFAULT NULL,
  `solusi` varchar(255) DEFAULT NULL,
  `status` enum('belum','tunda','selesai') DEFAULT 'belum',
  `catatan_supervisor` text DEFAULT NULL,
  `supervisor_id` int(11) DEFAULT NULL,
  `bukti_file` varchar(255) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `Logbook`
--

INSERT INTO `Logbook` (`logbook_id`, `karyawan_id`, `tanggal`, `aktivitas`, `waktu_mulai`, `waktu_selesai`, `hambatan`, `solusi`, `status`, `catatan_supervisor`, `supervisor_id`, `bukti_file`, `unit_id`) VALUES
(1, 2, '2025-05-21', 'Pengecekan alat medis', '08:00:00', '09:30:00', 'Tidak ada', '', 'selesai', 'Sudah dikerjakan dengan baik', 2, '-', 1),
(2, 2, '2025-05-21', 'Pengisian logbook harian', '09:30:00', '11:00:00', 'Kesulitan akses jaringan', 'Gunakan jaringan alternatif', 'tunda', 'Perlu ditindaklanjuti', 3, '-', 1),
(3, 2, '2025-05-21', 'Koordinasi dengan perawat', '13:00:00', '14:30:00', 'Tidak ada', '', 'selesai', 'Kerja sama baik', 2, '-', 2),
(4, 2, '2025-05-21', 'Rekap laporan harian', '14:30:00', '16:00:00', 'Tidak ada', '', 'belum', 'Tunggu data lengkap', 3, '-', 2),
(5, 3, '2025-05-21', 'Input data pasien', '08:00:00', '09:30:00', 'Aplikasi sempat error', 'Restart aplikasi', 'tunda', 'Perlu laporan IT', 2, '-', 1),
(6, 3, '2025-05-21', 'Pengecekan ruang rawat', '09:30:00', '11:00:00', 'Tidak ada', '', 'selesai', 'Sudah sesuai SOP', 3, '-', 2),
(7, 3, '2025-05-21', 'Pengisian logbook', '13:00:00', '14:30:00', 'Tidak ada', '', 'selesai', 'Lengkap dan tepat waktu', 2, '-', 1),
(8, 3, '2025-05-21', 'Koordinasi supervisor', '14:30:00', '16:00:00', 'Supervisor tidak tersedia', 'Reschedule meeting', 'tunda', 'Menunggu waktu baru', 3, '-', 2),
(9, 4, '2025-05-21', 'Pelaporan data logistik', '08:00:00', '09:30:00', 'Tidak ada', '', 'selesai', 'Cepat dan akurat', 2, '-', 2),
(10, 4, '2025-05-21', 'Stok opname alat', '09:30:00', '11:00:00', 'Kurangnya data stok', 'Minta laporan ke gudang', 'belum', 'Harus dilengkapi', 3, '-', 1),
(11, 4, '2025-05-21', 'Pengisian logbook', '13:00:00', '14:30:00', 'Tidak ada', '', 'selesai', 'Rapi dan tepat waktu', 2, '-', 1),
(12, 4, '2025-05-21', 'Update SOP', '14:30:00', '16:00:00', 'Belum mendapat template', 'Hubungi admin', 'tunda', 'Menunggu file SOP baru', 3, '-', 2),
(13, 2, '2025-05-22', 'Penginputan data shift pagi', '08:00:00', '09:30:00', 'Tidak ada', '', 'selesai', 'Data sesuai jadwal', 3, '-', 2),
(14, 2, '2025-05-22', 'Koordinasi antar unit', '09:30:00', '11:00:00', 'Tidak ada', '', 'selesai', 'Kolaborasi baik', 2, '-', 1),
(15, 2, '2025-05-22', 'Rekap kebutuhan ruangan', '13:00:00', '14:30:00', 'Tidak ada', '', 'belum', 'Tunggu data lengkap', 3, '-', 1),
(16, 2, '2025-05-22', 'Pengisian logbook', '14:30:00', '16:00:00', 'Tidak ada', '', 'selesai', 'Sudah diunggah', 2, '-', 2),
(17, 3, '2025-05-22', 'Rapat koordinasi', '08:00:00', '09:30:00', 'Supervisor terlambat hadir', 'Lanjutkan sesuai agenda', 'tunda', 'Rapat dilanjutkan siang', 2, '-', 1),
(18, 3, '2025-05-22', 'Input data rekam medis', '09:30:00', '11:00:00', 'Tidak ada', '', 'selesai', 'Input berhasil', 3, '-', 2),
(19, 3, '2025-05-22', 'Pengisian logbook', '13:00:00', '14:30:00', 'Tidak ada', '', 'selesai', 'Lengkap', 2, '-', 2),
(20, 3, '2025-05-22', 'Koordinasi IT', '14:30:00', '16:00:00', 'Server lambat', 'Lapor ke tim IT', 'tunda', 'Menunggu konfirmasi', 3, '-', 1),
(21, 4, '2025-05-22', 'Pelaporan kegiatan harian', '08:00:00', '09:30:00', 'Tidak ada', '', 'selesai', 'Sudah di-review', 2, '-', 1),
(22, 4, '2025-05-22', 'Update data logistik', '09:30:00', '11:00:00', 'Tidak ada', '', 'selesai', 'Data sudah masuk', 3, '-', 2),
(23, 4, '2025-05-22', 'Pengisian logbook', '13:00:00', '14:30:00', 'Tidak ada', '', 'selesai', 'Rutin dilakukan', 2, '-', 2),
(24, 4, '2025-05-22', 'Koordinasi dengan gudang', '14:30:00', '16:00:00', 'Gudang tutup sementara', 'Jadwal ulang kunjungan', 'tunda', 'Jadwal besok pagi', 3, '-', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Pengguna`
--

CREATE TABLE `Pengguna` (
  `pengguna_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Karyawan','Supervisor','Admin') NOT NULL,
  `karyawan_id` int(11) DEFAULT NULL,
  `supervisor_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `Pengguna`
--

INSERT INTO `Pengguna` (`pengguna_id`, `username`, `password`, `role`, `karyawan_id`, `supervisor_id`, `admin_id`) VALUES
(1, 'admin01', '$2y$10$UqgJoJFW4RS61VGqkHsROuEpgsEs43QhUxIKEAxcOpwwKHf6nQZdK', 'Admin', NULL, NULL, 1),
(2, 'admin02', '$2y$10$BbGaJC5s1sFkyzKoCgLMUeHrj7YGJcZGu5sH.z5hbpmisZnggM/LK', 'Admin', NULL, NULL, 2),
(5, 'supervisor01', '$2y$10$67XDZIDOmHG8sS24BBPIYOZ530Bg.aTDVNsJ6UQlrCXh/9yCI4jTS', 'Supervisor', NULL, 2, NULL),
(6, 'supervisor02', '$2y$10$1Q.JVlWxoSnEoi.INFQZe..WyTZ/xbLcNm35vhvRREmW2qZwIde6y', 'Supervisor', NULL, 3, NULL),
(8, 'karyawan01', '$2y$10$O1BzQ0toOqa/Xi3Ie3Oleuo3znV8ChreK0kczEHihxAi1cahSDF2W', 'Karyawan', 2, NULL, NULL),
(9, 'karyawan02', '$2y$10$nLj0G4dmKLHAvHn5KAjfZ.WwSe7zcQYdOD3d1V5kzEaDOUMxSvUqe', 'Karyawan', 3, NULL, NULL),
(11, 'karyawan03', '$2y$10$pfpfdY.CJUbTmWxvJ0Ql/eWRtVl1hCQFn1ISWR8HapoA/esJ950We', 'Karyawan', 4, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Setting`
--

CREATE TABLE `Setting` (
  `id` int(11) NOT NULL,
  `keterangan` varchar(50) DEFAULT NULL,
  `isian` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `Setting`
--

INSERT INTO `Setting` (`id`, `keterangan`, `isian`) VALUES
(1, 'link_excel_kategori', 'https://docs.google.com/spreadsheets/d/1TM13kpRMVa2mCsmcnZ5hz4stbTydLsma/edit?usp=sharing&ouid=116416830141700550935&rtpof=true&sd=true');

-- --------------------------------------------------------

--
-- Table structure for table `Supervisor`
--

CREATE TABLE `Supervisor` (
  `supervisor_id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `unit_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `Supervisor`
--

INSERT INTO `Supervisor` (`supervisor_id`, `nama`, `unit_id`) VALUES
(2, 'pele', 1),
(3, 'yana', 2);

-- --------------------------------------------------------

--
-- Table structure for table `Unit`
--

CREATE TABLE `Unit` (
  `unit_id` int(11) NOT NULL,
  `nama_unit` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `Unit`
--

INSERT INTO `Unit` (`unit_id`, `nama_unit`) VALUES
(1, 'HUMAS'),
(2, 'PKRS');

-- --------------------------------------------------------

--
-- Stand-in structure for view `View_Laporan_Kinerja`
-- (See below for the actual view)
--
CREATE TABLE `View_Laporan_Kinerja` (
`Karyawan` varchar(100)
,`tanggal` date
,`aktivitas` varchar(255)
,`waktu_mulai` time
,`waktu_selesai` time
,`hambatan` varchar(255)
,`solusi` varchar(255)
,`Status_Verifikasi` enum('belum','tunda','selesai')
,`catatan_supervisor` text
);

-- --------------------------------------------------------

--
-- Structure for view `View_Laporan_Kinerja`
--
DROP TABLE IF EXISTS `View_Laporan_Kinerja`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `View_Laporan_Kinerja`  AS SELECT `k`.`nama` AS `Karyawan`, `l`.`tanggal` AS `tanggal`, `l`.`aktivitas` AS `aktivitas`, `l`.`waktu_mulai` AS `waktu_mulai`, `l`.`waktu_selesai` AS `waktu_selesai`, `l`.`hambatan` AS `hambatan`, `l`.`solusi` AS `solusi`, `l`.`status` AS `Status_Verifikasi`, `l`.`catatan_supervisor` AS `catatan_supervisor` FROM (`Logbook` `l` join `Karyawan` `k` on(`l`.`karyawan_id` = `k`.`karyawan_id`)) WHERE `l`.`status` = 'Diverifikasi' ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Admin`
--
ALTER TABLE `Admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `Karyawan`
--
ALTER TABLE `Karyawan`
  ADD PRIMARY KEY (`karyawan_id`),
  ADD KEY `fk_karyawan_supervisor` (`supervisor_id`);

--
-- Indexes for table `LaporanKinerja`
--
ALTER TABLE `LaporanKinerja`
  ADD PRIMARY KEY (`laporan_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `Logbook`
--
ALTER TABLE `Logbook`
  ADD PRIMARY KEY (`logbook_id`),
  ADD KEY `karyawan_id` (`karyawan_id`),
  ADD KEY `supervisor_id` (`supervisor_id`),
  ADD KEY `fk_logbook_unit` (`unit_id`);

--
-- Indexes for table `Pengguna`
--
ALTER TABLE `Pengguna`
  ADD PRIMARY KEY (`pengguna_id`),
  ADD KEY `karyawan_id` (`karyawan_id`),
  ADD KEY `supervisor_id` (`supervisor_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `Setting`
--
ALTER TABLE `Setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Supervisor`
--
ALTER TABLE `Supervisor`
  ADD PRIMARY KEY (`supervisor_id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `Unit`
--
ALTER TABLE `Unit`
  ADD PRIMARY KEY (`unit_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Admin`
--
ALTER TABLE `Admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Karyawan`
--
ALTER TABLE `Karyawan`
  MODIFY `karyawan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `LaporanKinerja`
--
ALTER TABLE `LaporanKinerja`
  MODIFY `laporan_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Logbook`
--
ALTER TABLE `Logbook`
  MODIFY `logbook_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `Pengguna`
--
ALTER TABLE `Pengguna`
  MODIFY `pengguna_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `Setting`
--
ALTER TABLE `Setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Supervisor`
--
ALTER TABLE `Supervisor`
  MODIFY `supervisor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Unit`
--
ALTER TABLE `Unit`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Karyawan`
--
ALTER TABLE `Karyawan`
  ADD CONSTRAINT `fk_karyawan_supervisor` FOREIGN KEY (`supervisor_id`) REFERENCES `Supervisor` (`supervisor_id`);

--
-- Constraints for table `LaporanKinerja`
--
ALTER TABLE `LaporanKinerja`
  ADD CONSTRAINT `LaporanKinerja_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `Admin` (`admin_id`);

--
-- Constraints for table `Logbook`
--
ALTER TABLE `Logbook`
  ADD CONSTRAINT `Logbook_ibfk_1` FOREIGN KEY (`karyawan_id`) REFERENCES `Karyawan` (`karyawan_id`),
  ADD CONSTRAINT `Logbook_ibfk_2` FOREIGN KEY (`supervisor_id`) REFERENCES `Supervisor` (`supervisor_id`),
  ADD CONSTRAINT `fk_logbook_unit` FOREIGN KEY (`unit_id`) REFERENCES `Unit` (`unit_id`);

--
-- Constraints for table `Pengguna`
--
ALTER TABLE `Pengguna`
  ADD CONSTRAINT `Pengguna_ibfk_1` FOREIGN KEY (`karyawan_id`) REFERENCES `Karyawan` (`karyawan_id`),
  ADD CONSTRAINT `Pengguna_ibfk_2` FOREIGN KEY (`supervisor_id`) REFERENCES `Supervisor` (`supervisor_id`),
  ADD CONSTRAINT `Pengguna_ibfk_3` FOREIGN KEY (`admin_id`) REFERENCES `Admin` (`admin_id`);

--
-- Constraints for table `Supervisor`
--
ALTER TABLE `Supervisor`
  ADD CONSTRAINT `Supervisor_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `Unit` (`unit_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
