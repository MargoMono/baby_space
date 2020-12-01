<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\Admin\TurboModel;
use App\Middleware\AdminAuthenticationChecking;

class TurboController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new TurboModel();
    }

    public function actionIndex()
    {
        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();

        echo $this->model->getIndexData();
        exit();
    }
}
