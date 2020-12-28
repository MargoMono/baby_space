<?php

namespace App\Controllers\Site;

use App\Controllers\Controller;
use App\Models\Site\SearchModel;

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
