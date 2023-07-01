<?php

namespace App\Controller;

use Exception;
use App\Model\AuthModel;
use App\Model\UserModel;
use App\Model\SessionModel;

class UserController
{
    private $userModel;
    private $sessionModel;
    private $userAuth;

    public function __construct(SessionModel $sessionModel)
    {
        $this->sessionModel = $sessionModel; //récupéré via le rooter
        $this->userModel = new UserModel();
        $this->userAuth = new AuthModel();
    }

    public function connect(string $email, string $password)
    {
        $user = $this->userModel->findByEmail($email);

        if ($user) {
            // var_dump(password_verify($password, $user->getPassword()));
            // die;
            if (password_verify($password, $user->getPassword())) {
                // var_dump($user);
                // die;

                $setSessionId = $this->sessionModel->set('userID', $user->getId());
                $getSessionId = $this->sessionModel->get('userID');
                // print('$getSessionId' . "<br>");
                // var_dump($getSessionId);
                // die;

                $userCurrent = $this->userAuth->getCurrentUser();
                // print('$userCurrent' . "<br>");
                // var_dump($userCurrent);
                // die;
                $this->sessionModel->set('user', $userCurrent);
                // Authentification réussie
                header('Location: posts');
            } else {
                $this->sessionModel->set('message', "Authentification échouée");
                // Authentification échouée
                header('Location: login');
            }
        }
    }

    public function logoutUser()
    {
        $this->sessionModel->deleteKey('userID');
        $this->sessionModel->destroy();
        return    header('Location: login');
    }

    public function register()
    {
    }

    public function profil()
    {
    }
}
