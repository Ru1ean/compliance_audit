-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2025 at 12:32 PM
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
-- Database: `db_compliance_audit`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `log_id` int(11) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `action_description` text DEFAULT NULL,
  `entity_name` varchar(50) DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`log_id`, `action_type`, `action_description`, `entity_name`, `entity_id`, `timestamp`) VALUES
(1, 'add_organization', 'Added new organization: vb', 'organizations', NULL, '2025-03-30 09:11:30'),
(2, 'add_organization', 'Added new organization: hj', 'organizations', NULL, '2025-03-30 09:11:34'),
(3, 'submit_audit', 'Updated compliance status for 14 requirements', 'compliance_status', NULL, '2025-03-30 09:11:59'),
(4, 'submit_audit', 'Updated compliance status for 14 requirements', 'compliance_status', NULL, '2025-03-30 09:12:00'),
(5, 'submit_audit', 'Updated compliance status for 14 requirements', 'compliance_status', NULL, '2025-03-30 09:12:01'),
(6, 'submit_audit', 'Updated compliance status for 14 requirements', 'compliance_status', NULL, '2025-03-30 09:12:01'),
(7, 'submit_audit', 'Updated compliance status for 14 requirements', 'compliance_status', NULL, '2025-03-30 09:12:01'),
(8, 'submit_audit', 'Updated compliance status for 14 requirements', 'compliance_status', NULL, '2025-03-30 09:12:01'),
(9, 'submit_audit', 'Updated compliance status for 14 requirements', 'compliance_status', NULL, '2025-03-30 09:12:01'),
(10, 'add_organization', 'Added new organization: v', 'organizations', NULL, '2025-03-30 09:12:06'),
(11, 'delete_organization', 'Deleted organization: vb', 'organizations', 1, '2025-03-30 09:13:59'),
(12, 'delete_organization', 'Deleted organization: hj', 'organizations', 2, '2025-03-30 09:14:01'),
(13, 'submit_audit', 'Updated compliance status for 0 requirements', 'compliance_status', NULL, '2025-03-30 09:14:03'),
(14, 'submit_audit', 'Updated compliance status for 8 requirements', 'compliance_status', NULL, '2025-03-30 09:14:23'),
(15, 'submit_audit', 'Updated compliance status for 8 requirements', 'compliance_status', NULL, '2025-03-30 09:25:54'),
(16, 'delete_organization', 'Deleted organization: v', 'organizations', 3, '2025-03-30 11:38:58'),
(17, 'add_organization', 'Added new organization: Gwaps. Org.', 'organizations', NULL, '2025-03-30 11:39:48'),
(18, 'submit_audit', 'Updated compliance status for 8 requirements', 'compliance_status', NULL, '2025-03-30 11:40:00'),
(19, 'add_organization', 'Added new organization: Ahnjin Org.', 'organizations', NULL, '2025-03-30 11:42:41'),
(20, 'add_organization', 'Added new organization: Tets', 'organizations', NULL, '2025-03-30 11:43:55'),
(21, 'add_organization', 'Added new organization: Bubs', 'organizations', NULL, '2025-03-30 11:45:36'),
(22, 'submit_audit', 'Updated compliance status for 16 requirements', 'compliance_status', NULL, '2025-03-30 12:00:07'),
(23, 'delete_organization', 'Deleted organization: Ahnjin Org.', 'organizations', 5, '2025-03-30 12:00:39'),
(24, 'add_organization', 'Added new organization: Org6', 'organizations', NULL, '2025-03-30 17:52:42'),
(25, 'add_organization', 'Added new organization: 0rg7', 'organizations', NULL, '2025-03-30 17:52:46'),
(26, 'add_organization', 'Added new organization: org8', 'organizations', NULL, '2025-03-30 17:52:51'),
(27, 'test_action', 'This is a test log entry', 'test_entity', 123, '2025-03-30 19:50:58'),
(28, 'add_organization', 'Added new organization: tite dako', 'organizations', NULL, '2025-04-06 15:17:09'),
(29, 'submit_audit', 'Updated compliance status for 8 requirements', 'compliance_status', NULL, '2025-04-06 15:17:38'),
(30, 'add_organization', 'Added new organization: gwapo123', 'organizations', NULL, '2025-06-07 13:38:26'),
(31, 'submit_audit', 'Updated compliance status for 15 requirements', 'compliance_status', NULL, '2025-06-07 13:38:38'),
(32, 'add_organization', 'Added new organization: hj', 'organizations', NULL, '2025-06-07 19:01:19'),
(33, 'delete_organization', 'Deleted organization: hj', 'organizations', 13, '2025-06-07 19:01:29'),
(34, 'add_organization', 'Added new organization: dasd', 'organizations', NULL, '2025-06-07 19:17:05'),
(35, 'submit_audit', 'Updated compliance status for 15 requirements', 'compliance_status', NULL, '2025-06-07 19:20:07'),
(36, 'add_organization', 'Added new organization: asd', 'organizations', NULL, '2025-06-07 19:27:22'),
(37, 'submit_audit', 'Updated compliance status for 15 requirements', 'compliance_status', NULL, '2025-06-07 19:37:13'),
(38, 'submit_audit', 'Updated compliance status for 15 requirements', 'compliance_status', NULL, '2025-06-07 19:57:32'),
(39, 'delete_organization', 'Deleted organization: dfdsdf', 'organizations', 16, '2025-06-07 19:57:48'),
(40, 'delete_organization', 'Deleted organization: Bubs', 'organizations', 7, '2025-06-07 19:57:55'),
(41, 'delete_organization', 'Deleted organization: Org6', 'organizations', 8, '2025-06-07 19:57:57'),
(42, 'delete_organization', 'Deleted organization: tite dako', 'organizations', 11, '2025-06-07 19:58:01'),
(43, 'delete_organization', 'Deleted organization: org8', 'organizations', 10, '2025-06-07 19:58:04'),
(44, 'delete_organization', 'Deleted organization: dasd', 'organizations', 14, '2025-06-07 19:58:06'),
(45, 'delete_organization', 'Deleted organization: 0rg7', 'organizations', 9, '2025-06-07 19:58:08'),
(46, 'delete_organization', 'Deleted organization: vv', 'organizations', 17, '2025-06-07 19:58:11'),
(47, 'delete_organization', 'Deleted organization: asd', 'organizations', 15, '2025-06-07 19:58:18'),
(48, 'add', 'Added new requirement: SDF', 'requirement', 26, '2025-06-10 15:55:50'),
(49, 'add', 'Added new requirement: ASD', 'requirement', 27, '2025-06-10 16:02:14');

