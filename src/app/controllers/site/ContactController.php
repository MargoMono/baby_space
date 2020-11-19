<?php

namespace App\Controllers\Site;

use App\Controllers\Controller;

class ContactController extends Controller
{
    public function index()
    {
        $data['page'] = 'contact';
        $this->view->generate('/site/contact.twig', $data);
    }
}
