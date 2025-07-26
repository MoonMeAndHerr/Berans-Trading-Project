<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../global/main_configuration.php';
require_once __DIR__ . '/../private/auth_check.php';

$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? '';

// Clear flash messages immediately so they don't persist on refresh
unset($_SESSION['errors'], $_SESSION['success']);

// Open DB connection once
$pdo = openDB();

// Handle POST submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and get inputs
    $staff_name        = trim($_POST['staff_name'] ?? '');
    $staff_designation = trim($_POST['staff_designation'] ?? '');
    $staff_about       = trim($_POST['staff_about'] ?? '');
    $staff_number      = trim($_POST['staff_number'] ?? '');
    $username          = trim($_POST['username'] ?? '');
    $email             = trim($_POST['email'] ?? '');
    $password          = $_POST['password'] ?? '';
    $role              = $_POST['role'] ?? '';
    $company_id        = $_POST['company_id'] !== '' ? (int)$_POST['company_id'] : null;

    // Basic validations
    if ($staff_name === '')       $errors[] = 'Staff name is required.';
    if ($email === '')            $errors[] = 'Email is required.';
    if ($password === '')         $errors[] = 'Password is required.';
    if (!in_array($role, ['admin', 'manager', 'sales', 'warehouse'], true)) {
        $errors[] = 'Invalid role selected.';
    }

    if (empty($errors)) {
        try {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO Staff 
                (staff_name, staff_designation, staff_about, staff_number, username, email, password_hash, role, company_id)
                VALUES 
                (:staff_name, :staff_designation, :staff_about, :staff_number, :username, :email, :password_hash, :role, :company_id)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':staff_name'        => $staff_name,
                ':staff_designation' => $staff_designation ?: null,
                ':staff_about'       => $staff_about ?: null,
                ':staff_number'      => $staff_number ?: null,
                ':username'          => $username ?: null,
                ':email'             => $email,
                ':password_hash'     => $password_hash,
                ':role'              => $role,
                ':company_id'        => $company_id
            ]);

            $_SESSION['success'] = '✅ New staff added successfully!';
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            $_SESSION['errors'] = $e->getCode() == 23000
                ? ['Email, username, or staff number already exists.']
                : ['Database error: ' . $e->getMessage()];
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }
    } else {
        $_SESSION['errors'] = $errors;
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Fetch companies for dropdown
try {
    $stmt = $pdo->query("SELECT company_id, company_name FROM Company ORDER BY company_name");
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $companies = [];
    // Optionally log error here
}

// Generate next staff_number with 'EMP' prefix
try {
    $stmt = $pdo->query("SELECT staff_number FROM Staff WHERE staff_number LIKE 'EMP%' ORDER BY staff_id DESC LIMIT 1");
    $lastEmp = $stmt->fetchColumn();

    $num = $lastEmp ? (int)substr($lastEmp, 3) + 1 : 1;
    $staff_number = 'EMP' . str_pad($num, 3, '0', STR_PAD_LEFT);
} catch (PDOException $e) {
    $errors[] = 'Failed to generate employee number: ' . $e->getMessage();
}

// Pagination setup
$limit = 6;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

// Get total staff count
$totalStmt = $pdo->query("SELECT COUNT(*) FROM Staff");
$totalRows = (int)$totalStmt->fetchColumn();
$totalPages = (int)ceil($totalRows / $limit);

// Fetch limited staff list with company names
$sql = "SELECT s.staff_number, s.staff_name, s.staff_designation, s.username, s.email, s.role, c.company_name
        FROM Staff s
        LEFT JOIN Company c ON s.company_id = c.company_id
        ORDER BY s.staff_id ASC
        LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$staffList = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>