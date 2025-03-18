<?php

namespace Controller\App;

use DaguConnect\Core\BaseController;
use DaguConnect\PhpMailer\Email_Sender;

class ContactController extends BaseController
{
    public function sendContactMessage($userEmail,$message): void
    {
        $subject ='Report Concern';

        // Validation
        if (empty($userEmail) || !filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse(['error' => 'Invalid email address'],400);
            return;
        }
        if (empty($message)) {
           $this->jsonResponse(['error' => 'Message cannot be empty'],400);
            return;
        }

        // Send the email using the Email_Sender class
        Email_Sender::sendContactMessage($userEmail, $message);

       $this->jsonResponse(['message' => 'Contact message sent successfully']);
    }
}