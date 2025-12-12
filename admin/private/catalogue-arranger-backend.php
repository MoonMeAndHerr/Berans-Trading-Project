<?php

    require_once __DIR__ . '/../../global/main_configuration.php';
    use League\OAuth2\Client\Provider\GenericProvider;
    use GuzzleHttp\Client;

    $pdo = openDB();
    $stmtSection = $pdo->prepare("SELECT * FROM section");
    $stmtSection->execute();
    $sections = $stmtSection->fetchAll(PDO::FETCH_ASSOC);

    $stmtCategory = $pdo->prepare("SELECT * FROM category");
    $stmtCategory->execute();
    $categories = $stmtCategory->fetchAll(PDO::FETCH_ASSOC);

    $stmtSubcategory = $pdo->prepare("SELECT * FROM subcategory");
    $stmtSubcategory->execute();
    $subcategories = $stmtSubcategory->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("
    SELECT 
        fc.folder_id,
        fc.folder_name,
        -- Section info
        s.section_id,
        s.section_name,
        -- Category info (might be NULL)
        c.category_id,
        c.category_name,
        -- Subcategory info (might be NULL)
        sc.subcategory_id,
        sc.subcategory_name
    FROM folder_catalogue fc
    JOIN section s ON fc.section_id = s.section_id
    LEFT JOIN category c ON fc.category_id = c.category_id
    LEFT JOIN subcategory sc ON fc.subcategory_id = sc.subcategory_id
    ORDER BY fc.folder_id DESC
    ");

    $stmt->execute();
    $folders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'submit') {
    
    // 1. Retrieve Inputs from POST
    $folderName = $_POST['foldername'] ?? 'New Folder';
    $filterParam = $_POST['filter'] ?? '';
    $productIds = $_POST['product'] ?? []; 
    
    // Convert array to string
    $listItems = !empty($productIds) ? implode(',', $productIds) : '';

    $sectionId = null;
    $categoryId = null;
    $subcategoryId = null;

    if (strpos($filterParam, '-') !== false) {
        list($type, $id) = explode('-', $filterParam, 2);
        $id = (int)$id;

        try {
            switch ($type) {
                case 'sec':
                    $sectionId = $id;
                    break;
                case 'cat':
                    $categoryId = $id;
                    // Fetch parent section_id
                    $stmt = $pdo->prepare("SELECT section_id FROM category WHERE category_id = ?");
                    $stmt->execute([$id]);
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($row) $sectionId = $row['section_id'];
                    break;
                case 'subcat':
                    $subcategoryId = $id;
                    // Fetch parent category_id and section_id
                    $stmt = $pdo->prepare("
                        SELECT s.category_id, c.section_id 
                        FROM subcategory s 
                        JOIN category c ON s.category_id = c.category_id 
                        WHERE s.subcategory_id = ?
                    ");
                    $stmt->execute([$id]);
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($row) {
                        $categoryId = $row['category_id'];
                        $sectionId = $row['section_id'];
                    }
                    break;
            }

            if ($sectionId === null) {
                $errorMessage = "Error: Could not determine valid Section ID.";
            } else {
                // Insert
                $sql = "INSERT INTO folder_catalogue 
                        (folder_name, section_id, category_id, subcategory_id, listItems) 
                        VALUES (?, ?, ?, ?, ?)";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$folderName, $sectionId, $categoryId, $subcategoryId, $listItems]);

                echo "<script>
                alert('Folder created successfully!'); 
                window.location.href = 'catalogue-arranger.php'; 
                </script>";
            }

        } catch (PDOException $e) {
            $errorMessage = "Database Error: " . $e->getMessage();
        }
    } else {
        $errorMessage = "Invalid filter format.";
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $folderId = (int)$_GET['id'];

    // 1. The Query
    $sql = "DELETE FROM folder_catalogue WHERE folder_id = ?";
    
    // 2. Prepare and Execute
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$folderId]);

    // 3. Success Message & Redirect
    echo '<script>
            alert("Folder deleted successfully!");
            window.location.href = "../public/catalogue-arranger.php"; 
          </script>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
    
    // 1. Get Inputs
    $folderId = isset($_POST['folder_id']) ? (int)$_POST['folder_id'] : 0;
    $filterParam = isset($_POST['filter']) ? $_POST['filter'] : '';
    $productIds = isset($_POST['product']) ? $_POST['product'] : [];
    
    // 2. Prepare Data
    // Convert array of product IDs back to string "1,2,3"
    $listItems = !empty($productIds) ? implode(',', $productIds) : '';
    
    // Initialize IDs
    $sectionId = null;
    $categoryId = null;
    $subcategoryId = null;

    // 3. Parse Filter to determine new Section/Category/Subcategory IDs
    if ($folderId > 0 && strpos($filterParam, '-') !== false) {
        list($type, $id) = explode('-', $filterParam, 2);
        $id = (int)$id;

        try {
            switch ($type) {
                case 'sec':
                    $sectionId = $id;
                    break;

                case 'cat':
                    $categoryId = $id;
                    // We must find the parent section_id
                    $stmt = $pdo->prepare("SELECT section_id FROM category WHERE category_id = ?");
                    $stmt->execute([$id]);
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($row) $sectionId = $row['section_id'];
                    break;

                case 'subcat':
                    $subcategoryId = $id;
                    // We must find parent category_id and section_id
                    $stmt = $pdo->prepare("
                        SELECT s.category_id, c.section_id 
                        FROM subcategory s 
                        JOIN category c ON s.category_id = c.category_id 
                        WHERE s.subcategory_id = ?
                    ");
                    $stmt->execute([$id]);
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($row) {
                        $categoryId = $row['category_id'];
                        $sectionId = $row['section_id'];
                    }
                    break;
            }

            // 4. Validate
            if ($sectionId === null) {
                die("Error: Invalid Filter Selection. Could not determine Section ID.");
            }

            // 5. Update Query
            // Note: We update section/category/subcategory IDs in case the user changed the filter
            $sql = "UPDATE folder_catalogue 
                    SET 
                        section_id = ?,
                        category_id = ?,
                        subcategory_id = ?,
                        listItems = ?
                    WHERE folder_id = ?";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $sectionId,
                $categoryId,
                $subcategoryId,
                $listItems,
                $folderId
            ]);

            // 6. Success & Redirect
            echo '<script>
                    alert("Catalogue updated successfully!");
                    window.location.href = "catalogue-arranger.php";
                  </script>';
            exit;

        } catch (PDOException $e) {
            die("Database Error: " . $e->getMessage());
        }
    } else {
        die("Invalid request or missing Filter ID.");
    }
}
?>
