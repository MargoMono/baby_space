<?php

namespace App\Controllers\Site;

use App\Controllers\Controller;
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

    public function index()
    {
        $data = $this->model->getIndexData();
        $this->controllerContext->render($data, 'index.twig');
    }

    public function showOne($alias, $id)
    {
        $data = $this->model->getShowOneData($id);
        $data['page'] = 'company';
        $this->view->generate('site/blog/showOne.twig', $data);
    }

    public function showMore($count)
    {
        $data = $this->model->getShowMoreData($count);
        $this->view->generate('site/blog/showMore.twig', $data);
    }
}