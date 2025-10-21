<?php

if(session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../global/main_configuration.php';
use League\OAuth2\Client\Provider\GenericProvider;
use GuzzleHttp\Client;
/**
 * Profit & Loss Management Backend API
 * Handles profit/loss calculations, payment tracking, and order completion
 */

// Handle API requests
if(isset($_GET['action']) || isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    $pdo = openDB();
    $action = $_GET['action'] ?? $_POST['action'];
    
    // Debug logging
    error_log("=== PROFIT LOSS BACKEND ===");
    error_log("Action received: " . $action);
    error_log("GET action: " . ($_GET['action'] ?? 'not set'));
    error_log("POST action: " . ($_POST['action'] ?? 'not set'));
    error_log("Request method: " . $_SERVER['REQUEST_METHOD']);
    error_log("POST data: " . print_r($_POST, true));
    
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
                $stmt = $pdo->prepare("SELECT * FROM invoice WHERE invoice_id = ?");
                $stmt->execute([$invoiceId]);
                $invoice = $stmt->fetch(PDO::FETCH_ASSOC);
                $xero_relation = $invoice['xero_relation'] ?? null;

                    try {
                        $tokenData = refreshXeroToken();
                        $accessToken = $tokenData['access_token'];
                        $tenantId = $tokenData['tenant_id'];

                        $client = new Client();

                        // ✅ Simpler payload for single invoice
                        $voidPayload = [
                            'Status' => 'VOIDED'
                        ];

                        $response = $client->request('POST', "https://api.xero.com/api.xro/2.0/Invoices/{$xero_relation}", [
                            'headers' => [
                                'Authorization'  => 'Bearer ' . $accessToken,
                                'Xero-tenant-id' => $tenantId,
                                'Content-Type'   => 'application/json',
                                'Accept'         => 'application/json'
                            ],
                            'json' => $voidPayload
                        ]);

                        $responseBody = json_decode($response->getBody()->getContents(), true);
                        error_log("XERO_SUCCESS: Voided Xero invoice $xero_relation");
                        error_log(print_r($responseBody, true));

                    } catch (\GuzzleHttp\Exception\ClientException $e) {
                        $response = $e->getResponse();
                        $body = $response ? $response->getBody()->getContents() : 'No response body';
                        error_log("XERO_ERROR: Failed to void invoice. " . $body);
                    } catch (Exception $e) {
                        error_log("XERO_EXCEPTION: " . $e->getMessage());
                    }
                    
                    $result = deleteOrder($pdo, $invoiceId);

                    // echo json_encode($result);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Invoice ID required']);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'POST method required']);
            }
            break;
            
        case 'get_staff_commission_summary':
            $dateFrom = $_GET['date_from'] ?? null;
            $dateTo = $_GET['date_to'] ?? null;
            $staffId = $_GET['staff_id'] ?? null;
            
            if($dateFrom && $dateTo) {
                $result = getStaffCommissionSummary($pdo, $dateFrom, $dateTo, $staffId);
                echo json_encode($result);
            } else {
                echo json_encode(['success' => false, 'error' => 'Date range required']);
            }
            break;
            
        case 'add_payment_adjustment':
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $invoiceId = $_POST['invoice_id'] ?? null;
                $adjustmentType = $_POST['adjustment_type'] ?? null; // 'supplier' or 'shipping'
                $amount = $_POST['amount'] ?? null;
                $description = $_POST['description'] ?? null;
                
                error_log("Payment Adjustment Request - Invoice: $invoiceId, Type: $adjustmentType, Amount: $amount");
                
                if($invoiceId && $adjustmentType && $amount !== null) {
                    $result = addPaymentAdjustment($pdo, $invoiceId, $adjustmentType, $amount, $description);
                    echo json_encode($result);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'POST method required']);
            }
            break;
            
        case 'pay_staff_commission':
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $invoiceId = $_POST['invoice_id'] ?? null;
                $amount = $_POST['amount'] ?? null;
                $notes = $_POST['notes'] ?? '';
                
                if($invoiceId && $amount) {
                    $result = payStaffCommission($pdo, $invoiceId, $amount, $notes);
                    echo json_encode($result);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Invoice ID and amount required']);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'POST method required']);
            }
            break;
            
        case 'get_payment_summaries':
            $monthFilter = $_GET['month'] ?? '';
            $searchFilter = $_GET['search'] ?? '';
            $dateFromFilter = $_GET['date_from'] ?? '';
            $dateToFilter = $_GET['date_to'] ?? '';
            
            $result = getPaymentSummaries($pdo, $monthFilter, $searchFilter, $dateFromFilter, $dateToFilter);
            echo json_encode($result);
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
        
        // Get filter parameters
        $monthFilter = isset($_GET['month']) ? $_GET['month'] : '';
        $searchFilter = isset($_GET['search']) ? $_GET['search'] : '';
        $dateFromFilter = isset($_GET['date_from']) ? $_GET['date_from'] : '';
        $dateToFilter = isset($_GET['date_to']) ? $_GET['date_to'] : '';
        
        // Build WHERE conditions
        $whereConditions = ["c.deleted_at IS NULL"];
        $queryParams = [];
        
        // Month filter
        if (!empty($monthFilter)) {
            $whereConditions[] = "DATE_FORMAT(i.created_at, '%Y-%m') = ?";
            $queryParams[] = $monthFilter;
        }
        
        // Date range filter
        if (!empty($dateFromFilter)) {
            $whereConditions[] = "DATE(i.created_at) >= ?";
            $queryParams[] = $dateFromFilter;
        }
        if (!empty($dateToFilter)) {
            $whereConditions[] = "DATE(i.created_at) <= ?";
            $queryParams[] = $dateToFilter;
        }
        
        // Search filter
        if (!empty($searchFilter)) {
            $whereConditions[] = "(i.invoice_number LIKE ? OR c.customer_name LIKE ? OR c.customer_company_name LIKE ? OR DATE_FORMAT(i.created_at, '%Y-%m-%d') LIKE ?)";
            $searchTerm = '%' . $searchFilter . '%';
            $queryParams[] = $searchTerm;
            $queryParams[] = $searchTerm;
            $queryParams[] = $searchTerm;
            $queryParams[] = $searchTerm;
        }
        
        $whereClause = implode(' AND ', $whereConditions);
        
        $query = "
            SELECT 
                i.invoice_id,
                i.invoice_number as order_number,
                i.status,
                i.created_at as order_date,
                i.total_amount as total_revenue,
                i.supplier_payments_total,
                i.shipping_payments_total,
                (COALESCE(i.supplier_payments_total, 0) + COALESCE(i.shipping_payments_total, 0)) as total_paid,
                c.customer_name,
                c.customer_company_name,
                c.customer_phone,
                -- Staff commission data
                i.commission_staff_id,
                i.commission_percentage,
                i.commission_paid_amount,
                i.commission_payment_date,
                s.staff_name,
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
                -- Calculate theoretical profit (revenue - theoretical costs)
                (i.total_amount - COALESCE((
                    SELECT SUM((p.new_unit_price_yen / COALESCE(p.new_conversion_rate, 0.032) + p.new_unit_freight_cost_rm) * ii.quantity) 
                    FROM invoice_item ii 
                    JOIN price p ON p.product_id = ii.product_id 
                    WHERE ii.invoice_id = i.invoice_id
                ), 0)) as theoretical_profit,
                -- Calculate ACTUAL profit (revenue - actual payments made)
                -- Convert supplier payments from yen to RM using average conversion rate, then subtract both payments from revenue
                (i.total_amount - (
                    COALESCE(i.supplier_payments_total, 0) / COALESCE((
                        SELECT AVG(p.new_conversion_rate) 
                        FROM invoice_item ii 
                        JOIN price p ON p.product_id = ii.product_id 
                        WHERE ii.invoice_id = i.invoice_id AND p.new_conversion_rate > 0
                    ), 0.032) + COALESCE(i.shipping_payments_total, 0)
                )) as actual_profit_loss,
                -- Add average conversion rate for frontend calculations
                COALESCE((
                    SELECT AVG(p.new_conversion_rate) 
                    FROM invoice_item ii 
                    JOIN price p ON p.product_id = ii.product_id 
                    WHERE ii.invoice_id = i.invoice_id AND p.new_conversion_rate > 0
                ), 0.032) as avg_conversion_rate
            FROM invoice i
            LEFT JOIN customer c ON i.customer_id = c.customer_id
            LEFT JOIN staff s ON i.commission_staff_id = s.staff_id
            WHERE $whereClause
            ORDER BY i.created_at DESC
            LIMIT $limit OFFSET $offset
        ";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute($queryParams);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get total count for pagination with same filters
        $countQuery = "
            SELECT COUNT(*) as total
            FROM invoice i
            LEFT JOIN customer c ON i.customer_id = c.customer_id
            LEFT JOIN staff s ON i.commission_staff_id = s.staff_id
            WHERE $whereClause
        ";
        $countStmt = $pdo->prepare($countQuery);
        $countStmt->execute($queryParams);
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
                c.customer_address,
                s.staff_name
            FROM invoice i
            LEFT JOIN customer c ON i.customer_id = c.customer_id
            LEFT JOIN staff s ON i.commission_staff_id = s.staff_id
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
                (ii.quantity * COALESCE(p.new_unit_price_yen, 0) / COALESCE(p.new_conversion_rate, 0.032)) + (ii.quantity * COALESCE(p.new_unit_freight_cost_rm, 0)) as total_cost_rm,
                (ii.quantity * ii.unit_price) - ((ii.quantity * COALESCE(p.new_unit_price_yen, 0) / COALESCE(p.new_conversion_rate, 0.032)) + (ii.quantity * COALESCE(p.new_unit_freight_cost_rm, 0))) as item_profit,
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
        
        // Debug: Log the first item to see actual values
        if (!empty($items)) {
            error_log("Debug - First item data: " . json_encode($items[0]));
            error_log("Debug - Unit shipping cost: " . ($items[0]['unit_shipping_cost_rm'] ?? 'NULL'));
            error_log("Debug - Total cost: " . ($items[0]['total_cost_rm'] ?? 'NULL'));
        }
        
        // Get payment history from JSON field
        $payments = [];
        if (!empty($order['payment_history_json'])) {
            $payments = json_decode($order['payment_history_json'], true) ?: [];
        }
        
        // Calculate totals
        // For commission: use pre-discount revenue (sum of items)
        // For profit/revenue display: use post-discount total_amount
        $totalRevenueBeforeDiscount = array_sum(array_column($items, 'item_revenue')); // Pre-discount for commission
        $totalRevenueAfterDiscount = floatval($order['total_amount']); // Post-discount for profit calculation
        $totalSupplierCostYen = array_sum(array_column($items, 'total_supplier_cost_yen'));
        $totalShippingCostRm = array_sum(array_column($items, 'total_shipping_cost_rm'));
        $totalProfit = array_sum(array_column($items, 'item_profit'));
        
        // Calculate actual profit/loss (revenue - actual payments made)
        $supplierPaymentsMade = $order['supplier_payments_total'] ?: 0;
        $shippingPaymentsMade = $order['shipping_payments_total'] ?: 0;
        
        // Convert supplier payments from yen to RM using average conversion rate
        $avgConversionRate = 0.032; // Default rate
        if (!empty($items)) {
            $rates = array_column($items, 'conversion_rate');
            $validRates = array_filter($rates, function($rate) { return $rate > 0; });
            if (!empty($validRates)) {
                $avgConversionRate = array_sum($validRates) / count($validRates);
            }
        }
        
        $actualProfitLoss = $totalRevenueAfterDiscount - ($supplierPaymentsMade / $avgConversionRate + $shippingPaymentsMade);
        
        return [
            'success' => true,
            'order' => $order,
            'items' => $items,
            'payments' => $payments,
            'summary' => [
                'total_revenue' => $totalRevenueAfterDiscount, // Discounted total (what customer pays)
                'total_revenue_before_discount' => $totalRevenueBeforeDiscount, // Sum of items (before discount)
                'total_supplier_cost_yen' => $totalSupplierCostYen,
                'total_shipping_cost_rm' => $totalShippingCostRm, // Correctly named as RM
                'total_profit' => $totalProfit,
                'actual_profit_loss' => $actualProfitLoss,
                'supplier_payments_made' => $supplierPaymentsMade,
                'shipping_payments_made' => $shippingPaymentsMade,
                'supplier_balance' => $totalSupplierCostYen - $supplierPaymentsMade,
                'shipping_balance' => $totalShippingCostRm - $shippingPaymentsMade,
                'avg_conversion_rate' => $avgConversionRate // Add average conversion rate
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
        // Get invoice items to calculate average conversion rate
        $stmt = $pdo->prepare("
            SELECT COALESCE(p.new_conversion_rate, 0.032) as conversion_rate
            FROM invoice_item ii 
            LEFT JOIN price p ON p.product_id = ii.product_id 
            WHERE ii.invoice_id = ? AND p.new_conversion_rate > 0
        ");
        $stmt->execute([$invoiceId]);
        $rates = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Calculate average conversion rate
        $avgConversionRate = 0.032; // Default rate
        if (!empty($rates)) {
            $avgConversionRate = array_sum($rates) / count($rates);
        }
        
        // Convert RM to Yen for storage (since user now enters RM but DB stores Yen)
        $amountYen = $amount * $avgConversionRate; // FIXED: RM × conversion_rate = Yen
        
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
        
        // Add new payment to history (store Yen in history for consistency)
        $newPayment = [
            'type' => 'supplier',
            'amount' => $amountYen, // Store as Yen
            'amount_rm' => $amount, // Also store original RM amount for reference
            'conversion_rate' => $avgConversionRate, // Store the conversion rate used
            'description' => $description,
            'date' => date('Y-m-d H:i:s'),
            'timestamp' => time()
        ];
        $paymentHistory[] = $newPayment;
        
        // Update totals (store Yen)
        $newTotal = ($current['supplier_payments_total'] ?: 0) + $amountYen;
        
        // Update database
        $query = "
            UPDATE invoice 
            SET supplier_payments_total = ?, 
                payment_history_json = ?,
                supplier_payment_notes = CONCAT(COALESCE(supplier_payment_notes, ''), ?, '\n')
            WHERE invoice_id = ?
        ";
        
        $note = date('Y-m-d H:i:s') . " - Supplier payment: RM " . number_format($amount, 2) . " (¥" . number_format($amountYen, 2) . " @ rate " . number_format($avgConversionRate, 4) . ")" .
                ($description ? " - " . $description : "");
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([$newTotal, json_encode($paymentHistory), $note, $invoiceId]);
        
        return ['success' => true, 'message' => 'Supplier payment added successfully'];
        
    } catch(PDOException $e) {
        error_log("Error adding supplier payment: " . $e->getMessage());
        return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];
    }
}/**
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

/**
 * Get staff commission summary for a date range
 */
