-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 14, 2025 at 03:24 AM
-- Server version: 8.0.40
-- PHP Version: 8.3.16

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
  `id` int NOT NULL,
  `backup_time` datetime DEFAULT NULL,
  `backup_type` enum('database','website') COLLATE utf8mb4_general_ci NOT NULL,
  `triggered_by` enum('manual','cron') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'manual',
  `status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `backup_file` longblob,
  `error_message` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int NOT NULL,
  `section_id` int NOT NULL,
  `category_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int NOT NULL,
  `customer_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `customer_phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_address` text COLLATE utf8mb4_general_ci,
  `customer_city` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_region` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_postcode` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_country` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_company_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_designation` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `xero_relation` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `invoice_id` int NOT NULL,
  `invoice_number` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `price_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `company_id` int NOT NULL,
  `staff_id` int NOT NULL,
  `commission_staff_id` int DEFAULT NULL,
  `commission_percentage` decimal(5,2) DEFAULT '0.00',
  `total_amount` decimal(12,2) DEFAULT '0.00',
  `xero_relation` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `first_payment_date` timestamp NULL DEFAULT NULL,
  `status` enum('pending','completed') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `supplier_payments_total` decimal(15,2) DEFAULT '0.00' COMMENT 'Total supplier payments made in YEN',
  `shipping_payments_total` decimal(15,2) DEFAULT '0.00' COMMENT 'Total shipping payments made in YEN',
  `supplier_payment_notes` text COLLATE utf8mb4_general_ci COMMENT 'Notes about supplier payments',
  `shipping_payment_notes` text COLLATE utf8mb4_general_ci COMMENT 'Notes about shipping payments',
  `payment_history_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin COMMENT 'JSON array of payment history for detailed tracking',
  `commission_paid_amount` decimal(15,2) DEFAULT '0.00' COMMENT 'Amount of commission already paid to staff',
  `commission_payment_date` timestamp NULL DEFAULT NULL COMMENT 'Date when commission was last paid',
  `commission_payment_notes` text COLLATE utf8mb4_general_ci COMMENT 'Notes about commission payments'
) ;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_item`
--

