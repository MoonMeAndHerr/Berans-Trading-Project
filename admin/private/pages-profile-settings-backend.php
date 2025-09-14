<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../global/main_configuration.php';
require_once __DIR__ . '/../private/auth_check.php';

$pdo = openDB();

if (isset($_POST['submit_change_details'])) {
    try {

        $uploadDir = '../../media/';

        $stmt = $pdo->prepare("SELECT * FROM staff WHERE username = :username AND staff_id != :staff_id");
        $stmt->execute([
            ':username' => $_POST['staff_username'],
            ':staff_id' => $_POST['staff_id']
        ]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['error_message'] = "Username already taken. Please choose another.";
            header("Location: ../public/pages-profile-settings.php");
            exit();
        }

        $stmt = $pdo->prepare("SELECT * FROM staff WHERE email = :email AND staff_id != :staff_id");
        $stmt->execute([
            ':email' => $_POST['staff_email'],
            ':staff_id' => $_POST['staff_id']
        ]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['error_message'] = "Email already in use. Please choose another.";
            header("Location: ../public/pages-profile-settings.php");
            exit();
        }

        if (!empty($_FILES['image']['name'])) {

            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $pic_name = 'profile_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $pic_name);

            $stmt = $pdo->prepare("UPDATE staff SET staff_profile_picture = :profile_pic WHERE staff_id = :staff_id");
            $stmt->execute([
                ':profile_pic' => $pic_name,
                ':staff_id' => $_POST['staff_id']
            ]);

        }

        $stmt = $pdo->prepare("UPDATE staff SET username = :username, email = :email, staff_about = :about, staff_name = :staffname WHERE staff_id = :staff_id");
        $stmt->execute([
            ':username' => $_POST['staff_username'],
            ':email' => $_POST['staff_email'],
            ':about' => $_POST['staff_description'],
            ':staffname' => $_POST['staff_name'],
            ':staff_id' => $_POST['staff_id']
        ]);
        $_SESSION['success_message'] = "Profile details updated successfully.";
        header("Location: ../public/pages-profile-settings.php");
        exit();
    } catch (PDOException $e) {
        // Handle error appropriately
        die("Database error: " . $e->getMessage());
    } 
}

if (isset($_POST['submit_change_password'])) {

    try {

        if(password_verify($_POST['staff_old_pass'], $_POST['current_hashed_pass'])) {

            if ($_POST['staff_new_pass'] !== $_POST['staff_retype_pass']) {

                $_SESSION['error_message'] = "New passwords do not match.";
                header("Location: ../public/pages-profile-settings.php");
                exit();

            } else {

            $new_hashed_password = password_hash($_POST['staff_new_pass'], PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("UPDATE staff SET password_hash = :password WHERE staff_id = :staff_id");
            $stmt->execute([
                ':password' => $new_hashed_password,
                ':staff_id' => $_POST['staff_id']
            ]);
            $_SESSION['success_message'] = "Password changed successfully.";
            header("Location: ../public/pages-profile-settings.php");
            exit();

            }

        } else {

            $_SESSION['error_message'] = "Old password is incorrect.";
            header("Location: ../public/pages-profile-settings.php");
            exit();

        }

    } catch (PDOException $e) {
        // Handle error appropriately
        die("Database error: " . $e->getMessage());
    }
}


?>