<?php

namespace App\Controller;

use App\Model\Email;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;

session_start();

class EmailController
{

    public function envoieMail()
    {
        // Charger la vue correspondante
        $loader = new FilesystemLoader(__DIR__ . "/App/View");
        $twig = new Environment($loader, [
            'cache' => false, //__DIR__ .'./Tmp',
            'debug' => true,
        ]);
        $twig->addExtension(new DebugExtension); // permet d'utiliser dump() = var_dump() qui lui n'est pas accessible dans twig

        // Code pour traiter le formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            // Vérifier les données et effectuer d'autres validations si nécessaire
            if (!$this->password_verify($password, $confirmPassword)) {
                $_SESSION['message'] = 'Les mots de passe ne correspondent pas.';
                header('Location: ' . $_SERVER['REQUEST_URI']);
                exit();
            }

            // Instancier la classe Email
            $emailForUser = new Email();

            // Envoyer l'email
            $emailSent = $emailForUser->sendEmail($email, $password, 'John Doe'); // Remplacez 'John Doe' par le nom approprié

            if ($emailSent) {
                // Email envoyé avec succès
                // $_SESSION['message'] = $emailForUser->message_Success();
                $_SESSION['message'] = 'Un mail vous a été envoyé.';
            } else {
                // Erreur lors de l'envoi de l'email
                $_SESSION['message'] = 'Une erreur s\'est produite lors de l\'envoi de l\'email.';
            }

            // Rediriger vers la même page pour afficher le message
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit();
        }

        // Afficher la vue
        echo $twig->render('login.twig', ['emailSent' => isset($emailSent) ? $emailSent : false]);

        // Arrêtez l'exécution du script
        exit();
    }
    public function password_verify($password, $confirmPassword)
    {
        return $password === $confirmPassword;
    }
}
