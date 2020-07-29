-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 29, 2020 at 06:43 AM
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
-- Table structure for table `dv_geo_provinces`
--

DROP TABLE IF EXISTS `dv_geo_provinces`;

CREATE TABLE `dv_geo_provinces` (
  `id` int(11) NOT NULL,
  `prov_name` text DEFAULT NULL,
  `prov_code` varchar(255) DEFAULT NULL,
  `country_code` varchar(2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `dv_geo_provinces`
--

INSERT INTO `dv_geo_provinces` (`id`, `prov_name`, `prov_code`, `country_code`) VALUES
(1, 'Ilocos Norte', '0128', 'PH'),
(2, 'Ilocos Sur', '0129', 'PH'),
(3, 'La Union', '0133', 'PH'),
(4, 'Pangasinan', '0155', 'PH'),
(5, 'Batanes', '0209', 'PH'),
(6, 'Cagayan', '0215', 'PH'),
(7, 'Isabela', '0231', 'PH'),
(8, 'Nueva Vizcaya', '0250', 'PH'),
(9, 'Quirino', '0257', 'PH'),
(10, 'Bataan', '0308', 'PH'),
(11, 'Bulacan', '0314', 'PH'),
(12, 'Nueva Ecija', '0349', 'PH'),
(13, 'Pampanga', '0354', 'PH'),
(14, 'Tarlac', '0369', 'PH'),
(15, 'Zambales', '0371', 'PH'),
(16, 'Aurora', '0377', 'PH'),
(17, 'Batangas', '0410', 'PH'),
(18, 'Cavite', '0421', 'PH'),
(19, 'Laguna', '0434', 'PH'),
(20, 'Quezon', '0456', 'PH'),
(21, 'Rizal', '0458', 'PH'),
(22, 'Marinduque', '1740', 'PH'),
(23, 'Occidental Mindoro', '1751', 'PH'),
(24, 'Oriental Mindoro', '1752', 'PH'),
(25, 'Palawan', '1753', 'PH'),
(26, 'Romblon', '1759', 'PH'),
(27, 'Albay', '0505', 'PH'),
(28, 'Camarines Norte', '0516', 'PH'),
(29, 'Camarines Sur', '0517', 'PH'),
(30, 'Catanduanes', '0520', 'PH'),
(31, 'Masbate', '0541', 'PH'),
(32, 'Sorsogon', '0562', 'PH'),
(33, 'Aklan', '0604', 'PH'),
(34, 'Antique', '0606', 'PH'),
(35, 'Capiz', '0619', 'PH'),
(36, 'Iloilo', '0630', 'PH'),
(37, 'Negros Occidental', '0645', 'PH'),
(38, 'Guimaras', '0679', 'PH'),
(39, 'Bohol', '0712', 'PH'),
(40, 'Cebu', '0722', 'PH'),
(41, 'Negros Oriental', '0746', 'PH'),
(42, 'Siquijor', '0761', 'PH'),
(43, 'Eastern Samar', '0826', 'PH'),
(44, 'Leyte', '0837', 'PH'),
(45, 'Northern Samar', '0848', 'PH'),
(46, 'Samar (Western Samar)', '0860', 'PH'),
(47, 'Southern Leyte', '0864', 'PH'),
(48, 'Biliran', '0878', 'PH'),
(49, 'Zamboanga Del Norte', '0972', 'PH'),
(50, 'Zamboanga Del Sur', '0973', 'PH'),
(51, 'Zamboanga Sibugay', '0983', 'PH'),
(53, 'Bukidnon', '1013', 'PH'),
(54, 'Camiguin', '1018', 'PH'),
(55, 'Lanao Del Norte', '1035', 'PH'),
(56, 'Misamis Occidental', '1042', 'PH'),
(57, 'Misamis Oriental', '1043', 'PH'),
(58, 'Davao Del Norte', '1123', 'PH'),
(59, 'Davao Del Sur', '1124', 'PH'),
(60, 'Davao Oriental', '1125', 'PH'),
(61, 'Compostela Valley', '1182', 'PH'),
(62, 'Davao Occidental', '1186', 'PH'),
(63, 'Cotabato (North Cotabato)', '1247', 'PH'),
(64, 'South Cotabato', '1263', 'PH'),
(65, 'Sultan Kudarat', '1265', 'PH'),
(66, 'Sarangani', '1280', 'PH'),
(67, 'Cotabato City', '1298', 'PH'),
(68, 'NCR - 1st District', '1339', 'PH'),
(70, 'NCR - 2nd District', '1374', 'PH'),
(71, 'NCR - 3rd District', '1375', 'PH'),
(72, 'NCR - 4th District', '1376', 'PH'),
(73, 'Abra', '1401', 'PH'),
(74, 'Benguet', '1411', 'PH'),
(75, 'Ifugao', '1427', 'PH'),
(76, 'Kalinga', '1432', 'PH'),
(77, 'Mountain Province', '1444', 'PH'),
(78, 'Apayao', '1481', 'PH'),
(79, 'Basilan', '1507', 'PH'),
(80, 'Lanao Del Sur', '1536', 'PH'),
(81, 'Maguindanao', '1538', 'PH'),
(82, 'Sulu', '1566', 'PH'),
(83, 'Tawi-tawi', '1570', 'PH'),
(84, 'Agusan Del Norte', '1602', 'PH'),
(85, 'Agusan Del Sur', '1603', 'PH'),
(86, 'Surigao Del Norte', '1667', 'PH'),
(87, 'Surigao Del Sur', '1668', 'PH'),
(88, 'Dinagat Islands', '1685', 'PH');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dv_geo_provinces`
--
ALTER TABLE `dv_geo_provinces`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dv_geo_provinces`
--
ALTER TABLE `dv_geo_provinces`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
