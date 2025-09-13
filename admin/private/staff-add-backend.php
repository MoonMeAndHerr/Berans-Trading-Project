<?php
// Enable strict error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/staff_errors.log');

// Secure session handling
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 86400,
        'cookie_secure' => true,
        'cookie_httponly' => true,
        'cookie_samesite' => 'Strict'
    ]);
}

// Verify required files exist before including
$configPath = realpath(__DIR__ . '/../../global/main_configuration.php');
$authPath = realpath(__DIR__ . '/../private/auth_check.php');

if (!$configPath || !file_exists($configPath)) {
    die("Critical Error: Configuration file not found");
}
if (!$authPath || !file_exists($authPath)) {
    die("Critical Error: Authentication file not found");
}

require_once $configPath;
require_once $authPath;

// Initialize messages
$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? '';
unset($_SESSION['errors'], $_SESSION['success']);

// Database connection with error handling
try {
    $pdo = openDB();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("System temporarily unavailable. Please try again later.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $staff_name = trim($_POST['staff_name'] ?? '');
    $staff_designation = trim($_POST['staff_designation'] ?? '');
    $staff_about = trim($_POST['staff_about'] ?? '');
    $staff_number = trim($_POST['staff_number'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    $company_id = $_POST['company_id'] !== '' ? (int)$_POST['company_id'] : null;

    // Validation - Only basic required fields
    if (empty($staff_name)) $errors[] = 'Staff name is required.';
    if (empty($email)) $errors[] = 'Email is required.';
    if (empty($password)) $errors[] = 'Password is required.';
    if (empty($role)) $errors[] = 'Role is required.';
    
    // Very basic email format check
    if (!empty($email) && strpos($email, '@') === false) {
        $errors[] = 'Email must contain @ symbol';
    }

    // Remove phone number validation completely
    // Only check if it's required (if you want to make it optional, remove this check)
    if (empty($staff_number)) {
        $errors[] = 'Staff phone number is required.';
    }

    // Role validation remains
    if (!empty($role) && !in_array($role, ['admin', 'manager', 'sales', 'warehouse', 'staff'], true)) {
        $errors[] = 'Invalid role selected.';
    }

    if (empty($errors)) {
        try {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO staff 
                    (staff_name, staff_designation, staff_about, staff_number, username, email, password_hash, role, company_id)
                    VALUES 
                    (:staff_name, :staff_designation, :staff_about, :staff_number, :username, :email, :password_hash, :role, :company_id)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':staff_name'        => $staff_name,
                ':staff_designation' => !empty($staff_designation) ? $staff_designation : null,
                ':staff_about'       => !empty($staff_about) ? $staff_about : null,
                ':staff_number'     => $staff_number,
                ':username'          => !empty($username) ? $username : null,
                ':email'             => $email,
                ':password_hash'    => $password_hash,
                ':role'              => $role,
                ':company_id'       => $company_id
            ]);

            $_SESSION['success'] = '✅ New staff added successfully!';
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            error_log("Staff creation error: " . $e->getMessage());
            
            // More specific error messages
            if (strpos($e->getMessage(), 'username') !== false) {
                $_SESSION['errors'] = ['Username already exists. Please choose a different one.'];
            } else {
                $_SESSION['errors'] = ['Could not create staff member. Error: ' . $e->getMessage()];
            }
            
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}

// Fetch companies for dropdown
$companies = [];
try {
    $stmt = $pdo->query("SELECT company_id, company_name FROM site_config ORDER BY company_name");
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Company fetch error: " . $e->getMessage());
    $errors[] = 'Could not load company list.';
}

// Pagination setup
$limit = 6;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

try {
    $totalStmt = $pdo->query("SELECT COUNT(*) FROM staff");
    $totalRows = (int)$totalStmt->fetchColumn();
    $totalPages = (int)ceil($totalRows / $limit);

    $sql = "SELECT s.staff_id, s.staff_number, s.staff_name, s.staff_designation, 
                   s.username, s.email, s.role, c.company_name
            FROM staff s
            LEFT JOIN site_config c ON s.company_id = c.company_id
            ORDER BY s.staff_id ASC
            LIMIT :limit OFFSET :offset";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $staffList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Staff list fetch error: " . $e->getMessage());
    $errors[] = 'Could not load staff list.';
    $staffList = [];
    $totalPages = 1;
}

// Set content type header before any output
header('Content-Type: text/html; charset=UTF-8');
?>