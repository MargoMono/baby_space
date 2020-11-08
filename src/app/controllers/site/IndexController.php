<?php

namespace App\Controller\Site;

use App\Controller\Controller;
use App\Model\Site\IndexModel;

class IndexController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->model = new IndexModel();
    }

    public function showMainPage()
    {
        $data = $this->model->getMainPageData();
        $data['page'] = 'main';
        $this->view->generate('/site/index.twig', $data);
    }
}
