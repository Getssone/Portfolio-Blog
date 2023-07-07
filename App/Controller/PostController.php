<?php

namespace App\Controller;

use Exception;
use App\Model\AuthModel;
use App\Model\PostModel;
use App\Model\UserModel;
use App\Model\SessionModel;

class PostController
{
    private $sessionModel;
    private $authModel;
    private $user;
    private $postModel;
    private $userModel;



    public function __construct(SessionModel $sessionModel)
    {
        $this->sessionModel = $sessionModel; //récupéré via le rooter
        $this->authModel = new AuthModel();
        $this->user = $this->authModel->getCurrentUser();
        $this->postModel = new PostModel();
        $this->userModel = new UserModel();
    }


    public function seeAllPosts()
    {
        $allPosts = $this->postModel->getAllPosts();
        foreach ($allPosts as $post) {
            $authorID = $post->getCreated_By();
            $author = $this->userModel->read($authorID);
            $post->setCreated_By($author->getUsername());
        }
        // var_dump($allPosts);
        // die;
        $this->sessionModel->set('message', "Cher visiteur, quel récit souhaitez-vous découvrir parmi nos derniers chroniques publiés ? ");
        $this->sessionModel->set('posts', $allPosts);
        // var_dump($this->sessionModel->get('posts'));
        // die;
        header('Location: posts');
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
                    exit;
                }
            } else {
                // Une erreur s'est produite lors du téléchargement du fichier image
                $this->sessionModel->set('message', "Une erreur s'est produite lors du téléchargement de l'image");
                header('Location: admin');
                exit;
            }
        }
    }

    public function profil()
    {
    }
}
