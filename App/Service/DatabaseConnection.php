<?php

namespace App\Service;

use PDO;
use Exception;
use PDOException;
use App\Model\SessionModel;

class DatabaseConnection
{
    protected ?PDO $database = null; //déclare une propriété $database de type variable peut contenir soit une instance de la classe PDO, soit la valeur null. 
    //Elle est accessible par les enfants

    private $sessionModel;

    public function __construct($sessionModel)
    {
        $this->connect();
        $this->sessionModel = $sessionModel; //récupéré via le rooter
    }

    private function connect()
    {
        $dsn = 'mysql:dbname=blog_p5;host=localhost';
        $username = 'root';
        $password = 'root';

        try {
            $this->database = new PDO($dsn, $username, $password);
            // $this->database = new PDO(
            //     'mysql:host=' . __DBHOST . ';dbname=' . __DBNAME . ';charset=utf8',
            //     __DBUSER,
            //     __DBPASSWD,
            $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //pratique recommandée pour le traitement des erreurs de base de données, car cela facilite le débogage et la gestion des erreurs de manière plus précise.


            $this->database->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ); //les résultats sont renvoyés sous forme d'objets anonymes, où les noms des colonnes de la table deviennent des propriétés de l'objet. 
        } catch (PDOException $e) {
            // Gérer l'erreur de connexion à la base de données
            $this->sessionModel->set('message', "Une erreur est survenue lors de la connexion à la base de données. Veuillez réessayer ultérieurement.");
            throw new Exception('Erreur de connexion à la base de données : ' . $e->getMessage());
        }
    }
}
