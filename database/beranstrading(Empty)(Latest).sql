-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2025 at 05:55 PM
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
-- Database: `beranstrading`
--

-- --------------------------------------------------------

--
-- Table structure for table `backup_history`
--

CREATE TABLE `backup_history` (
  `id` int(11) NOT NULL,
  `backup_time` datetime DEFAULT NULL,
  `backup_type` enum('database','website') NOT NULL,
  `triggered_by` enum('manual','cron') NOT NULL DEFAULT 'manual',
  `status` varchar(50) DEFAULT NULL,
  `backup_file` longblob DEFAULT NULL,
  `error_message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `company_id` int(11) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `company_logo` varchar(255) NOT NULL,
  `company_tagline` varchar(255) NOT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_account_name` varchar(100) DEFAULT NULL,
  `bank_account_number` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL
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
  `customer_city` varchar(255) DEFAULT NULL,
  `customer_region` varchar(255) DEFAULT NULL,
  `customer_postcode` varchar(255) DEFAULT NULL,
  `customer_country` varchar(255) DEFAULT NULL,
  `customer_company_name` varchar(100) DEFAULT NULL,
  `customer_designation` varchar(50) DEFAULT NULL,
  `xero_relation` varchar(255) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `invoice_id` int(11) NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `price_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `commission_staff_id` int(11) DEFAULT NULL,
  `commission_percentage` decimal(5,2) DEFAULT 0.00,
  `total_amount` decimal(12,2) DEFAULT 0.00,
  `xero_relation` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `first_payment_date` timestamp NULL DEFAULT NULL,
  `status` enum('pending','completed') DEFAULT 'pending',
  `supplier_payments_total` decimal(15,2) DEFAULT 0.00 COMMENT 'Total supplier payments made in YEN',
  `shipping_payments_total` decimal(15,2) DEFAULT 0.00 COMMENT 'Total shipping payments made in YEN',
  `supplier_payment_notes` text DEFAULT NULL COMMENT 'Notes about supplier payments',
  `shipping_payment_notes` text DEFAULT NULL COMMENT 'Notes about shipping payments',
  `payment_history_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON array of payment history for detailed tracking' CHECK (json_valid(`payment_history_json`)),
  `commission_paid_amount` decimal(15,2) DEFAULT 0.00 COMMENT 'Amount of commission already paid to staff',
  `commission_payment_date` timestamp NULL DEFAULT NULL COMMENT 'Date when commission was last paid',
  `commission_payment_notes` text DEFAULT NULL COMMENT 'Notes about commission payments'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_item`
--

CREATE TABLE `invoice_item` (
  `invoice_item_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `material`
--

CREATE TABLE `material` (
  `material_id` int(11) NOT NULL,
  `subcategory_id` int(11) NOT NULL,
  `material_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_history`
--

CREATE TABLE `payment_history` (
  `payment_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `amount_paid` decimal(12,2) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
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
  `carton_weight` decimal(10,2) DEFAULT NULL,
  `pcs_per_carton` int(11) DEFAULT NULL,
  `no_of_carton` int(11) DEFAULT NULL,
  `cbm_carton` decimal(15,6) DEFAULT NULL,
  `new_price_yen` decimal(12,2) DEFAULT NULL,
  `new_moq_quantity` int(11) DEFAULT NULL,
  `new_shipping_moq_yen` decimal(12,2) DEFAULT NULL,
  `new_additional_price_moq_yen` decimal(12,2) DEFAULT NULL,
  `new_conversion_rate` decimal(12,4) DEFAULT NULL,
  `new_unit_price_yen` decimal(12,2) DEFAULT NULL,
  `new_freight_method` varchar(100) DEFAULT NULL,
  `new_total_cbm_moq` decimal(12,3) DEFAULT NULL,
  `new_total_weight_moq` decimal(12,2) DEFAULT NULL,
  `new_unit_price_rm` decimal(12,2) DEFAULT NULL,
  `new_unit_freight_cost_rm` decimal(12,2) DEFAULT NULL,
  `new_unit_profit_rm` decimal(12,2) DEFAULT NULL,
  `new_selling_price` decimal(12,2) DEFAULT NULL,
  `add_carton1_width` decimal(10,2) DEFAULT NULL,
  `add_carton1_height` decimal(10,2) DEFAULT NULL,
  `add_carton1_length` decimal(10,2) DEFAULT NULL,
  `add_carton1_pcs` int(11) DEFAULT NULL,
  `add_carton1_weight` decimal(10,2) DEFAULT NULL,
  `add_carton1_no` int(11) DEFAULT NULL,
  `add_carton1_total_cbm` decimal(15,6) DEFAULT NULL,
  `add_carton2_width` decimal(10,2) DEFAULT NULL,
  `add_carton2_height` decimal(10,2) DEFAULT NULL,
  `add_carton2_length` decimal(10,2) DEFAULT NULL,
  `add_carton2_pcs` int(11) DEFAULT NULL,
  `add_carton2_weight` decimal(10,2) DEFAULT NULL,
  `add_carton2_no` int(11) DEFAULT NULL,
  `add_carton2_total_cbm` decimal(15,6) DEFAULT NULL,
  `add_carton3_width` decimal(10,2) DEFAULT NULL,
  `add_carton3_height` decimal(10,2) DEFAULT NULL,
  `add_carton3_length` decimal(10,2) DEFAULT NULL,
  `add_carton3_pcs` int(11) DEFAULT NULL,
  `add_carton3_weight` decimal(10,2) DEFAULT NULL,
  `add_carton3_no` int(11) DEFAULT NULL,
  `add_carton3_total_cbm` decimal(15,6) DEFAULT NULL,
  `add_carton4_width` decimal(10,2) DEFAULT NULL,
  `add_carton4_height` decimal(10,2) DEFAULT NULL,
  `add_carton4_length` decimal(10,2) DEFAULT NULL,
  `add_carton4_pcs` int(11) DEFAULT NULL,
  `add_carton4_weight` decimal(10,2) DEFAULT NULL,
  `add_carton4_no` int(11) DEFAULT NULL,
  `add_carton4_total_cbm` decimal(15,6) DEFAULT NULL,
  `add_carton5_width` decimal(10,2) DEFAULT NULL,
  `add_carton5_height` decimal(10,2) DEFAULT NULL,
  `add_carton5_length` decimal(10,2) DEFAULT NULL,
  `add_carton5_pcs` int(11) DEFAULT NULL,
  `add_carton5_weight` decimal(10,2) DEFAULT NULL,
  `add_carton5_no` int(11) DEFAULT NULL,
  `add_carton5_total_cbm` decimal(15,6) DEFAULT NULL,
  `add_carton6_width` decimal(10,2) DEFAULT NULL,
  `add_carton6_height` decimal(10,2) DEFAULT NULL,
  `add_carton6_length` decimal(10,2) DEFAULT NULL,
  `add_carton6_pcs` int(11) DEFAULT NULL,
  `add_carton6_weight` decimal(10,2) DEFAULT NULL,
  `add_carton6_no` int(11) DEFAULT NULL,
  `add_carton6_total_cbm` decimal(15,6) DEFAULT NULL,
  `designlogo` text DEFAULT NULL,
  `price` decimal(10,6) DEFAULT NULL,
  `price_rm` decimal(15,6) DEFAULT NULL,
  `shipping_price` decimal(10,6) DEFAULT NULL,
  `additional_price` decimal(10,6) DEFAULT NULL,
  `conversion_rate` decimal(10,4) DEFAULT NULL,
  `total_price_yen` decimal(15,2) DEFAULT NULL,
  `total_price_rm` decimal(15,2) DEFAULT NULL,
  `deposit_50_yen` decimal(15,2) DEFAULT NULL,
  `deposit_50_rm` decimal(15,2) DEFAULT NULL,
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
  `final_selling_total` decimal(10,2) DEFAULT NULL,
  `final_total_price` decimal(10,2) DEFAULT NULL,
  `final_unit_price` decimal(10,2) DEFAULT NULL,
  `final_profit_per_unit_rm` decimal(10,2) DEFAULT NULL,
  `final_total_profit` decimal(10,2) DEFAULT NULL,
  `final_profit_percent` decimal(5,2) DEFAULT NULL,
  `zakat` decimal(15,2) DEFAULT 0.00,
  `final_selling_unit` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `price_shipping`
--

CREATE TABLE `price_shipping` (
  `shipping_price_id` int(11) NOT NULL,
  `shipping_code` varchar(10) NOT NULL,
  `freight_rate` decimal(10,2) DEFAULT 0.00,
  `shipping_name` varchar(100) DEFAULT NULL,
  `delivery_days` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `price_shipping_totals`
--

CREATE TABLE `price_shipping_totals` (
  `id` int(11) NOT NULL,
  `shipping_price_id` int(11) NOT NULL,
  `price_id` int(11) NOT NULL,
  `price_total_sea_shipping` decimal(10,2) DEFAULT 0.00,
  `price_total_air_shipping_vm` decimal(10,2) DEFAULT 0.00,
  `price_total_air_shipping_kg` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_code` varchar(20) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `subcategory_id` int(11) DEFAULT NULL,
  `material_id` int(11) DEFAULT NULL,
  `product_type_id` int(11) DEFAULT NULL,
  `shape` varchar(50) DEFAULT NULL,
  `size_volume` varchar(50) DEFAULT NULL,
  `size_1` varchar(50) DEFAULT NULL,
  `size_2` varchar(50) DEFAULT NULL,
  `size_3` varchar(50) DEFAULT NULL,
  `variant` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `production_lead_time` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `short_desc` text DEFAULT NULL,
  `long_desc` text DEFAULT NULL,
  `current_stock` int(11) DEFAULT 0,
  `reorder_level` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `xero_relation` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_type`
--

CREATE TABLE `product_type` (
  `product_type_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL
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
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `section_id` int(11) NOT NULL,
  `section_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 34, 'e55149f30c898a0e693efd01a81f449a', '2025-09-14 00:54:47');

-- --------------------------------------------------------

--
-- Table structure for table `site_config`
--

CREATE TABLE `site_config` (
  `company_id` int(11) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `company_logo` varchar(255) NOT NULL,
  `company_tagline` varchar(255) NOT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_account_name` varchar(100) DEFAULT NULL,
  `bank_account_number` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `favicon` varchar(255) NOT NULL,
  `logo_light` varchar(255) NOT NULL,
  `logo_dark` varchar(255) NOT NULL,
  `xero_refresh_token` varchar(255) DEFAULT NULL,
  `xero_tenant_id` varchar(255) DEFAULT NULL,
  `xero_ttl` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_config`
--

INSERT INTO `site_config` (`company_id`, `company_name`, `company_logo`, `company_tagline`, `bank_name`, `bank_account_name`, `bank_account_number`, `address`, `contact`, `email`, `favicon`, `logo_light`, `logo_dark`, `xero_refresh_token`, `xero_tenant_id`, `xero_ttl`) VALUES
(1, 'Berans Trading', 'berans.png', 'In Berans, We Trust', 'Tokyo Bank', 'Maybank', '123456789', 'Malaysia, Johor', '03-1234-5678', 'beranstrading@gmail.com', 'favicon_1756891365.png', 'logolight1756891365.png', 'logodark_1756891365.png', 'K39M9deDg4IuwFBJbGLIO3S2VAAXU7BNF6ezTX7OOIA', '26d074dc-81b8-4796-9709-6a79846b9532', '2025-09-09 16:22:07');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `staff_name` varchar(100) NOT NULL,
  `staff_profile_picture` varchar(255) DEFAULT NULL,
  `staff_designation` varchar(50) DEFAULT NULL,
  `staff_about` text DEFAULT NULL,
  `staff_number` varchar(20) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `role` varchar(50) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `remember_token` varchar(255) DEFAULT NULL,
  `remember_expiry` varchar(255) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `company_id`, `staff_name`, `staff_profile_picture`, `staff_designation`, `staff_about`, `staff_number`, `username`, `email`, `password_hash`, `role`, `is_active`, `remember_token`, `remember_expiry`, `last_login`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Admin User', 'profile_1757777234.png', 'System Administrator', 'My name is Tumulak, I come from Thailand, KL ni kawe legend, sawadikap', 'EMP001', 'admin', 'admin@company.com', '$2y$10$mKEA2gXwcrNv5GeEDbD2qe8/IFYcUn08EOGPsAWUo5pLy1AVOSubG', 'admin', 1, NULL, NULL, '2025-09-13 23:03:36', '2025-07-22 06:10:52', '2025-09-13 15:27:14', NULL),
(2, 1, 'Sales Manager', '', 'Sales Manager', NULL, 'EMP002', 'salesmgr', 'sales@company.com', 'test', 'manager', 1, NULL, NULL, NULL, '2025-07-22 06:10:52', '2025-07-24 05:07:05', NULL),
(34, 1, 'Fahmi', '', 'System Administrator', 'BRO', 'EMP005', 'fahmio', 'mohdfahmi1122@gmail.com', '$2y$10$SWRKB6J3XS1RIFBx0cONnOQLpyJFexdPcDaLey66XvffB7EYdiZvi', 'admin', 1, NULL, NULL, '2025-09-13 23:54:47', '2025-07-26 05:14:52', '2025-09-13 15:54:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subcategory`
--

CREATE TABLE `subcategory` (
  `subcategory_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `subcategory_name` varchar(100) NOT NULL
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
  `city` varchar(255) NOT NULL,
  `region` varchar(255) NOT NULL,
  `postcode` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `xero_relation` varchar(255) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `backup_history`
--
ALTER TABLE `backup_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`),
  ADD KEY `section_id` (`section_id`);

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
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `price_id` (`price_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `fk_commission_staff` (`commission_staff_id`);

--
-- Indexes for table `invoice_item`
--
ALTER TABLE `invoice_item`
  ADD PRIMARY KEY (`invoice_item_id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`material_id`),
  ADD KEY `subcategory_id` (`subcategory_id`);

--
-- Indexes for table `payment_history`
--
ALTER TABLE `payment_history`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `idx_payment_history_invoice` (`invoice_id`);

--
-- Indexes for table `price`
--
ALTER TABLE `price`
  ADD PRIMARY KEY (`price_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `price_shipping`
--
ALTER TABLE `price_shipping`
  ADD PRIMARY KEY (`shipping_price_id`),
  ADD UNIQUE KEY `shipping_code` (`shipping_code`);

--
-- Indexes for table `price_shipping_totals`
--
ALTER TABLE `price_shipping_totals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipping_price_id` (`shipping_price_id`),
  ADD KEY `price_id` (`price_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `fk_product_created_by` (`created_by`),
  ADD KEY `fk_product_updated_by` (`updated_by`),
  ADD KEY `fk_product_section` (`section_id`),
  ADD KEY `fk_product_category` (`category_id`),
  ADD KEY `fk_product_subcategory` (`subcategory_id`),
  ADD KEY `fk_product_material` (`material_id`),
  ADD KEY `fk_product_type` (`product_type_id`);

--
-- Indexes for table `product_type`
--
ALTER TABLE `product_type`
  ADD PRIMARY KEY (`product_type_id`),
  ADD KEY `material_id` (`material_id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`section_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `site_config`
--
ALTER TABLE `site_config`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_staff_company` (`company_id`);

--
-- Indexes for table `subcategory`
--
ALTER TABLE `subcategory`
  ADD PRIMARY KEY (`subcategory_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `backup_history`
--
ALTER TABLE `backup_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `invoice_item`
--
ALTER TABLE `invoice_item`
  MODIFY `invoice_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material`
--
ALTER TABLE `material`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_history`
--
ALTER TABLE `payment_history`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `price`
--
ALTER TABLE `price`
  MODIFY `price_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `price_shipping`
--
ALTER TABLE `price_shipping`
  MODIFY `shipping_price_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `price_shipping_totals`
--
ALTER TABLE `price_shipping_totals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_type`
--
ALTER TABLE `product_type`
  MODIFY `product_type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `site_config`
--
ALTER TABLE `site_config`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `subcategory`
--
ALTER TABLE `subcategory`
  MODIFY `subcategory_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `section` (`section_id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `fk_commission_staff` FOREIGN KEY (`commission_staff_id`) REFERENCES `staff` (`staff_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`price_id`) REFERENCES `price` (`price_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_ibfk_3` FOREIGN KEY (`company_id`) REFERENCES `site_config` (`company_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_ibfk_4` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice_item`
--
ALTER TABLE `invoice_item`
  ADD CONSTRAINT `invoice_item_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`invoice_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `material`
--
ALTER TABLE `material`
  ADD CONSTRAINT `material_ibfk_1` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategory` (`subcategory_id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_history`
--
ALTER TABLE `payment_history`
  ADD CONSTRAINT `payment_history_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`invoice_id`);

--
-- Constraints for table `price`
--
ALTER TABLE `price`
  ADD CONSTRAINT `price_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
  ADD CONSTRAINT `price_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`);

--
-- Constraints for table `price_shipping_totals`
--
ALTER TABLE `price_shipping_totals`
  ADD CONSTRAINT `price_shipping_totals_ibfk_1` FOREIGN KEY (`shipping_price_id`) REFERENCES `price_shipping` (`shipping_price_id`),
  ADD CONSTRAINT `price_shipping_totals_ibfk_2` FOREIGN KEY (`price_id`) REFERENCES `price` (`price_id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_product_created_by` FOREIGN KEY (`created_by`) REFERENCES `staff` (`staff_id`),
  ADD CONSTRAINT `fk_product_material` FOREIGN KEY (`material_id`) REFERENCES `material` (`material_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_product_section` FOREIGN KEY (`section_id`) REFERENCES `section` (`section_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_product_subcategory` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategory` (`subcategory_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_product_type` FOREIGN KEY (`product_type_id`) REFERENCES `product_type` (`product_type_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_product_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `product_type`
--
ALTER TABLE `product_type`
  ADD CONSTRAINT `product_type_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `material` (`material_id`) ON DELETE CASCADE;

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
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `fk_staff_company` FOREIGN KEY (`company_id`) REFERENCES `site_config` (`company_id`) ON DELETE SET NULL;

--
-- Constraints for table `subcategory`
--
ALTER TABLE `subcategory`
  ADD CONSTRAINT `subcategory_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
