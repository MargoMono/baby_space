<?php

namespace App\View;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View
{
    private $defaultData;

    public function __construct($defaultData = null)
    {
        $this->defaultData = $defaultData;
    }

    function generate($templateName, $data = [])
    {
        $loader = new FilesystemLoader('assets/views');

        $twig = new Environment($loader, []);

        $template = $twig->load($templateName);

        echo $template->render($data);
    }


    function generateEmail($templateName, $data = [])
    {
        $loader = new FilesystemLoader('assets/emails');

        $twig = new Environment($loader, []);

        $template = $twig->load($templateName);

        return $template->render($data);
    }
}
