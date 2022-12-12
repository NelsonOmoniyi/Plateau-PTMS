-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2022 at 11:55 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `plateau`
--

-- --------------------------------------------------------

--
-- Table structure for table `dealership`
--

CREATE TABLE `dealership` (
  `id` int(100) NOT NULL,
  `cat` varchar(255) NOT NULL,
  `amount` varchar(100) NOT NULL,
  `item_code` varchar(100) NOT NULL,
  `portal_id` varchar(100) NOT NULL,
  `tin` varchar(100) NOT NULL,
  `business_name` varchar(255) NOT NULL,
  `address` varchar(500) NOT NULL,
  `owner_name` varchar(255) NOT NULL,
  `cac_reg_no` varchar(100) NOT NULL,
  `sponsor` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `license_category` varchar(255) NOT NULL,
  `list_of_apprentice` varchar(255) NOT NULL,
  `license_union` varchar(255) NOT NULL,
  `mem_no` varchar(255) NOT NULL,
  `agreement` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `passport` varchar(255) NOT NULL,
  `created` varchar(255) NOT NULL,
  `expiry_date` varchar(255) NOT NULL,
  `approved` int(2) NOT NULL,
  `email` varchar(255) NOT NULL,
  `processed_date` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dealership`
--

INSERT INTO `dealership` (`id`, `cat`, `amount`, `item_code`, `portal_id`, `tin`, `business_name`, `address`, `owner_name`, `cac_reg_no`, `sponsor`, `phone`, `license_category`, `list_of_apprentice`, `license_union`, `mem_no`, `agreement`, `status`, `passport`, `created`, `expiry_date`, `approved`, `email`, `processed_date`) VALUES
(9, 'Motor Vehicle Vendors', '60000', '4296', 'DS1670494585', '23017766191', 'Olivers Concept', 'No 11 GRA Jos', 'Ezekiel Afolabi', '23017766123', 'Oliver nelson Queen', '08108929092', 'Danny', '3', 'NURTW', '123', '1', '1', 'uploads/1208111655.png', '2022-12-08 11:16:55', '2023-12-08', 1, 'ezekialafolabi11@gmail.com', '2022-12-08 11:19:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dealership`
--
ALTER TABLE `dealership`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dealership`
--
ALTER TABLE `dealership`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
