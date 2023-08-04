<?php

namespace App\Model;

use App\Class\User;
use App\Model\UserModel;

class AuthModel
{
    /**
     * User manager : requete PDO connection to Users stored in the database
     *
     * @var userModel
     */
    private $userModel;

    /**
     * User session
     *
     * @var Session
     */
    private $session;


    public function __construct()
    {
        $this->session = new SessionModel();
        $this->userModel = new UserModel($this->session);
    }

    /**
     * Checks whether user is currently logged in
     *
     * @return boolean
     */
    public function isLoggedIn(): bool
    {
        // Les informations ID de l'utilisateur sont stockées dans la session .
        if (empty($this->session->get('userID')) && empty($this->session->get('username'))) {
            return false;
        }
        return true;
    }

    /**
     * Retrieves current user from session storage
     *
     * @return User
     */
    public function getCurrentUser(): ?User
    {
        $userId = $this->session->get('userID');

        if ($userId === null) {
            return null;
        }

        $user = $this->userModel->read($userId);

        return $user;
    }


    /**
     * Checks whether the current user has admin role
     *
     * @return boolean
     */
    public function isCurrentUserAdmin(): bool
    {
        /**
         * First retrieve user from session
         */
        if ($this->isLoggedIn()) {
            $user = $this->userModel->read($this->session->get('userID'));
            $role = $user->getRole();
            if ($role === false || $role === 0 || $role === '0') {
                return false;
            }
            return true;
        }
        return false;
    }


    /**
     * Checks a user's role
     *
     * @param  User $user
     * @return boolean
     */
    public function isAdmin(User $user): bool
    {
        if ($user->getRole() === false || $user->getRole() === 0 || $user->getRole() === '0') {
            return false;
        }
        return true;
    }


    /**
     * Determines whether a user's account is disabled ("deleted") or not
     *
     * @param  User $user
     * @return boolean
     */
    public function isDisabled(User $user): bool
    {
        if ($user->getDeleted() === true || $user->getDeleted() === 1 ||  $user->getDeleted() === '1') {
            return true;
        }
        return false;
    }
}
