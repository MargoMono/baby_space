<?php

namespace App\Components;

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

        if (!empty($data)) {
            echo $template->render(array_merge($this->defaultData, $data));
            return;
        }

        echo $template->render($this->defaultData);
    }

    function generateAjax($data = [])
    {
        echo json_encode($data);
    }

    function generateEmail($templateName, $data = [])
    {
        $loader = new FilesystemLoader('assets/emails');

        $twig = new Environment($loader, []);

        $template = $twig->load($templateName);

        return $template->render($data);
    }
}
