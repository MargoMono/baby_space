<?php

namespace App\Controllers\Site;

class ContactController
{
    private $directory = '';
    private $controllerContext;

    public function __construct()
    {
        $this->controllerContext = new ControllerContext($this->directory);
    }

    public function index()
    {
        $this->controllerContext->render([], 'contact.twig');
    }
}
