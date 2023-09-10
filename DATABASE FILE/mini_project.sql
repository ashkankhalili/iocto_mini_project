-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 10, 2023 at 01:48 PM
-- Server version: 8.0.34
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mini_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pr_name` varchar(255) NOT NULL,
  `pr_summary` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `pr_name`, `pr_summary`) VALUES
(36, 'Project A', 'Description of Project A'),
(39, 'Project B', 'Description of Project B');

-- --------------------------------------------------------

--
-- Table structure for table `project_users`
--

DROP TABLE IF EXISTS `project_users`;
CREATE TABLE IF NOT EXISTS `project_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `project_id` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_id` (`project_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=187 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `project_users`
--

INSERT INTO `project_users` (`id`, `project_id`, `user_id`) VALUES
(184, 36, 1),
(185, 36, 43),
(186, 39, 1);

-- --------------------------------------------------------

--
-- Table structure for table `test_cases`
--

DROP TABLE IF EXISTS `test_cases`;
CREATE TABLE IF NOT EXISTS `test_cases` (
  `id` int NOT NULL AUTO_INCREMENT,
  `project_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `priority` enum('High','Normal','Low') NOT NULL,
  `description` text,
  `steps` text,
  `status` enum('Untested','Passed','Failed','On Process') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_test_cases_project_id` (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `test_cases`
--

INSERT INTO `test_cases` (`id`, `project_id`, `name`, `priority`, `description`, `steps`, `status`, `created_at`) VALUES
(16, 36, 'Test 1 ', 'High', 'Description of the <TEST1> test.', 'Steps of the case : \r\nStep1\r\nStep 2', 'Passed', '2023-09-10 13:45:33'),
(17, 39, 'Test 2', 'High', 'Description of the <TEST2> test.', 'Steps of the case : \r\nStep1\r\nStep 2', 'Failed', '2023-09-10 13:46:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_role` varchar(50) NOT NULL DEFAULT 'user',
  `username` varchar(255) NOT NULL,
  `email` varchar(60) DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `reg_date` timestamp NULL DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_role`, `username`, `email`, `password`, `reg_date`, `first_name`, `last_name`) VALUES
(1, 'admin', 'admin', 'admin@domain.com', '$2y$10$41EdaFLim0V8/CYyycCeieM.5b9QGa2YYobqKkvstWkK8k6nDcm2G', '2023-09-04 03:31:59', 'Ashkan', 'Khalili'),
(43, 'user', 'user1', 'user1@domain.com', '$2y$10$xVC.RuHcliBQ8qnW8Ze8QeoJJPS6ijIhTfMG.XnC5Tv/oFhI3KPvG', '2023-09-10 10:10:08', 'Mark', 'Anthony');

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

DROP TABLE IF EXISTS `user_profile`;
CREATE TABLE IF NOT EXISTS `user_profile` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`id`, `user_id`, `first_name`, `last_name`, `contact_number`, `date_of_birth`) VALUES
(1, 1, 'Ashkan', 'Khalili', '+989128938739', '1982-03-28'),
(12, 43, 'Mark', 'Anthony', '+15556401110', '1998-07-23');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `project_users`
--
ALTER TABLE `project_users`
  ADD CONSTRAINT `project_users_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`),
  ADD CONSTRAINT `project_users_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `test_cases`
--
ALTER TABLE `test_cases`
  ADD CONSTRAINT `fk_test_cases_project_id` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `test_cases_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);

--
-- Constraints for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD CONSTRAINT `user_profile_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
