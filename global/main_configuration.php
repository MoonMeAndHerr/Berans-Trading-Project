<?php

#======================= Database Configuration Starts =======================#

function openDB() {
    $host = 'localhost';
    $dbname = 'beranstrading';
    $username = 'root';
    $password = '';
    $charset = 'utf8mb4';

    #$host = 'localhost';#
    #$dbname = 'beranstrading';#
    #$username = 'root';#
    #$password = '';#
    #$charset = 'utf8mb4';#

    #$host = 'localhost';#
    #$dbname = 'beranstr_beranstrading';#
    #$username = 'beranstr_beransuser';#
    #$password = 'beranspassword';#
    #$charset = 'utf8mb4';#

    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

    try {
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        return new PDO($dsn, $username, $password, $options);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

function closeDB(&$pdo) {
    $pdo = null;
}

#======================= Database Configuration Ends =======================#

#======================= Site Identity Configuration Starts =======================#

$pdo = openDB();

$sql = "SELECT *
FROM `site_config` 
WHERE `company_id` = 1";
$stmt = $pdo->query($sql);

while ($row = $stmt->fetch()) {

    $website_name = $row['company_name'];
    $website_logo = $row['company_logo'];
    $website_tagline = $row['company_tagline'];
    $bankname = $row['bank_name'];
    $bank_account_name = $row['bank_account_name'];
    $bank_account_number = $row['bank_account_number'];
    $address = $row['address'];
    $contact = $row['contact'];
    $logo_light = $row['logo_light'];
    $logo_dark = $row['logo_dark'];
    $favicon = $row['favicon'];

}

closeDB($pdo);

#======================= Site Identity Configuration Stars =======================#

#======================= Global Variable Configuration Starts =======================#

define('BASE_DIR', dirname(__DIR__)); # Define the base directory of the project
define('WEB_NAME', $website_name); # Define the website name
define('WEB_LOGO', $website_logo); # Define the website logo
define('WEB_TAGLINE', $website_tagline); # Define the website tagline
define('COMPANY_BANK_NAME', $bankname); # Define the company bank name
define('COMPANY_ACCOUNT_NAME', $bank_account_name); # Define the company account name
define('COMPANY_ACCOUNT_NUMBER', $bank_account_number); # Define the company account number
define('COMPANY_ADDRESS', $address); # Define the company address
define('COMPANY_CONTACT', $contact); # Define the company contact information

#======================= Global Variable Configuration Starts =======================#



?>
