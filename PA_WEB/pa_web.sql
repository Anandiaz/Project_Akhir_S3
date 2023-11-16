-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2023 at 03:48 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pa_web`
--

-- --------------------------------------------------------

--
-- Table structure for table `akun_user`
--

CREATE TABLE `akun_user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `akun_user`
--

INSERT INTO `akun_user` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'gentazz', 'gentainformatika22@gmail.com', '$2y$10$Xx6PumRx55xmVDkFIsg8QOBglQrR6gEkZoGAGBm08QKGZgVOb2fsq', ''),
(2, 'bani', 'banisuta16@gmail.com', '$2y$10$8dPoEAuI4VyuJq22Pczqqu8jVMXOT60O.4Bjcm.PJlsFNDYJNubTu', ''),
(3, 'anandiaz', 'anandiaz@gmail.com', '$2y$10$4V33B8NfLrQbpXYxzWakgOh.5m4Lb6fKwOJY7HCKfqwcOkb/FyEA.', ''),
(4, 'admin', 'admin@gmail.com', '$2y$10$EuyNtlOCR/7JvEXedoy8t.q4LU8VCBjBWqchOTIJYfsBa/shbbMiu', 'admin'),
(5, 'test', 'test@gmail.com', '$2y$10$kx6xQDRZepvnsGUKJYZOVOIsit2l14WfDVy.5Q5ODzKiQSckAIMG6', '');

-- --------------------------------------------------------

--
-- Table structure for table `film`
--

CREATE TABLE `film` (
  `id_film` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `id_rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `film`
--

INSERT INTO `film` (`id_film`, `judul`, `gambar`, `id_rating`) VALUES
(24, 'Avengers', '652ec76869ab1.jpg', 1),
(25, 'Kimi No Nawa', '652ec7ab476ea.jpg', 2);

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `id_rating` int(11) NOT NULL,
  `nama_rating` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`id_rating`, `nama_rating`) VALUES
(1, 'SU'),
(2, 'R'),
(3, 'D');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id_pesan` int(11) NOT NULL,
  `id_tayang` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `kursi` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id_pesan`, `id_tayang`, `id_user`, `kursi`) VALUES
(3, 1, 1, 'E9,E10,E11');

-- --------------------------------------------------------

--
-- Table structure for table `tayang`
--

CREATE TABLE `tayang` (
  `id_tayang` int(11) NOT NULL,
  `id_film` int(11) NOT NULL,
  `jam_tayang` datetime NOT NULL,
  `kursi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tayang`
--

INSERT INTO `tayang` (`id_tayang`, `id_film`, `jam_tayang`, `kursi`) VALUES
(1, 24, '2023-11-16 19:23:00', 'A7,B5,A6,B6,D2,D3,D4,E9,E10,E11'),
(2, 25, '2023-11-16 19:27:00', ''),
(6, 25, '2023-11-16 19:27:00', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akun_user`
--
ALTER TABLE `akun_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `film`
--
ALTER TABLE `film`
  ADD PRIMARY KEY (`id_film`),
  ADD KEY `id_rating` (`id_rating`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`id_rating`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id_pesan`),
  ADD KEY `id_tayang` (`id_tayang`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `tayang`
--
ALTER TABLE `tayang`
  ADD PRIMARY KEY (`id_tayang`),
  ADD KEY `id_film` (`id_film`),
  ADD KEY `id_tayang` (`id_tayang`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `akun_user`
--
ALTER TABLE `akun_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `film`
--
ALTER TABLE `film`
  MODIFY `id_film` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `id_rating` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id_pesan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tayang`
--
ALTER TABLE `tayang`
  MODIFY `id_tayang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `film`
--
ALTER TABLE `film`
  ADD CONSTRAINT `film_ibfk_1` FOREIGN KEY (`id_rating`) REFERENCES `rating` (`id_rating`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`id_tayang`) REFERENCES `tayang` (`id_tayang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `akun_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tayang`
--
ALTER TABLE `tayang`
  ADD CONSTRAINT `tayang_ibfk_1` FOREIGN KEY (`id_film`) REFERENCES `film` (`id_film`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
