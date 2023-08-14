<?php

namespace App\Service;

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

        $this->loader = new FilesystemLoader("App/View");

        $this->twigEnvironment = new Environment($this->loader, [
            'cache' => false,
            'debug' => true,
            'filters' => ['escape' => ['twig', 'escape']],
        ]);

        $this->twigEnvironment->addExtension(new DebugExtension());
    }
    public function getTwig()
    {
        return $this->twigEnvironment;
    }
}
