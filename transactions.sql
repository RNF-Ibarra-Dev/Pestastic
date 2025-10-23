-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2025 at 08:06 AM
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
  `complete_request` tinyint(1) NOT NULL DEFAULT 0,
  `inspection_report` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `treatment_date`, `customer_name`, `customer_address`, `created_at`, `updated_at`, `transaction_status`, `void_request`, `transaction_time`, `notes`, `t_finished`, `package_id`, `treatment_type`, `treatment`, `pack_exp`, `session_no`, `pack_start`, `updated_by`, `created_by`, `branch`, `complete_request`, `inspection_report`) VALUES
(76, '2025-04-04', 'gffggh', NULL, '2025-03-31 13:07:43', '2025-04-04 13:56:20', 'Completed', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0, 0),
(80, '2025-04-25', 'dfgfd', NULL, '2025-04-01 14:23:01', '2025-05-30 10:06:07', 'Completed', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0, 0),
(82, NULL, 'ggddf', NULL, '2025-04-05 13:46:48', '2025-05-30 10:06:07', 'Pending', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0, 0),
(83, '2025-05-01', 'sssss', NULL, '2025-04-05 13:47:19', '2025-04-05 13:47:19', 'Voided', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0, 0),
(84, '2025-05-20', 'asd', NULL, '2025-04-05 14:14:53', '2025-05-30 10:06:07', 'Completed', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0, 0),
(85, '2025-04-19', 'kkjljkl', NULL, '2025-04-06 05:19:12', '2025-07-06 05:46:29', 'Finalizing', 0, '11:14:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 1, 0),
(96, '2025-04-19', 'dfsdf', NULL, '2025-04-10 06:51:37', '2025-05-28 14:22:40', 'Voided', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0, 0),
(97, '2025-04-25', 'ghj', NULL, '2025-04-10 06:53:43', '2025-05-30 10:06:07', 'Voided', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0, 0),
(98, '2025-04-25', 'jkl', NULL, '2025-04-10 06:54:24', '2025-07-06 05:50:29', 'Cancelled', 0, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0, 0),
(99, '2025-04-25', 'ASDASASD', NULL, '2025-04-10 06:57:10', '2025-09-03 13:03:34', 'Pending', 1, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0, 0),
(100, '2025-04-25', 'ghbgh', NULL, '2025-04-10 07:07:38', '2025-06-28 06:36:27', 'Voided', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 2, 0, 0),
(101, NULL, 'hjkjk', NULL, '2025-04-10 07:08:48', '2025-05-30 10:06:07', 'Pending', 1, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0, 0),
(102, '2025-04-18', 'lkkl', NULL, '2025-04-10 07:12:11', '2025-05-30 10:06:07', 'Pending', 1, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0, 0),
(103, '2025-08-26', 'sdf', 'addresssss', '2025-04-10 13:15:57', '2025-09-29 06:57:14', 'Dispatched', 0, '12:05:00', '', NULL, NULL, 'General Treatment', 3, NULL, NULL, NULL, 'asdf asdf', 'No User', 1, 0, 0),
(104, '2025-05-02', 'dfgdf', '222', '2025-04-12 15:10:19', '2025-09-02 12:53:49', 'Voided', 0, '00:00:00', '', NULL, NULL, 'General Treatment', 2, NULL, NULL, NULL, 'Manager One', 'No User', 1, 1, 0),
(10001, '2025-04-26', 'gfhfgh', NULL, '2025-04-12 15:15:31', '2025-06-28 06:36:32', 'Voided', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 3, 0, 0),
(10002, '2025-06-06', 'jhh', NULL, '2025-04-20 13:13:41', '2025-09-02 05:35:43', 'Finalizing', 0, '12:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 2, 1, 0),
(10003, '2025-05-01', 'sdfsds', NULL, '2025-04-22 07:52:06', '2025-05-30 10:06:07', 'Voided', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0, 0),
(10004, '2025-05-01', '......', NULL, '2025-04-22 07:56:38', '2025-05-29 12:12:41', 'Voided', 0, '00:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0, 0),
(10017, '2025-05-30', 'asd', 'erererer', '2025-05-11 07:11:03', '2025-07-05 14:13:02', 'Pending', 0, '00:00:00', '', NULL, 101, 'General Treatment', 4, '2027-07-15', 4, '2025-07-15', 'branch admin', 'No User', 1, 0, 0),
(10019, '2025-07-10', 'customer', NULL, '2025-05-29 15:18:17', '2025-07-06 15:27:30', 'Accepted', 0, '12:00:00', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'No User', 'No User', 1, 0, 0),
(10020, '2025-06-12', 'ggh', NULL, '2025-06-01 06:59:38', '2025-07-06 05:46:29', 'Finalizing', 0, '02:00:00', NULL, NULL, NULL, 'General Treatment', 3, NULL, NULL, NULL, 'No User', 'No User', 2, 1, 0),
(10021, '2025-06-11', 'Albert Einstein', 'jkjlkljjkl', '2025-06-01 07:05:44', '2025-07-13 07:13:40', 'Completed', 0, '02:00:00', '', NULL, NULL, 'Follow-up Treatment', 2, NULL, NULL, NULL, 'branch admin', 'No User', 1, 0, 0),
(10022, '2025-06-03', 'Sigmund Freud', 'asdas', '2025-06-02 14:44:33', '2025-07-06 05:46:29', 'Finalizing', 0, '12:00:00', '', NULL, 102, 'Follow-up Treatment', 4, '2027-06-17', 3, '2025-06-17', 'No User', 'No User', 1, 1, 0),
(10023, '2025-06-26', 'Name', 'Address', '2025-06-05 14:08:45', '2025-07-06 14:53:18', 'Completed', 0, '09:05:00', 'notess\r\n', NULL, 101, 'Follow-up Treatment', 4, '2027-06-18', 4, '2025-06-18', 'No User', 'No User', 1, 0, 0),
(10024, '2025-07-24', 'tutu', 'fghfgh', '2025-07-03 14:47:52', '2025-09-04 14:15:06', 'Completed', 0, '02:00:00', '', NULL, 101, 'Follow-up Treatment', 4, '2027-07-16', 0, '2025-07-16', 'Manager One', 'No User', 1, 0, 0),
(10027, '2025-07-23', 'ttiititit', 'sdfsdf', '2025-07-03 15:16:18', '2025-07-04 15:25:05', 'Completed', 0, '10:00:00', '', NULL, NULL, 'General Treatment', 1, NULL, NULL, NULL, '0', 'No User', 1, 0, 0),
(10028, '2025-07-16', '123456', 'sdffsdf', '2025-07-06 06:18:53', '2025-08-10 15:31:37', 'Completed', 0, '11:00:00', '', NULL, NULL, 'General Treatment', 2, NULL, NULL, NULL, 'branch admin', 'branch admin', 1, 0, 0),
(10039, '2025-07-30', 'dssf', 'sdfsdf', '2025-07-10 15:25:18', '2025-07-12 15:17:53', 'Finalizing', 0, '12:00:00', '20mL used for kitchen area ', NULL, NULL, 'Follow-up Treatment', 2, NULL, NULL, NULL, 'branch admin', 'branch admin', 1, 0, 0),
(10040, '2025-07-23', 'asd', 'sad', '2025-07-10 22:16:27', '2025-07-12 13:25:21', 'Completed', 0, '10:00:00', 'asdas', NULL, NULL, 'General Treatment', 2, NULL, NULL, NULL, 'branch admin', 'branch admin', 1, 0, 0),
(10041, '2025-07-24', 'Nw name', 'address', '2025-07-10 22:22:37', '2025-07-10 22:22:37', 'Completed', 0, '10:00:00', '', NULL, NULL, 'Follow-up Treatment', 2, NULL, NULL, NULL, 'No User', 'branch admin', 1, 0, 0),
(10042, '2025-07-30', 'dfgd', 'dddd', '2025-07-12 05:54:59', '2025-07-13 06:17:38', 'Finalizing', 0, '10:00:00', '', NULL, NULL, 'General Treatment', 2, NULL, NULL, NULL, 'branch admin', 'branch admin', 1, 0, 0),
(10043, '2025-07-30', 'dfgd', 'dddd', '2025-07-12 06:01:34', '2025-08-26 14:56:07', 'Finalizing', 0, '10:00:00', '', NULL, NULL, 'General Treatment', 2, NULL, NULL, NULL, 'asdf asdf', 'branch admin', 1, 0, 0),
(10044, '2025-07-31', 'Test', 'test', '2025-07-20 07:18:56', '2025-08-09 05:56:12', 'Completed', 0, '12:00:00', '', NULL, NULL, 'General Treatment', 2, NULL, NULL, NULL, 'No User', 'branch admin', 1, 0, 0),
(10045, '2025-09-03', 'Customer name', 'address', '2025-08-21 05:54:17', '2025-09-03 12:58:29', 'Dispatched', 0, '16:00:00', '', NULL, 101, 'Follow-up Treatment', 4, '2027-08-22', 1, '2025-08-22', 'asdf asdf', 'super admin', 1, 0, 0),
(10046, '2025-09-30', 'Juan Tamad', 'Pacita 2 San Pedro, Laguna', '2025-08-21 06:07:27', '2025-10-22 09:20:29', 'Dispatched', 0, '12:00:00', 'testing purposes', NULL, 102, 'General Treatment', 4, '2027-08-23', 1, '2025-08-23', 'Branch Admin', 'super admin', 1, 0, 3),
(10047, '2025-09-03', 'ABC Company', 'Makati, BGC', '2025-08-26 05:33:55', '2025-09-08 15:19:48', 'Dispatched', 0, '14:00:00', '', NULL, 101, 'General Treatment', 4, '2027-08-07', 2, '2025-08-07', 'Manager One', 'asdf asdf', 1, 0, 0),
(10048, '2025-08-29', 'Minute Burger', 'Plaza Pacita Complex, San Pedro Laguna', '2025-08-26 15:08:57', '2025-08-26 15:08:57', 'Pending', 0, '06:00:00', 'Landmark: In front of tricycle terminal', NULL, NULL, 'General Treatment', 4, '1970-01-01', NULL, '1970-01-01', 'asdf asdf', 'asdf asdf', 1, 0, 0),
(10049, '2025-09-20', 'Discaya Resident', 'Sa tabi lang', '2025-09-16 07:07:58', '2025-09-17 06:39:54', 'Dispatched', 0, '13:00:00', '', NULL, 101, 'Follow-up Treatment', 4, '2027-08-05', 2, '2025-08-05', 'Pestastic Owner', 'Pestastic Owner', 1, 0, 0),
(10050, '2025-09-20', 'aaaaa', 'aaaa', '2025-09-17 06:48:30', '2025-09-20 07:25:29', 'Completed', 0, '12:00:00', '', NULL, NULL, 'General Treatment', 3, NULL, NULL, NULL, 'Pestastic Owner', 'Pestastic Owner', 1, 0, 0),
(10051, '2025-09-22', 'sssss', 'sssss', '2025-09-21 06:43:04', '2025-09-21 07:55:37', 'Completed', 0, '13:00:00', '', NULL, NULL, 'General Treatment', 1, NULL, NULL, NULL, 'Pestastic Owner', 'Pestastic Owner', 1, 0, 0),
(10052, '2025-10-23', 'customer A', 'loc a1', '2025-10-22 13:10:47', '2025-10-22 13:10:47', 'Pending', 0, '13:00:00', '--', NULL, NULL, 'Follow-up Treatment', 2, NULL, NULL, NULL, 'Pestastic Owner', 'Pestastic Owner', 1, 0, 1),
(10054, '2025-10-31', 'Company RTS', 'Makati, BGC', '2025-10-22 15:39:20', '2025-10-22 15:39:20', 'Pending', 0, '14:00:00', '--', NULL, NULL, 'Follow-up Treatment', 2, NULL, NULL, NULL, 'Pestastic Owner', 'Pestastic Owner', 1, 0, 2),
(10055, '2025-10-31', 'Eatery SD', 'Tagapo, Sta Rosa Laguna', '2025-10-22 16:12:12', '2025-10-22 16:12:12', 'Pending', 0, '12:00:00', '-', NULL, 101, 'General Treatment', 4, '0000-00-00', 1, '2025-10-31', 'Branch Admin', 'Branch Admin', 1, 0, 4),
(10056, '2025-10-30', 'customer A', 'loc a1', '2025-10-22 16:29:22', '2025-10-22 16:29:22', 'Pending', 0, '12:00:00', '', NULL, 101, 'Follow-up Treatment', 4, '2027-10-30', 2, '2025-10-30', 'Branch Admin', 'Branch Admin', 1, 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_id` (`package_id`),
  ADD KEY `fk_t_id` (`treatment`),
  ADD KEY `fk_transactions_branch` (`branch`),
  ADD KEY `fk_inspection_report` (`inspection_report`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10057;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_inspection_report` FOREIGN KEY (`inspection_report`) REFERENCES `inspection_reports` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_t_id` FOREIGN KEY (`treatment`) REFERENCES `treatments` (`id`),
  ADD CONSTRAINT `fk_transactions_branch` FOREIGN KEY (`branch`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
