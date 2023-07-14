<?php

namespace App\Controller;

use Exception;
use App\Model\AuthModel;
use App\Model\PostModel;
use App\Model\UserModel;
use App\Model\SessionModel;
use App\Model\CommentModel;

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
        // $this->sessionModel = $sessionModel; //récupéré via le rooter
        $this->authModel = new AuthModel();
        $this->user = $this->authModel->getCurrentUser();
        $this->postModel = new PostModel();
        $this->userModel = new UserModel();
        $this->commentModel = new CommentModel();
    }

    public function getCommentsWithAuthors()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                // Récupérer les paramètres GET
                $idPost = $_GET['id'];
                $allComments = $this->commentModel->getAllComments($idPost);
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
            exit;
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
                    exit;
                } else {
                    $this->sessionModel->set('error_message', "Nous n'avons pas pu enregistrer votre message");
                    var_dump('bloque dans registerComment cote else');
                    // Redirection vers le post
                    header("Location: ../postAccess?id=$postId");
                    exit;
                }
            }
        } catch (Exception $e) {
            $this->sessionModel->set('message', $e->getMessage());
            // Redirection vers le post
            var_dump('bloque dans Exception sur registerComment');
            die;
            header("Location: ../posts");
            exit;
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
            exit;
        }
    }
}
