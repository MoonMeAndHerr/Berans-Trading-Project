<?php
require_once __DIR__ . '/../../global/main_configuration.php';
require_once __DIR__ . '/auth_check.php';
use GuzzleHttp\Client;
require 'vendor/autoload.php';
use League\OAuth2\Client\Provider\GenericProvider;

$pdo = openDB();

// --- Handle AJAX requests first (same as forms-product-add-new.php) ---
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

// Handle product update
if (isset($_POST['update_product']) && isset($_POST['product_id'])) {
    try {
        $pdo->beginTransaction();
        
        $product_id = $_POST['product_id'];
        
        // --- Handle sizes (same as forms-product-add-new.php) ---
        $size1 = !empty($_POST['size_1']) && !empty($_POST['metric_1']) ? $_POST['size_1'].' '.$_POST['metric_1'] : null;
        $size2 = !empty($_POST['size_2']) && !empty($_POST['metric_2']) ? $_POST['size_2'].' '.$_POST['metric_2'] : null;
        $size3 = !empty($_POST['size_3']) && !empty($_POST['metric_3']) ? $_POST['size_3'].' '.$_POST['metric_3'] : null;

        if (!empty($_FILES['product_image']['name'])) {

            $ext = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
            $product_image = 'product_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadDir . $logo_name);

            $pdo = openDB();
            $stmt = $pdo->prepare("UPDATE product SET image_url = :product_image WHERE product_id = :product_id");
            $stmt->bindParam(':product_image', $logo_name);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->execute();

        }

            $productName = $_POST['material_name'].' '.$_POST['product_type_name'].' '.$size1.'*'.$size2.'*'.$size3.' '.$_POST['variant'];

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
                    'ItemID' => $_POST['xero_relation'],   
                    'Code' => $_POST['product_code'],  // unique product code
                    'Name' => $productName,
                    'Description' => $_POST['description']
                ]
            ]);


        } catch (Exception $e) {
            // Log error but continue
            $output = var_export($e->getMessage(), true);
            echo "<script>console.log('Problem: " . $output . "' );</script>";
        }
        
        $updateStmt = $pdo->prepare("
            UPDATE product SET
                variant = ?,
                description = ?,
                production_lead_time = ?,
                size_1 = ?,
                size_2 = ?,
                size_3 = ?,
                updated_at = NOW(),
                updated_by = ?
            WHERE product_id = ? AND xero_relation = ?
        ");
        
        $updateStmt->execute([
            $_POST['variant'] ?: null,
            $_POST['description'] ?: null,
            $_POST['production_lead_time'] ?: null,
            $size1,
            $size2,
            $size3,
            $_SESSION['user_id'] ?? 1,
            $product_id,
            $_POST['xero_relation'],
        ]);

        // Update carton information in price table
        $priceCheckStmt = $pdo->prepare("SELECT price_id FROM price WHERE product_id = ? ORDER BY price_id DESC LIMIT 1");
        $priceCheckStmt->execute([$product_id]);
        $existingPrice = $priceCheckStmt->fetch(PDO::FETCH_ASSOC);

        if ($existingPrice) {
            $price_id = $existingPrice['price_id'];
            
            function calculateCBM($w,$h,$l){ return ($w*$h*$l)/1000000 * 1.28; }

            // Update supplier in price table if provided
            if (!empty($_POST['supplier_id'])) {
                $updateSupplierStmt = $pdo->prepare("UPDATE price SET supplier_id = ? WHERE price_id = ?");
                $updateSupplierStmt->execute([$_POST['supplier_id'], $price_id]);
            }

            // First, clear all additional cartons (set to NULL/0)
            for ($i = 1; $i <= 6; $i++) {
                $clearStmt = $pdo->prepare("
                    UPDATE price SET
                        add_carton{$i}_width = NULL, add_carton{$i}_height = NULL, add_carton{$i}_length = NULL,
                        add_carton{$i}_pcs = NULL, add_carton{$i}_weight = NULL, add_carton{$i}_total_cbm = NULL
                    WHERE price_id = ?
                ");
                $clearStmt->execute([$price_id]);
            }

            // Handle main carton and additional cartons
            if (isset($_POST['carton'])) {
                $cartonWidths  = $_POST['carton']['width'] ?? [];
                $cartonHeights = $_POST['carton']['height'] ?? [];
                $cartonLengths = $_POST['carton']['length'] ?? [];
                $cartonPcs     = $_POST['carton']['pcs'] ?? [];
                $cartonWeights = $_POST['carton']['weight'] ?? [];

                foreach($cartonWidths as $index => $width){
                    if (empty($width)) continue; // Skip empty cartons
                    
                    $height = $cartonHeights[$index] ?? 0;
                    $length = $cartonLengths[$index] ?? 0;
                    $pcs    = $cartonPcs[$index] ?? 0;
                    $weight = $cartonWeights[$index] ?? 0;
                    $cbm    = calculateCBM($width,$height,$length);

                    if($index === 0){
                        // Main carton update
                        $updateCartonStmt = $pdo->prepare("
                            UPDATE price SET
                                carton_width = ?, carton_height = ?, carton_length = ?,
                                pcs_per_carton = ?, carton_weight = ?, cbm_carton = ?
                            WHERE price_id = ?
                        ");
                        $updateCartonStmt->execute([$width, $height, $length, $pcs, $weight, $cbm, $price_id]);
                    } else {
                        // Additional cartons (index starts from 1 for additional cartons)
                        $updateStmt = $pdo->prepare("
                            UPDATE price SET
                                add_carton{$index}_width = ?, add_carton{$index}_height = ?, add_carton{$index}_length = ?,
                                add_carton{$index}_pcs = ?, add_carton{$index}_weight = ?, add_carton{$index}_total_cbm = ?
                            WHERE price_id = ?
                        ");
                        $updateStmt->execute([$width, $height, $length, $pcs, $weight, $cbm, $price_id]);
                    }
                }
            }
        }

        $pdo->commit();
        $success = true;
        $message = "Product updated successfully!";
        
    } catch (Exception $e) {
        $pdo->rollBack();
        $success = false;
        $message = "Error updating product: " . $e->getMessage();
    }
    
    // Return JSON response for AJAX
    if (isset($_POST['ajax_update'])) {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => $success ? 'success' : 'error', 
            'message' => $message
        ]);
        exit();
    }
    
    // Set session for regular form submission
    if ($success) {
        $_SESSION['success'] = $message;
        $_SESSION['show_success'] = true;
    } else {
        $_SESSION['error'] = $message;
        $_SESSION['show_error'] = true;
    }
    
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Handle delete action
if (isset($_POST['delete_product']) && isset($_POST['product_id'])) {
    try {
        $product_id = $_POST['product_id'];
        
        // Soft delete - update deleted_at timestamp
        $stmt = $pdo->prepare("UPDATE product SET deleted_at = NOW() WHERE product_id = ?");
        $stmt->execute([$product_id]);
        
        $_SESSION['success'] = "Product deleted successfully!";
        $_SESSION['show_success'] = true;
    } catch (Exception $e) {
        $_SESSION['error'] = "Error deleting product: " . $e->getMessage();
        $_SESSION['show_error'] = true;
    }
    
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch dropdown data for the update modal (same as forms-product-add-new.php)
$sections = $pdo->query("SELECT section_id, section_name FROM section")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT category_id, category_name, section_id FROM category")->fetchAll(PDO::FETCH_ASSOC);
$subcategories = $pdo->query("SELECT subcategory_id, subcategory_name, category_id FROM subcategory")->fetchAll(PDO::FETCH_ASSOC);
$materials = $pdo->query("SELECT material_id, material_name, subcategory_id FROM material")->fetchAll(PDO::FETCH_ASSOC);
$product_types = $pdo->query("SELECT product_type_id, product_name, material_id FROM product_type")->fetchAll(PDO::FETCH_ASSOC);
$suppliers = $pdo->query("SELECT supplier_id, supplier_name FROM supplier WHERE deleted_at IS NULL")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all products with related information including carton data
$products = $pdo->query("
    SELECT 
        p.*,
        s.section_name,
        c.category_name,
        sc.subcategory_name,
        IFNULL(m.material_name, 'N/A') as material_name,
        IFNULL(pt.product_name, 'N/A') as product_type_name,
        CONCAT(p.size_1, ' x ', p.size_2, ' x ', p.size_3) as dimensions,
        sup.supplier_name,
        sup.supplier_id,
        pr.new_selling_price,
        pr.carton_width, pr.carton_height, pr.carton_length, pr.pcs_per_carton, pr.carton_weight, pr.cbm_carton,
        pr.add_carton1_width, pr.add_carton1_height, pr.add_carton1_length, pr.add_carton1_pcs, pr.add_carton1_weight, pr.add_carton1_total_cbm,
        pr.add_carton2_width, pr.add_carton2_height, pr.add_carton2_length, pr.add_carton2_pcs, pr.add_carton2_weight, pr.add_carton2_total_cbm,
        pr.add_carton3_width, pr.add_carton3_height, pr.add_carton3_length, pr.add_carton3_pcs, pr.add_carton3_weight, pr.add_carton3_total_cbm,
        pr.add_carton4_width, pr.add_carton4_height, pr.add_carton4_length, pr.add_carton4_pcs, pr.add_carton4_weight, pr.add_carton4_total_cbm,
        pr.add_carton5_width, pr.add_carton5_height, pr.add_carton5_length, pr.add_carton5_pcs, pr.add_carton5_weight, pr.add_carton5_total_cbm,
        pr.add_carton6_width, pr.add_carton6_height, pr.add_carton6_length, pr.add_carton6_pcs, pr.add_carton6_weight, pr.add_carton6_total_cbm
    FROM product p
    LEFT JOIN section s ON p.section_id = s.section_id
    LEFT JOIN category c ON p.category_id = c.category_id
    LEFT JOIN subcategory sc ON p.subcategory_id = sc.subcategory_id
    LEFT JOIN material m ON p.material_id = m.material_id
    LEFT JOIN product_type pt ON p.product_type_id = pt.product_type_id
    LEFT JOIN (
        SELECT pr1.*
        FROM price pr1
        INNER JOIN (
            SELECT product_id, MAX(price_id) AS max_price_id
            FROM price
            GROUP BY product_id
        ) pr2 ON pr1.price_id = pr2.max_price_id
    ) pr ON p.product_id = pr.product_id
    LEFT JOIN supplier sup ON pr.supplier_id = sup.supplier_id
    WHERE p.deleted_at IS NULL
    ORDER BY p.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>