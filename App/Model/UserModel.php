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
        $querySQL = "SELECT * FROM users";
        $reponse = $this->database->query($querySQL);
        while ($user = $reponse->fetch()) {
            $usersArray[] = new User($user);
        }
        return $usersArray;
    }
    public function read(int $id)
    {
        $querySQL = ("SELECT * FROM users WHERE id = :id ");
        $reponse = $this->database->prepare($querySQL);
        $reponse->bindValue(":id", $id, PDO::PARAM_INT);
        $reponse->execute();
        $result = $reponse->fetch(PDO::FETCH_ASSOC);

        return new User($result);
    }

    public function findByEmail(string $email)
    {
        // var_dump($email);
        // die;
        $querySQL = ("SELECT * FROM users WHERE email = :email");
        $reponse = $this->database->prepare($querySQL);
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
        $querySQL = "SELECT * FROM users WHERE username = :username";
        $reponse = $this->database->prepare($querySQL);
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
                ':password' => password_hash($password, PASSWORD_BCRYPT),
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
        $reponse->bindValue(":userId", $userId, PDO::PARAM_INT);
        $reponse->execute();
        // $reponse = $this->database->prepare($querySQL);
        // $reponse->execute(array(':userId' => $userId));

        $user = $reponse->fetch(PDO::FETCH_ASSOC); //les résultats seront sous forme de tableau associatif

        return $user['username'];
    }
}
