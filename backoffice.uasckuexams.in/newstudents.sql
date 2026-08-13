-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2018 at 05:34 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `masterexams`
--

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(100) NOT NULL,
  `sname` varchar(60) NOT NULL,
  `fname` varchar(60) NOT NULL,
  `mname` varchar(60) NOT NULL,
  `email` varchar(50) NOT NULL,
  `dob` varchar(50) NOT NULL,
  `gender` varchar(60) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `group` varchar(50) NOT NULL,
  `haltckt` varchar(60) NOT NULL,
  `sem` varchar(30) NOT NULL,
  `year` int(20) NOT NULL,
  `aadhar` varchar(12) NOT NULL,
  `address` varchar(50) NOT NULL,
  `address2` varchar(60) NOT NULL,
  `mandal` varchar(50) NOT NULL,
  `city` varchar(30) NOT NULL,
  `state` varchar(20) NOT NULL,
  `pincode` int(30) NOT NULL,
  `image` varchar(100) NOT NULL,
  `signature` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `sname`, `fname`, `mname`, `email`, `dob`, `gender`, `phone`, `group`, `haltckt`, `sem`, `year`, `aadhar`, `address`, `address2`, `mandal`, `city`, `state`, `pincode`, `image`, `signature`) VALUES
(258, 'koraboina', 'kannaiah', 'shobha rani', 'koraboina@gmail.com', '19/10/2018', '0', '8689555', '0', '123456789', '0', 0, '12345', '', '', '', '', '', 0, '', ''),
(259, 'koraboina', 'kannaiah', 'shobha rani', 'koraboina@gmail.com', '10/19/2018', '0', '8689555', '0', '123456789', '0', 0, '12345', '', '', '', '', '', 0, '', ''),
(260, 'koraboina', 'kannaiah', 'shobha rani', 'koraboina@gmail.com', '2018-10-19', '0', '8689555', '0', '123456789', '0', 0, '12345', '', '', '', '', '', 0, '', ''),
(261, 'koraboina', 'kannaiah', 'shobha rani', 'koraboina@gmail.com', '10-19-2018', '0', '8689555', '0', '1234567899', '0', 0, '12345', '', '', '', '', '', 0, '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=262;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
