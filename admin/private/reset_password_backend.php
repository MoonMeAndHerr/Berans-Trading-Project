<?php

session_start();

require_once __DIR__ . '/../../global/main_configuration.php';

$pdo = openDB();

if(isset($_POST['resetpassword'])) {

    $reset_email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT * FROM staff WHERE email = :email AND is_active = 1");
    $stmt->bindParam(':email', $reset_email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $rowcount = $stmt->rowCount();
    $code = rand(1000000,9999999);

    $name = $user['staff_name'];
    $subject = "Password Reset Request from Berans Trading";
    $message = "You requested a password reset. Your Reset Code is ". $code .". If you did not make this request, please ignore this email.";

    if($rowcount > 0) {

        // Email settings
        $to      = $reset_email; 
        $headers = "From: $website_name <$email>\r\n";
        $headers .= "Reply-To: $reset_email\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $body = "
            <h2>New Contact Message from Berans Trading</h2>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$reset_email}</p>
            <p><strong>Subject:</strong> {$subject}</p>
            <p><strong>Message:</strong><br>{$message}</p>
            ";

        mail($to, $subject, $body, $headers);

        $_SESSION['status'] = "Email sent! Please check your inbox for reset code.";
        $_SESSION['resetemail'] = $code;
        $_SESSION['email'] = $reset_email;
        header("Location: ../public/auth-pass-reset?resetcode=true");
        

    } else {
        $_SESSION['status'] = "Email not found in our records";
        header("Location: ../public/auth-pass-reset?resetpassword=true");
    }

}

if(isset($_POST['submitcode'])) {

    $code = $_POST['code'];

    if ($code == $_SESSION['resetemail']){

        $_SESSION['status'] = "Code Verified! You can now change your password.";
        header("Location: ../public/auth-pass-reset?newpassword=true");

    } else {

        $_SESSION['status'] = "Wrong Code Entered. Please Try Again!";
        header("Location: ../public/auth-pass-reset?resetcode=true");

    }

}

if(isset($_POST['changepassword'])) {

    $newpassword = $_POST['password'];
    $confirmpassword = $_POST['repassword'];

    if ($newpassword == $confirmpassword){

        $hashed_password = password_hash($newpassword, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE staff SET password_hash = :password WHERE email = :email");
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $_SESSION['email']);
        $stmt->execute();

        $_SESSION['status'] = "Password Changed Successfully! You can now login.";
        header("Location: ../public/auth-signin-basic.php");

    } else {

        $_SESSION['status'] = "Passwords do not match. Please Try Again!";
        header("Location: ../public/auth-pass-reset?newpassword=true");

    }

}


closeDB($pdo);




?>
