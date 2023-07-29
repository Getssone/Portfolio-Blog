<?php

namespace App\Model;

use PDO;
use Exception;
use App\Class\User;
use App\Service\DatabaseConnection;

class UserModel extends DatabaseConnection
{

    public function updateRole(int $id, $role)
    {
        $querySQL = "UPDATE users SET role = :role WHERE id = :id";
        // var_dump($querySQL);
        // die;

        $request = $this->database->prepare($querySQL);
        $request->execute(
            array(
                ':role' => $role,
                ':id' => $id,
            )
        );
    }

    public function readAll()
    {
        $usersArray = [];
        $querySQL = "SELECT * FROM users";
        $reponse = $this->database->query($querySQL);
        while ($user = $reponse->fetch(PDO::FETCH_ASSOC)) {
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

        if (!$result) {
            return null; // Aucun utilisateur trouvé, retourne null
        }
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
        $requete = $this->database->prepare($querySQL);
        $requete->execute(
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
        $request = $this->database->prepare($querySQL);
        $request->bindValue(":userId", $userId, PDO::PARAM_INT);
        $request->execute();
        // $reponse = $this->database->prepare($querySQL);
        // $reponse->execute(array(':userId' => $userId));

        $user = $request->fetch(PDO::FETCH_ASSOC); //les résultats seront sous forme de tableau associatif

        return $user['username'];
    }
    public function deleteUser(int $userId)
    {
        $querySQL = "DELETE FROM users WHERE id = :id";
        $requete = $this->database->prepare($querySQL);
        $requete->bindValue(":id", $userId, PDO::PARAM_INT);
        $success = $requete->execute();

        return $success;
    }
}
