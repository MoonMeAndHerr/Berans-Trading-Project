-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2025 at 08:00 AM
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
-- Database: `beranstradingdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `commission`
--

CREATE TABLE `commission` (
  `commission_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `commission_amount` decimal(15,2) DEFAULT NULL,
  `date_given` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `company_id` int(11) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_account_name` varchar(100) DEFAULT NULL,
  `bank_account_number` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `customer_address` text DEFAULT NULL,
  `customer_company_name` varchar(100) DEFAULT NULL,
  `customer_designation` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `invoice_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `price_id` int(11) DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `expected_door_arrival_date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `subtotal` decimal(15,2) DEFAULT NULL,
  `deposit_50_percent` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `invoice_item_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit_price` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `price`
--

CREATE TABLE `price` (
  `price_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `carton_width` decimal(10,2) DEFAULT NULL,
  `carton_height` decimal(10,2) DEFAULT NULL,
  `carton_length` decimal(10,2) DEFAULT NULL,
  `pcs_per_carton` int(11) DEFAULT NULL,
  `no_of_carton` int(11) DEFAULT NULL,
  `designlogo` text DEFAULT NULL,
  `price` decimal(15,2) DEFAULT NULL COMMENT 'in yen',
  `shipping_price` decimal(15,2) DEFAULT NULL COMMENT 'in yen',
  `additional_price` decimal(15,2) DEFAULT NULL COMMENT 'in yen',
  `production_time` varchar(50) DEFAULT NULL,
  `conversion_rate` decimal(10,4) DEFAULT NULL,
  `total_price` decimal(15,2) DEFAULT NULL,
  `unit_price` decimal(15,2) DEFAULT NULL,
  `selling_unit` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `subcategory` varchar(50) DEFAULT NULL,
  `material` varchar(50) DEFAULT NULL,
  `shape` varchar(50) DEFAULT NULL,
  `size_volume` varchar(50) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `current_stock` int(11) DEFAULT 0,
  `reorder_level` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `schedule_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `task_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `priority` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `session_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping`
--

CREATE TABLE `shipping` (
  `shipping_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `shipping_provider` varchar(50) DEFAULT NULL,
  `tracking_number` varchar(50) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `expected_arrival_date` date DEFAULT NULL,
  `actual_arrival_date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `reminder_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `staff_name` varchar(100) NOT NULL,
  `staff_designation` varchar(50) DEFAULT NULL,
  `staff_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(100) NOT NULL,
  `supplier_contact_person` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','manager','sales','warehouse') NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `commission`
--
ALTER TABLE `commission`
  ADD PRIMARY KEY (`commission_id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `price_id` (`price_id`),
  ADD KEY `idx_invoice_date` (`invoice_date`),
  ADD KEY `idx_invoice_status` (`status`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`invoice_item_id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `price_id` (`price_id`);

--
-- Indexes for table `price`
--
ALTER TABLE `price`
  ADD PRIMARY KEY (`price_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `idx_product_category` (`category`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `shipping`
--
ALTER TABLE `shipping`
  ADD PRIMARY KEY (`shipping_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `idx_shipping_status` (`status`),
  ADD KEY `idx_shipping_tracking` (`tracking_number`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `staff_id` (`staff_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `commission`
--
ALTER TABLE `commission`
  MODIFY `commission_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `invoice_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `price`
--
ALTER TABLE `price`
  MODIFY `price_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping`
--
ALTER TABLE `shipping`
  MODIFY `shipping_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `commission`
--
ALTER TABLE `commission`
  ADD CONSTRAINT `commission_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`),
  ADD CONSTRAINT `commission_ibfk_2` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`invoice_id`);

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`),
  ADD CONSTRAINT `invoice_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`),
  ADD CONSTRAINT `invoice_ibfk_3` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`),
  ADD CONSTRAINT `invoice_ibfk_4` FOREIGN KEY (`price_id`) REFERENCES `price` (`price_id`);

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`invoice_id`),
  ADD CONSTRAINT `invoice_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
  ADD CONSTRAINT `invoice_items_ibfk_3` FOREIGN KEY (`price_id`) REFERENCES `price` (`price_id`);

--
-- Constraints for table `price`
--
ALTER TABLE `price`
  ADD CONSTRAINT `price_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
  ADD CONSTRAINT `price_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`);

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `shipping`
--
ALTER TABLE `shipping`
  ADD CONSTRAINT `shipping_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`),
  ADD CONSTRAINT `shipping_ibfk_2` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`invoice_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
