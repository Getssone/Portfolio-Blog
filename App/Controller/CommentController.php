<?php

namespace App\Controller;

use Exception;
use App\Class\Comment;
use App\Model\AuthModel;
use App\Model\PostModel;
use App\Model\UserModel;
use App\Model\CommentModel;
use App\Model\SessionModel;

class CommentController extends PostController
{
    protected $sessionModel;
    protected $authModel;
    protected $user;
    protected $postModel;
    protected $userModel;
    protected $commentModel;

    public function __construct(SessionModel $sessionModel)
    {
        $this->sessionModel = $sessionModel;
        $this->authModel = new AuthModel();
        $this->user = $this->authModel->getCurrentUser();
        $this->postModel = new PostModel($sessionModel);
        $this->userModel = new UserModel($sessionModel);
        $this->commentModel = new CommentModel($sessionModel);
    }

    public function updateCommentStatus()
    {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            throw new Exception("L'identifiant 'id' est manquant ou vide dans l'URL.");
            return;
        }

        if (isset($_GET['newStateStatus']) && !empty($_GET['newStateStatus'])) {
            $newStateStatus = $_GET['newStateStatus'];
        } else {
            throw new Exception("Le paramètre 'newStateStatus' est manquant ou vide dans l'URL.");
            return;
        }
        $comment = $this->commentModel->getCommentsWith($id);
        if ($newStateStatus === 'approved') {
            $this->approve($comment);
        } elseif ($newStateStatus === 'rejected') {
            $this->reject($comment);
        }
        $this->commentModel->updateCommentStatus($id, $comment->getStatus());
        $this->sessionModel->set('message', 'le status du commentaire à été mis à jour');
    }

    public function reject(Comment $comment)
    {
        return $comment->setStatus($comment::REJECTED);
    }

    public function approve(Comment $comment)
    {
        return $comment->setStatus($comment::APPROVED);
    }

    public function getAllRejectedComments()
    {
        try {

            $commentsRejected = $this->commentModel->getAllRejectedComments();
            if (is_array($commentsRejected)) {
                foreach ($commentsRejected as $comment) {
                    $authorID = $comment->getCreatedBy();
                    $infosAuthor = $this->userModel->read($authorID);
                    $comment->setCreated_by($infosAuthor->getUsername());
                    $postId = $comment->getPostId();
                    $allPost = $this->postModel->getPost($postId);
                    $titlePost = $allPost->getTitle();
                    $comment->setPost_id($titlePost);
                }
            }

            $this->sessionModel->set('commentsRejected', $commentsRejected);
            $this->sessionModel->set('message', "Avec brio, l'annotation a été ajustée.");
        } catch (Exception $e) {
            $this->sessionModel->set('message', $e->getMessage());
            header('Location: error_404');
        }
    }

    public function getAllApprovedComments()
    {
        try {

            $commentsApproved = $this->commentModel->getAllApprovedComments();
            if (is_array($commentsApproved)) {
                foreach ($commentsApproved as $comment) {
                    $authorID = $comment->getCreatedBy();
                    $infosAuthor = $this->userModel->read($authorID);
                    $comment->setCreated_by($infosAuthor->getUsername());
                    $postId = $comment->getPostId();
                    $allPost = $this->postModel->getPost($postId);
                    $titlePost = $allPost->getTitle();
                    $comment->setPost_id($titlePost);
                }
            }

            $this->sessionModel->set('commentsApproved', $commentsApproved);
            $this->sessionModel->set('message', "Avec brio, l'annotation a été ajustée.");
        } catch (Exception $e) {
            $this->sessionModel->set('message', $e->getMessage());
            header('Location: error_404');
        }
    }

    public function getAllPendingComments()
    {
        try {

            $allCommentsPending = $this->commentModel->getAllPendingComments();
            if (is_array($allCommentsPending)) {
                foreach ($allCommentsPending as $comment) {
                    $authorID = $comment->getCreatedBy();
                    $infosAuthor = $this->userModel->read($authorID);
                    $comment->setCreated_by($infosAuthor->getUsername());
                    $postId = $comment->getPostId();
                    $allPost = $this->postModel->getPost($postId);
                    $titlePost = $allPost->getTitle();
                    $comment->setPost_id([
                        'id' => $postId,
                        'title' => $titlePost
                    ]);
                }
            }
            $this->sessionModel->set('commentsPending', $allCommentsPending);
            $this->sessionModel->set('message', "Avec brio, l'annotation a été ajustée.");
        } catch (Exception $e) {
            $this->sessionModel->set('message', $e->getMessage());
            header('Location: error_404');
        }
    }

    public function getCommentsWithAuthors()
    {
        try {
            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'GET') {
                if (isset($_GET['id'])) {
                    $idPost = $_GET['id'];
                    $allComments = $this->commentModel->getAllCommentsWith($idPost);
                    $allAuthors = [];

                    foreach ($allComments as $comment) {
                        $authorID = $comment->getCreatedBy();
                        $infosAuthor = $this->userModel->read($authorID);
                        $comment->setCreated_by($infosAuthor->getUsername());
                        $allAuthors[] = $infosAuthor;
                    }

                    return [
                        'comments' => $allComments,
                        'authors' => $allAuthors
                    ];
                }
            }
        } catch (Exception $e) {
            $this->sessionModel->set('message', $e->getMessage());
            header('Location: error_404');
        }
    }



    /**
     * save a new comment in Database
     */

    public function registerComment()
    {
        $postId = htmlspecialchars($_POST['postId']);
        $userId = htmlspecialchars($_POST['userId']);
        $commentTitle = ucfirst(strtolower(htmlspecialchars($_POST['commentTitle'])));
        $commentContent = ucfirst(strtolower(htmlspecialchars($_POST['commentContent'])));

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {


                if (isset($commentTitle) && !empty($commentTitle)) {
                    $this->commentSaved($postId, $userId, $commentTitle, $commentContent);

                    header("Location: ../postAccess?id=$postId&location=post");
                } else {
                    $this->sessionModel->set('error_message', "Nous n'avons pas pu enregistrer votre message");
                    var_dump('bloque dans registerComment coter else');
                    header("Location: ../postAccess?id=$postId&location=post");
                }
            }
        } catch (Exception $e) {
            $this->sessionModel->set('message', $e->getMessage());
            header("Location: ../posts");
        }
    }

    protected function commentSaved(int $postId, int $userId, string $commentTitle, string $commentContent)
    {
        try {
            if (isset($commentTitle) && !empty($commentTitle)) {

                $commentIdSave = $this->commentModel->create($postId, $userId, $commentTitle, $commentContent);
                $this->sessionModel->set('messageComment', "Merci pour votre commentaire ! Votre message a bien été enregistré ID du commentaire : $commentIdSave");
            }
        } catch (Exception $e) {
            $this->sessionModel->set('message', $e->getMessage());
            header("Location: postAccess?id=$postId");
        }
    }
}
