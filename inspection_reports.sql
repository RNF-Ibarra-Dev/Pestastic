-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2025 at 08:00 AM
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
-- Table structure for table `inspection_reports`
--

CREATE TABLE `inspection_reports` (
  `id` int(11) NOT NULL,
  `customer` varchar(128) NOT NULL,
  `property_type` enum('residential','commercial') NOT NULL,
  `total_floor_area` decimal(10,2) NOT NULL,
  `floor_area_unit` varchar(10) NOT NULL,
  `total_floor_num` int(11) NOT NULL,
  `total_room` int(11) NOT NULL,
  `property_location` varchar(128) NOT NULL,
  `reported_pest_problem_location` varchar(128) NOT NULL,
  `exposed_soil_outside_property` enum('yes','no','no_termite') NOT NULL,
  `existing_pest_provider` tinyint(1) NOT NULL,
  `last_treatment` varchar(128) DEFAULT NULL,
  `branch` int(11) NOT NULL,
  `last_treatment_date` date DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` varchar(128) NOT NULL DEFAULT 'No user recorded',
  `updated_by` varchar(128) NOT NULL DEFAULT 'No user recorded'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inspection_reports`
--

INSERT INTO `inspection_reports` (`id`, `customer`, `property_type`, `total_floor_area`, `floor_area_unit`, `total_floor_num`, `total_room`, `property_location`, `reported_pest_problem_location`, `exposed_soil_outside_property`, `existing_pest_provider`, `last_treatment`, `branch`, `last_treatment_date`, `notes`, `added_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'customer A', 'residential', 360.00, 'sqm', 2, 2, 'loc a1', '0', 'yes', 0, 'treatment A', 1, '2025-10-29', 'property is this, that.', '2025-10-15 05:48:57', '2025-10-15 13:37:17', '', ''),
(2, 'Company RTS', 'commercial', 400.50, 'sqm', 5, 12, 'Makati, BGC', 'Room 210B, 3rd Flooe', 'no_termite', 1, NULL, 1, NULL, '-', '2025-10-15 13:30:15', '2025-10-15 13:37:22', '', ''),
(3, 'Company ABCS', 'commercial', 500.23, 'sqm', 4, 15, 'Tagapo, Sta Rosa', 'Inventory room, Ground floor', 'no', 0, 'Termite Control', 1, '2025-09-18', 'None', '2025-10-15 13:34:48', '2025-10-15 13:37:27', '', ''),
(4, 'Eatery SD', 'commercial', 360.30, 'sqm', 2, 5, 'Tagapo, Sta Rosa Laguna', 'Inventory Room, Bathroom (first floor)', 'no', 0, NULL, 1, NULL, 'n/a', '2025-10-15 14:27:29', '2025-10-19 14:29:28', '', 'Pestastic OwnerEmployee ID: 123'),
(5, 'Kuya J Restaurant', 'commercial', 400.30, 'sqm', 1, 3, 'SM Sta Rosa, 2nd Floor, Extension', 'Inventory room & Kitchen area', 'no', 1, NULL, 1, NULL, 'Available at dawn only', '2025-10-15 14:44:21', '2025-10-15 14:44:21', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inspection_reports`
--
ALTER TABLE `inspection_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch` (`branch`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inspection_reports`
--
ALTER TABLE `inspection_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inspection_reports`
--
ALTER TABLE `inspection_reports`
  ADD CONSTRAINT `inspection_reports_ibfk_1` FOREIGN KEY (`branch`) REFERENCES `branches` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
