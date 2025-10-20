<?php

// Ensure no output before JSON responses
if(session_status() === PHP_SESSION_NONE) session_start();

// Set error reporting for JSON responses
if (isset($_GET['action']) || isset($_POST['action'])) {
    // For AJAX requests, suppress PHP errors that could break JSON
    error_reporting(E_ERROR | E_PARSE);
    ini_set('display_errors', 0);
}

require_once __DIR__ . '/../../global/main_configuration.php';
require_once __DIR__ . '/../public/refresh_xero_token.php';
use League\OAuth2\Client\Provider\GenericProvider;
use GuzzleHttp\Client;

$pdo = openDB();

// Function to get all orders with customer info
function getOrderTabs() {
    global $pdo;
    $query = "
        SELECT 
            i.invoice_id,
            i.invoice_number,
            i.total_amount as original_total,
            (i.total_amount - COALESCE((SELECT SUM(amount_paid) FROM payment_history WHERE invoice_id = i.invoice_id), 0)) as total_amount,
            i.created_at,
            i.status,
            c.customer_name,
            c.customer_company_name,
            c.customer_phone,
            (SELECT SUM(amount_paid) FROM payment_history WHERE invoice_id = i.invoice_id) as total_paid,
            COALESCE(i.supplier_payments_total, 0) as supplier_payments_total,
            COALESCE(i.shipping_payments_total, 0) as shipping_payments_total,
            COALESCE(i.commission_paid_amount, 0) as commission_paid_amount,
            (
                SELECT COALESCE(AVG(p.new_conversion_rate), 0.032)
                FROM invoice_item ii
                LEFT JOIN price p ON ii.product_id = p.product_id
                WHERE ii.invoice_id = i.invoice_id
            ) as conversion_rate,
            CASE 
                WHEN EXISTS (
                    SELECT 1 FROM payment_history 
                    WHERE invoice_id = i.invoice_id 
                    LIMIT 1
                ) THEN 1
                ELSE 0
            END as has_payment,
            CASE 
                WHEN EXISTS (
                    SELECT 1 FROM payment_history 
                    WHERE invoice_id = i.invoice_id 
                    LIMIT 1
                ) THEN (
                    SELECT MAX(pr.production_lead_time + COALESCE(psh.delivery_days, 0)) 
                    FROM invoice_item ii 
                    JOIN product pr ON pr.product_id = ii.product_id 
                    LEFT JOIN price p ON p.product_id = pr.product_id
                    LEFT JOIN price_shipping psh ON p.new_freight_method = psh.shipping_code
                    WHERE ii.invoice_id = i.invoice_id
                )
                ELSE NULL
            END as max_lead_time,
            CASE 
                WHEN EXISTS (
                    SELECT 1 FROM payment_history 
                    WHERE invoice_id = i.invoice_id 
                    LIMIT 1
                ) THEN (
                    SELECT MAX(pr.production_lead_time) 
                    FROM invoice_item ii 
                    JOIN product pr ON pr.product_id = ii.product_id 
                    WHERE ii.invoice_id = i.invoice_id
                )
                ELSE NULL
            END as max_production_lead_time,
            CASE 
                WHEN EXISTS (
                    SELECT 1 FROM payment_history 
                    WHERE invoice_id = i.invoice_id 
                    LIMIT 1
                ) THEN (
                    SELECT psh.delivery_days
                    FROM invoice_item ii 
                    JOIN product pr ON pr.product_id = ii.product_id 
                    LEFT JOIN price p ON p.product_id = pr.product_id
                    LEFT JOIN price_shipping psh ON p.new_freight_method = psh.shipping_code
                    WHERE ii.invoice_id = i.invoice_id
                    AND pr.production_lead_time = (
                        SELECT MAX(pr2.production_lead_time) 
                        FROM invoice_item ii2 
                        JOIN product pr2 ON pr2.product_id = ii2.product_id 
                        WHERE ii2.invoice_id = i.invoice_id
                    )
                    LIMIT 1
                )
                ELSE NULL
            END as delivery_days,
            CASE 
                WHEN EXISTS (
                    SELECT 1 FROM payment_history 
                    WHERE invoice_id = i.invoice_id 
                    LIMIT 1
                ) THEN (
                    SELECT psh.shipping_name
                    FROM invoice_item ii 
                    JOIN product pr ON pr.product_id = ii.product_id 
                    LEFT JOIN price p ON p.product_id = pr.product_id
                    LEFT JOIN price_shipping psh ON p.new_freight_method = psh.shipping_code
                    WHERE ii.invoice_id = i.invoice_id
                    AND pr.production_lead_time = (
                        SELECT MAX(pr2.production_lead_time) 
                        FROM invoice_item ii2 
                        JOIN product pr2 ON pr2.product_id = ii2.product_id 
                        WHERE ii2.invoice_id = i.invoice_id
                    )
                    LIMIT 1
                )
                ELSE NULL
            END as shipping_method_name,
            CASE 
                WHEN EXISTS (
                    SELECT 1 FROM payment_history 
                    WHERE invoice_id = i.invoice_id 
                    LIMIT 1
                ) THEN DATE_ADD(CURRENT_DATE(), 
                    INTERVAL (
                        SELECT MAX(pr.production_lead_time + COALESCE(psh.delivery_days, 0)) 
                        FROM invoice_item ii 
                        JOIN product pr ON pr.product_id = ii.product_id 
                        LEFT JOIN price p ON p.product_id = pr.product_id
                        LEFT JOIN price_shipping psh ON p.new_freight_method = psh.shipping_code
                        WHERE ii.invoice_id = i.invoice_id
                    ) DAY
                )
                ELSE NULL
            END as estimated_completion_date
        FROM invoice i
        LEFT JOIN customer c ON i.customer_id = c.customer_id
        WHERE c.deleted_at IS NULL
        ORDER BY i.created_at DESC
    ";
    
    try {
        $stmt = $pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error fetching orders: " . $e->getMessage());
        return [];
    }
}

