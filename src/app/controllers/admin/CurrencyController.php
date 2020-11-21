<?php

namespace App\Controllers\Admin;

use App\Middleware\AdminAuthenticationChecking;
use App\Models\Admin\ModelContext;
use App\Models\Admin\CurrencyStrategy;

class CurrencyController implements ControllerStrategy
{
    private $controllerContext;

    private $directory = 'currency';

    public function __construct()
    {
        $this->controllerContext = new ControllerContext(new CurrencyStrategy(),
            new ModelContext(new CurrencyStrategy()), $this->directory);

        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();
    }

    public function actionIndex()
    {
        $this->controllerContext->actionIndex();
    }

    public function actionShowCreatePage()
    {
        $this->controllerContext->actionShowCreatePage();
    }

    public function create()
    {
        $this->controllerContext->create();
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
        $this->controllerContext->actionShowDeletePage($id);
    }

    public function delete()
    {
        $this->controllerContext->delete();
    }
}
