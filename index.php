<?php
require_once './vendor/autoload.php';


use App\Service\DatabaseConnection;
use App\Service\TwigRenderer;

use App\Controller\SignInController;
use App\Controller\UserController;
use App\Controller\PostController;
use App\Controller\CommentController;
use App\Controller\EmailController;
use App\Model\EmailModel;
use Twig\TwigFilter;
use Twig\TwigFunction;
use App\Model\SessionModel;

/** rendu template */

$twigRenderer = new TwigRenderer();
$twig = $twigRenderer->getTwig();




/** Extension de TWIG via une function */


/** Extension de TWIG via un Filtre */



/** Routing */

$sessionModel = new SessionModel();
$message = $sessionModel->get('message');
$sessionModel->deleteKey('message');

$databaseConnection = new DatabaseConnection($sessionModel);

if (isset($_GET["page"])) {
    $page = htmlspecialchars(($_GET["page"]));
} else {
    $page = 'postsAccess';
}
switch ($page) {
    case 'signIn':
        $twig->display('sign_In.twig', ['message' => $message]);
        break;

    case 'signInAction':
        $signInController = new SignInController($sessionModel);
        $signInController->signIn();
        break;

    case 'login':
        $twig->display('login.twig', ['message' => $message]);
        break;

    case 'logInAction':
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $userController = new UserController($sessionModel);
            $userController->connect($_POST['email'], $_POST['password']);
        } else {
            header('Location: login');
        }
        break;

    case 'logOut':
        $userController = new UserController($sessionModel);
        $userController->logoutUser();
        $twig->display('login.twig');
        break;

    case 'postsAccess':
        $postController = new PostController($sessionModel);
        $postController->seeAllPosts();
        header("Location: posts");
        break;


    case 'posts':
        $user = $sessionModel->get('user');
        $posts = $sessionModel->get('posts');
        $twig->display('posts.twig', ["user" => $user, 'posts' => $posts]);
        break;

    case 'postAccess':
        if (isset($_GET['location'])) {
            $location = $_GET['location'];
            $postController = new PostController($sessionModel);
            $postController->seePostID();
            header("Location: $location");
        } else {
            $postController = new PostController($sessionModel);
            $postController->seePostID();
            header("Location: posts");
        }
        break;

    case 'post':
        $user = $sessionModel->get('user');
        $post = $sessionModel->get('post');
        $authorPost = $sessionModel->get('authorPost');
        $authorComments = $sessionModel->get('authorComments');
        $comments = $sessionModel->get('comments');
        $messageComment = $sessionModel->get('messageComment');
        $sessionModel->deleteKey('messageComment');

        $twig->display('post.twig', [
            'user' => $user,
            'message' => $message,
            "post" => $post,
            "authorPost" => $authorPost,
            "authorComments" => $authorComments,
            "comments" => $comments,
            "messageComment" => $messageComment,
        ]);
        break;

    case 'post/add-comment':

        $commentController = new CommentController($sessionModel);
        $commentController->registerComment();
        break;

    case 'aboutme':
        $user = $sessionModel->get('user');
        $twig->display('aboutme.twig', ["user" => $user]);
        break;

    case 'contact':
        $user = $sessionModel->get('user');
        $twig->display('contact.twig', ["user" => $user, 'message' => $message]);
        break;
    case 'contactSendMail':
        $user = $sessionModel->get('user');
        $sendEmail = new EmailController($sessionModel);
        $sendEmail->sendMailViaContact();
        break;

    case 'CGU':
        $user = $sessionModel->get('user');
        $twig->display('CGU.twig', ["user" => $user]);
        break;

    case 'profile':
        $user = $sessionModel->get('user');
        $twig->display('profile.twig', ["user" => $user,]);
        break;

    case 'admin_create_post_Action':
        $postController = new PostController($sessionModel);
        $postController->createPost();
        break;

    case 'admin_create_post':
        $user = $sessionModel->get('user');
        $twig->display('admin_create_post.twig', ["user" => $user, 'message' => $message]);
        break;

    case 'admin_add_post_Action':
        $postController = new PostController($sessionModel);
        $postController->addPost();
        break;
    case 'admin':
        $user = $sessionModel->get('user');
        $twig->display('admin.twig', ["user" => $user, 'message' => $message]);
        break;

    case 'adminShowPostsAccess':
        $postController = new PostController($sessionModel);
        $postController->seeAllPosts();
        header("Location: admin_show_posts");
        break;

    case 'admin_show_posts':
        $user = $sessionModel->get('user');
        $posts = $sessionModel->get('posts');
        $twig->display('admin_show_posts.twig', [
            'user' => $user,
            'posts' => $posts,
            'message' => $message
        ]);
        break;

    case 'adminEditPostAccess':
        $postController = new PostController($sessionModel);
        $postController->seePostID();
        header("Location: admin_edit_post");
        break;

    case 'admin_edit_post':
        $user = $sessionModel->get('user');
        $post = $sessionModel->get('post');
        $authorPost = $sessionModel->get('authorPost');

        $twig->display('admin_edit_post.twig', [
            'user' => $user,
            'message' => $message,
            "post" => $post,
            "authorPost" => $authorPost,
        ]);
        break;

    case 'adminEditPostSuccess':
        $postController = new PostController($sessionModel);
        $postController->updatePostID();
        header("Location: admin");
        break;


    case 'admin_delete_post_access':
        $postController = new PostController($sessionModel);
        $postController->deletePostID();
        break;

    case 'admin_delete_post':
        $user = $sessionModel->get('user');
        $post = $sessionModel->get('post');
        $authorPost = $sessionModel->get('authorPost');

        $twig->display('admin_delete_post.twig', [
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
        break;

    case 'admin_pending_comments':
        $user = $sessionModel->get('user');
        $commentsPending = $sessionModel->get('commentsPending');
        $commentsApproved = $sessionModel->get('commentsApproved');
        $commentsRejected = $sessionModel->get('commentsRejected');
        $twig->display('admin_pending_comments.twig', [
            'user' => $user,
            'commentsPending' => $commentsPending,
            'commentsApproved' => $commentsApproved,
            'commentsRejected' => $commentsRejected,
        ]);
        break;

    case 'adminUpdateStatusComment':
        $commentController = new CommentController($sessionModel);
        $commentController->updateCommentStatus();
        header("Location: admin");
        break;

    case 'adminShowUsersAccess':
        $userController = new UserController($sessionModel);
        $userController->seeAllUsers();
        header("Location: admin_show_users");
        break;

    case 'admin_show_users':
        $user = $sessionModel->get('user');
        $users = $sessionModel->get('users');
        $twig->display('admin_show_users.twig', [
            'user' => $user,
            "users" => $users
        ]);
        break;

    case 'adminDeleteUserAccess':
        $userController = new UserController($sessionModel);
        $userController->seeUserID();
        header("Location: admin_delete_user");
        break;

    case 'admin_delete_user':
        $user = $sessionModel->get('user');
        $userToDeleted = $sessionModel->get('userToDeleted');
        $twig->display('admin_delete_user.twig', [
            'user' => $user,
            "userToDeleted" => $userToDeleted
        ]);
        break;

    case 'adminDeletedUserSuccess':
        $userController = new UserController($sessionModel);
        $userController->deletedUser();
        header("Location: admin");
        break;

    case 'adminUpdateRoleUser':
        var_dump("adminUpdateRoleUser");
        $userController = new UserController($sessionModel);
        $userController->updateRole();
        header("Location: admin");
        break;



    case 'error_404':
        $user = $sessionModel->get('user');
        $twig->display('404.twig', ['user' => $user]);
        break;

    default:
        header("Location: error_404");
}
