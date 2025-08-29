<?php
if(session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/../../global/main_configuration.php';

$pdo = openDB();

// --- AJAX for dynamic dropdowns ---
if(isset($_GET['ajax'], $_GET['type'], $_GET['parent_id'])) {
    header('Content-Type: application/json');

    $type = $_GET['type'];
    $parent_id = intval($_GET['parent_id']);
    $data = [];

    switch($type){
        case 'category':
            $stmt = $pdo->prepare("SELECT category_id, category_name FROM category WHERE section_id=? ORDER BY category_name");
            $stmt->execute([$parent_id]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;

        case 'subcategory':
            $stmt = $pdo->prepare("SELECT subcategory_id, subcategory_name FROM subcategory WHERE category_id=? ORDER BY subcategory_name");
            $stmt->execute([$parent_id]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;

        case 'material':
            $stmt = $pdo->prepare("SELECT material_id, material_name FROM material WHERE subcategory_id=? ORDER BY material_name");
            $stmt->execute([$parent_id]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;

        case 'product_type':
            $stmt = $pdo->prepare("SELECT product_type_id, product_name FROM product_type WHERE material_id=? ORDER BY product_name");
            $stmt->execute([$parent_id]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;
    }

    echo json_encode($data);
    exit();
}

// --- Handle POST (Add / Update / Delete) ---
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // --- UPDATE ---
        if(!empty($_POST['update_section_id']) && !empty($_POST['update_section_name'])){
            $stmt = $pdo->prepare("UPDATE section SET section_name=? WHERE section_id=?");
            $stmt->execute([trim($_POST['update_section_name']), intval($_POST['update_section_id'])]);

            // Update product codes for all affected products
            $products = $pdo->prepare("
                SELECT DISTINCT p.product_id, p.product_code, p.section_id, p.category_id, p.subcategory_id 
                FROM product p 
                WHERE p.section_id = ? AND p.deleted_at IS NULL
            ");
            $products->execute([$_POST['update_section_id']]);
            
            foreach ($products->fetchAll() as $product) {
                $newCode = generateProductCode(
                    $pdo, 
                    $product['section_id'], 
                    $product['category_id'], 
                    $product['subcategory_id'],
                    $product['product_id']  // Pass the actual product_id
                );
                $updateCode = $pdo->prepare("UPDATE product SET product_code = ? WHERE product_id = ?");
                $updateCode->execute([$newCode, $product['product_id']]);
            }
        }
        if(!empty($_POST['update_category_id']) && !empty($_POST['update_category_name'])){
            $stmt = $pdo->prepare("UPDATE category SET category_name=? WHERE category_id=?");
            $stmt->execute([trim($_POST['update_category_name']), intval($_POST['update_category_id'])]);

            // Update product codes for all affected products
            $products = $pdo->prepare("
                SELECT product_id, section_id, category_id, subcategory_id 
                FROM product 
                WHERE category_id = ?
            ");
            $products->execute([$_POST['update_category_id']]);
            
            foreach ($products->fetchAll() as $product) {
                $newCode = generateProductCode(
                    $pdo, 
                    $product['section_id'], 
                    $product['category_id'], 
                    $product['subcategory_id'],
                    $product['product_id']  // Add product_id parameter
                );
                $updateCode = $pdo->prepare("UPDATE product SET product_code = ? WHERE product_id = ?");
                $updateCode->execute([$newCode, $product['product_id']]);
            }
        }
        if(!empty($_POST['update_subcategory_id']) && !empty($_POST['update_subcategory_name'])){
            $stmt = $pdo->prepare("UPDATE subcategory SET subcategory_name=? WHERE subcategory_id=?");
            $stmt->execute([trim($_POST['update_subcategory_name']), intval($_POST['update_subcategory_id'])]);

            // Update product codes for all affected products
            $products = $pdo->prepare("
                SELECT product_id, section_id, category_id, subcategory_id 
                FROM product 
                WHERE subcategory_id = ?
            ");
            $products->execute([$_POST['update_subcategory_id']]);
            
            foreach ($products->fetchAll() as $product) {
                $newCode = generateProductCode(
                    $pdo, 
                    $product['section_id'], 
                    $product['category_id'], 
                    $product['subcategory_id'],
                    $product['product_id']  // Add product_id parameter
                );
                $updateCode = $pdo->prepare("UPDATE product SET product_code = ? WHERE product_id = ?");
                $updateCode->execute([$newCode, $product['product_id']]);
            }
        }
        if(!empty($_POST['update_material_id']) && !empty($_POST['update_material_name'])){
            $stmt = $pdo->prepare("UPDATE material SET material_name=? WHERE material_id=?");
            $stmt->execute([trim($_POST['update_material_name']), intval($_POST['update_material_id'])]);
        }
        if(!empty($_POST['update_product_type_id']) && !empty($_POST['update_product_type_name'])){
            $stmt = $pdo->prepare("UPDATE product_type SET product_name=? WHERE product_type_id=?");
            $stmt->execute([trim($_POST['update_product_type_name']), intval($_POST['update_product_type_id'])]);
        }

        // --- ADD ---
        if(!empty($_POST['new_section'])){
            $exists = $pdo->prepare("SELECT 1 FROM section WHERE section_name=?");
            $exists->execute([trim($_POST['new_section'])]);
            if($exists->fetch()) throw new Exception("Section already exists!");
            $stmt = $pdo->prepare("INSERT INTO section (section_name) VALUES (?)");
            $stmt->execute([trim($_POST['new_section'])]);
        }
        if(!empty($_POST['new_category']) && !empty($_POST['section_id'])){
            $exists = $pdo->prepare("SELECT 1 FROM category WHERE category_name=? AND section_id=?");
            $exists->execute([trim($_POST['new_category']), intval($_POST['section_id'])]);
            if($exists->fetch()) throw new Exception("Category already exists in this Section!");
            $stmt = $pdo->prepare("INSERT INTO category (category_name, section_id) VALUES (?, ?)");
            $stmt->execute([trim($_POST['new_category']), intval($_POST['section_id'])]);
        }
        if(!empty($_POST['new_subcategory']) && !empty($_POST['category_id'])){
            $exists = $pdo->prepare("SELECT 1 FROM subcategory WHERE subcategory_name=? AND category_id=?");
            $exists->execute([trim($_POST['new_subcategory']), intval($_POST['category_id'])]);
            if($exists->fetch()) throw new Exception("Subcategory already exists in this Category!");
            $stmt = $pdo->prepare("INSERT INTO subcategory (subcategory_name, category_id) VALUES (?, ?)");
            $stmt->execute([trim($_POST['new_subcategory']), intval($_POST['category_id'])]);
        }
        if(!empty($_POST['new_material']) && !empty($_POST['subcategory_id'])){
            $exists = $pdo->prepare("SELECT 1 FROM material WHERE material_name=? AND subcategory_id=?");
            $exists->execute([trim($_POST['new_material']), intval($_POST['subcategory_id'])]);
            if($exists->fetch()) throw new Exception("Material already exists in this Subcategory!");
            $stmt = $pdo->prepare("INSERT INTO material (material_name, subcategory_id) VALUES (?, ?)");
            $stmt->execute([trim($_POST['new_material']), intval($_POST['subcategory_id'])]);
        }
        if(!empty($_POST['new_product_type']) && !empty($_POST['material_id'])){
            $exists = $pdo->prepare("SELECT 1 FROM product_type WHERE product_name=? AND material_id=?");
            $exists->execute([trim($_POST['new_product_type']), intval($_POST['material_id'])]);
            if($exists->fetch()) throw new Exception("Product Type already exists in this Material!");
            $stmt = $pdo->prepare("INSERT INTO product_type (product_name, material_id) VALUES (?, ?)");
            $stmt->execute([trim($_POST['new_product_type']), intval($_POST['material_id'])]);
        }

        // --- DELETE (smart cascading) ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_level'])) {
            header('Content-Type: application/json');
            
            $level = $_POST['delete_level'];
            $id = $_POST["delete_{$level}_id"] ?? null;
            
            if(!$id) {
                echo json_encode(['error' => 'No ID provided']);
                exit;
            }

            // If not confirmed, show warning first
            if(!isset($_POST['confirm_delete'])) {
                $count_query = "
                    SELECT 
                        CASE 
                            WHEN ? = 'section' THEN (SELECT COUNT(*) FROM category WHERE section_id = ?)
                            WHEN ? = 'category' THEN (SELECT COUNT(*) FROM subcategory WHERE category_id = ?)
                            WHEN ? = 'subcategory' THEN (SELECT COUNT(*) FROM material WHERE subcategory_id = ?)
                            WHEN ? = 'material' THEN (SELECT COUNT(*) FROM product_type WHERE material_id = ?)
                            ELSE 0 
                        END as related_count,
                        (SELECT COUNT(*) FROM product WHERE {$level}_id = ?) as product_count,
                        (SELECT COUNT(*) FROM price p JOIN product pr ON p.product_id = pr.product_id WHERE pr.{$level}_id = ?) as price_count
                ";

                $stmt = $pdo->prepare($count_query);
                $stmt->execute([$level, $id, $level, $id, $level, $id, $level, $id, $id, $id]);
                $result = $stmt->fetch();

                $message = "⚠️ WARNING: This will permanently delete {$level}:\n";
                if ($result['related_count'] > 0) $message .= "- {$result['related_count']} related items\n";
                if ($result['product_count'] > 0) $message .= "- {$result['product_count']} products\n";
                if ($result['price_count'] > 0) $message .= "- {$result['price_count']} price records\n";
                
                echo json_encode([
                    'warning' => true,
                    'message' => $message,
                    'pending_delete' => $_POST
                ]);
                exit;
            }

            // If confirmed, proceed with deletion
            try {
                $pdo->beginTransaction();

                // First delete related prices
                $pdo->prepare("DELETE p FROM price p INNER JOIN product pr ON p.product_id = pr.product_id WHERE pr.{$level}_id = ?")->execute([$id]);
                
                // Then delete products
                $pdo->prepare("DELETE FROM product WHERE {$level}_id = ?")->execute([$id]);

                // Then delete related items based on level
                switch($level) {
                    case 'section':
                        $pdo->prepare("DELETE s FROM subcategory s INNER JOIN category c ON s.category_id = c.category_id WHERE c.section_id = ?")->execute([$id]);
                        $pdo->prepare("DELETE FROM category WHERE section_id = ?")->execute([$id]);
                        $pdo->prepare("DELETE FROM section WHERE section_id = ?")->execute([$id]);
                        break;
                    case 'category':
                        $pdo->prepare("DELETE FROM material WHERE subcategory_id IN (SELECT subcategory_id FROM subcategory WHERE category_id = ?)")->execute([$id]);
                        $pdo->prepare("DELETE FROM subcategory WHERE category_id = ?")->execute([$id]);
                        $pdo->prepare("DELETE FROM category WHERE category_id = ?")->execute([$id]);
                        break;
                    case 'subcategory':
                        $pdo->prepare("DELETE FROM product_type WHERE material_id IN (SELECT material_id FROM material WHERE subcategory_id = ?)")->execute([$id]);
                        $pdo->prepare("DELETE FROM material WHERE subcategory_id = ?")->execute([$id]);
                        $pdo->prepare("DELETE FROM subcategory WHERE subcategory_id = ?")->execute([$id]);
                        break;
                    case 'material':
                        $pdo->prepare("DELETE FROM product_type WHERE material_id = ?")->execute([$id]);
                        $pdo->prepare("DELETE FROM material WHERE material_id = ?")->execute([$id]);
                        break;
                    case 'product_type':
                        $pdo->prepare("DELETE FROM product_type WHERE product_type_id = ?")->execute([$id]);
                        break;
                }

                $pdo->commit();
                echo json_encode(['success' => true]);
                exit;
            } catch(Exception $e) {
                $pdo->rollBack();
                echo json_encode(['error' => $e->getMessage()]);
                exit;
            }
        }

        $_SESSION['modal_success'] = "✅ Items added/updated/deleted successfully!";
    } catch(Exception $e){
        $_SESSION['modal_error'] = "❌ " . $e->getMessage();
    }

    // Save POST for repopulation
    $_SESSION['modal_values'] = $_POST;
    $_SESSION['reopen_modal'] = true; // Add this line
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

// Update the generateProductCode function
function generateProductCode($pdo, $section_id, $category_id, $subcategory_id, $product_id = null) {
    // Get first letters
    $stmt = $pdo->prepare("SELECT section_name FROM section WHERE section_id = ?");
    $stmt->execute([$section_id]);
    $section = $stmt->fetch()['section_name'];
    
    $stmt = $pdo->prepare("SELECT category_name FROM category WHERE category_id = ?");
    $stmt->execute([$category_id]);
    $category = $stmt->fetch()['category_name'];
    
    $stmt = $pdo->prepare("SELECT subcategory_name FROM subcategory WHERE subcategory_id = ?");
    $stmt->execute([$subcategory_id]);
    $subcategory = $stmt->fetch()['subcategory_name'];

    // Generate prefix from first letters
    $prefix = strtoupper(substr($section, 0, 1) . substr($category, 0, 1) . substr($subcategory, 0, 1));
    
    // Use provided product_id or get next available
    if ($product_id === null) {
        $stmt = $pdo->query("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'product'");
        $product_id = $stmt->fetch()['AUTO_INCREMENT'] ?? 1;
    }
    
    // Keep the product_id padded to 4 digits
    $number = str_pad($product_id, 4, '0', STR_PAD_LEFT);
    
    return $prefix . $number;
}

// Handle Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_section_id'])) {
    try {
        $pdo->beginTransaction();

        // Update section
        if (!empty($_POST['update_section_name'])) {
            $stmt = $pdo->prepare("UPDATE section SET section_name = ? WHERE section_id = ?");
            $stmt->execute([$_POST['update_section_name'], $_POST['update_section_id']]);

            // Update product codes for all affected products
            $products = $pdo->prepare("
                SELECT DISTINCT p.product_id, p.product_code, p.section_id, p.category_id, p.subcategory_id 
                FROM product p 
                WHERE p.section_id = ? AND p.deleted_at IS NULL
            ");
            $products->execute([$_POST['update_section_id']]);
            
            foreach ($products->fetchAll() as $product) {
                $newCode = generateProductCode(
                    $pdo, 
                    $product['section_id'], 
                    $product['category_id'], 
                    $product['subcategory_id'],
                    $product['product_id']  // Pass the actual product_id
                );
                $updateCode = $pdo->prepare("UPDATE product SET product_code = ? WHERE product_id = ?");
                $updateCode->execute([$newCode, $product['product_id']]);
            }
        }

        // Update category
        if (!empty($_POST['update_category_name'])) {
            $stmt = $pdo->prepare("UPDATE category SET category_name = ? WHERE category_id = ?");
            $stmt->execute([$_POST['update_category_name'], $_POST['update_category_id']]);

            // Update product codes for all affected products
            $products = $pdo->prepare("
                SELECT product_id, section_id, category_id, subcategory_id 
                FROM product 
                WHERE category_id = ?
            ");
            $products->execute([$_POST['update_category_id']]);
            
            foreach ($products->fetchAll() as $product) {
                $newCode = generateProductCode(
                    $pdo, 
                    $product['section_id'], 
                    $product['category_id'], 
                    $product['subcategory_id'],
                    $product['product_id']  // Add product_id parameter
                );
                $updateCode = $pdo->prepare("UPDATE product SET product_code = ? WHERE product_id = ?");
                $updateCode->execute([$newCode, $product['product_id']]);
            }
        }

        // Update subcategory
        if (!empty($_POST['update_subcategory_name'])) {
            $stmt = $pdo->prepare("UPDATE subcategory SET subcategory_name = ? WHERE subcategory_id = ?");
            $stmt->execute([$_POST['update_subcategory_name'], $_POST['update_subcategory_id']]);

            // Update product codes for all affected products
            $products = $pdo->prepare("
                SELECT product_id, section_id, category_id, subcategory_id 
                FROM product 
                WHERE subcategory_id = ?
            ");
            $products->execute([$_POST['update_subcategory_id']]);
            
            foreach ($products->fetchAll() as $product) {
                $newCode = generateProductCode(
                    $pdo, 
                    $product['section_id'], 
                    $product['category_id'], 
                    $product['subcategory_id'],
                    $product['product_id']  // Add product_id parameter
                );
                $updateCode = $pdo->prepare("UPDATE product SET product_code = ? WHERE product_id = ?");
                $updateCode->execute([$newCode, $product['product_id']]);
            }
        }

        // Similar updates for material and product_type...

        $pdo->commit();
        $_SESSION['modal_success'] = "Update successful. Product codes have been updated.";
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['modal_error'] = "Update failed: " . $e->getMessage();
    }
}

// Handle Deletes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_level'])) {
    header('Content-Type: application/json');
    
    $level = $_POST['delete_level'];
    $id = $_POST["delete_{$level}_id"] ?? null;
    
    if(!$id) {
        echo json_encode(['error' => 'No ID provided']);
        exit;
    }

    // If not confirmed, show warning first
    if(!isset($_POST['confirm_delete'])) {
        $count_query = "
            SELECT 
                CASE 
                    WHEN ? = 'section' THEN (SELECT COUNT(*) FROM category WHERE section_id = ?)
                    WHEN ? = 'category' THEN (SELECT COUNT(*) FROM subcategory WHERE category_id = ?)
                    WHEN ? = 'subcategory' THEN (SELECT COUNT(*) FROM material WHERE subcategory_id = ?)
                    WHEN ? = 'material' THEN (SELECT COUNT(*) FROM product_type WHERE material_id = ?)
                    ELSE 0 
                END as related_count,
                (SELECT COUNT(*) FROM product WHERE {$level}_id = ?) as product_count,
                (SELECT COUNT(*) FROM price p JOIN product pr ON p.product_id = pr.product_id WHERE pr.{$level}_id = ?) as price_count
        ";

        $stmt = $pdo->prepare($count_query);
        $stmt->execute([$level, $id, $level, $id, $level, $id, $level, $id, $id, $id]);
        $result = $stmt->fetch();

        $message = "⚠️ WARNING: This will permanently delete {$level}:\n";
        if ($result['related_count'] > 0) $message .= "- {$result['related_count']} related items\n";
        if ($result['product_count'] > 0) $message .= "- {$result['product_count']} products\n";
        if ($result['price_count'] > 0) $message .= "- {$result['price_count']} price records\n";
        
        echo json_encode([
            'warning' => true,
            'message' => $message,
            'pending_delete' => $_POST
        ]);
        exit;
    }

    // If confirmed, proceed with deletion
    try {
        $pdo->beginTransaction();

        // First delete related prices
        $pdo->prepare("DELETE p FROM price p INNER JOIN product pr ON p.product_id = pr.product_id WHERE pr.{$level}_id = ?")->execute([$id]);
        
        // Then delete products
        $pdo->prepare("DELETE FROM product WHERE {$level}_id = ?")->execute([$id]);

        // Then delete related items based on level
        switch($level) {
            case 'section':
                $pdo->prepare("DELETE s FROM subcategory s INNER JOIN category c ON s.category_id = c.category_id WHERE c.section_id = ?")->execute([$id]);
                $pdo->prepare("DELETE FROM category WHERE section_id = ?")->execute([$id]);
                $pdo->prepare("DELETE FROM section WHERE section_id = ?")->execute([$id]);
                break;
            case 'category':
                $pdo->prepare("DELETE FROM material WHERE subcategory_id IN (SELECT subcategory_id FROM subcategory WHERE category_id = ?)")->execute([$id]);
                $pdo->prepare("DELETE FROM subcategory WHERE category_id = ?")->execute([$id]);
                $pdo->prepare("DELETE FROM category WHERE category_id = ?")->execute([$id]);
                break;
            case 'subcategory':
                $pdo->prepare("DELETE FROM product_type WHERE material_id IN (SELECT material_id FROM material WHERE subcategory_id = ?)")->execute([$id]);
                $pdo->prepare("DELETE FROM material WHERE subcategory_id = ?")->execute([$id]);
                $pdo->prepare("DELETE FROM subcategory WHERE subcategory_id = ?")->execute([$id]);
                break;
            case 'material':
                $pdo->prepare("DELETE FROM product_type WHERE material_id = ?")->execute([$id]);
                $pdo->prepare("DELETE FROM material WHERE material_id = ?")->execute([$id]);
                break;
            case 'product_type':
                $pdo->prepare("DELETE FROM product_type WHERE product_type_id = ?")->execute([$id]);
                break;
        }

        $pdo->commit();
        echo json_encode(['success' => true]);
        exit;
    } catch(Exception $e) {
        $pdo->rollBack();
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

// Inside your POST handling section, update the delete confirmation code:
if (isset($_POST['confirm_delete'])) {
    $pdo->beginTransaction();
    try {
        if (!empty($_POST['delete_section_id'])) {
            // Delete price records first
            $stmt = $pdo->prepare("
                DELETE p FROM price p 
                INNER JOIN product pr ON p.product_id = pr.product_id 
                WHERE pr.section_id = ?
            ");
            $stmt->execute([$_POST['delete_section_id']]);

            // Delete products
            $stmt = $pdo->prepare("DELETE FROM product WHERE section_id = ?");
            $stmt->execute([$_POST['delete_section_id']]);

            // Delete all subcategories
            $stmt = $pdo->prepare("
                DELETE s FROM subcategory s 
                INNER JOIN category c ON s.category_id = c.category_id 
                WHERE c.section_id = ?
            ");
            $stmt->execute([$_POST['delete_section_id']]);

            // Delete categories
            $stmt = $pdo->prepare("DELETE FROM category WHERE section_id = ?");
            $stmt->execute([$_POST['delete_section_id']]);

            // Finally delete section
            $stmt = $pdo->prepare("DELETE FROM section WHERE section_id = ?");
            $stmt->execute([$_POST['delete_section_id']]);
        }
        // Add similar blocks for category, subcategory, etc.

        $pdo->commit();
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'All selected items have been permanently deleted.'
        ]);
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => 'Delete failed: ' . $e->getMessage()
        ]);
        exit;
    }
}

// Inside the delete section, update the success response:
if ($result['category_count'] > 0 || $result['product_count'] > 0 || $result['price_count'] > 0) {
    if (!isset($_POST['confirm_delete'])) {
        $message = "⚠️ WARNING: This will permanently delete:\n";
        if ($result['category_count'] > 0) $message .= "- {$result['category_count']} categories\n";
        if ($result['product_count'] > 0) $message .= "- {$result['product_count']} products\n";
        if ($result['price_count'] > 0) $message .= "- {$result['price_count']} price records\n";
        $message .= "\nThis action CANNOT be undone. Are you sure?";
        
        header('Content-Type: application/json');
        echo json_encode([
            'warning' => true,
            'message' => $message,
            'pending_delete' => $_POST
        ]);
        exit;
    }
} 

// After successful deletion:
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'message' => "✅ Successfully deleted the selected items and all associated data!"
]);
exit;
