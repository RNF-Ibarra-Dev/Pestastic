-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 11, 2025 at 01:08 AM
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
(1, 'branch', 'admin', 'bAdmin', 'bAdmin@email.com', '$2y$13$4bt2QK95Yrz929t9CoHIc.balO5OVF.kFsirEhEU2krxHSyvhb7ji', '012345678', '-', '123', '1930-01-14', 1),
(2, 'os', 'os', 'os', 'os@email.com', '$2y$10$qikFsWEjotZYeneR0x5.C.fTlLe/qQEkYLPlit8koQaQYFNh4cb.6', '0123', '-', '123', '1930-01-01', 2),
(3, 'wers', 'wers', 'wers', 'wer@gmail.com', '$2y$10$EeO/yYAJ/2NLT0QJ0yNzke9HVVnXXmiNHEuB1lWMOk/lryJpcoAg2', '123123', '-', '123123000', '1930-01-01', 1),
(5, 'aya', 'ibarra', 'aya123', 'aya@gmail.com', '$2y$10$fuswGyoFW1DOV.Hb3Y04W.L4BQvwHfxiGjz2wur3Q7OFkKiynjlf2', '002', '--', '123123123', '2020-06-10', 1),
(6, 'dsfsdf', 'sdfsd', 'sdfsd', 'yacava6772@exitbit.com', '$2y$10$j42eZ1FNbU70Tx45BSyx1.R6OBX8p9dXwVIat8jTXtU3rC7wS36mC', '323', 'sdfsdf', '12345345321', '1930-01-01', 1);

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
(2, 'Pestastic Parañaque Branch', 'Parañaque'),
(3, 'Cavite Branch', 'Cavite km. 69'),
(4, 'Quezon Branch', 'Quezon city'),
(5, 'Cavite Branch', 'Cavite Km. 69'),
(7, 'hjjhh', 'hjhjkk');

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
  `quantity_unit` enum('mg','g','kg','L','mL') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chemicals`
--

INSERT INTO `chemicals` (`id`, `name`, `brand`, `chemLevel`, `container_size`, `unop_cont`, `expiryDate`, `added_at`, `updated_at`, `request`, `notes`, `branch`, `added_by`, `updated_by`, `date_received`, `quantity_unit`) VALUES
(1, 'Deltacide', 'ENVU', 6.03, 1000, 1, '2025-03-06', '2025-04-12 15:06:30', '2025-07-10 15:59:33', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL'),
(2, 'Abates', 'BASF', 5.00, 250, 2, '2032-03-11', '2025-04-12 15:06:30', '2025-07-10 22:22:37', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL'),
(3, 'chemical', 'ABXV', 1.00, 1000, 1, '2028-01-19', '2025-04-12 15:06:30', '2025-07-05 07:18:34', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'kg'),
(19, 'qwe', 'qwe', 0.00, 1000, 0, '2025-04-03', '2025-04-12 15:06:30', '2025-07-05 06:42:00', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL'),
(44, 'asd', 'asd', 0.00, 1000, 0, '2025-01-01', '2025-04-12 15:06:30', '2025-07-05 06:42:00', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL'),
(45, 'g', 'g', 0.00, 1000, 0, '2025-01-01', '2025-04-12 15:06:30', '2025-07-05 06:42:00', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL'),
(46, 'n', 'n', 0.00, 1000, 0, '2025-04-03', '2025-04-12 15:06:30', '2025-07-05 06:42:00', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL'),
(47, 'sad', 'asd', 492.00, 1000, 0, '2028-07-19', '2025-04-12 15:06:30', '2025-07-09 22:08:37', 0, '', 1, 'No Record', 'sAdmin | Employee no. 123', '2025-06-13', 'g'),
(49, 'asd', 'asd', 0.00, 1000, 0, '2027-08-13', '2025-04-12 15:06:30', '2025-07-05 06:42:00', 0, '', 1, 'No Record', 'sAdmin | Employee no. 123', '2025-06-13', 'mL'),
(50, 'Deltacides', 'CHEMM', 500.00, 1000, 5, '2025-01-01', '2025-04-12 15:06:30', '2025-07-05 06:42:00', 1, '', 1, 'No Record', 'bAdmin | Employee no. 012345678', '2025-06-13', 'mL'),
(51, 'asd', 'asd', 30.00, 1000, 0, '2025-01-29', '2025-04-12 15:06:30', '2025-07-05 06:42:00', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL'),
(52, 'asd', 'asd', 25.00, 1000, 0, '2025-01-29', '2025-04-12 15:06:30', '2025-07-05 06:42:00', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL'),
(54, 'chem B', 'brand B', 85.00, 1000, 0, '2029-06-21', '2025-04-12 15:06:30', '2025-07-10 22:16:27', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL'),
(1000, 'hj', 'hj', 5.00, 1000, 0, '2025-01-01', '2025-04-17 13:03:08', '2025-07-05 06:42:00', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL'),
(1001, 'gh', 'gh', 3.00, 1000, 0, '2025-01-01', '2025-04-17 13:15:47', '2025-07-05 06:42:00', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL'),
(1002, 'fgh', 'fgh', 6.00, 1000, 0, '2025-01-01', '2025-04-17 13:16:32', '2025-07-05 07:13:26', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'g'),
(1004, 'fgh', 'fgh', 5.00, 1000, 2, '2025-01-01', '2025-04-17 13:17:42', '2025-07-05 06:42:00', 0, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL'),
(1005, 'hk', 'hjk', 8.00, 1000, 0, '2025-01-01', '2025-04-17 13:23:47', '2025-07-05 06:42:00', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL'),
(1006, 'fgd', 'd', 5.00, 1000, 4, '2025-01-01', '2025-04-17 13:24:22', '2025-07-05 06:42:00', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL'),
(1007, 'fgh', 'fgh', 6.00, 250, 0, '2025-01-01', '2025-04-17 13:25:27', '2025-07-05 06:42:00', 1, '', 1, 'No Record', 'bAdmin | Employee no. 012345678', '2025-06-13', 'mL'),
(1008, 'fgh', 'fgh', 6.00, 1000, 5, '2025-01-01', '2025-04-17 13:25:30', '2025-07-05 07:13:32', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'kg'),
(1009, 'fgh', 'fgh', 6.00, 1000, 0, '2025-01-01', '2025-04-17 13:25:31', '2025-07-05 06:42:00', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL'),
(1012, 'ghjp', 'ghj', 6.00, 500, 0, '2025-01-01', '2025-04-17 13:27:35', '2025-07-05 06:42:00', 1, NULL, 1, 'No Record', 'No Update Record', '2025-06-13', 'mL'),
(1013, 'asbg', 'gdfg', 500.00, 1000, 5, '2025-07-24', '2025-04-17 13:31:02', '2025-07-08 23:09:18', 1, '', 1, 'No Record', 'bAdmin | Employee no. 012345678', '2025-06-13', 'mL'),
(1014, 'jhghj', 'hjghj', 74.00, 1000, 1, '2025-01-01', '2025-04-17 13:33:20', '2025-07-09 05:09:09', 1, '', 1, 'No Record', '[1] - sAdmin', '2025-06-13', 'mL'),
(1063, 'ssdf', 'sdfsdf', 250.00, 500, 2, '2026-08-06', '2025-07-03 02:19:10', '2025-07-05 06:42:00', 0, NULL, 1, '[1] - bAdmin', 'No Update Record', '2025-07-11', 'mL'),
(1065, 'ddg', 'dfgdfg', 200.00, 200, 2, '2025-07-03', '2025-07-03 07:40:26', '2025-07-05 06:42:00', 0, '424242', 1, '[1] - sAdmin', 'No Update Record', '2025-07-03', 'mL');

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
-- Table structure for table `inventory_log`
--

CREATE TABLE `inventory_log` (
  `log_id` int(11) NOT NULL,
  `chem_id` int(11) NOT NULL,
  `log_type` varchar(50) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `log_date` datetime DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL,
  `user_role` varchar(50) DEFAULT NULL,
  `trans_id` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `branch` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_log`
