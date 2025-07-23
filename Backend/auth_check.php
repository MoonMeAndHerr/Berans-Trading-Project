<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check for logged_out flag
if (isset($_SESSION['logged_out'])) {
    header("Location: auth-signin-basic.php");
    exit;
}

// Define important paths
$loginPage = 'auth-signin-basic.php';
$dashboardPage = 'forms-elements.php';

// Fix config path
$configPath = file_exists(__DIR__ . '/config.php') 
    ? __DIR__ . '/config.php' 
    : __DIR__ . '/../config.php';

if (!file_exists($configPath)) {
    die('Configuration file missing');
}
require_once $configPath;

// Skip auth check for login page
$currentPage = basename($_SERVER['PHP_SELF']);
if ($currentPage === $loginPage) {
    return; // Don't check auth on login page
}

// Check if user is logged in
if (!isset($_SESSION['staff_id'])) {
    // Check for remember me cookie
    if (isset($_COOKIE['remember_token'])) {
        $cookie_data = json_decode($_COOKIE['remember_token'], true);
        
        try {
            $stmt = $pdo->prepare("SELECT s.* FROM Sessions se 
                                  JOIN Staff s ON se.staff_id = s.staff_id
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
                
                // Redirect to dashboard if this was a remember-me login
                if ($currentPage === $loginPage) {
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
    } else {
        header("Location: $loginPage");
        exit;
    }
}

// Session verification (existing code remains the same)
?>