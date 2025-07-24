-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 24, 2025 at 07:34 AM
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

--
-- Dumping data for table `commission`
--

INSERT INTO `commission` (`commission_id`, `staff_id`, `invoice_id`, `commission_amount`, `date_given`) VALUES
(1, 3, 1, 6000.00, '2025-07-22');

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

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`company_id`, `company_name`, `bank_name`, `bank_account_name`, `bank_account_number`, `address`, `contact`) VALUES
(1, 'Berans Trading', 'Tokyo Bank', 'Maybank', '123456789', 'Malaysia, Johor', '03-1234-5678');

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
  `customer_designation` varchar(50) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `customer_name`, `customer_phone`, `customer_address`, `customer_company_name`, `customer_designation`, `deleted_at`) VALUES
(1, 'Tanaka San', '090-1111-2222', '2-3-4 Shinjuku, Tokyo', 'ABC Corporation', 'Purchasing Manager', NULL),
(2, 'Suzuki San', '090-3333-4444', '5-6-7 Ginza, Tokyo', 'XYZ Inc.', 'Director', NULL);

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

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`invoice_id`, `company_id`, `customer_id`, `staff_id`, `price_id`, `invoice_date`, `order_date`, `expected_door_arrival_date`, `status`, `subtotal`, `deposit_50_percent`) VALUES
(1, 1, 1, 3, NULL, '2025-07-22', '2025-07-15', NULL, 'pending', 120000.00, NULL);

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

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`invoice_item_id`, `invoice_id`, `product_id`, `price_id`, `description`, `image_url`, `quantity`, `unit_price`) VALUES
(1, 1, 1, NULL, NULL, NULL, 500, 60.00),
(2, 1, 2, NULL, NULL, NULL, 200, 180.00);

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
  `price_rm` decimal(15,6) DEFAULT NULL,
  `shipping_price` decimal(15,2) DEFAULT NULL COMMENT 'in yen',
  `additional_price` decimal(15,2) DEFAULT NULL COMMENT 'in yen',
  `production_time` varchar(50) DEFAULT NULL,
  `conversion_rate` decimal(10,4) DEFAULT NULL,
  `total_price_yen` decimal(15,2) DEFAULT NULL,
  `total_price_rm` decimal(15,2) DEFAULT NULL,
  `deposit_50_yen` decimal(15,2) DEFAULT NULL,
  `deposit_50_rm` decimal(15,2) DEFAULT NULL,
  `cbm_carton` decimal(10,6) DEFAULT NULL,
  `total_cbm` decimal(10,6) DEFAULT NULL,
  `vm_carton` decimal(10,2) DEFAULT NULL,
  `total_vm` decimal(10,2) DEFAULT NULL,
  `total_weight` decimal(10,2) DEFAULT NULL,
  `sg_tax` decimal(15,2) DEFAULT NULL,
  `supplier_1st_yen` decimal(15,2) DEFAULT NULL,
  `supplier_2nd_yen` decimal(15,2) DEFAULT NULL,
  `customer_1st_rm` decimal(15,2) DEFAULT NULL,
  `customer_2nd_rm` decimal(15,2) DEFAULT NULL,
  `estimated_arrival` date DEFAULT NULL,
  `add_carton1_width` float DEFAULT 0,
  `add_carton1_height` float DEFAULT 0,
  `add_carton1_length` float DEFAULT 0,
  `add_carton1_pcs` int(11) DEFAULT 0,
  `add_carton1_no` int(11) DEFAULT 0,
  `add_carton1_total_cbm` float DEFAULT 0,
  `add_carton2_width` float DEFAULT 0,
  `add_carton2_height` float DEFAULT 0,
  `add_carton2_length` float DEFAULT 0,
  `add_carton2_pcs` int(11) DEFAULT 0,
  `add_carton2_no` int(11) DEFAULT 0,
  `add_carton2_total_cbm` float DEFAULT 0,
  `add_carton3_width` float DEFAULT 0,
  `add_carton3_height` float DEFAULT 0,
  `add_carton3_length` float DEFAULT 0,
  `add_carton3_pcs` int(11) DEFAULT 0,
  `add_carton3_no` int(11) DEFAULT 0,
  `add_carton3_total_cbm` float DEFAULT 0,
  `add_carton4_width` float DEFAULT 0,
  `add_carton4_height` float DEFAULT 0,
  `add_carton4_length` float DEFAULT 0,
  `add_carton4_pcs` int(11) DEFAULT 0,
  `add_carton4_no` int(11) DEFAULT 0,
  `add_carton4_total_cbm` float DEFAULT 0,
  `add_carton5_width` float DEFAULT 0,
  `add_carton5_height` float DEFAULT 0,
  `add_carton5_length` float DEFAULT 0,
  `add_carton5_pcs` int(11) DEFAULT 0,
  `add_carton5_no` int(11) DEFAULT 0,
  `add_carton5_total_cbm` float DEFAULT 0,
  `add_carton6_width` decimal(10,2) DEFAULT NULL,
  `add_carton6_height` decimal(10,2) DEFAULT NULL,
  `add_carton6_length` decimal(10,2) DEFAULT NULL,
  `add_carton6_pcs` int(11) DEFAULT NULL,
  `add_carton6_no` int(11) DEFAULT NULL,
  `add_carton6_total_cbm` decimal(10,6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `price`
--

INSERT INTO `price` (`price_id`, `product_id`, `supplier_id`, `quantity`, `carton_width`, `carton_height`, `carton_length`, `pcs_per_carton`, `no_of_carton`, `designlogo`, `price`, `price_rm`, `shipping_price`, `additional_price`, `production_time`, `conversion_rate`, `total_price_yen`, `total_price_rm`, `deposit_50_yen`, `deposit_50_rm`, `cbm_carton`, `total_cbm`, `vm_carton`, `total_vm`, `total_weight`, `sg_tax`, `supplier_1st_yen`, `supplier_2nd_yen`, `customer_1st_rm`, `customer_2nd_rm`, `estimated_arrival`, `add_carton1_width`, `add_carton1_height`, `add_carton1_length`, `add_carton1_pcs`, `add_carton1_no`, `add_carton1_total_cbm`, `add_carton2_width`, `add_carton2_height`, `add_carton2_length`, `add_carton2_pcs`, `add_carton2_no`, `add_carton2_total_cbm`, `add_carton3_width`, `add_carton3_height`, `add_carton3_length`, `add_carton3_pcs`, `add_carton3_no`, `add_carton3_total_cbm`, `add_carton4_width`, `add_carton4_height`, `add_carton4_length`, `add_carton4_pcs`, `add_carton4_no`, `add_carton4_total_cbm`, `add_carton5_width`, `add_carton5_height`, `add_carton5_length`, `add_carton5_pcs`, `add_carton5_no`, `add_carton5_total_cbm`, `add_carton6_width`, `add_carton6_height`, `add_carton6_length`, `add_carton6_pcs`, `add_carton6_no`, `add_carton6_total_cbm`) VALUES
(57, 2, 2, 1000, 30.00, 20.00, 40.00, 50, 20, '', 5.00, NULL, 500.00, 0.00, '7', 0.0320, 5500.00, 171875.00, 2750.00, 85937.50, 0.024000, 0.614400, 4.80, 96.00, 200.00, 15468.75, 2750.00, 2750.00, 0.00, 0.00, '0000-00-00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0, 0, 0.000000),
(58, 2, 2, 1000, 30.00, 20.00, 40.00, 50, 20, 'Yes', 5.00, NULL, 500.00, 1.00, '7', 1.6000, 5501.00, 3438.13, 2750.50, 1719.06, 0.024000, 3261.448861, 4.80, 96.00, 200.00, 309.43, 2750.50, 2750.50, 0.00, 0.00, '2025-07-24', 1123, 123, 123, 123, 123, 2089.75, 123, 123, 123, 123, 123, 228.887, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 123.00, 123.00, 123.00, 123, 123, 228.886641),
(59, 1, 1, 1000, 30.00, 20.00, 40.00, 50, 20, '', 5.00, NULL, 500.00, 0.00, '7', 0.0320, 5500.00, 171875.00, 2750.00, 85937.50, 0.024000, 0.614400, 4.80, 96.00, 200.00, 15468.75, 2750.00, 2750.00, 0.00, 0.00, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0, 0, 0.000000),
(60, 2, 2, 1000, 30.00, 20.00, 40.00, 50, 20, '', 5.00, NULL, 500.00, 0.00, '7', 0.0320, 5500.00, 171875.00, 2750.00, 85937.50, 0.024000, 0.614400, 4.80, 96.00, 200.00, 15468.75, 2750.00, 2750.00, 0.00, 0.00, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0, 0, 0.000000),
(61, 1, 1, 1000, 30.00, 20.00, 40.00, 50, 20, '', 5.00, 156.250000, 500.00, 0.00, '7', 0.0320, 5500.00, 171875.00, 2750.00, 85937.50, 0.024000, 0.614400, 4.80, 96.00, 200.00, 15468.75, 2750.00, 2750.00, 0.00, 0.00, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0, 0, 0.000000);

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `name`, `category`, `subcategory`, `material`, `shape`, `size_volume`, `image_url`, `current_stock`, `reorder_level`, `is_active`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`) VALUES
(1, 'Plastic Bottle 500ml', 'Containers', 'Bottles', 'PET', 'Cylindrical', '500ml', NULL, 1000, 200, 1, '2025-07-22 06:10:53', '2025-07-22 06:10:53', NULL, NULL, NULL),
(2, 'Metal Can 350ml', 'Containers', 'Cans', 'Aluminum', 'Cylindrical', '350ml', NULL, 500, 100, 1, '2025-07-22 06:10:53', '2025-07-22 06:10:53', NULL, NULL, NULL),
(3, 'Glass Jar 250ml', 'Containers', 'Jars', 'Glass', 'Cylindrical', '250ml', NULL, 300, 50, 1, '2025-07-22 06:10:53', '2025-07-22 06:10:53', NULL, NULL, NULL);

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

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`schedule_id`, `staff_id`, `task_name`, `description`, `start_date`, `end_date`, `status`, `priority`, `created_at`, `updated_at`) VALUES
(1, 2, 'Monthly Sales Meeting', 'Review monthly sales targets', '2025-07-22 00:00:00', '2025-07-22 02:00:00', 'pending', 'high', '2025-07-22 06:10:53', '2025-07-22 06:10:53');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `session_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`session_id`, `staff_id`, `token`, `expires_at`) VALUES
(98, 1, '4cdf9a151c43cc7430f65da4016e99b2', '2025-07-23 19:28:37'),
(99, 1, '31ab3b92591fbddd757dd24a1d85cce9', '2025-07-24 06:34:10');

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
  `company_id` int(11) DEFAULT NULL,
  `staff_name` varchar(100) NOT NULL,
  `staff_designation` varchar(50) DEFAULT NULL,
  `staff_about` text DEFAULT NULL,
  `staff_number` varchar(20) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `role` enum('admin','manager','sales','warehouse') DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `company_id`, `staff_name`, `staff_designation`, `staff_about`, `staff_number`, `username`, `email`, `password_hash`, `role`, `is_active`, `last_login`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Admin User', 'System Administrator', 'My name is Tumulak, I come from Thailand, KL ni kawe legend, sawadikap', 'EMP001', 'admin', 'admin@company.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, '2025-07-24 11:34:10', '2025-07-22 06:10:52', '2025-07-24 05:07:03', NULL),
