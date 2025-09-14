<?php

require_once '../../global/main_configuration.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $keyword = trim($_POST['keyword'] ?? '');
    $sectionId = trim($_POST['section'] ?? ''); 

    $search = "%$keyword%";
    if ($sectionId == 'all') {
        $sectionId = '';
    }

    if ($keyword === '' && $sectionId === '') {
        // No keyword and no section
        $pdo = openDB();
        $stmt = $pdo->query("SELECT * FROM product p
                             JOIN section s ON p.section_id = s.section_id
                             LEFT JOIN category c ON p.category_id = c.category_id
                             LEFT JOIN subcategory sc ON p.subcategory_id = sc.subcategory_id
                             LEFT JOIN material mt ON p.material_id = mt.material_id
                             LEFT JOIN product_type pt ON p.product_type_id = pt.product_type_id");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = count($results);
        closeDB($pdo);

    } elseif ($keyword !== '' && $sectionId === '') {
        // Keyword only
        $pdo = openDB();
        $sql = "SELECT p.*, s.section_name, c.category_name, sc.subcategory_name, mt.*, pt.*
                FROM product p
                JOIN section s ON p.section_id = s.section_id
                LEFT JOIN category c ON p.category_id = c.category_id
                LEFT JOIN subcategory sc ON p.subcategory_id = sc.subcategory_id
                LEFT JOIN material mt ON p.material_id = mt.material_id
                LEFT JOIN product_type pt ON p.product_type_id = pt.product_type_id
                WHERE p.product_code COLLATE utf8mb4_general_ci LIKE ?
                   OR p.description COLLATE utf8mb4_general_ci LIKE ?
                   OR c.category_name COLLATE utf8mb4_general_ci LIKE ?
                   OR sc.subcategory_name COLLATE utf8mb4_general_ci LIKE ?
                   OR mt.material_name COLLATE utf8mb4_general_ci LIKE ?
                   OR pt.product_name COLLATE utf8mb4_general_ci LIKE ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$search, $search, $search, $search, $search, $search]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = count($results);
        closeDB($pdo);

    } elseif ($keyword === '' && $sectionId !== '') {
        // Section only (by ID)
        $pdo = openDB();
        $sql = "SELECT p.*, s.section_name, c.category_name, sc.subcategory_name, mt.*, pt.*
                FROM product p
                JOIN section s ON p.section_id = s.section_id
                LEFT JOIN category c ON p.category_id = c.category_id
                LEFT JOIN subcategory sc ON p.subcategory_id = sc.subcategory_id
                LEFT JOIN material mt ON p.material_id = mt.material_id
                LEFT JOIN product_type pt ON p.product_type_id = pt.product_type_id
                WHERE s.section_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$sectionId]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = count($results);
        closeDB($pdo);

    } else {
        // Both keyword and section (by ID)
        $pdo = openDB();
        $sql = "SELECT p.*, s.section_name, c.category_name, sc.subcategory_name, mt.*, pt.*
                FROM product p
                JOIN section s ON p.section_id = s.section_id
                LEFT JOIN category c ON p.category_id = c.category_id
                LEFT JOIN subcategory sc ON p.subcategory_id = sc.subcategory_id
                LEFT JOIN material mt ON p.material_id = mt.material_id
                LEFT JOIN product_type pt ON p.product_type_id = pt.product_type_id
                WHERE (OR p.description COLLATE utf8mb4_general_ci LIKE ?
                    OR p.product_code COLLATE utf8mb4_general_ci LIKE ?
                    OR c.category_name COLLATE utf8mb4_general_ci LIKE ?
                    OR sc.subcategory_name COLLATE utf8mb4_general_ci LIKE ?
                    OR mt.material_name COLLATE utf8mb4_general_ci LIKE ?
                    OR pt.product_name COLLATE utf8mb4_general_ci LIKE ?)
                  AND p.section_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$search, $search, $search, $search, $search, $search, $sectionId]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = count($results);
        closeDB($pdo);
    }
}

?>