function getStaffCommissionSummary($pdo, $dateFrom, $dateTo, $staffId = null) {
    try {
        $whereConditions = ["i.created_at >= ?", "i.created_at <= ?"];
        $queryParams = [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'];
        
        // If specific staff selected, filter by that staff
        if ($staffId) {
            $whereConditions[] = "i.commission_staff_id = ?";
            $queryParams[] = $staffId;
        }
        
        $whereClause = implode(' AND ', $whereConditions);
        
        $query = "
            SELECT 
                s.staff_id,
                s.staff_name,
                COUNT(i.invoice_id) as total_orders,
                SUM(i.total_amount) as total_revenue,
                SUM(
                    (i.total_amount - (
                        COALESCE(i.supplier_payments_total, 0) * COALESCE((
                            SELECT AVG(p.new_conversion_rate) 
                            FROM invoice_item ii 
                            JOIN price p ON p.product_id = ii.product_id 
                            WHERE ii.invoice_id = i.invoice_id AND p.new_conversion_rate > 0
                        ), 0.032) + COALESCE(i.shipping_payments_total, 0)
                    ))
                ) as total_profit,
                SUM(
                    CASE WHEN i.commission_percentage > 0 
                    THEN COALESCE((
                        SELECT SUM(ii.quantity * ii.unit_price) 
                        FROM invoice_item ii 
                        WHERE ii.invoice_id = i.invoice_id
                    ), i.total_amount) * (i.commission_percentage / 100)
                    ELSE 0 END
                ) as total_commission_due,
                SUM(COALESCE(i.commission_paid_amount, 0)) as total_commission_paid,
                SUM(
                    CASE WHEN i.commission_percentage > 0 
                    THEN (COALESCE((
                        SELECT SUM(ii.quantity * ii.unit_price) 
                        FROM invoice_item ii 
                        WHERE ii.invoice_id = i.invoice_id
                    ), i.total_amount) * (i.commission_percentage / 100)) - COALESCE(i.commission_paid_amount, 0)
                    ELSE 0 END
                ) as total_commission_remaining
            FROM invoice i
            LEFT JOIN staff s ON i.commission_staff_id = s.staff_id
            WHERE $whereClause AND i.commission_staff_id IS NOT NULL
            GROUP BY s.staff_id, s.staff_name
            ORDER BY total_commission_remaining DESC
        ";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute($queryParams);
        $staffSummary = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get overall summary
        $summaryQuery = "
            SELECT 
                COUNT(i.invoice_id) as total_orders,
                SUM(i.total_amount) as total_revenue,
                SUM(
                    (i.total_amount - (
                        COALESCE(i.supplier_payments_total, 0) * COALESCE((
                            SELECT AVG(p.new_conversion_rate) 
                            FROM invoice_item ii 
                            JOIN price p ON p.product_id = ii.product_id 
                            WHERE ii.invoice_id = i.invoice_id AND p.new_conversion_rate > 0
                        ), 0.032) + COALESCE(i.shipping_payments_total, 0)
                    ))
                ) as total_profit,
                SUM(
                    CASE WHEN i.commission_percentage > 0 
                    THEN COALESCE((
                        SELECT SUM(ii.quantity * ii.unit_price) 
                        FROM invoice_item ii 
                        WHERE ii.invoice_id = i.invoice_id
                    ), i.total_amount) * (i.commission_percentage / 100)
                    ELSE 0 END
                ) as total_commission_due,
                SUM(COALESCE(i.commission_paid_amount, 0)) as total_commission_paid
            FROM invoice i
            WHERE $whereClause AND i.commission_staff_id IS NOT NULL
        ";
        
        $summaryStmt = $pdo->prepare($summaryQuery);
        $summaryStmt->execute($queryParams);
        $overallSummary = $summaryStmt->fetch(PDO::FETCH_ASSOC);
        
        return [
            'success' => true,
            'staff_summary' => $staffSummary,
            'overall_summary' => $overallSummary,
            'date_range' => ['from' => $dateFrom, 'to' => $dateTo]
        ];
        
    } catch(PDOException $e) {
        error_log("Error getting staff commission summary: " . $e->getMessage());
        return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];
    }
}

