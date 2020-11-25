<?php

namespace App\Controllers\Admin;

use App\Middleware\AdminAuthenticationChecking;
use App\Models\Admin\ModelContext;
use App\Models\Admin\ProductModel;
use App\Repository\ProductRepository;

class ProductController implements ControllerStrategy
{
    private $controllerContext;

    private $directory = 'product';

    public function __construct()
    {
        $this->controllerContext = new ControllerContext(new ProductModel(),
            new ModelContext(new ProductModel()), $this->directory);

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

    public function imageDelete($id, $imageId)
    {
        $this->controllerContext->imageDelete($id, $imageId);
    }
}

