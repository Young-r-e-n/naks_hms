-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 04, 2025 at 06:02 AM
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
-- Database: `pharmacy_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_credentials`
--

CREATE TABLE `admin_credentials` (
  `USERNAME` varchar(50) NOT NULL,
  `PASSWORD` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

--
-- Dumping data for table `admin_credentials`
--

INSERT INTO `admin_credentials` (`USERNAME`, `PASSWORD`) VALUES
('admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `ID` int(11) NOT NULL,
  `customer_uid` varchar(100) DEFAULT NULL,
  `NAME` varchar(20) NOT NULL,
  `CONTACT_NUMBER` varchar(10) NOT NULL,
  `ADDRESS` varchar(100) NOT NULL,
  `DOCTOR_NAME` varchar(20) NOT NULL,
  `DOCTOR_ADDRESS` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`ID`, `customer_uid`, `NAME`, `CONTACT_NUMBER`, `ADDRESS`, `DOCTOR_NAME`, `DOCTOR_ADDRESS`) VALUES
(18, 'CUS202507017073', 'Test', '1234567899', 'Cssssdsd,ddsssaasa', 'Azxcssasasas', 'Qwsadadadffvb'),
(21, 'CUS202507014785', 'Walkincustomer', '0712312325', 'Anywhere,anywhere', 'Anydoctor', 'Anywhere,anywhere\n'),
(22, 'CUS202507032582', 'Saturday', '1112312345', 'Anywhere, Nakuru Kenya', 'Anydoctor', 'Anywhere, Nakuru Kenya');

-- --------------------------------------------------------

--
-- Table structure for table `customer_history`
--

CREATE TABLE `customer_history` (
  `id` int(11) NOT NULL,
  `customer_uid` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `visit_date` date NOT NULL,
  `added_by` varchar(100) NOT NULL,
  `consultation_charge` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_history`
--

INSERT INTO `customer_history` (`id`, `customer_uid`, `description`, `visit_date`, `added_by`, `consultation_charge`, `created_at`) VALUES
(4, 'CUS202507017073', 'treated for malaria', '2025-06-30', 'Admin', '', '2025-07-01 09:30:12'),
(5, 'CUS202507014785', 'Walk-in Customer', '2025-07-01', 'Admin', '', '2025-07-01 09:50:30'),
(6, 'CUS202507017073', 'treatmnt2', '2025-07-01', 'Admin', '', '2025-07-01 18:29:16'),
(7, 'CUS202507017073', 'trial once', '2025-07-02', 'Admin', '', '2025-07-02 10:48:29'),
(8, 'CUS202507017073', 'treated once again with piriton', '2025-07-03', 'Admin', '600', '2025-07-03 12:20:55'),
(9, 'CUS202507032582', 'Anywhere, Nakuru Kenya', '2025-07-04', 'Doctor Eddy', '200', '2025-07-03 13:17:01');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `INVOICE_ID` int(11) NOT NULL,
  `INVOICE_NUMBER` varchar(50) NOT NULL,
  `NET_TOTAL` double NOT NULL DEFAULT 0,
  `INVOICE_DATE` timestamp NOT NULL DEFAULT current_timestamp(),
  `CUSTOMER_ID` int(11) NOT NULL,
  `TOTAL_AMOUNT` double NOT NULL,
  `TOTAL_DISCOUNT` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`INVOICE_ID`, `INVOICE_NUMBER`, `NET_TOTAL`, `INVOICE_DATE`, `CUSTOMER_ID`, `TOTAL_AMOUNT`, `TOTAL_DISCOUNT`) VALUES
(1, '1', 30, '2021-10-19 00:00:00', 14, 30, 0),
(2, '2', 2626, '2021-10-19 00:00:00', 6, 2626, 0),
(3, '3', 5252, '2025-07-01 00:00:00', 21, 5252, 0),
(5, '4', 200, '2025-07-01 00:00:00', 21, 200, 0),
(7, '6', 40, '2025-07-02 21:00:00', 21, 40, 0),
(8, '7', 100, '2025-07-02 21:00:00', 21, 100, 0),
(9, '8', 40, '2025-07-02 21:00:00', 21, 40, 0),
(10, '9', 40, '2025-07-02 21:00:00', 21, 40, 0),
(11, '10', 400, '2025-07-02 21:00:00', 21, 400, 0),
(13, '11', 20, '2025-07-02 21:00:00', 21, 20, 0),
(14, '12', 20, '2025-07-02 21:00:00', 21, 20, 0),
(16, '2025', 0, '0000-00-00 00:00:00', 21, 0, 60),
(18, '2026', 0, '0000-00-00 00:00:00', 21, 0, 120),
(20, '1751589702', 0, '0000-00-00 00:00:00', 21, 0, 60),
(21, '1751591753', 40, '2025-07-03 21:00:00', 21, 40, 0),
(22, '1751592142', 120, '2025-07-03 21:00:00', 21, 120, 0),
(23, '1751592937', 180, '2025-07-03 21:00:00', 21, 180, 0),
(24, '', 40, '2025-07-03 21:00:00', 21, 40, 0),
(25, '1751595024393', 40, '2025-07-03 21:00:00', 21, 40, 0),
(26, '1751600271185', 140, '2025-07-04 02:37:00', 21, 140, 0);

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `ID` int(11) NOT NULL,
  `NAME` varchar(100) NOT NULL,
  `PACKING` varchar(20) NOT NULL,
  `GENERIC_NAME` varchar(100) NOT NULL,
  `SUPPLIER_NAME` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`ID`, `NAME`, `PACKING`, `GENERIC_NAME`, `SUPPLIER_NAME`) VALUES
(19, 'Treble', 'SINGLE TABS', 'Tr', 'Ram');

-- --------------------------------------------------------

--
-- Table structure for table `medicines_stock`
--

CREATE TABLE `medicines_stock` (
  `ID` int(11) NOT NULL,
  `NAME` varchar(100) NOT NULL,
  `BATCH_ID` varchar(20) NOT NULL,
  `EXPIRY_DATE` varchar(10) NOT NULL,
  `QUANTITY` int(11) NOT NULL,
  `MRP` double NOT NULL,
  `RATE` double NOT NULL,
  `INVOICE_NUMBER` varchar(50) DEFAULT NULL,
  `medicine_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

--
-- Dumping data for table `medicines_stock`
--

INSERT INTO `medicines_stock` (`ID`, `NAME`, `BATCH_ID`, `EXPIRY_DATE`, `QUANTITY`, `MRP`, `RATE`, `INVOICE_NUMBER`, `medicine_id`) VALUES
(12, 'Treble', 'CUSTOM', '12/40', 12, 20, 5, 'N/A-20250703142516', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `SUPPLIER_NAME` varchar(100) NOT NULL,
  `INVOICE_NUMBER` varchar(50) NOT NULL,
  `VOUCHER_NUMBER` int(11) NOT NULL,
  `PURCHASE_DATE` varchar(10) NOT NULL,
  `TOTAL_AMOUNT` double NOT NULL,
  `PAYMENT_STATUS` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`SUPPLIER_NAME`, `INVOICE_NUMBER`, `VOUCHER_NUMBER`, `PURCHASE_DATE`, `TOTAL_AMOUNT`, `PAYMENT_STATUS`) VALUES
('Ram', '145', 1, '2025-07-01', 300, 'PAID'),
('Ram', '21', 2, '2025-07-01', 900, 'PAID'),
('Ram', '14', 3, '2025-07-01', 900, 'PAID'),
('Ram', '99', 4, '2025-07-01', 1000, 'PAID'),
('Ram', 'qaz12', 5, '2025-07-03', 270, 'PAID'),
('Ram', '1455', 6, '2025-07-03', 60, 'PAID'),
('Ram', 'N/A', 7, '2025-07-03', 500, 'PAID'),
('Ram', 'N/A-20250703141413', 8, '2025-07-03', 400, 'PAID'),
('Ram', 'N/A-20250703141646', 9, '2025-07-03', 100, 'PAID'),
('Ram', 'N/A-20250703142516', 10, '2025-07-03', 500, 'PAID');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `ID` int(11) NOT NULL,
  `CUSTOMER_ID` int(11) NOT NULL,
  `INVOICE_NUMBER` varchar(50) NOT NULL,
  `MEDICINE_NAME` varchar(100) NOT NULL,
  `BATCH_ID` varchar(50) NOT NULL,
  `EXPIRY_DATE` date NOT NULL,
  `QUANTITY` int(11) NOT NULL,
  `MRP` decimal(10,2) NOT NULL,
  `DISCOUNT` decimal(10,2) DEFAULT 0.00,
  `TOTAL` decimal(10,2) NOT NULL,
  `PROFIT` decimal(10,2) DEFAULT 0.00,
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`ID`, `CUSTOMER_ID`, `INVOICE_NUMBER`, `MEDICINE_NAME`, `BATCH_ID`, `EXPIRY_DATE`, `QUANTITY`, `MRP`, `DISCOUNT`, `TOTAL`, `PROFIT`, `CREATED_AT`) VALUES
(4, 21, '3', 'CROSIN', 'CROS12', '0000-00-00', 2, 2626.00, 0.00, 5252.00, 5200.00, '2025-07-01 10:51:43'),
(5, 21, '4', 'TREBLE', 'Q21', '0000-00-00', 1, 100.00, 0.00, 100.00, 10.00, '2025-07-01 11:39:20'),
(6, 21, '5', 'TREBLE', 'Q21', '0000-00-00', 2, 100.00, 0.00, 200.00, 20.00, '2025-07-01 12:02:16'),
(7, 21, '6', 'TREBLE', 'Q21', '0000-00-00', 1, 100.00, 0.00, 100.00, 10.00, '2025-07-01 12:13:10'),
(8, 21, '7', 'TREBLE', 'CUSTOM', '0000-00-00', 2, 20.00, 0.00, 40.00, 30.00, '2025-07-03 11:31:52'),
(9, 21, 'INV-20250703175059-339', 'TREBLE', 'CUSTOM', '0000-00-00', 2, 20.00, 0.00, 40.00, 30.00, '2025-07-03 14:51:59'),
(10, 21, 'INV-20250703175502-610', 'TREBLE', 'CUSTOM', '0000-00-00', 20, 20.00, 0.00, 400.00, 300.00, '2025-07-03 14:55:14'),
(11, 21, 'INV-20250703180045-104', 'TREBLE', 'CUSTOM', '0000-00-00', 1, 20.00, 0.00, 20.00, 15.00, '2025-07-03 15:01:13'),
(12, 21, 'INV-20250703190106-434', 'TREBLE', 'CUSTOM', '0000-00-00', 1, 20.00, 0.00, 20.00, 15.00, '2025-07-03 16:01:21'),
(13, 21, 'INV-20250703190458-760', 'TREBLE', 'CUSTOM', '0000-00-00', 2, 20.00, 0.00, 40.00, 30.00, '2025-07-03 16:05:17'),
(14, 21, 'INV-20250703191537-516', 'TREBLE', 'CUSTOM', '0000-00-00', 3, 20.00, 0.00, 60.00, 45.00, '2025-07-03 16:15:57'),
(15, 21, 'INV-20250703191816-934', 'TREBLE', 'CUSTOM', '0000-00-00', 5, 20.00, 0.00, 100.00, 75.00, '2025-07-03 16:18:35'),
(16, 21, '1751560889224', 'TREBLE', 'CUSTOM', '0000-00-00', 6, 20.00, 0.00, 120.00, 90.00, '2025-07-03 16:41:44'),
(17, 21, '1751587854249', 'TREBLE', 'CUSTOM', '0000-00-00', 2, 20.00, 0.00, 40.00, 30.00, '2025-07-04 00:34:11'),
(18, 21, '1751589685894', 'TREBLE', 'CUSTOM', '0000-00-00', 3, 20.00, 0.00, 60.00, 45.00, '2025-07-04 00:41:42'),
(19, 21, '1751591735998', 'TREBLE', 'CUSTOM', '0000-00-00', 2, 20.00, 0.00, 40.00, 30.00, '2025-07-04 01:15:53'),
(20, 21, '1751592097738', 'TREBLE', 'CUSTOM', '0000-00-00', 6, 20.00, 0.00, 120.00, 90.00, '2025-07-04 01:22:22'),
(21, 21, '1751592919959', 'TREBLE', 'CUSTOM', '0000-00-00', 9, 20.00, 0.00, 180.00, 135.00, '2025-07-04 01:35:37'),
(22, 21, '1751593610751', 'TREBLE', 'CUSTOM', '0000-00-00', 5, 20.00, 0.00, 100.00, 75.00, '2025-07-04 01:47:14'),
(23, 21, '1751593899923', 'TREBLE', 'CUSTOM', '0000-00-00', 2, 20.00, 0.00, 40.00, 30.00, '2025-07-04 01:51:55'),
(24, 21, '1751595024393', 'TREBLE', 'CUSTOM', '0000-00-00', 2, 20.00, 0.00, 40.00, 30.00, '2025-07-04 02:10:56'),
(25, 21, '1751600271185', 'TREBLE', 'CUSTOM', '0000-00-00', 7, 20.00, 0.00, 140.00, 105.00, '2025-07-04 03:38:20');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `ID` int(11) NOT NULL,
  `NAME` varchar(100) NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `CONTACT_NUMBER` varchar(10) NOT NULL,
  `ADDRESS` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`ID`, `NAME`, `EMAIL`, `CONTACT_NUMBER`, `ADDRESS`) VALUES
