<?php

	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	
	require_once '../../global/main_configuration.php';


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize form data
        $name    = htmlspecialchars(trim($_POST["name"]));
        $email   = htmlspecialchars(trim($_POST["email"]));
        $subject = htmlspecialchars(trim($_POST["subject"]));
        $message = htmlspecialchars(trim($_POST["message"]));

        // Validate
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Invalid email format");
        }

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

        // Send
        if (mail($to, $subject, $body, $headers)) {
            $_SESSION['status'] = "Success";
            header("Location: ../public/contact-us");
        } else {
            $_SESSION['status'] = "Failed";
            header("Location: ../public/contact-us");
        }
    }


?>