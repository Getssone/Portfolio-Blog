<?php

namespace App\Controller;

use Exception;
use App\Model\AuthModel;
use App\Model\PostModel;
use App\Model\UserModel;
use App\Model\SessionModel;
use App\Controller\CommentController;

class PostController
{
    protected $sessionModel;
    protected $authModel;
    protected $user;
    protected $postModel;
    protected $userModel;
    protected $commentController;



    public function __construct(SessionModel $sessionModel)
    {
        $this->sessionModel = $sessionModel; //récupéré via le rooter
        $this->authModel = new AuthModel();
        $this->user = $this->authModel->getCurrentUser();
        $this->postModel = new PostModel();
        $this->userModel = new UserModel();
        $this->commentController = new CommentController($this->sessionModel);
    }


    public function deletePostID()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                // Récupérer les paramètres GET
                $postId = $_GET['id'];

                if (isset($postId) && !empty($postId)) {
                    $thisPostDeleted = $this->postModel->delPost($postId);
                    if ($thisPostDeleted === true) {
                        $this->sessionModel->deleteKey('posts');
                        $this->seeAllPosts();
                        $this->sessionModel->set('message', "le récit à été banni de nos terres   ");

                        header('Location: admin');
                    }
                } else {
                    $this->sessionModel->set('error_message', "Nous n'avons pas pu bannir ce récits il doit être ensorceler");
                    header('Location: admin');
                }
            }
        } catch (Exception $e) {
            $this->sessionModel->set('message', $e->getMessage());
            // Redirection vers le post
            header('Location: error_404');
        }
    }
    public function updatePostID()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $postId = $_POST['postId'];

                if (isset($postId) && !empty($postId)) {
                    $id =  ucfirst(strtolower(htmlspecialchars($_POST['postId'])));

                    $title =  ucfirst(strtolower(htmlspecialchars($_POST['title'])));

                    $leadSentence = htmlspecialchars($_POST['lead_sentence']);

                    $content = htmlspecialchars($_POST['content']);
                    // var_dump($_POST);
                    // die;
                    $this->postModel->update($id, $title, $content, $leadSentence);

                    $this->sessionModel->set('message', "Le post a été enregistré avec succès");
                } else {
                    // Une erreur s'est produite lors de l'enregistrement du fichier
                    $this->sessionModel->set('message', "Une erreur s'est produite lors de l'enregistrement");
                    header('Location: admin');
                }
            }
        } catch (Exception $e) {
            $this->sessionModel->set('message', $e->getMessage());
            // Redirection vers le post
            header('Location: error_404');
        }
    }

    public function seePostID()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {

                // Récupérer les paramètres GET
                $postId = $_GET['id'];
                // $location = $_GET['location'];

                if (isset($postId) && !empty($postId)) {
                    $thisPost = $this->postModel->getPost($postId);
                    $idAuthorPost = $thisPost->getCreated_By();
                    $infosAuthorPost = $this->userModel->read($idAuthorPost);
                    $comments = $this->commentController->getCommentsWithAuthors();
                    // $authorComments = $this->commentController->getAuthorComment();
                    // foreach ($comments as $comment) {
                    //     $idAuthorComments = $comment->getCreated_By();
                    //     $infosAuthorComments = $this->userModel->read($idAuthorComments);
                    //     $authorName = $infosAuthorComments->getUsername();
                    //     $comment->setCreated_By($authorName);
                    // }
                    $this->sessionModel->set('post', $thisPost);
                    $this->sessionModel->set('authorPost', $infosAuthorPost->getUsername());
                    $this->sessionModel->set('comments', $comments["comments"]);
                    $this->sessionModel->set('authorComments', $comments["authors"]);
                    $this->sessionModel->set('message', "Voici le récit que vous souhaitiez ");
                } else {
                    $this->sessionModel->set('error_message', "Nous n'avons pas pu accéder à ce post");
                    header('Location: error_404');
                }
            }
        } catch (Exception $e) {
            $this->sessionModel->set('message', $e->getMessage());
            // Redirection vers le post
            header('Location: error_404');
        }
    }

    public function seeAllPosts()
    {
        $allPosts = $this->postModel->getAllPosts();
        foreach ($allPosts as $post) {
            $authorID = $post->getCreated_By();
            $infosAuthor = $this->userModel->read($authorID);
            $post->setCreated_By($infosAuthor->getUsername());
        }
        $this->sessionModel->set('message', "Cher visiteur, quel récit souhaitez-vous découvrir parmi nos derniers chroniques publiés ? ");
        $this->sessionModel->set('posts', $allPosts);
        // header('Location: posts');
    }

    public function createPost()
    {
        if (!$this->authModel->isLoggedIn()) {
            $this->sessionModel->set('message', "Authentification échouée");
            header('Location: login');
        } else if (!$this->authModel->isCurrentUserAdmin()) {
            $this->sessionModel->set('message', "Demandée à un administrateur pour accéder à cette pages");
            header('Location: posts');
        } else {
            $this->sessionModel->set('message', "Ô noble Administrateur, quelle histoire allez-vous donc nous conter ? ");
            header('Location: admin_create_post');
        }
    }

    public function addPost()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifier si un fichier image a été téléchargé avec succès
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {


                // Récupération du nom du fichier 
                $fileName = $_FILES['image']['name'];

                // Accès au Chemin temporaire du fichier téléchargé
                $tmpFilePath = $_FILES['image']['tmp_name'];
                // Déplacer le fichier temporaire vers l'emplacement souhaité
                $destinationPath = 'public/assets/img/' . $fileName;
                // var_dump($destinationPath);
                // die;


                $save_File = move_uploaded_file($tmpFilePath, $destinationPath);

                if ($save_File == true) {
                    // L'enregistrement du fichier a été réussi

                    // On continuer avec le reste du traitement
                    $title =  ucfirst(strtolower(htmlspecialchars($_POST['title'])));
                    $image =  $destinationPath; // Utiliser le chemin du fichier enregistré
                    $created_by = $this->sessionModel->get('userID');
                    $lead_sentence = htmlspecialchars($_POST['leadSentence']);
                    $content = htmlspecialchars($_POST['content']);

                    $this->postModel->create($title, $image, $created_by, $lead_sentence, $content);

                    $this->sessionModel->set('message', "Le post a été enregistré avec succès");
                    header('Location: admin');
                } else {
                    // Une erreur s'est produite lors de l'enregistrement du fichier
                    $this->sessionModel->set('message', "Une erreur s'est produite lors de l'enregistrement de l'image");
                    header('Location: admin');
                }
            } else {
                // Une erreur s'est produite lors du téléchargement du fichier image
                $this->sessionModel->set('message', "Une erreur s'est produite lors du téléchargement de l'image");
                header('Location: admin');
            }
        }
    }
}
