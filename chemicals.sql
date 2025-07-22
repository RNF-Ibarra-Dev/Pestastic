-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2025 at 01:45 PM
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
-- Database: `pestastic_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `chemicals`
--

CREATE TABLE `chemicals` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `brand` varchar(128) NOT NULL,
  `chemLevel` double(10,2) NOT NULL COMMENT 'current level of opened container',
  `container_size` int(128) NOT NULL DEFAULT 0 COMMENT 'maximum/total volume of container',
  `unop_cont` int(128) NOT NULL DEFAULT 0 COMMENT 'unopened containers\r\n',
  `expiryDate` date DEFAULT '2025-01-01',
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `request` tinyint(1) NOT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `branch` int(11) NOT NULL DEFAULT 1,
  `added_by` varchar(64) NOT NULL DEFAULT 'No Record',
  `updated_by` varchar(64) NOT NULL DEFAULT 'No Update Record',
  `date_received` date DEFAULT NULL,
  `quantity_unit` enum('mg','g','kg','L','mL') NOT NULL,
  `chem_location` varchar(50) NOT NULL DEFAULT 'Room A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chemicals`
--

INSERT INTO `chemicals` (`id`, `name`, `brand`, `chemLevel`, `container_size`, `unop_cont`, `expiryDate`, `added_at`, `updated_at`, `request`, `notes`, `branch`, `added_by`, `updated_by`, `date_received`, `quantity_unit`, `chem_location`) VALUES
(1, 'Deltacide', 'ENVU', 6.03, 1000, 1, '2025-03-06', '2025-04-12 15:06:30', '2025-07-10 15:59:33', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL', 'Room A'),
(2, 'Abates', 'BASF', 1.00, 250, 2, '2032-03-11', '2025-04-12 15:06:30', '2025-07-11 07:55:10', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL', 'Room A'),
(3, 'chemical', 'ABXV', 1.00, 1000, 1, '2028-01-19', '2025-04-12 15:06:30', '2025-07-05 07:18:34', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'kg', 'Room A'),
(19, 'qwe', 'qwe', 0.00, 1000, 0, '2025-04-03', '2025-04-12 15:06:30', '2025-07-05 06:42:00', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL', 'Room A'),
(44, 'asd', 'asd', 0.00, 1000, 0, '2025-01-01', '2025-04-12 15:06:30', '2025-07-05 06:42:00', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL', 'Room A'),
(45, 'g', 'g', 0.00, 1000, 0, '2025-01-01', '2025-04-12 15:06:30', '2025-07-05 06:42:00', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL', 'Room A'),
(46, 'n', 'n', 0.00, 1000, 0, '2025-04-03', '2025-04-12 15:06:30', '2025-07-05 06:42:00', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL', 'Room A'),
(47, 'sad', 'asd', 492.00, 1000, 0, '2028-07-19', '2025-04-12 15:06:30', '2025-07-09 22:08:37', 0, '', 1, 'No Record', 'sAdmin | Employee no. 123', '2025-06-13', 'g', 'Room A'),
(49, 'asd', 'asd', 0.00, 1000, 0, '2027-08-13', '2025-04-12 15:06:30', '2025-07-05 06:42:00', 0, '', 1, 'No Record', 'sAdmin | Employee no. 123', '2025-06-13', 'mL', 'Room A'),
(50, 'Deltacides', 'CHEMM', 500.00, 1000, 5, '2025-01-01', '2025-04-12 15:06:30', '2025-07-05 06:42:00', 1, '', 1, 'No Record', 'bAdmin | Employee no. 012345678', '2025-06-13', 'mL', 'Room A'),
(51, 'asd', 'asd', 30.00, 1000, 0, '2025-01-29', '2025-04-12 15:06:30', '2025-07-05 06:42:00', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL', 'Room A'),
(52, 'asd', 'asd', 25.00, 1000, 0, '2025-01-29', '2025-04-12 15:06:30', '2025-07-05 06:42:00', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL', 'Room A'),
(54, 'chem B', 'brand B', 86.00, 1000, 0, '2029-06-21', '2025-04-12 15:06:30', '2025-07-11 05:47:15', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL', 'Room A'),
(1000, 'hj', 'hj', 5.00, 1000, 0, '2025-01-01', '2025-04-17 13:03:08', '2025-07-05 06:42:00', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL', 'Room A'),
(1001, 'gh', 'gh', 5.00, 1000, 0, '2025-01-01', '2025-04-17 13:15:47', '2025-07-12 10:25:47', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL', 'Room A'),
(1002, 'fgh', 'fgh', 6.00, 1000, 0, '2025-01-01', '2025-04-17 13:16:32', '2025-07-05 07:13:26', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'g', 'Room A'),
(1004, 'fgh', 'fgh', 5.00, 1000, 2, '2025-01-01', '2025-04-17 13:17:42', '2025-07-05 06:42:00', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL', 'Room A'),
(1005, 'hk', 'hjk', 8.00, 1000, 0, '2025-01-01', '2025-04-17 13:23:47', '2025-07-05 06:42:00', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL', 'Room A'),
(1006, 'fgd', 'd', 5.00, 1000, 4, '2025-01-01', '2025-04-17 13:24:22', '2025-07-05 06:42:00', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL', 'Room A'),
(1007, 'fgh', 'fgh', 6.00, 250, 0, '2025-01-01', '2025-04-17 13:25:27', '2025-07-05 06:42:00', 1, '', 1, 'No Record', 'bAdmin | Employee no. 012345678', '2025-06-13', 'mL', 'Room A'),
(1008, 'fgh', 'fgh', 6.00, 1000, 5, '2025-01-01', '2025-04-17 13:25:30', '2025-07-05 07:13:32', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'kg', 'Room A'),
(1009, 'fgh', 'fgh', 6.00, 1000, 0, '2025-01-01', '2025-04-17 13:25:31', '2025-07-05 06:42:00', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL', 'Room A'),
(1012, 'ghjp', 'ghj', 6.00, 500, 0, '2025-01-01', '2025-04-17 13:27:35', '2025-07-05 06:42:00', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL', 'Room A'),
(1013, 'asbg', 'gdfg', 501.00, 1000, 5, '2025-07-24', '2025-04-17 13:31:02', '2025-07-11 07:55:10', 1, '', 1, 'No Record', 'bAdmin | Employee no. 012345678', '2025-06-13', 'mL', 'Room A'),
(1014, 'jhghj', 'hjghj', 74.00, 1000, 1, '2025-01-01', '2025-04-17 13:33:20', '2025-07-09 05:09:09', 1, '', 1, 'No Record', '[1] - sAdmin', '2025-06-13', 'mL', 'Room A'),
(1063, 'ssdf', 'sdfsdf', 250.00, 500, 2, '2026-08-06', '2025-07-03 02:19:10', '2025-07-05 06:42:00', 0, NULL, 1, '[1] - bAdmin', 'No Update Record', '2025-07-11', 'mL', 'Room A'),
(1065, 'ddg', 'dfgdfg', 200.00, 200, 2, '2025-07-03', '2025-07-03 07:40:26', '2025-07-05 06:42:00', 0, '424242', 1, '[1] - sAdmin', 'No Update Record', '2025-07-03', 'mL', 'Room A');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chemicals`
--
ALTER TABLE `chemicals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_chem_branch` (`branch`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chemicals`
--
ALTER TABLE `chemicals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1066;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chemicals`
--
ALTER TABLE `chemicals`
  ADD CONSTRAINT `fk_chem_branch` FOREIGN KEY (`branch`) REFERENCES `branches` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
