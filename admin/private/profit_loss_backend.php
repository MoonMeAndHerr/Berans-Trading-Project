<?php

if(session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../global/main_configuration.php';

/**
 * Profit & Loss Management Backend API
 * Handles profit/loss calculations, payment tracking, and order completion
 */

// Handle API requests
if(isset($_GET['action']) || isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    $pdo = openDB();
    $action = $_GET['action'] ?? $_POST['action'];
    
    switch($action) {
        case 'get_orders':
            echo json_encode(getProfitLossOrders($pdo));
            break;
            
        case 'get_order_details':
            if(isset($_GET['invoice_id'])) {
                echo json_encode(getOrderProfitDetails($pdo, $_GET['invoice_id']));
            } else {
                echo json_encode(['success' => false, 'error' => 'Invoice ID required']);
            }
            break;
            
        case 'add_supplier_payment':
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $invoiceId = $_POST['invoice_id'] ?? null;
                $amount = $_POST['amount'] ?? null;
                $description = $_POST['description'] ?? null;
                
                error_log("Supplier Payment Request - Invoice: $invoiceId, Amount: $amount, Description: $description");
                
                if($invoiceId && $amount) {
                    $result = addSupplierPayment($pdo, $invoiceId, $amount, $description);
                    error_log("Supplier Payment Result: " . json_encode($result));
                    echo json_encode($result);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Missing required fields: invoice_id=' . $invoiceId . ', amount=' . $amount]);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'POST method required']);
            }
            break;
            
        case 'add_shipping_payment':
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $invoiceId = $_POST['invoice_id'] ?? null;
                $amount = $_POST['amount'] ?? null;
                $description = $_POST['description'] ?? null;
                
                error_log("Shipping Payment Request - Invoice: $invoiceId, Amount: $amount, Description: $description");
                
                if($invoiceId && $amount) {
                    $result = addShippingPayment($pdo, $invoiceId, $amount, $description);
                    error_log("Shipping Payment Result: " . json_encode($result));
                    echo json_encode($result);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Missing required fields: invoice_id=' . $invoiceId . ', amount=' . $amount]);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'POST method required']);
            }
            break;
            
        case 'mark_complete':
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $invoiceId = $_POST['invoice_id'] ?? null;
                
                if($invoiceId) {
                    echo json_encode(markOrderComplete($pdo, $invoiceId));
                } else {
                    echo json_encode(['success' => false, 'error' => 'Invoice ID required']);
                }
            }
            break;
            
        case 'get_payment_history':
            if(isset($_GET['invoice_id'])) {
                echo json_encode(getPaymentHistory($pdo, $_GET['invoice_id']));
            } else {
                echo json_encode(['success' => false, 'error' => 'Invoice ID required']);
            }
            break;
            
        case 'delete_order':
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $invoiceId = $_POST['invoice_id'] ?? null;
                
                if($invoiceId) {
                    $result = deleteOrder($pdo, $invoiceId);
                    echo json_encode($result);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Invoice ID required']);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'POST method required']);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
    }
    
    closeDB($pdo);
    exit;
}

/**
 * Get all orders with profit/loss calculations
 */
function getProfitLossOrders($pdo) {
    try {
        // Get pagination parameters
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 6; // Max 6 records per page
        $offset = ($page - 1) * $limit;
        
        $query = "
            SELECT 
                i.invoice_id,
                i.invoice_number as order_number,
                i.status,
                i.created_at as order_date,
                i.supplier_payments_total,
                i.shipping_payments_total,
                (COALESCE(i.supplier_payments_total, 0) + COALESCE(i.shipping_payments_total, 0)) as total_paid,
                c.customer_name,
                c.customer_company_name,
                c.customer_phone,
                -- Calculate total supplier cost using new_unit_price_yen (same as modal)
                COALESCE((
                    SELECT SUM(p.new_unit_price_yen * ii.quantity) 
                    FROM invoice_item ii 
                    JOIN price p ON p.product_id = ii.product_id 
                    WHERE ii.invoice_id = i.invoice_id
                ), 0) as total_supplier_cost_yen,
                -- Calculate total shipping cost using new_unit_freight_cost_rm (same as modal)
                COALESCE((
                    SELECT SUM(p.new_unit_freight_cost_rm * ii.quantity) 
                    FROM invoice_item ii 
                    JOIN price p ON p.product_id = ii.product_id 
                    WHERE ii.invoice_id = i.invoice_id
                ), 0) as total_shipping_cost_rm,
                -- No total due calculation - keep separate like modal
                0 as total_due_yen,
                -- Calculate profit (revenue in RM - costs converted to RM)
                (i.total_amount - COALESCE((
                    SELECT SUM((p.new_unit_price_yen * p.conversion_rate + p.new_unit_freight_cost_rm) * ii.quantity) 
                    FROM invoice_item ii 
                    JOIN price p ON p.product_id = ii.product_id 
                    WHERE ii.invoice_id = i.invoice_id
                ), 0)) as total_profit
            FROM invoice i
            LEFT JOIN customer c ON i.customer_id = c.customer_id
            WHERE c.deleted_at IS NULL
            ORDER BY i.created_at DESC
            LIMIT $limit OFFSET $offset
        ";
        
        $stmt = $pdo->query($query);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get total count for pagination
        $countQuery = "
            SELECT COUNT(*) as total
            FROM invoice i
            LEFT JOIN customer c ON i.customer_id = c.customer_id
            WHERE c.deleted_at IS NULL
        ";
        $countStmt = $pdo->query($countQuery);
        $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalRecords / $limit);
        
        // Calculate remaining amount for each order - keep separate like modal
        foreach($orders as &$order) {
            $supplierCostYen = floatval($order['total_supplier_cost_yen']);
            $shippingCostRm = floatval($order['total_shipping_cost_rm']);
            $supplierPaidYen = floatval($order['supplier_payments_total']);
            $shippingPaidRm = floatval($order['shipping_payments_total']);
            
            // Calculate remaining separately (no currency mixing)
            $order['supplier_remaining_yen'] = max(0, $supplierCostYen - $supplierPaidYen);
            $order['shipping_remaining_rm'] = max(0, $shippingCostRm - $shippingPaidRm);
            
            // Total paid in their respective currencies
            $order['supplier_total_paid'] = $supplierPaidYen;
            $order['shipping_total_paid'] = $shippingPaidRm;
        }
        
        return [
            'success' => true, 
            'orders' => $orders,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_records' => $totalRecords,
                'limit' => $limit
            ]
        ];
        
    } catch(PDOException $e) {
        error_log("Error fetching profit/loss orders: " . $e->getMessage());
        return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];
    }
}