(2, 1, 'Sales Manager', 'Sales Manager', NULL, 'EMP002', 'salesmgr', 'sales@company.com', 'test', 'manager', 1, NULL, '2025-07-22 06:10:52', '2025-07-24 05:07:05', NULL),
(3, 1, 'John Sales', 'Sales Representative', NULL, 'EMP003', 'johns', 'john@company.com', '$2a$10$xJwLH5XrWrH2rYi2ZqBQ.eq3zq1p6bO9X9VdL9JfZ8dKv1QmY3XaK', 'sales', 1, NULL, '2025-07-22 06:10:52', '2025-07-24 05:07:06', NULL),
(4, 1, 'Warehouse Joe', 'Warehouse Staff', NULL, 'EMP004', 'warejoe', 'joe@company.com', '$2a$10$xJwLH5XrWrH2rYi2ZqBQ.eq3zq1p6bO9X9VdL9JfZ8dKv1QmY3XaK', 'warehouse', 1, NULL, '2025-07-22 06:10:52', '2025-07-24 05:07:07', NULL);

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
  `notes` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplier_id`, `supplier_name`, `supplier_contact_person`, `phone`, `address`, `email`, `notes`, `deleted_at`) VALUES
(1, 'Plastic Goods Co.', 'Chen San', '80-1234-5678', 'Guangzhou, China', 'sales@plasticgoods.com', 'Main plastic supplier', NULL),
(2, 'Metal Works Ltd.', 'Wang San', '80-8765-4321', 'Shenzhen, China', 'info@metalworks.com', 'Quality metal products', NULL);

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
  ADD KEY `idx_product_category` (`category`),
  ADD KEY `fk_product_created_by` (`created_by`),
  ADD KEY `fk_product_updated_by` (`updated_by`);

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
  ADD KEY `staff_id` (`staff_id`);

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
  ADD PRIMARY KEY (`staff_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_staff_company` (`company_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `commission`
--
ALTER TABLE `commission`
  MODIFY `commission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `invoice_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `price`
--
ALTER TABLE `price`
  MODIFY `price_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `shipping`
--
ALTER TABLE `shipping`
  MODIFY `shipping_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_product_created_by` FOREIGN KEY (`created_by`) REFERENCES `staff` (`staff_id`),
  ADD CONSTRAINT `fk_product_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `shipping`
--
ALTER TABLE `shipping`
  ADD CONSTRAINT `shipping_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`),
  ADD CONSTRAINT `shipping_ibfk_2` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`invoice_id`);

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `fk_staff_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
