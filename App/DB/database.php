<?php

namespace App\DB\database;

use PDO;

class DatabaseConnection
{
    public ?\PDO $database = null;
    public function test()
    {
        echo "oki";
    }
    // public function getConnection(): \PDO
    // {
    //     if ($this->database === null) {
    //         $this->database = new \PDO('mysql:dbname=blog_p5;host=localhost', 'root', 'root');
    //     }

    //     return $this->database;
    // }
    public function repas()
    { {
            if ($this->database === null) {
                $this->database = new \PDO('mysql:dbname=test_exo_partage_de_recette;host=localhost', 'root', 'root');
            }

            $repas = $this->database->query(" SELECT * FROM recipes ");
            return $repas;
        }
        // $pdo = new PDO('mysql:dbname=test_exo_partage_de_recette;host=localhost', 'root', 'root');
        // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        // $repas = $pdo->query(" SELECT * FROM recipes ");
        // return $repas;
    }
}
