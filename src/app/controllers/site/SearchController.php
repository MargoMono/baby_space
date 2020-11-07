<?php

namespace App\Controller\Site;

use App\Components\Controller;
use App\Model\Site\SearchModel;

class SearchController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->model = new SearchModel();
    }

    public function actionIndex()
    {
        $data = $this->model->getIndexData($_POST);
        $this->view->generate('/site/search.twig', $data);
    }
}
