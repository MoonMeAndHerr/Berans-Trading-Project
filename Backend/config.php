<?php
$host = 'localhost';
$dbname = 'beranstradingdb';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Use the integer value 2 if the constant isn't available
    $pdo->setAttribute(2, 2); // 2 = PDO::ATTR_ERRMODE, 2 = PDO::ERRMODE_EXCEPTION
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>