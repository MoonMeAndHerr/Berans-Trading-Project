<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../private/auth_check.php';
require_once __DIR__ . '/../../global/main_configuration.php';

$pdo = openDB();

$errors = [];
$success = '';
$price_id = $_GET['price_id'] ?? $_POST['price_id'] ?? null;

// ===== Fetch product options =====
try {
    $stmt = $pdo->query("SELECT product_id, name, size_volume FROM product ORDER BY name ASC");
    $productOptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $productOptions = [];
}

// ===== Fetch supplier options =====
try {
    $stmt = $pdo->query("SELECT supplier_id, supplier_name FROM supplier ORDER BY supplier_name ASC");
    $supplierOptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $supplierOptions = [];
}


// ===== Fetch all price records (with product & supplier info) =====
try {
    $stmt = $pdo->query("
        SELECT p.*, pr.name AS product_name, pr.size_volume, s.supplier_name
        FROM price p
        JOIN product pr ON p.product_id = pr.product_id
        JOIN supplier s ON p.supplier_id = s.supplier_id
        ORDER BY p.price_id ASC
    ");
    $priceRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $priceRecords = [];
    $errors[] = "Error fetching price records: " . $e->getMessage();
}

// ===== Prefill if price_id provided =====
$priceData = [];
if (!empty($price_id)) {
    try {
        $stmt = $pdo->prepare("
            SELECT p.*, pr.name AS product_name, pr.size_volume, s.supplier_name
            FROM price p
            JOIN product pr ON p.product_id = pr.product_id
            JOIN supplier s ON p.supplier_id = s.supplier_id
            WHERE p.price_id = ?
        ");
        $stmt->execute([$price_id]);
        $priceData = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$priceData) {
            $errors[] = "Price record not found.";
            $price_id = null;
        }
    } catch (PDOException $e) {
        $errors[] = "❌ Error fetching price data: " . $e->getMessage();
        $price_id = null;
    }
}

// ===== Handle form submission =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($price_id)) {
    // Grab all editable fields from POST
    $product_id      = $_POST['product_id'] !== '' ? intval($_POST['product_id']) : null;
    $supplier_id     = $_POST['supplier_id'] !== '' ? intval($_POST['supplier_id']) : null;
    $quantity        = $_POST['quantity'] !== '' ? floatval($_POST['quantity']) : null;
    $carton_width    = $_POST['carton_width'] !== '' ? floatval($_POST['carton_width']) : null;
    $carton_height   = $_POST['carton_height'] !== '' ? floatval($_POST['carton_height']) : null;
    $carton_length   = $_POST['carton_length'] !== '' ? floatval($_POST['carton_length']) : null;
    $pcs_per_carton  = $_POST['pcs_per_carton'] !== '' ? intval($_POST['pcs_per_carton']) : null;
    $no_of_carton    = $_POST['no_of_carton'] !== '' ? intval($_POST['no_of_carton']) : null;
    $designlogo      = $_POST['designlogo'] ?? '';
    $price           = $_POST['price'] !== '' ? floatval($_POST['price']) : null;
    $shipping_price  = $_POST['shipping_price'] !== '' ? floatval($_POST['shipping_price']) : null;
    $additional_price = $_POST['additional_price'] !== '' ? floatval($_POST['additional_price']) : null;
    $conversion_rate = $_POST['conversion_rate'] !== '' ? floatval($_POST['conversion_rate']) : null;
    $estimated_arrival = $_POST['estimated_arrival'] !== '' ? $_POST['estimated_arrival'] : null;

    // basic validation
    if ($quantity === null || $quantity <= 0) {
        $errors[] = "Quantity must be greater than 0.";
    }

    // ✅ validate product_id exists
    if ($product_id !== null) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM product WHERE product_id = ?");
        $stmt->execute([$product_id]);
        if ($stmt->fetchColumn() == 0) {
            $errors[] = "Selected product does not exist.";
        }
    } else {
        $errors[] = "Product is required.";
    }

    // ✅ validate supplier_id exists
    if ($supplier_id !== null) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM supplier WHERE supplier_id = ?");
        $stmt->execute([$supplier_id]);
        if ($stmt->fetchColumn() == 0) {
            $errors[] = "Selected supplier does not exist.";
        }
    } else {
        $errors[] = "Supplier is required.";
    }

    if (empty($errors)) {
        try {
            $sql = "
                UPDATE price SET
                    product_id = :product_id,
                    supplier_id = :supplier_id,
                    quantity = :quantity,
                    carton_width = :carton_width,
                    carton_height = :carton_height,
                    carton_length = :carton_length,
                    pcs_per_carton = :pcs_per_carton,
                    no_of_carton = :no_of_carton,
                    designlogo = :designlogo,
                    price = :price,
                    shipping_price = :shipping_price,
                    additional_price = :additional_price,
                    conversion_rate = :conversion_rate,
                    estimated_arrival = :estimated_arrival
                WHERE price_id = :price_id
            ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':product_id' => $product_id,
                ':supplier_id' => $supplier_id,
                ':quantity' => $quantity,
                ':carton_width' => $carton_width,
                ':carton_height' => $carton_height,
                ':carton_length' => $carton_length,
                ':pcs_per_carton' => $pcs_per_carton,
                ':no_of_carton' => $no_of_carton,
                ':designlogo' => $designlogo,
                ':price' => $price,
                ':shipping_price' => $shipping_price,
                ':additional_price' => $additional_price,
                ':conversion_rate' => $conversion_rate,
                ':estimated_arrival' => $estimated_arrival,
                ':price_id' => $price_id
            ]);

            $success = "✅ Price record updated successfully!";

            // Refresh price data
            $stmt = $pdo->prepare("
                SELECT p.*, pr.name AS product_name, pr.size_volume, s.supplier_name
                FROM price p
                JOIN product pr ON p.product_id = pr.product_id
                JOIN supplier s ON p.supplier_id = s.supplier_id
                WHERE p.price_id = ?
            ");
            $stmt->execute([$price_id]);
            $priceData = $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            $errors[] = "❌ Database error: " . $e->getMessage();
        }

        $price_id = null;
        $priceData = [];
    }
}

closeDB($pdo);

$successMsg = $success;
$errorMsg = !empty($errors) ? implode('<br>', $errors) : '';
?>
