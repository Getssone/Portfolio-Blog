<?php

namespace App\Model;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Allows you to send emails via SMTP using the PHPMailer library
 */
class EmailModel
{
    private $mail;
    private $sessionModel;

    public function __construct(SessionModel $sessionModel)
    {
        $this->mail = new PHPMailer(true);
        $this->sessionModel = $sessionModel;
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'yourEmail@mail.com';
        $this->mail->Password = 'yourPW';
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Port = 587;
        $this->mail->SMTPDebug = 0;
        $this->mail->isHTML(true);
    }

    public function sendMe($name, $email, $object, $message)
    {
        try {
            $destinataire = "gaetan.solis@gmail.com";

            $this->mail->setFrom($email, $name);
            $this->mail->addAddress($destinataire);
            $this->mail->Subject = $object;
            $this->mail->Body = "Nom : $name\nEmail : $email\nMessage :\n$message";

            if ($this->mail->send()) {
                return true;
            } else {
                throw new Exception("Une erreur est survenue lors de l'envoi du message : " . $this->mail->ErrorInfo);
            }
        } catch (Exception $e) {
            throw new Exception("Une erreur est survenue noun'avons pas pue commencer à envoyé le mail : " . $e->getMessage());
        }
    }
}
