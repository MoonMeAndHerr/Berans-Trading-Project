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

if (isset($_POST['submit_change_themecustomizer'])) {

    $stmt = $pdo->prepare("UPDATE staff SET web_layout = :web_layout, web_skin = :web_skin, web_width = :web_width, layout_pos = :layout_pos, topbar_color = :topbar_color, sidebar_size = :sidebar_size, sidebar_color = :sidebar_color, sidebar_view = :sidebar_view WHERE staff_id = :staff_id");
    $stmt->execute([
        ':web_layout' => $_POST['websitelayout'],
        ':web_skin' => $_POST['websiteskin'],
        ':web_width' => $_POST['layoutwidth'],
        ':layout_pos' => $_POST['layoutposition'],
        ':topbar_color' => $_POST['topbarcolour'],
        ':sidebar_size' => $_POST['sidebarsize'],
        ':sidebar_color' => $_POST['sidebarcolour'],
        ':sidebar_view' => $_POST['sidebarview'],
        ':staff_id' => $_POST['staff_id']
    ]);

    $_SESSION['web_layout']   = $_POST['websitelayout'];
    $_SESSION['web_skin']   = $_POST['websiteskin'];
    $_SESSION['web_width']       = $_POST['layoutwidth'];
    $_SESSION['layout_pos'] = $_POST['layoutposition'];
    $_SESSION['topbar_color']   = $_POST['topbarcolour'];
    $_SESSION['sidebar_size']   = $_POST['sidebarsize'];
    $_SESSION['sidebar_color']       = $_POST['sidebarcolour'];
    $_SESSION['sidebar_view'] = $_POST['sidebarview'];

    $_SESSION['success_message'] = "Theme Customizer settings updated successfully. Please Relog to Apply Changes.";
    header("Location: ../public/pages-profile-settings.php");
    exit();


}


?>