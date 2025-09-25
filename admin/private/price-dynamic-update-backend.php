<?php
require_once __DIR__ . '/../../global/main_configuration.php';
require_once __DIR__ . '/auth_check.php';

$pdo = openDB();

// Get current conversion rate and shipping rates
$shippingRates = $pdo->query("SELECT * FROM price_shipping")->fetchAll(PDO::FETCH_ASSOC);

// Get current conversion rate from database
$currentConversionRateStmt = $pdo->query("SELECT new_conversion_rate FROM price WHERE new_conversion_rate IS NOT NULL AND new_conversion_rate > 0 ORDER BY price_id DESC LIMIT 1");
$currentConversionRate = $currentConversionRateStmt->fetchColumn() ?: 1.0000;

// Function to recalculate price
function recalculatePrice($price, $newConversionRate, $newFreightRates) {
    // Get carton data directly from price table
    $totalCartons = ceil($price['new_moq_quantity'] / max(1, $price['pcs_per_carton']));
    $totalCBM = $totalCartons * $price['cbm_carton'];
    $totalWeight = $totalCartons * $price['carton_weight'];

    // Include additional cartons (add_carton1 through add_carton6) - matching forms-price-add-new.php logic
    for ($i = 1; $i <= 6; $i++) {
        $addCartonPcs = $price["add_carton{$i}_pcs"] ?? 0;
        $addCartonCBM = $price["add_carton{$i}_total_cbm"] ?? 0;
        $addCartonWeight = $price["add_carton{$i}_weight"] ?? 0;
        
        if ($addCartonPcs > 0) {
            $extraCartons = ceil($price['new_moq_quantity'] / $addCartonPcs);
            $totalCBM += $extraCartons * $addCartonCBM;
            $totalWeight += $extraCartons * $addCartonWeight;
        }
    }

    // Get freight rate based on shipping code
    $shippingCode = $price['new_freight_method'];
    $freightRate = 0;
    
    // Find the correct freight rate from the shipping rates
    foreach ($newFreightRates as $rate) {
        if ($rate['shipping_code'] === $shippingCode) {
            $freightRate = $rate['freight_rate'];
            break;
        }
    }
    
    // Determine if air freight based on shipping code
    $isAirFreight = (strtolower($shippingCode) === 'air' || strpos(strtolower($shippingCode), 'air') !== false);
    
    // Recalculate prices - use weight for air, CBM for sea
    $totalFreightCost = $isAirFreight 
        ? $freightRate * $totalWeight 
        : $freightRate * $totalCBM;

    $totalSupplierPrice = ($price['new_unit_price_yen'] * $price['new_moq_quantity']) / $newConversionRate;
    $totalPrice = $totalFreightCost + $totalSupplierPrice;
    
    return [
        'new_unit_price_rm' => $totalPrice / $price['new_moq_quantity'],
        'new_unit_freight_cost_rm' => $totalFreightCost / $price['new_moq_quantity'],
        'new_unit_profit_rm' => $price['new_selling_price'] - ($totalPrice / $price['new_moq_quantity']),
        'new_total_cbm_moq' => $totalCBM,
        'new_total_weight_moq' => $totalWeight
    ];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['update_prices'])) {
    try {
        $pdo->beginTransaction();

        // Use current conversion rate if not provided
        $newConversionRate = !empty($_POST['conversion_rate']) 
            ? $_POST['conversion_rate'] 
            : $currentConversionRate;
            
        $updatedShippingRates = $_POST['shipping_rates'];

        // Update shipping rates first
        foreach ($updatedShippingRates as $shippingId => $freightRate) {
            $updateShippingStmt = $pdo->prepare("
                UPDATE price_shipping 
                SET freight_rate = ?
                WHERE shipping_price_id = ?
            ");
            
            $updateShippingStmt->execute([
                $freightRate,
                $shippingId
            ]);
        }

        // Get updated shipping rates for calculations
        $shippingRatesUpdated = $pdo->query("SELECT * FROM price_shipping")->fetchAll(PDO::FETCH_ASSOC);

        // Get all prices that need updating
        $stmt = $pdo->query("SELECT * FROM price WHERE new_unit_price_yen IS NOT NULL");

        while ($price = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $newPrices = recalculatePrice($price, $newConversionRate, $shippingRatesUpdated);
            
            $updateStmt = $pdo->prepare("
                UPDATE price 
                SET new_conversion_rate = ?,
                    new_unit_price_rm = ?,
                    new_unit_freight_cost_rm = ?,
                    new_unit_profit_rm = ?,
                    new_total_cbm_moq = ?,
                    new_total_weight_moq = ?
                WHERE price_id = ?
            ");

            $updateStmt->execute([
                $newConversionRate,
                $newPrices['new_unit_price_rm'],
                $newPrices['new_unit_freight_cost_rm'],
                $newPrices['new_unit_profit_rm'],
                $newPrices['new_total_cbm_moq'],
                $newPrices['new_total_weight_moq'],
                $price['price_id']
            ]);
        }

        $pdo->commit();
        $_SESSION['success'] = "Prices and shipping rates updated successfully";
        
        // Redirect to prevent resubmission
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
        
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Error updating prices: " . $e->getMessage();
        
        // Redirect to prevent resubmission
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>