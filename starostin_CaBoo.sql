-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 14, 2025 at 04:51 PM
-- Server version: 10.11.11-MariaDB-ubu2204
-- PHP Version: 8.3.20

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
  `educationDescription` text DEFAULT NULL,
  `proforientation_test_results` text DEFAULT NULL,
  `proforientation_recommendations` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `registration_date`, `email_verification_token`, `is_email_verified`, `role`, `first_name`, `last_name`, `city`, `company_name`, `company_description`, `avatar`, `company_logo`, `about`, `skills`, `desired_salary`, `birthdate`, `social_links`, `experience`, `education`, `resume`, `gender`, `industry`, `educationInstitution`, `educationDegree`, `educationStart`, `educationEnd`, `educationDescription`, `proforientation_test_results`, `proforientation_recommendations`) VALUES
(116, 'dan24_00', '$2y$10$0uSSWMet369P7pfWg8U2Eue4qbHBeGhSHB7KmhMjFtxd.GQ6ikjKa', 'dan24_00@mail.ru', '2025-04-09 14:44:09', '0496eb8ab6f5389d0cd2060578da294b9c98ce314b0c6180478e9dc46b69709a', 0, 'seeker', 'Даниил', 'Иванов', 'г Чебоксары', NULL, NULL, NULL, NULL, '///', '///', 0, '2007-12-24', '///', NULL, NULL, NULL, 'male', NULL, 'МАОУ Лицей №3', 'спо', '2014-09', '2026-05', '...', NULL, NULL),
(123, '222', '$2y$10$.QG0W1Vaoq.qvK2r6rboQOGxhYYawz/YWg/aiazfn/wg1CQA8ia2W', 'romstar744@gmail.com', '2025-04-10 20:56:25', NULL, 1, 'employer', NULL, NULL, NULL, 'ПКГХ', 'Крутая компания', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, 'IT', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(127, '333', '$2y$10$fnY9DXzTHNwJJHGTkIAdiuZ7O7TNJLsFR.2YctVUXWWe2tYsKPfJC', 'romstar744@mail.ru', '2025-04-14 21:39:09', '360003983ab2536e7f55c7e79d95c3eccaaeafbcbba68d528fe72cf71536666c', 0, 'seeker', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(133, '111', '$2y$10$gUgNLmiOQitumelit88o2udqugRETHKeNQLvy9gtP1BCItOHTVfg2', 'romstar742@gmail.com', '2025-05-14 15:48:14', NULL, 1, 'seeker', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', 'not_specified', NULL, 'ПКГХ', 'спо', NULL, NULL, NULL, '{\"q1\":\"reading\",\"q2\":\"problem_solving\",\"q3\":\"organized\",\"q4\":\"python\",\"q5\":\"salary\",\"q6\":\"online_courses\",\"q7\":\"web_development\",\"q8\":\"collaboration\",\"q9\":\"break_down\",\"q10\":\"leader\"}', '                <div class=\"recommendation\">                    <h3>Data Science</h3>                    <p>Анализ данных и машинное обучение.</p>                    <p>Ключевые навыки: Python, SQL, Machine Learning</p>                </div>                            <div class=\"recommendation\">                    <h3>Веб-разработка</h3>                    <p>Создание сайтов и веб-приложений.</p>                    <p>Ключевые навыки: HTML, CSS, JavaScript</p>                </div>                            <div class=\"recommendation\">                    <h3>Менеджмент</h3>                    <p>Управление проектами и командами.</p>                    <p>Ключевые навыки: Agile, Scrum, Лидерство</p>                </div>            ');

-- --------------------------------------------------------

--
-- Table structure for table `vacancies`
--

CREATE TABLE `vacancies` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `requirements` text NOT NULL,
  `salary` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vacancies`
--

INSERT INTO `vacancies` (`id`, `company_id`, `title`, `description`, `requirements`, `salary`, `created_at`) VALUES
(3, 123, 'Программист', 'Junior', 'C#, C++', '9999999', '2025-05-14 16:49:56');

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
-- Indexes for table `vacancies`
--
ALTER TABLE `vacancies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT for table `vacancies`
--
ALTER TABLE `vacancies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `vacancies`
--
ALTER TABLE `vacancies`
  ADD CONSTRAINT `vacancies_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
