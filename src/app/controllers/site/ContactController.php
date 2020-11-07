<?php

namespace App\Controller\Site;

use App\Components\Controller;

class ContactController extends Controller
{
    public function index()
    {
        $data['page'] = 'contact';
        $this->view->generate('/site/contact.twig', $data);
    }
}
