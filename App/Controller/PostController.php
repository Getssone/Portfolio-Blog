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
        $this->sessionModel = $sessionModel;
        $this->authModel = new AuthModel();
        $this->user = $this->authModel->getCurrentUser();
        $this->postModel = new PostModel($sessionModel);
        $this->userModel = new UserModel($sessionModel);
        $this->commentController = new CommentController($this->sessionModel);
    }


    public function deletePostID()
    {
        try {
            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'GET') {
                if (isset($_GET['id'])) {
                    $postId = filter_input(INPUT_GET, 'id', FILTER_DEFAULT);
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
            }
        } catch (Exception $e) {
            $this->sessionModel->set('message', $e->getMessage());
            header('Location: admin');
        }
    }
    public function updatePostID()
    {
        try {
            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['postId'])) {

                    $postId = $_POST['postId'];

                    if (isset($postId) && !empty($postId)) {
                        $id =  ucfirst(strtolower(htmlspecialchars($_POST['postId'])));

                        $title =  ucfirst(strtolower(htmlspecialchars($_POST['title'])));

                        $leadSentence = htmlspecialchars($_POST['lead_sentence']);

                        $content = htmlspecialchars($_POST['content']);
                        $this->postModel->update($id, $title, $content, $leadSentence);

                        $this->sessionModel->set('message', "Le post a été enregistré avec succès");
                    } else {

                        $this->sessionModel->set('message', "Une erreur s'est produite lors de l'enregistrement");
                        header('Location: admin');
                    }
                }
            }
        } catch (Exception $e) {
            $this->sessionModel->set('message', $e->getMessage());

            header('Location: error_404');
        }
    }

    public function seePostID()
    {
        try {
            if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] ===  'GET') {
                if (isset($_GET['id'])) {

                    $postId = filter_input(INPUT_GET, 'id', FILTER_DEFAULT);

                    if (isset($postId) && !empty($postId)) {
                        $thisPost = $this->postModel->getPost($postId);
                        $idAuthorPost = $thisPost->getCreated_By();
                        $infosAuthorPost = $this->userModel->read($idAuthorPost);
                        $comments = $this->commentController->getCommentsWithAuthors();
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
            }
        } catch (Exception $e) {
            $this->sessionModel->set('message', $e->getMessage());
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
    }

    public function createPost()
    {
        try {
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
        } catch (\Exception $e) {
            $this->sessionModel->set('message', "Une erreur s'est produite. Veuillez réessayer plus tard.");
            header('Location: posts');
        }
    }

    public function addPost()
    {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_FILES['image']) && isset($_FILES['image']['error'])) {
                if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    if (isset($_FILES['image']['name'])) {
                        $fileName = $_FILES['image']['name'];
                    } else {
                        $defaultFileName = 'default_image.jpg';
                        $fileName = $defaultFileName;
                    }

                    $tmpFilePath = $_FILES['image']['tmp_name'];
                    $destinationPath = 'public/assets/img/' . $fileName;


                    $save_File = move_uploaded_file($tmpFilePath, $destinationPath);
                    if ($save_File == true) {

                        $title = ucfirst(strtolower(filter_var($_POST['title'], FILTER_DEFAULT)));
                        $image = $destinationPath;
                        $created_by = $this->sessionModel->get('userID');
                        $lead_sentence = filter_var(htmlspecialchars($_POST['leadSentence']), FILTER_DEFAULT);
                        $content = filter_var(htmlspecialchars($_POST['content']), FILTER_DEFAULT);

                        $this->postModel->create($title, $image, $created_by, $lead_sentence, $content);

                        $this->sessionModel->set('message', "Le post a été enregistré avec succès");
                        header('Location: admin');
                    } else {
                        $this->sessionModel->set('message', "Une erreur s'est produite lors de l'enregistrement de l'image");
                        header('Location: admin');
                    }
                } else {
                    $this->sessionModel->set('message', "Une erreur s'est produite lors du téléchargement de l'image");
                    header('Location: admin');
                }
            } else {
                $this->sessionModel->set('message', "Le fichier n'a pas été téléchargé");
                header('Location: admin');
            }
        }
    }
}
