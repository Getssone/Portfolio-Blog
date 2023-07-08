<?php
require_once './vendor/autoload.php';
require_once 'App/Service/DatabaseConnection.php';
require_once 'App/Service/TwigRenderer.php';



session_start();

// use Twig\TwigFilter;
// use PDO;
// use Twig\TwigFunction;

use App\Controller\PostController;
use Twig\TwigFilter;
use Twig\TwigFunction;
use App\Model\SessionModel;
use App\Model\TwigRenderer;
// use App\Controller\EmailController;
use App\Controller\SignInController;
use App\Controller\UserController;
use App\Service\DatabaseConnection\DatabaseConnection;


// var_dump(basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))); 
//die;

$databaseConnection = new DatabaseConnection();
// $posts = $databaseConnection->getAllPosts();

/** //rendu template */

$twigRenderer = new TwigRenderer();
$twig = $twigRenderer->getTwig();




/** Extension de TWIG via une function */
// $twig->addFunction(new TwigFunction('soustraction', function ($value1, $value2) {
//     return "salut je suis une fonction qui soustrait : $value1 - $value2 = " . $value1 - $value2;
// }));


/** Extension de TWIG via un Filtre */
// $twig->addFilter(new TwigFilter('textadd', function ($value1) {
//     return "salut je suis un filtre " . $value1;
// }, ["is_safe" => ["html"]]));



/** Routing */

$sessionModel = new SessionModel();
$message = $sessionModel->get('message');
$sessionModel->deleteKey('message');


//Permet de vérifier l'url ex: http://localhost/P5/Code_p5/?p=home
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 'postsAccess';
}


/* Ne peut être déplacé si non bug */
switch ($page) {
    case 'signIn':
        echo $twig->render('sign_In.twig', ['message' => $message]);
        break;

    case 'signInAction':
        $signInController = new SignInController($sessionModel);
        $signInController->signIn();
        break;

    case 'login':
        echo $twig->render('login.twig', ['message' => $message]);
        break;

    case 'logInAction':
        $userController = new UserController($sessionModel);
        $userController->connect($_POST['email'], $_POST['password']);
        break;

    case 'logOut':
        $userController = new UserController($sessionModel);
        $userController->logoutUser();
        echo $twig->render('login.twig');
        break;

    case 'postsAccess':
        $postController = new PostController($sessionModel);
        $postController->seeAllPosts();


    case 'posts':
        $user = $sessionModel->get('user');
        $posts = $sessionModel->get('posts');
        echo $twig->render('posts.twig', ["user" => $user, 'posts' => $posts]);
        break;

    case 'postAccess':
        $postController = new PostController($sessionModel);
        $postController->seePostID($_GET['id']);
        break;

    case 'post':

        $user = $sessionModel->get('user');
        $post = $sessionModel->get('post');
        // var_dump($user);
        // var_dump($post);
        // die;
        echo $twig->render('post.twig', [
            'user' => $user,
            'message' => $message,
            "author" => [], "post" => $post,
            "comments" => []
        ]);
        break;

    case 'aboutme':
        echo $twig->render('aboutme.twig');
        break;

    case 'contact':
        echo $twig->render('contact.twig');
        break;

    case 'CGU':
        echo $twig->render('CGU.twig');
        break;

    case 'profile':
        $user = $sessionModel->get('user');
        echo $twig->render('profile.twig', ["user" => $user,]);
        break;

    case 'admin_create_post_Action':
        $postController = new PostController($sessionModel);
        $postController->createPost();
        break;

    case 'admin_create_post':
        $user = $sessionModel->get('user');
        echo $twig->render('admin_create_post.twig', ["user" => $user, 'message' => $message]);
        break;

    case 'admin_add_post_Action':
        $postController = new PostController($sessionModel);
        $postController->addPost();
        break;
    case 'admin':
        $user = $sessionModel->get('user');
        // var_dump($user);
        // die;
        echo $twig->render('admin.twig', ["user" => $user, 'message' => $message]);
        break;

        // case 'admin_show_posts':
        //     echo $twig->render('admin_show_posts.twig', [
        //         "users" => [["id" => 1, "username" => "Getssone", "email" => "getssone@mailo.com", "first_name" => "Gaëtan", "last_name" => "Solis", "role" => 1,], ["id" => 2, "username" => "TotoLescargot", "email" => "totoLescargot@mailo.com", "first_name" => "Toto", "last_name" => "Lescargot", "role" => 0]],
        //         'posts' => $posts
        //     ]);
        //     break;



        // case 'admin_pending_comments':
        //     echo $twig->render('admin_pending_comments.twig', [
        //         "users" => [["id" => 1, "username" => "Getssone", "email" => "getssone@mailo.com", "first_name" => "Gaëtan", "last_name" => "Solis", "role" => 1,], ["id" => 2, "username" => "TotoLescargot", "email" => "totoLescargot@mailo.com", "first_name" => "Toto", "last_name" => "Lescargot", "role" => 0]],
        //         'posts' => $posts
        //     ]);
        //     break;

        // case 'admin_show_users':
        //     echo $twig->render('admin_show_users.twig', [
        //         "users" => [["id" => 1, "username" => "Getssone", "email" => "getssone@mailo.com", "first_name" => "Gaëtan", "last_name" => "Solis", "role" => 1,], ["id" => 2, "username" => "TotoLescargot", "email" => "totoLescargot@mailo.com", "first_name" => "Toto", "last_name" => "Lescargot", "role" => 0]],
        //         'posts' => $posts
        //     ]);
        //     break;




        // case 'admin_edit_comment':
        //     if (isset($_GET['commentId'])) {
        //         var_dump($PHP_SELF . "?" . $_SERVER['QUERY_STRING']);
        //         die;
        //         $commentId = $_GET['commentId'];
        //         echo $twig->render('admin_edit_comment.twig', [
        //             "user" => ["name" => "Solis", "alias" => "Getssone", "picture" => "public\assets\img\Accueil.jpg", "role" => 1,], "comments" => [["id" => $commentId, "author" => "Toto", "content" => "Very interesting", "date" => "24-05-2023", "picture" => "public\assets\img\CGU.jpg",], ["id" => $commentId, "author" => "Titi", "content" => "Thank you for every thing", "date" => "24-05-2023", "picture" => "public\assets\img\Accueil.jpg",]]
        //         ]);
        //         header('Location: /post');
        //         exit();
        //     } else {
        //         // L'ID du commentaire n'est pas spécifié, affichez une erreur ou redirigez l'utilisateur
        //         header('HTTP/1.0 404 Not Found');
        //         echo $twig->render('404.twig');
        //         exit();
        //     }
        //     break;


    default:
        header('HTTP/1.0 404 Not Found');
        echo $twig->render('404.twig');
        break;
}
