<?php
$host = 'localhost';
$dbname = 'beranstradingdb';
$username = 'root';
$password = '';

/*
// Production credentials example
$host = 'localhost';
$dbname = 'beranstr_beranstradingdb';
$username = 'beranstr_beransuser';
$password = 'beranspassword';
*/

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Avoid sending output here that could break headers
    error_log("Database connection failed: " . $e->getMessage());
    exit('Database connection failed.'); // Safe minimal message
}

// ✅ Do NOT close PHP tag
