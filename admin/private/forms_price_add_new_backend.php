<?php
if(session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../private/auth_check.php';
require_once __DIR__ . '/../../global/main_configuration.php';

$pdo = openDB();
$successMsg = $errorMsg = '';

// Fetch dropdowns
$sections = $pdo->query("SELECT section_id, section_name FROM section")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT category_id, category_name FROM category")->fetchAll(PDO::FETCH_ASSOC);
$subcategories = $pdo->query("SELECT subcategory_id, subcategory_name FROM subcategory")->fetchAll(PDO::FETCH_ASSOC);
$materials = $pdo->query("SELECT material_id, material_name FROM material")->fetchAll(PDO::FETCH_ASSOC);
$product_types = $pdo->query("SELECT product_type_id, product_name FROM product_type")->fetchAll(PDO::FETCH_ASSOC);
$suppliers = $pdo->query("SELECT supplier_id, supplier_name FROM supplier WHERE deleted_at IS NULL")->fetchAll(PDO::FETCH_ASSOC);

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    try{
        $pdo->beginTransaction();

        // --- Insert Product ---
        $stmt = $pdo->prepare("INSERT INTO Product 
            (section_id, category_id, subcategory_id, material_id, product_type_id, variant, description, production_lead_time,
            size_1, size_2, size_3)
            VALUES (?,?,?,?,?,?,?,?,?,?,?)");

        $stmt->execute([
            $_POST['section'],
            $_POST['category'],
            $_POST['subcategory'],
            $_POST['material_id'],
            $_POST['product_type_id'],
            $_POST['variant'] ?? null,
            $_POST['description'] ?? null,
            $_POST['production_lead_time'] ?? null,
            !empty($_POST['size_1']) && !empty($_POST['metric_1']) ? $_POST['size_1'].' '.$_POST['metric_1'] : null,
            !empty($_POST['size_2']) && !empty($_POST['metric_2']) ? $_POST['size_2'].' '.$_POST['metric_2'] : null,
            !empty($_POST['size_3']) && !empty($_POST['metric_3']) ? $_POST['size_3'].' '.$_POST['metric_3'] : null
        ]);

        $product_id = $pdo->lastInsertId();

        // --- Generate Product Code ---
        $section = $pdo->query("SELECT section_name FROM section WHERE section_id = ".(int)$_POST['section'])->fetchColumn();
        $category = $pdo->query("SELECT category_name FROM category WHERE category_id = ".(int)$_POST['category'])->fetchColumn();
        $subcategory = $pdo->query("SELECT subcategory_name FROM subcategory WHERE subcategory_id = ".(int)$_POST['subcategory'])->fetchColumn();

        $code_prefix = strtoupper(substr($section,0,1) . substr($category,0,1) . substr($subcategory,0,1));
        $product_code = $code_prefix . str_pad($product_id,5,'0',STR_PAD_LEFT);

        $pdo->prepare("UPDATE Product SET product_code = ? WHERE product_id = ?")
            ->execute([$product_code, $product_id]);

        // --- Insert Price (Carton + Supplier info) ---
        $supplier_id = $_POST['supplier_id'] ?? null;

        // Helper function for CBM calculation
        function calculateCBM($w,$h,$l){ return ($w*$h*$l)/1000000 * 1.28; }

        // Loop over all cartons (main + additional)
        $cartonWidths  = $_POST['carton']['width'] ?? [];
        $cartonHeights = $_POST['carton']['height'] ?? [];
        $cartonLengths = $_POST['carton']['length'] ?? [];
        $cartonPcs     = $_POST['carton']['pcs'] ?? [];
        $cartonWeights = $_POST['carton']['weight'] ?? [];

        $price_id = null;

        foreach($cartonWidths as $index => $width){
            $height = $cartonHeights[$index] ?? 0;
            $length = $cartonLengths[$index] ?? 0;
            $pcs    = $cartonPcs[$index] ?? 0;
            $weight = $cartonWeights[$index] ?? 0;
            $cbm    = calculateCBM($width,$height,$length);

            if($index === 0){
                // Main carton
                $priceStmt = $pdo->prepare("INSERT INTO Price
                    (product_id, supplier_id, quantity, carton_width, carton_height, carton_length, pcs_per_carton, cbm_carton, carton_weight)
                    VALUES (?,?,?,?,?,?,?,?,?)");
                $priceStmt->execute([
                    $product_id,
                    $supplier_id,
                    $pcs,
                    $width,
                    $height,
                    $length,
                    $pcs,
                    $cbm,
                    $weight
                ]);
                $price_id = $pdo->lastInsertId();
            } else {
                // Additional cartons
                $updateStmt = $pdo->prepare("
                    UPDATE Price SET
                        add_carton{$index}_width = ?, add_carton{$index}_height = ?, add_carton{$index}_length = ?,
                        add_carton{$index}_pcs = ?, add_carton{$index}_weight = ?, add_carton{$index}_total_cbm = ?
                    WHERE price_id = ?
                ");
                $updateStmt->execute([$width, $height, $length, $pcs, $weight, $cbm, $price_id]);
            }
        }

        $pdo->commit();
        $successMsg = "Product and Price info added successfully! Product Code: $product_code";
    } catch(Exception $e){
        $pdo->rollBack();
        $errorMsg = "Error: ".$e->getMessage();
    }
}
?>
