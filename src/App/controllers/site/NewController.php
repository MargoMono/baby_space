<?php

namespace App\Controllers\Site;

use App\Models\Site\NewModel;

class NewController
{
    private $directory = 'new';
    private $controllerContext;
    private $model;

    public function __construct()
    {
        $this->controllerContext = new ControllerContext($this->directory);
        $this->model = new NewModel();
    }

    public function actionIndex()
    {
        $data = $this->model->getIndexData();
        $this->controllerContext->render($data, 'index.twig');
    }

    public function actionShowMore($offset)
    {
        $data = $this->model->getShowMoreData($offset);
        $this->controllerContext->render($data, 'more.twig');
    }

    public function actionLastPage($count)
    {
        $data = $this->model->checkLastPage($count);
        $this->controllerContext->generateAjax($data);
    }
}