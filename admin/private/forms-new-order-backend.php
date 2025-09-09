<?php
if(session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../private/auth_check.php';
require_once __DIR__ . '/../../global/main_configuration.php';
use League\OAuth2\Client\Provider\GenericProvider;
use GuzzleHttp\Client;

$pdo = openDB();

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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("Form submitted. POST data: " . print_r($_POST, true));
    
    if (!isset($_POST['products']) || !isset($_POST['customer_id'])) {
        $_SESSION['error'] = "Missing required data (products or customer)";
        error_log("Missing data - Products: " . isset($_POST['products']) . ", Customer: " . isset($_POST['customer_id']));
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    try {
        $pdo->beginTransaction();
        
        $customer_id = $_POST['customer_id'];
        $products = $_POST['products'];

        $stmt = $pdo->prepare("SELECT * FROM customer WHERE customer_id = ?");
        $stmt->execute([$_POST['customer_id']]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
        $xero_relation_customer = $customer['xero_relation'];

        
        error_log("Products array received: " . print_r($products, true));

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

        $staff_id = $_SESSION['user_id'] ?? 1; 
        $company_id = 1;

        // Generate invoice number
        $date = date('Ymd');
        $stmt = $pdo->prepare("SELECT MAX(invoice_number) as max_num FROM invoice WHERE invoice_number LIKE ?");
        $stmt->execute(["INV-$date-%"]);
        $result = $stmt->fetch();
        $sequence = $result['max_num'] ? intval(substr($result['max_num'], -3)) + 1 : 1;
        $invoice_number = sprintf("INV-%s-%03d", $date, $sequence);

        // Insert invoice
        $stmt = $pdo->prepare("
            INSERT INTO invoice (
                invoice_number, 
                price_id,
                customer_id, 
                company_id, 
                staff_id,
                total_amount,
                created_at,
                updated_at
            ) VALUES (
                ?, ?, ?, ?, ?, ?, 
                NOW(), 
                NOW()
            )
        ");

        error_log("Executing invoice insert with: " . print_r([
            $invoice_number, 
            $price_id,
            $customer_id, 
            $company_id, 
            $staff_id, 
            $total_amount
        ], true));

        $stmt->execute([
            $invoice_number,
            $price_id,
            $customer_id,
            $company_id,
            $staff_id,
            $total_amount
        ]);
        
        $invoice_id = $pdo->lastInsertId();
        error_log("Invoice created with ID: " . $invoice_id);

        // Insert invoice items
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
            
            error_log("Processing product: " . print_r($product_data, true));
            
            $stmt->execute([
                $invoice_id,
                $product_data['product_id'],
                $product_data['product_name'], // Make sure this matches
                $product_data['unit_price'],
                $product_data['quantity'],
                $product_data['total_price']
            ]);
        }

        $pdo->commit();

        $lineItems = [];

        foreach ($products as $productJson) {
            // Decode JSON string to array
            $product = json_decode($productJson, true);

            $product_id = $product['product_id'];
            $stmt = $pdo->prepare("SELECT * FROM product WHERE product_id = ?");
            $stmt->execute([$product_id]);
            $productfetch = $stmt->fetch(PDO::FETCH_ASSOC);
            $product_code = $productfetch['item_code'];

            $price_id = $product['price_id'];
            $stmt = $pdo->prepare("SELECT * FROM price WHERE price_id = ?");
            $stmt->execute([$price_id]);
            $pricefetch = $stmt->fetch(PDO::FETCH_ASSOC);
            $price = $pricefetch['new_selling_price'];

            $quantity = $product['quantity'];
            $productName = $product['product_name'];

            // Build line item
            $lineItems[] = [
                'Description' => $productName,
                'Quantity'    => $quantity,
                'UnitAmount'  => $price,
                'AccountCode' => '200', // default Sales account
                'ItemCode'    => $product_code,
            ];
        }

            $stmt = $pdo->prepare("SELECT * FROM customer WHERE customer_id = ?");
            $stmt->execute([$_POST['customer_id']]);
            $customer = $stmt->fetch(PDO::FETCH_ASSOC);
            $xero_relation_customer = $customer['xero_relation'];

            try {

                $xeroAuth = refreshXeroToken(); // always returns valid token
                $accessToken = $xeroAuth['access_token'];
                $tenantId    = $xeroAuth['tenant_id'];
                                
                $client = new Client();

                $response = $client->post('https://api.xero.com/api.xro/2.0/Invoices', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Xero-tenant-id' => $tenantId,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'Type' => 'ACCREC', // sales invoice
                    'Contact' => [
                        'ContactID' => $xero_relation_customer,
                    ],
                    'Date' => date('Y-m-d'),
                    'DueDate' => date('Y-m-d', strtotime('+14 days')),
                    'LineItems' => $lineItems,
                    'Reference' => $invoice_number,
                    'Status'    => 'AUTHORISED' 
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            $xero_relation = $data['Invoices'][0]['InvoiceID'];

            $stmt = $pdo->prepare("UPDATE invoice SET xero_relation = ? WHERE invoice_id = ?");
            $stmt->execute([$xero_relation, $invoice_id]);

            } catch (Exception $e) {
                // Log error but continue
                $output = var_export($e->getMessage(), true);
                echo "<script>console.log('Problem: " . $output . "' );</script>";
            }

        $_SESSION['success'] = "Invoice #$invoice_number created successfully!";
        error_log("Transaction committed successfully");
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        $errorMsg = "Error creating invoice: " . $e->getMessage();
        if (isset($stmt) && $stmt->errorInfo()[2]) {
            $errorMsg .= " SQL Error: " . $stmt->errorInfo()[2];
        }
        error_log($errorMsg);
        $_SESSION['error'] = $errorMsg;
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}