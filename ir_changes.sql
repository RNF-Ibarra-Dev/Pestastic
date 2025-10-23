-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2025 at 08:03 AM
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
-- Table structure for table `inspection_problems`
--

DROP TABLE IF EXISTS `inspection_problems`;
CREATE TABLE `inspection_problems` (
  `inspection_id` int(11) NOT NULL,
  `pest_problem` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inspection_reports`
--

DROP TABLE IF EXISTS `inspection_reports`;
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
-- Indexes for dumped tables
--

--
-- Indexes for table `inspection_problems`
--
ALTER TABLE `inspection_problems`
  ADD PRIMARY KEY (`inspection_id`,`pest_problem`),
  ADD KEY `pest_problem` (`pest_problem`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inspection_problems`
--
ALTER TABLE `inspection_problems`
  ADD CONSTRAINT `inspection_problems_ibfk_1` FOREIGN KEY (`inspection_id`) REFERENCES `inspection_reports` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inspection_problems_ibfk_2` FOREIGN KEY (`pest_problem`) REFERENCES `pest_problems` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inspection_reports`
--
ALTER TABLE `inspection_reports`
  ADD CONSTRAINT `inspection_reports_ibfk_1` FOREIGN KEY (`branch`) REFERENCES `branches` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
