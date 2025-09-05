<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
use GuzzleHttp\Client;
require 'vendor/autoload.php';
use League\OAuth2\Client\Provider\GenericProvider;

require_once __DIR__ . '/../private/auth_check.php';
require_once __DIR__ . '/../../global/main_configuration.php';

$pdo = openDB();

// 🔁 Auto-redirect to latest price_id if no price_id provided via GET or POST
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['price_id'])) {
    try {
        $stmt = $pdo->query("SELECT price_id FROM price ORDER BY price_id DESC LIMIT 1");
        $latestPriceId = $stmt->fetchColumn();
        if ($latestPriceId) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?price_id=" . $latestPriceId);
            exit();
        }
    } catch (PDOException $e) {
        // handle error
    }
}


$successMsg = $_SESSION['successMsg'] ?? '';
unset($_SESSION['successMsg']);
$errorMsg = '';

// Fetch all price_ids and some identifying info for the dropdown
try {
    $stmt = $pdo->query("
    SELECT p.price_id, p.product_id, pr.name as product_name 
    FROM price p
    LEFT JOIN product pr ON p.product_id = pr.product_id
    ORDER BY p.price_id ASC
");
    $priceList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMsg = "❌ Error fetching price list: " . $e->getMessage();
}

// Initialize empty arrays for form data
$priceData = [];
$shippingTotals = [];
$selectedShippingCode = ''; // <-- Added to hold current shipping_code

// Determine selected price_id from POST or GET (priority to POST to handle form submit)
$price_id = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $price_id = intval($_POST['price_id'] ?? 0);
} else {
    $price_id = intval($_GET['price_id'] ?? 0);
}

// If a valid price_id is provided, fetch the detailed data
if ($price_id > 0) {
    try {
        // Fetch main price data
        $stmt = $pdo->prepare("SELECT * FROM price WHERE price_id = ?");
        $stmt->execute([$price_id]);
        $priceData = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$priceData) {
            $errorMsg = "❌ Price record not found.";
            $price_id = 0; // reset invalid id
        }

        // Fetch shipping totals
        $stmt = $pdo->prepare("SELECT * FROM price_shipping_totals WHERE price_id = ?");
        $stmt->execute([$price_id]);
        $shippingTotals = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        // Fetch current shipping_code linked to price_shipping_totals
        if ($shippingTotals) {
            $stmt2 = $pdo->prepare("
                SELECT ps.shipping_code
                FROM price_shipping ps
                WHERE ps.shipping_price_id = ?
                LIMIT 1
            ");
            $stmt2->execute([$shippingTotals['shipping_price_id']]);
            $selectedShippingCode = $stmt2->fetchColumn() ?: '';
        }

    } catch (PDOException $e) {
        $errorMsg = "❌ Error fetching price data: " . $e->getMessage();
        $price_id = 0; // reset on error
    }
}

// Fetch dropdown options (product, supplier, shipping) same as before
$productOptions = [];
$supplierOptions = [];
$shippingOptions = [];
try {
    $stmt = $pdo->query("SELECT product_id, name, size_volume FROM product WHERE deleted_at IS NULL AND is_active = 1");
    $productOptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT supplier_id, supplier_name FROM supplier WHERE deleted_at IS NULL");
    $supplierOptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT shipping_code FROM price_shipping");
    $shippingOptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMsg = "❌ Error fetching dropdown data: " . $e->getMessage();
}

// Fetch all shipping price data for JS
$shippingPrices = [];
try {
    $stmt = $pdo->query("SELECT * FROM price_shipping");
    $shippingPrices = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMsg = "❌ Error fetching shipping prices: " . $e->getMessage();
}

