<?php

namespace App\Model;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;

/**
 * Parent of all controllers
 */
class TwigRenderer
{
    /**
     * @var FilesystemLoader
     */
    private $loader;

    /**
     *
     * @var Environment
     */
    private $twigEnvironment;

    public function __construct()
    {
        //* rendu template */

        $this->loader = new FilesystemLoader("App/View"); // identique au code ci dessous, il spécifiez le répertoire contenant les templates


        // $this->loader = new FilesystemLoader(dirname(__DIR__) . "/View");// identique au code ci dessus

        $this->twigEnvironment = new Environment($this->loader, [
            'cache' => false, //__DIR__ .'./Tmp',
            'debug' => true,
        ]);

        $this->twigEnvironment->addExtension(new DebugExtension()); // permet d'utiliser dump() = var_dump() qui lui n'est pas accessible dans twig

    }
    public function getTwig()
    {
        return $this->twigEnvironment;
    }
}
