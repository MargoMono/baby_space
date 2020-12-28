<?php

namespace App\Controllers;

use App\Components\Logger;
use App\Models\Model;
use App\View\View;

abstract class Controller
{
    public $model;
    public $view;
    public $context;

    public $strategy;
    protected $logger;

    function __construct()
    {
        $this->model = new Model();
        $defaultData = $this->model->getDefaultData();
        $this->view = new View($defaultData);
        $this->logger = Logger::getLogger(static::class);
    }
}
