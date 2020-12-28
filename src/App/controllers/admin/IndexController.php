<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\AdminModel;
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
