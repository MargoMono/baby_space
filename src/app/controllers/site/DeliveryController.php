<?php

namespace App\Controllers\Site;

use App\Controllers\Controller;
use App\Models\Site\DeliveryModel;

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
