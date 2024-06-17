-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2024 at 09:54 PM
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
-- Database: `ca_project1`
--

-- --------------------------------------------------------

--
-- Table structure for table `additional_note`
--

CREATE TABLE `additional_note` (
  `id` int(11) NOT NULL,
  `gstin` varchar(15) NOT NULL,
  `remark` text NOT NULL,
  `user` varchar(100) NOT NULL,
  `date` date DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `additional_note`
--

INSERT INTO `additional_note` (`id`, `gstin`, `remark`, `user`, `date`, `attachment`) VALUES
(22, '24ABIPD1676Q1ZB', 'Utsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav Shah\r\n\r\nUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav Shah\r\nUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav ShahUtsav Shah', 'utsav', '2024-06-07', ''),
(23, '24ABIPD1676Q1ZB', 'abc', 'utsav', '2024-06-07', '');

-- --------------------------------------------------------

--
-- Table structure for table `gst_basic_info`
--

CREATE TABLE `gst_basic_info` (
  `id` int(11) NOT NULL,
  `sr_no` int(11) NOT NULL,
  `gstin` varchar(15) NOT NULL,
  `trade_name` varchar(255) NOT NULL,
  `return_filing_frequency` varchar(50) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `status` int(10) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gst_basic_info`
--

INSERT INTO `gst_basic_info` (`id`, `sr_no`, `gstin`, `trade_name`, `return_filing_frequency`, `group_name`, `status`) VALUES
(814, 1, '24ABIPD1676Q1ZB', 'ANOKHI SARI CENTRE', 'MONTHLY', 'ABC', 1),
(815, 2, '24ACIPP2964J1ZA', 'BHAGYODAY KIRANA STORES', 'COMPOSITION', 'ABC', 1),
(816, 3, '24ADPPG7997J1ZR', 'CHAMUNDA ICE FACTORY', 'COMPOSITION', 'ABC', 1),
(817, 4, '24AFEPP6910K1ZC', 'NILKANTH MOTORS', 'COMPOSITION', 'ABC', 1),
(818, 5, '24BFHPJ4056G1ZM', 'SARASWATI ICE FACTORY', 'MONTHLY', 'ABC', 1),
(819, 6, '24AHVPP3292P1ZC', 'SHIV NAMKEEN', 'QUARTERLY', 'XYZ', 1),
(820, 7, '24AGRPJ6375N2ZL', 'SHRI GAJANAND SALES AGENCY', 'QUARTERLY', 'ABC', 1),
(821, 8, '24AGHPP8346D2ZB', 'ACCURATE CARE', 'MONTHLY', 'ABC', 1),
(822, 9, '24ADWPP0557G2Z7', 'AJAYEE TEXTILES', 'MONTHLY', 'XYZ', 1),
(823, 10, '24ACVPP3881F1Z4', 'ANILA FABRICS', 'QUARTERLY', 'ZYX', 1),
(824, 11, '22AAAAA0000A1Z5', 'pooja', 'MONTHLY', 'kk', 1),
(825, 12, '22AAAAA0000A1Z6', 'zalak', 'MONTHLY', 'kk', 1);

-- --------------------------------------------------------

--
-- Table structure for table `gst_details`
--

CREATE TABLE `gst_details` (
  `detid` int(11) NOT NULL,
  `basic_info_id` int(11) NOT NULL,
  `gstr3b` varchar(50) NOT NULL,
  `gstr3b_query` varchar(255) DEFAULT NULL,
  `gstr3b_query_solved` varchar(3) NOT NULL,
  `gstr1` varchar(50) NOT NULL,
  `gstr1_query` varchar(255) DEFAULT NULL,
  `gstr1_query_solved` varchar(3) NOT NULL,
  `month` varchar(50) NOT NULL,
  `year` int(11) NOT NULL,
  `return_status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gst_details`
--

INSERT INTO `gst_details` (`detid`, `basic_info_id`, `gstr3b`, `gstr3b_query`, `gstr3b_query_solved`, `gstr1`, `gstr1_query`, `gstr1_query_solved`, `month`, `year`, `return_status`) VALUES
(82, 814, 'TAX', '5000000  o ok kmkim', 'YES', 'NIL', 'oko l k ijijik', 'YES', 'January', 2024, 'M'),
(83, 814, 'FILED', '00 ok ok ok ok ok ok', 'NO', 'NIL', '', 'NO', 'February', 2024, 'M'),
(84, 815, 'FILED', 'knkn0 ok ok oik ok ok', 'YES', 'NIL', 'kji', 'YES', 'February', 2024, 'C'),
(85, 818, 'TAX', ' knjkn', 'YES', 'NIL', 'kji', 'NO', 'February', 2024, 'M'),
(86, 819, 'NIL', ',,ll ok ij ok ok ok ok ok ijijij', 'NO', 'TAX', 'njnjnjn', 'NO', 'February', 2024, 'Q'),
(87, 818, 'FILED', 'kmkmk ok oko ok', 'YES', 'FILED', 'pkokk mkm', 'YES', 'January', 2024, 'M'),
(88, 822, 'FILED', 'ok ojjjojo ok ok k okok', 'YES', 'NIL', 'mkjjk oko', 'YES', 'February', 2024, 'M'),
(89, 820, 'FILED', 'may', 'YES', 'FILED', 'sdd', 'YES', 'February', 2024, 'Q'),
(90, 824, 'NIL', 'mkm okokkk', 'YES', 'FILED', '  m,mm', 'YES', 'February', 2024, 'M'),
(91, 819, 'FILED', '5000 ok ok ok ok ok', 'NO', 'NIL', 'may', 'YES', 'January', 2024, 'Q'),
(92, 823, 'FILED', 'abcok', 'YES', 'NIL', 'may', 'YES', 'February', 2024, 'Q'),
(93, 815, 'FILED', 'ok ok ko ok', 'NO', 'NIL', 'may ok', 'YES', 'January', 2024, 'C'),
(94, 816, 'FILED', '322', 'YES', 'TAX', '25', 'YES', 'February', 2024, 'C'),
(95, 817, 'FILED', '123 okokok', 'YES', 'FILED', '233', 'NO', 'February', 2024, 'C'),
(96, 821, 'FILED', '', 'NO', 'NIL', 'ok', 'YES', 'February', 2024, 'M'),
(97, 820, 'TAX', '5000 pl kk', 'YES', 'NIL', '0000', 'YES', 'January', 2024, 'Q');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` int(50) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`) VALUES
(1, 'utsav', 123, 'shahutsav352003@gmail.com'),
(2, 'sidh', 123, 'abc@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `additional_note`
--
ALTER TABLE `additional_note`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gst_basic_info`
--
ALTER TABLE `gst_basic_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sr_no` (`sr_no`,`gstin`,`trade_name`,`return_filing_frequency`,`group_name`);

--
-- Indexes for table `gst_details`
--
ALTER TABLE `gst_details`
  ADD PRIMARY KEY (`detid`),
  ADD KEY `basic_info_id` (`basic_info_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `additional_note`
--
ALTER TABLE `additional_note`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `gst_basic_info`
--
ALTER TABLE `gst_basic_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=826;

--
-- AUTO_INCREMENT for table `gst_details`
--
ALTER TABLE `gst_details`
  MODIFY `detid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gst_details`
--
ALTER TABLE `gst_details`
  ADD CONSTRAINT `gst_details_ibfk_1` FOREIGN KEY (`basic_info_id`) REFERENCES `gst_basic_info` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
