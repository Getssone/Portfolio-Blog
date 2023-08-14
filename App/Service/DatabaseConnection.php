<?php

namespace App\Service;

use PDO;
use Exception;
use PDOException;
use App\Model\SessionModel;

class DatabaseConnection
{
    protected ?PDO $database = null;

    private $sessionModel;

    public function __construct($sessionModel)
    {
        $this->connect();
        $this->sessionModel = $sessionModel;
    }

    private function connect()
    {
        $dsn = 'mysql:dbname=blog_p5;host=localhost';
        $username = 'root';
        $password = 'root';

        try {
            $this->database = new PDO($dsn, $username, $password);

            $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            $this->database->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            $this->sessionModel->set('message', "Une erreur est survenue lors de la connexion à la base de données. Veuillez réessayer ultérieurement.");
            throw new Exception('Erreur de connexion à la base de données : ' . $e->getMessage());
        }
    }
}
