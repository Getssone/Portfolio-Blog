<?php

namespace App\Controller;

use Exception;
use Twig\Environment;
use App\Model\EmailModel;
use App\Model\SessionModel;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;

class EmailController
{
    private $sessionModel;
    private $emailModel;

    public function __construct(SessionModel $sessionModel)
    {

        $this->sessionModel = $sessionModel; //récupéré via le rooter
        $this->emailModel = new EmailModel($sessionModel);
    }

    public function sendMailViaContact()
    {
        try {
            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['object']) && isset($_POST['message'])) {
                    $name = $_POST['name'];
                    $email = $_POST['email'];
                    $object = $_POST['object'];
                    $message = $_POST['message'];

                    // Appel au modèle pour envoyer l'e-mail
                    $this->emailModel->sendMe($name, $email, $object, $message);
                    $this->sessionModel->set('message', 'Votre message a été envoyé avec succès.');
                    header('Location: contact');
                }
            }
        } catch (Exception $e) {
            $this->sessionModel->set('message', "Il y a une erreur dans le sendMailViaContact:" . $e->getMessage());
            //throw $th;
        }
    }

    // public function envoieMail()
    // {

    //     // Code pour traiter le formulaire
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         // Récupérer les données du formulaire
    //         $email = $_POST['email'];
    //         $password = $_POST['password'];
    //         $confirmPassword = $_POST['confirm_password'];

    //         // Vérifier les données et effectuer d'autres validations si nécessaire
    //         if (!$this->password_verify($password, $confirmPassword)) {
    //             $_SESSION['message'] = 'Les mots de passe ne correspondent pas.';
    //             header('Location: ' . $_SERVER['REQUEST_URI']);
    //         
    //         }

    //         // Instancier la classe Email
    //         $emailForUser = new EmailModel();

    //         // Envoyer l'email
    //         $emailSent = $emailForUser->sendEmail($email, $password, 'John Doe'); // Remplacez 'John Doe' par le nom approprié

    //         if ($emailSent) {
    //             // Email envoyé avec succès
    //             // $_SESSION['message'] = $emailForUser->message_Success();
    //             $_SESSION['message'] = 'Un mail vous a été envoyé.';
    //         } else {
    //             // Erreur lors de l'envoi de l'email
    //             $_SESSION['message'] = 'Une erreur s\'est produite lors de l\'envoi de l\'email.';
    //         }

    //         // Rediriger vers la même page pour afficher le message
    //         header('Location: ' . $_SERVER['REQUEST_URI']);
    //
    //     }

    //     // Afficher la vue
    //     echo $twig->render('login.twig', ['emailSent' => isset($emailSent) ? $emailSent : false]);

    //     // Arrêtez l'exécution du script

    // }

    // public function password_verify($password, $confirmPassword)
    // {
    //     return $password === $confirmPassword;
    // }
}
