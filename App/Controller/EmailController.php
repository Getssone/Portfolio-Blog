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
                    $name = filter_var($_POST['name'], FILTER_DEFAULT);
                    $email = filter_var(stripslashes($_POST['email']), FILTER_SANITIZE_EMAIL);
                    $object = filter_var(stripslashes($_POST['object']), FILTER_DEFAULT);
                    $message = filter_var(stripslashes($_POST['message']), FILTER_DEFAULT);

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
}
