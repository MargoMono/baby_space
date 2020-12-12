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
        $this->controllerContext->render([], 'index.twig');
    }

    public function actionShowCategory($alias, $id)
    {
        $data = $this->model->getCategoryData($id);
        $this->controllerContext->render($data, 'category.twig');
    }

    public function actionShowMore($categoryId, $count)
    {
        $data = $this->model->getShowMoreData($categoryId, $count);
        $this->controllerContext->render($data, 'more.twig');
    }

    public function actionLastPage($categoryId, $count)
    {
        $data = $this->model->checkLastPage($categoryId, $count);
        $this->controllerContext->generateAjax($data);
    }
}
