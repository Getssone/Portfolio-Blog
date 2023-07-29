<?php
require_once './vendor/autoload.php';
require_once 'App/Service/DatabaseConnection.php';
require_once 'App/Service/TwigRenderer.php';



session_start();

// use Twig\TwigFilter;
// use PDO;
// use Twig\TwigFunction;

use App\Controller\SignInController;
use App\Controller\UserController;
use App\Controller\PostController;
use App\Controller\CommentController;
use App\Controller\EmailController;
use App\Model\EmailModel;
use Twig\TwigFilter;
use Twig\TwigFunction;
use App\Model\SessionModel;
use App\Model\TwigRenderer;
// use App\Controller\EmailController;
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
        exit();
        break;

    case 'signInAction':
        $signInController = new SignInController($sessionModel);
        $signInController->signIn();
        exit();
        break;

    case 'login':
        echo $twig->render('login.twig', ['message' => $message]);
        exit();
        break;

    case 'logInAction':
        $userController = new UserController($sessionModel);
        $userController->connect($_POST['email'], $_POST['password']);
        exit();
        break;

    case 'logOut':
        $userController = new UserController($sessionModel);
        $userController->logoutUser();
        echo $twig->render('login.twig');
        exit();
        break;

    case 'postsAccess':
        $postController = new PostController($sessionModel);
        $postController->seeAllPosts();
        header("Location: posts");
        exit();
        break;


    case 'posts': //Tout les posts
        $user = $sessionModel->get('user');
        $posts = $sessionModel->get('posts');
        echo $twig->render('posts.twig', ["user" => $user, 'posts' => $posts]);
        exit();
        break;

    case 'postAccess': // Vérification si le Post existe
        // var_dump("postAccess");
        // var_dump($_GET['id']);
        // die;
        $location = $_GET['location'];
        $postController = new PostController($sessionModel);
        $postController->seePostID();
        header("Location: $location");
        exit();
        break;

    case 'post': // 1 Post
        $user = $sessionModel->get('user');
        $post = $sessionModel->get('post');
        $authorPost = $sessionModel->get('authorPost');
        $authorComments = $sessionModel->get('authorComments');
        $comments = $sessionModel->get('comments');
        $messageComment = $sessionModel->get('messageComment');
        $sessionModel->deleteKey('messageComment');
        // Envoyé uniquement quand le page "post/add-comment" à validé l'enregistrement

        echo $twig->render('post.twig', [
            'user' => $user,
            'message' => $message, //Appeler tous en haut de l'index.php
            "post" => $post,
            "authorPost" => $authorPost,
            "authorComments" => $authorComments,
            "comments" => $comments,
            "messageComment" => $messageComment,
        ]);
        exit();
        break;

    case 'post/add-comment':

        $commentController = new CommentController($sessionModel);
        $commentController->registerComment();
        exit();
        break;

    case 'aboutme':
        $user = $sessionModel->get('user');
        echo $twig->render('aboutme.twig', ["user" => $user]);
        exit();
        break;

    case 'contact':
        $user = $sessionModel->get('user');
        echo $twig->render('contact.twig', ["user" => $user, 'message' => $message]);
        exit();
        break;
    case 'contactSendMail':
        $user = $sessionModel->get('user');
        $sendEmail = new EmailController($sessionModel);
        $sendEmail->sendMailViaContact();
        exit();
        break;

    case 'CGU':
        $user = $sessionModel->get('user');
        echo $twig->render('CGU.twig', ["user" => $user]);
        exit();
        break;

    case 'profile':
        $user = $sessionModel->get('user');
        echo $twig->render('profile.twig', ["user" => $user,]);
        exit();
        break;

    case 'admin_create_post_Action':
        $postController = new PostController($sessionModel);
        $postController->createPost();
        exit();
        break;

    case 'admin_create_post':
        $user = $sessionModel->get('user');
        echo $twig->render('admin_create_post.twig', ["user" => $user, 'message' => $message]);
        exit();
        break;

    case 'admin_add_post_Action':
        $postController = new PostController($sessionModel);
        $postController->addPost();
        exit();
        break;
    case 'admin':
        $user = $sessionModel->get('user');
        // var_dump($user);
        // die;
        echo $twig->render('admin.twig', ["user" => $user, 'message' => $message]);
        exit();
        break;

    case 'adminShowPostsAccess':
        $postController = new PostController($sessionModel);
        $postController->seeAllPosts();
        header("Location: admin_show_posts");
        exit();
        break;

    case 'admin_show_posts':
        $user = $sessionModel->get('user');
        $posts = $sessionModel->get('posts');
        // var_dump($user);
        // die;
        echo $twig->render('admin_show_posts.twig', [
            'user' => $user,
            'posts' => $posts,
            'message' => $message
        ]);
        exit();
        break;

    case 'adminEditPostAccess':
        // var_dump("adminEditPostAccess");
        // var_dump($_GET['id']);
        // die;
        $postController = new PostController($sessionModel);
        $postController->seePostID();
        header("Location: admin_edit_post");
        exit();
        break;

    case 'admin_edit_post':
        $user = $sessionModel->get('user');
        $post = $sessionModel->get('post');
        $authorPost = $sessionModel->get('authorPost');
        // Envoyé uniquement quand le page "post/add-comment" à validé l'enregistrement

        echo $twig->render('admin_edit_post.twig', [
            'user' => $user,
            'message' => $message, //Appeler tous en haut de l'index.php
            "post" => $post,
            "authorPost" => $authorPost,
        ]);
        exit();
        break;

    case 'adminEditPostSuccess':
        // var_dump("adminEditPostSuccess");
        $postController = new PostController($sessionModel);
        $postController->updatePostID();
        header("Location: admin");
        exit();
        break;


    case 'admin_delete_post_access': // Vérification si le Post existe
        // var_dump($_GET['id']);
        // die;
        $postController = new PostController($sessionModel);
        $postController->deletePostID();
        exit();
        break;

    case 'admin_delete_post':
        $user = $sessionModel->get('user');
        $post = $sessionModel->get('post');
        $authorPost = $sessionModel->get('authorPost');

        echo $twig->render('admin_delete_post.twig', [
            'user' => $user,
            "post" => $post,
            "authorPost" => $authorPost
        ]);
        break;



    case 'admin_pending_comments_access':
        $commentController = new CommentController($sessionModel);
        $commentController->getAllApprovedComments();
        $commentController->getAllPendingComments();
        $commentController->getAllRejectedComments();
        header('Location: admin_pending_comments');
        exit();
        break;

    case 'admin_pending_comments':
        $user = $sessionModel->get('user');
        $commentsPending = $sessionModel->get('commentsPending');
        $commentsApproved = $sessionModel->get('commentsApproved');
        $commentsRejected = $sessionModel->get('commentsRejected');
        echo $twig->render('admin_pending_comments.twig', [
            'user' => $user,
            'commentsPending' => $commentsPending,
            'commentsApproved' => $commentsApproved,
            'commentsRejected' => $commentsRejected,
        ]);
        break;

    case 'adminUpdateStatusComment':
        // var_dump("adminUpdateStatusComment");
        $commentController = new CommentController($sessionModel);
        $commentController->updateCommentStatus();
        header("Location: admin");
        exit();
        break;

    case 'adminShowUsersAccess':
        // var_dump("adminShowUsersAccess");
        // die;
        $userController = new UserController($sessionModel);
        $userController->seeAllUsers();
        header("Location: admin_show_users");
        exit();
        break;

    case 'admin_show_users':
        $user = $sessionModel->get('user');
        $users = $sessionModel->get('users');
        echo $twig->render('admin_show_users.twig', [
            'user' => $user,
            "users" => $users
        ]);
        break;

    case 'adminDeleteUserAccess':
        $userController = new UserController($sessionModel);
        $userController->seeUserID();
        header("Location: admin_delete_user");
        exit();
        break;

    case 'admin_delete_user':
        $user = $sessionModel->get('user');
        $userToDeleted = $sessionModel->get('userToDeleted');
        echo $twig->render('admin_delete_user.twig', [
            'user' => $user,
            "userToDeleted" => $userToDeleted
        ]);
        break;

    case 'adminDeletedUserSuccess':
        // var_dump("adminEditPostSuccess");
        $userController = new UserController($sessionModel);
        $userController->deletedUser();
        header("Location: admin");
        exit();
        break;

    case 'adminUpdateRoleUser':
        var_dump("adminUpdateRoleUser");
        $userController = new UserController($sessionModel);
        $userController->updateRole();
        header("Location: admin");
        exit();
        break;



    case 'error_404':
        $user = $sessionModel->get('user');
        echo $twig->render('404.twig', ['user' => $user]);
        exit();
        break;

    default:
        header("Location: error_404");
}