/**
 * Get detailed profit/loss breakdown for a specific order
 */
function getOrderProfitDetails($pdo, $invoiceId) {
    try {
        // Get order basic info
        $orderQuery = "
            SELECT 
                i.*,
                c.customer_name,
                c.customer_company_name,
                c.customer_phone,
                c.customer_address
            FROM invoice i
            LEFT JOIN customer c ON i.customer_id = c.customer_id
            WHERE i.invoice_id = ? AND c.deleted_at IS NULL
        ";
        
        $stmt = $pdo->prepare($orderQuery);
        $stmt->execute([$invoiceId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) {
            return ['success' => false, 'error' => 'Order not found'];
        }
        
        // Get item-level profit breakdown
        $itemsQuery = "
            SELECT 
                ii.invoice_item_id,
                ii.product_id,
                ii.quantity,
                ii.unit_price,
                ii.quantity * ii.unit_price as item_revenue,
                COALESCE(p.new_unit_price_yen, 0) as unit_supplier_cost_yen,
                COALESCE(p.new_unit_freight_cost_rm, 0) as unit_shipping_cost_rm,
                COALESCE(p.new_conversion_rate, 0.032) as conversion_rate,
                (ii.quantity * COALESCE(p.new_unit_price_yen, 0)) as total_supplier_cost_yen,
                (ii.quantity * COALESCE(p.new_unit_freight_cost_rm, 0)) as total_shipping_cost_rm,
                (ii.quantity * COALESCE(p.new_unit_price_yen, 0) * COALESCE(p.new_conversion_rate, 0.032)) + (ii.quantity * COALESCE(p.new_unit_freight_cost_rm, 0)) as total_cost_rm,
                (ii.quantity * ii.unit_price) - ((ii.quantity * COALESCE(p.new_unit_price_yen, 0) * COALESCE(p.new_conversion_rate, 0.032)) + (ii.quantity * COALESCE(p.new_unit_freight_cost_rm, 0))) as item_profit,
                COALESCE(pr.product_code, 'Unknown Product') as product_name
            FROM invoice_item ii
            LEFT JOIN price p ON p.product_id = ii.product_id
            LEFT JOIN product pr ON pr.product_id = ii.product_id
            WHERE ii.invoice_id = ?
            ORDER BY ii.invoice_item_id
        ";
        
        $stmt = $pdo->prepare($itemsQuery);
        $stmt->execute([$invoiceId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get payment history from JSON field
        $payments = [];
        if (!empty($order['payment_history_json'])) {
            $payments = json_decode($order['payment_history_json'], true) ?: [];
        }
        
        // Calculate totals
        $totalRevenue = array_sum(array_column($items, 'item_revenue'));
        $totalSupplierCostYen = array_sum(array_column($items, 'total_supplier_cost_yen'));
        $totalShippingCostRm = array_sum(array_column($items, 'total_shipping_cost_rm'));
        $totalProfit = array_sum(array_column($items, 'item_profit'));
        
        return [
            'success' => true,
            'order' => $order,
            'items' => $items,
            'payments' => $payments,
            'summary' => [
                'total_revenue' => $totalRevenue,
                'total_supplier_cost_yen' => $totalSupplierCostYen,
                'total_shipping_cost_yen' => $totalShippingCostRm, // Actually in RM but keeping the key name for frontend compatibility
                'total_profit' => $totalProfit,
                'supplier_payments_made' => $order['supplier_payments_total'] ?: 0,
                'shipping_payments_made' => $order['shipping_payments_total'] ?: 0,
                'supplier_balance' => $totalSupplierCostYen - ($order['supplier_payments_total'] ?: 0),
                'shipping_balance' => $totalShippingCostRm - ($order['shipping_payments_total'] ?: 0)
            ]
        ];
        
    } catch(PDOException $e) {
        error_log("Error fetching order profit details: " . $e->getMessage());
        return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];
    }
}

/**
 * Add supplier payment
 */
function addSupplierPayment($pdo, $invoiceId, $amount, $description = null) {
    try {
        // Get current payment history
        $stmt = $pdo->prepare("SELECT payment_history_json, supplier_payments_total FROM invoice WHERE invoice_id = ?");
        $stmt->execute([$invoiceId]);
        $current = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$current) {
            return ['success' => false, 'error' => 'Invoice not found'];
        }
        
        // Parse existing payment history
        $paymentHistory = [];
        if (!empty($current['payment_history_json'])) {
            $paymentHistory = json_decode($current['payment_history_json'], true) ?: [];
        }
        
        // Add new payment to history
        $newPayment = [
            'type' => 'supplier',
            'amount' => $amount,
            'description' => $description,
            'date' => date('Y-m-d H:i:s'),
            'timestamp' => time()
        ];
        $paymentHistory[] = $newPayment;
        
        // Update totals
        $newTotal = ($current['supplier_payments_total'] ?: 0) + $amount;
        
        // Update database
        $query = "
            UPDATE invoice 
            SET supplier_payments_total = ?, 
                payment_history_json = ?,
                supplier_payment_notes = CONCAT(COALESCE(supplier_payment_notes, ''), ?, '\n')
            WHERE invoice_id = ?
        ";
        
        $note = date('Y-m-d H:i:s') . " - Supplier payment: ¥" . number_format($amount, 2) . 
                ($description ? " - " . $description : "");
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([$newTotal, json_encode($paymentHistory), $note, $invoiceId]);
        
        return ['success' => true, 'message' => 'Supplier payment added successfully'];
        
    } catch(PDOException $e) {
        error_log("Error adding supplier payment: " . $e->getMessage());
        return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];
    }
}

