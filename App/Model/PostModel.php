<?php

namespace App\Model;

use PDO;
use DateTime;
use Exception;
use App\Class\Post;
use App\Service\DatabaseConnection\DatabaseConnection;

class PostModel extends DatabaseConnection
{
    /**
     * Get all blogposts sorted by creation date
     *
     * @return array
     */
    public function getAllPosts()
    {
        $posts = [];
        $requeteSQL = "SELECT * FROM posts ORDER BY created_at DESC";
        // TODO: jointure sur le User pour éviter d'avoir à appeler un READ supplémentaire pour chaque userID
        $results = $this->database->query($requeteSQL);
        while ($post = $results->fetch(PDO::FETCH_ASSOC)) {
            $posts[] = new Post($post);
        }
        // var_dump("--posts--");
        // var_dump($posts);
        // die;
        return $posts;

        // $requeteSQL = "SELECT * FROM posts";
        // $reponse = $this->database->query($requeteSQL); //query() est une fonction de l'object PDO et return un object PDOStatement
        // $posts = $reponse->fetch(); //fetchAll() est une fonction de l'object PDOStatement
        // return $posts;
    }

    /**
     * Find a blogpost by ID
     *
     * @param  integer $id
     * @return Post
     */
    public function read(int $id)
    {
        $querySQL = "SELECT * FROM posts WHERE id = :id";
        $response = $this->database->prepare($querySQL);
        $response->bindValue(":id", $id, PDO::PARAM_INT);
        $response->execute();
        $arrayPost = $response->fetch(PDO::FETCH_ASSOC);
        if (!$arrayPost) {
            return null;
        }
        //$postData = get_object_vars($result);
        return new Post($arrayPost);
    }

    public function findByTitle(string $title)
    {
        // var_dump($title);
        // die;
        $querySQL = ("SELECT * FROM posts WHERE title = :title");
        $reponse = $this->database->prepare($querySQL);
        $reponse->bindValue(":title", $title, PDO::PARAM_STR);
        $reponse->execute();
        $result = $reponse->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            return null;
        }
        // $postData = (array) $result;
        //$postData = get_object_vars($result);
        // return new Post($postData);
        return new Post($result);
    }


    public function create(string $title, string $image, int $created_by, string $lead_sentence, string $content)
    {
        $existingPost = $this->findByTitle($title);
        if ($existingPost) {
            throw new Exception("Un post avec ceux titre existe déjà.");
        }
        $now = new DateTime();
        $postArray = [];
        $querySQL = "INSERT INTO posts (title, image, created_at, created_by,lead_sentence,content) VALUES (:title, :image, :created_at,:created_by,:lead_sentence,:content)";
        $reponse = $this->database->prepare($querySQL);
        $reponse->execute(
            array(
                ':title' => $title,
                ':image' => $image,
                ':created_at' => $now->format("Y-m-d H:i:s"),
                ':created_by' => $created_by,
                ':content' => $content,
                ':lead_sentence' => $lead_sentence,
            )
        );
        $newPostId = $this->database->lastInsertId();

        return $newPostId;
    }
}
