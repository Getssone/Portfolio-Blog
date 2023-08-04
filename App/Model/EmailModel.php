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
        $this->sessionModel = $sessionModel; //récupéré via le rooter

        // Paramètres SMTP
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'yourEmail@gmail.com';
        $this->mail->Password = 'yourMp';
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Port = 587;
        // Paramètres de débogage (facultatif)
        $this->mail->SMTPDebug = 0; // 0 pour désactiver les messages de débogage
        // Paramètres généraux du message
        $this->mail->isHTML(true); // Le message est au format HTML par défaut


    }

    public function sendMe($name, $email, $object, $message)
    {
        try {
            // Destinataire du message
            $destinataire = "gaetan.solis@gmail.com";

            // Configuration de l'e-mail avec PHPMailer
            $this->mail->setFrom($email, $name);
            $this->mail->addAddress($destinataire);
            $this->mail->Subject = $object;
            $this->mail->Body = "Nom : $name\nEmail : $email\nMessage :\n$message";

            // Envoi de l'e-mail
            if ($this->mail->send()) {
                return true;
            } else {
                // Lever une exception en cas d'erreur
                throw new Exception("Une erreur est survenue lors de l'envoi du message : " . $this->mail->ErrorInfo);
            }
        } catch (Exception $e) {
            throw new Exception("Une erreur est survenue noun'avons pas pue commencer à envoyé le mail : " . $e);
        }
    }
}
