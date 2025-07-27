<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../global/main_configuration.php';
require_once __DIR__ . '/../private/auth_check.php';

$errors = [];
$success = '';

// Open DB connection
$pdo = openDB();

// Get staff_id from GET or POST (depending on request method)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staffId = isset($_POST['staff_id']) ? (int)$_POST['staff_id'] : null;
} else {
    $staffId = isset($_GET['staff_id']) ? (int)$_GET['staff_id'] : null;
}

if (!$staffId) {
    die('No staff selected.');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // If DELETE button is pressed
    if (isset($_POST['delete_staff']) && $_POST['delete_staff'] === '1') {
        try {
            $stmt = $pdo->prepare("DELETE FROM staff WHERE staff_id = :staff_id");
            $stmt->execute([':staff_id' => $staffId]);

            $_SESSION['successDelete'] = '✅ Staff deleted successfully!';
            header('Location: staff-add.php');
            exit;
        } catch (PDOException $e) {
            $_SESSION['errors'] = ['Error deleting staff: ' . $e->getMessage()];
            header('Location: ' . $_SERVER['PHP_SELF'] . '?staff_id=' . urlencode($staffId));
            exit;
        }
    }

    // Otherwise, it’s an UPDATE
    else {
        // Sanitize inputs
        $staff_name        = trim($_POST['staff_name'] ?? '');
        $staff_designation = trim($_POST['staff_designation'] ?? '');
        $staff_about       = trim($_POST['staff_about'] ?? '');
        $username          = trim($_POST['username'] ?? '');
        $email             = trim($_POST['email'] ?? '');
        $role              = $_POST['role'] ?? '';
        $company_id        = $_POST['company_id'] !== '' ? (int)$_POST['company_id'] : null;
        $password          = $_POST['password'] ?? '';
        $new_staff_number  = trim($_POST['staff_number'] ?? '');

        // Validate inputs
        if ($staff_name === '') $errors[] = 'Staff name is required.';
        if ($email === '')      $errors[] = 'Email is required.';
        if ($new_staff_number === '') $errors[] = 'Staff number is required.';
        if (!in_array($role, ['admin','manager','sales','warehouse','staff'], true)) {
            $errors[] = 'Invalid role selected.';
        }

        // Update if no errors
        if (empty($errors)) {
            try {
                $sql = "UPDATE staff SET 
                            staff_name = :staff_name,
                            staff_designation = :staff_designation,
                            staff_about = :staff_about,
                            username = :username,
                            email = :email,
                            role = :role,
                            company_id = :company_id,
                            staff_number = :new_staff_number";

                $params = [
                    ':staff_name'        => $staff_name,
                    ':staff_designation' => $staff_designation ?: null,
                    ':staff_about'       => $staff_about ?: null,
                    ':username'          => $username ?: null,
                    ':email'             => $email,
                    ':role'              => $role,
                    ':company_id'        => $company_id,
                    ':new_staff_number'  => $new_staff_number,
                    ':staff_id'          => $staffId,
                ];

                if ($password !== '') {
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $sql .= ", password_hash = :password_hash";
                    $params[':password_hash'] = $password_hash;
                }

                $sql .= " WHERE staff_id = :staff_id";

                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);

                $success = "✅ Staff updated successfully!";
            } catch (PDOException $e) {
                $errors[] = "Database error: " . $e->getMessage();
            }
        }
    }
}

// Fetch staff info for form prefill
try {
    $stmt = $pdo->prepare("SELECT * FROM staff WHERE staff_id = ?");
    $stmt->execute([$staffId]);
    $staff = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$staff) {
        die('Staff not found.');
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Fetch companies for dropdown
try {
    $stmt = $pdo->query("SELECT company_id, company_name FROM Company ORDER BY company_name");
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $companies = [];
}
?>
