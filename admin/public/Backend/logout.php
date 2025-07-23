<?php
// logout.php - Handles the actual logout processing

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/logger.php';

// Log logout attempt
logDebug("Logout initiated", [
    'staff_id' => $_SESSION['staff_id'] ?? null,
    'username' => $_SESSION['username'] ?? null,
    'ip' => $_SERVER['REMOTE_ADDR'],
    'user_agent' => $_SERVER['HTTP_USER_AGENT']
]);

// Store session data for logging before destroying
$sessionData = $_SESSION;

// ====================== SESSION DESTRUCTION ======================
// 1. Set logout flag first
$_SESSION['logged_out'] = true;

// 2. Clear all session data
$_SESSION = [];

// 3. Invalidate session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// 4. Destroy the session
session_destroy();

// ====================== REMEMBER TOKEN CLEANUP ======================
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/', '', true, true);
    logDebug("Remember token cookie cleared", [
        'staff_id' => $sessionData['staff_id'] ?? null
    ]);
}

// ====================== DATABASE SESSION CLEANUP ======================
if (isset($pdo) && isset($sessionData['staff_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM Sessions WHERE staff_id = :staff_id");
        $stmt->bindParam(':staff_id', $sessionData['staff_id']);
        $stmt->execute();
        
        $rowsAffected = $stmt->rowCount();
        logDebug("Database session cleanup", [
            'staff_id' => $sessionData['staff_id'],
            'sessions_deleted' => $rowsAffected
        ]);
    } catch (PDOException $e) {
        $errorMsg = "Logout error: " . $e->getMessage();
        error_log($errorMsg);
        logDebug($errorMsg, [
            'staff_id' => $sessionData['staff_id'] ?? null
        ]);
    }
}

// Log successful logout
logDebug("Logout completed successfully", [
    'staff_id' => $sessionData['staff_id'] ?? null,
    'username' => $sessionData['username'] ?? null
]);

// Redirect to the logout confirmation page
header('Location: ../auth-logout-basic.php');
exit;
?>