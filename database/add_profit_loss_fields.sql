-- SQL script to add profit/loss tracking fields to the invoice table

-- Add supplier payment tracking fields
ALTER TABLE `invoice` 
ADD COLUMN `supplier_payments_total` DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Total supplier payments made in YEN',
ADD COLUMN `shipping_payments_total` DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Total shipping payments made in YEN',
ADD COLUMN `supplier_payment_notes` TEXT DEFAULT NULL COMMENT 'Notes about supplier payments',
ADD COLUMN `shipping_payment_notes` TEXT DEFAULT NULL COMMENT 'Notes about shipping payments',
ADD COLUMN `payment_history_json` JSON DEFAULT NULL COMMENT 'JSON array of payment history for detailed tracking';

-- Update existing records to have default values
UPDATE `invoice` SET 
  `supplier_payments_total` = 0.00, 
  `shipping_payments_total` = 0.00 
WHERE `supplier_payments_total` IS NULL OR `shipping_payments_total` IS NULL;