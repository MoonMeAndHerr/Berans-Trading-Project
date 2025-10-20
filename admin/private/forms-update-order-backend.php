<?php
if(session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../private/auth_check.php';
require_once __DIR__ . '/../../global/main_configuration.php';
// refresh_xero_token.php is already included in main_configuration.php
use League\OAuth2\Client\Provider\GenericProvider;
use GuzzleHttp\Client;

$pdo = openDB();

// Get invoice_id from GET parameter
$invoice_id = isset($_GET['invoice_id']) ? intval($_GET['invoice_id']) : null;
$existingOrder = null;
$existingItems = [];

// Fetch existing order data if invoice_id is provided
if ($invoice_id) {
    try {
        // Get invoice details
        $stmt = $pdo->prepare("
            SELECT i.*, c.customer_name, c.customer_id 
            FROM invoice i
            LEFT JOIN customer c ON i.customer_id = c.customer_id
            WHERE i.invoice_id = ?
        ");
        $stmt->execute([$invoice_id]);
        $existingOrder = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existingOrder) {
            // Check if there are any payments
            $supplierPayments = floatval($existingOrder['supplier_payments_total'] ?? 0);
            $shippingPayments = floatval($existingOrder['shipping_payments_total'] ?? 0);
            $commissionPayments = floatval($existingOrder['commission_paid_amount'] ?? 0);
            $existingOrder['has_any_payments'] = ($supplierPayments > 0 || $shippingPayments > 0 || $commissionPayments > 0);
            
            // Debug: Log payment check
            error_log("PAYMENT CHECK - Invoice ID: {$invoice_id}, Supplier: {$supplierPayments}, Shipping: {$shippingPayments}, Commission: {$commissionPayments}, Has Payments: " . ($existingOrder['has_any_payments'] ? 'YES' : 'NO'));
            
            // Get invoice items
            $stmt = $pdo->prepare("
                SELECT ii.*, p.section_id, p.category_id, p.subcategory_id,
                       pr.new_moq_quantity, pr.new_selling_price, pr.price_id
                FROM invoice_item ii
                LEFT JOIN product p ON ii.product_id = p.product_id
                LEFT JOIN (
                    SELECT p1.* 
                    FROM price p1
                    INNER JOIN (
                        SELECT product_id, MAX(price_id) as max_price_id
                        FROM price
                        GROUP BY product_id
                    ) p2 ON p1.price_id = p2.max_price_id
                ) pr ON ii.product_id = pr.product_id
                WHERE ii.invoice_id = ?
            ");
            $stmt->execute([$invoice_id]);
            $existingItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (Exception $e) {
        error_log("Error fetching order data: " . $e->getMessage());
    }
}

// --- Handle AJAX requests (dependent dropdowns) ---
if(isset($_GET['ajax'], $_GET['type'], $_GET['parent_id'])){
    $type = $_GET['type'];
    $parent_id = intval($_GET['parent_id']);
    $data = [];

    switch($type){
        case 'category':
            $stmt = $pdo->prepare("SELECT category_id, category_name FROM category WHERE section_id=?");
            $stmt->execute([$parent_id]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;

        case 'subcategory':
            $stmt = $pdo->prepare("SELECT subcategory_id, subcategory_name FROM subcategory WHERE category_id=?");
            $stmt->execute([$parent_id]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;

        case 'product':
            $stmt = $pdo->prepare("
                SELECT 
                    p.product_id,
                    CONCAT(
                        p.product_code, ' | ',
                        IFNULL(m.material_name, ''), ' ',
                        IFNULL(pt.product_name, ''), ' ',
                        p.size_1, '*', p.size_2, '*', p.size_3, ' ',
                        IFNULL(p.variant,'')
                    ) AS display_name
                FROM product p
                LEFT JOIN material m ON p.material_id = m.material_id
                LEFT JOIN product_type pt ON pt.product_type_id = p.product_type_id
                WHERE subcategory_id=?
            ");
            $stmt->execute([$parent_id]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;

            }

    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// --- Load initial dropdown data (so form still works without JS) ---
$sections = $pdo->query("SELECT section_id, section_name FROM section")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT category_id, category_name, section_id FROM category")->fetchAll(PDO::FETCH_ASSOC);
$subcategories = $pdo->query("SELECT subcategory_id, subcategory_name, category_id FROM subcategory")->fetchAll(PDO::FETCH_ASSOC);
$customers = $pdo->query("SELECT customer_id, customer_name FROM customer WHERE deleted_at IS NULL")->fetchAll(PDO::FETCH_ASSOC);
$staff = $pdo->query("SELECT staff_id, staff_name FROM staff WHERE deleted_at IS NULL ORDER BY staff_name")->fetchAll(PDO::FETCH_ASSOC);
$products = $pdo->query("
    SELECT 
        p.product_id,
        p.section_id,
        p.category_id,
        p.subcategory_id,
        CONCAT(
            p.product_code, ' | ',
            IFNULL(m.material_name, ''), ' ',
            IFNULL(pt.product_name, ''), ' ',
            p.size_1, '*', p.size_2, '*', p.size_3, ' ',
            IFNULL(p.variant,'')
        ) AS display_name,
        pr.price_id,
        pr.new_moq_quantity,
        COALESCE(pr.new_selling_price, 0) as new_selling_price  -- Use COALESCE to handle NULL
    FROM product p
    LEFT JOIN material m ON p.material_id = m.material_id
    LEFT JOIN product_type pt ON pt.product_type_id = p.product_type_id
    LEFT JOIN (
        SELECT p1.* 
        FROM price p1
        INNER JOIN (
            SELECT product_id, MAX(price_id) as max_price_id
            FROM price
            GROUP BY product_id
        ) p2 ON p1.price_id = p2.max_price_id
    ) pr ON p.product_id = pr.product_id
    WHERE p.deleted_at IS NULL
")->fetchAll(PDO::FETCH_ASSOC);

// Handle AJAX cost calculation request (preview before submission)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'calculate_cost_changes') {
    header('Content-Type: application/json');
    
    try {
        $invoice_id = intval($_POST['invoice_id']);
        $products = $_POST['products'] ?? [];
        
        if (empty($products)) {
            echo json_encode(['success' => false, 'error' => 'No products provided']);
            exit;
        }
        
        // Parse products
        $parsedProducts = [];
        foreach ($products as $productJson) {
            $product = json_decode($productJson, true);
            if ($product) {
                $parsedProducts[] = $product;
            }
        }
        
        // Calculate OLD costs from existing invoice_items
        $stmt = $pdo->prepare("
            SELECT 
                SUM(ii.quantity * COALESCE(p.new_unit_price_yen, 0)) as total_supplier_yen,
                SUM(ii.quantity * COALESCE(p.new_unit_freight_cost_rm, 0)) as total_shipping_rm,
                AVG(COALESCE(p.new_conversion_rate, 0.032)) as avg_conversion_rate
            FROM invoice_item ii
            LEFT JOIN price p ON ii.product_id = p.product_id
            WHERE ii.invoice_id = ?
        ");
        $stmt->execute([$invoice_id]);
        $oldCosts = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $oldSupplierYen = floatval($oldCosts['total_supplier_yen'] ?? 0);
        $oldShippingRm = floatval($oldCosts['total_shipping_rm'] ?? 0);
        $conversionRate = floatval($oldCosts['avg_conversion_rate'] ?? 0.032);
        
        $oldSupplierRm = $oldSupplierYen / $conversionRate;
        
        // Calculate NEW costs from provided products
        $newSupplierYen = 0;
        $newShippingRm = 0;
        
        foreach ($parsedProducts as $product) {
            $quantity = intval($product['quantity'] ?? 1);
            $productId = $product['product_id'] ?? null;
            
            // Get price data from the price table using product_id
            if (!empty($productId)) {
                $stmt = $pdo->prepare("
                    SELECT new_unit_price_yen, new_unit_freight_cost_rm, new_conversion_rate
                    FROM price
                    WHERE product_id = ?
                    ORDER BY price_id DESC
                    LIMIT 1
                ");
                $stmt->execute([$productId]);
                $priceData = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($priceData) {
                    $newSupplierYen += floatval($priceData['new_unit_price_yen'] ?? 0) * $quantity;
                    $newShippingRm += floatval($priceData['new_unit_freight_cost_rm'] ?? 0) * $quantity;
                    if ($conversionRate == 0.032 && !empty($priceData['new_conversion_rate'])) {
                        $conversionRate = floatval($priceData['new_conversion_rate']);
                    }
                }
            }
        }
        
        $newSupplierRm = $newSupplierYen / $conversionRate;
        
        // Calculate differences
        $diffSupplierRm = $newSupplierRm - $oldSupplierRm;
        $diffShippingRm = $newShippingRm - $oldShippingRm;
        
        // Get current payments and commission info
        $stmt = $pdo->prepare("
            SELECT 
                supplier_payments_total, 
                shipping_payments_total,
                commission_paid_amount,
                commission_staff_id,
                commission_percentage,
                total_amount as old_total_amount
            FROM invoice
            WHERE invoice_id = ?
        ");
        $stmt->execute([$invoice_id]);
        $invoice = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $paymentsMadeSupplier = floatval($invoice['supplier_payments_total'] ?? 0);
        $paymentsMadeShipping = floatval($invoice['shipping_payments_total'] ?? 0);
        $commissionPaidAmount = floatval($invoice['commission_paid_amount'] ?? 0);
        $oldCommissionStaffId = $invoice['commission_staff_id'];
        $oldCommissionPercentage = floatval($invoice['commission_percentage'] ?? 0);
        $oldTotalAmount = floatval($invoice['old_total_amount'] ?? 0);
        
        // Get new total_amount from POST (discounted revenue)
        $newTotalAmount = floatval($_POST['total'] ?? 0);
        
        // Calculate commission changes (commission based on discounted total_amount)
        $oldCommissionDue = 0;
        $newCommissionDue = 0;
        $commissionDifferenceRm = 0;
        $newCommissionBalanceRm = 0;
        $hasCommissionChange = false;
        
        if (!empty($oldCommissionStaffId) && $oldCommissionPercentage > 0) {
            $oldCommissionDue = $oldTotalAmount * ($oldCommissionPercentage / 100);
            $newCommissionDue = $newTotalAmount * ($oldCommissionPercentage / 100);
            $commissionDifferenceRm = $newCommissionDue - $oldCommissionDue;
            $newCommissionBalanceRm = $newCommissionDue - $commissionPaidAmount;
            $hasCommissionChange = abs($commissionDifferenceRm) > 0.01;
        }
        
        // Calculate new balances (convert supplier payments from YEN to RM)
        $paymentsMadeSupplierRm = $paymentsMadeSupplier / $conversionRate; // Convert YEN to RM
        $newBalanceSupplier = $newSupplierRm - $paymentsMadeSupplierRm;
        $newBalanceShipping = $newShippingRm - $paymentsMadeShipping;
        $newBalanceTotal = $newBalanceSupplier + $newBalanceShipping + $newCommissionBalanceRm;
        
        // Determine if adjustment is needed (changes significant enough)
        $threshold = 0.01; // RM 0.01 threshold
        $adjustmentNeeded = (abs($diffSupplierRm) > $threshold) || (abs($diffShippingRm) > $threshold) || $hasCommissionChange;
        
        $adjustmentData = [
            'has_payments' => ($paymentsMadeSupplier > 0 || $paymentsMadeShipping > 0 || $commissionPaidAmount > 0),
            'adjustment_needed' => $adjustmentNeeded,
            'old_costs' => [
                'supplier_yen' => $oldSupplierYen,
                'supplier_rm' => $oldSupplierRm,
                'shipping_rm' => $oldShippingRm,
                'commission_rm' => $oldCommissionDue,
                'total_rm' => $oldSupplierRm + $oldShippingRm + $oldCommissionDue
            ],
            'new_costs' => [
                'supplier_yen' => $newSupplierYen,
                'supplier_rm' => $newSupplierRm,
                'shipping_rm' => $newShippingRm,
                'commission_rm' => $newCommissionDue,
                'total_rm' => $newSupplierRm + $newShippingRm + $newCommissionDue
            ],
            'differences' => [
                'supplier_rm' => $diffSupplierRm,
                'shipping_rm' => $diffShippingRm,
                'commission_rm' => $commissionDifferenceRm,
                'total_rm' => $diffSupplierRm + $diffShippingRm + $commissionDifferenceRm
            ],
            'payments_made' => [
                'supplier_rm' => $paymentsMadeSupplier / $conversionRate, // Convert YEN to RM
                'shipping_rm' => $paymentsMadeShipping,
                'commission_rm' => $commissionPaidAmount,
                'total_rm' => ($paymentsMadeSupplier / $conversionRate) + $paymentsMadeShipping + $commissionPaidAmount
            ],
            'new_balances' => [
                'supplier_rm' => $newBalanceSupplier,
                'shipping_rm' => $newBalanceShipping,
                'commission_rm' => $newCommissionBalanceRm,
                'total_rm' => $newBalanceTotal
            ]
        ];
        
        echo json_encode([
            'success' => true,
            'adjustment' => $adjustmentData
        ]);
        exit;
        
    } catch (Exception $e) {
        error_log("Cost calculation error: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
        exit;
    }
}

// Handle form submission (UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if this is an AJAX request
    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    
    // For AJAX requests, use output buffering and error handling
    if ($isAjax) {
        ob_start();
        
        // Set error reporting to catch all errors
        error_reporting(E_ALL);
        ini_set('display_errors', 0); // Don't display errors directly
        
        // Custom error handler for AJAX
        set_error_handler(function($severity, $message, $file, $line) {
            ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => "PHP Error: $message in $file on line $line"]);
            exit;
        });
        
        // Custom exception handler for AJAX
        set_exception_handler(function($exception) {
            ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => "Exception: " . $exception->getMessage()]);
            exit;
        });
    }
    
    if (!isset($_POST['products']) || !isset($_POST['customer_id']) || !isset($_POST['invoice_id'])) {
        $errorMsg = "Missing required data (products, customer, or invoice_id)";
        error_log("Missing data - Products: " . isset($_POST['products']) . ", Customer: " . isset($_POST['customer_id']) . ", Invoice ID: " . isset($_POST['invoice_id']));
        
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $errorMsg]);
            exit;
        } else {
            $_SESSION['error'] = $errorMsg;
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
        exit;
    }

    try {
        $pdo->beginTransaction();
        
        $invoice_id = intval($_POST['invoice_id']);
        
        $customer_id = $_POST['customer_id'];
        $selected_staff_id = $_POST['selected_staff'] ?? null;
        $staff_commission_percentage = $_POST['staff_commission_percentage'] ?? 0;
        $products = $_POST['products'];
        
        // Handle discount data
        $discount_type = $_POST['discount_type'] ?? 'none';
        $discount_value = $_POST['discount_value'] ?? 0;
        $discount_amount = $_POST['discount_amount'] ?? 0;
        $subtotal = $_POST['subtotal'] ?? 0;
        $grand_total = $_POST['grand_total'] ?? 0;
        
        $stmt = $pdo->prepare("SELECT * FROM customer WHERE customer_id = ?");
        $stmt->execute([$_POST['customer_id']]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $xero_relation_customer = $customer['xero_relation'];

        // Get first product data to get price_id
        $first_product = json_decode($products[0], true);
        if ($first_product === null) {
            throw new Exception('Invalid product data format: ' . json_last_error_msg());
        }
        
        // Get price_id from the price table
        $priceStmt = $pdo->prepare("
            SELECT price_id 
            FROM price 
            WHERE product_id = ? 
            ORDER BY price_id DESC 
            LIMIT 1
        ");
        $priceStmt->execute([$first_product['product_id']]);
        $priceResult = $priceStmt->fetch();
        
        if (!$priceResult) {
            throw new Exception('No price record found for product ID: ' . $first_product['product_id']);
        }
        
        $price_id = $priceResult['price_id'];

        // Calculate total amount
        $total_amount = 0;
        foreach ($products as $product) {
            $product_data = json_decode($product, true);
            if ($product_data === null) {
                throw new Exception('Invalid product data format: ' . json_last_error_msg());
            }
            $total_amount += $product_data['total_price'];
        }

        // Use grand_total if available, otherwise fallback to calculated total_amount
        $final_total = $grand_total > 0 ? $grand_total : $total_amount;

        // Get the existing invoice number
        $stmt = $pdo->prepare("SELECT invoice_number FROM invoice WHERE invoice_id = ?");
        $stmt->execute([$invoice_id]);
        $invoiceData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$invoiceData) {
            throw new Exception("Invoice not found with ID: " . $invoice_id);
        }
        
        $invoice_number = $invoiceData['invoice_number'];

        // ===== FETCH OLD DATA BEFORE UPDATE (for "after changes" comparison) =====
        // Get old payment totals and commission data BEFORE updating invoice
        $stmt = $pdo->prepare("
            SELECT 
                supplier_payments_total, 
                shipping_payments_total, 
                commission_paid_amount,
                commission_staff_id,
                commission_percentage,
                total_amount as old_total_amount
            FROM invoice 
            WHERE invoice_id = ?
        ");
        $stmt->execute([$invoice_id]);
        $oldPaymentData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $supplierPaymentsMade = floatval($oldPaymentData['supplier_payments_total'] ?? 0);
        $shippingPaymentsMade = floatval($oldPaymentData['shipping_payments_total'] ?? 0);
        $commissionPaidAmount = floatval($oldPaymentData['commission_paid_amount'] ?? 0);
        $oldCommissionStaffId = $oldPaymentData['commission_staff_id'];
        $oldCommissionPercentage = floatval($oldPaymentData['commission_percentage'] ?? 0);
        $oldTotalAmount = floatval($oldPaymentData['old_total_amount'] ?? 0);
        $hasPayments = ($supplierPaymentsMade > 0 || $shippingPaymentsMade > 0 || $commissionPaidAmount > 0);

        // Update invoice
        $stmt = $pdo->prepare("
            UPDATE invoice SET
                price_id = ?,
                customer_id = ?, 
                commission_staff_id = ?,
                commission_percentage = ?,
                discount_type = ?,
                discount_value = ?,
                discount_amount = ?,
                subtotal = ?,
                grand_total = ?,
                total_amount = ?,
                updated_at = NOW()
            WHERE invoice_id = ?
        ");

        error_log("Executing invoice update with: " . print_r([
            $price_id,
            $customer_id, 
            $selected_staff_id,
            $staff_commission_percentage,
            $discount_type,
            $discount_value,
            $discount_amount,
            $subtotal,
            $grand_total,
            $final_total,
            $invoice_id
        ], true));

        $success = $stmt->execute([
            $price_id,
            $customer_id,
            $selected_staff_id,
            $staff_commission_percentage,
            $discount_type,
            $discount_value,
            $discount_amount,
            $subtotal,
            $grand_total,
            $final_total,
            $invoice_id
        ]);
        
        if (!$success) {
            throw new Exception("Failed to update invoice: " . implode(", ", $stmt->errorInfo()));
        }

        // ===== CALCULATE OLD COSTS BEFORE DELETING =====
        $oldCostsQuery = "
            SELECT 
                SUM(ii.quantity * COALESCE(p.new_unit_price_yen, 0)) as old_supplier_cost_yen,
                SUM(ii.quantity * COALESCE(p.new_unit_freight_cost_rm, 0)) as old_shipping_cost_rm,
                AVG(COALESCE(p.new_conversion_rate, 0.032)) as avg_conversion_rate
            FROM invoice_item ii
            LEFT JOIN price p ON ii.product_id = p.product_id
            WHERE ii.invoice_id = ?
        ";
        $stmt = $pdo->prepare($oldCostsQuery);
        $stmt->execute([$invoice_id]);
        $oldCosts = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $oldSupplierCostYen = floatval($oldCosts['old_supplier_cost_yen'] ?? 0);
        $oldShippingCostRm = floatval($oldCosts['old_shipping_cost_rm'] ?? 0);
        $avgConversionRate = floatval($oldCosts['avg_conversion_rate'] ?? 0.032);

        // Delete existing invoice items
        $stmt = $pdo->prepare("DELETE FROM invoice_item WHERE invoice_id = ?");
        $stmt->execute([$invoice_id]);

        // Insert new invoice items
        $stmt = $pdo->prepare("
            INSERT INTO invoice_item (
                invoice_id, 
                product_id,
                product_name,
                unit_price,
                quantity,
                total_price,
                created_at,
                updated_at
            ) VALUES (
                ?, ?, ?, ?, ?, ?, NOW(), NOW()
            )
        ");
        foreach ($products as $product) {
            $product_data = json_decode($product, true);
            if ($product_data === null) {
                throw new Exception('Invalid product data format: ' . json_last_error_msg());
            }
            
            $item_success = $stmt->execute([
                $invoice_id,
                $product_data['product_id'],
                $product_data['product_name'],
                $product_data['unit_price'],
                $product_data['quantity'],
                $product_data['total_price']
            ]);
            
            if (!$item_success) {
                throw new Exception("Failed to insert invoice item: " . implode(", ", $stmt->errorInfo()));
            }
        }

        // ===== CALCULATE NEW COSTS AFTER INSERTING =====
        $newCostsQuery = "
            SELECT 
                SUM(ii.quantity * COALESCE(p.new_unit_price_yen, 0)) as new_supplier_cost_yen,
                SUM(ii.quantity * COALESCE(p.new_unit_freight_cost_rm, 0)) as new_shipping_cost_rm
            FROM invoice_item ii
            LEFT JOIN price p ON ii.product_id = p.product_id
            WHERE ii.invoice_id = ?
        ";
        $stmt = $pdo->prepare($newCostsQuery);
        $stmt->execute([$invoice_id]);
        $newCosts = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $newSupplierCostYen = floatval($newCosts['new_supplier_cost_yen'] ?? 0);
        $newShippingCostRm = floatval($newCosts['new_shipping_cost_rm'] ?? 0);
        
        // Calculate differences
        $supplierDifferenceYen = $newSupplierCostYen - $oldSupplierCostYen;
        $shippingDifferenceRm = $newShippingCostRm - $oldShippingCostRm;
        
        // Convert to RM for display
        $oldSupplierCostRm = $oldSupplierCostYen / $avgConversionRate;
        $newSupplierCostRm = $newSupplierCostYen / $avgConversionRate;
        $supplierDifferenceRm = $supplierDifferenceYen / $avgConversionRate;
        
        // Calculate new balances
        $newSupplierBalanceRm = $newSupplierCostRm - ($supplierPaymentsMade / $avgConversionRate);
        $newShippingBalanceRm = $newShippingCostRm - $shippingPaymentsMade;
        
        // Calculate commission changes (commission is based on discounted total_amount)
        $newTotalAmount = floatval($final_total); // This is the new discounted revenue (grand_total or calculated total)
        $oldCommissionDue = 0;
        $newCommissionDue = 0;
        $commissionDifferenceRm = 0;
        $newCommissionBalanceRm = 0;
        $hasCommissionChange = false;
        
        // Only calculate commission if staff is assigned
        if (!empty($selected_staff_id) && $staff_commission_percentage > 0) {
            $oldCommissionDue = $oldTotalAmount * ($oldCommissionPercentage / 100);
            $newCommissionDue = $newTotalAmount * ($staff_commission_percentage / 100);
            $commissionDifferenceRm = $newCommissionDue - $oldCommissionDue;
            $newCommissionBalanceRm = $newCommissionDue - $commissionPaidAmount;
            $hasCommissionChange = abs($commissionDifferenceRm) > 0.01;
        }
        
        // Prepare adjustment data
        $adjustmentNeeded = $hasPayments && (abs($supplierDifferenceRm) > 0.01 || abs($shippingDifferenceRm) > 0.01 || $hasCommissionChange);
        $adjustmentData = [
            'has_payments' => $hasPayments,
            'adjustment_needed' => $adjustmentNeeded,
            'old_costs' => [
                'supplier_rm' => $oldSupplierCostRm,
                'shipping_rm' => $oldShippingCostRm,
                'commission_rm' => $oldCommissionDue,
                'total_rm' => $oldSupplierCostRm + $oldShippingCostRm + $oldCommissionDue
            ],
            'new_costs' => [
                'supplier_rm' => $newSupplierCostRm,
                'shipping_rm' => $newShippingCostRm,
                'commission_rm' => $newCommissionDue,
                'total_rm' => $newSupplierCostRm + $newShippingCostRm + $newCommissionDue
            ],
            'differences' => [
                'supplier_rm' => $supplierDifferenceRm,
                'shipping_rm' => $shippingDifferenceRm,
                'commission_rm' => $commissionDifferenceRm,
                'total_rm' => $supplierDifferenceRm + $shippingDifferenceRm + $commissionDifferenceRm
            ],
            'payments_made' => [
                'supplier_rm' => $supplierPaymentsMade / $avgConversionRate,
                'shipping_rm' => $shippingPaymentsMade,
                'commission_rm' => $commissionPaidAmount
            ],
            'new_balances' => [
                'supplier_rm' => $newSupplierBalanceRm,
                'shipping_rm' => $newShippingBalanceRm,
                'commission_rm' => $newCommissionBalanceRm,
                'total_rm' => $newSupplierBalanceRm + $newShippingBalanceRm + $newCommissionBalanceRm
            ]
        ];

        $pdo->commit();

        $lineItems = [];

        foreach ($products as $productJson) {
            // Decode JSON string to array
            $product = json_decode($productJson, true);

            $product_id = $product['product_id'];
            $stmt = $pdo->prepare("SELECT * FROM product WHERE product_id = ?");
            $stmt->execute([$product_id]);
            $productfetch = $stmt->fetch(PDO::FETCH_ASSOC);
            $product_code = $productfetch['product_code'] ?? 'UNKNOWN';

            $quantity = $product['quantity'];
            $productName = $product['product_name'];
            // Use the actual unit price from the order (not from database)
            $unitPrice = $product['unit_price'];

            // Build line item
            $lineItems[] = [
                'Description' => $productName,
                'Quantity'    => $quantity,
                'UnitAmount'  => $unitPrice,
                'AccountCode' => '200', // default Sales account
                'ItemCode'    => $product_code,
            ];
        }

        // Add discount as a separate line item if there is a discount
        if ($discount_type !== 'none' && $discount_amount > 0) {
            $lineItems[] = [
                'Description' => 'Discount (' . ucfirst($discount_type) . 
                    ($discount_type === 'percentage' ? ': ' . $discount_value . '%' : '') . ')',
                'Quantity' => 1,
                'UnitAmount' => -$discount_amount, // Negative amount for discount
                'AccountCode' => '200', // same sales account
            ];
        }

            $stmt = $pdo->prepare("SELECT * FROM customer WHERE customer_id = ?");
            $stmt->execute([$_POST['customer_id']]);
            $customer = $stmt->fetch(PDO::FETCH_ASSOC);
            $xero_relation_customer = $customer['xero_relation'];

            // Create Xero invoice
            try {
                // Always refresh Xero tokens to ensure they're current
                try {
                    $tokenData = refreshXeroToken();
                    $accessToken = $tokenData['access_token'];
                    $tenantId = $tokenData['tenant_id'];
                    error_log("XERO_INFO: Successfully refreshed Xero tokens for invoice creation");
                } catch (Exception $refreshError) {
                    error_log("XERO_WARNING: Failed to refresh Xero tokens: " . $refreshError->getMessage());
                    // Skip Xero integration if refresh fails
                    throw new Exception("Xero tokens unavailable: " . $refreshError->getMessage());
                }
                $xeroInvoiceData = [
                        'Type' => 'ACCREC',
                        'Contact' => [
                            'ContactID' => $xero_relation_customer
                        ],
                        'Date' => date('Y-m-d'),
                        'DueDate' => date('Y-m-d', strtotime('+30 days')),
                        'Reference' => $invoice_number,
                        'LineItems' => $lineItems,
                        'Status' => 'AUTHORISED'
                    ];

                    $client = new Client();
                    $response = $client->request('POST', 'https://api.xero.com/api.xro/2.0/Invoices', [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Xero-tenant-id' => $tenantId,
                            'Content-Type' => 'application/json',
                            'Accept' => 'application/json'
                        ],
                        'json' => $xeroInvoiceData
                    ]);

                    $responseBody = $response->getBody()->getContents();
                    $xeroResponse = json_decode($responseBody, true);
                    
                    if (isset($xeroResponse['Invoices'][0]['InvoiceID'])) {
                        $xero_invoice_id = $xeroResponse['Invoices'][0]['InvoiceID'];
                        
                        // Update the invoice with Xero ID
                        $updateStmt = $pdo->prepare("UPDATE invoice SET xero_relation = ? WHERE invoice_id = ?");
                        $updateStmt->execute([$xero_invoice_id, $invoice_id]);
                        
                        error_log("XERO_SUCCESS: Xero invoice created successfully: " . $xero_invoice_id);
                    } else {
                        error_log("XERO_ERROR: Xero invoice creation failed: " . print_r($xeroResponse, true));
                    }
            } catch (Exception $e) {
                // Log error but continue - don't let Xero issues block invoice creation
                error_log("XERO_ERROR: Exception caught: " . $e->getMessage());
                error_log("XERO_ERROR: Exception trace: " . $e->getTraceAsString());
                // Don't throw the exception - just log it and continue
            }

        // $_SESSION['success'] = "Invoice #$invoice_number updated successfully!";
        error_log("Transaction committed successfully");
        
        if ($isAjax) {
            ob_clean(); // Clear any output buffer content
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => "Invoice #$invoice_number updated successfully!", 
                'invoice_number' => $invoice_number,
                'invoice_id' => $invoice_id,
                'adjustment' => $adjustmentData
            ]);
            exit;
        } else {
            $_SESSION['success'] = "Invoice #$invoice_number updated successfully!";
            header('Location: view_order_tabs.php');
            exit;
        }

    } catch (Exception $e) {
        $pdo->rollBack();
        $errorMsg = "Error updating invoice: " . $e->getMessage();
        $errorMsg .= " | File: " . $e->getFile() . " | Line: " . $e->getLine();
        if (isset($stmt) && $stmt->errorInfo()[2]) {
            $errorMsg .= " | SQL Error: " . $stmt->errorInfo()[2];
        }
        error_log("INVOICE_ERROR: " . $errorMsg);
        
        if ($isAjax) {
            ob_clean(); // Clear any output buffer content
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $errorMsg]);
            exit;
        } else {
            $_SESSION['error'] = $errorMsg;
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }
}