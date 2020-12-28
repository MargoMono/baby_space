<?php

namespace App\Controllers\Admin;

use App\Middleware\AdminAuthenticationChecking;
use App\Models\Admin\ModelContext;
use App\Models\Admin\PageModel;

class PageController implements ControllerStrategy
{
    private $controllerContext;

    private $directory = 'page';

    public function __construct()
    {
        $this->controllerContext = new ControllerContext(new PageModel(),
            new ModelContext(new PageModel()), $this->directory);

        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();
    }

    public function actionIndex()
    {
        $this->controllerContext->actionIndex();
    }

    public function actionShowCreatePage()
    {
    }

    public function create()
    {
    }

    public function actionShowUpdatePage($id)
    {
        $this->controllerContext->actionShowUpdatePage($id);
    }

    public function update()
    {
        $this->controllerContext->update();
    }

    public function actionShowDeletePage($id)
    {
    }

    public function delete()
    {
    }
}
