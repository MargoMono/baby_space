<?php

namespace App\Controller\Site;

use App\Components\Controller;
use App\Model\Site\BlogModel;

class BlogController extends Controller
{
    function __construct()
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