(30, 'Ram', 'ram@gmail.com', '1234567896', 'Anywhere,anywhere');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$bElojy5RG0BcbXIXxWBmIeVdkw2dDMGB/6TQZmYa1hAirY/cZrP4G', 'admin', '2025-06-30 18:44:36'),
(2, 'pharmacist', '$2y$10$v5hPCSb1m9JKFVHKKZd5LegntAP8Sj3NJnh1OblZNLdL3is.m6Zyy', 'pharmacist', '2025-07-01 07:30:50'),
(3, 'doctor', '$2y$10$Djv3Ic2o/Fhj0biKQn4oPu0mIKRnS/UxkK9P9xtwfh.pMdgJVL.R6', 'doctor', '2025-07-01 07:30:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_credentials`
--
ALTER TABLE `admin_credentials`
  ADD PRIMARY KEY (`USERNAME`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `customer_uid` (`customer_uid`);

--
-- Indexes for table `customer_history`
--
ALTER TABLE `customer_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_uid` (`customer_uid`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`INVOICE_ID`),
  ADD UNIQUE KEY `INVOICE_NUMBER` (`INVOICE_NUMBER`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `medicines_stock`
--
ALTER TABLE `medicines_stock`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `BATCH_ID` (`BATCH_ID`),
  ADD KEY `fk_medicine_id` (`medicine_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`VOUCHER_NUMBER`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CUSTOMER_ID` (`CUSTOMER_ID`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `customer_history`
--
ALTER TABLE `customer_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `INVOICE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `medicines_stock`
--
ALTER TABLE `medicines_stock`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `VOUCHER_NUMBER` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer_history`
--
ALTER TABLE `customer_history`
  ADD CONSTRAINT `customer_history_ibfk_1` FOREIGN KEY (`customer_uid`) REFERENCES `customers` (`customer_uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `medicines_stock`
--
ALTER TABLE `medicines_stock`
  ADD CONSTRAINT `fk_medicine_id` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`ID`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`CUSTOMER_ID`) REFERENCES `customers` (`ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
