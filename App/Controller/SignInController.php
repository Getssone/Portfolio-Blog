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
                $userName = filter_var(ucfirst(strtolower(htmlspecialchars($_POST['username']))), FILTER_DEFAULT);
                $email = filter_var(strtolower(htmlspecialchars($_POST['email'])), FILTER_VALIDATE_EMAIL);
                $firstName = filter_var(ucfirst(strtolower(htmlspecialchars($_POST['first_name']))), FILTER_DEFAULT);
                $lastName = filter_var(ucfirst(strtolower(htmlspecialchars($_POST['last_name']))), FILTER_DEFAULT);
                $password = filter_var(htmlspecialchars($_POST['password']), FILTER_DEFAULT);
                $passwordConfirmed = filter_var(htmlspecialchars($_POST['password_Confirmed']), FILTER_DEFAULT);

                if (!isset($userName) || !isset($email) || !isset($firstName) || !isset($lastName) || !isset($password) || !isset($passwordConfirmed)) {
                    throw new Exception('Tous les champs requis ne sont pas présents.');
                }

                if ($password !== $passwordConfirmed) {
                    throw new Exception('Les mots de passe ne correspondent pas.');
                }

                $this->messageWelcome($userName, $email, $firstName, $lastName, $password);

                header('Location: login');
            }
        } catch (Exception $e) {
            $this->sessionModel->set('message', $e->getMessage());
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


        $this->sessionModel->set('message', "Merci pour votre enregistrement ! Votre compte a bien été créé au nom de : $username ");
    }
}
