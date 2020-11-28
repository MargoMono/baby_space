<?php

namespace App\Controllers\Admin;

use App\Middleware\AdminAuthenticationChecking;
use App\Models\Admin\ModelContext;
use App\Models\Admin\OrderModel;

class OrderController implements ControllerStrategy
{
    private $controllerContext;
    private $model;

    private $directory = 'order';

    public function __construct()
    {
        $this->model = new OrderModel();
        $this->controllerContext = new ControllerContext(new OrderModel(),
            new ModelContext(new OrderModel()), $this->directory);

        $adminAuthenticationChecking = new AdminAuthenticationChecking();
        $adminAuthenticationChecking->handle();
    }

    public function actionIndex()
    {
        $this->controllerContext->actionIndex();
    }

    public function actionShowCreatePage()
    {
        return null;
    }

    public function create()
    {
        return null;
    }

    public function actionShowViewPage($id)
    {
        $this->model->actionShowViewPage($id);
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

    public function actionFilter()
    {
        $this->controllerContext->actionFilter();
    }
}

