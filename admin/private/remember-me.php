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

// Fix config path
$configPath = __DIR__ . '/../../global/main_configuration.php';

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

                header("Location: $dashboardPage");
                exit;

            }

        } elseif (isset($_SESSION['staff_id'])) {
            header("Location: $dashboardPage");
            exit;
        }


?>
