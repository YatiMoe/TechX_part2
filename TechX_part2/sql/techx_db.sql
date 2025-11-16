-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2025 at 12:28 PM
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
-- Database: `techx_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `eoi`
--

CREATE TABLE `eoi` (
  `EOInumber` int(11) NOT NULL,
  `job_ref_number` varchar(10) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `street_address` varchar(40) NOT NULL,
  `suburb` varchar(40) NOT NULL,
  `state` enum('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,
  `postcode` char(4) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `skill1` tinyint(1) DEFAULT 0,
  `skill2` tinyint(1) DEFAULT 0,
  `skill3` tinyint(1) DEFAULT 0,
  `skill4` tinyint(1) DEFAULT 0,
  `skill5` tinyint(1) DEFAULT 0,
  `other_skills` text DEFAULT NULL,
  `status` enum('New','Current','Final') DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `job_ref` varchar(10) NOT NULL,
  `job_title` varchar(100) NOT NULL,
  `salary_range` varchar(50) NOT NULL,
  `responsibilities` text NOT NULL,
  `essential_skills` text NOT NULL,
  `preferable_skills` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `job_ref`, `job_title`, `salary_range`, `responsibilities`, `essential_skills`, `preferable_skills`, `image`) VALUES
(4, 'IT001', 'IT Support Technician', '$55,000 – $70,000', 'Provide hardware/software troubleshooting; Assist users with network issues; Document and escalate technical problems', '2+ years IT support experience; Knowledge of Windows & Linux; Strong communication and teamwork skills', 'CompTIA A+ or Network+ certification preferred', 'IT Support Technician.jpg'),
(5, 'UX002', 'UI/UX Designer', '$65,000 – $85,000', 'Design user-friendly and visually appealing web interfaces; Collaborate with developers and stakeholders; Conduct usability testing and gather feedback', 'Experience with Figma or Adobe XD; Understanding of accessibility standards; Creative problem-solving and design thinking', 'Experience in animation or branding is beneficial', 'UIUX Designer.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `manager`
--

CREATE TABLE `manager` (
  `manager_id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `failed_attempts` int(11) DEFAULT 0,
  `last_failed_login` datetime DEFAULT NULL,
  `locked_until` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eoi`
--
ALTER TABLE `eoi`
  ADD PRIMARY KEY (`EOInumber`),
  ADD KEY `job_ref_number` (`job_ref_number`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `job_ref` (`job_ref`);

--
-- Indexes for table `manager`
--
ALTER TABLE `manager`
  ADD PRIMARY KEY (`manager_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eoi`
--
ALTER TABLE `eoi`
  MODIFY `EOInumber` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `manager`
--
ALTER TABLE `manager`
  MODIFY `manager_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `eoi`
--
ALTER TABLE `eoi`
  ADD CONSTRAINT `eoi_ibfk_1` FOREIGN KEY (`job_ref_number`) REFERENCES `jobs` (`job_ref`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
