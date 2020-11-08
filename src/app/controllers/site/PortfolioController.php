<?php

namespace App\Controller\Site;

use App\Controller\Controller;
use App\Model\Site\PortfolioModel;

class PortfolioController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->model = new PortfolioModel();
    }

    public function showPortfolioPage()
    {
        $data = $this->model->getIndexData();
        $data['page'] = 'company';
        $this->view->generate('/site/portfolio/index.twig', $data);
    }

    public function showMore($count)
    {
        $data = $this->model->getShowMoreData($count);

        $this->view->generate('site/portfolio/showMore.twig', $data);
    }
}
