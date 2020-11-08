<?php

namespace App\Controller\Site;

use App\Controller\Controller;


class MailController extends Controller
{
    public function getTemplate($template, $data)
    {
        return $this->view->generateEmail($template, $data);
    }
}