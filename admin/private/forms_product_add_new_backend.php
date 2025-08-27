<?php
if(session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../private/auth_check.php';
require_once __DIR__ . '/../../global/main_configuration.php';

$pdo = openDB();

// --- Handle AJAX requests first ---
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
        case 'material':
            $stmt = $pdo->prepare("SELECT material_id, material_name FROM material WHERE subcategory_id=?");
            $stmt->execute([$parent_id]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;
        case 'product_type':
            $stmt = $pdo->prepare("SELECT product_type_id, product_name FROM product_type WHERE material_id=?");
            $stmt->execute([$parent_id]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;
    }

    header('Content-Type: application/json');
    echo json_encode($data);
    exit(); // <-- prevent HTML from rendering
}

// --- Normal page content ---
$successMsg = $errorMsg = '';
$sections = $pdo->query("SELECT section_id, section_name FROM section")->fetchAll(PDO::FETCH_ASSOC);
$suppliers = $pdo->query("SELECT supplier_id, supplier_name FROM supplier WHERE deleted_at IS NULL")->fetchAll(PDO::FETCH_ASSOC);

// --- Handle form submission ---
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    try{
        $pdo->beginTransaction();

        // --- Handle sizes ---
        $size1 = !empty($_POST['size_1']) && !empty($_POST['metric_1']) ? $_POST['size_1'].' '.$_POST['metric_1'] : null;
        $size2 = !empty($_POST['size_2']) && !empty($_POST['metric_2']) ? $_POST['size_2'].' '.$_POST['metric_2'] : null;
        $size3 = !empty($_POST['size_3']) && !empty($_POST['metric_3']) ? $_POST['size_3'].' '.$_POST['metric_3'] : null;

        // --- Insert Product ---
        $stmt = $pdo->prepare("INSERT INTO Product 
            (section_id, category_id, subcategory_id, material_id, product_type_id, variant, description, production_lead_time,
            size_1, size_2, size_3, product_code)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");

        $stmt->execute([
            $_POST['section'],
            $_POST['category'],
            $_POST['subcategory'],
            $_POST['material_id'],
            $_POST['product_type_id'],
            $_POST['variant'] ?? null,
            $_POST['description'] ?? null,
            $_POST['production_lead_time'] ?? null,
            $size1,
            $size2,
            $size3,
            null
        ]);

        $product_id = $pdo->lastInsertId();

        // --- Generate Product Code ---
        $sectionName = $pdo->query("SELECT section_name FROM section WHERE section_id = ".$_POST['section'])->fetchColumn();
        $categoryName = $pdo->query("SELECT category_name FROM category WHERE category_id = ".$_POST['category'])->fetchColumn();
        $subcatName = $pdo->query("SELECT subcategory_name FROM subcategory WHERE subcategory_id = ".$_POST['subcategory'])->fetchColumn();
        $product_code = strtoupper(substr($sectionName,0,1).substr($categoryName,0,1).substr($subcatName,0,1))
                        .str_pad($product_id, 5, '0', STR_PAD_LEFT);

        $updateCodeStmt = $pdo->prepare("UPDATE Product SET product_code = ? WHERE product_id = ?");
        $updateCodeStmt->execute([$product_code, $product_id]);

        // --- Insert Price (Carton info) ---
        $supplier_id = $_POST['supplier_id'] ?? null;

        function calculateCBM($w,$h,$l){ return ($w*$h*$l)/1000000 * 1.28; }

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
                    (product_id, supplier_id, carton_width, carton_height, carton_length, pcs_per_carton, carton_weight, cbm_carton)
                    VALUES (?,?,?,?,?,?,?,?)");

                $priceStmt->execute([
                    $product_id,
                    $supplier_id,
                    $width,
                    $height,
                    $length,
                    $pcs,
                    $weight,
                    $cbm
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
        $_SESSION['successMsg'] = "Product and Carton info saved successfully!";
        header("Location: ".$_SERVER['PHP_SELF']); 
        exit;

    } catch(Exception $e){
        $pdo->rollBack();
        $errorMsg = "Error: ".$e->getMessage();
    }
}

// --- Show message after redirect ---
if(!empty($_SESSION['successMsg'])){
    $successMsg = $_SESSION['successMsg'];
    unset($_SESSION['successMsg']);
}
?>
