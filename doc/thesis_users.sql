-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 08, 2016 at 02:00 AM
-- Server version: 5.5.48-37.8
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `leewe27_quantum_f3`
--

-- --------------------------------------------------------

--
-- Table structure for table `thesis_users`
--

CREATE TABLE IF NOT EXISTS `thesis_users` (
  `userLongId` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `forgotPass` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `fname` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mname` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lname` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` varchar(20) COLLATE utf8_unicode_ci DEFAULT 'student',
  `approved` varchar(2) COLLATE utf8_unicode_ci DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `thesis_users`
--

INSERT INTO `thesis_users` (`userLongId`, `id`, `forgotPass`, `password`, `email`, `fname`, `mname`, `lname`, `position`, `approved`) VALUES
('', 'cchi3975', NULL, '$2y$10$FeYJ/CjrSmM9yRqxFo7fUur5gx8HbS1I2MUJx4.KxMeeipDElR0G.', 'cal_wc85@yahoo.com', 'calvin', '', 'chiew', 'admin', '1'),
('a94fe2e68a8a', '3333', NULL, '$2y$10$dDYXbBsZNYbDdCn0nwNkCukp0cdfxfbfEPheU6IAWh/enAWFB6UCK', 'email3@email.com', 'course', NULL, 'coordinator 3', 'coordinator', '0'),
('d17a600d7351', 'q32', NULL, '', 'assessor1@email.com', 'assessor', NULL, '1', 'assessor', '0'),
('1cfb41ca8024', '455', NULL, '$2y$10$gK2V5f4VeTq9qe3y/zFeR.WQEr63naYW3ZJoXRun2tbIY6tCRaGJW', 'assessor2@email.com', 'assessor', NULL, '2', 'assessor', '0'),
('fb8f96a03b99', 'sId123', NULL, '$2y$10$zMVe5XZ/SrKIaV9H.Vp9fe7yBD9mrBnRwDTdiq2umxEQPmJN5OBqG', 'student1@email.com', 'calvin', 'middle1', 'last1', 'student', '0'),
('6f6e8d594c90', 'qwe234', NULL, '$2y$10$S4GauB1io3jGhnKyFf5ncO9uvB3Vwa868QFSireGvbMdEUknkZw7m', 'student2@email.com', 'first2', 'middle2', 'last2', 'student', '0'),
('39fe7d2f2340', 'qwe345', NULL, '$2y$10$NdeN.gIpV36/.V/67oXjGe9i.zvH91fRePkecf4TPTLwVGwvLwOD2', 'student3@email.com', 'first3', 'middle3', 'last3', 'student', '0'),
('069d24348d4d', 'qwe456', NULL, '$2y$10$wOjvuWJWkc5IkObx6rpzZeTMtl4LjpLe.xBrD9R/vlbU4r6Cj11ES', 'student4@email.com', 'first4', 'middle4', 'last4', 'student', '0'),
('e7674f45dc98', 'lshen', NULL, '$2y$10$Kc7nrnI8ROUGXj2pu8OsuupAHvBxTQHWxSJAtwh9walmYL1qiX0zC', 'luming.shen@sydney.edu.au', 'Luming', '', 'Shen', 'admin', '1'),
('f973779d1d39', 'lshen_coordinator', NULL, '$2y$10$m/ddJWCizImDO68/QsW.1.qskeB.vQ2CZtoRBsFsXYqmZqVwq05JO', 'luming.shen@sydney.edu.au', 'Luming', NULL, 'Shen', 'coordinator', '0'),
('656c028e7b67', 'a333', NULL, '$2y$10$1dav034jrGYoTgy/akfBR.LSu.MYwriyQrz1EKQIxlwcnJc6dK2s2', 'assessor3@email.com', 'assessor', NULL, '3', 'assessor', '0'),
('63a72a59b1a2', '1111', NULL, '$2y$10$zDkpiN5S/ovDYS/2Ggj3DexIoUgNtIAy3Bxz7hTrY/4Y1Its6UH1y', 'email1@email.com', 'course', NULL, 'coordinator 1', 'coordinator', '0'),
('2cda0c845355', '2222', NULL, '$2y$10$p7qHVkBiKWDh80kRtYCboOqsY6iSyJbbERBnedszvNI4tBcTG5daO', 'email@email.com', 'course', NULL, 'coordinator 2', 'coordinator', '0'),
('c8d01c5a8852', '2', NULL, '$2y$10$KuLBRQrbM/c5HExheHiz9.EWZaSaT589lqSnIdcxICTqKAZHdoMuy', 'cal_wc85@yahoo.com', '2ee1', '', '2qwe', 'student', '0'),
('1a335b8d809c', '1', NULL, '$2y$10$EroU00T54NBAS/TOtvK02uNOPxuqdjHQ9vbWtvACbIYnbwCyc3E52', '1', '1', '1', '1', 'student', '0'),
('0199fd042bb8', '1582388478717075', NULL, '$2y$10$YoHCS8qK/pGTXyFiyItBFuxMs5nyP2VM.4k5hRM2w6YUgWzkZreTa', 'cal_wc85@yahoo.com', 'test', '', 'qwe', 'admin', '0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `thesis_users`
--
ALTER TABLE `thesis_users`
  ADD PRIMARY KEY (`userLongId`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
