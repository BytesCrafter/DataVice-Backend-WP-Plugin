-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 28, 2020 at 08:57 AM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wordpress`
--

-- --------------------------------------------------------

--
-- Table structure for table `dv_provinces`
--

DROP TABLE IF EXISTS `dv_provinces`;

CREATE TABLE `dv_provinces` (
  `id` int(11) NOT NULL,
  `prov_name` text DEFAULT NULL,
  `prov_code` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dv_provinces`
--

INSERT INTO `dv_provinces` (`id`, `prov_name`, `prov_code`, `status`) VALUES
(1, 'Ilocos Norte', '0128', 0),
(2, 'Ilocos Sur', '0129', 0),
(3, 'La Union', '0133', 0),
(4, 'Pangasinan', '0155', 0),
(5, 'Batanes', '0209', 0),
(6, 'Cagayan', '0215', 0),
(7, 'Isabela', '0231', 0),
(8, 'Nueva Vizcaya', '0250', 0),
(9, 'Quirino', '0257', 0),
(10, 'Bataan', '0308', 0),
(11, 'Bulacan', '0314', 0),
(12, 'Nueva Ecija', '0349', 0),
(13, 'Pampanga', '0354', 0),
(14, 'Tarlac', '0369', 0),
(15, 'Zambales', '0371', 0),
(16, 'Aurora', '0377', 0),
(17, 'Batangas', '0410', 0),
(18, 'Cavite', '0421', 0),
(19, 'Laguna', '0434', 0),
(20, 'Quezon', '0456', 0),
(21, 'Rizal', '0458', 0),
(22, 'Marinduque', '1740', 0),
(23, 'Occidental Mindoro', '1751', 0),
(24, 'Oriental Mindoro', '1752', 0),
(25, 'Palawan', '1753', 0),
(26, 'Romblon', '1759', 0),
(27, 'Albay', '0505', 0),
(28, 'Camarines Norte', '0516', 0),
(29, 'Camarines Sur', '0517', 0),
(30, 'Catanduanes', '0520', 0),
(31, 'Masbate', '0541', 0),
(32, 'Sorsogon', '0562', 0),
(33, 'Aklan', '0604', 0),
(34, 'Antique', '0606', 0),
(35, 'Capiz', '0619', 0),
(36, 'Iloilo', '0630', 0),
(37, 'Negros Occidental', '0645', 0),
(38, 'Guimaras', '0679', 0),
(39, 'Bohol', '0712', 0),
(40, 'Cebu', '0722', 0),
(41, 'Negros Oriental', '0746', 0),
(42, 'Siquijor', '0761', 0),
(43, 'Eastern Samar', '0826', 0),
(44, 'Leyte', '0837', 0),
(45, 'Northern Samar', '0848', 0),
(46, 'Samar (Western Samar)', '0860', 0),
(47, 'Southern Leyte', '0864', 0),
(48, 'Biliran', '0878', 0),
(49, 'Zamboanga Del Norte', '0972', 0),
(50, 'Zamboanga Del Sur', '0973', 0),
(51, 'Zamboanga Sibugay', '0983', 0),
(53, 'Bukidnon', '1013', 0),
(54, 'Camiguin', '1018', 0),
(55, 'Lanao Del Norte', '1035', 0),
(56, 'Misamis Occidental', '1042', 0),
(57, 'Misamis Oriental', '1043', 0),
(58, 'Davao Del Norte', '1123', 0),
(59, 'Davao Del Sur', '1124', 0),
(60, 'Davao Oriental', '1125', 0),
(61, 'Compostela Valley', '1182', 0),
(62, 'Davao Occidental', '1186', 0),
(63, 'Cotabato (North Cotabato)', '1247', 0),
(64, 'South Cotabato', '1263', 0),
(65, 'Sultan Kudarat', '1265', 0),
(66, 'Sarangani', '1280', 0),
(67, 'Cotabato City', '1298', 0),
(68, 'NCR - 1st District', '1339', 0),
(70, 'NCR - 2nd District', '1374', 0),
(71, 'NCR - 3rd District', '1375', 0),
(72, 'NCR - 4th District', '1376', 0),
(73, 'Abra', '1401', 0),
(74, 'Benguet', '1411', 0),
(75, 'Ifugao', '1427', 0),
(76, 'Kalinga', '1432', 0),
(77, 'Mountain Province', '1444', 0),
(78, 'Apayao', '1481', 0),
(79, 'Basilan', '1507', 0),
(80, 'Lanao Del Sur', '1536', 0),
(81, 'Maguindanao', '1538', 0),
(82, 'Sulu', '1566', 0),
(83, 'Tawi-tawi', '1570', 0),
(84, 'Agusan Del Norte', '1602', 0),
(85, 'Agusan Del Sur', '1603', 0),
(86, 'Surigao Del Norte', '1667', 0),
(87, 'Surigao Del Sur', '1668', 0),
(88, 'Dinagat Islands', '1685', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dv_provinces`
--
ALTER TABLE `dv_provinces`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dv_provinces`
--
ALTER TABLE `dv_provinces`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