/**
 * Pay staff commission for a specific order
 */
function payStaffCommission($pdo, $invoiceId, $amount, $notes = '') {
    try {
        // First get the order details to validate commission
        $stmt = $pdo->prepare("
            SELECT 
                i.commission_staff_id, 
                i.commission_percentage, 
                i.commission_paid_amount,
                i.total_amount,
                i.supplier_payments_total,
                i.shipping_payments_total,
                -- Calculate theoretical profit (revenue - theoretical costs)
                (i.total_amount - COALESCE((
                    SELECT SUM((p.new_unit_price_yen * COALESCE(p.new_conversion_rate, 0.032) + p.new_unit_freight_cost_rm) * ii.quantity) 
                    FROM invoice_item ii 
                    JOIN price p ON p.product_id = ii.product_id 
                    WHERE ii.invoice_id = i.invoice_id
                ), 0)) as theoretical_profit,
                -- Calculate ACTUAL profit (revenue - actual payments made)
                (i.total_amount - (
                    COALESCE(i.supplier_payments_total, 0) * COALESCE((
                        SELECT AVG(p.new_conversion_rate) 
                        FROM invoice_item ii 
                        JOIN price p ON p.product_id = ii.product_id 
                        WHERE ii.invoice_id = i.invoice_id AND p.new_conversion_rate > 0
                    ), 0.032) + COALESCE(i.shipping_payments_total, 0)
                )) as actual_profit_loss,
                s.staff_name
            FROM invoice i 
            LEFT JOIN staff s ON i.commission_staff_id = s.staff_id 
            WHERE i.invoice_id = ?
        ");
        $stmt->execute([$invoiceId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) {
            return ['success' => false, 'error' => 'Order not found'];
        }
        
        if (!$order['commission_staff_id']) {
            return ['success' => false, 'error' => 'No staff assigned to this order'];
        }
        
        // Calculate commission amounts based on revenue (total_amount)
        $commissionDue = $order['total_amount'] * ($order['commission_percentage'] / 100);
        $alreadyPaid = floatval($order['commission_paid_amount']);
        $newTotalPaid = $alreadyPaid + floatval($amount);
        
        // Get current payment history
        $historyStmt = $pdo->prepare("SELECT payment_history_json FROM invoice WHERE invoice_id = ?");
        $historyStmt->execute([$invoiceId]);
        $historyData = $historyStmt->fetch(PDO::FETCH_ASSOC);
        
        // Parse existing payment history
        $paymentHistory = [];
        if (!empty($historyData['payment_history_json'])) {
            $paymentHistory = json_decode($historyData['payment_history_json'], true) ?: [];
        }
        
        // Add new commission payment to history
        $newPayment = [
            'type' => 'commission',
            'amount' => floatval($amount),
            'description' => $notes,
            'staff_name' => $order['staff_name'],
            'date' => date('Y-m-d H:i:s'),
            'timestamp' => time()
        ];
        $paymentHistory[] = $newPayment;
        
        // Update the commission payment and payment history
        $updateStmt = $pdo->prepare("
            UPDATE invoice 
            SET commission_paid_amount = ?, 
                commission_payment_date = NOW(),
                commission_payment_notes = ?,
                payment_history_json = ?
            WHERE invoice_id = ?
        ");
        
        $updateStmt->execute([$newTotalPaid, $notes, json_encode($paymentHistory), $invoiceId]);
        
        return [
            'success' => true,
            'message' => 'Commission payment recorded successfully',
            'staff_name' => $order['staff_name'],
            'payment_amount' => floatval($amount),
            'total_paid' => $newTotalPaid,
            'commission_due' => $commissionDue,
            'remaining' => $commissionDue - $newTotalPaid
        ];
        
    } catch(PDOException $e) {
        error_log("Error paying staff commission: " . $e->getMessage());
        return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];
    }
}

/**
 * Get payment summaries based on current filters
 */
function getPaymentSummaries($pdo, $monthFilter = '', $searchFilter = '', $dateFromFilter = '', $dateToFilter = '') {
    try {
        // Build WHERE conditions (same as main query)
        $whereConditions = ["c.deleted_at IS NULL"];
        $queryParams = [];
        
        // Month filter
        if (!empty($monthFilter)) {
            $whereConditions[] = "DATE_FORMAT(i.created_at, '%Y-%m') = ?";
            $queryParams[] = $monthFilter;
        }
        
        // Date range filter
        if (!empty($dateFromFilter)) {
            $whereConditions[] = "DATE(i.created_at) >= ?";
            $queryParams[] = $dateFromFilter;
        }
        if (!empty($dateToFilter)) {
            $whereConditions[] = "DATE(i.created_at) <= ?";
            $queryParams[] = $dateToFilter;
        }
        
        // Search filter
        if (!empty($searchFilter)) {
            $whereConditions[] = "(i.invoice_number LIKE ? OR c.customer_name LIKE ? OR c.customer_company_name LIKE ? OR DATE_FORMAT(i.created_at, '%Y-%m-%d') LIKE ?)";
            $searchTerm = '%' . $searchFilter . '%';
            $queryParams[] = $searchTerm;
            $queryParams[] = $searchTerm;
            $queryParams[] = $searchTerm;
            $queryParams[] = $searchTerm;
        }
        
        $whereClause = implode(' AND ', $whereConditions);
        
        $query = "
            SELECT 
                -- Commission calculations
                SUM(COALESCE(i.commission_paid_amount, 0)) as total_commission_payments,
                SUM(
                    CASE 
                        WHEN i.commission_staff_id IS NOT NULL AND i.commission_percentage > 0 
                        THEN (i.total_amount * (i.commission_percentage / 100)) - COALESCE(i.commission_paid_amount, 0)
                        ELSE 0 
                    END
                ) as total_commission_remaining,
                
                -- Shipping calculations (already in RM)
                SUM(COALESCE(i.shipping_payments_total, 0)) as total_shipping_payments,
                SUM(
                    COALESCE((
                        SELECT SUM(ii.quantity * p.new_unit_freight_cost_rm) 
                        FROM invoice_item ii 
                        JOIN price p ON p.product_id = ii.product_id 
                        WHERE ii.invoice_id = i.invoice_id
                    ), 0) - COALESCE(i.shipping_payments_total, 0)
                ) as total_shipping_remaining,
                
                -- Supplier calculations (convert from Yen to RM)
                SUM(
                    COALESCE(i.supplier_payments_total, 0) / COALESCE((
                        SELECT AVG(p.new_conversion_rate) 
                        FROM invoice_item ii 
                        JOIN price p ON p.product_id = ii.product_id 
                        WHERE ii.invoice_id = i.invoice_id AND p.new_conversion_rate > 0
                    ), 0.032)
                ) as total_supplier_payments_rm,
                SUM(
                    COALESCE((
                        SELECT SUM(ii.quantity * p.new_unit_price_yen) 
                        FROM invoice_item ii 
                        JOIN price p ON p.product_id = ii.product_id 
                        WHERE ii.invoice_id = i.invoice_id
                    ), 0) / COALESCE((
                        SELECT AVG(p.new_conversion_rate) 
                        FROM invoice_item ii 
                        JOIN price p ON p.product_id = ii.product_id 
                        WHERE ii.invoice_id = i.invoice_id AND p.new_conversion_rate > 0
                    ), 0.032) - 
                    COALESCE(i.supplier_payments_total, 0) / COALESCE((
                        SELECT AVG(p.new_conversion_rate) 
                        FROM invoice_item ii 
                        JOIN price p ON p.product_id = ii.product_id 
                        WHERE ii.invoice_id = i.invoice_id AND p.new_conversion_rate > 0
                    ), 0.032)
                ) as total_supplier_remaining_rm
            FROM invoice i
            LEFT JOIN customer c ON i.customer_id = c.customer_id
            WHERE $whereClause
        ";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute($queryParams);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return [
            'success' => true,
            'summaries' => [
                'total_commission_payments' => floatval($result['total_commission_payments'] ?? 0),
                'total_commission_remaining' => floatval($result['total_commission_remaining'] ?? 0),
                'total_shipping_payments' => floatval($result['total_shipping_payments'] ?? 0),
                'total_shipping_remaining' => floatval($result['total_shipping_remaining'] ?? 0),
                'total_supplier_payments' => floatval($result['total_supplier_payments_rm'] ?? 0),
                'total_supplier_remaining' => floatval($result['total_supplier_remaining_rm'] ?? 0)
            ]
        ];
        
    } catch(PDOException $e) {
        error_log("Error fetching payment summaries: " . $e->getMessage());
        return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];
    }
}

