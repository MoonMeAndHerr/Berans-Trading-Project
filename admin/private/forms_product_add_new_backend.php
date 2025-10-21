<?php
if(session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../private/auth_check.php';
require_once __DIR__ . '/../../global/main_configuration.php';
use League\OAuth2\Client\Provider\GenericProvider;
use GuzzleHttp\Client;

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

        $uploadDir = '../../media/';
        $all_images = [];

        // --- Generate Product Code ---
        $sectionName = $pdo->query("SELECT section_name FROM section WHERE section_id = ".$_POST['section'])->fetchColumn();
        $categoryName = $pdo->query("SELECT category_name FROM category WHERE category_id = ".$_POST['category'])->fetchColumn();
        $subcatName = $pdo->query("SELECT subcategory_name FROM subcategory WHERE subcategory_id = ".$_POST['subcategory'])->fetchColumn();

        $stmt = $pdo->prepare("SELECT * FROM material WHERE material_id = ?");
        $stmt->execute([$_POST['material_id']]);
        $material = $stmt->fetch(PDO::FETCH_ASSOC);
        $materrialName = $material['material_name'];

        $stmt = $pdo->prepare("SELECT * FROM product_type WHERE product_type_id = ?");
        $stmt->execute([$_POST['product_type_id']]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        $productType = $product['product_name'];

        // --- Handle sizes ---
        $size1 = !empty($_POST['size_1']) && !empty($_POST['metric_1']) ? $_POST['size_1'].''.$_POST['metric_1'] : null;
        $size2 = !empty($_POST['size_2']) && !empty($_POST['metric_2']) ? $_POST['size_2'].''.$_POST['metric_2'] : null;
        $size3 = !empty($_POST['size_3']) && !empty($_POST['metric_3']) ? $_POST['size_3'].''.$_POST['metric_3'] : null;

        $productName = $materrialName.' '.$productType.' '.$size1.'*'.$size2.' '.$_POST['variant'];

        if (strlen($productName) > 50) {
            $_SESSION['errorMsg'] = "Product name exceeds 50 characters limit for Xero integration.";
            header("Location: ".$_SERVER['PHP_SELF']);
            exit;
        } 

        if (!empty($_FILES['image']['name'])) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $logo_name = 'product_cover_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $logo_name);
            $all_images[] = $logo_name; // add to array
        }

        if (!empty($_FILES['listimg']['name'][0])) {
            foreach ($_FILES['listimg']['name'] as $key => $filename) {
                if (!empty($filename)) {
                    $ext = pathinfo($filename, PATHINFO_EXTENSION);
                    $img_name = 'product_' . time() . '_' . $key . '.' . $ext;
                    move_uploaded_file($_FILES['listimg']['tmp_name'][$key], $uploadDir . $img_name);
                    $all_images[] = $img_name; // add each image to array
                }
            }
        }

        $images_string = implode(',', $all_images);

        // --- Insert Product ---
        $stmt = $pdo->prepare("INSERT INTO product 
            (section_id, category_id, subcategory_id, material_id, product_type_id, variant, description, production_lead_time,
            image_url,size_1, size_2, size_3, xero_relation, product_code)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

        $stmt->execute([
            $_POST['section'],
            $_POST['category'],
            $_POST['subcategory'],
            $_POST['material_id'],
            $_POST['product_type_id'],
            $_POST['variant'] ?? null,
            $_POST['description'] ?? null,
            $_POST['production_lead_time'] ?? null,
            $images_string ?? null,
            $size1,
            $size2,
            $size3,
            $xero_relation,
            null
        ]);

        $product_id = $pdo->lastInsertId();

        $product_code = strtoupper(substr($sectionName,0,1).substr($categoryName,0,1).substr($subcatName,0,1))
                        .str_pad($product_id, 5, '0', STR_PAD_LEFT);

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
                    'Code' => $product_code,  // unique product code
                    'Name' => $productName,
                    'Description' => $_POST['description'],
                    'IsSold' => true,
                    'IsPurchased' => false,
                    'IsTrackedAsInventory' => false, // no stock tracking

                    'SalesDetails' => [
                        'UnitPrice'   => 1.00,
                        'AccountCode' => '200',   // Sales account in Xero
                        'TaxType'     => 'OUTPUT' // GST/VAT sales tax (depends on your Xero settings)
                    ]
                ]
            ]);

            $result = json_decode($response->getBody(), true);
            $xero_relation = $result['Items'][0]['ItemID'] ?? null;

        } catch (Exception $e) {
            // Log error but continue
            $output = var_export($e->getMessage(), true);
            echo "<script>console.log('Problem: " . $output . "' );</script>";
        }

        $updateCodeStmt = $pdo->prepare("UPDATE product SET product_code = ?, xero_relation = ? WHERE product_id = ?");
        $updateCodeStmt->execute([$product_code, $xero_relation, $product_id]);

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
            
            // Format decimal values to 3 decimal places
            $width = number_format(floatval($width), 3, '.', '');
            $height = number_format(floatval($height), 3, '.', '');
            $length = number_format(floatval($length), 3, '.', '');
            $weight = number_format(floatval($weight), 3, '.', '');
            $cbm = number_format(floatval($cbm), 3, '.', '');

            if($index === 0){
                // Main carton
                $priceStmt = $pdo->prepare("INSERT INTO price
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
                    UPDATE price SET
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
