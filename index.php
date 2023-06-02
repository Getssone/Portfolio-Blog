<?php
require_once './vendor/autoload.php';

session_start();


// use Twig\TwigFilter;
// use PDO;
// use Twig\TwigFunction;
use Twig\TwigFilter;
use Twig\Environment;
use Twig\TwigFunction;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;
use App\Controller\EmailController;



// var_dump(basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))); 
//die;

//Récupère les derniers repas
function repas()
{
    $pdo = new PDO('mysql:dbname=test_exo_partage_de_recette;host=localhost', 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $repas = $pdo->query(" SELECT * FROM recipes ");
    return $repas;
}


//rendu template

$loader = new FilesystemLoader(__DIR__ . "/App/View");
$twig = new Environment($loader, [
    'cache' => false, //__DIR__ .'./Tmp',
    'debug' => true,
]);

$twig->addExtension(new DebugExtension); // permet d'utiliser dump() = var_dump() qui lui n'est pas accessible dans twig


/** Extension de TWIG via une function */
// $twig->addFunction(new TwigFunction('soustraction', function ($value1, $value2) {
//     return "salut je suis une fonction qui soustrait : $value1 - $value2 = " . $value1 - $value2;
// }));


/** Extension de TWIG via un Filtre */
// $twig->addFilter(new TwigFilter('textadd', function ($value1) {
//     return "salut je suis un filtre " . $value1;
// }, ["is_safe" => ["html"]]));

/** Routing */

//Permet de vérifier l'url ex: http://localhost/P5/Code_p5/?p=home
if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 'signIn';
}


/* Ne peut être déplacé si non bug */
switch ($page) {
    case 'signIn':
        echo $twig->render('sign_In.twig');
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

        // Afficher la vue
        echo $twig->render('sign_In.twig');
        break;

    case 'login':
        echo $twig->render('login.twig');
        break;
    case 'contact':
        echo $twig->render('contact.twig');
        break;
    case 'CGU':
        echo $twig->render('CGU.twig');
        break;

    case 'articles':
        echo $twig->render('articles.twig', ["user" => ["name" => "Solis", "alias" => "Getssone", "role" => 0,], 'repas' => repas()]);
        break;
    case 'aboutme':
        echo $twig->render('aboutme.twig');
        break;
    case 'post':
        echo $twig->render('post.twig', [
            "user" => ["name" => "Solis", "alias" => "Getssone", "picture" => "public\assets\img\Accueil.jpg", "role" => 1,], "post" => ["title" => "Welcome in my World", "date_of_publication" => "06-05-1994", "picture" => "public\assets\img\post-bg.jpg", "summary" => "I was born in a small village in France called Boulieu.

        It's a very pleasant village, but also full of history, as it's a fortified town. 
        
        I spent my childhood in this village where ...", "content" => "The Article
        Never in all their history have men been able truly to conceive of the world as one: a single sphere, a globe, having the qualities of a globe, a round earth in which all the directions eventually meet, in which there is no center because every point, or none, is center — an equal earth which all men occupy as equals. The airman's earth, if free men make it, will be truly round: a globe in practice, not in theory.
        <h2 class='section-heading'>The Final Frontier</h2>
        <p>There can be no thought of finishing for ‘aiming for the stars.’ Both figuratively and literally, it is a task to occupy the generations. And no matter how much progress one makes, there is always the thrill of just beginning.</p>
        <blockquote class='blockquote'>The dreams of yesterday are the hopes of today and the reality of tomorrow. Science has not yet mastered prophecy. We predict too much for the next year and yet far too little for the next ten.</blockquote>
        <a href='#!'><img class='img-fluid' src='public\assets\img\post-sample-image.jpg' alt='...'></a>
        <span class='caption text-muted'>To go places and do things that have never been done before – that’s what living is all about.</span>
        <p>
						Placeholder text by
						<a href='http://spaceipsum.com/'>Space Ipsum</a>
						· Images by
						<a href='https://www.flickr.com/photos/nasacommons/'>NASA on The Commons</a>
					</p>
        ",],
            "comments" => [["id" => 1, "author" => "Toto", "content" => "Very interesting", "date" => "24-05-2023", "picture" => "public\assets\img\CGU.jpg",], ["id" => 2, "author" => "Titi", "content" => "Thank you for every thing", "date" => "24-05-2023", "picture" => "public\assets\img\Accueil.jpg",]]
        ]);
        break;
    case ':id/post':
        echo $twig->render('new_post.twig', ["user" => ["name" => "Solis", "alias" => "Getssone"]]);
        break;
    case 'new_post':
        echo $twig->render('new_post.twig', ["user" => ["name" => "Solis", "alias" => "Getssone"]]);
        break;
        // case 'edit_comment':
        //     echo $twig->render('edit_comment.twig', [
        //         "user" => ["name" => "Solis", "alias" => "Getssone", "picture" => "public\assets\img\Accueil.jpg", "role" => 1,], "comments" => [["id" => 1, "author" => "Toto", "content" => "Very interesting", "date" => "24-05-2023", "picture" => "public\assets\img\CGU.jpg",], ["id" => 2, "author" => "Titi", "content" => "Thank you for every thing", "date" => "24-05-2023", "picture" => "public\assets\img\Accueil.jpg",]]
        //     ]);
    case 'edit_comment/':
        if (isset($_GET['commentId'])) {
            var_dump($PHP_SELF . "?" . $_SERVER['QUERY_STRING']);
            die;
            $commentId = $_GET['commentId'];
            echo $twig->render('edit_comment.twig', [
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
