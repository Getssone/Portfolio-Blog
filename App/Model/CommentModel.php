<?php

namespace App\Model;

use PDO;
use Exception;
use DateTime;
use App\Class\Comment;
use App\Service\DatabaseConnection\DatabaseConnection;

class CommentModel extends DatabaseConnection
{
    /**
     * Get all comments
     *
     * @return array of Comment objects
     */
    public function getAllComments($idPost)
    {
        $requeteSQL = "SELECT * FROM comments WHERE post_id = :id AND status = 'APPROVED' ORDER BY created_at DESC;";
        $requete = $this->database->prepare($requeteSQL);
        $requete->bindValue(":id", $idPost, PDO::PARAM_INT);
        $requete->execute();
        $comments = $requete->fetchAll(PDO::FETCH_ASSOC);
        $commentObjects = [];
        // var_dump($comments);
        // die;
        foreach ($comments as $comment) {
            $commentObjects[] = new Comment($comment);
        }
        return $commentObjects;
    }


    /**
     * Find 1 comment by ID
     *
     * @param  integer $id
     * @return Comment
     */
    public function getComment(int $id)
    {
        $requeteSQL = "SELECT * FROM comment WHERE id = ?";
        $requete = $this->database->prepare($requeteSQL);
        $requete->bindValue(1, $id, PDO::PARAM_INT);
        $requete->execute();
        return new Comment($requete->fetch());
    }

    /**
     * Insert a new comment (with pending status) in the database
     *
     * @param  integer $postId    Foreign key
     * @param  integer $userId  Foreign key
     * @param  string  $commentTitle
     * @param  string  $commentContent
     * @return int
     */
    public function create(int $postId, int $userId, string $commentTitle, string $commentContent)
    {
        try {
            //code...
            $now = new DateTime();
            $querySQL = "INSERT INTO comments ( post_id,created_by, title, content, created_at,  status) 
                    VALUES(:post_id,:created_by,:title, :content, :created_at, :status)";

            $request = $this->database->prepare($querySQL);
            $request->execute(
                array(
                    ':content' => $commentContent,
                    ':title' => $commentTitle,
                    ':created_at' => $now->format('Y-m-d H:i:s'),
                    ':created_by' => $userId,
                    ':post_id' => $postId,
                    ':status' => 'PENDING'
                )
            );
            $newCommentId = $this->database->lastInsertId();

            return $newCommentId;
        } catch (Exception $e) {
            var_dump($e->getMessage());
            die;
            // Redirection vers le posts
            header('Location: posts');
            exit;
        }
    }
}
