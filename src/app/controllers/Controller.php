<?php

namespace App\Controller;

use App\Model\Model;
use App\View\View;

abstract class Controller
{
    public $model;
    public $view;

    function __construct()
    {
        $this->model = new Model();
        $defaultData = $this->model->getDefaultData();
        $this->view = new View($defaultData);
    }
}
