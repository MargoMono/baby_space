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

    public function actionIndex($id = null)
    {
        $data = $this->model->getIndexData($id);
        $this->controllerContext->render($data, 'index.twig');
    }

    public function actionGetFilteredProductList()
    {
        $data = $this->model->getFilteredProductList($_POST);
        $this->controllerContext->render($data, 'more.twig');
    }

    public function actionShowMore()
    {
        $data = $this->model->getShowMoreData($_POST);
        $this->controllerContext->render($data, 'more.twig');
    }

    public function actionLastPage()
    {
        $data = $this->model->checkLastPage($_POST);
        $this->controllerContext->generateAjax($data);
    }
}
