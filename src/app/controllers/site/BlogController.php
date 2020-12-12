<?php

namespace App\Controllers\Site;

use App\Models\Site\BlogModel;

class BlogController
{
    private $directory = 'blog';
    private $controllerContext;
    private $model;

    public function __construct()
    {
        $this->controllerContext = new ControllerContext($this->directory);
        $this->model = new BlogModel();
    }

    public function actionIndex()
    {
        $data = $this->model->getIndexData();
        $this->controllerContext->render($data, 'index.twig');
    }

    public function actionShowSingle($alias, $id)
    {
        $data = $this->model->getShowOneData($id);
        $this->controllerContext->render($data, 'single.twig');
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