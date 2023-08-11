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
                // Get the data from the form
                $userName = filter_var(ucfirst(strtolower(htmlspecialchars($_POST['username']))), FILTER_DEFAULT);
                $email = filter_var(strtolower(htmlspecialchars($_POST['email'])), FILTER_VALIDATE_EMAIL);
                $firstName = filter_var(ucfirst(strtolower(htmlspecialchars($_POST['first_name']))), FILTER_DEFAULT);
                $lastName = filter_var(ucfirst(strtolower(htmlspecialchars($_POST['last_name']))), FILTER_DEFAULT);
                $password = filter_var(htmlspecialchars($_POST['password']), FILTER_DEFAULT);
                $passwordConfirmed = filter_var(htmlspecialchars($_POST['password_Confirmed']), FILTER_DEFAULT);

                // Check if the required fields are present
                if (!isset($userName) || !isset($email) || !isset($firstName) || !isset($lastName) || !isset($password) || !isset($passwordConfirmed)) {
                    throw new Exception('Tous les champs requis ne sont pas présents.');
                }

                // Check if the passwords match
                if ($password !== $passwordConfirmed) {
                    throw new Exception('Les mots de passe ne correspondent pas.');
                }

                // Call the messageWelcome method
                $this->messageWelcome($userName, $email, $firstName, $lastName, $password);

                // Redirect to the login page
                header('Location: login');
            }
        } catch (Exception $e) {
            $this->sessionModel->set('message', $e->getMessage());
            // Redirect to the signIn page
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
        var_dump('ok1');
        die;


        // Afficher un message de remerciement avec le nom d'utilisateur récupéré
        $this->sessionModel->set('message', "Merci pour votre enregistrement ! Votre compte a bien été créé au nom de : $username ");
    }
}
