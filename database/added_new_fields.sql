-- =====================================================
-- BERANS TRADING - DATABASE FIELD ADDITIONS
-- =====================================================
-- This file contains all new field additions to the database
-- for enhanced profit/loss tracking and staff commission system
-- 
-- Execute this file to add all required fields to existing database
-- =====================================================

-- =====================================================
-- 1. PRICE TABLE - New pricing fields for profit calculations
-- =====================================================
-- Add new fields to price table for profit/loss calculations
ALTER TABLE `price` 
ADD COLUMN `new_unit_price_yen` DECIMAL(15,2) DEFAULT 0 COMMENT 'New unit price in Yen for supplier payment calculation',
ADD COLUMN `new_unit_freight_cost_rm` DECIMAL(15,2) DEFAULT 0 COMMENT 'New unit freight cost in RM for shipping payment calculation';

-- Update existing records with current price values as defaults
UPDATE `price` 
SET `new_unit_price_yen` = COALESCE(`price`, 0),
    `new_unit_freight_cost_rm` = COALESCE(`price_rm`, 0);

-- =====================================================
-- 2. INVOICE TABLE - Profit/Loss tracking fields
-- =====================================================
-- Add supplier payment tracking fields
ALTER TABLE `invoice` 
ADD COLUMN `supplier_payments_total` DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Total supplier payments made in YEN',
ADD COLUMN `shipping_payments_total` DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Total shipping payments made in YEN',
ADD COLUMN `supplier_payment_notes` TEXT DEFAULT NULL COMMENT 'Notes about supplier payments',
ADD COLUMN `shipping_payment_notes` TEXT DEFAULT NULL COMMENT 'Notes about shipping payments',
ADD COLUMN `payment_history_json` LONGTEXT DEFAULT NULL COMMENT 'JSON array of payment history for detailed tracking';

-- Update existing records to have default values
UPDATE `invoice` SET 
  `supplier_payments_total` = 0.00, 
  `shipping_payments_total` = 0.00 
WHERE `supplier_payments_total` IS NULL OR `shipping_payments_total` IS NULL;

-- =====================================================
-- 3. INVOICE TABLE - Staff Commission System
-- =====================================================
-- Add commission tracking fields for staff profit sharing
ALTER TABLE `invoice` 
ADD COLUMN `commission_staff_id` INT(11) NULL COMMENT 'Staff member who gets commission from this order',
ADD COLUMN `commission_percentage` DECIMAL(5,2) DEFAULT 0.00 COMMENT 'Commission percentage (0-100) for the assigned staff';

-- Add foreign key constraint for commission_staff_id
ALTER TABLE `invoice` 
ADD CONSTRAINT `fk_commission_staff` 
FOREIGN KEY (`commission_staff_id`) REFERENCES `staff`(`staff_id`) ON DELETE SET NULL;

-- =====================================================
-- VERIFICATION QUERIES
-- =====================================================
-- Use these queries to verify all fields were added correctly:

-- Check price table new fields:
-- SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT, COLUMN_COMMENT 
-- FROM INFORMATION_SCHEMA.COLUMNS 
-- WHERE TABLE_NAME = 'price' AND COLUMN_NAME IN ('new_unit_price_yen', 'new_unit_freight_cost_rm');

-- Check invoice table profit/loss fields:
-- SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT, COLUMN_COMMENT 
-- FROM INFORMATION_SCHEMA.COLUMNS 
-- WHERE TABLE_NAME = 'invoice' AND COLUMN_NAME IN ('supplier_payments_total', 'shipping_payments_total', 'payment_history_json');

-- Check invoice table commission fields:
-- SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT, COLUMN_COMMENT 
-- FROM INFORMATION_SCHEMA.COLUMNS 
-- WHERE TABLE_NAME = 'invoice' AND COLUMN_NAME IN ('commission_staff_id', 'commission_percentage');

-- =====================================================
-- FIELD SUMMARY
-- =====================================================
-- PRICE TABLE:
-- + new_unit_price_yen (DECIMAL 15,2) - Supplier cost per unit in Yen
-- + new_unit_freight_cost_rm (DECIMAL 15,2) - Shipping cost per unit in RM
--
-- INVOICE TABLE (Profit/Loss):
-- + supplier_payments_total (DECIMAL 15,2) - Total paid to suppliers in Yen
-- + shipping_payments_total (DECIMAL 15,2) - Total paid for shipping in RM  
-- + supplier_payment_notes (TEXT) - Notes about supplier payments
-- + shipping_payment_notes (TEXT) - Notes about shipping payments
-- + payment_history_json (LONGTEXT) - Detailed payment history as JSON
--
-- INVOICE TABLE (Commission):
-- + commission_staff_id (INT 11) - FK to staff table for commission recipient
-- + commission_percentage (DECIMAL 5,2) - Commission percentage (0-100%)
-- + fk_commission_staff (FOREIGN KEY) - Ensures valid staff reference
-- =====================================================