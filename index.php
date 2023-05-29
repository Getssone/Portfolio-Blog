<?php
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

require_once './vendor/autoload.php';


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
        // echo $twig->render('sign_In.twig');
        // break;

        $emailController = new EmailController;

        // Vérifier la méthode HTTP
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            // Envoyer l'email
            $emailSent = $emailController->envoieMail($email, $password, $confirmPassword);

            if ($emailSent) {
                // Email envoyé avec succès
                $_SESSION['message'] = 'Un mail vous a été envoyé.';
            } else {
                // Erreur lors de l'envoi de l'email
                $_SESSION['message'] = 'Une erreur s\'est produite lors de l\'envoi de l\'email.';
            }

            // Rediriger vers la même page pour afficher le message
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit();
        }

        // Afficher la vue
        echo $twig->render('sign_In.twig');
        break;

    case 'login':
        echo $twig->render('login.twig');
        break;
    case 'contact':
        echo $twig->render('contact.twig');
        break;

    case 'articles':
        echo $twig->render('articles.twig', ["user" => ["name" => "Solis", "alias" => "Getssone"], 'repas' => repas()]);
        break;
    case 'aboutme':
        echo $twig->render('aboutme.twig');
        break;
    case 'post':
        echo $twig->render('post.twig', ["user" => ["name" => "Solis", "alias" => "Getssone"]]);
        break;

    default:
        header('HTTP/1.0 404 Not Found');
        echo $twig->render('404.twig');
        break;
}
