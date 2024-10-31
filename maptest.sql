-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2024 at 05:33 AM
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
-- Database: `maptest`
--

-- --------------------------------------------------------

--
-- Table structure for table `image_maps`
--

CREATE TABLE `image_maps` (
  `id` int(11) NOT NULL,
  `coordinates` varchar(1000) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `image_maps`
--

INSERT INTO `image_maps` (`id`, `coordinates`, `title`, `description`, `link`, `created_at`) VALUES
(1, '{\"x\":454,\"y\":400,\"width\":null,\"height\":null}', 'ss', 'ss', 'ss', '2024-10-30 07:43:00'),
(2, '{\"x\":2852,\"y\":5250,\"width\":null,\"height\":null}', 'beach', 'hahahah', 'asas', '2024-10-30 07:46:44'),
(3, '{\"x\":25,\"y\":56,\"width\":26,\"height\":30}', 'ss', 'ss', 'ss', '2024-10-30 07:51:30'),
(4, '{\"x\":635,\"y\":728,\"width\":null,\"height\":null}', 'AIRPORT', 'SAN ANDREAS AIRPORT', '', '2024-10-30 07:54:25'),
(5, '{\"x\":454.5,\"y\":181,\"width\":null,\"height\":null}', 'eee', 'eee', 'eee', '2024-10-30 08:13:02'),
(6, '{\"x\":197.5,\"y\":447,\"width\":null,\"height\":null}', 'test', 'eyy', 'sss', '2024-10-30 08:17:05'),
(7, '{\"x\":157.353,\"y\":90.5882,\"width\":255.294,\"height\":204.118}', 'asas', 'sasa', 'sasa', '2024-10-30 08:17:37'),
(8, '{\"x\":330.5,\"y\":279,\"width\":110.5,\"height\":158}', 'Hello Worl', 'lorem ipsum', '', '2024-10-30 08:20:16'),
(9, '{\"x\":563.5,\"y\":364,\"width\":8,\"height\":22}', 'kupal', 'pala lulu si Kian\n', '', '2024-10-30 17:31:54'),
(10, '{\"x\":657.5,\"y\":585,\"width\":null,\"height\":null}', 'Skate park', 'Skate park San Andreas\n', '', '2024-10-30 17:33:16'),
(11, '{\"x\":691.5,\"y\":608,\"width\":41,\"height\":18}', 'Grove Street', 'Best Street', 'https://www.google.com/maps/place/E+Cocoa+St,+Compton,+CA,+USA/@33.8893273,-118.2241606,17z/data=!3m1!4b1!4m6!3m5!1s0x80c2cb69ef8bfff1:0x836a3463c1014da8!8m2!3d33.8893273!4d-118.2215857!16s%2Fg%2F1tp08cwt?entry=ttu&g_ep=EgoyMDI0MTAyNy4wIKXMDSoASAFQAw%3D%3', '2024-10-30 17:46:36'),
(12, '{\"x\":558.5,\"y\":584,\"width\":null,\"height\":null}', 'TEST', 'HAHAHAHAHAHA', '', '2024-10-31 02:56:17'),
(13, '{\"x\":17,\"y\":23,\"width\":168.5,\"height\":415}', '', '', '', '2024-10-31 03:22:28'),
(14, '{\"x\":612.5,\"y\":491,\"width\":28,\"height\":19}', 'pje0 ouwddwdw', 'dqw 0 ', '', '2024-10-31 03:23:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `image_maps`
--
ALTER TABLE `image_maps`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `image_maps`
--
ALTER TABLE `image_maps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
