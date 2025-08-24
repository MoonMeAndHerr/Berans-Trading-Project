<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../private/auth_check.php';
require_once __DIR__ . '/../../global/main_configuration.php';

$pdo = openDB();

$successMessage = '';
$errorMessage = '';

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
            ':new_conversion_rate' => $_POST['new_conversion_rate'] ?? null,
            ':new_unit_price_yen' => $_POST['new_unit_price_yen'] ?? null,
            ':new_freight_method' => $_POST['new_freight_method'] ?? null,
            ':new_total_cbm_moq' => $_POST['new_total_cbm_moq'] ?? null,
            ':new_total_weight_moq' => $_POST['new_total_weight_moq'] ?? null,
            ':new_unit_price_rm' => $_POST['new_unit_price_rm'] ?? null,
            ':new_unit_freight_cost_rm' => $_POST['new_unit_freight_cost_rm'] ?? null,
            ':new_unit_profit_rm' => $_POST['new_unit_profit_rm'] ?? null,
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
                    new_unit_profit_rm = :new_unit_profit_rm
                WHERE price_id = :price_id
            ");
            $data[':price_id'] = $existing['price_id'];
            $stmt->execute($data);

            $successMessage = "Pricing record updated successfully!";
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO price (
                    product_id, supplier_id,
                    new_price_yen, new_moq_quantity, new_shipping_moq_yen, new_additional_price_moq_yen,
                    new_conversion_rate, new_unit_price_yen, new_freight_method, new_total_cbm_moq,
                    new_total_weight_moq, new_unit_price_rm, new_unit_freight_cost_rm, new_unit_profit_rm
                ) VALUES (
                    :product_id, :supplier_id,
                    :new_price_yen, :new_moq_quantity, :new_shipping_moq_yen, :new_additional_price_moq_yen,
                    :new_conversion_rate, :new_unit_price_yen, :new_freight_method, :new_total_cbm_moq,
                    :new_total_weight_moq, :new_unit_price_rm, :new_unit_freight_cost_rm, :new_unit_profit_rm
                )
            ");
            $data[':product_id'] = $product_id;
            $data[':supplier_id'] = $supplier_id;
            $stmt->execute($data);

            $successMessage = "Pricing record inserted successfully!";
        }
    }
}

// Fetch dropdown data
$sections = $pdo->query("SELECT * FROM section")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT * FROM category")->fetchAll(PDO::FETCH_ASSOC);
$subcategories = $pdo->query("SELECT * FROM subcategory")->fetchAll(PDO::FETCH_ASSOC);

// Fetch shipping methods
$shipping_methods = $pdo->query("SELECT * FROM price_shipping")->fetchAll(PDO::FETCH_ASSOC);

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