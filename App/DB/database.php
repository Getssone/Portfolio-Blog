<?php

namespace App\DB\database;

use PDO;
use PDOException;

class DatabaseConnection
{
    private ?PDO $database = null; //déclare une propriété $database de type variable peut contenir soit une instance de la classe PDO, soit la valeur null.

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        $dsn = 'mysql:dbname=blog_p5;host=localhost';
        $username = 'root';
        $password = 'root';

        try {
            $this->database = new PDO($dsn, $username, $password);
            $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //pratique recommandée pour le traitement des erreurs de base de données, car cela facilite le débogage et la gestion des erreurs de manière plus précise.


            $this->database->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ); //les résultats sont renvoyés sous forme d'objets anonymes, où les noms des colonnes de la table deviennent des propriétés de l'objet. 
        } catch (PDOException $e) {
            // Gérer l'erreur de connexion à la base de données
            echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
            exit();
            // exit( $e);
            // exit( $e->getMessage());
        }
    }

    public function articles()
    {
        $requeteSQL = "SELECT * FROM articles";
        $reponse = $this->database->query($requeteSQL);
        $articles = $reponse->fetchAll();
        return $articles;
    }
}
