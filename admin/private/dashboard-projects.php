<?php

require_once __DIR__ . '/../../global/main_configuration.php';

$db = openDB();

function getTotalStaff(){

    global $db;
    $stmt = $db->query("SELECT COUNT(*) as total FROM staff");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];

}

function getTotalSupplier(){

    global $db;
    $stmt = $db->query("SELECT COUNT(*) as total FROM supplier");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];

}

function getTotalCustomer(){

    global $db;
    $stmt = $db->query("SELECT COUNT(*) as total FROM customer");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];

}

function getTotalProduct(){

    global $db;
    $stmt = $db->query("SELECT COUNT(*) as total FROM product WHERE deleted_at IS NULL");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];

}

function getTotalRevenue(){

    global $db;
    $stmt = $db->query("SELECT SUM(amount_paid) as total FROM payment_history");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];

}

function getTotalInvoice(){

    global $db;
    $stmt = $db->query("SELECT SUM(total_amount) as total FROM invoice");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];

}

$months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

// Current year
$currentYear = date("Y");

// Issued query
$sqlIssued = "SELECT DATE_FORMAT(created_at, '%m') AS month_num, SUM(total_amount) AS total
              FROM invoice
              WHERE YEAR(created_at) = :year
              GROUP BY DATE_FORMAT(created_at, '%m')";
$stmt = $db->prepare($sqlIssued);
$stmt->execute(['year' => $currentYear]);
$issued = array_fill(1, 12, 0);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $issued[(int)$row['month_num']] = (float)$row['total'];
}

// Received query
$sqlReceived = "SELECT DATE_FORMAT(payment_date, '%m') AS month_num, SUM(amount_paid) AS total
                FROM payment_history
                WHERE YEAR(payment_date) = :year
                GROUP BY DATE_FORMAT(payment_date, '%m')";
$stmt2 = $db->prepare($sqlReceived);
$stmt2->execute(['year' => $currentYear]);
$received = array_fill(1, 12, 0);
while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $received[(int)$row['month_num']] = (float)$row['total'];
}

$issuedData   = array_values($issued);
$receivedData = array_values($received);




?>
