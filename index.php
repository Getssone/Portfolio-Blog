<?php

// use Twig\TwigFilter;
use Twig\Environment;
// use Twig\TwigFunction;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;

require_once './vendor/autoload.php';

//Routing
$page = "home";

$segments = explode('/', $_SERVER['REQUEST_URI']);
var_dump($segments[3]);
die;
//Permet de vérifier l'url ex: http://localhost/P5/Code_p5/?p=home
if (isset($_GET["p"])) {
    $page = $_GET["p"];
}
// var_dump($page);
// var_dump(basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))); 

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

/* Ne peut être déplacé si non bug */
switch ($page) {
    case 'contact':
        echo $twig->render('contact.twig');
        break;

    case 'home':
        echo $twig->render('home.twig', ["user" => ["name" => "Solis", "alias" => "Getssone"], 'repas' => repas()]);
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
