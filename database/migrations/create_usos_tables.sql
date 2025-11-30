-- USOS (Unit Systematic Ordering System) Database Schema
-- Created: 2025-11-30

-- Main USOS Configuration Table
CREATE TABLE IF NOT EXISTS `usos_config` (
  `usos_id` INT(11) NOT NULL AUTO_INCREMENT,
  `customer_id` INT(11) NOT NULL,
  `order_date` DATE NOT NULL,
  `total_quantity_ordered` DECIMAL(10,2) NOT NULL,
  `monthly_usage` DECIMAL(10,2) NOT NULL,
  `daily_usage` DECIMAL(10,2) GENERATED ALWAYS AS (`monthly_usage` / 30) STORED,
  `production_lead_time_days` INT(11) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`usos_id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `usos_config_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- USOS Schedule Table (tracks each delivery cycle)
CREATE TABLE IF NOT EXISTS `usos_schedule` (
  `schedule_id` INT(11) NOT NULL AUTO_INCREMENT,
  `usos_id` INT(11) NOT NULL,
  `order_date` DATE NOT NULL,
  `arrival_date` DATE NOT NULL,
  `run_out_date` DATE DEFAULT NULL,
  `actual_arrival_date` DATE DEFAULT NULL,
  `is_completed` TINYINT(1) DEFAULT 0,
  `notes` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`schedule_id`),
  KEY `usos_id` (`usos_id`),
  CONSTRAINT `usos_schedule_ibfk_1` FOREIGN KEY (`usos_id`) REFERENCES `usos_config` (`usos_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
