<?php

namespace App\Controllers\Site;

use App\Controllers\Controller;
use App\Models\Site\BlogModel;

class BlogController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new BlogModel();
    }

    public function index()
    {
        $data = $this->model->getIndexData();
        $data['page'] = 'company';
        $this->view->generate('site/blog/index.twig', $data);
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