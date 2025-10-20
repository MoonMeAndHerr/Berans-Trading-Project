<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../global/main_configuration.php';
require_once __DIR__ . '/auth_check.php';

// Open database connection
$pdo = openDB();

// Fetch all suppliers
try {
    $stmt = $pdo->query("
        SELECT supplier_id, supplier_name, supplier_contact_person, phone, email, 
               address, city, region, postcode, country, notes, xero_relation, 
               created_at, updated_at
        FROM supplier 
        WHERE deleted_at IS NULL
        ORDER BY supplier_name ASC
    ");
    $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = "Error fetching suppliers: " . $e->getMessage();
    $suppliers = [];
}

// Close database connection
closeDB($pdo);
?>