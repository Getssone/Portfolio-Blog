<?php

namespace App\Controller;

use Exception;
use App\Class\User;
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
        $this->userModel = new UserModel($sessionModel);
        $this->userAuth = new AuthModel();
    }

    public function updateRole()
    {
        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            $id = $_GET['id'];
            $newStateRole = $_GET['newStateRole'];
            // var_dump($id);
            // var_dump($newStateRole);
            // die;

            $user = $this->userModel->read($id);
            switch ($newStateRole) {
                case 'admin':
                    $this->admin($user);
                    break;
                case 'blocked':
                    $this->blocked($user);
                    break;
                case 'deleted':
                    $this->deleted($user);
                    break;

                default:
                    $this->guest($user);
                    break;
            }
            $this->userModel->updateRole($id, $user->getRole());
            $this->sessionModel->set('message', "le role de l'utilisateur à été mis à jour");
        }
    }

    public function admin(User $role)
    {
        return $role->setRole(1);
    }

    public function blocked(User $role)
    {
        return $role->setRole(2);
    }
    public function deleted(User $role)
    {
        return $role->setRole(3);
    }
    public function guest(User $role)
    {
        return $role->setRole(0);
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
                header('Location: postsAccess');
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
    }


    public function seeAllUsers()
    {
        $users = $this->userModel->readAll();
        $this->sessionModel->set('users', $users);
    }

    public function seeUserID()
    {
        $id = $_GET['id'];
        $userToDeleted = $this->userModel->read($id);
        $this->sessionModel->set('userToDeleted', $userToDeleted);
    }
    public function deletedUser()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                // Récupérer les paramètres GET
                $userId = $_GET['id'];

                if (isset($userId) && !empty($userId)) {
                    $thisUserDeleted = $this->userModel->deleteUser($userId);
                    if ($thisUserDeleted === true) {
                        $this->sessionModel->set('message', "Cette ombre à été banni de nos terres   ");
                        header('Location: admin');
                    }
                } else {
                    $this->sessionModel->set('error_message', "Nous n'avons pu bannir ce ombre il doit être ensorceler");
                    header('Location: admin');
                }
            }
        } catch (Exception $e) {
            $this->sessionModel->set('message', $e->getMessage());
            // Redirection vers le post
            header('Location: error_404');
        }
    }
}
