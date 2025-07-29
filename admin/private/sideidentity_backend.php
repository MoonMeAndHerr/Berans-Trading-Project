<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../global/main_configuration.php';
require_once __DIR__ . '/../private/auth_check.php';

$pdo = openDB();

$errors = [];
$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $company_name = trim($_POST['company_name'] ?? '');
    $company_tagline = trim($_POST['company_tagline'] ?? '');
    $bank_name = trim($_POST['bank_name'] ?? '');
    $bank_account_name = trim($_POST['bank_account_name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // Basic validation
    if (empty($company_name)) $errors[] = "Company name is required.";
    if (empty($company_tagline)) $errors[] = "Company tagline is required.";
    if (empty($bank_name)) $errors[] = "Bank name is required.";
    if (empty($bank_account_name)) $errors[] = "Bank account name is required.";
    if (empty($address)) $errors[] = "Company address is required.";
    if (empty($contact)) $errors[] = "Contact number is required.";
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }


    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                UPDATE company SET 
                    company_name = :company_name,
                    company_tagline = :company_tagline,
                    bank_name = :bank_name,
                    bank_account_name = :bank_account_name,
                    address = :address,
                    contact = :contact,
                    email = :email
                WHERE company_id = 1
                LIMIT 1
            ");

            $stmt->execute([
                ':company_name' => $company_name,
                ':company_tagline' => $company_tagline,
                ':bank_name' => $bank_name,
                ':bank_account_name' => $bank_account_name,
                ':address' => $address,
                ':contact' => $contact,
                ':email' => $email,
            ]);

            $_SESSION['custom_sa_success'] = true;
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}

closeDB($pdo);
?>
