<?php

namespace App\Controllers;

use App\Models\Model;
use App\View\View;

abstract class Controller
{
    public $model;
    public $view;
    public $context;

    public $strategy;

    function __construct()
    {
        $this->model = new Model();
        $defaultData = $this->model->getDefaultData();
        $this->view = new View($defaultData);
    }
}
