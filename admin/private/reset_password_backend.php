<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../global/main_configuration.php';

if(isset($_POST['resetpassword'])) {

    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT * FROM staff WHERE email = :email AND is_active = 1");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $code = rand(1000000,9999999);

    $name = $user['staff_name'];
    $subject = "Password Reset Request from Berans Trading";
    $message = "You requested a password reset. Your Reset Code is ". $code ."If you did not make this request, please ignore this email.";

    if($user) {

        // Email settings
        $to      = COMPANY_EMAIL; 
        $headers = "From: $name <$email>\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $body = "
            <h2>New Contact Message from Berans Trading</h2>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Subject:</strong> {$subject}</p>
            <p><strong>Message:</strong><br>{$message}</p>
            ";

        mail($to, $subject, $body, $headers);

        $_SESSION['status'] = "Email sent! Please check your inbox for reset code.";
        $_SESSION['resetemail'] = $code;
        header("Location: auth-pass-reset?resetcode=true");
        

    } else {
        $_SESSION['status'] = "Email not found in our records";
        header("Location: auth-pass-reset");
    }

if(isset($_POST['submitcode'])) {

    $code = $_POST['code'];

    if ($code == $_SESSION['resetemail']){

        header("Location: auth-pass-reset?newpassword=true");

    } else {

        $_SESSION['status'] = "Wrong Code Entered. Please Try Again!"
        header("Location: auth-pass-reset?resetcode=true");

    }

}






}
?>
