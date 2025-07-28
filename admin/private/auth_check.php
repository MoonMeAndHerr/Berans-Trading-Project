<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define absolute paths for redirects (adjust these if your URL base differs)
$loginPage = '/beranstrading/admin/public/auth-signin-basic.php';
$dashboardPage = '/beranstrading/admin/public/dashboard-projects.php';

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

// Check if user is logged in
if (!isset($_SESSION['staff_id'])) {
    // Check for remember me cookie
    if (isset($_COOKIE['remember_token'])) {
        $cookie_data = json_decode($_COOKIE['remember_token'], true);
        $pdo = openDb();
        try {
            $stmt = $pdo->prepare("SELECT s.* FROM sessions se 
                                  JOIN staff s ON se.staff_id = s.staff_id
                                  WHERE se.staff_id = :staff_id AND se.token = :token AND se.expires_at > NOW()");
            $stmt->bindParam(':staff_id', $cookie_data['staff_id']);
            $stmt->bindParam(':token', $cookie_data['token']);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $_SESSION['staff_id'] = $user['staff_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['staff_name'] = $user['staff_name'];
                
                // Regenerate session ID for security
                session_regenerate_id(true);

                // Redirect to dashboard if this was a remember-me login on the login page
                if ($currentPage === basename($loginPage)) {
                    header("Location: $dashboardPage");
                    exit;
                }
            } else {
                // Invalid remember token
                setcookie('remember_token', '', time() - 3600, '/');
                header("Location: $loginPage");
                exit;
            }
        } catch (PDOException $e) {
            error_log("Remember me error: " . $e->getMessage());
            header("Location: $loginPage");
            exit;
        }
        ClosenDb($pdo);
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
