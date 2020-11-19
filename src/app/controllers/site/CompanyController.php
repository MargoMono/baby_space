<?php

namespace App\Controllers\Site;

use App\Controllers\Controller;
use App\Models\Site\CompanyModel;

class CompanyController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->model = new CompanyModel();
    }

    public function showCompanyPage()
    {
        $data = $this->model->getCompanyPageData();
        $data['page'] = 'company';
        $this->view->generate('/site/company.twig', $data);
    }
}
