<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define absolute paths for redirects (adjust these if your URL base differs)
$loginPage = '../public/auth-signin-basic.php';
$dashboardPage = '../public/dashboard-projects.php';

// Inactivity timeout (seconds)
$inactivityLimit = 600;

if (isset($_SESSION['LAST_ACTIVITY'])) {
    $elapsed = time() - $_SESSION['LAST_ACTIVITY'];
    if ($elapsed > $inactivityLimit) {
        session_unset();
        session_destroy();
        header("Location: $loginPage?timeout=1");
        exit;
    }
}
$_SESSION['LAST_ACTIVITY'] = time();

// Check for logged_out flag
if (isset($_SESSION['logged_out'])) {
    header("Location: $loginPage");
    exit;
}

// Fix config path
$configPath = __DIR__ . '/../../global/main_configuration.php';

if (!file_exists($configPath)) {
    die('Configuration file missing');
}
require_once $configPath;

// Skip auth check for login page itself
$currentPage = basename($_SERVER['PHP_SELF']);
if ($currentPage === basename($loginPage)) {
    return; // No further check for login page
}

// ====================== AUTHENTICATION CHECK ======================
// If no valid session, check remember me cookie
if(!isset($_SESSION['staff_id'])) {
    // Check for remember me cookie
    if (isset($_COOKIE['remember_me'])) {

            $token = $_COOKIE['remember_me'];

            $pdo = openDb();

            $stmt = $pdo->prepare("SELECT * FROM staff WHERE remember_token = ?");
            $stmt->execute([$token]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (strtotime($user['remember_expiry']) > time()) {

                $stmt = $pdo->prepare("SELECT * FROM staff WHERE staff_id = ?");
                $stmt->execute([$user['staff_id']]);
                $login = $stmt->fetch(PDO::FETCH_ASSOC);

                $_SESSION['staff_id'] = $login['staff_id'];
                $_SESSION['username'] = $login['username'];
                $_SESSION['role'] = $login['role'];
                $_SESSION['staff_name'] = $login['staff_name'];
                
            }

        } else {
            header("Location: $loginPage");
            exit;
        }
    } 


// --- Role-Based Access Control ---
$roleAccessMap = [
    'customer-add.php'          => ['admin', 'manager'],
    'customer-edit-update.php'  => ['admin', 'manager'],
    'staff-add.php'             => ['admin', 'manager'],
    'staff-edit-update.php'     => ['admin', 'manager'],
    
    // Add other page => roles entries here
];

// If page has role restrictions, enforce them
if (isset($roleAccessMap[$currentPage])) {
    $allowedRoles = $roleAccessMap[$currentPage];
    $userRole = $_SESSION['role'] ?? '';

    if (!in_array($userRole, $allowedRoles)) {
        // Redirect unauthorized users to dashboard instead of showing message
        header("Location: $dashboardPage");
        exit;
    }
}

// If page is not listed, allow access by default
?>
