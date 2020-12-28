<?php

namespace App\Controllers\Site;

use App\Controllers\Controller;


class MailController extends Controller
{
    public function getTemplate($template, $data)
    {
        return $this->view->generateEmail($template, $data);
    }
}