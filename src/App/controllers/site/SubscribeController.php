<?php
// var_dump (1); die();

namespace App\Controllers\Site;

use App\Controllers\Controller;
use App\Exceptions\AdminException;
use App\Exceptions\SiteException;
use App\Helpers\MailerHelper;

class SubscribeController
{
    private $directory = 'includes';
    private $controllerContext;
    private $model;

    public function __construct()
    {
        $this->controllerContext = new ControllerContext($this->directory);
        $this->model = new SubscribeModel();
    }

    public function createSubscribe()
    {
        try {
            $this->model->createSubscribe($_POST);
        } catch (SiteException | AdminException $e) {
            $this->controllerContext->generateAjax([
                'status' => false,
                'error' => $e->getMessage()
            ]);
        }

        $this->controllerContext->generateAjax([
            'status' => true
        ]);
    }
}
