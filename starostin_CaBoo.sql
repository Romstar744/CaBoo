-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 10, 2025 at 09:09 PM
-- Server version: 10.11.10-MariaDB-ubu2204
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `starostin_CaBoo`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `registration_date` timestamp NULL DEFAULT current_timestamp(),
  `email_verification_token` varchar(255) DEFAULT NULL,
  `is_email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `role` varchar(50) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_description` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `company_logo` varchar(255) DEFAULT NULL,
  `about` text DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `desired_salary` int(11) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `social_links` text DEFAULT NULL,
  `experience` text DEFAULT NULL,
  `education` text DEFAULT NULL,
  `resume` varchar(255) DEFAULT NULL,
  `gender` enum('male','female','other','not_specified') DEFAULT NULL,
  `industry` varchar(255) DEFAULT NULL,
  `educationInstitution` varchar(255) DEFAULT NULL,
  `educationDegree` varchar(255) DEFAULT NULL,
  `educationStart` varchar(7) DEFAULT NULL,
  `educationEnd` varchar(7) DEFAULT NULL,
  `educationDescription` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `registration_date`, `email_verification_token`, `is_email_verified`, `role`, `first_name`, `last_name`, `city`, `company_name`, `company_description`, `avatar`, `company_logo`, `about`, `skills`, `desired_salary`, `birthdate`, `social_links`, `experience`, `education`, `resume`, `gender`, `industry`, `educationInstitution`, `educationDegree`, `educationStart`, `educationEnd`, `educationDescription`) VALUES
(110, '111', '$2y$10$EppCkItIA8hGHrAPYKkEjubFqWeZnIda/kd8eyc3XNgdQ7Z12olLy', 'romstar742@gmail.com', '2025-03-28 12:28:51', NULL, 1, 'seeker', 'Роман', 'Старостин', 'Moscow', NULL, NULL, '../img/avatars/67e69602db573_1.jpg', NULL, 'dthd', 'знаю C#', 1000000, '2024-06-03', 'tg: LIL_SPAL', NULL, NULL, '../resumes/67e69602da5a1_ЗаданиеПП.02_ПодготовкаДЭ_2025.pdf', 'male', NULL, 'ПКГХ', 'спо', '2000-01', '2004-01', 'укук'),
(116, 'dan24_00', '$2y$10$0uSSWMet369P7pfWg8U2Eue4qbHBeGhSHB7KmhMjFtxd.GQ6ikjKa', 'dan24_00@mail.ru', '2025-04-09 14:44:09', '0496eb8ab6f5389d0cd2060578da294b9c98ce314b0c6180478e9dc46b69709a', 0, 'seeker', 'Даниил', 'Иванов', 'г Чебоксары', NULL, NULL, NULL, NULL, '///', '///', 0, '2007-12-24', '///', NULL, NULL, NULL, 'male', NULL, 'МАОУ Лицей №3', 'спо', '2014-09', '2026-05', '...'),
(123, '222', '$2y$10$.QG0W1Vaoq.qvK2r6rboQOGxhYYawz/YWg/aiazfn/wg1CQA8ia2W', 'romstar744@gmail.com', '2025-04-10 20:56:25', NULL, 1, 'employer', NULL, NULL, NULL, 'аыува', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, 'ацуа', NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
