<?php

namespace App\Model;

use PDO;
use Exception;
use App\Class\User;
use App\Service\DatabaseConnection\DatabaseConnection;

class UserModel extends DatabaseConnection
{
    public function readAll()
    {
        $usersArray = [];
        $requeteSQL = "SELECT * FROM users";
        $reponse = $this->database->query($requeteSQL);
        while ($user = $reponse->fetch()) {
            $usersArray[] = new User($user);
        }
        return $usersArray;
    }
    public function read(int $id)
    {
        $requeteSQL = ("SELECT * FROM user WHERE id = :id ");
        $reponse = $this->database->prepare($requeteSQL);
        $reponse->bindValue(":id", $id, PDO::PARAM_INT);
        $reponse->execute();
        $result = $reponse->fetch();

        return new User($result);
    }

    public function findByEmail(string $email)
    {
        $requeteSQL = ("SELECT * FROM users WHERE email = :email");
        $reponse = $this->database->prepare($requeteSQL);
        $reponse->bindValue(":email", $email, PDO::PARAM_STR);
        $reponse->execute();
        $result = $reponse->fetch();
        if (!$result) {
            return null;
        }
        $userData = (array) $result;
        //$userData = get_object_vars($result);
        return new User($userData);
    }
    public function findByUsername(string $username)
    {
        $requeteSQL = "SELECT * FROM users WHERE username = :username";
        $reponse = $this->database->prepare($requeteSQL);
        $reponse->bindValue(1, $username, PDO::PARAM_STR);
        $reponse->execute();
        $result = $reponse->fetch();
        if (!$result) {
            return null;
        }
        return new User($result);
    }
    public function create(string $username, string $email, string $first_name, string $last_name, string $password, $role)
    {

        $existingUser = $this->findByEmail($email);
        if ($existingUser) {
            throw new Exception("Un utilisateur avec cette adresse e-mail existe déjà.");
        }

        $querySQL = "INSERT INTO users(username, email, first_name, last_name, password, role) 
                VALUES(:username, :email, :first_name, :last_name, :password, :role)";
        $reponse = $this->database->prepare($querySQL);
        $reponse->execute(
            array(
                ':username' => $username,
                ':email' => $email,
                ':first_name' => $first_name,
                ':last_name' => $last_name,
                ':password' => md5($password),
                ':role' => $role
            )
        );
        $newUserId = $this->database->lastInsertId();
        return $newUserId;
    }

    public function getUsernameById(int $userId)
    {
        $querySQL = "SELECT username FROM users WHERE id = :userId";
        $reponse = $this->database->prepare($querySQL);
        $reponse->execute(array(':userId' => $userId));
        $user = $reponse->fetch(PDO::FETCH_ASSOC);

        return $user['username'];
    }
}
