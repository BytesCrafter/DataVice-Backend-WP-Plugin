-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 27, 2020 at 06:11 AM
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
  `prov_code` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dv_provinces`
--

INSERT INTO `dv_provinces` (`id`, `prov_name`, `prov_code`) VALUES
(1, 'Ilocos Norte', '0128'),
(2, 'Ilocos Sur', '0129'),
(3, 'La Union', '0133'),
(4, 'Pangasinan', '0155'),
(5, 'Batanes', '0209'),
(6, 'Cagayan', '0215'),
(7, 'Isabela', '0231'),
(8, 'Nueva Vizcaya', '0250'),
(9, 'Quirino', '0257'),
(10, 'Bataan', '0308'),
(11, 'Bulacan', '0314'),
(12, 'Nueva Ecija', '0349'),
(13, 'Pampanga', '0354'),
(14, 'Tarlac', '0369'),
(15, 'Zambales', '0371'),
(16, 'Aurora', '0377'),
(17, 'Batangas', '0410'),
(18, 'Cavite', '0421'),
(19, 'Laguna', '0434'),
(20, 'Quezon', '0456'),
(21, 'Rizal', '0458'),
(22, 'Marinduque', '1740'),
(23, 'Occidental Mindoro', '1751'),
(24, 'Oriental Mindoro', '1752'),
(25, 'Palawan', '1753'),
(26, 'Romblon', '1759'),
(27, 'Albay', '0505'),
(28, 'Camarines Norte', '0516'),
(29, 'Camarines Sur', '0517'),
(30, 'Catanduanes', '0520'),
(31, 'Masbate', '0541'),
(32, 'Sorsogon', '0562'),
(33, 'Aklan', '0604'),
(34, 'Antique', '0606'),
(35, 'Capiz', '0619'),
(36, 'Iloilo', '0630'),
(37, 'Negros Occidental', '0645'),
(38, 'Guimaras', '0679'),
(39, 'Bohol', '0712'),
(40, 'Cebu', '0722'),
(41, 'Negros Oriental', '0746'),
(42, 'Siquijor', '0761'),
(43, 'Eastern Samar', '0826'),
(44, 'Leyte', '0837'),
(45, 'Northern Samar', '0848'),
(46, 'Samar (Western Samar)', '0860'),
(47, 'Southern Leyte', '0864'),
(48, 'Biliran', '0878'),
(49, 'Zamboanga Del Norte', '0972'),
(50, 'Zamboanga Del Sur', '0973'),
(51, 'Zamboanga Sibugay', '0983'),
(53, 'Bukidnon', '1013'),
(54, 'Camiguin', '1018'),
(55, 'Lanao Del Norte', '1035'),
(56, 'Misamis Occidental', '1042'),
(57, 'Misamis Oriental', '1043'),
(58, 'Davao Del Norte', '1123'),
(59, 'Davao Del Sur', '1124'),
(60, 'Davao Oriental', '1125'),
(61, 'Compostela Valley', '1182'),
(62, 'Davao Occidental', '1186'),
(63, 'Cotabato (North Cotabato)', '1247'),
(64, 'South Cotabato', '1263'),
(65, 'Sultan Kudarat', '1265'),
(66, 'Sarangani', '1280'),
(67, 'Cotabato City', '1298'),
(68, 'NCR - 1st District', '1339'),
(70, 'NCR - 2nd District', '1374'),
(71, 'NCR - 3rd District', '1375'),
(72, 'NCR - 4th District', '1376'),
(73, 'Abra', '1401'),
(74, 'Benguet', '1411'),
(75, 'Ifugao', '1427'),
(76, 'Kalinga', '1432'),
(77, 'Mountain Province', '1444'),
(78, 'Apayao', '1481'),
(79, 'Basilan', '1507'),
(80, 'Lanao Del Sur', '1536'),
(81, 'Maguindanao', '1538'),
(82, 'Sulu', '1566'),
(83, 'Tawi-tawi', '1570'),
(84, 'Agusan Del Norte', '1602'),
(85, 'Agusan Del Sur', '1603'),
(86, 'Surigao Del Norte', '1667'),
(87, 'Surigao Del Sur', '1668'),
(88, 'Dinagat Islands', '1685');

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
