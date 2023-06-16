<?php

namespace App\Model;

use PDO;
use Exception;
use App\Class\User;
use App\Service\DatabaseConnection\DatabaseConnection;

class UserModel extends DatabaseConnection
{

    public function read(int $id)
    {
        $requeteSQL = "SELECT * FROM user WHERE id = ?";
        $reponse = $this->database->prepare($requeteSQL);
        $reponse->bindValue(1, $id, PDO::PARAM_INT);
        $reponse->execute();

        return new User($reponse->fetch());
    }
}
