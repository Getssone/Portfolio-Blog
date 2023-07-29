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


    // /** Pas besoin de crée le __construct car nous faisons appelle au élément parent (ici le parent est : PostController)*/ // Ne fonctionne plus car bug a cause (trop de session envoyé) 
    public function __construct(SessionModel $sessionModel)
    {
        $this->sessionModel = $sessionModel; //récupéré via le rooter
        $this->authModel = new AuthModel();
        $this->user = $this->authModel->getCurrentUser();
        $this->postModel = new PostModel();
        $this->userModel = new UserModel();
        $this->commentModel = new CommentModel();
    }

    public function updateCommentStatus()
    {
        $id = $_GET['id'];
        $newStateStatus = $_GET['newStateStatus'];
        // var_dump($id);
        // die;
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
                    // var_dump($comment);
                    // die;
                }
            }

            $this->sessionModel->set('commentsRejected', $commentsRejected);
            $this->sessionModel->set('message', "Avec brio, l'annotation a été ajustée.");
        } catch (Exception $e) {
            $this->sessionModel->set('message', $e->getMessage());
            // Redirection vers le post
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
                    // var_dump($comment);
                    // die;
                }
            }

            $this->sessionModel->set('commentsApproved', $commentsApproved);
            $this->sessionModel->set('message', "Avec brio, l'annotation a été ajustée.");
        } catch (Exception $e) {
            $this->sessionModel->set('message', $e->getMessage());
            // Redirection vers le post
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
            // Redirection vers le post
            header('Location: error_404');
        }
    }

    public function getCommentsWithAuthors()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                // Récupérer les paramètres GET
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
        } catch (Exception $e) {
            $this->sessionModel->set('message', $e->getMessage());
            // Redirection vers le post
            header('Location: error_404');
        }
    }



    /**
     * save a new comment in Database
     */

    public function registerComment()
    {
        // Récupérer les données du formulaire
        $postId = htmlspecialchars($_POST['postId']);
        $userId = htmlspecialchars($_POST['userId']);
        $commentTitle = ucfirst(strtolower(htmlspecialchars($_POST['commentTitle'])));
        $commentContent = ucfirst(strtolower(htmlspecialchars($_POST['commentContent'])));

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {


                if (isset($commentTitle) && !empty($commentTitle)) {
                    // Appeler la méthode commentSaved
                    $this->commentSaved($postId, $userId, $commentTitle, $commentContent);

                    //TEST
                    // Redirection vers le post
                    // header("Refresh:0; url= ../postAccess?id= $postId"); Fonctionne idem que en dessous
                    header("Location: ../postAccess?id=$postId");
                } else {
                    $this->sessionModel->set('error_message', "Nous n'avons pas pu enregistrer votre message");
                    var_dump('bloque dans registerComment coter else');
                    // Redirection vers le post
                    header("Location: ../postAccess?id=$postId");
                }
            }
        } catch (Exception $e) {
            $this->sessionModel->set('message', $e->getMessage());
            // Redirection vers le post
            var_dump('bloque dans Exception sur registerComment');
            die;
            header("Location: ../posts");
        }
    }

    protected function commentSaved(int $postId, int $userId, string $commentTitle, string $commentContent)
    {
        try {
            if (isset($commentTitle) && !empty($commentTitle)) {

                $commentIdSave = $this->commentModel->create($postId, $userId, $commentTitle, $commentContent);
                // Afficher un message de remerciement avec l'id du commentaire
                $this->sessionModel->set('messageComment', "Merci pour votre commentaire ! Votre message a bien été enregistré ID du commentaire : $commentIdSave");
                // var_dump('bloque dans commentSaved');
                // die;

            }
        } catch (Exception $e) {
            $this->sessionModel->set('message', $e->getMessage());
            // Redirection vers le post
            var_dump('bloque Exception dans commentSaved');
            die;
            header("Location: postAccess?id=$postId");
        }
    }
}