/**
 * Add shipping payment
 */
function addShippingPayment($pdo, $invoiceId, $amount, $description = null) {
    try {
        // Get current payment history
        $stmt = $pdo->prepare("SELECT payment_history_json, shipping_payments_total FROM invoice WHERE invoice_id = ?");
        $stmt->execute([$invoiceId]);
        $current = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$current) {
            return ['success' => false, 'error' => 'Invoice not found'];
        }
        
        // Parse existing payment history
        $paymentHistory = [];
        if (!empty($current['payment_history_json'])) {
            $paymentHistory = json_decode($current['payment_history_json'], true) ?: [];
        }
        
        // Add new payment to history
        $newPayment = [
            'type' => 'shipping',
            'amount' => $amount,
            'description' => $description,
            'date' => date('Y-m-d H:i:s'),
            'timestamp' => time()
        ];
        $paymentHistory[] = $newPayment;
        
        // Update totals
        $newTotal = ($current['shipping_payments_total'] ?: 0) + $amount;
        
        // Update database
        $query = "
            UPDATE invoice 
            SET shipping_payments_total = ?, 
                payment_history_json = ?,
                shipping_payment_notes = CONCAT(COALESCE(shipping_payment_notes, ''), ?, '\n')
            WHERE invoice_id = ?
        ";
        
        $note = date('Y-m-d H:i:s') . " - Shipping payment: ¥" . number_format($amount, 2) . 
                ($description ? " - " . $description : "");
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([$newTotal, json_encode($paymentHistory), $note, $invoiceId]);
        
        return ['success' => true, 'message' => 'Shipping payment added successfully'];
        
    } catch(PDOException $e) {
        error_log("Error adding shipping payment: " . $e->getMessage());
        return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];
    }
}