-- --------------------------------------------------------

--
-- Table structure for table `compliance_status`
--

CREATE TABLE `compliance_status` (
  `id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `req_id` int(11) NOT NULL,
  `status` enum('comply','not_comply') NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `compliance_status`
--

INSERT INTO `compliance_status` (`id`, `org_id`, `req_id`, `status`, `updated_at`) VALUES
(128, 56, 6, 'comply', '2025-06-11 05:38:33'),
(129, 56, 7, 'comply', '2025-06-11 05:38:33'),
(130, 56, 8, 'comply', '2025-06-11 05:38:33'),
(131, 57, 1, 'comply', '2025-08-05 10:33:52'),
(132, 57, 4, 'not_comply', '2025-08-05 10:33:52'),
(133, 57, 5, 'comply', '2025-08-05 10:33:52'),
(134, 57, 7, 'not_comply', '2025-08-05 10:33:52'),
(135, 58, 36, 'comply', '2025-08-06 08:37:23');

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `org_id` int(11) NOT NULL,
  `org_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`org_id`, `org_name`, `created_at`) VALUES
(56, 'BSIT', '2025-06-11 05:38:21'),
(57, 'registrar', '2025-08-05 10:33:45'),
(58, 'test3', '2025-08-06 08:37:20');

-- --------------------------------------------------------

--
-- Table structure for table `requirements`
--

