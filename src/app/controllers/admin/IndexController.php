<?php

namespace App\Controller\Admin;

use App\Components\Controller;
use App\Model\AdminModel;
use App\Middleware\AdminAuthenticationChecking;

class IndexController extends Controller
{
    public function actionIndex()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        $this->view->generate('admin/index.twig');
    }

}
