<?php

namespace App\Controller\Site;

use App\Controller\Controller;
use App\Model\Site\NewModel;

class NewController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->model = new NewModel();
    }

    public function index()
    {
        $data = $this->model->getIndexData();
        $data['page'] = 'company';
        $this->view->generate('site/new/index.twig', $data);
    }

    public function showOne($alias, $id)
    {
        $data = $this->model->getShowOneData($id);
        $data['page'] = 'company';
        $this->view->generate('site/new/showOne.twig', $data);
    }

    public function showMore($count)
    {
        $data = $this->model->getShowMoreData($count);
        $this->view->generate('site/new/showMore.twig', $data);
    }
}