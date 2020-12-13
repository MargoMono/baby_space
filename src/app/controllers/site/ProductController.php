<?php

namespace App\Controllers\Site;

use App\Models\Site\ProductModel;

class ProductController
{
    private $directory = 'product';
    private $controllerContext;
    private $model;

    public function __construct()
    {
        $this->controllerContext = new ControllerContext($this->directory);
        $this->model = new ProductModel();
    }

    public function actionIndex($alias, $id)
    {
        $data = $this->model->getIndexData($id);
        $this->controllerContext->render($data, 'index.twig');
    }
}
