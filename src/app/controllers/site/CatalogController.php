<?php

namespace App\Controllers\Site;

use App\Models\Site\CatalogModel;

class CatalogController
{
    private $directory = 'catalog';
    private $controllerContext;
    private $model;

    public function __construct()
    {
        $this->controllerContext = new ControllerContext($this->directory);
        $this->model = new CatalogModel();
    }

    public function actionIndex()
    {
        $data = $this->model->getIndexData();
        $this->controllerContext->render($data, 'index.twig');
    }

    public function actionShowMore($count)
    {
        $data = $this->model->getShowMoreData($count);
        $this->controllerContext->render($data, 'more.twig');
    }

    public function actionLastPage($count)
    {
        $data = $this->model->checkLastPage($count);
        $this->controllerContext->generateAjax($data);
    }
}
