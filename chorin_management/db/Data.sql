-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 31, 2024 at 04:07 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ChorIn`
--

-- --------------------------------------------------------

--
-- Table structure for table `Admin`
--

CREATE TABLE `Admin` (
  `adminId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `hashed_password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Admin`
--

INSERT INTO `Admin` (`adminId`, `name`, `email`, `password`, `hashed_password`) VALUES
(1, 'Sombang', 'sombang@gmail.com', 'Strange4life', '$2y$10$RHcVDPTuz96rXOAh2ljuhuYNpfJgDCaosedC4EU8u.WdM6Sr2wct2array(3)');

-- --------------------------------------------------------

--
-- Table structure for table `Cleaners`
--

CREATE TABLE `Cleaners` (
  `cleanerId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `bio` text DEFAULT NULL,
  `availabilityStatus` varchar(255) DEFAULT 'available',
  `completedJobsCount` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Cleaners`
--

INSERT INTO `Cleaners` (`cleanerId`, `name`, `email`, `bio`, `availabilityStatus`, `completedJobsCount`) VALUES
(1, 'Alice Johnson', 'alice@example.com', 'Experienced cleaner with 5 years of work in various settings.', 'Available', 0),
(2, 'Bob Brown', 'bob@example.com', 'Detail-oriented cleaner specializing in residential cleaning.', 'available', 1),
(16, 'Frederick', 'Frederick@gmail.com', 'Multitalented cleaner with stain specifics', 'unavailable', 1),
(17, 'Hannah', 'Hannah@gmail.com', 'Specialised with oil stains', 'available', 2),
(18, 'Aloy', 'Aloy@gmail.com', 'Has a degree in household management', 'Available', 0),
(19, 'Rammah', 'Rammah@gmail.com', '10 years experience', 'unavailable', 1),
(21, 'Ryana', 'Ryana@gmail.com', '12 years experience', 'unavailable', 0);

-- --------------------------------------------------------

--
-- Table structure for table `JobCategories`
--

CREATE TABLE `JobCategories` (
  `categoryId` int(11) NOT NULL,
  `categoryName` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `JobCategories`
--

INSERT INTO `JobCategories` (`categoryId`, `categoryName`, `description`) VALUES
(1, 'Floor Cleaning', 'Here we focus on all types of floors, cement, tiles, and wood floors.'),
(2, 'Glass Cleaning', 'Here we focus on all types of glasses, windows, and buildings with walls built from glass.'),
(3, 'Carpet Cleaning', 'Here we focus on wool, grass, and plastic carpets.'),
(4, 'Toilet Cleaning', 'Here we focus on all types of toilets, pit, water closet, and bucket latrines.'),
(6, 'Plank Cleaning', 'We are specialised with different wood categories');

-- --------------------------------------------------------

--
-- Table structure for table `Jobs`
--

CREATE TABLE `Jobs` (
  `jobId` int(11) NOT NULL,
  `categoryId` int(11) DEFAULT NULL,
  `job_address` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Completed') DEFAULT 'Pending',
  `cleanerId` int(11) DEFAULT NULL,
  `notificationId` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Jobs`
--

INSERT INTO `Jobs` (`jobId`, `categoryId`, `job_address`, `status`, `cleanerId`, `notificationId`, `description`) VALUES
(4, 3, ';ikuhopi', 'Pending', 2, NULL, 'mmmmmmmm'),
(13, 2, 'Ashesi', 'Completed', 2, NULL, 'Clean all top surfaces'),
(16, 3, 'Amasama', 'Completed', 2, NULL, 'Brown and white carpet'),
(17, 2, 'lkshd', 'Completed', 2, NULL, 'skjndlksdjmfcsd'),
(19, 2, 'fre', 'Completed', 2, NULL, 'vrevr'),
(20, 3, 'Pokuase', 'Completed', 2, NULL, 'Clear the entire kitchen'),
(29, 6, 'Pokuase', 'Completed', 1, NULL, 'Clean all the door posts'),
(30, 3, 'jyhb', 'Pending', 1, NULL, 'njkkn'),
(31, 3, 'k,mk', 'Pending', 18, NULL, 'kl;'),
(32, 2, 'Ashesi', 'Pending', 17, NULL, 'Double layer glasses'),
(33, 2, 'as', 'Pending', 2, NULL, 'ss'),
(34, 2, 'klj', 'Completed', 16, NULL, 'm,,.'),
(35, 2, 'thty', 'Completed', 17, NULL, 'rttgrt'),
(36, 2, 'ttrt', 'Completed', 17, NULL, 'trt'),
(37, 3, 'hgcj', 'Pending', 21, NULL, 'hbjk'),
(38, 3, 'Pokuase', 'Completed', 19, NULL, 'dsd'),
(39, 3, 'uijhk', 'Completed', 2, NULL, 'lkjm'),
(43, 4, 'jlkll', 'Pending', 18, NULL, 'jlnk.');

-- --------------------------------------------------------

--
-- Table structure for table `Notifications`
--

CREATE TABLE `Notifications` (
  `notificationId` int(11) NOT NULL,
  `recipientEmail` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `jobId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Notifications`
--

INSERT INTO `Notifications` (`notificationId`, `recipientEmail`, `message`, `jobId`) VALUES
(1, 'alice@example.com', 'You have been assigned a new job for Floor Cleaning at 123 Main St.', 1),
(2, 'bob@example.com', 'You have been assigned a new job for Glass Cleaning at 456 Elm St.', 2);

-- --------------------------------------------------------

--
-- Table structure for table `UserJobs`
--

CREATE TABLE `UserJobs` (
  `userId` int(11) NOT NULL,
  `jobId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `UserJobs`
--

INSERT INTO `UserJobs` (`userId`, `jobId`) VALUES
(3, 16),
(3, 17),
(3, 19),
(3, 20),
(3, 29),
(3, 34),
(3, 35),
(3, 36),
(3, 38),
(3, 39),
(3, 43),
(5, 13);

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `userId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `user_address` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`userId`, `name`, `email`, `user_address`, `password`) VALUES
(3, 'Sombang', 'patience.sombang@ashesi.edu.gh', '', '$2y$10$vIlbefPAYBfAYEBny4OBSeV/L/CaVrOEu/BWUXwh3oMsbnbQIKkhe'),
(4, 'Sombang', 'me@gmail.com', '', '$2y$10$P7Df7MX4oEILzYRLrkRKi.p85zQB3..yfOxILaP87GOQO3tFqtSrC'),
(5, 'Patience', 'Patience@gmail.com', '', '$2y$10$IHtfWESQfzzWpbXPiDS1OOZv7qalQ5/YaDH.ejZ9jsLbhEi1EOwdi'),
(6, 'Anthony', 'Anthony@gmail.com', '', '$2y$10$M1Wf63FUO6O2rD1/FJwBXe/BfvLnsXpgv.Ksx7Sm7ZQQhqxMi3q12'),
(10, 'Favour', 'Favour@gmail.com', 'sdfe', '$2y$10$c/0aVNd7kIRtCgvZrq8Oo.7YqiSfrO1Hz8CSnrWo8mkESNp3xemca'),
(11, 'new', 'new@gmail.com', 'sdfd', '$2y$10$ZYExEDOP2Yt5wQnol/FLkerKOk2X5F/ka08EQ/UojZkhOcaDZ1Z3m'),
(12, 'Nyonglema', 'Nyonglema@gmail.com', 'Ashesi', '$2y$10$n81Aw6f.UOe4KmJEkCRdFOBICKJ3l9tY47kdP9wYlSecWV2KIfAVq');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Admin`
--
ALTER TABLE `Admin`
  ADD PRIMARY KEY (`adminId`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `Cleaners`
--
ALTER TABLE `Cleaners`
  ADD PRIMARY KEY (`cleanerId`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `JobCategories`
--
ALTER TABLE `JobCategories`
  ADD PRIMARY KEY (`categoryId`);

--
-- Indexes for table `Jobs`
--
ALTER TABLE `Jobs`
  ADD PRIMARY KEY (`jobId`),
  ADD KEY `categoryId` (`categoryId`),
  ADD KEY `cleanerId` (`cleanerId`),
  ADD KEY `notificationId` (`notificationId`);

--
-- Indexes for table `Notifications`
--
ALTER TABLE `Notifications`
  ADD PRIMARY KEY (`notificationId`);

--
-- Indexes for table `UserJobs`
--
ALTER TABLE `UserJobs`
  ADD PRIMARY KEY (`userId`,`jobId`),
  ADD KEY `userjobs_ibfk_2` (`jobId`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Admin`
--
ALTER TABLE `Admin`
  MODIFY `adminId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Cleaners`
--
ALTER TABLE `Cleaners`
  MODIFY `cleanerId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `JobCategories`
--
ALTER TABLE `JobCategories`
  MODIFY `categoryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `Jobs`
--
ALTER TABLE `Jobs`
  MODIFY `jobId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `Notifications`
--
ALTER TABLE `Notifications`
  MODIFY `notificationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Jobs`
--
ALTER TABLE `Jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`categoryId`) REFERENCES `JobCategories` (`categoryId`),
  ADD CONSTRAINT `jobs_ibfk_2` FOREIGN KEY (`cleanerId`) REFERENCES `Cleaners` (`cleanerId`),
  ADD CONSTRAINT `jobs_ibfk_3` FOREIGN KEY (`notificationId`) REFERENCES `Notifications` (`notificationId`);

--
-- Constraints for table `UserJobs`
--
ALTER TABLE `UserJobs`
  ADD CONSTRAINT `userjobs_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `Users` (`userId`),
  ADD CONSTRAINT `userjobs_ibfk_2` FOREIGN KEY (`jobId`) REFERENCES `Jobs` (`jobId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
