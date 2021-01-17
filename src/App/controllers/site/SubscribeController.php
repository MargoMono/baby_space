<?php

namespace App\Controllers\Site;

use App\Controllers\Controller;
use App\Exceptions\AdminException;
use App\Exceptions\SiteException;
use App\Helpers\MailerHelper;

class CommentController
{
    private $directory = 'subscribe';
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
            $this->model->createSubscribe($_FILES, $_POST);
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