--

INSERT INTO `inventory_log` (`log_id`, `chem_id`, `log_type`, `quantity`, `log_date`, `user_id`, `user_role`, `trans_id`, `notes`, `branch`) VALUES
(1, 1014, 'Manual Stock Correction (In)', 3.00, '2025-07-08 00:00:00', 1, 'branchadmin', NULL, 'f', 1),
(2, 1013, 'Lost/Damaged Item', -2.00, '2025-07-09 00:12:47', 1, 'branchadmin', NULL, 'nawala ngani', 1),
(3, 1013, 'Found Outside', 1000.00, '2025-07-09 05:52:22', 1, 'branchadmin', NULL, 'container found outside', 1),
(4, 1013, 'Manual Stock Correction (In)', 1000.00, '2025-07-09 06:02:41', 1, 'branchadmin', NULL, 'chemical stock from garage', 1),
(5, 1013, 'Chemical Adjustment Error', -1000.00, '2025-07-09 06:09:52', 1, 'branchadmin', NULL, 'chemical bug occured', 1),
(6, 1013, 'Other', -1000.00, '2025-07-09 06:32:24', 1, 'branchadmin', NULL, 'bug testing', 1),
(7, 1013, 'Manual Stock Correction (Out)', -1000.00, '2025-07-09 06:35:56', 1, 'branchadmin', NULL, 'removing expired container', 1),
(8, 1013, 'Manual Stock Correction (In)', 0.00, '2025-07-09 06:40:23', 1, 'branchadmin', NULL, '---', 1),
(9, 1013, 'Manual Stock Correction (In)', 0.00, '2025-07-09 06:56:19', 1, 'branchadmin', NULL, 'none', 1),
(10, 1013, 'Manual Stock Correction (Out)', -2.00, '2025-07-09 06:56:33', 1, 'branchadmin', NULL, 'leaks', 1),
(11, 1013, 'Manual Stock Correction (Out)', 0.00, '2025-07-09 07:05:23', 1, 'branchadmin', NULL, 'note', 1),
(12, 1013, 'Manual Stock Correction (Out)', 0.00, '2025-07-09 07:06:04', 1, 'branchadmin', NULL, 'note', 1),
(13, 1013, 'Manual Stock Correction (Out)', 0.00, '2025-07-09 07:06:42', 1, 'branchadmin', NULL, 'note', 1),
(14, 1013, 'Manual Stock Correction (Out)', 0.00, '2025-07-09 07:06:42', 1, 'branchadmin', NULL, 'note', 1),
(15, 1013, 'Manual Stock Correction (In)', 500.00, '2025-07-09 07:08:48', 1, 'branchadmin', NULL, '000', 1),
(16, 1013, 'Balance', 0.00, '2025-07-09 07:09:18', 1, 'branchadmin', NULL, '----', 1),
(17, 1014, 'Manual Stock Correction (In)', 2000.00, '2025-07-09 13:07:56', 1, 'branchadmin', NULL, '--', 1),
(18, 1014, 'Trashed Item', -1000.00, '2025-07-09 13:09:09', 1, 'branchadmin', NULL, 'expired chemical', 1),
(19, 1000, 'Out', 2.00, '2025-07-10 21:33:08', 1, 'branchadmin', NULL, '', 1),
(20, 51, 'Out', 5.00, '2025-07-10 21:33:08', 1, 'branchadmin', NULL, '', 1),
(21, 54, 'Out', 2.00, '2025-07-11 06:16:27', 1, 'branchadmin', 10040, '', 1),
(22, 2, 'Return', 2.00, '2025-07-11 06:22:37', 1, 'branchadmin', 10041, '', 1);

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
-- Table structure for table `reset_password`
--

