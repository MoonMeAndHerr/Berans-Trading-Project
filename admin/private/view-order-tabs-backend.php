<?php

if(session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../global/main_configuration.php';
use GuzzleHttp\Client;
require 'vendor/autoload.php';
use League\OAuth2\Client\Provider\GenericProvider;

$pdo = openDB();

// Function to get all orders with customer info
function getOrderTabs() {
    global $pdo;
    $query = "
        SELECT 
            i.invoice_id,
            i.invoice_number,
            i.total_amount,
            i.created_at,
            i.status,
            c.customer_name,
            c.customer_company_name,
            c.customer_phone,
            (SELECT SUM(amount_paid) FROM payment_history WHERE invoice_id = i.invoice_id) as total_paid,
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
                    SELECT MAX(p.production_lead_time) 
                    FROM invoice_item ii 
                    JOIN product p ON p.product_id = ii.product_id 
                    WHERE ii.invoice_id = i.invoice_id
                )
                ELSE NULL
            END as max_lead_time,
            CASE 
                WHEN EXISTS (
                    SELECT 1 FROM payment_history 
                    WHERE invoice_id = i.invoice_id 
                    LIMIT 1
                ) THEN DATE_ADD(CURRENT_DATE(), 
                    INTERVAL (
                        SELECT MAX(p.production_lead_time) 
                        FROM invoice_item ii 
                        JOIN product p ON p.product_id = ii.product_id 
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
            ii.quantity as ordered_quantity,
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

// Add this new function to get the highest production lead time
function getHighestProductionLeadTime($invoice_id) {
    global $pdo;
    $query = "
        SELECT MAX(p.production_lead_time) as max_lead_time
        FROM invoice_item ii
        JOIN product p ON p.product_id = ii.product_id
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
if(isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    switch($_GET['action']) {
        case 'get_order_items':
            $invoice_id = $_GET['invoice_id'] ?? null;
            if($invoice_id) {
                try {
                    $items = getOrderItems($invoice_id);
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
                    echo json_encode([
                        'success' => false,
                        'error' => $e->getMessage(),
                        'debug' => [
                            'invoice_id' => $invoice_id,
                            'error_info' => $e->getMessage()
                        ]
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'Invoice ID required'
                ]);
            }
            exit;
            break;
        
        case 'get_carton_details':
            $invoice_id = $_GET['invoice_id'] ?? null;
            if($invoice_id) {
                try {
                    $details = getCartonDetails($invoice_id);
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
                    echo json_encode([
                        'success' => false,
                        'error' => $e->getMessage(),
                        'debug' => [
                            'invoice_id' => $invoice_id,
                            'error_info' => $e->getMessage()
                        ]
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'Invoice ID required'
                ]);
            }
            exit;
            break;
        
        case 'submit_payment':
            $invoice_id = $_POST['invoice_id'] ?? null;
            $amount_paid = $_POST['amount_paid'] ?? null;

            $stmt = $pdo->prepare("SELECT xero_relation FROM invoice WHERE invoice_id=?");
            $stmt->execute([$invoice_id]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $xero_relation = $data[0]['xero_relation'];

            try {
                $xeroAuth   = refreshXeroToken(); // always returns valid token
                $accessToken = $xeroAuth['access_token'];
                $tenantId    = $xeroAuth['tenant_id'];

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
                            'InvoiceID' => $xero_relation // the invoice you created earlier
                        ],
                        'Account' => [
                            'Code' => '090' // Bank account code in your Chart of Accounts
                        ],
                        'Date'   => date('Y-m-d'),
                        'Amount' => $amount_paid, 
                    ]
                ]);

                $data = json_decode($response->getBody(), true);

            } catch (Exception $e) {
                error_log("Xero API Error: " . $e->getMessage());
            }
            
            if($invoice_id && $amount_paid) {
                try {
                    $pdo->beginTransaction();
                    
                    // Insert payment record
                    $stmt = $pdo->prepare("
                        INSERT INTO payment_history (invoice_id, amount_paid) 
                        VALUES (?, ?)
                    ");
                    $stmt->execute([$invoice_id, $amount_paid]);
                    
                    // Update invoice total_amount
                    $stmt = $pdo->prepare("
                        UPDATE invoice 
                        SET total_amount = total_amount - ?,
                            first_payment_date = CASE 
                                WHEN first_payment_date IS NULL THEN CURRENT_TIMESTAMP
                                ELSE first_payment_date
                            END,
                            updated_at = CURRENT_TIMESTAMP
                        WHERE invoice_id = ?
                    ");
                    $stmt->execute([$amount_paid, $invoice_id]);
                    
                    $pdo->commit();
                    echo json_encode(['success' => true]);
                } catch(Exception $e) {
                    $pdo->rollBack();
                    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'Missing required parameters'
                ]);
            }
            exit;
            break;
        
        case 'toggle_status':
            $invoice_id = $_POST['invoice_id'] ?? null;
            $new_status = $_POST['status'] ?? null;
            
            if($invoice_id && $new_status) {
                try {
                    $stmt = $pdo->prepare("
                        UPDATE invoice 
                        SET status = ?,
                            updated_at = CURRENT_TIMESTAMP
                        WHERE invoice_id = ?
                    ");
                    $stmt->execute([$new_status, $invoice_id]);
                    echo json_encode(['success' => true]);
                } catch(Exception $e) {
                    echo json_encode([
                        'success' => false,
                        'error' => $e->getMessage()
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'Missing required parameters'
                ]);
            }
            exit;
            break;
    }
}