CREATE TABLE `requirements` (
  `req_id` int(11) NOT NULL,
  `req_name` varchar(100) NOT NULL,
  `req_description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requirements`
--

INSERT INTO `requirements` (`req_id`, `req_name`, `req_description`, `created_at`) VALUES
(1, 'Data Privacy Compliance', 'Ensures the organization properly handles and protects personal data according to regulations like GDPR or CCPA.', '2025-03-30 09:01:31'),
(2, 'Secure Payment Processing', 'Verifies that payment systems adhere to PCI DSS standards and other security protocols for financial transactions.', '2025-03-30 09:01:31'),
(3, 'Employee Background Checks', 'Confirms that proper vetting procedures are in place for hiring and periodically reviewing staff credentials.', '2025-03-30 09:01:31'),
(4, 'Cybersecurity Measures', 'Evaluates implementation of firewalls, encryption, intrusion detection, and other security controls to protect systems.', '2025-03-30 09:01:31'),
(5, 'Workplace Safety Standards', 'Assesses adherence to health and safety regulations designed to prevent accidents and injuries in the workplace.', '2025-03-30 09:01:31'),
(6, 'Customer Data Protection', 'Reviews measures in place to safeguard customer information from unauthorized access or breaches.', '2025-03-30 09:01:31'),
(7, 'Ethical Sourcing Policy', 'Examines if the organization sources materials and services responsibly, avoiding exploitation and environmental harm.', '2025-03-30 09:01:31'),
(8, 'Accessibility Compliance', 'Determines if facilities, services, and digital platforms are accessible to persons with disabilities as per regulations.', '2025-03-30 09:01:31'),
(9, 'Incident Response Plan', 'Validates the presence of a documented and regularly tested incident response strategy to address security breaches.', '2025-06-07 18:28:19'),
(10, 'Data Retention Policy', 'Ensures data is stored and deleted in compliance with legal and organizational timelines.', '2025-06-07 18:41:28'),
(11, 'Vendor Risk Management', 'Assesses how third-party vendors are evaluated and monitored for compliance and security standards.', '2025-06-07 18:41:28'),
(12, 'Audit Trail Integrity', 'Checks that audit logs are maintained securely and are tamper-proof to ensure traceability of actions.', '2025-06-07 18:41:28'),
(13, 'User Access Controls', 'Verifies role-based access controls and permissions are enforced to restrict data to authorized users.', '2025-06-07 18:41:28'),
(14, 'System Patch Management', 'Evaluates how regularly systems are updated and patched against known vulnerabilities.', '2025-06-07 18:41:28'),
(15, 'Encryption Standards Compliance', 'Confirms that sensitive data is encrypted in transit and at rest according to best practices.', '2025-06-07 18:41:28'),
(16, 'Business Continuity Plan', 'Reviews the existence and effectiveness of business continuity and disaster recovery procedures.', '2025-06-07 18:41:28'),
(17, 'Whistleblower Protection', 'Assures there are policies in place that allow anonymous reporting and protection of whistleblowers.', '2025-06-07 18:41:28'),
(18, 'Training and Awareness Programs', 'Checks whether employees receive regular training on compliance, data protection, and security practices.', '2025-06-07 18:41:28'),
(25, 'TEST', 'TEST', '2025-06-10 15:47:28'),
(26, 'SDF', 'SDF', '2025-06-10 15:55:50'),
(27, 'ASD', 'SDF', '2025-06-10 16:02:14'),
(29, 'sadfasdc', 'sdfasdffds', '2025-06-10 16:37:52'),
(30, 'sdsdfsdasd', 'sdasfd', '2025-06-10 16:37:59'),
(31, '1', '1', '2025-06-10 16:38:02'),
(32, 'fasdf', 'sdfasdf', '2025-06-10 23:07:53'),
(33, '5 Full time faculty members', 'Must have 5 fulltime faculty members', '2025-06-11 03:47:53'),
(34, 'Safety measures', 'for safety measures', '2025-06-11 04:56:27'),
(35, 'kjnj', 'njkj', '2025-08-05 10:36:40'),
(36, 'test3', 'desc3', '2025-08-06 08:37:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','staff') NOT NULL,
  `profile` varchar(255) NOT NULL,
  `remember_token` varchar(64) DEFAULT NULL,
  `token_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `username`, `password`, `email`, `role`, `profile`, `remember_token`, `token_expires`) VALUES
(1, 'vj2', '$2y$10$ZZt.iv8OX30WfAqCEm2MWedULeAYiexOkBfXgg44iud6Tg6U7X2vi', 'vjjavellan412@gmail.com', 'staff', '', NULL, NULL),
(2, 'Admin', '$2y$10$R87iLEXbzfGZrt7r.oFVweI.k6UOOCn9rUHf75pV7AsiM2j8XNt0K', 'admin@gmail.com', 'admin', 'Admin_0f47ef4214fa3fd70a91c0bf44ca3593.jpg', NULL, NULL),
(3, 'user1', '$2y$10$gTp6cSacgvQ.uWy.sdz/meyoDOYEvVkof6hCS.W7NHJ6ebXZY2d/W', 'user1@gmail.com', 'staff', 'user1_6d5e4535f86414e01d304762cc4e6633.jpg', NULL, NULL),
(4, 'admin2', '$2y$10$xgAyM/SQFEt90wBSUSMKiewT6Ruyf04C/dVCemXzbWt6jA2gVDNfu', 'admin2@gmial.com', 'staff', 'admin2_b128e71fb02b1105f03b1fe2c9db0beb.jpg', NULL, NULL),
(5, 'Ruleam', '$2y$10$PEAX7nibSZkJDVAgipFiZOHrk8n2OiBd8HDcRAf7dfdJsEBw9SdU2', 'rulean@gmail.com', 'staff', '', NULL, NULL),
(6, 'user2', '$2y$10$TU8jv21SMrXbtlo4BHG9L.OWvWjTqpz3e2RTxlymGbPHoosQw0Bzy', 'user2@gmail.com', 'staff', '', NULL, NULL),
(7, 'user3', '$2y$10$hAgS6lqewnqoa8pNXL17Ku5Sm/j.iy77ED3lZQ0bqcl/UHp56zfJe', 'user3@gmail.com', 'staff', '', NULL, NULL),
(8, 'TEST1', '$2y$10$U2rGbMI7inoiaWoMt5oWle5FWss3cbFuzMb.XT4w/CV.kt.h2q1IC', 'ADSFYGUVG@gmail.com', 'staff', 'TEST1_c264d129d9b310e5fc2a7b6ad4179bb2.jpg', '3c2bfe36d02b6d5fbec86b1027a929777138bc8d19a58dba27dfe65e65758b79', '2025-07-11 02:23:02'),
(9, 'adminnew', '$2y$10$LuQEigttKftdvAoC9nwNruH3Ahtyu1YyngRAK1GD2UEVF2/48B91W', 'adminnew@gmail.com', 'admin', '', NULL, NULL),
(10, 'ADMINS', '$2y$10$pbbWOrNKIgPQrMdNte6lPOTlXq7jIinnkgzuN/YSYbgcsPsAZqjDa', 'admins@gmail.com', 'admin', '', NULL, NULL),
(11, 'marc', '$2y$10$32nb/BjM1wkXN3xfhOwLH.loaPxGJi39WAFEPOjbnvhPAE7A0Y43C', 'marc@email.com', 'admin', '', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `timestamp` (`timestamp`);

--
-- Indexes for table `compliance_status`
--
ALTER TABLE `compliance_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_org_req` (`org_id`,`req_id`),
  ADD KEY `req_id` (`req_id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`org_id`),
  ADD UNIQUE KEY `org_name` (`org_name`);

--
-- Indexes for table `requirements`
--
ALTER TABLE `requirements`
  ADD PRIMARY KEY (`req_id`),
  ADD UNIQUE KEY `req_name` (`req_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `compliance_status`
--
ALTER TABLE `compliance_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `org_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `requirements`
--
ALTER TABLE `requirements`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `compliance_status`
--
ALTER TABLE `compliance_status`
  ADD CONSTRAINT `compliance_status_ibfk_1` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`org_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `compliance_status_ibfk_2` FOREIGN KEY (`req_id`) REFERENCES `requirements` (`req_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
