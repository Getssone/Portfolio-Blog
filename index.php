<?php
require_once './vendor/autoload.php';
require_once 'App/Service/DatabaseConnection.php';
require_once 'App/Service/TwigRenderer.php';



session_start();

// use Twig\TwigFilter;
// use PDO;
// use Twig\TwigFunction;
use Twig\TwigFilter;
use Twig\TwigFunction;
use App\Model\SessionModel;
use App\Model\TwigRenderer;
use App\Controller\EmailController;
use App\Controller\SignInController;
use App\Service\DatabaseConnection\DatabaseConnection;


// var_dump(basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))); 
//die;

$databaseConnection = new DatabaseConnection();
$posts = $databaseConnection->getAllPosts();

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
    $page = 'signIn';
}


/* Ne peut être déplacé si non bug */
switch ($page) {
    case 'signIn':
        echo $twig->render('sign_In.twig', ['message' => $message]);
        break;

    case 'signInAction':
        // var_dump($_POST);
        // die;
        $signInController = new SignInController($sessionModel);
        $signInController->signIn();
        // echo $twig->render('login.twig', ['message' => $message]);
        break;

        // $emailController = new EmailController;

        // // Vérifier la méthode HTTP
        // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //     // Récupérer les données du formulaire
        //     $email = $_POST['email'];
        //     $password = $_POST['password'];
        //     $confirmPassword = $_POST['confirm_password'];

        //     // Envoyer l'email
        //     $emailSent = $emailController->envoieMail($email, $password, $confirmPassword);

        //     if ($emailSent) {
        //         // Email envoyé avec succès
        //         $_SESSION['message'] = 'Un mail vous a été envoyé.';
        //     } else {
        //         // Erreur lors de l'envoi de l'email
        //         $_SESSION['message'] = 'Une erreur s\'est produite lors de l\'envoi de l\'email.';
        //     }

        //     // Rediriger vers la même page pour afficher le message
        //     header('Location: ' . $_SERVER['REQUEST_URI']);
        //     exit();
        // }

    case 'login':
        echo $twig->render('login.twig', ['message' => $message]);
        break;
    case 'contact':
        echo $twig->render('contact.twig');
        break;
    case 'CGU':
        echo $twig->render('CGU.twig');
        break;
    case 'profile':
        echo $twig->render('profile.twig', ["user" => ["name" => "Solis", "username" => "Getssone", "picture" => "public\assets\img\Accueil.jpg", "role" => 0,]]);
        break;

    case 'admin':
        echo $twig->render('admin.twig', ["users" => [["id" => 1, "username" => "Getssone", "email" => "getssone@mailo.com", "first_name" => "Gaëtan", "last_name" => "Solis", "role" => 1,], ["id" => 2, "username" => "TotoLescargot", "email" => "totoLescargot@mailo.com", "first_name" => "Toto", "last_name" => "Lescargot", "role" => 0]]]);
        break;
    case 'admin_show_posts':
        echo $twig->render('admin_show_posts.twig', [
            "users" => [["id" => 1, "username" => "Getssone", "email" => "getssone@mailo.com", "first_name" => "Gaëtan", "last_name" => "Solis", "role" => 1,], ["id" => 2, "username" => "TotoLescargot", "email" => "totoLescargot@mailo.com", "first_name" => "Toto", "last_name" => "Lescargot", "role" => 0]],
            'posts' => $posts
        ]);
        break;
    case 'admin_create_post':
        echo $twig->render('admin_create_post.twig', [
            "users" => [["id" => 1, "username" => "Getssone", "email" => "getssone@mailo.com", "first_name" => "Gaëtan", "last_name" => "Solis", "role" => 1,], ["id" => 2, "username" => "TotoLescargot", "email" => "totoLescargot@mailo.com", "first_name" => "Toto", "last_name" => "Lescargot", "role" => 0]],
            'posts' => $posts
        ]);
        break;
    case 'admin_pending_comments':
        echo $twig->render('admin_pending_comments.twig', [
            "users" => [["id" => 1, "username" => "Getssone", "email" => "getssone@mailo.com", "first_name" => "Gaëtan", "last_name" => "Solis", "role" => 1,], ["id" => 2, "username" => "TotoLescargot", "email" => "totoLescargot@mailo.com", "first_name" => "Toto", "last_name" => "Lescargot", "role" => 0]],
            'posts' => $posts
        ]);
        break;
    case 'admin_show_users':
        echo $twig->render('admin_show_users.twig', [
            "users" => [["id" => 1, "username" => "Getssone", "email" => "getssone@mailo.com", "first_name" => "Gaëtan", "last_name" => "Solis", "role" => 1,], ["id" => 2, "username" => "TotoLescargot", "email" => "totoLescargot@mailo.com", "first_name" => "Toto", "last_name" => "Lescargot", "role" => 0]],
            'posts' => $posts
        ]);
        break;

    case 'posts':
        echo $twig->render('posts.twig', ["user" => ["name" => "Solis", "alias" => "Getssone", "role" => 1,], 'posts' => $posts]);
        break;
    case 'aboutme':
        echo $twig->render('aboutme.twig');
        break;
    case 'post':
        echo $twig->render('post.twig', [
            "author" => ["name" => "Solis", "username" => "Getssone", "picture" => "public\assets\img\Accueil.jpg", "role" => 0,], "post" => ["title" => "Welcome in my World", "date_of_publication" => "06-05-1994", "picture" => "public\assets\img\post-bg.jpg", "summary" => "I was born in a small village in France called Boulieu.

        It's a very pleasant village, but also full of history, as it's a fortified town. 
        
        I spent my childhood in this village where ...", "content" => "The Article
        Never in all their history have men been able truly to conceive of the world as one.
        <h2 class='section-heading'>The Final Frontier</h2>
        <p>There can be no thought of finishing for ‘aiming for the stars.’ Both figuratively and literally, it is a task to occupy the generations...</p>
        <blockquote class='blockquote'>The dreams of yesterday are the hopes of today and the reality of tomorrow.</blockquote>
        <a href='#!'><img class='img-fluid' src='public\assets\img\post-sample-image.jpg' alt='...'></a>
        <span class='caption text-muted'>To go places and do things that have never been done before – that’s what living is all about.</span>
        <p>
						Placeholder text by
						<a href='http://spaceipsum.com/'>Space Ipsum</a>
						· Images by
						<a href='https://www.flickr.com/photos/nasacommons/'>NASA on The Commons</a>
					</p>
        ",],
            "comments" => [["id" => 0, "author" => "Toto", "content" => "Very interesting", "date" => "24-05-2023", "picture" => "public\assets\img\CGU.jpg",], ["id" => 2, "author" => "Titi", "content" => "Thank you for every thing", "date" => "24-05-2023", "picture" => "public\assets\img\Accueil.jpg",]]
        ]);
        break;
    case ':id/post':
        echo $twig->render('post.twig', ["user" => ["name" => "Solis", "alias" => "Getssone"]]);
        break;
    case 'admin_edit_comment':
        if (isset($_GET['commentId'])) {
            var_dump($PHP_SELF . "?" . $_SERVER['QUERY_STRING']);
            die;
            $commentId = $_GET['commentId'];
            echo $twig->render('admin_edit_comment.twig', [
                "user" => ["name" => "Solis", "alias" => "Getssone", "picture" => "public\assets\img\Accueil.jpg", "role" => 1,], "comments" => [["id" => $commentId, "author" => "Toto", "content" => "Very interesting", "date" => "24-05-2023", "picture" => "public\assets\img\CGU.jpg",], ["id" => $commentId, "author" => "Titi", "content" => "Thank you for every thing", "date" => "24-05-2023", "picture" => "public\assets\img\Accueil.jpg",]]
            ]);
            header('Location: /post');
            exit();
        } else {
            // L'ID du commentaire n'est pas spécifié, affichez une erreur ou redirigez l'utilisateur
            header('HTTP/1.0 404 Not Found');
            echo $twig->render('404.twig');
            exit();
        }
        break;


    default:
        header('HTTP/1.0 404 Not Found');
        echo $twig->render('404.twig');
        break;
}
