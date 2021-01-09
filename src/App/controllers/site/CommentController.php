<?php

namespace App\Controllers\Site;

use App\Controllers\Controller;
use App\Exceptions\AdminException;
use App\Exceptions\SiteException;
use App\Helpers\MailerHelper;
use App\Models\Site\BlogModel;
use App\Models\Site\CommentModel;

class CommentController
{
    private $directory = 'comment';
    private $controllerContext;
    private $model;

    public function __construct()
    {
        $this->controllerContext = new ControllerContext($this->directory);
        $this->model = new CommentModel();
    }

    public function index()
    {
        $data = $this->model->getIndexData();
        $this->controllerContext->render($data, 'index.twig');
    }

    public function actionShowMore()
    {
        $data = $this->model->getShowMoreData($_POST);
        $this->controllerContext->render($data, 'more.twig');
    }

    public function actionLastPage()
    {
        $data = $this->model->checkLastPage($_POST);
        $this->controllerContext->generateAjax($data);
    }

    public function createComment()
    {
        try {
            $this->model->createComment($_FILES, $_POST);
        } catch (SiteException | AdminException $e) {
            $this->controllerContext->generateAjax([
                'status' => false,
                'error' => $e->getMessage()
            ]);
        }

        $this->controllerContext->generateAjax([
            'status' => true
        ]);

//        $emailParams = [
//            'user_name' => $_POST['user_name'],
//            'base_url' => $_SERVER['HTTP_HOST'],
//        ];
//
//        $mailModel = new MailController();
//        $body = $mailModel->getTemplate('newComment.twig', $emailParams);
//        $subject = 'Новый отзыв сайте baby-space.store';
//
//        $mailer = new MailerHelper($subject, $body, 'margomonogarova@gmail.ru', 'Baby\'s Space');
//        $mailer->send();
    }
}
