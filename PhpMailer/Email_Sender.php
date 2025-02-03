<?php

namespace DaguConnect\PhpMailer;

use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../Services/Env.php';

class Email_Sender
{
    public static function sendVerificationEmail($email):void
    {

        //Path to the HTML template
        $templatePath = __DIR__ . '/../Views/Template_email.html';

        // Read the HTML template
        $emailTemplate = file_get_contents($templatePath);


        //Domain Ip verification
         $verificationUrl = "http://" . $_SERVER['HTTP_HOST'] ."/verify-email?email=" . urlencode($email);

        //body of the email
        $emailBody = str_replace('{{verification_url}}', $verificationUrl, $emailTemplate);

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $_ENV['MAIL_PORT'];


            //Recepients
            $mail->setFrom($_ENV['MAIL_USERNAME'], $_ENV['APP_NAME']);
            $mail->addAddress($email);

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'Verify Your Email';
            $mail->Body = $emailBody;

            $mail->send();

        }catch (\Exception $e) {
            error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }
}