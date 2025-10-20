<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../global/main_configuration.php';
require_once __DIR__ . '/auth_check.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$supplier_id = $_POST['supplier_id'] ?? null;

if (!$supplier_id) {
    echo json_encode(['success' => false, 'message' => 'No supplier ID provided']);
    exit;
}

try {
    $pdo = openDB();
    
    // First check if supplier exists
    $check = $pdo->prepare("SELECT supplier_id, supplier_name FROM supplier WHERE supplier_id = ?");
    $check->execute([$supplier_id]);
    $supplier = $check->fetch(PDO::FETCH_ASSOC);
    
    if (!$supplier) {
        echo json_encode(['success' => false, 'message' => 'Supplier not found']);
        exit;
    }

    // Check if supplier has associated prices
    $checkPrices = $pdo->prepare("SELECT COUNT(*) FROM price WHERE supplier_id = ?");
    $checkPrices->execute([$supplier_id]);
    $priceCount = $checkPrices->fetchColumn();
    
    if ($priceCount > 0) {
        echo json_encode([
            'success' => false, 
            'message' => 'Cannot delete supplier because they have associated prices in the system. Please remove all prices for this supplier first.'
        ]);
        exit;
    }
    
    // If no prices exist, perform delete
    $stmt = $pdo->prepare("DELETE FROM supplier WHERE supplier_id = ?");
    $stmt->execute([$supplier_id]);
    
    if ($stmt->rowCount() > 0) {
        $_SESSION['success_delete'] = "🗑️ Supplier deleted successfully!";
        echo json_encode([
            'success' => true, 
            'message' => 'Supplier deleted successfully',
            'supplier_name' => $supplier['supplier_name']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete supplier']);
    }
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} finally {
    if (isset($pdo)) {
        closeDB($pdo);
    }
}
?>