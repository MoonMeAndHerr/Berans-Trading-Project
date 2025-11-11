USE beranstrading;

ALTER TABLE `invoice` 
ADD COLUMN IF NOT EXISTS `production_status` VARCHAR(20) NULL DEFAULT NULL AFTER `status`,
ADD COLUMN IF NOT EXISTS `production_start_date` TIMESTAMP NULL DEFAULT NULL AFTER `production_status`,
ADD COLUMN IF NOT EXISTS `profit_loss_status` VARCHAR(20) NULL DEFAULT 'pending' AFTER `production_start_date`,
ADD COLUMN IF NOT EXISTS `completion_date` TIMESTAMP NULL DEFAULT NULL AFTER `profit_loss_status`,
ADD COLUMN IF NOT EXISTS `commission_staff_id` INT NULL DEFAULT NULL AFTER `completion_date`,
ADD COLUMN IF NOT EXISTS `commission_percentage` DECIMAL(5, 2) DEFAULT 0.00 AFTER `commission_staff_id`,
ADD COLUMN IF NOT EXISTS `commission_paid_amount` DECIMAL(15, 4) DEFAULT 0.0000 AFTER `commission_percentage`,
ADD COLUMN IF NOT EXISTS `commission_payment_date` TIMESTAMP NULL DEFAULT NULL AFTER `commission_paid_amount`,
ADD COLUMN IF NOT EXISTS `supplier_payments_total` DECIMAL(15, 4) DEFAULT 0.0000 AFTER `commission_payment_date`,
ADD COLUMN IF NOT EXISTS `shipping_payments_total` DECIMAL(15, 4) DEFAULT 0.0000 AFTER `supplier_payments_total`,
ADD COLUMN IF NOT EXISTS `payment_history_json` TEXT NULL DEFAULT NULL AFTER `shipping_payments_total`,
ADD COLUMN IF NOT EXISTS `zakat_amount` DECIMAL(15, 4) DEFAULT 0.0000 AFTER `payment_history_json`;

ALTER TABLE `invoice` 
ADD CONSTRAINT IF NOT EXISTS `fk_invoice_commission_staff` 
FOREIGN KEY (`commission_staff_id`) REFERENCES `staff`(`staff_id`) 
ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `invoice` 
ADD INDEX IF NOT EXISTS `idx_production_status` (`production_status`),
ADD INDEX IF NOT EXISTS `idx_profit_loss_status` (`profit_loss_status`),
ADD INDEX IF NOT EXISTS `idx_completion_date` (`completion_date`),
ADD INDEX IF NOT EXISTS `idx_commission_staff_id` (`commission_staff_id`);

UPDATE invoice i
SET i.zakat_amount = (
    COALESCE(i.total_amount, 0) - 
    COALESCE((
        SELECT SUM(ii.quantity * p.new_unit_price_yen) / COALESCE(AVG(p.new_conversion_rate), 0.032)
        FROM invoice_item ii 
        JOIN price p ON p.product_id = ii.product_id 
        WHERE ii.invoice_id = i.invoice_id
    ), 0) -
    COALESCE((
        SELECT SUM(ii.quantity * p.new_unit_freight_cost_rm)
        FROM invoice_item ii 
        JOIN price p ON p.product_id = ii.product_id 
        WHERE ii.invoice_id = i.invoice_id
    ), 0)
) * 0.10
WHERE i.invoice_id IS NOT NULL;
