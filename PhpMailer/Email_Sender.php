<?php

namespace DaguConnect\PhpMailer;

use DaguConnect\Core\BaseController;
use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../Services/Env.php';

class Email_Sender extends BaseController
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

    public static function sendResetPasswordToken($email, $token): void
    {
        // Path to the HTML template
        $templatePath = __DIR__ . '/../Views/ResetPassword_email.html';

        // Read the HTML template
        $emailTemplate = file_get_contents($templatePath);

        // Replace the placeholder with the token
        $emailBody = str_replace('{{reset_token}}', $token, $emailTemplate);

        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $_ENV['MAIL_PORT'];

            // Recipients
            $mail->setFrom($_ENV['MAIL_USERNAME'], $_ENV['APP_NAME']);
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Token';
            $mail->Body = $emailBody;

            $mail->send();
        } catch (\Exception $e) {
            error_log("Reset password email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }

    // New method for users to contact your email
    public static function sendContactMessage($userEmail, $message): void
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings (same as your existing config)
            $mail->isSMTP();
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $_ENV['MAIL_PORT'];

            $reportConcernPath = __DIR__ . '/../Views/Report_Concern.html';

            $reportTemplate =file_get_contents($reportConcernPath);

            // Replace the placeholder with the token
            // Replace all placeholders
            $emailBody = str_replace(
                ['{{userEmail}}', '{{message}}', '{{appName}}', '{{year}}'],
                [$userEmail, nl2br(htmlspecialchars($message)), $_ENV['APP_NAME'], date('Y')],
                $reportTemplate
            );

            // Gmail SMTP will force the "From" address to be the authenticated account (daguconnect.team@gmail.com)
            $mail->setFrom($_ENV['MAIL_USERNAME'], $_ENV['APP_NAME']);
            // Set the user's email as the Reply-To address so you can reply to them directly
            $mail->addReplyTo($userEmail);
            // Send the email to your address
            $mail->addAddress($_ENV['MAIL_USERNAME']);

            // Content
            $mail->isHTML(true);
            $mail->Subject = "Report Concern";
            // Simple HTML body including the user's message and their email
            $mail->Body = $emailBody;
            $mail->AltBody = "From: {$userEmail}\nMessage:\n{$message}";

            $mail->send();
        } catch (\Exception $e) {
            error_log("Contact email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }

}