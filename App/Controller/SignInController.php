<?php

namespace App\Controller;

use Exception;
use App\Model\UserModel;
use App\Model\SessionModel;


class SignInController
{
    private $sessionModel;

    public function __construct(SessionModel $sessionModel)
    {
        $this->sessionModel = $sessionModel;
    }

    public function signIn()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Récupérer les données du formulaire
                $userName = ucfirst(strtolower(htmlspecialchars($_POST['username'])));
                $email = strtolower(htmlspecialchars($_POST['email']));
                $firstName = ucfirst(strtolower(htmlspecialchars($_POST['first_name'])));
                $lastName = ucfirst(strtolower(htmlspecialchars($_POST['last_name'])));
                $password = htmlspecialchars($_POST['password']);
                $passwordConfirmed = htmlspecialchars($_POST['password_Confirmed']);

                // Effectuer les vérifications de sécurité ici
                if ($password === $passwordConfirmed) {
                    // Appeler la méthode messageWelcome
                    $this->messageWelcome($userName, $email, $firstName, $lastName, $password);

                    // Rediriger vers la page de connexion
                    header('Location: login');

                    // Terminer l'exécution du script pour éviter tout affichage supplémentaire
                    exit;
                } else {
                    // Les mots de passe ne correspondent pas, afficher un message d'erreur
                    $this->sessionModel->set('error_message', 'Les mots de passe ne correspondent pas.');
                    header('Location: signIn');
                    exit;
                }
            }
        } catch (Exception $e) {
            $this->sessionModel->set('message', $e->getMessage());
            // Redirection vers la page de connexion
            header('Location: signIn');
            exit;
        }
    }


    protected function messageWelcome(string $username, string $email, string $first_name, string $last_name, string $password)
    {
        $userModel = new UserModel();
        $userId = $userModel->create($username, $email, $first_name, $last_name, $password, 0);
        $username = $userModel->getUsernameById($userId);

        // Afficher un message de remerciement avec le nom d'utilisateur récupéré
        // echo "Merci pour votre enregistrement ! Votre compte a bien été créé au nom de : " . $username . ".";

        // Afficher un message de remerciement avec le nom d'utilisateur récupéré
        $this->sessionModel->set('message', "Merci pour votre enregistrement ! Votre compte a bien été créé au nom de : $username ");
    }
}
