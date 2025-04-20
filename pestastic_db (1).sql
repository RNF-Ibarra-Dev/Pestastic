-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2025 at 05:40 PM
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
  `baBirthdate` date NOT NULL DEFAULT '1930-01-01'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branchadmin`
--

INSERT INTO `branchadmin` (`baID`, `baFName`, `baLName`, `baUsn`, `baEmail`, `baPwd`, `baEmpId`, `baAddress`, `baContact`, `baBirthdate`) VALUES
(1, 'branch', 'admin', 'bAdmin', 'bAdmin@email.com', 'bAdmin', '012345678', '-', '123', '1930-01-14'),
(2, 'os', 'os', 'os', 'os@email.com', '$2y$10$qikFsWEjotZYeneR0x5.C.fTlLe/qQEkYLPlit8koQaQYFNh4cb.6', '0123', '-', '123', '1930-01-01'),
(3, 'wers', 'wers', 'wers', 'wer@gmail.com', '$2y$10$EeO/yYAJ/2NLT0QJ0yNzke9HVVnXXmiNHEuB1lWMOk/lryJpcoAg2', '123123', '-', '123123000', '1930-01-01'),
(5, 'aya', 'ibarra', 'aya123', 'aya@gmail.com', '$2y$10$fuswGyoFW1DOV.Hb3Y04W.L4BQvwHfxiGjz2wur3Q7OFkKiynjlf2', '002', '--', '123123123', '2020-06-10');

-- --------------------------------------------------------

--
-- Table structure for table `chemicals`
--

CREATE TABLE `chemicals` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `brand` varchar(128) NOT NULL,
  `chemLevel` int(11) NOT NULL,
  `expiryDate` date DEFAULT '2025-01-01'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chemicals`
--

INSERT INTO `chemicals` (`id`, `name`, `brand`, `chemLevel`, `expiryDate`) VALUES
(1, 'Deltacide', 'ENVU', 5, '2025-03-06'),
(2, 'Abates', 'BASF', 3, '2032-03-11'),
(3, 'chemical', 'ABXV', 1, '2028-01-19'),
(19, 'qwe', 'qwe', 0, '2025-04-03'),
(44, 'asd', 'asd', 0, '2025-01-01'),
(45, 'g', 'g', 0, '2025-01-01'),
(46, 'n', 'n', 0, '2025-04-03'),
(47, 'sad', 'asd', 500, '2025-01-01'),
(48, 'asd', 'asd', 0, '2025-01-01'),
(49, 'asd', 'asd', 0, '2025-01-01'),
(50, 'Deltacides', 'CHEM', 3, '2025-01-01'),
(51, 'asd', 'asd', 33, '2025-01-29'),
(52, 'asd', 'asd', 33, '2025-01-29');

-- --------------------------------------------------------

--
-- Table structure for table `equipments`
--

CREATE TABLE `equipments` (
  `id` int(11) NOT NULL,
  `equipment` varchar(255) NOT NULL,
  `availability` enum('Available','Unavailable','In Repair') NOT NULL DEFAULT 'Unavailable'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipments`
--

INSERT INTO `equipments` (`id`, `equipment`, `availability`) VALUES
(1, 'Sprayer', 'Unavailable'),
(2, 'Duster', 'Unavailable'),
(3, 'Foamer', 'Unavailable'),
(4, 'Fogger', 'Unavailable'),
(5, 'Granule Spreader', 'Unavailable'),
(6, 'UV Flashlight', 'Unavailable'),
(7, 'Inspection Mirror', 'Unavailable'),
(8, 'Moisture Meter', 'Unavailable'),
(9, 'Termite Detector', 'Unavailable');

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
  `saBirthdate` date NOT NULL DEFAULT '1930-01-01'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `superadmin`
--

INSERT INTO `superadmin` (`saID`, `saUsn`, `saName`, `saEmail`, `saPwd`, `saEmpId`, `saBirthdate`) VALUES
(1, 'sAdmin', 'superAdmin', 'sAdmin@email.com', 'sAdmin', '123456789', '1930-01-01');

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
  `techBirthdate` date NOT NULL DEFAULT '1930-01-01'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `technician`
--

INSERT INTO `technician` (`technicianId`, `firstName`, `lastName`, `username`, `techEmail`, `techPwd`, `techContact`, `techAddress`, `techEmpId`, `techBirthdate`) VALUES
(1, 'rayan', 'ibarra', 'qweee3231', 'pedro@email.com', '$2y$10$cti18IMlEMqlgJi2X2Dpfut49RIGvIartiYitMKLCqaMFbx/u97aq', '0917 123 4567', 'Phase 9, Phase 7 B3, Lot 14, Bagong Silang, Caloocan City, Metro Manila', '456', '1930-02-05'),
(2, 'asdf', 'asdf', 'asdf', 'asd@a.com', '$2y$10$GJR3SETW1tj4bcPRLX.VseeH//i/ZFwRWjDtlZ8G/WjfFNHPS.cbS', '639610970714', '362 Moret St, Sampaloc, Manila, 1008 Metro Manila, Philippines', '654', '1930-01-29'),
(4, 'rayan', 'ibarra', 'ribarra', 'rnnoleal@gmail.com', '041902', '6391 2321 123', '-', '065', '1930-01-01'),
(10, 'fgh', 'fgh', 'fgh', 'fghfgh@email.com', '$2y$10$/upNMNYpGayHj5V51qpSuuyN9kTYFGhUT9pyt9jB8fcKPphv4Crv.', '456564546', '--', '645', '1930-01-01'),
(15, 'alena', 'datolayta', 'alena', 'alenadatolayta10@gmail.com', '$2y$10$0nDgpiyIh1B9un8kCOIQnupvVI6fgaEY3CVE/LDxeIbQPLEGTLSnq', '32132131', '--', '001', '2002-07-10');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `treatment_date` date NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `treatment` enum('Soil Injection','Termite Powder Application','Wooden Structures Treatment','Termite Control','Crawling Insects Control','Follow-up Crawling Insects Control') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `transaction_status` enum('Pending','Accepted','Voided','Completed') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_chemicals`
--

CREATE TABLE `transaction_chemicals` (
  `trans_id` int(11) NOT NULL,
  `chem_id` int(11) NOT NULL,
  `chem_brand` varchar(255) NOT NULL,
  `amt_used` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `id` int(11) NOT NULL,
  `trans_id` int(11) DEFAULT NULL,
  `problem_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branchadmin`
--
ALTER TABLE `branchadmin`
  ADD PRIMARY KEY (`baID`);

--
-- Indexes for table `chemicals`
--
ALTER TABLE `chemicals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `equipments`
--
ALTER TABLE `equipments`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`saID`);

--
-- Indexes for table `technician`
--
ALTER TABLE `technician`
  ADD PRIMARY KEY (`technicianId`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_problems_ibfk_1` (`trans_id`),
  ADD KEY `transaction_problems_ibfk_2` (`problem_id`);

--
-- Indexes for table `transaction_technicians`
--
ALTER TABLE `transaction_technicians`
  ADD PRIMARY KEY (`trans_id`,`tech_id`),
  ADD KEY `transaction_technicians_ibfk_2` (`tech_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branchadmin`
--
ALTER TABLE `branchadmin`
  MODIFY `baID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `chemicals`
--
ALTER TABLE `chemicals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `equipments`
--
ALTER TABLE `equipments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  MODIFY `technicianId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `transaction_problems`
--
ALTER TABLE `transaction_problems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- Constraints for dumped tables
--

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