CREATE TABLE `invoice_item` (
  `invoice_item_id` int NOT NULL,
  `invoice_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `quantity` int NOT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `material`
--

CREATE TABLE `material` (
  `material_id` int NOT NULL,
  `subcategory_id` int NOT NULL,
  `material_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_history`
--

CREATE TABLE `payment_history` (
  `payment_id` int NOT NULL,
  `invoice_id` int NOT NULL,
  `amount_paid` decimal(12,2) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `price`
--

CREATE TABLE `price` (
  `price_id` int NOT NULL,
  `product_id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `quantity` int DEFAULT NULL,
  `carton_width` decimal(10,2) DEFAULT NULL,
  `carton_height` decimal(10,2) DEFAULT NULL,
  `carton_length` decimal(10,2) DEFAULT NULL,
  `carton_weight` decimal(10,2) DEFAULT NULL,
  `pcs_per_carton` int DEFAULT NULL,
  `no_of_carton` int DEFAULT NULL,
  `cbm_carton` decimal(15,6) DEFAULT NULL,
  `new_price_yen` decimal(12,2) DEFAULT NULL,
  `new_moq_quantity` int DEFAULT NULL,
  `new_shipping_moq_yen` decimal(12,2) DEFAULT NULL,
  `new_additional_price_moq_yen` decimal(12,2) DEFAULT NULL,
  `new_conversion_rate` decimal(12,4) DEFAULT NULL,
  `new_unit_price_yen` decimal(12,2) DEFAULT NULL,
  `new_freight_method` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `new_total_cbm_moq` decimal(12,3) DEFAULT NULL,
  `new_total_weight_moq` decimal(12,2) DEFAULT NULL,
  `new_unit_price_rm` decimal(12,2) DEFAULT NULL,
  `new_unit_freight_cost_rm` decimal(12,2) DEFAULT NULL,
  `new_unit_profit_rm` decimal(12,2) DEFAULT NULL,
  `new_selling_price` decimal(12,2) DEFAULT NULL,
  `add_carton1_width` decimal(10,2) DEFAULT NULL,
  `add_carton1_height` decimal(10,2) DEFAULT NULL,
  `add_carton1_length` decimal(10,2) DEFAULT NULL,
  `add_carton1_pcs` int DEFAULT NULL,
  `add_carton1_weight` decimal(10,2) DEFAULT NULL,
  `add_carton1_no` int DEFAULT NULL,
  `add_carton1_total_cbm` decimal(15,6) DEFAULT NULL,
  `add_carton2_width` decimal(10,2) DEFAULT NULL,
  `add_carton2_height` decimal(10,2) DEFAULT NULL,
  `add_carton2_length` decimal(10,2) DEFAULT NULL,
  `add_carton2_pcs` int DEFAULT NULL,
  `add_carton2_weight` decimal(10,2) DEFAULT NULL,
  `add_carton2_no` int DEFAULT NULL,
  `add_carton2_total_cbm` decimal(15,6) DEFAULT NULL,
  `add_carton3_width` decimal(10,2) DEFAULT NULL,
  `add_carton3_height` decimal(10,2) DEFAULT NULL,
  `add_carton3_length` decimal(10,2) DEFAULT NULL,
  `add_carton3_pcs` int DEFAULT NULL,
  `add_carton3_weight` decimal(10,2) DEFAULT NULL,
  `add_carton3_no` int DEFAULT NULL,
  `add_carton3_total_cbm` decimal(15,6) DEFAULT NULL,
  `add_carton4_width` decimal(10,2) DEFAULT NULL,
  `add_carton4_height` decimal(10,2) DEFAULT NULL,
  `add_carton4_length` decimal(10,2) DEFAULT NULL,
  `add_carton4_pcs` int DEFAULT NULL,
  `add_carton4_weight` decimal(10,2) DEFAULT NULL,
  `add_carton4_no` int DEFAULT NULL,
  `add_carton4_total_cbm` decimal(15,6) DEFAULT NULL,
  `add_carton5_width` decimal(10,2) DEFAULT NULL,
  `add_carton5_height` decimal(10,2) DEFAULT NULL,
  `add_carton5_length` decimal(10,2) DEFAULT NULL,
  `add_carton5_pcs` int DEFAULT NULL,
  `add_carton5_weight` decimal(10,2) DEFAULT NULL,
  `add_carton5_no` int DEFAULT NULL,
  `add_carton5_total_cbm` decimal(15,6) DEFAULT NULL,
  `add_carton6_width` decimal(10,2) DEFAULT NULL,
  `add_carton6_height` decimal(10,2) DEFAULT NULL,
  `add_carton6_length` decimal(10,2) DEFAULT NULL,
  `add_carton6_pcs` int DEFAULT NULL,
  `add_carton6_weight` decimal(10,2) DEFAULT NULL,
  `add_carton6_no` int DEFAULT NULL,
  `add_carton6_total_cbm` decimal(15,6) DEFAULT NULL,
  `designlogo` text COLLATE utf8mb4_general_ci,
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
  `zakat` decimal(15,2) DEFAULT '0.00',
  `final_selling_unit` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `price_shipping`
--

CREATE TABLE `price_shipping` (
  `shipping_price_id` int NOT NULL,
  `shipping_code` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `freight_rate` decimal(10,2) DEFAULT '0.00',
  `shipping_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `delivery_days` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `price_shipping` (`shipping_price_id`, `shipping_code`, `freight_rate`, `shipping_name`) VALUES
(29, 'M1', 420.00, 'Sea Normal Goods'),
(30, 'M2', 390.00, 'Sea Sensitive Goods'),
(31, 'S1', 285.00, 'SG Sea Normal Goods'),
(32, 'S2', 285.00, 'SG Sea Sensitive Goods'),
(35, 'M3a', 17.00, 'Air VM Normal Goods'),
(36, 'M3b', 17.00, 'Air KG Normal Goods'),
(37, 'M4a', 19.00, 'Air VM Sensitive Goods'),
(38, 'M4b', 19.00, 'Air KG Sensitive Goods'),
(39, 'S3a', 24.00, 'SG Air VM Normal Goods'),
(40, 'S3b', 24.00, 'SG Air KG Normal Goods'),
(41, 'S4a', 24.00, 'SG Air VM Sensitive Goods'),
(42, 'S4b', 24.00, 'SG Air KG Sensitive Goods');

-- --------------------------------------------------------

--
-- Table structure for table `price_shipping_totals`
--

CREATE TABLE `price_shipping_totals` (
  `id` int NOT NULL,
  `shipping_price_id` int NOT NULL,
  `price_id` int NOT NULL,
  `price_total_sea_shipping` decimal(10,2) DEFAULT '0.00',
  `price_total_air_shipping_vm` decimal(10,2) DEFAULT '0.00',
  `price_total_air_shipping_kg` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int NOT NULL,
  `product_code` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `section_id` int DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `subcategory_id` int DEFAULT NULL,
  `material_id` int DEFAULT NULL,
  `product_type_id` int DEFAULT NULL,
  `shape` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `size_volume` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `size_1` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `size_2` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `size_3` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `variant` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `production_lead_time` int DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `short_desc` text COLLATE utf8mb4_general_ci,
  `long_desc` text COLLATE utf8mb4_general_ci,
  `current_stock` int DEFAULT '0',
  `reorder_level` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `xero_relation` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_type`
--

CREATE TABLE `product_type` (
  `product_type_id` int NOT NULL,
  `material_id` int NOT NULL,
  `product_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `schedule_id` int NOT NULL,
  `staff_id` int NOT NULL,
  `task_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `priority` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `section_id` int NOT NULL,
  `section_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `session_id` int NOT NULL,
  `staff_id` int NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `site_config`
--

CREATE TABLE `site_config` (
  `company_id` int NOT NULL,
  `company_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `company_logo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `company_tagline` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `bank_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bank_account_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bank_account_number` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_general_ci,
  `contact` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `favicon` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `logo_light` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `logo_dark` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `xero_refresh_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `xero_tenant_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `xero_ttl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_config`
--

INSERT INTO `site_config` (`company_id`, `company_name`, `company_logo`, `company_tagline`, `bank_name`, `bank_account_name`, `bank_account_number`, `address`, `contact`, `email`, `favicon`, `logo_light`, `logo_dark`, `xero_refresh_token`, `xero_tenant_id`, `xero_ttl`) VALUES
(1, 'Berans Trading', 'default_logo.png', 'In Berans, We Trust', 'Tokyo Bank', 'Maybank', '123456789', 'Malaysia, Johor', '03-1234-5678', 'beranstrading@gmail.com', 'default_favicon.png', 'default_logolight.png', 'default_logodark.png', 'K39M9deDg4IuwFBJbGLIO3S2VAAXU7BNF6ezTX7OOIA', '26d074dc-81b8-4796-9709-6a79846b9532', '2025-09-14 03:24:01');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int NOT NULL,
  `company_id` int DEFAULT NULL,
  `staff_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `staff_profile_picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'default.png',
  `staff_designation` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `staff_about` text COLLATE utf8mb4_general_ci,
  `staff_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `is_superadmin` int DEFAULT '0',
  `remember_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `remember_expiry` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `company_id`, `staff_name`, `staff_profile_picture`, `staff_designation`, `staff_about`, `staff_number`, `username`, `email`, `password_hash`, `role`, `is_active`, `remember_token`, `remember_expiry`, `last_login`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Admin User', 'default.png', 'System Administrator', 'My name is Tumulak, I come from Thailand, KL ni kawe legend, sawadikap', 'EMP001', 'admin', 'admin@company.com', '$2y$10$mKEA2gXwcrNv5GeEDbD2qe8/IFYcUn08EOGPsAWUo5pLy1AVOSubG', 'admin', 1, NULL, NULL, '2025-09-13 23:03:36', '2025-07-22 06:10:52', '2025-09-14 03:23:21', NULL),
(2, 1, 'Sales Manager', 'default.png', 'Sales Manager', NULL, 'EMP002', 'salesmgr', 'sales@company.com', 'test', 'manager', 1, NULL, NULL, NULL, '2025-07-22 06:10:52', '2025-09-14 03:23:23', NULL),
(34, 1, 'Fahmi', 'default.png', 'System Administrator', 'BRO', 'EMP005', 'fahmio', 'mohdfahmi1122@gmail.com', '$2y$10$SWRKB6J3XS1RIFBx0cONnOQLpyJFexdPcDaLey66XvffB7EYdiZvi', 'admin', 1, NULL, NULL, '2025-09-13 23:54:47', '2025-07-26 05:14:52', '2025-09-14 03:23:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subcategory`
--

CREATE TABLE `subcategory` (
  `subcategory_id` int NOT NULL,
  `category_id` int NOT NULL,
  `subcategory_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int NOT NULL,
  `supplier_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `supplier_contact_person` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_general_ci,
  `city` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `region` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `postcode` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_general_ci,
  `xero_relation` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
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
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `invoice_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_item`
--
ALTER TABLE `invoice_item`
  MODIFY `invoice_item_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material`
--
ALTER TABLE `material`
  MODIFY `material_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_history`
--
ALTER TABLE `payment_history`
  MODIFY `payment_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `price`
--
ALTER TABLE `price`
  MODIFY `price_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `price_shipping`
--
ALTER TABLE `price_shipping`
  MODIFY `shipping_price_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `price_shipping_totals`
--
ALTER TABLE `price_shipping_totals`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_type`
--
ALTER TABLE `product_type`
  MODIFY `product_type_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `schedule_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `section_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `session_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `site_config`
--
ALTER TABLE `site_config`
  MODIFY `company_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `subcategory`
--
ALTER TABLE `subcategory`
  MODIFY `subcategory_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int NOT NULL AUTO_INCREMENT;

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
