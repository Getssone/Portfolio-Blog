<?php

namespace Application\DB\Database;

class DatabaseConnection
{
    public ?\PDO $database = null;

    public function getConnection(): \PDO
    {
        if ($this->database === null) {
            $this->database = new \PDO('mysql:dbname=blog_p5;host=localhost', 'root', 'root');
        }

        return $this->database;
    }
}
