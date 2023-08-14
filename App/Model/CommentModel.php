<?php

namespace App\Model;

use PDO;
use Exception;
use DateTime;
use App\Class\Comment;
use App\Service\DatabaseConnection;

class CommentModel extends DatabaseConnection
{
    /**
     * Get all rejected comments for a single blog post with its author's username
     *
     * @param  int $postID
     * @return void
     */
    public function  updateCommentStatus(int $id, $status)
    {
        $querySQL = "UPDATE comments SET comments.status=:status WHERE id = :id";
        $request = $this->database->prepare($querySQL);
        $request->execute(
            array(
                ':id' => $id,
                ':status' => $status,
            )
        );
    }


    /**
     * Get all rejected comments for a single blog post with its author's username
     *
     * @param  int $postID
     * @return void
     */
    public function  getAllRejectedComments()
    {
        $requeteSQL = "SELECT * FROM comments WHERE comments.status = 'REJECTED' ORDER BY created_at DESC;";
        $requete = $this->database->prepare($requeteSQL);
        $requete->execute();
        $comments = $requete->fetchAll(PDO::FETCH_ASSOC);
        $commentRejected = [];
        if ($comments) {
            foreach ($comments as $comment) {
                $commentRejected[] = new Comment($comment);
            }
        }

        return $commentRejected;
    }

    /**
     * Get all approved comments for a single blog post with its author's username
     *
     * @param  int $postID
     * @return void
     */
    public function  getAllApprovedComments()
    {
        $requeteSQL = "SELECT * FROM comments WHERE comments.status = 'APPROVED' ORDER BY created_at DESC;";
        $requete = $this->database->prepare($requeteSQL);
        $requete->execute();
        $comments = $requete->fetchAll(PDO::FETCH_ASSOC);
        $commentApproved = [];
        if ($comments) {
            foreach ($comments as $comment) {
                $commentApproved[] = new Comment($comment);
            }
        }

        return $commentApproved;
    }

    /**
     * Get all comments
     *
     * @return array of Comment objects
     */
    public function getAllPendingComments()
    {
        $requeteSQL = "SELECT * FROM comments WHERE comments.status = 'PENDING' ORDER BY created_at DESC;";
        $requete = $this->database->prepare($requeteSQL);
        $requete->execute();
        $comments = $requete->fetchAll(PDO::FETCH_ASSOC);
        $commentPending = [];

        if ($comments) {
            foreach ($comments as $comment) {
                $commentPending[] = new Comment($comment);
            }
        }

        return $commentPending;
    }

    /**
     * Find 1 comment by ID
     *
     * @param  integer $id
     * @return Comment
     */
    public function getCommentsWith(int $id)
    {
        $requeteSQL = "SELECT * FROM comments WHERE id = :id";
        $requete = $this->database->prepare($requeteSQL);
        $requete->bindValue(':id', $id, PDO::PARAM_INT);
        $requete->execute();
        return new Comment($requete->fetch(PDO::FETCH_ASSOC));
    }

    public function getAllCommentsWith($idPost)
    {
        $requeteSQL = "SELECT * FROM comments WHERE post_id = :id AND status = 'APPROVED' ORDER BY created_at DESC;";
        $requete = $this->database->prepare($requeteSQL);
        $requete->bindValue(":id", $idPost, PDO::PARAM_INT);
        $requete->execute();
        $comments = $requete->fetchAll(PDO::FETCH_ASSOC);
        $commentObjects = [];

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
        $requeteSQL = "SELECT * FROM comments WHERE id = ?";
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

            throw new Exception("Une erreur est survenue lors de la creation du comment : " . $e->getMessage());

            header('Location: posts');
        }
    }
}