/**
 * Mark order as complete
 */
function markOrderComplete($pdo, $invoiceId) {
    try {
        $query = "UPDATE invoice SET status = 'completed' WHERE invoice_id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$invoiceId]);
        
        return ['success' => true, 'message' => 'Order marked as complete'];
        
    } catch(PDOException $e) {
        error_log("Error marking order complete: " . $e->getMessage());
        return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];
    }
}

/**
 * Get payment history for an order
 */
function getPaymentHistory($pdo, $invoiceId) {
    try {
        $query = "
            SELECT 
                invoice_id,
                payment_history_json,
                supplier_payments_total,
                shipping_payments_total,
                supplier_payment_notes,
                shipping_payment_notes
            FROM invoice 
            WHERE invoice_id = ?
        ";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([$invoiceId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$result) {
            return ['success' => false, 'error' => 'Invoice not found'];
        }
        
        // Parse payment history JSON
        $payments = [];
        if (!empty($result['payment_history_json'])) {
            $payments = json_decode($result['payment_history_json'], true) ?: [];
        }
        
        return [
            'success' => true, 
            'payments' => $payments,
            'totals' => [
                'supplier_payments_total' => $result['supplier_payments_total'],
                'shipping_payments_total' => $result['shipping_payments_total']
            ],
            'notes' => [
                'supplier_notes' => $result['supplier_payment_notes'],
                'shipping_notes' => $result['shipping_payment_notes']
            ]
        ];
        
    } catch(PDOException $e) {
        error_log("Error fetching payment history: " . $e->getMessage());
        return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];
    }
}

/**
 * Delete an order and all its associated data
 * @param PDO $pdo Database connection
 * @param int $invoiceId Invoice ID to delete
 * @return array Success/error response
 */
function deleteOrder($pdo, $invoiceId) {
    try {
        $pdo->beginTransaction();
        
        // First check if order exists
        $checkStmt = $pdo->prepare("SELECT invoice_id FROM invoice WHERE invoice_id = ?");
        $checkStmt->execute([$invoiceId]);
        if (!$checkStmt->fetch()) {
            $pdo->rollBack();
            return ['success' => false, 'error' => 'Order not found'];
        }
        
        // Delete payment history first (foreign key constraint)
        $deletePaymentHistoryStmt = $pdo->prepare("DELETE FROM payment_history WHERE invoice_id = ?");
        $deletePaymentHistoryStmt->execute([$invoiceId]);
        
        // Delete invoice items (foreign key constraint)
        $deleteItemsStmt = $pdo->prepare("DELETE FROM invoice_item WHERE invoice_id = ?");
        $deleteItemsStmt->execute([$invoiceId]);
        
        // Delete the main invoice record
        $deleteInvoiceStmt = $pdo->prepare("DELETE FROM invoice WHERE invoice_id = ?");
        $deleteInvoiceStmt->execute([$invoiceId]);
        
        $pdo->commit();
        
        return [
            'success' => true, 
            'message' => 'Order deleted successfully',
            'deleted_invoice_id' => $invoiceId
        ];
        
    } catch(PDOException $e) {
        $pdo->rollBack();
        error_log("Error deleting order: " . $e->getMessage());
        return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];
    }
}

?>