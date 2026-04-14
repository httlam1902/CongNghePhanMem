-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2026 at 06:53 PM
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
-- Database: `ql_thuvien`
--

-- --------------------------------------------------------

--
-- Table structure for table `dat_lai_mk`
--

CREATE TABLE `dat_lai_mk` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(64) NOT NULL,
  `het_han` datetime NOT NULL,
  `da_dung` tinyint(1) DEFAULT 0,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `muon_sach`
--

CREATE TABLE `muon_sach` (
  `id` int(11) NOT NULL,
  `ma_nguoi_dung` int(11) NOT NULL,
  `ma_sach` int(11) NOT NULL,
  `ngay_muon` date DEFAULT NULL,
  `han_tra` date DEFAULT NULL,
  `ngay_tra` date DEFAULT NULL,
  `trang_thai` enum('cho_duyet','dang_muon','da_tra') DEFAULT 'cho_duyet',
  `phi_phat` decimal(10,2) DEFAULT 0.00,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nguoi_dung`
--

CREATE TABLE `nguoi_dung` (
  `id` int(11) NOT NULL,
  `ten_dang_nhap` varchar(50) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `ho_ten` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `vai_tro` enum('admin','librarian','reader') NOT NULL DEFAULT 'reader',
  `trang_thai` tinyint(1) NOT NULL DEFAULT 1,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`id`, `ten_dang_nhap`, `mat_khau`, `ho_ten`, `email`, `vai_tro`, `trang_thai`, `ngay_tao`) VALUES
(1, 'admin', '$2y$10$qDd1FRGhYMz27o6gnp2cjuVePAb4w6I6sx9boJtC/RlSrmtcZeDH6', 'Quản Trị Viên', 'admin@thuvien.com', 'admin', 1, '2026-03-25 18:20:09'),
(2, 'thuthu1', '$2y$10$1hmz2IinHFVn00CT9jaGm.iEnJB5X6VT6KbpFL6fjvtSBo24aSI9q', 'Nguyễn Đăng Khôi', 'thuthu@thuvien.com', 'librarian', 1, '2026-03-25 18:20:09');

-- --------------------------------------------------------

--
-- Table structure for table `sach`
--

CREATE TABLE `sach` (
  `id` int(11) NOT NULL,
  `ten_sach` varchar(200) NOT NULL,
  `tac_gia` varchar(150) NOT NULL,
  `the_loai` varchar(80) NOT NULL,
  `so_luong` int(11) NOT NULL DEFAULT 1,
  `con_lai` int(11) NOT NULL DEFAULT 1,
  `nam_xuat_ban` year(4) DEFAULT NULL,
  `mo_ta` text DEFAULT NULL,
  `ngay_them` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sach`
--

INSERT INTO `sach` (`id`, `ten_sach`, `tac_gia`, `the_loai`, `so_luong`, `con_lai`, `nam_xuat_ban`, `mo_ta`, `ngay_them`) VALUES
(1, 'Lập trình PHP căn bản', 'Nguyễn Văn An', 'CNTT', 3, 6, '2022', NULL, '2026-03-25 18:20:09'),
(2, 'Cơ sở dữ liệu MySQL', 'Trần Thị Bình', 'CNTT', 2, 2, '2021', NULL, '2026-03-25 18:20:09'),
(3, 'HTML và CSS thực hành', 'Lê Văn Cường', 'CNTT', 4, 4, '2023', NULL, '2026-03-25 18:20:09'),
(4, 'JavaScript cơ bản', 'Phạm Thị Dung', 'CNTT', 2, 2, '2023', NULL, '2026-03-25 18:20:09'),
(5, 'Truyện Kiều', 'Nguyễn Du', 'Văn học', 5, 5, '2019', NULL, '2026-03-25 18:20:09'),
(6, 'Số đỏ', 'Vũ Trọng Phụng', 'Văn học', 3, 3, '2020', NULL, '2026-03-25 18:20:09'),
(7, 'Vật lý đại cương', 'Trần Văn Em', 'Khoa học', 2, 1, '2018', NULL, '2026-03-25 18:20:09'),
(11, 'Chí phèo', 'Nam Cao', 'Truyện ngắn', 3, 3, '1957', '', '2026-04-05 16:51:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dat_lai_mk`
--
ALTER TABLE `dat_lai_mk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_token` (`token`);

--
-- Indexes for table `muon_sach`
--
ALTER TABLE `muon_sach`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ma_nguoi_dung` (`ma_nguoi_dung`),
  ADD KEY `ma_sach` (`ma_sach`);

--
-- Indexes for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ten_dang_nhap` (`ten_dang_nhap`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `sach`
--
ALTER TABLE `sach`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dat_lai_mk`
--
ALTER TABLE `dat_lai_mk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `muon_sach`
--
ALTER TABLE `muon_sach`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sach`
--
ALTER TABLE `sach`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `muon_sach`
--
ALTER TABLE `muon_sach`
  ADD CONSTRAINT `muon_sach_ibfk_1` FOREIGN KEY (`ma_nguoi_dung`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `muon_sach_ibfk_2` FOREIGN KEY (`ma_sach`) REFERENCES `sach` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

