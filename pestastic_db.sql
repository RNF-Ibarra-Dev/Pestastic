-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2025 at 10:08 AM
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
-- Table structure for table `branchadmin`
--

CREATE TABLE `branchadmin` (
  `baID` int(11) NOT NULL,
  `baFName` varchar(128) NOT NULL,
  `baLName` varchar(128) NOT NULL,
  `baUsn` varchar(128) NOT NULL,
  `baEmail` varchar(128) NOT NULL,
  `baPwd` varchar(128) NOT NULL,
  `baEmpId` varchar(15) NOT NULL,
  `baAddress` varchar(255) NOT NULL,
  `baContact` varchar(15) NOT NULL,
  `baBirthdate` date NOT NULL DEFAULT '1930-01-01',
  `user_branch` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branchadmin`
--

INSERT INTO `branchadmin` (`baID`, `baFName`, `baLName`, `baUsn`, `baEmail`, `baPwd`, `baEmpId`, `baAddress`, `baContact`, `baBirthdate`, `user_branch`) VALUES
(1, 'branch', 'admin', 'bAdmin', 'bAdmin@email.com', 'bAdmin', '012345678', '-', '123', '1930-01-14', 1),
(2, 'os', 'os', 'os', 'os@email.com', '$2y$10$qikFsWEjotZYeneR0x5.C.fTlLe/qQEkYLPlit8koQaQYFNh4cb.6', '0123', '-', '123', '1930-01-01', 2),
(3, 'wers', 'wers', 'wers', 'wer@gmail.com', '$2y$10$EeO/yYAJ/2NLT0QJ0yNzke9HVVnXXmiNHEuB1lWMOk/lryJpcoAg2', '123123', '-', '123123000', '1930-01-01', 1),
(5, 'aya', 'ibarra', 'aya123', 'aya@gmail.com', '$2y$10$fuswGyoFW1DOV.Hb3Y04W.L4BQvwHfxiGjz2wur3Q7OFkKiynjlf2', '002', '--', '123123123', '2020-06-10', 1),
(6, 'dsfsdf', 'sdfsd', 'sdfsd', 'sdffsd@email.com', '$2y$10$j42eZ1FNbU70Tx45BSyx1.R6OBX8p9dXwVIat8jTXtU3rC7wS36mC', '323', 'sdfsdf', '12345345321', '1930-01-01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `location` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `location`) VALUES
(1, 'Pestastic Main Branch', 'Sta Rosa'),
(2, 'Pestastic Parañaque Branch', 'Parañaque');

-- --------------------------------------------------------

--
-- Table structure for table `chemicals`
--

CREATE TABLE `chemicals` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `brand` varchar(128) NOT NULL,
  `chemLevel` int(11) NOT NULL,
  `expiryDate` date DEFAULT '2025-01-01',
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `request` tinyint(1) NOT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `branch` int(11) NOT NULL DEFAULT 1,
  `added_by` varchar(64) NOT NULL DEFAULT 'No Record',
  `updated_by` varchar(64) NOT NULL DEFAULT 'No Update Record',
  `date_received` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chemicals`
--

INSERT INTO `chemicals` (`id`, `name`, `brand`, `chemLevel`, `expiryDate`, `added_at`, `updated_at`, `request`, `notes`, `branch`, `added_by`, `updated_by`, `date_received`) VALUES
(1, 'Deltacide', 'ENVU', 5, '2025-03-06', '2025-04-12 15:06:30', '2025-06-11 08:38:36', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13'),
(2, 'Abates', 'BASF', 3, '2032-03-11', '2025-04-12 15:06:30', '2025-06-11 08:38:36', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13'),
(3, 'chemical', 'ABXV', 1, '2028-01-19', '2025-04-12 15:06:30', '2025-06-11 08:38:36', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13'),
(19, 'qwe', 'qwe', 0, '2025-04-03', '2025-04-12 15:06:30', '2025-06-11 08:38:36', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13'),
(44, 'asd', 'asd', 0, '2025-01-01', '2025-04-12 15:06:30', '2025-06-11 08:38:36', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13'),
(45, 'g', 'g', 0, '2025-01-01', '2025-04-12 15:06:30', '2025-06-11 08:38:36', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13'),
(46, 'n', 'n', 0, '2025-04-03', '2025-04-12 15:06:30', '2025-06-11 08:38:36', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13'),
(47, 'sad', 'asd', 500, '2025-01-01', '2025-04-12 15:06:30', '2025-06-11 08:38:36', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13'),
(49, 'asd', 'asd', 0, '2026-06-04', '2025-04-12 15:06:30', '2025-06-14 15:00:46', 0, '', 1, 'No Record', 'sAdmin | Employee no. 123', '2025-06-13'),
(50, 'Deltacides', 'CHEM', 0, '2025-01-01', '2025-04-12 15:06:30', '2025-06-11 08:38:36', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13'),
(51, 'asd', 'asd', 30, '2025-01-29', '2025-04-12 15:06:30', '2025-06-11 08:38:36', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13'),
(52, 'asd', 'asd', 25, '2025-01-29', '2025-04-12 15:06:30', '2025-06-11 08:38:36', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13'),
(54, 'chem B', 'brand B', 86, '2029-06-21', '2025-04-12 15:06:30', '2025-06-11 08:38:36', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13'),
(1000, 'hj', 'hj', 5, '2025-01-01', '2025-04-17 13:03:08', '2025-06-11 08:38:36', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13'),
(1001, 'gh', 'gh', 6, '2025-01-01', '2025-04-17 13:15:47', '2025-06-11 08:38:36', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13'),
(1002, 'fgh', 'fgh', 6, '2025-01-01', '2025-04-17 13:16:32', '2025-06-15 05:49:12', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13'),
(1003, 'asd', 'asd', 3, '2025-01-01', '2025-04-17 13:17:16', '2025-06-15 05:49:08', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13'),
(1004, 'fgh', 'fgh', 5, '2025-01-01', '2025-04-17 13:17:42', '2025-06-11 08:38:36', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13'),
(1005, 'hk', 'hjk', 8, '2025-01-01', '2025-04-17 13:23:47', '2025-06-15 05:49:09', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13'),
(1006, 'fgd', 'd', 5, '2025-01-01', '2025-04-17 13:24:22', '2025-06-15 05:49:07', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13'),
(1007, 'fgh', 'fgh', 6, '2025-01-01', '2025-04-17 13:25:27', '2025-06-11 08:38:36', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13'),
(1008, 'fgh', 'fgh', 6, '2025-01-01', '2025-04-17 13:25:30', '2025-06-15 05:49:05', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13'),
(1009, 'fgh', 'fgh', 6, '2025-01-01', '2025-04-17 13:25:31', '2025-06-15 05:49:03', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13'),
(1012, 'ghjp', 'ghj', 6, '2025-01-01', '2025-04-17 13:27:35', '2025-06-15 05:49:10', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13'),
(1013, 'jhh', 'gdfg', 500, '2025-01-01', '2025-04-17 13:31:02', '2025-06-14 14:42:26', 0, '', 1, 'No Record', 'sAdmin | Employee no. 123', '2025-06-13'),
(1014, 'jhghj', 'hjghj', 70, '2025-01-01', '2025-04-17 13:33:20', '2025-06-15 05:49:11', 1, '', 1, 'No Record', '[1] - sAdmin', '2025-06-13'),
(1039, 'asasas', 'asasas', 2, '2025-06-10', '2025-06-11 16:00:00', '2025-06-12 14:58:59', 1, '', 1, '[1] - sAdmin', 'No Update Record', '2025-06-13'),
(1040, 'dsdsds', 'dsdsds', 2, '2025-06-04', '2025-06-02 16:00:00', '2025-06-12 14:58:59', 1, NULL, 1, '[1] - sAdmin', 'No Update Record', '2025-06-13');

-- --------------------------------------------------------

--
-- Table structure for table `equipments`
--

CREATE TABLE `equipments` (
  `id` int(11) NOT NULL,
  `equipment` varchar(255) NOT NULL,
  `availability` enum('Available','Unavailable','In Repair') NOT NULL DEFAULT 'Unavailable',
  `equipment_image` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipments`
--

INSERT INTO `equipments` (`id`, `equipment`, `availability`, `equipment_image`, `description`) VALUES
(1, 'Sprayer', 'Unavailable', NULL, NULL),
(2, 'Duster', 'Unavailable', NULL, NULL),
(3, 'Foamer', 'In Repair', NULL, NULL),
(4, 'Fogger', 'Unavailable', NULL, NULL),
(5, 'Granule Spreader', 'Unavailable', NULL, NULL),
(6, 'UV Flashlight', 'Unavailable', NULL, NULL),
(7, 'Inspection Mirror', 'Unavailable', NULL, ''),
(8, 'Moisture Meter', 'Unavailable', NULL, ''),
(9, 'Termite Detector', 'Unavailable', NULL, 'FFFFs'),
(28, 'Tulip', 'Available', '../../uploads/e_68219b9315e777.44682019-7b325eeb8b126ff93b7f22f65c3c7849841f4a300617365cd9515c1e4e20aba8_25-05-12_single tulip.jpg', 'Tulip');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT 'Name not set',
  `session_count` int(11) NOT NULL DEFAULT 0,
  `year_warranty` int(11) NOT NULL DEFAULT 0,
  `treatment` int(11) DEFAULT NULL,
  `branch` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `name`, `session_count`, `year_warranty`, `treatment`, `branch`) VALUES
(101, 'Termite Control Package', 4, 2, 4, 1),
(102, 'Termite Control Package', 8, 2, 4, 1),
(103, 'Termite Control Package', 1, 1, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pest_problems`
--

CREATE TABLE `pest_problems` (
  `id` int(11) NOT NULL,
  `problems` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pest_problems`
--

INSERT INTO `pest_problems` (`id`, `problems`) VALUES
(6, 'Ants'),
(5, 'Bed Bugs'),
(10, 'Centipedes'),
(4, 'Cockroach'),
(1, 'Drywood Termites'),
(12, 'Fleas'),
(8, 'Flies'),
(3, 'German Cockroach'),
(13, 'Milipedes'),
(7, 'Mosquitoes'),
(2, 'Subterranean Termites'),
(11, 'Ticks'),
(9, 'Weevils');

-- --------------------------------------------------------

--
-- Table structure for table `superadmin`
--

CREATE TABLE `superadmin` (
  `saID` int(11) NOT NULL,
  `saUsn` varchar(128) NOT NULL,
  `saName` varchar(128) NOT NULL,
  `saEmail` varchar(128) NOT NULL,
  `saPwd` varchar(128) NOT NULL,
  `saEmpId` varchar(15) NOT NULL,
  `saBirthdate` date NOT NULL DEFAULT '1930-01-01',
  `user_branch` int(11) NOT NULL DEFAULT 1,
  `saLName` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `superadmin`
--

INSERT INTO `superadmin` (`saID`, `saUsn`, `saName`, `saEmail`, `saPwd`, `saEmpId`, `saBirthdate`, `user_branch`, `saLName`) VALUES
(1, 'sAdmin', 'super', 'sAdmin@email.com', 'sAdmin', '123', '1930-01-01', 1, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `technician`
--

CREATE TABLE `technician` (
  `technicianId` int(11) NOT NULL,
  `firstName` varchar(128) NOT NULL,
  `lastName` varchar(128) NOT NULL,
  `username` varchar(128) NOT NULL,
  `techEmail` varchar(128) NOT NULL,
  `techPwd` varchar(128) NOT NULL,
  `techContact` varchar(15) NOT NULL,
  `techAddress` varchar(255) NOT NULL,
  `techEmpId` varchar(3) NOT NULL,
  `techBirthdate` date NOT NULL DEFAULT '1930-01-01',
  `technician_status` enum('Available','On Leave','Dispatched','Unavailable') NOT NULL DEFAULT 'Unavailable',
  `user_branch` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `technician`
--

INSERT INTO `technician` (`technicianId`, `firstName`, `lastName`, `username`, `techEmail`, `techPwd`, `techContact`, `techAddress`, `techEmpId`, `techBirthdate`, `technician_status`, `user_branch`) VALUES
(1, 'rayan', 'ibarra', 'qweee3231', 'pedro@email.com', '$2y$10$cti18IMlEMqlgJi2X2Dpfut49RIGvIartiYitMKLCqaMFbx/u97aq', '0917 123 4567', 'Phase 9, Phase 7 B3, Lot 14, Bagong Silang, Caloocan City, Metro Manila', '456', '1930-02-05', 'Unavailable', 1),
(2, 'asdf', 'asdf', 'tech1', 'asd@a.com', '$2y$10$70/GbO1etq830pk1gX6sMuz6QstqZIpMO2/xEey4Sla01wqRekdKG', '639610970714', '362 Moret St, Sampaloc, Manila, 1008 Metro Manila, Philippines', '654', '1930-01-29', 'Available', 1),
(4, 'rayan', 'ibarra', 'sdf', 'leal@gmail.com', '041902', '6391 2321 123', '-', '065', '1930-01-01', 'Dispatched', 1),
(10, 'fgh', 'fgh', 'fgh', 'fghfgh@email.com', '$2y$10$/upNMNYpGayHj5V51qpSuuyN9kTYFGhUT9pyt9jB8fcKPphv4Crv.', '456564546', '--', '645', '1930-01-01', 'On Leave', 1),
(15, 'alena', 'datolayta', 'alena', 'alenadatolayta10@gmail.com', '$2y$10$0nDgpiyIh1B9un8kCOIQnupvVI6fgaEY3CVE/LDxeIbQPLEGTLSnq', '32132131', '--', '001', '2002-07-10', 'Dispatched', 1),
(18, 'gf', 'ddfd', 'dgdf', 'ddg@email.com', '$2y$10$4Gjaym94aue7hMlxsxnavuhYT4sXZo7wHaq4LVNBT2a4TazvZFxn6', '00000000000', 'dasdas', '111', '1930-01-01', 'Available', 1),
(19, 'sdfsdf', 'sdfsdfs', '32424', 'bsdfsdfa@email.com', '$2y$10$nOHsQ94.118PwzZwiC/e3.rL17YZG1cAgWTKumc4upgwHmdSOuO0u', '00000000000', '000', '000', '1930-01-21', 'Dispatched', 1);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `treatment_date` date DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `transaction_status` enum('Pending','Accepted','Voided','Completed','Cancelled') NOT NULL DEFAULT 'Pending',
  `void_request` tinyint(1) NOT NULL DEFAULT 0,
  `transaction_time` time DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `t_finished` datetime DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `treatment_type` enum('General Treatment','Follow-up Treatment','Quarterly Treatment','Monthly Treatment') DEFAULT NULL,
  `treatment` int(11) NOT NULL,
  `pack_exp` date DEFAULT NULL,
  `session_no` int(11) DEFAULT NULL,
  `pack_start` date DEFAULT NULL,
  `updated_by` varchar(128) NOT NULL DEFAULT 'No User',
  `created_by` varchar(128) NOT NULL DEFAULT 'No User',
  `branch` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `treatment_date`, `customer_name`, `customer_address`, `created_at`, `updated_at`, `transaction_status`, `void_request`, `transaction_time`, `notes`, `t_finished`, `package_id`, `treatment_type`, `treatment`, `pack_exp`, `session_no`, `pack_start`, `updated_by`, `created_by`, `branch`) VALUES
(76, '2025-04-04', 'gffggh', NULL, '2025-03-31 13:07:43', '2025-04-04 13:56:20', 'Completed', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1),
(80, '2025-04-25', 'dfgfd', NULL, '2025-04-01 14:23:01', '2025-05-30 10:06:07', 'Completed', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1),
(82, NULL, 'ggddf', NULL, '2025-04-05 13:46:48', '2025-05-30 10:06:07', 'Pending', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1),
(83, '2025-05-01', 'sssss', NULL, '2025-04-05 13:47:19', '2025-04-05 13:47:19', 'Voided', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1),
(84, '2025-05-20', 'asd', NULL, '2025-04-05 14:14:53', '2025-05-30 10:06:07', 'Completed', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1),
(85, '2025-04-19', 'kkjljkl', NULL, '2025-04-06 05:19:12', '2025-04-13 14:09:32', 'Accepted', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1),
(96, '2025-04-19', 'dfsdf', NULL, '2025-04-10 06:51:37', '2025-05-28 14:22:40', 'Voided', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1),
(97, '2025-04-25', 'ghj', NULL, '2025-04-10 06:53:43', '2025-05-30 10:06:07', 'Voided', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1),
(98, '2025-04-25', 'jkl', NULL, '2025-04-10 06:54:24', '2025-05-30 10:06:07', 'Pending', 0, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1),
(99, '2025-04-25', NULL, NULL, '2025-04-10 06:57:10', '2025-05-30 10:06:07', 'Pending', 1, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1),
(100, '2025-04-25', 'ghbgh', NULL, '2025-04-10 07:07:38', '2025-05-30 10:06:07', 'Voided', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1),
(101, NULL, 'hjkjk', NULL, '2025-04-10 07:08:48', '2025-05-30 10:06:07', 'Pending', 1, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1),
(102, '2025-04-18', 'lkkl', NULL, '2025-04-10 07:12:11', '2025-05-30 10:06:07', 'Pending', 1, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1),
(103, '2025-04-24', 'sdf', NULL, '2025-04-10 13:15:57', '2025-05-01 13:38:29', 'Pending', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1),
(104, '2025-05-02', 'dfgdf', NULL, '2025-04-12 15:10:19', '2025-05-30 10:06:07', 'Accepted', 1, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1),
(10001, '2025-04-26', 'gfhfgh', NULL, '2025-04-12 15:15:31', '2025-05-30 10:06:07', 'Voided', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1),
(10002, '2025-06-06', 'jhh', NULL, '2025-04-20 13:13:41', '2025-05-30 10:06:07', 'Accepted', 1, '12:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1),
(10003, '2025-05-01', 'sdfsds', NULL, '2025-04-22 07:52:06', '2025-05-30 10:06:07', 'Voided', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1),
(10004, '2025-05-01', '......', NULL, '2025-04-22 07:56:38', '2025-05-29 12:12:41', 'Voided', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1),
(10017, '2025-05-30', 'asd', NULL, '2025-05-11 07:11:03', '2025-05-30 10:06:07', 'Pending', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1),
(10018, '2025-05-30', 'dfgfg', NULL, '2025-05-22 06:18:45', '2025-05-30 10:06:07', 'Pending', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1),
(10019, '2025-06-05', 'customer', NULL, '2025-05-29 15:18:17', '2025-05-30 10:06:07', 'Pending', 0, '11:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1),
(10020, '2025-06-12', 'ggh', NULL, '2025-06-01 06:59:38', '2025-06-02 12:20:38', 'Pending', 0, '02:00:00', NULL, NULL, NULL, 'General Treatment', 3, NULL, NULL, NULL, 'No User', 'No User', 1),
(10021, '2025-06-11', 'Albert Einstein', NULL, '2025-06-01 07:05:44', '2025-06-02 12:20:35', 'Pending', 0, '02:00:00', NULL, NULL, NULL, 'Follow-up Treatment', 2, NULL, NULL, NULL, 'No User', 'No User', 1),
(10022, '2025-06-03', 'Sigmund Freud', 'asdas', '2025-06-02 14:44:33', '2025-06-10 08:02:03', 'Accepted', 0, '12:00:00', '', NULL, 102, 'Follow-up Treatment', 4, '2027-06-17', 3, '2025-06-17', 'No User', 'No User', 1),
(10023, '2025-06-26', 'Name', 'Address', '2025-06-05 14:08:45', '2025-06-07 15:02:57', 'Accepted', 0, '09:05:00', 'notess\r\n', NULL, 101, 'Follow-up Treatment', 4, '2027-06-18', 4, '2025-06-18', 'No User', 'No User', 1);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_chemicals`
--

CREATE TABLE `transaction_chemicals` (
  `trans_id` int(11) NOT NULL,
  `chem_id` int(11) NOT NULL,
  `chem_brand` varchar(255) NOT NULL,
  `amt_used` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_chemicals`
--

INSERT INTO `transaction_chemicals` (`trans_id`, `chem_id`, `chem_brand`, `amt_used`) VALUES
(76, 1, 'Deltacide | ENVU', 56),
(76, 51, 'asd | asd', 56),
(80, 54, 'chem B | brand B', 56),
(82, 52, 'asd | asd', 55),
(83, 54, 'chem B | brand B', 22),
(84, 1, 'Deltacide | ENVU', 89),
(85, 52, 'asd | asd', 11),
(96, 51, 'asd | asd', 3),
(97, 51, 'asd | asd', 6),
(98, 50, 'Deltacides | CHEM', 9),
(99, 1, 'Deltacide | ENVU', 1),
(100, 1, 'Deltacide | ENVU', 1),
(101, 1, 'Deltacide | ENVU', 1),
(102, 1, 'Deltacide | ENVU', 1),
(103, 1, 'Deltacide | ENVU', 1),
(104, 3, 'chemical | ABXV', 5),
(10001, 2, 'Abates | BASF', 4),
(10001, 47, 'sad | asd', 3),
(10001, 51, 'asd | asd', 5),
(10002, 54, 'chem B | brand B', 8),
(10003, 51, 'asd | asd', 3),
(10003, 1002, 'fgh | fgh', 3),
(10004, 1004, 'fgh | fgh', 7),
(10017, 1014, 'jhghj | hjghj', 4),
(10018, 50, 'Deltacides | CHEM', 3),
(10019, 54, 'chem B | brand B', 5),
(10020, 54, 'chem B | brand B', 0),
(10020, 1004, 'fgh | fgh', 0),
(10021, 54, 'chem B | brand B', 0),
(10021, 1005, 'hk | hjk', 0),
(10022, 51, 'asd | asd', 3),
(10023, 52, 'asd | asd', 5);

--
-- Triggers `transaction_chemicals`
--
DELIMITER $$
CREATE TRIGGER `verify_chem_id` BEFORE INSERT ON `transaction_chemicals` FOR EACH ROW BEGIN

	IF NOT EXISTS (SELECT 1 FROM chemicals WHERE id = NEW.chem_id) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Chemical does not exist.';
    END IF;
    
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_problems`
--

CREATE TABLE `transaction_problems` (
  `trans_id` int(11) NOT NULL,
  `problem_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_problems`
--

INSERT INTO `transaction_problems` (`trans_id`, `problem_id`) VALUES
(76, 4),
(76, 11),
(76, 12),
(80, 4),
(80, 5),
(80, 11),
(80, 12),
(82, 7),
(82, 10),
(82, 13),
(83, 6),
(83, 10),
(84, 2),
(84, 10),
(85, 4),
(85, 11),
(96, 3),
(97, 4),
(98, 10),
(99, 1),
(100, 1),
(101, 1),
(102, 1),
(103, 1),
(104, 3),
(104, 10),
(10001, 1),
(10001, 2),
(10001, 3),
(10001, 9),
(10001, 10),
(10002, 3),
(10002, 10),
(10003, 2),
(10003, 3),
(10003, 4),
(10003, 10),
(10003, 11),
(10004, 2),
(10004, 3),
(10004, 4),
(10017, 3),
(10017, 10),
(10018, 3),
(10018, 10),
(10019, 3),
(10019, 10),
(10020, 2),
(10020, 9),
(10021, 1),
(10021, 9),
(10022, 1),
(10023, 1),
(10023, 3),
(10023, 6),
(10023, 13);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_technicians`
--

CREATE TABLE `transaction_technicians` (
  `trans_id` int(11) NOT NULL,
  `tech_id` int(11) NOT NULL,
  `tech_info` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_technicians`
--

INSERT INTO `transaction_technicians` (`trans_id`, `tech_id`, `tech_info`) VALUES
(76, 2, 'asdf asdf'),
(76, 10, 'fgh fgh'),
(80, 2, 'asdf asdf'),
(82, 4, 'rayan ibarra'),
(83, 4, 'rayan ibarra'),
(84, 4, 'rayan ibarra'),
(85, 2, 'asdf asdf'),
(96, 2, 'asdf asdf'),
(97, 10, 'fgh fgh'),
(98, 2, 'asdf asdf'),
(99, 2, 'asdf asdf'),
(100, 2, 'asdf asdf'),
(101, 2, 'asdf asdf'),
(102, 2, 'asdf asdf'),
(103, 2, 'asdf asdf'),
(104, 2, 'asdf asdf'),
(10001, 1, 'rayan ibarra'),
(10001, 2, 'asdf asdf'),
(10001, 4, 'rayan ibarra'),
(10002, 2, 'asdf asdf'),
(10003, 2, 'asdf asdf'),
(10003, 10, 'fgh fgh'),
(10004, 2, 'asdf asdf'),
(10017, 2, 'asdf asdf'),
(10019, 4, 'rayan ibarra'),
(10020, 1, 'rayan ibarra'),
(10020, 10, 'fgh fgh'),
(10021, 4, 'rayan ibarra'),
(10021, 10, 'fgh fgh'),
(10022, 4, 'rayan ibarra'),
(10022, 10, 'fgh fgh'),
(10023, 15, 'alena datolayta');

--
-- Triggers `transaction_technicians`
--
DELIMITER $$
CREATE TRIGGER `verify_tech_id` BEFORE INSERT ON `transaction_technicians` FOR EACH ROW BEGIN
	
    IF NOT EXISTS (SELECT 1 FROM technician WHERE technicianId = NEW.tech_id) THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Error: Technician does not exist.';
    END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `treatments`
--

CREATE TABLE `treatments` (
  `id` int(11) NOT NULL,
  `t_name` varchar(125) NOT NULL,
  `branch` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `treatments`
--

INSERT INTO `treatments` (`id`, `t_name`, `branch`) VALUES
(1, 'Wooden Structures Treatment', 1),
(2, 'Termite Powder Application', 1),
(3, 'Soil Injection', 1),
(4, 'Termite Control', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branchadmin`
--
ALTER TABLE `branchadmin`
  ADD PRIMARY KEY (`baID`),
  ADD KEY `fk_ba_user_branch` (`user_branch`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chemicals`
--
ALTER TABLE `chemicals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_chem_branch` (`branch`);

--
-- Indexes for table `equipments`
--
ALTER TABLE `equipments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `treatment` (`treatment`),
  ADD KEY `fk_packages_branch` (`branch`);

--
-- Indexes for table `pest_problems`
--
ALTER TABLE `pest_problems`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `problems` (`problems`);

--
-- Indexes for table `superadmin`
--
ALTER TABLE `superadmin`
  ADD PRIMARY KEY (`saID`),
  ADD KEY `fk_sa_user_branch` (`user_branch`);

--
-- Indexes for table `technician`
--
ALTER TABLE `technician`
  ADD PRIMARY KEY (`technicianId`),
  ADD KEY `fk_tech_user_branch` (`user_branch`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_id` (`package_id`),
  ADD KEY `fk_t_id` (`treatment`),
  ADD KEY `fk_transactions_branch` (`branch`);

--
-- Indexes for table `transaction_chemicals`
--
ALTER TABLE `transaction_chemicals`
  ADD PRIMARY KEY (`trans_id`,`chem_id`),
  ADD KEY `transaction_chemicals_ibfk_2` (`chem_id`);

--
-- Indexes for table `transaction_problems`
--
ALTER TABLE `transaction_problems`
  ADD PRIMARY KEY (`trans_id`,`problem_id`),
  ADD KEY `transaction_problems_ibfk_1` (`trans_id`),
  ADD KEY `transaction_problems_ibfk_2` (`problem_id`);

--
-- Indexes for table `transaction_technicians`
--
ALTER TABLE `transaction_technicians`
  ADD PRIMARY KEY (`trans_id`,`tech_id`),
  ADD KEY `transaction_technicians_ibfk_2` (`tech_id`);

--
-- Indexes for table `treatments`
--
ALTER TABLE `treatments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_treatments_branch` (`branch`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branchadmin`
--
ALTER TABLE `branchadmin`
  MODIFY `baID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `chemicals`
--
ALTER TABLE `chemicals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1063;

--
-- AUTO_INCREMENT for table `equipments`
--
ALTER TABLE `equipments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `pest_problems`
--
ALTER TABLE `pest_problems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `superadmin`
--
ALTER TABLE `superadmin`
  MODIFY `saID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `technician`
--
ALTER TABLE `technician`
  MODIFY `technicianId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10024;

--
-- AUTO_INCREMENT for table `treatments`
--
ALTER TABLE `treatments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `branchadmin`
--
ALTER TABLE `branchadmin`
  ADD CONSTRAINT `fk_ba_user_branch` FOREIGN KEY (`user_branch`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `fk_user_branch` FOREIGN KEY (`user_branch`) REFERENCES `branches` (`id`);

--
-- Constraints for table `chemicals`
--
ALTER TABLE `chemicals`
  ADD CONSTRAINT `fk_chem_branch` FOREIGN KEY (`branch`) REFERENCES `branches` (`id`);

--
-- Constraints for table `packages`
--
ALTER TABLE `packages`
  ADD CONSTRAINT `fk_packages_branch` FOREIGN KEY (`branch`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `packages_ibfk_1` FOREIGN KEY (`treatment`) REFERENCES `treatments` (`id`);

--
-- Constraints for table `superadmin`
--
ALTER TABLE `superadmin`
  ADD CONSTRAINT `fk_sa_user_branch` FOREIGN KEY (`user_branch`) REFERENCES `branches` (`id`);

--
-- Constraints for table `technician`
--
ALTER TABLE `technician`
  ADD CONSTRAINT `fk_tech_user_branch` FOREIGN KEY (`user_branch`) REFERENCES `branches` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_t_id` FOREIGN KEY (`treatment`) REFERENCES `treatments` (`id`),
  ADD CONSTRAINT `fk_transactions_branch` FOREIGN KEY (`branch`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`);

--
-- Constraints for table `transaction_chemicals`
--
ALTER TABLE `transaction_chemicals`
  ADD CONSTRAINT `transaction_chemicals_ibfk_2` FOREIGN KEY (`chem_id`) REFERENCES `chemicals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_chemicals_ibfk_3` FOREIGN KEY (`trans_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction_problems`
--
ALTER TABLE `transaction_problems`
  ADD CONSTRAINT `transaction_problems_ibfk_1` FOREIGN KEY (`trans_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_problems_ibfk_2` FOREIGN KEY (`problem_id`) REFERENCES `pest_problems` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction_technicians`
--
ALTER TABLE `transaction_technicians`
  ADD CONSTRAINT `transaction_technicians_ibfk_1` FOREIGN KEY (`trans_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_technicians_ibfk_2` FOREIGN KEY (`tech_id`) REFERENCES `technician` (`technicianId`) ON DELETE CASCADE;

--
-- Constraints for table `treatments`
--
ALTER TABLE `treatments`
  ADD CONSTRAINT `fk_treatments_branch` FOREIGN KEY (`branch`) REFERENCES `branches` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
