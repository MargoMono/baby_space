<?php

namespace App\Controllers\Site;

use App\Controllers\Controller;

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
