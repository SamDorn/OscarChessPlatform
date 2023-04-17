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
        $mail->Subject = 'Testing PHPMailer';
        $mail->Body = '<h1><center>Welcome on  OscarChessPlatform.</center></h1><br><br>
        <h3>Click here to verify your email</h3>
        <center><a href="link">Confirm email</a></center>';
        if (!$mail->Send()) {
            header("Location: login?error=02");
        } else {
            header("Location: login?emailSent=1");
        }
    }
}
