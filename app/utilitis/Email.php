<?php

namespace App\utilitis;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{

    public static function sendEmail(string $email)
    {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->Mailer = "smtp";

        $mail->SMTPAuth   = TRUE;
        $mail->SMTPSecure = "tls";
        $mail->Port       = 587;
        $mail->Host       = "smtp.gmail.com";
        $mail->Username   = "";
        $mail->Password   = "";
        $mail->addAddress($email);
        $mail->SetFrom("", "OscarChessPlatform");
        $mail->isHTML(true);
        $mail->Subject = 'Verify your email';
        $mail->Body = '<center><img src="https://raw.githubusercontent.com/SamDorn/OscarChessPlatform/main/public/images/icon/icon.PNG" style="
        height: 50px;
        width: 50px;"><h1>Welcome to OscarChessPlatform</h1><br>
        <h3>Click here to verify your email</h3><br>
        <a href="link"style="
        border: 3px solid black;
        background-color: black;
        color: white;
        padding: 12px;
        font-family: arial;
        text-decoration: none;
        text-align:center; 
        font-size:1.2rem;">Confirm email</a><br><br>
        <p >By verifying your email you will be able to chat with your opponent<br>
        and make posts on the forum (when they will be available)</p></center>';
        if (!$mail->Send()) {
            header("Location: login?error=02");
        } else {
            header("Location: login?emailSent=1");
        }
    }
}
