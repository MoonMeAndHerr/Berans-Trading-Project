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
        }
        if(!empty($_POST['update_category_id']) && !empty($_POST['update_category_name'])){
            $stmt = $pdo->prepare("UPDATE category SET category_name=? WHERE category_id=?");
            $stmt->execute([trim($_POST['update_category_name']), intval($_POST['update_category_id'])]);
        }
        if(!empty($_POST['update_subcategory_id']) && !empty($_POST['update_subcategory_name'])){
            $stmt = $pdo->prepare("UPDATE subcategory SET subcategory_name=? WHERE subcategory_id=?");
            $stmt->execute([trim($_POST['update_subcategory_name']), intval($_POST['update_subcategory_id'])]);
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
        if(!empty($_POST['delete_product_type_id'])){
            $stmt = $pdo->prepare("DELETE FROM product_type WHERE product_type_id=?");
            $stmt->execute([intval($_POST['delete_product_type_id'])]);
        } elseif(!empty($_POST['delete_material_id'])){
            // delete all product_types under material
            $stmt = $pdo->prepare("DELETE FROM product_type WHERE material_id=?");
            $stmt->execute([intval($_POST['delete_material_id'])]);
            $stmt = $pdo->prepare("DELETE FROM material WHERE material_id=?");
            $stmt->execute([intval($_POST['delete_material_id'])]);
        } elseif(!empty($_POST['delete_subcategory_id'])){
            // delete materials and product_types under subcategory
            $stmt = $pdo->prepare("SELECT material_id FROM material WHERE subcategory_id=?");
            $stmt->execute([intval($_POST['delete_subcategory_id'])]);
            $materials = $stmt->fetchAll(PDO::FETCH_COLUMN);
            if($materials){
                $in = str_repeat('?,', count($materials)-1) . '?';
                $pdo->prepare("DELETE FROM product_type WHERE material_id IN ($in)")->execute($materials);
                $pdo->prepare("DELETE FROM material WHERE material_id IN ($in)")->execute($materials);
            }
            $stmt = $pdo->prepare("DELETE FROM subcategory WHERE subcategory_id=?");
            $stmt->execute([intval($_POST['delete_subcategory_id'])]);
        } elseif(!empty($_POST['delete_category_id'])){
            // delete subcategories, materials, product_types under category
            $stmt = $pdo->prepare("SELECT subcategory_id FROM subcategory WHERE category_id=?");
            $stmt->execute([intval($_POST['delete_category_id'])]);
            $subcategories = $stmt->fetchAll(PDO::FETCH_COLUMN);
            if($subcategories){
                $inSub = str_repeat('?,', count($subcategories)-1) . '?';
                $stmtMat = $pdo->prepare("SELECT material_id FROM material WHERE subcategory_id IN ($inSub)");
                $stmtMat->execute($subcategories);
                $materials = $stmtMat->fetchAll(PDO::FETCH_COLUMN);
                if($materials){
                    $inMat = str_repeat('?,', count($materials)-1) . '?';
                    $pdo->prepare("DELETE FROM product_type WHERE material_id IN ($inMat)")->execute($materials);
                    $pdo->prepare("DELETE FROM material WHERE material_id IN ($inMat)")->execute($materials);
                }
                $pdo->prepare("DELETE FROM subcategory WHERE subcategory_id IN ($inSub)")->execute($subcategories);
            }
            $stmt = $pdo->prepare("DELETE FROM category WHERE category_id=?");
            $stmt->execute([intval($_POST['delete_category_id'])]);
        } elseif(!empty($_POST['delete_section_id'])){
            // delete all categories/subcategories/materials/product_types under section
            $stmt = $pdo->prepare("SELECT category_id FROM category WHERE section_id=?");
            $stmt->execute([intval($_POST['delete_section_id'])]);
            $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
            if($categories){
                $inCat = str_repeat('?,', count($categories)-1) . '?';
                $stmtSub = $pdo->prepare("SELECT subcategory_id FROM subcategory WHERE category_id IN ($inCat)");
                $stmtSub->execute($categories);
                $subcategories = $stmtSub->fetchAll(PDO::FETCH_COLUMN);
                if($subcategories){
                    $inSub = str_repeat('?,', count($subcategories)-1) . '?';
                    $stmtMat = $pdo->prepare("SELECT material_id FROM material WHERE subcategory_id IN ($inSub)");
                    $stmtMat->execute($subcategories);
                    $materials = $stmtMat->fetchAll(PDO::FETCH_COLUMN);
                    if($materials){
                        $inMat = str_repeat('?,', count($materials)-1) . '?';
                        $pdo->prepare("DELETE FROM product_type WHERE material_id IN ($inMat)")->execute($materials);
                        $pdo->prepare("DELETE FROM material WHERE material_id IN ($inMat)")->execute($materials);
                    }
                    $pdo->prepare("DELETE FROM subcategory WHERE subcategory_id IN ($inSub)")->execute($subcategories);
                }
                $pdo->prepare("DELETE FROM category WHERE category_id IN ($inCat)")->execute($categories);
            }
            $stmt = $pdo->prepare("DELETE FROM section WHERE section_id=?");
            $stmt->execute([intval($_POST['delete_section_id'])]);
        }

        $_SESSION['modal_success'] = "✅ Items added/updated/deleted successfully!";
    } catch(Exception $e){
        $_SESSION['modal_error'] = "❌ " . $e->getMessage();
    }

    // Save POST for repopulation
    $_SESSION['modal_values'] = $_POST;

    $redirect = $_SERVER['HTTP_REFERER'] ?? '/admin/public/forms_product_add_new.php';
    header("Location: ".$redirect."#addSCSMPModal");
    exit();
}