CREATE TABLE `reset_password` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `reset_token_hash` varchar(64) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reset_password`
--

INSERT INTO `reset_password` (`id`, `email`, `reset_token_hash`, `reset_token_expires_at`) VALUES
(1, 'yacava6772@exitbit.com', 'b6565d9731f6bab39ab914033d18f28b2b4319dea7822ef5ee358333a6d2f883', '2025-06-26 14:05:18'),
(3, 'rnnoleal@gmail.com', 'c83057191e4d4c5b99abb1cb020240e036ecc03f12d2e1839cde00eb115f994b', '2025-06-26 21:25:40'),
(21, 'alenadatolayta10@gmail.com', '7e433b304e340418ec7c978b24849b3ca7563aedc3de2e13cba15e24e6d7c6e5', '2025-07-01 13:28:25');

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
(1, 'rayan', 'ibarra', 'qweee3231', 'rnnoleal@gmail.com', '$2y$10$cti18IMlEMqlgJi2X2Dpfut49RIGvIartiYitMKLCqaMFbx/u97aq', '0917 123 4567', 'Phase 9, Phase 7 B3, Lot 14, Bagong Silang, Caloocan City, Metro Manila', '456', '1930-02-05', 'Unavailable', 1),
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
  `transaction_status` enum('Pending','Accepted','Voided','Completed','Cancelled','Finalizing','Dispatched') NOT NULL DEFAULT 'Pending',
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
  `branch` int(11) NOT NULL DEFAULT 1,
  `complete_request` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `treatment_date`, `customer_name`, `customer_address`, `created_at`, `updated_at`, `transaction_status`, `void_request`, `transaction_time`, `notes`, `t_finished`, `package_id`, `treatment_type`, `treatment`, `pack_exp`, `session_no`, `pack_start`, `updated_by`, `created_by`, `branch`, `complete_request`) VALUES
(76, '2025-04-04', 'gffggh', NULL, '2025-03-31 13:07:43', '2025-04-04 13:56:20', 'Completed', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0),
(80, '2025-04-25', 'dfgfd', NULL, '2025-04-01 14:23:01', '2025-05-30 10:06:07', 'Completed', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0),
(82, NULL, 'ggddf', NULL, '2025-04-05 13:46:48', '2025-05-30 10:06:07', 'Pending', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0),
(83, '2025-05-01', 'sssss', NULL, '2025-04-05 13:47:19', '2025-04-05 13:47:19', 'Voided', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0),
(84, '2025-05-20', 'asd', NULL, '2025-04-05 14:14:53', '2025-05-30 10:06:07', 'Completed', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0),
(85, '2025-04-19', 'kkjljkl', NULL, '2025-04-06 05:19:12', '2025-07-06 05:46:29', 'Finalizing', 0, '11:14:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 1),
(96, '2025-04-19', 'dfsdf', NULL, '2025-04-10 06:51:37', '2025-05-28 14:22:40', 'Voided', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0),
(97, '2025-04-25', 'ghj', NULL, '2025-04-10 06:53:43', '2025-05-30 10:06:07', 'Voided', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0),
(98, '2025-04-25', 'jkl', NULL, '2025-04-10 06:54:24', '2025-07-06 05:50:29', 'Cancelled', 0, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0),
(99, '2025-04-25', NULL, NULL, '2025-04-10 06:57:10', '2025-05-30 10:06:07', 'Pending', 1, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0),
(100, '2025-04-25', 'ghbgh', NULL, '2025-04-10 07:07:38', '2025-06-28 06:36:27', 'Voided', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 2, 0),
(101, NULL, 'hjkjk', NULL, '2025-04-10 07:08:48', '2025-05-30 10:06:07', 'Pending', 1, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0),
(102, '2025-04-18', 'lkkl', NULL, '2025-04-10 07:12:11', '2025-05-30 10:06:07', 'Pending', 1, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0),
(103, '2025-04-24', 'sdf', NULL, '2025-04-10 13:15:57', '2025-05-01 13:38:29', 'Pending', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0),
(104, '2025-05-02', 'dfgdf', NULL, '2025-04-12 15:10:19', '2025-07-06 05:46:29', 'Finalizing', 1, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 1),
(10001, '2025-04-26', 'gfhfgh', NULL, '2025-04-12 15:15:31', '2025-06-28 06:36:32', 'Voided', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 3, 0),
(10002, '2025-06-06', 'jhh', NULL, '2025-04-20 13:13:41', '2025-07-06 05:46:29', 'Finalizing', 1, '12:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 2, 1),
(10003, '2025-05-01', 'sdfsds', NULL, '2025-04-22 07:52:06', '2025-05-30 10:06:07', 'Voided', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0),
(10004, '2025-05-01', '......', NULL, '2025-04-22 07:56:38', '2025-05-29 12:12:41', 'Voided', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0),
(10017, '2025-05-30', 'asd', 'erererer', '2025-05-11 07:11:03', '2025-07-05 14:13:02', 'Pending', 0, '00:00:00', '', NULL, 101, 'General Treatment', 4, '2027-07-15', 4, '2025-07-15', 'branch admin', 'No User', 1, 0),
(10019, '2025-07-10', 'customer', NULL, '2025-05-29 15:18:17', '2025-07-06 15:27:30', 'Accepted', 0, '12:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0),
(10020, '2025-06-12', 'ggh', NULL, '2025-06-01 06:59:38', '2025-07-06 05:46:29', 'Finalizing', 0, '02:00:00', NULL, NULL, NULL, 'General Treatment', 3, NULL, NULL, NULL, 'No User', 'No User', 2, 1),
(10021, '2025-06-11', 'Albert Einstein', NULL, '2025-06-01 07:05:44', '2025-07-07 14:27:24', 'Accepted', 0, '02:00:00', NULL, NULL, NULL, 'Follow-up Treatment', 2, NULL, NULL, NULL, 'branch admin', 'No User', 1, 0),
(10022, '2025-06-03', 'Sigmund Freud', 'asdas', '2025-06-02 14:44:33', '2025-07-06 05:46:29', 'Finalizing', 0, '12:00:00', '', NULL, 102, 'Follow-up Treatment', 4, '2027-06-17', 3, '2025-06-17', 'No User', 'No User', 1, 1),
(10023, '2025-06-26', 'Name', 'Address', '2025-06-05 14:08:45', '2025-07-06 14:53:18', 'Completed', 0, '09:05:00', 'notess\r\n', NULL, 101, 'Follow-up Treatment', 4, '2027-06-18', 4, '2025-06-18', 'No User', 'No User', 1, 0),
(10024, '2025-07-24', 'tutu', 'fghfgh', '2025-07-03 14:47:52', '2025-07-09 13:53:56', 'Dispatched', 0, '02:00:00', '', NULL, 101, 'Follow-up Treatment', 4, '2027-07-16', 0, '2025-07-16', 'branch admin', 'No User', 1, 0),
(10027, '2025-07-23', 'ttiititit', 'sdfsdf', '2025-07-03 15:16:18', '2025-07-04 15:25:05', 'Completed', 0, '10:00:00', '', NULL, NULL, 'General Treatment', 1, NULL, NULL, NULL, '0', 'No User', 1, 0),
(10028, '2025-07-16', '123456', 'sdffsdf', '2025-07-06 06:18:53', '2025-07-07 14:35:47', 'Pending', 0, '11:00:00', '', NULL, NULL, 'General Treatment', 2, NULL, NULL, NULL, 'branch admin', 'branch admin', 1, 0),
(10039, '2025-07-30', 'dssf', 'sdfsdf', '2025-07-10 15:25:18', '2025-07-10 15:25:18', 'Dispatched', 0, '12:00:00', '', NULL, NULL, 'Follow-up Treatment', 2, NULL, NULL, NULL, 'No User', 'branch admin', 1, 0),
(10040, '2025-07-23', 'asd', 'sad', '2025-07-10 22:16:27', '2025-07-10 22:16:27', 'Dispatched', 0, '10:00:00', '', NULL, NULL, 'General Treatment', 2, NULL, NULL, NULL, 'No User', 'branch admin', 1, 0),
(10041, '2025-07-24', 'Nw name', 'address', '2025-07-10 22:22:37', '2025-07-10 22:22:37', 'Completed', 0, '10:00:00', '', NULL, NULL, 'Follow-up Treatment', 2, NULL, NULL, NULL, 'No User', 'branch admin', 1, 0);

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
(10019, 54, 'chem B | brand B', 5),
(10020, 54, 'chem B | brand B', 0),
(10020, 1004, 'fgh | fgh', 0),
(10021, 54, 'chem B | brand B', 0),
(10021, 1005, 'hk | hjk', 0),
(10022, 51, 'asd | asd', 3),
(10023, 52, 'asd | asd', 5),
(10027, 1001, 'gh | gh', 3),
(10028, 1013, 'tamod | gdfg', 0),
(10040, 54, 'chem B | brand B', 2),
(10041, 2, 'Abates | BASF', 2);

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
(10023, 13),
(10024, 2),
(10024, 9),
(10024, 10),
(10027, 2),
(10027, 3),
(10027, 10),
(10028, 2),
(10028, 10),
(10040, 3),
(10041, 3),
(10041, 7);

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
(10023, 15, 'alena datolayta'),
(10024, 10, 'fgh fgh'),
(10027, 2, 'asdf asdf'),
(10028, 1, 'rayan ibarra'),
(10040, 2, 'asdf asdf'),
(10041, 1, 'rayan ibarra');

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
-- Indexes for table `inventory_log`
--
ALTER TABLE `inventory_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `fk_chemical_id` (`chem_id`),
  ADD KEY `fk_trans_id` (`trans_id`),
  ADD KEY `fk_branch` (`branch`);

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
-- Indexes for table `reset_password`
--
ALTER TABLE `reset_password`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `reset_token_hash` (`reset_token_hash`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `chemicals`
--
ALTER TABLE `chemicals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1066;

--
-- AUTO_INCREMENT for table `equipments`
--
ALTER TABLE `equipments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `inventory_log`
--
ALTER TABLE `inventory_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `pest_problems`
--
ALTER TABLE `pest_problems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `reset_password`
--
ALTER TABLE `reset_password`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10042;

--
-- AUTO_INCREMENT for table `treatments`
--
ALTER TABLE `treatments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
-- Constraints for table `inventory_log`
--
ALTER TABLE `inventory_log`
  ADD CONSTRAINT `fk_branch` FOREIGN KEY (`branch`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `fk_chemical_id` FOREIGN KEY (`chem_id`) REFERENCES `chemicals` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_trans_id` FOREIGN KEY (`trans_id`) REFERENCES `transactions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

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