// ✅ If form submitted, handle UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture fields (same as in add page)
    $product_id       = intval($_POST['product_id'] ?? 0);
    $supplier_id      = intval($_POST['supplier_id'] ?? 0);
    $quantity         = floatval($_POST['quantity'] ?? 0);
    $carton_width     = floatval($_POST['carton_width'] ?? 0);
    $carton_height    = floatval($_POST['carton_height'] ?? 0);
    $carton_length    = floatval($_POST['carton_length'] ?? 0);
    $pcs_per_carton   = intval($_POST['pcs_per_carton'] ?? 0);
    $no_of_carton     = intval($_POST['no_of_carton'] ?? 0);
    $designlogo       = trim($_POST['designlogo'] ?? '');
    $price            = floatval($_POST['price'] ?? 0);
    $shipping_price   = floatval($_POST['shipping_price'] ?? 0);
    $additional_price = floatval($_POST['additional_price'] ?? 0);
    $conversion_rate  = floatval($_POST['conversion_rate'] ?? 0);
    $weight_carton    = floatval($_POST['weight_carton'] ?? 0);
    $estimated_arrival = $_POST['estimated_arrival'] ?? null;
    if ($estimated_arrival === '') {
        $estimated_arrival = null;
    }

    // Calculated fields
    $price_rm          = floatval($_POST['price_rm'] ?? 0);
    $total_price_yen   = floatval($_POST['total_price_yen'] ?? 0);
    $total_price_rm    = floatval($_POST['total_price_rm'] ?? 0);
    $deposit_50_yen    = floatval($_POST['deposit_50_yen'] ?? 0);
    $deposit_50_rm     = floatval($_POST['deposit_50_rm'] ?? 0);
    $cbm_carton        = floatval($_POST['cbm_carton'] ?? 0);
    $total_cbm         = floatval($_POST['total_cbm'] ?? 0);
    $vm_carton         = floatval($_POST['vm_carton'] ?? 0);
    $total_vm          = floatval($_POST['total_vm'] ?? 0);
    $total_weight      = floatval($_POST['total_weight'] ?? 0);
    $sg_tax            = floatval($_POST['sg_tax'] ?? 0);
    $supplier_1st_yen  = floatval($_POST['supplier_1st_yen'] ?? 0);
    $supplier_2nd_yen  = floatval($_POST['supplier_2nd_yen'] ?? 0);
    $customer_1st_rm   = floatval($_POST['customer_1st_rm'] ?? 0);
    $customer_2nd_rm   = floatval($_POST['customer_2nd_rm'] ?? 0);
    $final_selling_total       = floatval($_POST['final_selling_total'] ?? 0);
    $final_total_price         = floatval($_POST['final_total_price'] ?? 0);
    $final_unit_price          = floatval($_POST['final_unit_price'] ?? 0);
    $final_profit_per_unit_rm  = floatval($_POST['final_profit_per_unit_rm'] ?? 0);
    $final_total_profit        = floatval($_POST['final_total_profit'] ?? 0);
    $final_profit_percent      = floatval($_POST['final_profit_percent'] ?? 0);
    $final_selling_unit        = floatval($_POST['final_selling_unit'] ?? 0);

    $shipping_code = trim($_POST['shipping_code'] ?? '');

    $price_total_sea_shipping    = floatval($_POST['price_total_sea_shipping'] ?? 0);
    $price_total_air_shipping_vm = floatval($_POST['price_total_air_shipping_vm'] ?? 0);
    $price_total_air_shipping_kg = floatval($_POST['price_total_air_shipping_kg'] ?? 0);

    // Additional cartons
    $addCartons = [];
    for ($i = 1; $i <= 6; $i++) {
        $addCartons[$i] = [
            'width'     => floatval($_POST["add_carton{$i}_width"] ?? 0),
            'height'    => floatval($_POST["add_carton{$i}_height"] ?? 0),
            'length'    => floatval($_POST["add_carton{$i}_length"] ?? 0),
            'pcs'       => intval($_POST["add_carton{$i}_pcs"] ?? 0),
            'no'        => intval($_POST["add_carton{$i}_no"] ?? 0),
            'total_cbm' => floatval($_POST["add_carton{$i}_total_cbm"] ?? 0),
        ];
    }

    try {
        // 🔹 Update main price table
            $sql = "
                UPDATE price SET
                    product_id = :product_id, supplier_id = :supplier_id, quantity = :quantity,
                    carton_width = :carton_width, carton_height = :carton_height, carton_length = :carton_length,
                    pcs_per_carton = :pcs_per_carton, no_of_carton = :no_of_carton,
                    designlogo = :designlogo, price = :price, shipping_price = :shipping_price, additional_price = :additional_price,
                    conversion_rate = :conversion_rate, price_rm = :price_rm,
                    total_price_yen = :total_price_yen, total_price_rm = :total_price_rm, deposit_50_yen = :deposit_50_yen, deposit_50_rm = :deposit_50_rm,
                    cbm_carton = :cbm_carton, total_cbm = :total_cbm, vm_carton = :vm_carton, total_vm = :total_vm,
                    total_weight = :total_weight, sg_tax = :sg_tax, supplier_1st_yen = :supplier_1st_yen, supplier_2nd_yen = :supplier_2nd_yen,
                    customer_1st_rm = :customer_1st_rm, customer_2nd_rm = :customer_2nd_rm,
                    estimated_arrival = :estimated_arrival,
                    final_selling_total = :final_selling_total, final_total_price = :final_total_price, final_unit_price = :final_unit_price,
                    final_profit_per_unit_rm = :final_profit_per_unit_rm, final_total_profit = :final_total_profit, final_profit_percent = :final_profit_percent,
                    zakat = :zakat,
                    final_selling_unit = :final_selling_unit, weight_carton = :weight_carton,
                    add_carton1_width = :a1w, add_carton1_height = :a1h, add_carton1_length = :a1l, add_carton1_pcs = :a1p, add_carton1_no = :a1n, add_carton1_total_cbm = :a1c,
                    add_carton2_width = :a2w, add_carton2_height = :a2h, add_carton2_length = :a2l, add_carton2_pcs = :a2p, add_carton2_no = :a2n, add_carton2_total_cbm = :a2c,
                    add_carton3_width = :a3w, add_carton3_height = :a3h, add_carton3_length = :a3l, add_carton3_pcs = :a3p, add_carton3_no = :a3n, add_carton3_total_cbm = :a3c,
                    add_carton4_width = :a4w, add_carton4_height = :a4h, add_carton4_length = :a4l, add_carton4_pcs = :a4p, add_carton4_no = :a4n, add_carton4_total_cbm = :a4c,
                    add_carton5_width = :a5w, add_carton5_height = :a5h, add_carton5_length = :a5l, add_carton5_pcs = :a5p, add_carton5_no = :a5n, add_carton5_total_cbm = :a5c,
                    add_carton6_width = :a6w, add_carton6_height = :a6h, add_carton6_length = :a6l, add_carton6_pcs = :a6p, add_carton6_no = :a6n, add_carton6_total_cbm = :a6c
                WHERE price_id = :price_id
            ";

        $stmt = $pdo->prepare($sql);

    $bind = [
        ':price_id'         => $price_id,
        ':product_id'       => $product_id,
        ':supplier_id'      => $supplier_id,
        ':quantity'         => $quantity,
        ':carton_width'     => $carton_width,
        ':carton_height'    => $carton_height,
        ':carton_length'    => $carton_length,
        ':pcs_per_carton'   => $pcs_per_carton,
        ':no_of_carton'     => $no_of_carton,
        ':designlogo'       => $designlogo,
        ':price'            => $price,
        ':shipping_price'   => $shipping_price,
        ':additional_price' => $additional_price,
        ':conversion_rate'  => $conversion_rate,
        ':price_rm'         => $price_rm,
        ':total_price_yen'  => $total_price_yen,
        ':total_price_rm'   => $total_price_rm,
        ':deposit_50_yen'   => $deposit_50_yen,
        ':deposit_50_rm'    => $deposit_50_rm,
        ':cbm_carton'       => $cbm_carton,
        ':total_cbm'        => $total_cbm,
        ':vm_carton'        => $vm_carton,
        ':total_vm'         => $total_vm,
        ':total_weight'     => $total_weight,
        ':sg_tax'           => $sg_tax,
        ':supplier_1st_yen' => $supplier_1st_yen,
        ':supplier_2nd_yen' => $supplier_2nd_yen,
        ':customer_1st_rm'  => $customer_1st_rm,
        ':customer_2nd_rm'  => $customer_2nd_rm,
        ':estimated_arrival'=> $estimated_arrival,
        ':final_selling_total'      => $final_selling_total,
        ':final_total_price'        => $final_total_price,
        ':final_unit_price'         => $final_unit_price,
        ':final_profit_per_unit_rm' => $final_profit_per_unit_rm,
        ':final_total_profit'       => $final_total_profit,
        ':final_profit_percent'     => $final_profit_percent,
        ':zakat'                   => floatval($_POST['zakat'] ?? 0),
        ':final_selling_unit'       => $final_selling_unit,
        ':weight_carton'            => $weight_carton,
    ];

        for ($i = 1; $i <= 6; $i++) {
            $bind[":a{$i}w"] = $addCartons[$i]['width'];
            $bind[":a{$i}h"] = $addCartons[$i]['height'];
            $bind[":a{$i}l"] = $addCartons[$i]['length'];
            $bind[":a{$i}p"] = $addCartons[$i]['pcs'];
            $bind[":a{$i}n"] = $addCartons[$i]['no'];
            $bind[":a{$i}c"] = $addCartons[$i]['total_cbm'];
        }

        $stmt->execute($bind);

        $stmt = $pdo->prepare("SELECT * FROM product WHERE product_id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        $product_code = $product['product_code'];
        
        try {
            $xeroAuth = refreshXeroToken(); // always returns valid token
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
                    'Code' => $product_code,
                    'SalesDetails' => [
                        'UnitPrice' => $final_unit_price,
                    ]
                ]
            ]);

            $result = json_decode($response->getBody(), true);
            echo "<pre>"; print_r($result); echo "</pre>";

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $body = $response ? $response->getBody()->getContents() : 'No response body';
            echo "<pre>API Error: " . $body . "</pre>";
        }

        // 🔹 UPSERT shipping totals (update if exists, else insert)
        // Check if price_id exists in price_shipping_totals
        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM price_shipping_totals WHERE price_id = ?");
        $stmtCheck->execute([$price_id]);
        $exists = $stmtCheck->fetchColumn() > 0;

        // Find shipping_price_id from shipping_code
        $stmt2 = $pdo->prepare("SELECT shipping_price_id FROM price_shipping WHERE shipping_code = ?");
        $stmt2->execute([$shipping_code]);
        $shipping_price_id = $stmt2->fetchColumn();

        if ($shipping_price_id) {
            if ($exists) {
                // Update existing
                $stmt3 = $pdo->prepare("
                    UPDATE price_shipping_totals
                    SET shipping_price_id = :shipping_price_id,
                        price_total_sea_shipping = :sea,
                        price_total_air_shipping_vm = :air_vm,
                        price_total_air_shipping_kg = :air_kg
                    WHERE price_id = :price_id
                ");
                $stmt3->execute([
                    ':shipping_price_id' => $shipping_price_id,
                    ':sea'               => $price_total_sea_shipping,
                    ':air_vm'            => $price_total_air_shipping_vm,
                    ':air_kg'            => $price_total_air_shipping_kg,
                    ':price_id'          => $price_id,
                ]);
            } else {
                // Insert new
                $stmt3 = $pdo->prepare("
                    INSERT INTO price_shipping_totals 
                    (price_id, shipping_price_id, price_total_sea_shipping, price_total_air_shipping_vm, price_total_air_shipping_kg)
                    VALUES
                    (:price_id, :shipping_price_id, :sea, :air_vm, :air_kg)
                ");
                $stmt3->execute([
                    ':price_id'          => $price_id,
                    ':shipping_price_id' => $shipping_price_id,
                    ':sea'               => $price_total_sea_shipping,
                    ':air_vm'            => $price_total_air_shipping_vm,
                    ':air_kg'            => $price_total_air_shipping_kg,
                ]);
            }
        }

        $_SESSION['successMsg'] = "✅ Price record updated successfully!";
        header("Location: " . $_SERVER['PHP_SELF'] . "?price_id=" . $price_id);
        exit();

    } catch (PDOException $e) {
        $errorMsg = "❌ Error updating price: " . $e->getMessage();
    }
}

closeDB($pdo);

// Pass shipping prices to JS
echo "<script>const dbShippingPrices = " . json_encode($shippingPrices) . ";</script>";
?>

