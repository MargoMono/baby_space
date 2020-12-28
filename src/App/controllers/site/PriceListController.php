<?php

namespace App\Controllers\Site;

use App\Controllers\Controller;
use App\Models\Site\PriceListModel;

class PriceListController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new PriceListModel();
    }

    public function sendPriceListToClient()
    {
        $data = $this->model->sendPriceListToClient($_POST);
        $this->view->generateAjax($data);
        return;
    }
}