// Function to get specific order details
function getOrderDetails($invoice_id) {
    global $pdo;
    $query = "
        SELECT 
            i.*,
            c.customer_name,
            c.customer_company_name,
            c.customer_phone,
            c.customer_address,
            c.customer_designation
        FROM invoice i
        LEFT JOIN customer c ON i.customer_id = c.customer_id
        WHERE i.invoice_id = ? AND c.deleted_at IS NULL
    ";
    
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute([$invoice_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error fetching order details: " . $e->getMessage());
        return null;
    }
}

// Function to get order items
function getOrderItems($invoice_id) {
    global $pdo;
    $query = "
        SELECT 
            ii.invoice_item_id,
            ii.product_name,
            ii.unit_price,
            ii.quantity,
            ii.total_price,
            ii.created_at
        FROM invoice_item ii         /* Changed from invoice_items to invoice_item */
        WHERE ii.invoice_id = ?
        ORDER BY ii.created_at ASC
    ";
    
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute([$invoice_id]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Debug logging
        error_log("Retrieved " . count($items) . " items for invoice_id: " . $invoice_id);
        error_log("SQL Query: " . $query);
        
        return $items;
    } catch(PDOException $e) {
        error_log("Error fetching order items: " . $e->getMessage());
        error_log("SQL Query: " . $query);
        return [];
    }
}

// Add/update this function in your backend file
// Update the getCartonDetails function by removing no_of_carton
function getCartonDetails($invoice_id) {
    global $pdo;
    
    $query = "
        SELECT 
            ii.product_name,
            ii.quantity,
            p.carton_width,
            p.carton_height,
            p.carton_length,
            p.carton_weight,
            p.pcs_per_carton,
            p.cbm_carton,
            pr.product_code,
            p.add_carton1_total_cbm,
            p.add_carton2_total_cbm,
            p.add_carton3_total_cbm,
            p.add_carton4_total_cbm,
            p.add_carton5_total_cbm,
            p.add_carton6_total_cbm,
            p.new_total_cbm_moq
        FROM invoice_item ii
        JOIN product pr ON pr.product_id = ii.product_id
        JOIN price p ON p.product_id = pr.product_id
        WHERE ii.invoice_id = ?
        ORDER BY ii.created_at ASC
    ";
    
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute([$invoice_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error in getCartonDetails: " . $e->getMessage());
        return [];
    }
}

// Add this new function to get the highest production lead time + delivery days
function getHighestProductionLeadTime($invoice_id) {
    global $pdo;
    $query = "
        SELECT MAX(pr.production_lead_time + COALESCE(psh.delivery_days, 0)) as max_lead_time
        FROM invoice_item ii
        JOIN product pr ON pr.product_id = ii.product_id
        LEFT JOIN price p ON p.product_id = pr.product_id
        LEFT JOIN price_shipping psh ON p.new_freight_method = psh.shipping_code
        WHERE ii.invoice_id = ?
    ";
    
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute([$invoice_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['max_lead_time'] ?? 0;
    } catch(PDOException $e) {
        error_log("Error getting production lead time: " . $e->getMessage());
        return 0;
    }
}

// Add new case in the switch statement for handling payments
if(isset($_GET['action']) || isset($_POST['action'])) {
    // Start output buffering to prevent any unwanted output
    if (ob_get_level() === 0) { ob_start(); }
    
    // Set JSON header
    header('Content-Type: application/json; charset=utf-8');
    
    // Get action from either GET or POST
    $action = $_GET['action'] ?? $_POST['action'] ?? null;
    
    switch($action) {
        case 'get_order_items':
            try {
                $invoice_id = $_GET['invoice_id'] ?? null;
                if (!$invoice_id) {
                    throw new Exception('Invoice ID required');
                }
                
                $items = getOrderItems($invoice_id);
                
                // Clean any buffered output before sending JSON
                if (ob_get_level() > 0) { ob_clean(); }
                
                echo json_encode([
                    'success' => true,
                    'items' => $items,
                    'debug' => [
                        'invoice_id' => $invoice_id,
                        'item_count' => count($items),
                        'query_status' => 'success'
                    ]
                ]);
            } catch(Exception $e) {
                // Clean any buffered output before sending JSON
                if (ob_get_level() > 0) { ob_clean(); }
                
                echo json_encode([
                    'success' => false,
                    'error' => $e->getMessage(),
                    'debug' => [
                        'invoice_id' => $_GET['invoice_id'] ?? null,
                        'error_info' => $e->getMessage()
                    ]
                ]);
            }
            exit;
            break;
        
        case 'get_carton_details':
            try {
                $invoice_id = $_GET['invoice_id'] ?? null;
                if (!$invoice_id) {
                    throw new Exception('Invoice ID required');
                }
                
                $details = getCartonDetails($invoice_id);
                
                // Clean any buffered output before sending JSON
                if (ob_get_level() > 0) { ob_clean(); }
                
                echo json_encode([
                    'success' => true,
                    'details' => $details,
                    'debug' => [
                        'invoice_id' => $invoice_id,
                        'detail_count' => count($details),
                        'has_items' => !empty($details)
                    ]
                ]);
            } catch(Exception $e) {
                // Clean any buffered output before sending JSON
                if (ob_get_level() > 0) { ob_clean(); }
                
                echo json_encode([
                    'success' => false,
                    'error' => $e->getMessage(),
                    'debug' => [
                        'invoice_id' => $_GET['invoice_id'] ?? null,
                        'error_info' => $e->getMessage()
                    ]
                ]);
            }
            exit;
            break;
        
        case 'get_payment_history':
            try {
                $invoice_id = $_GET['invoice_id'] ?? null;
                if (!$invoice_id) {
                    throw new Exception('Invoice ID required');
                }
                
                // Get payment history
                $stmt = $pdo->prepare("
                    SELECT 
                        payment_id,
                        amount_paid,
                        payment_date,
                        description
                    FROM payment_history 
                    WHERE invoice_id = ? 
                    ORDER BY payment_date DESC
                ");
                $stmt->execute([$invoice_id]);
                $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Format dates
                foreach ($payments as &$payment) {
                    $payment['formatted_date'] = date('d M Y, g:i A', strtotime($payment['payment_date']));
                }
                
                // Clean any buffered output before sending JSON
                if (ob_get_level() > 0) { ob_clean(); }
                
                echo json_encode([
                    'success' => true,
                    'payments' => $payments
                ]);
            } catch(Exception $e) {
                if (ob_get_level() > 0) { ob_clean(); }
                
                echo json_encode([
                    'success' => false,
                    'error' => $e->getMessage(),
                    'payments' => []
                ]);
            }
            exit;
            break;
        
        case 'submit_payment':
            // Start output buffering to capture any unwanted output
            if (ob_get_level() === 0) { ob_start(); }
            
            try {
                $invoice_id = $_POST['invoice_id'] ?? null;
                $amount_paid = $_POST['amount_paid'] ?? null;
                
                // Debug logging
                error_log("Payment submission attempt - Invoice ID: " . $invoice_id . ", Amount: " . $amount_paid);
                
                // Validate input
                if (!$invoice_id || !$amount_paid) {
                    throw new Exception('Missing required parameters: invoice_id or amount_paid');
                }
                
                if (!is_numeric($amount_paid) || $amount_paid == 0) {
                    throw new Exception('Invalid payment amount (cannot be zero)');
                }

                // Check if invoice exists with more detailed query
                $stmt = $pdo->prepare("SELECT invoice_id, xero_relation, invoice_number, total_amount FROM invoice WHERE invoice_id = ?");
                $stmt->execute([$invoice_id]);
                $invoiceData = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Debug logging
                error_log("Invoice lookup result: " . json_encode($invoiceData));
                
                if (!$invoiceData) {
                    // Try to find any invoices to see if the table has data
                    $stmt = $pdo->query("SELECT COUNT(*) as count FROM invoice LIMIT 1");
                    $countResult = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    throw new Exception('Invoice not found (ID: ' . $invoice_id . '). Total invoices in database: ' . $countResult['count']);
                }
                
                $xero_relation = $invoiceData['xero_relation'];

                // Try Xero API call (optional - don't fail if this fails)
                // Skip Xero API for negative payments (reversals/deductions)
                $xeroApiSuccess = false;
                try {
                    if (!empty($xero_relation) && $amount_paid > 0) {
                        $xeroAuth = refreshXeroToken();
                        $accessToken = $xeroAuth['access_token'];
                        $tenantId = $xeroAuth['tenant_id'];

                        $client = new Client();
                        $response = $client->post('https://api.xero.com/api.xro/2.0/Payments', [
                            'headers' => [
                                'Authorization' => 'Bearer ' . $accessToken,
                                'Xero-tenant-id' => $tenantId,
                                'Accept' => 'application/json',
                                'Content-Type' => 'application/json'
                            ],
                            'json' => [
                                'Invoice' => [
                                    'InvoiceID' => $xero_relation
                                ],
                                'Account' => [
                                    'Code' => '090'
                                ],
                                'Date' => date('Y-m-d'),
                                'Amount' => floatval($amount_paid)
                            ]
                        ]);
                        $xeroApiSuccess = true;
                    } elseif ($amount_paid < 0) {
                        // For negative payments, log that we're skipping Xero
                        error_log("Skipping Xero API for negative payment (deduction): " . $amount_paid);
                    }
                } catch (Exception $e) {
                    // Log Xero error but don't fail the payment
                    if (ob_get_level() > 0) { ob_clean(); }
                
                    echo json_encode([
                        'success' => false,
                        'error' => $e->getMessage(),
                    ]);
                }

                // Database transaction
                $pdo->beginTransaction();
                
                // Check if payment_history table exists and has correct structure
                $stmt = $pdo->prepare("SHOW TABLES LIKE 'payment_history'");
                $stmt->execute();
                if ($stmt->rowCount() === 0) {
                    throw new Exception('Payment history table not found');
                }
                
                // Check the actual columns in payment_history table
                $stmt = $pdo->prepare("DESCRIBE payment_history");
                $stmt->execute();
                $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
                error_log("Payment history table columns: " . json_encode($columns));
                
                // Insert payment record - adjust based on actual table structure
                // Most likely the table has 'payment_date' instead of 'created_at'
                $stmt = $pdo->prepare("
                    INSERT INTO payment_history (invoice_id, amount_paid, payment_date) 
                    VALUES (?, ?, NOW())
                ");
                $result = $stmt->execute([$invoice_id, $amount_paid]);
                
                if (!$result) {
                    throw new Exception('Failed to insert payment record');
                }
                
                // Update invoice payment tracking
                $stmt = $pdo->prepare("
                    UPDATE invoice 
                    SET first_payment_date = CASE 
                            WHEN first_payment_date IS NULL THEN CURRENT_TIMESTAMP
                            ELSE first_payment_date
                        END,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE invoice_id = ?
                ");
                $result = $stmt->execute([$invoice_id]);
                
                if (!$result) {
                    throw new Exception('Failed to update invoice payment tracking');
                }
                
                $pdo->commit();
                
                // Clean any buffered output before sending JSON
                if (ob_get_level() > 0) { ob_clean(); }
                
                echo json_encode([
                    'success' => true, 
                    'message' => 'Payment recorded successfully',
                    'xero_sync' => $xeroApiSuccess
                ]);
                
            } catch (Exception $e) {
                // Rollback transaction if it was started
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                
                // Clean any buffered output before sending JSON
                if (ob_get_level() > 0) { ob_clean(); }
                
                echo json_encode([
                    'success' => false, 
                    'error' => $e->getMessage()
                ]);
            }
            exit;
            break;
        
        case 'toggle_status':
            try {
                $invoice_id = $_POST['invoice_id'] ?? null;
                $new_status = $_POST['status'] ?? null;
                
                if (!$invoice_id || !$new_status) {
                    throw new Exception('Missing required parameters: invoice_id or status');
                }
                
                if (!in_array($new_status, ['pending', 'completed', 'cancelled'])) {
                    throw new Exception('Invalid status value');
                }
                
                $stmt = $pdo->prepare("
                    UPDATE invoice 
                    SET status = ?,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE invoice_id = ?
                ");
                $stmt->execute([$new_status, $invoice_id]);
                
                if ($stmt->rowCount() === 0) {
                    throw new Exception('Invoice not found or no changes made');
                }
                
                // Clean any buffered output before sending JSON
                if (ob_get_level() > 0) { ob_clean(); }
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Status updated successfully'
                ]);
                
            } catch(Exception $e) {
                // Clean any buffered output before sending JSON
                if (ob_get_level() > 0) { ob_clean(); }
                
                echo json_encode([
                    'success' => false,
                    'error' => $e->getMessage()
                ]);
            }
            exit;
            break;
            
        case 'debug_invoice':
            try {
                $invoice_id = $_POST['invoice_id'] ?? $_GET['invoice_id'] ?? null;
                
                if (!$invoice_id) {
                    // If no specific invoice_id, show general debug info
                    $stmt = $pdo->query("SELECT COUNT(*) as total_count FROM invoice");
                    $totalCount = $stmt->fetch(PDO::FETCH_ASSOC)['total_count'];
                    
                    $stmt = $pdo->query("SELECT invoice_id, invoice_number, total_amount, status FROM invoice ORDER BY created_at DESC LIMIT 5");
                    $sampleInvoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    // Clean any buffered output before sending JSON
                    if (ob_get_level() > 0) { ob_clean(); }
                    
                    echo json_encode([
                        'success' => true,
                        'message' => 'General debug info',
                        'total_invoices' => $totalCount,
                        'sample_invoices' => $sampleInvoices
                    ]);
                } else {
                    // Check specific invoice
                    $stmt = $pdo->prepare("
                        SELECT 
                            i.invoice_id,
                            i.invoice_number,
                            i.total_amount,
                            i.status,
                            i.created_at,
                            c.customer_name
                        FROM invoice i
                        LEFT JOIN customer c ON i.customer_id = c.customer_id
                        WHERE i.invoice_id = ?
                    ");
                    $stmt->execute([$invoice_id]);
                    $invoiceData = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($invoiceData) {
                        // Get payment history for this invoice
                        $stmt = $pdo->prepare("SELECT * FROM payment_history WHERE invoice_id = ?");
                        $stmt->execute([$invoice_id]);
                        $paymentHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        // Clean any buffered output before sending JSON
                        if (ob_get_level() > 0) { ob_clean(); }
                        
                        echo json_encode([
                            'success' => true,
                            'invoice_id' => $invoiceData['invoice_id'],
                            'invoice_number' => $invoiceData['invoice_number'],
                            'total_amount' => $invoiceData['total_amount'],
                            'status' => $invoiceData['status'],
                            'customer_name' => $invoiceData['customer_name'],
                            'date' => $invoiceData['created_at'],
                            'payment_history' => $paymentHistory,
                            'items_count' => 'N/A - not calculated in this debug'
                        ]);
                    } else {
                        // Clean any buffered output before sending JSON
                        if (ob_get_level() > 0) { ob_clean(); }
                        
                        echo json_encode([
                            'success' => false,
                            'message' => "Invoice not found in database",
                            'requested_id' => $invoice_id
                        ]);
                    }
                }
                
            } catch(Exception $e) {
                // Clean any buffered output before sending JSON
                if (ob_get_level() > 0) { ob_clean(); }
                
                echo json_encode([
                    'success' => false,
                    'error' => $e->getMessage()
                ]);
            }
            exit;
            break;
    }
    
    // If no action matched, return error
    if (ob_get_level() > 0) { ob_clean(); }
    echo json_encode([
        'success' => false,
        'error' => 'Invalid action specified'
    ]);
    exit;
}