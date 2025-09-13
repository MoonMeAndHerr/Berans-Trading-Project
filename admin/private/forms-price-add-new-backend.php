<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
use League\OAuth2\Client\Provider\GenericProvider;
use GuzzleHttp\Client;

require_once __DIR__ . '/../private/auth_check.php';
require_once __DIR__ . '/../../global/main_configuration.php';

$pdo = openDB();

$successMessage = '';
$errorMessage = '';

// Get current conversion rate from database
$conversionRateStmt = $pdo->query("SELECT new_conversion_rate FROM price WHERE new_conversion_rate IS NOT NULL AND new_conversion_rate > 0 ORDER BY price_id DESC LIMIT 1");
$currentConversionRate = $conversionRateStmt->fetchColumn();

// If no conversion rate found, set a default
if (!$currentConversionRate) {
    $currentConversionRate = 1.0000; // Default value
}

// Insert or update product pricing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $product_id = $_POST['product_id'] ?? null;
    $supplier_id = $_POST['supplier_id'] ?? null;

    if (!$product_id) {
        $errorMessage = "Please select a product!";
    } else {
        // Check if a price record already exists for this product + supplier
        $checkStmt = $pdo->prepare("SELECT price_id FROM price WHERE product_id = :product_id AND supplier_id = :supplier_id");
        $checkStmt->execute([':product_id' => $product_id, ':supplier_id' => $supplier_id]);
        $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

        $data = [
            ':new_price_yen' => $_POST['new_price_yen'] ?? null,
            ':new_moq_quantity' => $_POST['new_moq_quantity'] ?? null,
            ':new_shipping_moq_yen' => $_POST['new_shipping_moq_yen'] ?? null,
            ':new_additional_price_moq_yen' => $_POST['new_additional_price_moq_yen'] ?? null,
            ':new_conversion_rate' => $currentConversionRate, // Use database value
            ':new_unit_price_yen' => $_POST['new_unit_price_yen'] ?? null,
            ':new_freight_method' => $_POST['new_freight_method'] ?? null,
            ':new_total_cbm_moq' => $_POST['new_total_cbm_moq'] ?? null,
            ':new_total_weight_moq' => $_POST['new_total_weight_moq'] ?? null,
            ':new_unit_price_rm' => $_POST['new_unit_price_rm'] ?? null,
            ':new_unit_freight_cost_rm' => $_POST['new_unit_freight_cost_rm'] ?? null,
            ':new_unit_profit_rm' => $_POST['new_unit_profit_rm'] ?? null,
            ':new_selling_price' => $_POST['selling_price_unit'] ?? null,
        ];

        if ($existing) {
            $stmt = $pdo->prepare("
                UPDATE price SET
                    new_price_yen = :new_price_yen,
                    new_moq_quantity = :new_moq_quantity,
                    new_shipping_moq_yen = :new_shipping_moq_yen,
                    new_additional_price_moq_yen = :new_additional_price_moq_yen,
                    new_conversion_rate = :new_conversion_rate,
                    new_unit_price_yen = :new_unit_price_yen,
                    new_freight_method = :new_freight_method,
                    new_total_cbm_moq = :new_total_cbm_moq,
                    new_total_weight_moq = :new_total_weight_moq,
                    new_unit_price_rm = :new_unit_price_rm,
                    new_unit_freight_cost_rm = :new_unit_freight_cost_rm,
                    new_unit_profit_rm = :new_unit_profit_rm,
                    new_selling_price = :new_selling_price
                WHERE price_id = :price_id
            ");
            $data[':price_id'] = $existing['price_id'];
            $stmt->execute($data);

            $stmt = $pdo->prepare("SELECT * FROM product WHERE product_id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            $product_code = $product['product_code'];
            $xero_relation = $product['xero_relation'];
            $description = $product['description'];
            $material_id = $product['material_id'];
            $product_type_id = $product['product_type_id'];
            $size1 = $product['size_1'];
            $size2 = $product['size_2'];
            $size3 = $product['size_3'];
            $variant = $product['variant'];

            $stmt = $pdo->prepare("SELECT * FROM material WHERE material_id = ?");
            $stmt->execute([$material_id]);
            $material = $stmt->fetch(PDO::FETCH_ASSOC);
            $materrialName = $material['material_name'];

            $stmt = $pdo->prepare("SELECT * FROM product_type WHERE product_type_id = ?");
            $stmt->execute([$product_type_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            $productType = $product['product_name'];

            $productName = $materrialName.' '.$productType.' '.$size1.'*'.$size2.'*'.$size3.' '.$variant;
        
            try {
                $xeroAuth = refreshXeroToken(); // May return null if token refresh fails
                
                // Only proceed with Xero API call if token refresh was successful
                if ($xeroAuth && isset($xeroAuth['access_token']) && isset($xeroAuth['tenant_id'])) {
                    $accessToken = $xeroAuth['access_token'];
                    $tenantId    = $xeroAuth['tenant_id'];
                    
                    $client = new Client();
                    $response = $client->post('https://api.xero.com/api.xro/2.0/Items', [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Xero-tenant-id' => $tenantId,
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json'
                        ],
                        'json' => [
                            'ItemID' => $xero_relation, // safer to include if available
                            'Name' => $productName,  // safer to include
                            'Description' => $description,
                            'Code' => $product_code,
                            'SalesDetails' => [
                                'UnitPrice' => $_POST['selling_price_unit'],
                            ]
                        ]
                    ]);

                    $result = json_decode($response->getBody(), true);
                } else {
                    // Xero token refresh failed, but continue with database operation
                    error_log("Xero integration skipped due to token refresh failure");
                }

            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $response = $e->getResponse();
                $body = $response ? $response->getBody()->getContents() : 'No response body';
                error_log("Xero API error: " . $body);
            } catch (Exception $e) {
                error_log("Xero integration error: " . $e->getMessage());
            }

            $successMessage = "Pricing record updated successfully!";
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO price (
                    product_id, supplier_id,
                    new_price_yen, new_moq_quantity, new_shipping_moq_yen, new_additional_price_moq_yen,
                    new_conversion_rate, new_unit_price_yen, new_freight_method, new_total_cbm_moq,
                    new_total_weight_moq, new_unit_price_rm, new_unit_freight_cost_rm, new_unit_profit_rm,
                    new_selling_price
                ) VALUES (
                    :product_id, :supplier_id,
                    :new_price_yen, :new_moq_quantity, :new_shipping_moq_yen, :new_additional_price_moq_yen,
                    :new_conversion_rate, :new_unit_price_yen, :new_freight_method, :new_total_cbm_moq,
                    :new_total_weight_moq, :new_unit_price_rm, :new_unit_freight_cost_rm, :new_unit_profit_rm,
                    :new_selling_price
                )
            ");
            $data[':product_id'] = $product_id;
            $data[':supplier_id'] = $supplier_id;
            $stmt->execute($data);

            $stmt = $pdo->prepare("SELECT * FROM product WHERE product_id = ?");
            $stmt->execute([$_POST['product_id']]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            $product_code = $product['product_code'];
            $xero_relation = $product['xero_relation'];
            $description = $product['description'];

            $stmt = $pdo->prepare("SELECT * FROM material WHERE material_id = ?");
            $stmt->execute([$material_id]);
            $material = $stmt->fetch(PDO::FETCH_ASSOC);
            $materrialName = $material['material_name'];

            $stmt = $pdo->prepare("SELECT * FROM product_type WHERE product_type_id = ?");
            $stmt->execute([$product_type_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            $productType = $product['product_name'];

            $productName = $materrialName.' '.$productType.' '.$size1.'*'.$size2.'*'.$size3.' '.$_POST['variant'];
        
            try {
                $xeroAuth = refreshXeroToken(); // May return null if token refresh fails
                
                // Only proceed with Xero API call if token refresh was successful
                if ($xeroAuth && isset($xeroAuth['access_token']) && isset($xeroAuth['tenant_id'])) {
                    $accessToken = $xeroAuth['access_token'];
                    $tenantId    = $xeroAuth['tenant_id'];
                    
                    $client = new Client();
                    $response = $client->post('https://api.xero.com/api.xro/2.0/Items', [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Xero-tenant-id' => $tenantId,
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json'
                        ],
                        'json' => [
                            'ItemID' => $xero_relation, // safer to include if available
                            'Name' => $productName,  // safer to include
                            'Description' => $description,
                            'Code' => $product_code,
                            'SalesDetails' => [
                                'UnitPrice' => $_POST['selling_price_unit'],
                            ]
                        ]
                    ]);

                    $result = json_decode($response->getBody(), true);
                } else {
                    // Xero token refresh failed, but continue with database operation
                    error_log("Xero integration skipped due to token refresh failure");
                }

            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $response = $e->getResponse();
                $body = $response ? $response->getBody()->getContents() : 'No response body';
                error_log("Xero API error: " . $body);
            } catch (Exception $e) {
                error_log("Xero integration error: " . $e->getMessage());
            }

            $successMessage = "Pricing record inserted successfully!";
        }
    }
}

// Fetch dropdown data
$sections = $pdo->query("SELECT * FROM section")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT * FROM category")->fetchAll(PDO::FETCH_ASSOC);
$subcategories = $pdo->query("SELECT * FROM subcategory")->fetchAll(PDO::FETCH_ASSOC);

// Fetch shipping methods - Updated to use simplified structure
$shipping_methods = $pdo->query("SELECT shipping_price_id, shipping_code, shipping_name, freight_rate FROM price_shipping WHERE freight_rate > 0")->fetchAll(PDO::FETCH_ASSOC);

// Check if we need to pre-populate data for a specific product
$prePopulateData = null;
$targetProductId = $_GET['product_id'] ?? null;

if ($targetProductId) {
    // Fetch the latest pricing data for this product
    $stmt = $pdo->prepare("
        SELECT 
            pr.*,
            p.section_id,
            p.category_id, 
            p.subcategory_id,
            p.product_code,
            CONCAT(
                p.product_code, ' | ',
                IFNULL(m.material_name, ''), ' ',
                IFNULL(pt.product_name, ''), ' ',
                p.size_1, '*', p.size_2, '*', p.size_3, ' ',
                p.variant
            ) AS display_name,
            s.supplier_name,
            ship.shipping_code,
            ship.freight_rate
        FROM price pr
        JOIN product p ON p.product_id = pr.product_id
        LEFT JOIN material m ON p.material_id = m.material_id
        LEFT JOIN product_type pt ON pt.product_type_id = p.product_type_id
        LEFT JOIN supplier s ON s.supplier_id = pr.supplier_id
        LEFT JOIN price_shipping ship ON ship.shipping_code = pr.new_freight_method
        WHERE pr.product_id = :product_id
        ORDER BY pr.price_id DESC
        LIMIT 1
    ");
    $stmt->execute([':product_id' => $targetProductId]);
    $prePopulateData = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch products with latest supplier + carton info
$products = $pdo->query("
    SELECT 
        p.product_id,
        CONCAT(
            p.product_code, ' | ',
            IFNULL(m.material_name, ''), ' ',
            IFNULL(pt.product_name, ''), ' ',
            p.size_1, '*', p.size_2, '*', p.size_3, ' ',
            p.variant
        ) AS display_name,
        p.section_id,
        p.category_id,
        p.subcategory_id,
        s.supplier_id,
        s.supplier_name,
        pr.pcs_per_carton,
        pr.cbm_carton,
        pr.carton_weight,
        pr.add_carton1_pcs, pr.add_carton1_total_cbm, pr.add_carton1_weight,
        pr.add_carton2_pcs, pr.add_carton2_total_cbm, pr.add_carton2_weight,
        pr.add_carton3_pcs, pr.add_carton3_total_cbm, pr.add_carton3_weight,
        pr.add_carton4_pcs, pr.add_carton4_total_cbm, pr.add_carton4_weight,
        pr.add_carton5_pcs, pr.add_carton5_total_cbm, pr.add_carton5_weight,
        pr.add_carton6_pcs, pr.add_carton6_total_cbm, pr.add_carton6_weight
    FROM product p
    LEFT JOIN material m ON p.material_id = m.material_id
    LEFT JOIN product_type pt ON pt.product_type_id = p.product_type_id
    LEFT JOIN (
        SELECT pr1.*
        FROM price pr1
        INNER JOIN (
            SELECT product_id, MAX(price_id) AS max_price_id
            FROM price
            GROUP BY product_id
        ) pr2 ON pr1.price_id = pr2.max_price_id
    ) pr ON pr.product_id = p.product_id
    LEFT JOIN supplier s ON s.supplier_id = pr.supplier_id
")->fetchAll(PDO::FETCH_ASSOC);
?>