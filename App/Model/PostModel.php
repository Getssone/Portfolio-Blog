<?php

namespace App\Model;

use PDO;
use DateTime;
use Exception;
use PDOException;
use App\Class\Post;
use App\Service\DatabaseConnection;

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
        $requeteSQL = "SELECT * FROM posts ORDER BY  updated_at DESC";

        $requete = $this->database->query($requeteSQL);
        while ($post = $requete->fetch(PDO::FETCH_ASSOC)) {
            $posts[] = new Post($post);
        }

        return $posts;
    }

    /**
     * Find a blogpost by ID
     *
     * @param  integer $id
     * @return Post
     */
    public function getPost(int $id)
    {
        $posts = [];
        $querySQL = "SELECT * FROM posts WHERE id = :id";
        $requete = $this->database->prepare($querySQL);
        $requete->bindValue(":id", $id, PDO::PARAM_INT);
        $requete->execute();
        $posts = $requete->fetch(PDO::FETCH_ASSOC);
        if (!$posts) {
            return null;
        }
        return new Post($posts);
    }

    public function delPost(int $id)
    {
        try {
            $querySQL = "DELETE FROM posts WHERE id = :id";
            $requete = $this->database->prepare($querySQL);
            $requete->bindValue(":id", $id, PDO::PARAM_INT);
            $success = $requete->execute();
            return $success;
        } catch (Exception $e) {
            throw new Exception("On ne peut supprimer ou mettre à jour un article qui possède des commentaires");
        }
    }

    public function findByTitle(string $title)
    {
        $querySQL = ("SELECT * FROM posts WHERE title = :title");
        $reponse = $this->database->prepare($querySQL);
        $reponse->bindValue(":title", $title, PDO::PARAM_STR);
        $reponse->execute();
        $result = $reponse->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            return null;
        }
        return new Post($result);
    }

    public function findTitleById(string $id)
    {

        $querySQL = ("SELECT * FROM posts WHERE id = :id");
        $reponse = $this->database->prepare($querySQL);
        $reponse->bindValue(":id", $id, PDO::PARAM_STR);
        $reponse->execute();
        $result = $reponse->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            return null;
        }

        return new Post($result);
    }


    public function create(string $title, string $image, int $created_by, string $lead_sentence, string $content)
    {
        $existingPost = $this->findByTitle($title);
        if ($existingPost) {
            throw new Exception("Un post avec ceux titre existe déjà.");
        }
        $now = new DateTime();
        $querySQL = "INSERT INTO posts (title, image, created_at, updated_at,created_by,lead_sentence,content) VALUES (:title, :image, :created_at, :updated_at,:created_by,:lead_sentence,:content)";
        $request = $this->database->prepare($querySQL);
        $request->execute(
            array(
                ':title' => $title,
                ':image' => $image,
                ':created_at' => $now->format("Y-m-d H:i:s"),
                ':updated_at' => $now->format("Y-m-d H:i:s"),
                ':created_by' => $created_by,
                ':content' => $content,
                ':lead_sentence' => $lead_sentence,
            )
        );
        $newPostId = $this->database->lastInsertId();

        return $newPostId;
    }

    /**
     * Update a blog post
     *
     * @param  integer $id
     * @param  string  $title
     * @param  string  $content
     * @param  string  $slug
     * @param  string  $leadSentence
     * @return void
     */
    public function update(int $id, string $title, string $content, string $leadSentence)
    {

        try {
            $querySQL = "UPDATE posts SET title = :title, content = :content, lead_sentence = :lead_sentence, updated_at = :updated_at WHERE id = :id";
            $request = $this->database->prepare($querySQL);
            $now = new DateTime();
            $request->bindValue(':title', $title, PDO::PARAM_STR);
            $request->bindValue(':lead_sentence', $leadSentence, PDO::PARAM_STR);
            $request->bindValue(':content', $content, PDO::PARAM_STR);
            $request->bindValue(':updated_at', $now->format('Y-m-d H:i:s'));
            $request->bindValue(':id', $id, PDO::PARAM_INT);
            $request->execute();
        } catch (PDOException $e) {
            throw new Exception("Une erreur est survenue lors de la mise à jour du post : " . $e->getMessage());
        }
    }
}
