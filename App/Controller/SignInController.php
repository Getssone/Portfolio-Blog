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
    /**
     * Store a new user in Database
     *
     */
    public function signIn()
    {
        try {
            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                // Récupérer les données du formulaire
                if (isset($_POST['username'])) {
                    // La variable est définie, on peut l'utiliser
                    $userName = filter_var(ucfirst(strtolower(htmlspecialchars($_POST['username']))), FILTER_DEFAULT);
                }
                if (isset($_POST['email'])) {
                    $email = filter_var(strtolower(htmlspecialchars($_POST['email'])), FILTER_VALIDATE_EMAIL);
                }

                if (isset($_POST['first_name'])) {
                    $firstName = filter_var(ucfirst(strtolower(htmlspecialchars($_POST['first_name']))), FILTER_DEFAULT);
                }
                if (isset($_POST['last_name'])) {

                    $lastName = filter_var(ucfirst(strtolower(htmlspecialchars($_POST['last_name']))), FILTER_DEFAULT);
                }
                if (isset($_POST['password'])) {

                    $password = filter_var(htmlspecialchars($_POST['password']), FILTER_DEFAULT);
                }
                if (isset($_POST['password_Confirmed'])) {
                    $passwordConfirmed = filter_var(htmlspecialchars($_POST['password_Confirmed']), FILTER_DEFAULT);
                }

                // Vérifier si les champs requis sont présents
                if (!isset($userName) || !isset($email) || !isset($firstName) || !isset($lastName) || !isset($password) || !isset($passwordConfirmed)) {
                    throw new Exception('Tous les champs requis ne sont pas présents.');
                }

                // Effectuer les vérifications de sécurité ici
                if ($password !== $passwordConfirmed) {
                    throw new Exception('Les mots de passe ne correspondent pas.');
                }

                // Appeler la méthode messageWelcome
                $this->messageWelcome($userName, $email, $firstName, $lastName, $password);

                // Rediriger vers la page de connexion
                header('Location: login');
            }
        } catch (Exception $e) {
            $this->sessionModel->set('message', $e->getMessage());
            // Redirection vers la page de connexion
            header('Location: signIn');
        }
    }

    /**
     * Store a new message for new User
     *
     */

    protected function messageWelcome(string $username, string $email, string $first_name, string $last_name, string $password)
    {
        $userModel = new UserModel($this->sessionModel);
        $userId = $userModel->create($username, $email, $first_name, $last_name, $password, 0);
        $username = $userModel->getUsernameById($userId);


        // Afficher un message de remerciement avec le nom d'utilisateur récupéré
        $this->sessionModel->set('message', "Merci pour votre enregistrement ! Votre compte a bien été créé au nom de : $username ");
    }
}
