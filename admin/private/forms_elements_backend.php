<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../private/auth_check.php';
require_once __DIR__ . '/../../global/main_configuration.php';

$pdo = openDB();

$successMsg = '';
$errorMsg = '';

if (isset($_SESSION['successMsg'])) {
    $successMsg = $_SESSION['successMsg'];
    unset($_SESSION['successMsg']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ===== Main fields =====
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
        $estimated_arrival = null;  // convert empty string to null
    }

    // ===== Calculated fields =====
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


    // ===== Shipping code from user input =====
    $shipping_code = trim($_POST['shipping_code'] ?? '');

    // ===== Shipping totals (calculated in JS, passed as hidden inputs) =====
    $price_total_sea_shipping    = floatval($_POST['price_total_sea_shipping'] ?? 0);
    $price_total_air_shipping_vm = floatval($_POST['price_total_air_shipping_vm'] ?? 0);
    $price_total_air_shipping_kg = floatval($_POST['price_total_air_shipping_kg'] ?? 0);

    // ===== Additional cartons =====
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
        // Insert main price record with additional carton fields
            $sql = "
            INSERT INTO price (
                product_id, supplier_id, quantity,
                carton_width, carton_height, carton_length, pcs_per_carton, no_of_carton,
                designlogo, price, shipping_price, additional_price, conversion_rate, price_rm,
                total_price_yen, total_price_rm, deposit_50_yen, deposit_50_rm,
                cbm_carton, total_cbm, vm_carton, total_vm, total_weight, sg_tax,
                supplier_1st_yen, supplier_2nd_yen, customer_1st_rm, customer_2nd_rm,
                estimated_arrival,
                add_carton1_width, add_carton1_height, add_carton1_length, add_carton1_pcs, add_carton1_no, add_carton1_total_cbm,
                add_carton2_width, add_carton2_height, add_carton2_length, add_carton2_pcs, add_carton2_no, add_carton2_total_cbm,
                add_carton3_width, add_carton3_height, add_carton3_length, add_carton3_pcs, add_carton3_no, add_carton3_total_cbm,
                add_carton4_width, add_carton4_height, add_carton4_length, add_carton4_pcs, add_carton4_no, add_carton4_total_cbm,
                add_carton5_width, add_carton5_height, add_carton5_length, add_carton5_pcs, add_carton5_no, add_carton5_total_cbm,
                add_carton6_width, add_carton6_height, add_carton6_length, add_carton6_pcs, add_carton6_no, add_carton6_total_cbm,
                final_selling_total, final_total_price, final_unit_price,
                final_profit_per_unit_rm, final_total_profit, final_profit_percent, final_selling_unit
            ) VALUES (
                :product_id, :supplier_id, :quantity,
                :carton_width, :carton_height, :carton_length, :pcs_per_carton, :no_of_carton,
                :designlogo, :price, :shipping_price, :additional_price, :conversion_rate, :price_rm,
                :total_price_yen, :total_price_rm, :deposit_50_yen, :deposit_50_rm,
                :cbm_carton, :total_cbm, :vm_carton, :total_vm, :total_weight, :sg_tax,
                :supplier_1st_yen, :supplier_2nd_yen, :customer_1st_rm, :customer_2nd_rm,
                :estimated_arrival,
                :a1w,:a1h,:a1l,:a1p,:a1n,:a1c,
                :a2w,:a2h,:a2l,:a2p,:a2n,:a2c,
                :a3w,:a3h,:a3l,:a3p,:a3n,:a3c,
                :a4w,:a4h,:a4l,:a4p,:a4n,:a4c,
                :a5w,:a5h,:a5l,:a5p,:a5n,:a5c,
                :a6w,:a6h,:a6l,:a6p,:a6n,:a6c,
                :final_selling_total, :final_total_price, :final_unit_price,
                :final_profit_per_unit_rm, :final_total_profit, :final_profit_percent, :final_selling_unit
            )
            ";

        $stmt = $pdo->prepare($sql);

        $bind = [
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
        ];

        for ($i = 1; $i <= 6; $i++) {
            $bind[":a{$i}w"] = $addCartons[$i]['width'];
            $bind[":a{$i}h"] = $addCartons[$i]['height'];
            $bind[":a{$i}l"] = $addCartons[$i]['length'];
            $bind[":a{$i}p"] = $addCartons[$i]['pcs'];
            $bind[":a{$i}n"] = $addCartons[$i]['no'];
            $bind[":a{$i}c"] = $addCartons[$i]['total_cbm'];
        }
            $bind[':final_selling_total']      = $final_selling_total;
            $bind[':final_total_price']        = $final_total_price;
            $bind[':final_unit_price']         = $final_unit_price;
            $bind[':final_profit_per_unit_rm'] = $final_profit_per_unit_rm;
            $bind[':final_total_profit']       = $final_total_profit;
            $bind[':final_profit_percent']     = $final_profit_percent;
            $bind[':final_selling_unit']       = $final_selling_unit;
        $stmt->execute($bind);

        // Get the newly inserted price_id
        $price_id = $pdo->lastInsertId();

        // Fetch price_shipping id by shipping_code
        $stmt2 = $pdo->prepare("SELECT shipping_price_id FROM price_shipping WHERE shipping_code = ?");
        $stmt2->execute([$shipping_code]);
        $shipping_price_id = $stmt2->fetchColumn();

        if ($shipping_price_id) {
            // Insert into price_shipping_total
            $sql_shipping_total = "
                INSERT INTO price_shipping_totals
                (shipping_price_id, price_id, price_total_sea_shipping, price_total_air_shipping_vm, price_total_air_shipping_kg)
                VALUES (:shipping_price_id, :price_id, :sea, :air_vm, :air_kg)
            ";
            $stmt3 = $pdo->prepare($sql_shipping_total);
            $stmt3->execute([
                ':shipping_price_id' => $shipping_price_id,
                ':price_id'          => $price_id,
                ':sea'               => $price_total_sea_shipping,
                ':air_vm'            => $price_total_air_shipping_vm,
                ':air_kg'            => $price_total_air_shipping_kg,
            ]);
        }

        $_SESSION['successMsg'] = "✅ Price record with additional cartons and shipping totals saved successfully!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();

    } catch (PDOException $e) {
        $errorMsg = "❌ Error: " . $e->getMessage();
    }
}

// Fetch products for dropdown
$productOptions = [];
try {
    $stmt = $pdo->query("SELECT product_id, name, size_volume FROM product WHERE deleted_at IS NULL AND is_active = 1");
    $productOptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMsg = "❌ Error fetching products: " . $e->getMessage();
}

// Fetch suppliers for dropdown
$supplierOptions = [];
try {
    $stmt = $pdo->query("SELECT supplier_id, supplier_name FROM supplier WHERE deleted_at IS NULL");
    $supplierOptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMsg = "❌ Error fetching suppliers: " . $e->getMessage();
}

// Fetch shipping options for dropdown
$shippingOptions = [];
try {
    $stmt = $pdo->query("SELECT shipping_code FROM price_shipping");
    $shippingOptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMsg = "❌ Error fetching shipping options: " . $e->getMessage();
}

closeDB($pdo);

?>