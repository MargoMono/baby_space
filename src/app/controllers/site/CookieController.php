<?php

namespace App\Controller\Site;

use App\Controller\Controller;

class CookieController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function showCookiePage()
    {
        $this->view->generate('/site/cookie.twig', []);
    }
}
