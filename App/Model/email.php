<?php

namespace App\Model;

/** Attention essayer de connecter avec .env.example */

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'path/to/PHPMailer/src/PHPMailer.php';
require 'path/to/PHPMailer/src/Exception.php';
require 'path/to/PHPMailer/src/SMTP.php';

// Charger les dépendances de PHPMailer
require 'vendor/autoload.php';

// Logique d'envoi de l'email

class Email
{
    private $mail;
    protected $emailSent = false; // Par défaut, on suppose que l'email n'a pas été envoyé

    public function __construct()
    {
        // Instancier un nouvel objet PHPMailer
        $this->mail = new PHPMailer(true);
    }

    public function sendEmail($user_Mail, $password, $user_Name)
    {
        try {
            //Server settings
            $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $this->mail->isSMTP();                                            //Send using SMTP
            $this->mail->Host       = 'smtp.example.com';                     //Set the SMTP server to send through
            $this->mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $this->mail->Username   = 'user@example.com';                     //SMTP username
            $this->mail->Password   = 'secret';                               //SMTP password
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $this->mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            // Paramètres du serveur SMTP
            $this->mail->isSMTP();
            $this->mail->Host = 'smtp.example.com';
            $this->mail->SMTPAuth = true;
            $this->mail->Username = 'getssone@mailo.com';
            $this->mail->Password = 'your-password';
            $this->mail->SMTPSecure = 'tls';
            $this->mail->Port = 587;

            // Destinataire, sujet et contenu de l'email
            $this->mail->setFrom('getssone@mailo.com', 'mon email ');
            $this->mail->addAddress($user_Mail, 'email du bénéficiaire');
            $this->mail->Subject = 'Plus qu’un Blog.
            Une véritable boite à Outils . ';
            $this->mail->Body = "Voici pourquoi $user_Name, j'ai le plaisir de te donner l’accès au site via ce liens : http://localhost/P5/Code_p5/articles 
            
            Penser à garder vos ID et mot de passe : 
            id: $user_Mail
            mp: $password";

            //Attachments
            // $this->mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            $this->mail->addAttachment('public/assets/img/CarteVisite.svg', 'Logo.jpg');    //Optional name

            //Content
            $this->mail->isHTML(true);                                  //Set email format to HTML
            $this->mail->Subject = '<h1>Plus qu’un Blog.
            Une véritable boite à Outils .</h1>';
            $this->mail->Body    = "<p> Voici pourquoi $user_Name, j'ai le plaisir de te donner l’accès au site via ce liens :<p> <a href= http://localhost/P5/Code_p5/articles > Ta boite à Outils 😉</a>
            <pre>
            Penser à garder vos ID et mot de passe : 
            id: $user_Mail
            mp: $password
            </pre>";
            $this->mail->AltBody = "tu as un souci de visualisation";

            $this->mail->send();
            // L'email a été envoyé avec succès
            return true;
        } catch (Exception $e) {
            // Une erreur s'est produite lors de l'envoi de l'email
            // Vous pouvez afficher ou enregistrer l'erreur ici
            echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
            return false;
        }
    }
    public function message_Success()
    {
        // Si le formulaire est valide et que vous souhaitez afficher un message de succès
        $_SESSION['message'] = 'Le formulaire a été soumis avec succès !';
        // Rediriger vers la même page pour afficher le message
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit();
    }
}
