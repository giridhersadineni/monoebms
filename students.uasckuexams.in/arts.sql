-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 01, 2018 at 05:01 AM
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
-- Database: `arts`
--

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`username`, `password`) VALUES
('rajesh', 'password');

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
  `phone` varchar(30) NOT NULL,
  `group` varchar(50) NOT NULL,
  `haltckt` varchar(60) NOT NULL,
  `sem` varchar(30) NOT NULL,
  `year` int(20) NOT NULL,
  `aadhar` int(20) NOT NULL,
  `address` varchar(50) NOT NULL,
  `address2` varchar(60) NOT NULL,
  `mandal` varchar(50) NOT NULL,
  `city` varchar(30) NOT NULL,
  `state` varchar(20) NOT NULL,
  `pincode` int(30) NOT NULL,
  `imgurl` varchar(80) NOT NULL,
  `signature` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `sname`, `fname`, `mname`, `email`, `phone`, `group`, `haltckt`, `sem`, `year`, `aadhar`, `address`, `address2`, `mandal`, `city`, `state`, `pincode`, `imgurl`, `signature`) VALUES
(1, 'rajesh', 'kannaiah', 'shobhrani', 'rajesh.koraboina19@gmail.com', '8686962607', 'cse', '12s51a516', '', 0, 123456789, 'gudibandal', 'gudibandal', 'hnk', 'hnk', 'TELANGANA', 506001, '', ''),
(2, 'manish', 'praveen', 'vijaya', 'mainshkotti@gmail.com', '122554555', 'b.com', '12511245862', '', 0, 1478956933, 'new shampet', 'subadari', 'hnk', 'hnk', 'TELANGANA', 506002, '', '');

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
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
