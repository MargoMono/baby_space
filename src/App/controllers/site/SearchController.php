<?php

namespace App\Controllers\Site;

use App\Models\Site\SearchModel;

class SearchController
{
    private $directory = 'search';
    private $controllerContext;
    private $model;

    public function __construct()
    {
        $this->controllerContext = new ControllerContext($this->directory);
        $this->model = new SearchModel();
    }

    public function actionIndex()
    {
        $this->controllerContext->render( [], 'index.twig');
    }

    public function actionSearch()
    {
        $data = $this->model->getSearchData($_POST);
        $this->controllerContext->render($data, 'search.twig');
    }
}
