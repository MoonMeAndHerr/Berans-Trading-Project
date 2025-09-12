-- Add new fields to price table for profit/loss calculations
ALTER TABLE `price` 
ADD COLUMN `new_unit_price_yen` DECIMAL(15,2) DEFAULT 0 COMMENT 'New unit price in Yen for supplier payment calculation',
ADD COLUMN `new_unit_freight_cost_rm` DECIMAL(15,2) DEFAULT 0 COMMENT 'New unit freight cost in RM for shipping payment calculation';

-- Update existing records with current price values as defaults
UPDATE `price` 
SET `new_unit_price_yen` = COALESCE(`price`, 0),
    `new_unit_freight_cost_rm` = COALESCE(`price_rm`, 0);