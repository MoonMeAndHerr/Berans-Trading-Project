<?php
require_once __DIR__ . '/../private/auth_check.php';
require_once __DIR__ . '/../../global/main_configuration.php';

header('Content-Type: application/json');

$price_id = intval($_GET['price_id'] ?? 0);

if ($price_id <= 0) {
    echo json_encode(['error' => 'Invalid price ID']);
    exit;
}

$pdo = openDB();

try {
    // Fetch main price data
    $stmt = $pdo->prepare("SELECT * FROM price WHERE price_id = ?");
    $stmt->execute([$price_id]);
    $priceData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$priceData) {
        echo json_encode(['error' => 'Price record not found']);
        exit;
    }
    
    // Fetch shipping totals if needed
    $shippingTotals = [];
    $stmt = $pdo->prepare("SELECT * FROM price_shipping_totals WHERE price_id = ?");
    $stmt->execute([$price_id]);
    $shippingTotals = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    
    // Merge all data
    $responseData = array_merge($priceData, [
        'shipping_totals' => $shippingTotals
    ]);
    
    echo json_encode($responseData);
    
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}

closeDB($pdo);
?>