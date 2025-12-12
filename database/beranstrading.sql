-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 12, 2025 at 03:51 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

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

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `section_id`, `category_name`) VALUES
(1, 1, 'Fries');

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `company_id` int NOT NULL,
  `company_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `company_logo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `company_tagline` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `bank_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bank_account_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bank_account_number` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_general_ci,
  `contact` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
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

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `customer_name`, `customer_phone`, `customer_address`, `customer_city`, `customer_region`, `customer_postcode`, `customer_country`, `customer_company_name`, `customer_designation`, `xero_relation`, `deleted_at`) VALUES
(1, 'Mohamad Izzat Amir bin Zairul', '0147343662', '29, Lorong BLM 3/20\r\nBandar Laguna', 'Sungai Petanii', 'Kedahi', '080009', 'Malaysiaa', 'Parallaxing', 'CEO', '85c67d4e-a58a-4b4e-bc3b-408808950553', '2025-09-23 07:01:49'),
(2, 'AYAM GEPUK LEGEND', '+60199397038', '13 JALAN TANJUNG SD 13/2', 'KUALA LUMPUR', 'SELANGOR', '52200', 'MALAYSIA', 'AYAM GEPUK LEGEND', 'OWNER', 'd87bd5b5-2812-4c33-b2d4-587d52133ae3', NULL),
(3, 'VICTORY RESTAURANT PTE LTD', '+6585115786', 'Victory Restaurant, 701 North Bridge Road', '701', 'North Bridge Road', '198677', 'Singapore', 'Victory Restaurant Pte Ltd', 'Owner', '180f2105-c974-4e03-8fde-c0a17a388a7e', NULL),
(4, 'Muhammad Yusuf', '+60197766325', 'Prima Regency', 'Johor Bahru', 'Masai', '81750', 'Malaysia', 'Berans Trading', 'Owner 2', '4d56027e-b56a-4257-a221-847f3f4f1428', '2025-09-24 12:17:06'),
(5, 'MOHD AFEEF', '+60197766325', 'Prima Regency', 'Johor Bahru', 'Masai', '81750', 'Malaysia', 'Berans Trading', 'Owner 3', '45cb75fd-6357-4f51-aa76-6462a1137f59', '2025-09-24 12:26:24'),
(6, 'HAI WORLD', '0123456789', '29, Lorong BLM 3/20\r\nBandar Laguna Merbok', 'Sungai Petani', 'Kedah', '08000', 'Malaysia', 'Polo Troll', 'CEO', 'e008ee3a-415a-44af-bc95-b1734112c0c1', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `folder_catalogue`
--

CREATE TABLE `folder_catalogue` (
  `folder_id` int NOT NULL,
  `folder_name` varchar(255) NOT NULL,
  `section_id` int NOT NULL,
  `category_id` int DEFAULT NULL,
  `subcategory_id` int DEFAULT NULL,
  `listItems` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `discount_type` enum('none','percentage','amount') COLLATE utf8mb4_general_ci DEFAULT 'none',
  `discount_value` decimal(10,2) DEFAULT '0.00',
  `discount_amount` decimal(12,2) DEFAULT '0.00',
  `subtotal` decimal(12,2) DEFAULT '0.00',
  `grand_total` decimal(12,2) DEFAULT '0.00',
  `total_amount` decimal(12,2) DEFAULT '0.00',
  `xero_relation` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `first_payment_date` timestamp NULL DEFAULT NULL,
  `status` enum('pending','completed') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `completion_date` timestamp NULL DEFAULT NULL COMMENT 'Timestamp when order was marked as completed',
  `production_status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Production status: NULL (not started), started (production in progress)',
  `production_start_date` timestamp NULL DEFAULT NULL COMMENT 'Timestamp when order production was started',
  `profit_loss_status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'pending' COMMENT 'Profit/Loss tracking status: pending, completed (independent from main order status)',
  `supplier_payments_total` decimal(15,2) DEFAULT '0.00' COMMENT 'Total supplier payments made in YEN',
  `shipping_payments_total` decimal(15,2) DEFAULT '0.00' COMMENT 'Total shipping payments made in YEN',
  `supplier_payment_notes` text COLLATE utf8mb4_general_ci COMMENT 'Notes about supplier payments',
  `shipping_payment_notes` text COLLATE utf8mb4_general_ci COMMENT 'Notes about shipping payments',
  `payment_history_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin COMMENT 'JSON array of payment history for detailed tracking',
  `commission_paid_amount` decimal(15,2) DEFAULT '0.00' COMMENT 'Amount of commission already paid to staff',
  `commission_payment_date` timestamp NULL DEFAULT NULL COMMENT 'Date when commission was last paid',
  `zakat_amount` decimal(15,4) DEFAULT '0.0000' COMMENT 'Zakat 10% of total profit',
  `commission_payment_notes` text COLLATE utf8mb4_general_ci COMMENT 'Notes about commission payments'
) ;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`invoice_id`, `invoice_number`, `price_id`, `customer_id`, `company_id`, `staff_id`, `commission_staff_id`, `commission_percentage`, `discount_type`, `discount_value`, `discount_amount`, `subtotal`, `grand_total`, `total_amount`, `xero_relation`, `created_at`, `updated_at`, `first_payment_date`, `status`, `completion_date`, `production_status`, `production_start_date`, `profit_loss_status`, `supplier_payments_total`, `shipping_payments_total`, `supplier_payment_notes`, `shipping_payment_notes`, `payment_history_json`, `commission_paid_amount`, `commission_payment_date`, `zakat_amount`, `commission_payment_notes`) VALUES
(16, 'INV-20250925-001', 3, 3, 1, 1, 1, 10.00, 'percentage', 10.00, 400.00, 4000.00, 3600.00, 3600.00, NULL, '2025-09-24 16:11:26', '2025-11-11 04:58:27', '2025-09-27 12:09:23', 'pending', NULL, NULL, NULL, 'pending', 0.00, 0.00, NULL, NULL, NULL, 0.00, NULL, 114.5920, NULL),
(17, 'INV-20250925-002', 3, 6, 1, 1, 1, 10.00, 'percentage', 20.00, 400.00, 2000.00, 1600.00, 1600.00, NULL, '2025-09-24 16:17:23', '2025-11-11 07:57:01', '2025-09-24 16:21:13', 'completed', '2025-11-11 07:57:01', 'started', '2025-11-11 07:46:40', 'completed', 244.62, 1076.04, '2025-11-11 13:06:40 - Supplier payment: RM 151.00 (¥244.62 @ rate 1.6200)\n', '2025-11-11 13:06:36 - Shipping payment: ¥1,076.04 - 1,076.0400 \n', '[{\"type\":\"commission\",\"amount\":160,\"description\":\"\",\"staff_name\":\"Admin User\",\"date\":\"2025-11-11 13:06:27\",\"timestamp\":1762837587},{\"type\":\"shipping\",\"amount\":\"1076.04\",\"description\":\"1,076.0400 \",\"date\":\"2025-11-11 13:06:36\",\"timestamp\":1762837596},{\"type\":\"supplier\",\"amount\":244.62,\"amount_rm\":\"151\",\"conversion_rate\":1.62,\"description\":\"\",\"date\":\"2025-11-11 13:06:40\",\"timestamp\":1762837600}]', 160.00, '2025-11-11 05:06:27', 37.2960, ''),
(23, 'INV-20251020-001', 3, 2, 1, 1, 1, 10.00, 'percentage', 10.00, 1000.00, 10000.00, 9000.00, 9000.00, NULL, '2025-10-19 21:53:21', '2025-11-11 08:02:27', '2025-11-10 15:35:02', 'pending', NULL, 'started', '2025-11-10 15:35:07', 'pending', 0.00, 0.00, NULL, NULL, NULL, 0.00, NULL, 286.4800, NULL),
(30, 'INV-20251020-004', 6, 2, 1, 1, 1, 10.00, 'percentage', 10.00, 540.00, 5400.00, 4860.00, 4860.00, '93579f54-0891-4efd-ac0f-45c6fdad8f9f', '2025-10-20 09:08:16', '2025-11-12 09:12:04', '2025-10-20 09:08:39', 'pending', NULL, 'started', '2025-10-04 16:00:00', 'overdue', 0.00, -80.04, '2025-10-20 17:30:54 - Supplier payment: RM 1,000.00 (¥1,620.00 @ rate 1.6200)\n2025-10-20 20:58:25 - Supplier payment: RM -1,000.00 (¥-1,620.00 @ rate 1.6200)\n', '2025-10-20 17:30:57 - Shipping payment: ¥1,000.00\n2025-10-20 20:58:06 - Shipping payment: ¥-200.00\n2025-10-20 20:58:36 - Shipping payment: ¥-280.04\n2025-10-20 20:58:52 - Shipping payment: ¥-600.00\n', '[{\"type\":\"supplier\",\"amount\":1620,\"amount_rm\":\"1000\",\"conversion_rate\":1.62,\"description\":\"\",\"date\":\"2025-10-20 17:30:54\",\"timestamp\":1760952654},{\"type\":\"shipping\",\"amount\":\"1000\",\"description\":\"\",\"date\":\"2025-10-20 17:30:57\",\"timestamp\":1760952657},{\"type\":\"commission\",\"amount\":100,\"description\":\"\",\"staff_name\":\"Admin User\",\"date\":\"2025-10-20 17:31:16\",\"timestamp\":1760952676},{\"type\":\"shipping\",\"amount\":\"-200\",\"description\":\"\",\"date\":\"2025-10-20 20:58:06\",\"timestamp\":1760965086},{\"type\":\"supplier\",\"amount\":-1620,\"amount_rm\":\"-1000\",\"conversion_rate\":1.62,\"description\":\"\",\"date\":\"2025-10-20 20:58:25\",\"timestamp\":1760965105},{\"type\":\"shipping\",\"amount\":\"-280.04\",\"description\":\"\",\"date\":\"2025-10-20 20:58:36\",\"timestamp\":1760965116},{\"type\":\"shipping\",\"amount\":\"-600\",\"description\":\"\",\"date\":\"2025-10-20 20:58:52\",\"timestamp\":1760965132}]', 100.00, '2025-10-20 09:31:16', 143.8805, ''),
(31, 'INV-20251020-005', 6, 2, 1, 1, 1, 10.00, 'percentage', 10.00, 1350.00, 13500.00, 12150.00, 12150.00, '5f531a3c-8d36-441a-a209-9b338bae8bc4', '2025-10-20 13:00:44', '2025-11-11 08:15:41', '2025-10-20 13:12:59', 'completed', '2025-11-11 08:11:37', 'started', '2025-10-08 16:00:00', 'completed', 11750.00, 1299.90, '2025-10-20 21:01:18 - Supplier payment: RM 2,901.24 (¥4,700.00 @ rate 1.6200)\n2025-10-20 21:14:38 - Supplier payment: RM 1,450.62 (¥2,350.00 @ rate 1.6200)\n2025-10-20 21:17:49 - Supplier payment: RM -1,450.62 (¥-2,350.00 @ rate 1.6200)\n2025-10-20 21:32:05 - ⚖️ PAYMENT ADJUSTMENT: RM 725.31 (¥1,175.00 @ rate 1.6200) - Automatic adjustment after order update\n2025-10-20 21:33:20 - Supplier payment: RM -725.31 (¥-1,175.00 @ rate 1.6200)\n2025-10-20 21:33:53 - ⚖️ PAYMENT ADJUSTMENT: RM 725.31 (¥1,175.00 @ rate 1.6200) - Automatic adjustment after order update\n2025-10-20 21:40:22 - ⚖️ PAYMENT ADJUSTMENT: RM 725.31 (¥1,175.00 @ rate 1.6200) - Automatic adjustment after order update\n2025-11-11 16:11:27 - ⚖️ PAYMENT ADJUSTMENT: RM 2,901.23 (¥4,700.00 @ rate 1.6200) - Automatic adjustment after order update\n', '2025-10-20 21:01:14 - Shipping payment: ¥519.96\n2025-10-20 21:14:44 - Shipping payment: ¥259.98\n2025-10-20 21:17:54 - Shipping payment: ¥-259.98\n2025-10-20 21:32:05 - ⚖️ PAYMENT ADJUSTMENT: RM 129.99 - Automatic adjustment after order update\n2025-10-20 21:33:25 - Shipping payment: ¥-129.99\n2025-10-20 21:33:53 - ⚖️ PAYMENT ADJUSTMENT: RM 129.99 - Automatic adjustment after order update\n2025-10-20 21:40:22 - ⚖️ PAYMENT ADJUSTMENT: RM 129.99 - Automatic adjustment after order update\n2025-11-11 16:11:27 - ⚖️ PAYMENT ADJUSTMENT: RM 519.96 - Automatic adjustment after order update\n', '[{\"type\":\"shipping\",\"amount\":\"519.96\",\"description\":\"\",\"date\":\"2025-10-20 21:01:14\",\"timestamp\":1760965274},{\"type\":\"supplier\",\"amount\":4700.0007000000005,\"amount_rm\":\"2901.235\",\"conversion_rate\":1.62,\"description\":\"\",\"date\":\"2025-10-20 21:01:18\",\"timestamp\":1760965278},{\"type\":\"commission\",\"amount\":121.5,\"description\":\"Automatic commission adjustment after order update\",\"staff_name\":\"Admin User\",\"date\":\"2025-10-20 21:03:08\",\"timestamp\":1760965388},{\"type\":\"commission\",\"amount\":121.5,\"description\":\"Automatic commission adjustment after order update\",\"staff_name\":\"Admin User\",\"date\":\"2025-10-20 21:13:57\",\"timestamp\":1760966037},{\"type\":\"supplier\",\"amount\":2349.9995400000003,\"amount_rm\":\"1450.617\",\"conversion_rate\":1.62,\"description\":\"\",\"date\":\"2025-10-20 21:14:38\",\"timestamp\":1760966078},{\"type\":\"shipping\",\"amount\":\"259.98\",\"description\":\"\",\"date\":\"2025-10-20 21:14:44\",\"timestamp\":1760966084},{\"type\":\"commission\",\"amount\":567,\"description\":\"\",\"staff_name\":\"Admin User\",\"date\":\"2025-10-20 21:14:48\",\"timestamp\":1760966088},{\"type\":\"commission\",\"amount\":121.5,\"description\":\"Automatic commission adjustment after order update\",\"staff_name\":\"Admin User\",\"date\":\"2025-10-20 21:15:19\",\"timestamp\":1760966119},{\"type\":\"supplier\",\"amount\":-2349.9995400000003,\"amount_rm\":\"-1450.617\",\"conversion_rate\":1.62,\"description\":\"\",\"date\":\"2025-10-20 21:17:49\",\"timestamp\":1760966269},{\"type\":\"shipping\",\"amount\":\"-259.98\",\"description\":\"\",\"date\":\"2025-10-20 21:17:54\",\"timestamp\":1760966274},{\"type\":\"commission\",\"amount\":-391.5,\"description\":\"\",\"staff_name\":\"Admin User\",\"date\":\"2025-10-20 21:18:00\",\"timestamp\":1760966280},{\"type\":\"commission\",\"amount\":121.5,\"description\":\"Automatic commission adjustment after order update\",\"staff_name\":\"Admin User\",\"date\":\"2025-10-20 21:20:26\",\"timestamp\":1760966426},{\"type\":\"commission\",\"amount\":-300,\"description\":\"\",\"staff_name\":\"Admin User\",\"date\":\"2025-10-20 21:27:41\",\"timestamp\":1760966861},{\"type\":\"commission\",\"amount\":121.5,\"description\":\"Automatic commission adjustment after order update\",\"staff_name\":\"Admin User\",\"date\":\"2025-10-20 21:28:18\",\"timestamp\":1760966898},{\"type\":\"supplier\",\"amount\":1175,\"amount_rm\":\"725.3086419753085\",\"conversion_rate\":1.62,\"description\":\"\\u2696\\ufe0f ADJUSTMENT: Automatic adjustment after order update\",\"is_adjustment\":true,\"date\":\"2025-10-20 21:32:05\",\"timestamp\":1760967125},{\"type\":\"shipping\",\"amount\":\"129.9899999999999\",\"description\":\"\\u2696\\ufe0f ADJUSTMENT: Automatic adjustment after order update\",\"is_adjustment\":true,\"date\":\"2025-10-20 21:32:05\",\"timestamp\":1760967125},{\"type\":\"commission\",\"amount\":121.5,\"description\":\"Automatic commission adjustment after order update\",\"staff_name\":\"Admin User\",\"date\":\"2025-10-20 21:32:05\",\"timestamp\":1760967125},{\"type\":\"supplier\",\"amount\":-1175.0005800000001,\"amount_rm\":\"-725.309\",\"conversion_rate\":1.62,\"description\":\"\",\"date\":\"2025-10-20 21:33:20\",\"timestamp\":1760967200},{\"type\":\"shipping\",\"amount\":\"-129.99\",\"description\":\"\",\"date\":\"2025-10-20 21:33:25\",\"timestamp\":1760967205},{\"type\":\"commission\",\"amount\":-118.5,\"description\":\"\",\"staff_name\":\"Admin User\",\"date\":\"2025-10-20 21:33:31\",\"timestamp\":1760967211},{\"type\":\"supplier\",\"amount\":1175,\"amount_rm\":\"725.3086419753085\",\"conversion_rate\":1.62,\"description\":\"\\u2696\\ufe0f ADJUSTMENT: Automatic adjustment after order update\",\"is_adjustment\":true,\"date\":\"2025-10-20 21:33:53\",\"timestamp\":1760967233},{\"type\":\"shipping\",\"amount\":\"129.99\",\"description\":\"\\u2696\\ufe0f ADJUSTMENT: Automatic adjustment after order update\",\"is_adjustment\":true,\"date\":\"2025-10-20 21:33:53\",\"timestamp\":1760967233},{\"type\":\"commission\",\"amount\":121.5,\"description\":\"Automatic commission adjustment after order update\",\"staff_name\":\"Admin User\",\"date\":\"2025-10-20 21:33:53\",\"timestamp\":1760967233},{\"type\":\"supplier\",\"amount\":1175,\"amount_rm\":\"725.3086419753085\",\"conversion_rate\":1.62,\"description\":\"\\u2696\\ufe0f ADJUSTMENT: Automatic adjustment after order update\",\"is_adjustment\":true,\"date\":\"2025-10-20 21:40:22\",\"timestamp\":1760967622},{\"type\":\"shipping\",\"amount\":\"129.99\",\"description\":\"\\u2696\\ufe0f ADJUSTMENT: Automatic adjustment after order update\",\"is_adjustment\":true,\"date\":\"2025-10-20 21:40:22\",\"timestamp\":1760967622},{\"type\":\"commission\",\"amount\":121.5,\"description\":\"Automatic commission adjustment after order update\",\"staff_name\":\"Admin User\",\"date\":\"2025-10-20 21:40:22\",\"timestamp\":1760967622},{\"type\":\"supplier\",\"amount\":4700,\"amount_rm\":\"2901.234567901234\",\"conversion_rate\":1.62,\"description\":\"\\u2696\\ufe0f ADJUSTMENT: Automatic adjustment after order update\",\"is_adjustment\":true,\"date\":\"2025-11-11 16:11:27\",\"timestamp\":1762848687},{\"type\":\"shipping\",\"amount\":\"519.96\",\"description\":\"\\u2696\\ufe0f ADJUSTMENT: Automatic adjustment after order update\",\"is_adjustment\":true,\"date\":\"2025-11-11 16:11:27\",\"timestamp\":1762848687},{\"type\":\"commission\",\"amount\":486,\"description\":\"Automatic commission adjustment after order update\",\"staff_name\":\"Admin User\",\"date\":\"2025-11-11 16:11:27\",\"timestamp\":1762848687}]', 1215.00, '2025-11-11 08:11:27', 359.7014, 'Automatic commission adjustment after order update'),
(39, 'INV-20251112-001', 6, 2, 1, 1, 1, 10.00, 'none', 0.00, 0.00, 5400.00, 5400.00, 5400.00, 'de3ed895-0c74-4ff6-9e59-95b6e3261549', '2025-11-12 08:55:04', '2025-11-12 08:55:07', NULL, 'pending', NULL, NULL, NULL, 'pending', 0.00, 0.00, NULL, NULL, NULL, 0.00, NULL, 0.0000, NULL),
(40, 'INV-20251112-002', 5, 6, 1, 1, 1, 10.00, 'none', 0.00, 0.00, 12001.80, 12001.80, 12001.80, NULL, '2025-11-12 08:55:46', '2025-11-12 08:55:46', NULL, 'pending', NULL, NULL, NULL, 'pending', 0.00, 0.00, NULL, NULL, NULL, 0.00, NULL, 0.0000, NULL),
(41, 'INV-20251112-003', 6, 2, 1, 1, 34, 10.00, 'none', 0.00, 0.00, 5400.00, 5400.00, 5400.00, 'f66c5e36-7d2e-4061-885a-a8d4bc7c6ab8', '2025-11-12 08:57:10', '2025-11-12 08:57:13', NULL, 'pending', NULL, NULL, NULL, 'pending', 0.00, 0.00, NULL, NULL, NULL, 0.00, NULL, 0.0000, NULL),
(42, 'INV-20251112-004', 6, 2, 1, 1, 1, 1.00, 'none', 0.00, 0.00, 2700.00, 2700.00, 2700.00, 'b43a69fa-04da-417a-9d3a-f144e01d610b', '2025-11-12 08:57:37', '2025-11-12 08:57:40', NULL, 'pending', NULL, NULL, NULL, 'pending', 0.00, 0.00, NULL, NULL, NULL, 0.00, NULL, 0.0000, NULL),
(43, 'INV-20251112-005', 6, 2, 1, 1, 1, 10.00, 'none', 0.00, 0.00, 5400.00, 5400.00, 5400.00, 'd00bd4b2-59b6-48f2-bf5d-9f880beb4cea', '2025-11-12 08:58:09', '2025-11-12 08:58:12', NULL, 'pending', NULL, NULL, NULL, 'pending', 0.00, 0.00, NULL, NULL, NULL, 0.00, NULL, 0.0000, NULL),
(44, 'INV-20251116-001', 6, 2, 1, 1, 1, 10.00, 'none', 0.00, 0.00, 5400.00, 5400.00, 5400.00, NULL, '2025-11-16 02:24:51', '2025-12-12 09:48:00', NULL, 'pending', NULL, NULL, NULL, 'pending', 0.00, 0.00, NULL, NULL, NULL, 0.00, NULL, 197.8805, NULL);

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

--
-- Dumping data for table `invoice_item`
--

INSERT INTO `invoice_item` (`invoice_item_id`, `invoice_id`, `product_id`, `product_name`, `unit_price`, `quantity`, `total_price`, `created_at`, `updated_at`) VALUES
(22, 16, 3, 'FFC00003 | Spicy Cheesy 100 cm*100 cm*100 cm 20', 100.00, 20, 2000.00, '2025-09-24 16:11:26', '2025-09-24 16:11:26'),
(23, 16, 3, 'FFC00003 | Spicy Cheesy 100 cm*100 cm*100 cm 20', 100.00, 20, 2000.00, '2025-09-24 16:11:26', '2025-09-24 16:11:26'),
(24, 17, 3, 'FFC00003 | Spicy Cheesy 100 cm*100 cm*100 cm 20', 100.00, 20, 2000.00, '2025-09-24 16:17:23', '2025-09-24 16:17:23'),
(63, 23, 3, 'FFC00003 | Spicy Cheesy 100 cm*100 cm*100 cm 20', 100.00, 100, 10000.00, '2025-10-19 21:53:21', '2025-10-19 21:53:21'),
(94, 30, 31, 'FFT00031 | TEST! TEST666 12cm*1200cm*100cm asdasdasd', 270.00, 20, 5400.00, '2025-10-20 12:19:25', '2025-10-20 12:19:25'),
(139, 31, 31, 'FFT00031 | TEST! TEST666 12cm*1200cm*100cm asdasdasd', 270.00, 30, 8100.00, '2025-11-11 08:11:22', '2025-11-11 08:11:22'),
(140, 31, 31, 'FFT00031 | TEST! TEST666 12cm*1200cm*100cm asdasdasd', 270.00, 20, 5400.00, '2025-11-11 08:11:22', '2025-11-11 08:11:22'),
(142, 39, 31, 'FFT00031 | TEST! TEST666 12cm*1200cm*100cm asdasdasd', 270.00, 20, 5400.00, '2025-11-12 08:55:04', '2025-11-12 08:55:04'),
(143, 40, 30, 'FFC00000 | Spicy Cheesy 90mm*500ml*8g TEST', 0.20, 10, 2.00, '2025-11-12 08:55:46', '2025-11-12 08:55:46'),
(144, 40, 30, 'FFC00000 | Spicy Cheesy 90mm*500ml*8g TEST', 0.20, 59999, 11999.80, '2025-11-12 08:55:46', '2025-11-12 08:55:46'),
(145, 41, 31, 'FFT00031 | TEST! TEST666 12cm*1200cm*100cm asdasdasd', 270.00, 20, 5400.00, '2025-11-12 08:57:10', '2025-11-12 08:57:10'),
(146, 42, 31, 'FFT00031 | TEST! TEST666 12cm*1200cm*100cm asdasdasd', 270.00, 10, 2700.00, '2025-11-12 08:57:37', '2025-11-12 08:57:37'),
(147, 43, 31, 'FFT00031 | TEST! TEST666 12cm*1200cm*100cm asdasdasd', 270.00, 20, 5400.00, '2025-11-12 08:58:09', '2025-11-12 08:58:09'),
(148, 44, 31, 'FFT00031 | TEST! TEST666 12cm*1200cm*100cm asdasdasd', 270.00, 20, 5400.00, '2025-11-16 02:24:51', '2025-11-16 02:24:51');

-- --------------------------------------------------------

--
-- Table structure for table `material`
--

CREATE TABLE `material` (
  `material_id` int NOT NULL,
  `subcategory_id` int NOT NULL,
  `material_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `material`
--

INSERT INTO `material` (`material_id`, `subcategory_id`, `material_name`) VALUES
(1, 1, 'Spicy'),
(2, 1, 'TEST JE'),
(3, 2, 'TEST!');

-- --------------------------------------------------------

--
-- Table structure for table `payment_history`
--

CREATE TABLE `payment_history` (
  `payment_id` int NOT NULL,
  `invoice_id` int NOT NULL,
  `amount_paid` decimal(12,2) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_history`
--

INSERT INTO `payment_history` (`payment_id`, `invoice_id`, `amount_paid`, `payment_date`, `description`) VALUES
(1, 17, 200.00, '2025-09-24 16:21:13', NULL),
(4, 16, 100.00, '2025-09-27 12:09:23', NULL),
(34, 30, 1000.00, '2025-10-20 09:08:39', NULL),
(35, 30, -200.00, '2025-10-20 09:08:47', NULL),
(36, 30, 1.00, '2025-10-20 09:14:53', NULL),
(37, 30, 100.00, '2025-10-20 12:18:52', NULL),
(38, 31, 1000.00, '2025-10-20 13:12:59', NULL),
(39, 31, -500.00, '2025-10-20 13:13:12', NULL),
(40, 31, 100.00, '2025-11-10 08:42:34', NULL),
(41, 23, 2000.00, '2025-11-10 15:35:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `price`
--

CREATE TABLE `price` (
  `price_id` int NOT NULL,
  `product_id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `quantity` int DEFAULT NULL,
  `carton_width` decimal(10,3) DEFAULT NULL,
  `carton_height` decimal(10,3) DEFAULT NULL,
  `carton_length` decimal(10,3) DEFAULT NULL,
  `carton_weight` decimal(10,3) DEFAULT NULL,
  `pcs_per_carton` int DEFAULT NULL,
  `no_of_carton` int DEFAULT NULL,
  `cbm_carton` decimal(15,3) DEFAULT NULL,
  `new_price_yen` decimal(12,3) DEFAULT NULL,
  `new_moq_quantity` int DEFAULT NULL,
  `new_shipping_moq_yen` decimal(12,3) DEFAULT NULL,
  `new_additional_price_moq_yen` decimal(12,3) DEFAULT NULL,
  `new_conversion_rate` decimal(10,3) DEFAULT NULL,
  `new_unit_price_yen` decimal(12,3) DEFAULT NULL,
  `new_freight_method` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `new_total_cbm_moq` decimal(12,3) DEFAULT NULL,
  `new_total_weight_moq` decimal(12,3) DEFAULT NULL,
  `new_unit_price_rm` decimal(12,3) DEFAULT NULL,
  `new_unit_freight_cost_rm` decimal(12,3) DEFAULT NULL,
  `new_unit_profit_rm` decimal(12,3) DEFAULT NULL,
  `new_selling_price` decimal(12,3) DEFAULT NULL,
  `add_carton1_width` decimal(10,3) DEFAULT NULL,
  `add_carton1_height` decimal(10,3) DEFAULT NULL,
  `add_carton1_length` decimal(10,3) DEFAULT NULL,
  `add_carton1_pcs` int DEFAULT NULL,
  `add_carton1_weight` decimal(10,3) DEFAULT NULL,
  `add_carton1_no` int DEFAULT NULL,
  `add_carton1_total_cbm` decimal(15,3) DEFAULT NULL,
  `add_carton2_width` decimal(10,3) DEFAULT NULL,
  `add_carton2_height` decimal(10,3) DEFAULT NULL,
  `add_carton2_length` decimal(10,3) DEFAULT NULL,
  `add_carton2_pcs` int DEFAULT NULL,
  `add_carton2_weight` decimal(10,3) DEFAULT NULL,
  `add_carton2_no` int DEFAULT NULL,
  `add_carton2_total_cbm` decimal(15,3) DEFAULT NULL,
  `add_carton3_width` decimal(10,3) DEFAULT NULL,
  `add_carton3_height` decimal(10,3) DEFAULT NULL,
  `add_carton3_length` decimal(10,3) DEFAULT NULL,
  `add_carton3_pcs` int DEFAULT NULL,
  `add_carton3_weight` decimal(10,3) DEFAULT NULL,
  `add_carton3_no` int DEFAULT NULL,
  `add_carton3_total_cbm` decimal(15,3) DEFAULT NULL,
  `add_carton4_width` decimal(10,3) DEFAULT NULL,
  `add_carton4_height` decimal(10,3) DEFAULT NULL,
  `add_carton4_length` decimal(10,3) DEFAULT NULL,
  `add_carton4_pcs` int DEFAULT NULL,
  `add_carton4_weight` decimal(10,3) DEFAULT NULL,
  `add_carton4_no` int DEFAULT NULL,
  `add_carton4_total_cbm` decimal(15,3) DEFAULT NULL,
  `add_carton5_width` decimal(10,3) DEFAULT NULL,
  `add_carton5_height` decimal(10,3) DEFAULT NULL,
  `add_carton5_length` decimal(10,3) DEFAULT NULL,
  `add_carton5_pcs` int DEFAULT NULL,
  `add_carton5_weight` decimal(10,3) DEFAULT NULL,
  `add_carton5_no` int DEFAULT NULL,
  `add_carton5_total_cbm` decimal(15,3) DEFAULT NULL,
  `add_carton6_width` decimal(10,3) DEFAULT NULL,
  `add_carton6_height` decimal(10,3) DEFAULT NULL,
  `add_carton6_length` decimal(10,3) DEFAULT NULL,
  `add_carton6_pcs` int DEFAULT NULL,
  `add_carton6_weight` decimal(10,3) DEFAULT NULL,
  `add_carton6_no` int DEFAULT NULL,
  `add_carton6_total_cbm` decimal(15,3) DEFAULT NULL,
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

--
-- Dumping data for table `price`
--

INSERT INTO `price` (`price_id`, `product_id`, `supplier_id`, `quantity`, `carton_width`, `carton_height`, `carton_length`, `carton_weight`, `pcs_per_carton`, `no_of_carton`, `cbm_carton`, `new_price_yen`, `new_moq_quantity`, `new_shipping_moq_yen`, `new_additional_price_moq_yen`, `new_conversion_rate`, `new_unit_price_yen`, `new_freight_method`, `new_total_cbm_moq`, `new_total_weight_moq`, `new_unit_price_rm`, `new_unit_freight_cost_rm`, `new_unit_profit_rm`, `new_selling_price`, `add_carton1_width`, `add_carton1_height`, `add_carton1_length`, `add_carton1_pcs`, `add_carton1_weight`, `add_carton1_no`, `add_carton1_total_cbm`, `add_carton2_width`, `add_carton2_height`, `add_carton2_length`, `add_carton2_pcs`, `add_carton2_weight`, `add_carton2_no`, `add_carton2_total_cbm`, `add_carton3_width`, `add_carton3_height`, `add_carton3_length`, `add_carton3_pcs`, `add_carton3_weight`, `add_carton3_no`, `add_carton3_total_cbm`, `add_carton4_width`, `add_carton4_height`, `add_carton4_length`, `add_carton4_pcs`, `add_carton4_weight`, `add_carton4_no`, `add_carton4_total_cbm`, `add_carton5_width`, `add_carton5_height`, `add_carton5_length`, `add_carton5_pcs`, `add_carton5_weight`, `add_carton5_no`, `add_carton5_total_cbm`, `add_carton6_width`, `add_carton6_height`, `add_carton6_length`, `add_carton6_pcs`, `add_carton6_weight`, `add_carton6_no`, `add_carton6_total_cbm`, `designlogo`, `price`, `price_rm`, `shipping_price`, `additional_price`, `conversion_rate`, `total_price_yen`, `total_price_rm`, `deposit_50_yen`, `deposit_50_rm`, `total_cbm`, `vm_carton`, `total_vm`, `total_weight`, `sg_tax`, `supplier_1st_yen`, `supplier_2nd_yen`, `customer_1st_rm`, `customer_2nd_rm`, `estimated_arrival`, `final_selling_total`, `final_total_price`, `final_unit_price`, `final_profit_per_unit_rm`, `final_total_profit`, `final_profit_percent`, `zakat`, `final_selling_unit`) VALUES
(3, 3, 5, NULL, 100.000, 100.000, 100.000, 100.000, 100, NULL, 1.280, 10.231, 10, 10.001, 10.001, 1.620, 12.231, 'M1', 1.281, 110.000, 61.352, 53.802, 38.648, 100.000, 10.000, 10.000, 10.000, 10, 10.000, NULL, 0.001, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL),
(4, 29, 5, NULL, 10.005, 10.002, 10.005, 10.005, 10, NULL, 0.001, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL),
(5, 30, 2, NULL, 53.000, 35.500, 45.000, 8.000, 1000, NULL, 0.108, 0.190, 10000, 0.000, 0.000, 1.620, 0.190, 'M1', 1.390, 110.000, 0.176, 0.058, 0.024, 0.200, 31.000, 19.000, 41.000, 1000, 3.000, NULL, 0.031, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL),
(6, 31, 5, NULL, 122.000, 62.000, 9.000, 20.000, 2, NULL, 0.087, 220.000, 10, 150.000, 0.000, 1.620, 235.000, 'M1', 0.619, 350.000, 171.060, 25.998, 98.940, 270.000, 42.000, 72.000, 3.000, 2, 30.000, NULL, 0.012, 25.000, 25.000, 3.000, 5, 30.000, NULL, 0.002, 71.000, 41.000, 16.000, 5, 20.000, NULL, 0.060, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `price_shipping`
--

CREATE TABLE `price_shipping` (
  `shipping_price_id` int NOT NULL,
  `shipping_code` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `freight_rate` decimal(12,3) DEFAULT NULL,
  `shipping_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `delivery_days` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `price_shipping`
--

INSERT INTO `price_shipping` (`shipping_price_id`, `shipping_code`, `freight_rate`, `shipping_name`, `delivery_days`) VALUES
(1, 'M1', 420.000, 'Sea Normal Goods', 21),
(2, 'M2', 420.000, 'Sea Sensitive Goods', 21),
(3, 'S1', 285.000, 'SG Sea Normal Goods', 21),
(4, 'S2', 285.000, 'SG Sea Sensitive Goods', 21),
(5, 'M3a', 20.000, 'Air VM Normal Goods', 7),
(6, 'M3b', 20.000, 'Air KG Normal Goods', 7),
(7, 'M4a', 20.000, 'Air VM Sensitive Goods', 7),
(8, 'M4b', 20.000, 'Air KG Sensitive Goods', 7),
(9, 'S3a', 24.000, 'SG Air VM Normal Goods', 7),
(10, 'S3b', 24.000, 'SG Air KG Normal Goods', 7),
(11, 'S4a', 24.000, 'SG Air VM Sensitive Goods', 7),
(12, 'S4b', 24.000, 'SG Air KG Sensitive Goods', 7);

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
  `visibility` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Shown',
  `xero_relation` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_code`, `section_id`, `category_id`, `subcategory_id`, `material_id`, `product_type_id`, `shape`, `size_volume`, `size_1`, `size_2`, `size_3`, `variant`, `description`, `production_lead_time`, `image_url`, `short_desc`, `long_desc`, `current_stock`, `reorder_level`, `is_active`, `visibility`, `xero_relation`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`) VALUES
(3, 'FFC00003', 1, 1, 1, 1, 1, NULL, NULL, '100 cm', '100 cm', '100 cm', '20', '20', 20, 'product_1758729202.png', NULL, NULL, 0, 0, 1, 'Shown', '937cf388-b06c-4214-b5da-ffb598461da9', '2025-09-24 15:53:22', '2025-09-24 16:51:56', NULL, NULL, 1),
(29, 'FFC00029', 1, 1, 1, 1, 1, NULL, NULL, '5 cm', '5 cm', '5 cm', 'TEST', 'TEST', 2, 'product_1758732766.png', NULL, NULL, 0, 0, 1, 'Shown', '4fd9dc35-bd52-47b1-af45-a05ed1fab3ad', '2025-09-24 16:52:46', '2025-09-24 16:57:20', NULL, NULL, 1),
(30, 'FFC00000', 1, 1, 1, 1, 1, NULL, NULL, '90mm', '500ml', '8g', 'TEST', 'TEST', 20, 'product_1758770784.png', NULL, NULL, 0, 0, 1, 'Shown', '4a5baf79-72d9-40ec-8350-31f3205a019a', '2025-09-25 03:26:24', '2025-09-25 03:26:26', NULL, NULL, NULL),
(31, 'FFT00031', 1, 1, 2, 3, 3, NULL, NULL, '12cm', '1200cm', '100cm', 'asdasdasd', 'awdasdsa', 12, 'product_cover_1761047720.jpg', NULL, NULL, 0, 0, 1, 'Shown', '5e7dd9e5-c3bc-4be3-8d9b-714bb7e66ebe', '2025-10-19 17:19:14', '2025-10-21 11:55:21', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_type`
--

CREATE TABLE `product_type` (
  `product_type_id` int NOT NULL,
  `material_id` int NOT NULL,
  `product_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_type`
--

INSERT INTO `product_type` (`product_type_id`, `material_id`, `product_name`) VALUES
(1, 1, 'Cheesy'),
(2, 2, 'TEST SATU'),
(3, 3, 'TEST666');

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

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`section_id`, `section_name`) VALUES
(1, 'F&B');

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

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`session_id`, `staff_id`, `token`, `expires_at`) VALUES
(4, 34, '47056e70e4b5ae0aaf03d398e0c09eb4', '2025-09-14 03:15:49'),
(17, 34, 'f02c7e26fd7e7e99194fbcf1b93747be', '2025-10-20 02:04:30'),
(77, 1, '663da797f2a13aaa125ee229303b08cf', '2025-12-13 00:36:18');

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
(1, 'Berans Trading', 'berans.png', 'In Berans, We Trust', 'Tokyo Bank', 'Maybank', '123456789', 'Malaysia, Johor', '03-1234-5678', 'beranstrading@gmail.com', 'favicon_1756891365.png', 'logolight1756891365.png', 'logodark_1756891365.png', 'gxo7nbFFTIRbJ8KIMECe2FDEoyidmEfajD9R9zuP2yc', '26d074dc-81b8-4796-9709-6a79846b9532', '2025-11-12 09:28:10');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int NOT NULL,
  `company_id` int DEFAULT NULL,
  `staff_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `staff_profile_picture` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `staff_designation` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `staff_about` text COLLATE utf8mb4_general_ci,
  `staff_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `remember_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `remember_expiry` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `web_layout` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'vertical',
  `web_skin` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'light',
  `web_width` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'fluid',
  `layout_pos` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'fixed',
  `topbar_color` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'light',
  `sidebar_size` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'lg',
  `sidebar_color` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'light',
  `sidebar_view` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'default',
  `sidebar_` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'default',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `company_id`, `staff_name`, `staff_profile_picture`, `staff_designation`, `staff_about`, `staff_number`, `username`, `email`, `password_hash`, `role`, `is_active`, `remember_token`, `remember_expiry`, `web_layout`, `web_skin`, `web_width`, `layout_pos`, `topbar_color`, `sidebar_size`, `sidebar_color`, `sidebar_view`, `sidebar_`, `last_login`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Admin User', 'profile_1757777234.png', 'System Administrator', 'My name is Tumulak, I come from Thailand, KL ni kawe legend, sawadikap', 'EMP001', 'admin', 'admin@company.com', '$2y$10$oATeGKY26KzvlO7UNjIcSeC2LKimJdpgehpwlbo85JRmDVzhY7Oi6', 'admin', 1, NULL, NULL, 'vertical', 'dark', 'fluid', 'fixed', 'light', 'lg', 'dark', 'default', 'default', '2025-12-12 23:36:18', '2025-07-22 06:10:52', '2025-12-12 15:36:18', NULL),
(2, 1, 'Sales Manager', '', 'Sales Manager', NULL, 'EMP002', 'salesmgr', 'sales@company.com', 'test', 'manager', 1, NULL, NULL, 'vertical', 'light', 'fluid', 'fixed', 'light', 'lg', 'light', 'default', 'default', NULL, '2025-07-22 06:10:52', '2025-07-24 05:07:05', NULL),
(34, 1, 'Fahmi', '', 'System Administrator', 'BRO', 'EMP005', 'fahmio', 'mohdfahmi1122@gmail.com', '$2y$10$SWRKB6J3XS1RIFBx0cONnOQLpyJFexdPcDaLey66XvffB7EYdiZvi', 'admin', 1, NULL, NULL, 'vertical', 'light', 'fluid', 'fixed', 'light', 'lg', 'light', 'default', 'default', '2025-10-20 01:04:30', '2025-07-26 05:14:52', '2025-10-19 17:04:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subcategory`
--

CREATE TABLE `subcategory` (
  `subcategory_id` int NOT NULL,
  `category_id` int NOT NULL,
  `subcategory_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subcategory`
--

INSERT INTO `subcategory` (`subcategory_id`, `category_id`, `subcategory_name`) VALUES
(1, 1, 'Cripspy'),
(2, 1, 'TEST');

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
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplier_id`, `supplier_name`, `supplier_contact_person`, `phone`, `address`, `city`, `region`, `postcode`, `country`, `email`, `notes`, `xero_relation`, `deleted_at`) VALUES
(1, 'TEST', 'MUHAMMAD FAHMI AZHAR', '+60167350072', 'NO 79, LORONG SUTERA INDAH 1/5\r\nTAMAN SUTERA INDAH', 'LUNAS', 'Kedah', '09600', 'Malaysia', 'mohdfahmi1122@gmail.com', '', '9f1d644d-1d3d-483c-a760-3958eb0c7045', NULL),
(2, 'John Chairs', 'John', '+8615233168898', 'Chaoyang Beijing', 'Chaoyang', 'Beijing', '100026', 'China', 'prodbyusuf@gmail.com', '', '65ca59e8-c5da-46fe-bf37-c6f7dd62c303', NULL),
(5, 'Hello World', 'Nabil Rahman', '+6012345678', '29, Lorong BLM 3/20\r\nBandar Laguna Merbok', 'Sungai Petani', 'Kedah', '08000', 'Malaysia', 'test@gmail.com', '', 'f03945f8-df5c-47f4-af24-4bfa9d675b2b', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usos_config`
--

CREATE TABLE `usos_config` (
  `usos_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `order_date` date NOT NULL,
  `total_quantity_ordered` decimal(10,2) NOT NULL,
  `monthly_usage` decimal(10,2) NOT NULL,
  `daily_usage` decimal(10,2) GENERATED ALWAYS AS ((`monthly_usage` / 30)) STORED,
  `production_lead_time_days` int NOT NULL,
  `shipping_code` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usos_config`
--

INSERT INTO `usos_config` (`usos_id`, `customer_id`, `order_date`, `total_quantity_ordered`, `monthly_usage`, `production_lead_time_days`, `shipping_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, '2025-11-30', 30000.00, 15000.00, 40, NULL, '2025-11-30 10:09:30', '2025-11-30 10:14:38', '2025-11-30 10:14:38'),
(2, 2, '2025-01-01', 30000.00, 15000.00, 42, NULL, '2025-11-30 10:15:11', '2025-11-30 10:47:57', '2025-11-30 10:47:57'),
(3, 6, '2025-01-01', 30000.00, 15000.00, 42, NULL, '2025-11-30 10:26:55', '2025-11-30 10:29:49', '2025-11-30 10:29:49'),
(4, 6, '2025-11-30', 5000.00, 2000.00, 42, NULL, '2025-11-30 10:30:12', '2025-11-30 10:53:27', '2025-11-30 10:53:27'),
(5, 2, '2025-11-30', 30000.00, 15000.00, 42, NULL, '2025-11-30 10:53:43', '2025-11-30 10:53:43', NULL),
(6, 6, '2025-12-01', 2000.00, 1000.00, 42, NULL, '2025-11-30 17:45:48', '2025-12-01 02:18:46', '2025-12-01 02:18:46'),
(7, 3, '2025-12-01', 500.00, 100.00, 40, NULL, '2025-11-30 17:54:27', '2025-12-01 02:18:43', '2025-12-01 02:18:43'),
(8, 2, '2025-12-01', 30000.00, 15000.00, 21, 'M1', '2025-12-01 02:17:52', '2025-12-01 02:18:39', '2025-12-01 02:18:39'),
(9, 6, '2025-01-01', 30000.00, 15000.00, 21, 'M1', '2025-12-01 02:20:19', '2025-12-01 02:21:15', '2025-12-01 02:21:15'),
(10, 2, '2025-11-30', 30000.00, 15000.00, 21, 'M4a', '2025-12-01 02:21:39', '2025-12-01 02:57:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usos_items`
--

CREATE TABLE `usos_items` (
  `usos_item_id` int NOT NULL,
  `usos_id` int NOT NULL,
  `product_id` int NOT NULL,
  `price_id` int DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) GENERATED ALWAYS AS ((`quantity` * `unit_price`)) STORED,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usos_items`
--

INSERT INTO `usos_items` (`usos_item_id`, `usos_id`, `product_id`, `price_id`, `quantity`, `unit_price`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 10, 3, 3, 1.00, 100.00, '2025-12-01 02:57:14', '2025-12-01 02:57:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usos_schedule`
--

CREATE TABLE `usos_schedule` (
  `schedule_id` int NOT NULL,
  `usos_id` int NOT NULL,
  `order_date` date NOT NULL,
  `arrival_date` date NOT NULL,
  `run_out_date` date DEFAULT NULL,
  `actual_arrival_date` date DEFAULT NULL,
  `is_completed` tinyint(1) DEFAULT '0',
  `notes` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usos_schedule`
--

INSERT INTO `usos_schedule` (`schedule_id`, `usos_id`, `order_date`, `arrival_date`, `run_out_date`, `actual_arrival_date`, `is_completed`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-11-30', '2026-01-09', '2026-03-10', '2026-01-11', 1, NULL, '2025-11-30 10:09:30', '2025-11-30 10:14:00'),
(2, 1, '2026-01-31', '2026-03-12', '2026-03-12', NULL, 0, NULL, '2025-11-30 10:14:00', '2025-11-30 10:14:00'),
(3, 2, '2025-01-01', '2025-02-12', '2025-04-13', '2025-01-08', 1, NULL, '2025-11-30 10:15:11', '2025-11-30 10:15:45'),
(4, 2, '2025-01-26', '2025-03-09', '2025-03-09', '2025-11-11', 1, NULL, '2025-11-30 10:15:45', '2025-11-30 10:24:55'),
(5, 2, '2025-11-29', '2026-01-10', '2026-01-10', NULL, 0, NULL, '2025-11-30 10:24:55', '2025-11-30 10:24:55'),
(6, 3, '2025-01-01', '2025-02-12', '2025-04-13', NULL, 0, NULL, '2025-11-30 10:26:55', '2025-11-30 10:26:55'),
(7, 4, '2025-11-30', '2026-01-11', '2026-03-27', '2025-01-09', 1, NULL, '2025-11-30 10:30:12', '2025-11-30 10:52:23'),
(8, 4, '2025-02-11', '2025-03-25', '2025-06-08', '2025-03-25', 1, NULL, '2025-11-30 10:52:23', '2025-11-30 10:53:14'),
(9, 4, '2025-04-27', '2025-06-08', '2025-08-22', NULL, 0, NULL, '2025-11-30 10:53:14', '2025-11-30 10:53:14'),
(10, 5, '2025-11-30', '2026-01-11', '2026-03-12', '2026-01-11', 1, NULL, '2025-11-30 10:53:43', '2025-11-30 10:54:13'),
(11, 5, '2026-01-29', '2026-03-12', '2026-05-11', '2026-03-10', 1, NULL, '2025-11-30 10:54:13', '2025-11-30 10:54:56'),
(12, 5, '2026-03-28', '2026-05-09', '2026-07-08', '2026-05-12', 1, NULL, '2025-11-30 10:54:56', '2025-11-30 13:19:37'),
(13, 5, '2026-05-30', '2026-07-11', '2026-09-09', '2026-07-10', 1, NULL, '2025-11-30 13:19:37', '2025-11-30 17:22:36'),
(14, 5, '2026-07-28', '2026-09-08', '2026-11-07', '2026-09-08', 1, NULL, '2025-11-30 17:22:36', '2025-11-30 17:22:58'),
(15, 5, '2026-09-26', '2026-11-07', '2027-01-06', '2026-11-07', 1, NULL, '2025-11-30 17:22:58', '2025-11-30 17:29:34'),
(16, 5, '2026-11-25', '2027-01-06', '2027-03-07', NULL, 0, NULL, '2025-11-30 17:24:38', '2025-11-30 17:24:38'),
(17, 6, '2025-12-01', '2026-01-12', '2026-03-12', NULL, 0, NULL, '2025-11-30 17:45:48', '2025-11-30 17:45:48'),
(18, 7, '2025-12-01', '2026-01-10', '2026-06-09', NULL, 0, NULL, '2025-11-30 17:54:27', '2025-11-30 17:54:27'),
(19, 8, '2025-12-01', '2026-01-12', '2026-03-13', NULL, 0, NULL, '2025-12-01 02:17:52', '2025-12-01 02:17:52'),
(20, 9, '2025-01-01', '2025-02-12', '2025-04-13', NULL, 0, NULL, '2025-12-01 02:20:19', '2025-12-01 02:20:19'),
(21, 10, '2025-11-30', '2026-01-11', '2026-03-12', '2026-01-11', 1, NULL, '2025-12-01 02:21:39', '2025-12-01 02:22:42'),
(22, 10, '2026-01-29', '2026-03-12', '2026-05-11', '2026-03-12', 1, NULL, '2025-12-01 02:22:42', '2025-12-01 02:27:12'),
(23, 10, '2026-04-13', '2026-05-11', '2026-07-10', NULL, 0, NULL, '2025-12-01 02:27:12', '2025-12-01 02:27:12');

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
-- Indexes for table `folder_catalogue`
--
ALTER TABLE `folder_catalogue`
  ADD PRIMARY KEY (`folder_id`);

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
  ADD KEY `fk_commission_staff` (`commission_staff_id`),
  ADD KEY `idx_production_status` (`production_status`);

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
-- Indexes for table `usos_config`
--
ALTER TABLE `usos_config`
  ADD PRIMARY KEY (`usos_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `shipping_code` (`shipping_code`);

--
-- Indexes for table `usos_items`
--
ALTER TABLE `usos_items`
  ADD PRIMARY KEY (`usos_item_id`),
  ADD KEY `usos_id` (`usos_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `price_id` (`price_id`);

--
-- Indexes for table `usos_schedule`
--
ALTER TABLE `usos_schedule`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `usos_id` (`usos_id`);

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
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `folder_catalogue`
--
ALTER TABLE `folder_catalogue`
  MODIFY `folder_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `invoice_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_item`
--
ALTER TABLE `invoice_item`
  MODIFY `invoice_item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT for table `material`
--
ALTER TABLE `material`
  MODIFY `material_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payment_history`
--
ALTER TABLE `payment_history`
  MODIFY `payment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `price`
--
ALTER TABLE `price`
  MODIFY `price_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `price_shipping`
--
ALTER TABLE `price_shipping`
  MODIFY `shipping_price_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `price_shipping_totals`
--
ALTER TABLE `price_shipping_totals`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `product_type`
--
ALTER TABLE `product_type`
  MODIFY `product_type_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `schedule_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `section_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `session_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

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
  MODIFY `subcategory_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `usos_config`
--
ALTER TABLE `usos_config`
  MODIFY `usos_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `usos_items`
--
ALTER TABLE `usos_items`
  MODIFY `usos_item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `usos_schedule`
--
ALTER TABLE `usos_schedule`
  MODIFY `schedule_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

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

--
-- Constraints for table `usos_config`
--
ALTER TABLE `usos_config`
  ADD CONSTRAINT `usos_config_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `usos_items`
--
ALTER TABLE `usos_items`
  ADD CONSTRAINT `usos_items_ibfk_1` FOREIGN KEY (`usos_id`) REFERENCES `usos_config` (`usos_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usos_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `usos_items_ibfk_3` FOREIGN KEY (`price_id`) REFERENCES `price` (`price_id`) ON DELETE SET NULL;

--
-- Constraints for table `usos_schedule`
--
ALTER TABLE `usos_schedule`
  ADD CONSTRAINT `usos_schedule_ibfk_1` FOREIGN KEY (`usos_id`) REFERENCES `usos_config` (`usos_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