/**
 * Add payment adjustment (positive or negative) after order update
 */
function addPaymentAdjustment($pdo, $invoiceId, $adjustmentType, $amount, $description = null) {
    try {
        // Get invoice items to calculate average conversion rate
        $stmt = $pdo->prepare("
            SELECT COALESCE(p.new_conversion_rate, 0.032) as conversion_rate
            FROM invoice_item ii 
            LEFT JOIN price p ON p.product_id = ii.product_id 
            WHERE ii.invoice_id = ? AND p.new_conversion_rate > 0
        ");
        $stmt->execute([$invoiceId]);
        $rates = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Calculate average conversion rate
        $avgConversionRate = 0.032; // Default rate
        if (!empty($rates)) {
            $avgConversionRate = array_sum($rates) / count($rates);
        }
        
        // Get current payment history and totals
        if ($adjustmentType === 'supplier') {
            $stmt = $pdo->prepare("SELECT payment_history_json, supplier_payments_total FROM invoice WHERE invoice_id = ?");
        } else {
            $stmt = $pdo->prepare("SELECT payment_history_json, shipping_payments_total FROM invoice WHERE invoice_id = ?");
        }
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
        
        // Prepare adjustment payment
        if ($adjustmentType === 'supplier') {
            // Convert RM to Yen for storage (RM × rate = Yen, where rate is Yen per 1 RM)
            $amountYen = $amount * $avgConversionRate;
            
            $newPayment = [
                'type' => 'supplier',
                'amount' => $amountYen, // Store as Yen
                'amount_rm' => $amount, // Also store original RM amount
                'conversion_rate' => $avgConversionRate,
                'description' => '⚖️ ADJUSTMENT: ' . ($description ?: 'Cost changed after order update'),
                'is_adjustment' => true,
                'date' => date('Y-m-d H:i:s'),
                'timestamp' => time()
            ];
            
            $newTotal = ($current['supplier_payments_total'] ?: 0) + $amountYen;
            $field = 'supplier_payments_total';
            $noteField = 'supplier_payment_notes';
            $note = date('Y-m-d H:i:s') . " - ⚖️ PAYMENT ADJUSTMENT: RM " . number_format($amount, 2) . 
                    " (¥" . number_format($amountYen, 2) . " @ rate " . number_format($avgConversionRate, 4) . ")" .
                    ($description ? " - " . $description : " - Cost adjustment after order update");
        } else {
            $newPayment = [
                'type' => 'shipping',
                'amount' => $amount, // Shipping is in RM
                'description' => '⚖️ ADJUSTMENT: ' . ($description ?: 'Cost changed after order update'),
                'is_adjustment' => true,
                'date' => date('Y-m-d H:i:s'),
                'timestamp' => time()
            ];
            
            $newTotal = ($current['shipping_payments_total'] ?: 0) + $amount;
            $field = 'shipping_payments_total';
            $noteField = 'shipping_payment_notes';
            $note = date('Y-m-d H:i:s') . " - ⚖️ PAYMENT ADJUSTMENT: RM " . number_format($amount, 2) .
                    ($description ? " - " . $description : " - Cost adjustment after order update");
        }
        
        $paymentHistory[] = $newPayment;
        
        // Update database
        $query = "
            UPDATE invoice 
            SET $field = ?, 
                payment_history_json = ?,
                $noteField = CONCAT(COALESCE($noteField, ''), ?, '\n')
            WHERE invoice_id = ?
        ";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([$newTotal, json_encode($paymentHistory), $note, $invoiceId]);
        
        $adjustmentLabel = $amount >= 0 ? 'increase' : 'reduction';
        return [
            'success' => true, 
            'message' => ucfirst($adjustmentType) . " payment adjustment recorded successfully (RM " . number_format(abs($amount), 2) . " " . $adjustmentLabel . ")"
        ];
        
    } catch(PDOException $e) {
        error_log("Error adding payment adjustment: " . $e->getMessage());
        return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];
    }
}

?>