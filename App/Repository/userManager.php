<?php

namespace App\Repository;

use PDO;
use Exception;
use App\Class\User;
use App\DB\Database\DatabaseConnection;

class UserManager extends DatabaseConnection
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
