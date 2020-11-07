<?php

namespace App\Controller\Site;

use App\Components\Controller;
use App\Model\Site\DeliveryModel;

class DeliveryController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->model = new DeliveryModel();
    }

    public function showDeliveryPage()
    {
        $data = $this->model->getDeliveryPageData();
        $data['page'] = 'company';
        $this->view->generate('/site/delivery.twig', $data);
    }
}
