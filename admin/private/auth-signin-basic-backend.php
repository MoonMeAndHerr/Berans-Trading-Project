<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
<<<<<<< HEAD

require_once __DIR__ . '/../../global/main_configuration.php';
require_once __DIR__ . '/../private/logger.php';

$pdo = openDB(); 

// Initialize variables
$error = '';
$username = '';
$loginData = [
    'username'   => $_POST['username'] ?? '',
    'ip'         => $_SERVER['REMOTE_ADDR'],
=======
require_once __DIR__ . '/../private/config.php';
require_once __DIR__ . '/../private/logger.php';

// Initialize variables
$error = '';
$loginData = [
    'username' => $_POST['username'] ?? '',
    'ip' => $_SERVER['REMOTE_ADDR'],
>>>>>>> 4a4c08832b13d9a9224dab3d45af38c452daa710
    'user_agent' => $_SERVER['HTTP_USER_AGENT']
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
<<<<<<< HEAD
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

=======
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
>>>>>>> 4a4c08832b13d9a9224dab3d45af38c452daa710
    logDebug("Login attempt started", $loginData);

    // Validate inputs
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
        logDebug("Login validation failed - empty fields", ['username' => $username]);
    } else {
        try {
            logDebug("Attempting database query for user", ['username' => $username]);
<<<<<<< HEAD

=======
            
>>>>>>> 4a4c08832b13d9a9224dab3d45af38c452daa710
            $stmt = $pdo->prepare("SELECT * FROM staff WHERE username = :username AND is_active = 1");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                logDebug("User found in database", ['staff_id' => $user['staff_id'], 'username' => $user['username']]);
<<<<<<< HEAD

                if (password_verify($password, $user['password_hash'])) {
                    logDebug("Password verification successful", ['staff_id' => $user['staff_id']]);

                    // ✅ Create session variables
                    $_SESSION['staff_id']   = $user['staff_id'];
                    $_SESSION['username']   = $user['username'];
                    $_SESSION['role']       = $user['role'];
=======
                
                if (password_verify($password, $user['password_hash'])) {
                    logDebug("Password verification successful", ['staff_id' => $user['staff_id']]);
                    
                    // Create session
                    $_SESSION['staff_id'] = $user['staff_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
>>>>>>> 4a4c08832b13d9a9224dab3d45af38c452daa710
                    $_SESSION['staff_name'] = $user['staff_name'];

                    logDebug("Session created", [
                        'staff_id' => $user['staff_id'],
                        'username' => $user['username'],
<<<<<<< HEAD
                        'role'     => $user['role']
                    ]);

                    // ✅ Create session token in database
                    $token      = bin2hex(random_bytes(16));
                    $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

=======
                        'role' => $user['role']
                    ]);

                    // Create session token in database
                    $token = bin2hex(random_bytes(16));
                    $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
                    
>>>>>>> 4a4c08832b13d9a9224dab3d45af38c452daa710
                    $stmt = $pdo->prepare("INSERT INTO sessions (staff_id, token, expires_at) VALUES (:staff_id, :token, :expires_at)");
                    $stmt->bindParam(':staff_id', $user['staff_id']);
                    $stmt->bindParam(':token', $token);
                    $stmt->bindParam(':expires_at', $expires_at);
                    $stmt->execute();

                    logDebug("Session token created in database", ['staff_id' => $user['staff_id'], 'token' => $token]);

<<<<<<< HEAD
                    // ✅ Update last login
=======
                    // Update last login
>>>>>>> 4a4c08832b13d9a9224dab3d45af38c452daa710
                    $stmt = $pdo->prepare("UPDATE staff SET last_login = NOW() WHERE staff_id = :staff_id");
                    $stmt->bindParam(':staff_id', $user['staff_id']);
                    $stmt->execute();

                    logDebug("Last login updated", ['staff_id' => $user['staff_id']]);

<<<<<<< HEAD
                    // ✅ Redirect to dashboard
                    header('Location: ../public/dashboard-projects.php');
                    exit;

=======
                    // Redirect to dashboard
                    header('Location: ../public/dashboard-projects.php');  // relative path example
                    exit;
>>>>>>> 4a4c08832b13d9a9224dab3d45af38c452daa710
                } else {
                    $error = 'Invalid password';
                    logDebug("Password verification failed", ['username' => $username]);
                }
            } else {
                $error = 'User not found or inactive';
                logDebug("User not found or inactive", ['username' => $username]);
            }
<<<<<<< HEAD

        } catch (PDOException $e) {
            $error = $e->getMessage();
            logDebug("Database error during login", [
                'error'    => $error,
                'username' => $username
            ]);
        }
=======
        } catch (PDOException $e) {
                $error = $e->getMessage();  // Show actual error message
                logDebug("Database error during login", [
                    'error' => $error,
                    'username' => $username
    ]);
}

>>>>>>> 4a4c08832b13d9a9224dab3d45af38c452daa710
    }
}

// If we get here, login failed
logDebug("Login attempt failed", [
    'username' => $username,
<<<<<<< HEAD
    'error'    => $error,
    'ip'       => $_SERVER['REMOTE_ADDR']
]);

// Optional: you can handle showing $error on your frontend page
?>
=======
    'error' => $error,
    'ip' => $_SERVER['REMOTE_ADDR']
]);
?>
>>>>>>> 4a4c08832b13d9a9224dab3d45af38c452daa710
