<?php

namespace App\Controllers\Site;

use App\Models\Site\PageModel;

class PageController
{
    private $directory = 'page';
    private $controllerContext;
    private $model;

    function __construct()
    {
        $this->controllerContext = new ControllerContext($this->directory);
        $this->model = new PageModel();
    }

    public function actionShowCompanyPage()
    {
        $data = $this->model->getCompanyData();
        $this->controllerContext->render($data, 'company.twig');
    }

    public function actionShowDeliveryPage()
    {
        $data = $this->model->getDeliveryData();
        $this->controllerContext->render($data, 'delivery.twig');
    }
}
