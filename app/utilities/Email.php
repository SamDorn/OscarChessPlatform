<?php

namespace App\utilities;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{

    public static function sendEmail(string $email, string $type, ?string $verificationCode): string
    {
        if ($type === "google") {
            $body = "<center><img src='https://raw.githubusercontent.com/SamDorn/OscarChessPlatform/main/public/images/icon/icon.PNG' style='
            height: 50px;
            width: 50px;'><h1>Welcome to OscarChessPlatform</h1><br>
            <center><h4>Here you can play with your friends, chat with your opponents and even <br>
            post on the forum (when it will be available)</h4></center>
            ";
            $subject = "Welcome";
        } else {
            $body = "<center><img src='https://raw.githubusercontent.com/SamDorn/OscarChessPlatform/main/public/images/icon/icon.PNG' style='
            height: 50px;
            width: 50px;'><h1>Welcome to OscarChessPlatform</h1><br>
            <h3>Click here to verify your email</h3><br>
            <a href='http://192.168.1.15/verifyEmail?code=$verificationCode'style='
            border: 3px solid black;
            background-color: black;
            color: white;
            padding: 12px;
            font-family: arial;
            text-decoration: none;
            text-align:center; 
            font-size:1.2rem;'>Confirm email</a><br><br><br>
            <p>By verifying your email you will be able to chat with your opponent<br>
            and make posts on the forum (when they will be available)</p></center>";
            $subject = 'Verify your email';
        }
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->Mailer = "smtp";

        $mail->SMTPAuth   = TRUE;
        $mail->SMTPSecure = $_ENV['SMTP_SECURE'];
        $mail->Port       = $_ENV['PORT_TLS'];
        $mail->Host       = $_ENV['SMTP_HOST'];
        $mail->Username   = $_ENV['SMTP_USERNAME'];
        $mail->Password   = $_ENV['SMTP_PASSWORD'];
        $mail->addAddress($email);
        $mail->SetFrom($_ENV['SMTP_USERNAME'], "OscarChessPlatform");
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        if (!$mail->Send()) {
            return "problem with sending the email";
        }
        return "email sent";
    }
}
