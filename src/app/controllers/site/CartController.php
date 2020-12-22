<?php

namespace App\Controllers\Site;

use App\Models\Site\CartModel;

class CartController
{
    private $directory = 'cart';
    private $controllerContext;
    private $model;

    public function __construct()
    {
        $this->controllerContext = new ControllerContext($this->directory);
        $this->model = new CartModel();
    }

    public function actionIndex()
    {
        $data = $this->model->getIndexData();
        $this->controllerContext->render($data, 'index.twig');
    }
}